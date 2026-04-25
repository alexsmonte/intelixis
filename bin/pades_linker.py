#!/usr/bin/env python3
"""
pades_linker.py — Digital signature preparation and injection (PAdES/ICP-Brasil).
NATIVE PYHANKO WITH IDENTITY EXTRACTION AND FOOTER LOGO.
"""

import sys
import json
import base64
import asyncio
import os
import hashlib
import datetime
import re

from pyhanko.pdf_utils.incremental_writer import IncrementalPdfFileWriter
from pyhanko.sign.signers import PdfSignatureMetadata, PdfSigner, Signer
from pyhanko.sign import fields
from pyhanko.stamp import TextStampStyle
from pyhanko.pdf_utils.text import TextBoxStyle
from pyhanko.pdf_utils.images import PdfImage
from pyhanko.pdf_utils.layout import SimpleBoxLayoutRule, Margins, AxisAlignment, InnerScaling
from asn1crypto import cms, algos, x509, core, pem as asn1pem, tsp


# ─── IDENTITY HELPER ────────────────────────────────────────────────────────
def extract_icp_brasil_info(cert_obj):
    """
    Extrai Nome e CPF/CNPJ de um certificado ICP-Brasil via OIDs específicos no SAN.
    """
    name = str(cert_obj.subject.native.get('common_name', 'Signatário'))
    id_info = ""
    
    try:
        # Procurar extensão SubjectAltName (SAN)
        san = cert_obj.subject_alt_name_value
        if san:
            # SAN OtherNames costumam conter os OIDs da ICP-Brasil
            for entry in san:
                if entry.name == 'other_name':
                    oid = entry.value['type_id'].dotted
                    val = entry.value['value'].native
                    
                    # OID 2.16.76.1.3.1 = CPF do Titular
                    if oid == '2.16.76.1.3.1':
                        # Formato ICP-Brasil: CPF é uma string que pode estar prefixada/posfixada
                        # Geralmente os primeiros 11 dígitos
                        match = re.search(r'\d{11}', str(val))
                        if match: id_info = f"CPF: {match.group(0)}"
                    
                    # OID 2.16.76.1.3.3 = CNPJ da Empresa
                    elif oid == '2.16.76.1.3.3':
                        match = re.search(r'\d{14}', str(val))
                        if match: id_info = f"CNPJ: {match.group(0)}"
                        
    except Exception as e:
        pass # Ignora erros de extração e usa o que tiver
        
    return name, id_info


# ─── MockSigner ──────────────────────────────────────────────────────────────
class MockSigner(Signer):
    """Signer used for placeholder calculation."""
    @property
    def signing_cert(self): return None
    @property
    def cert_registry(self): return None
    def get_signature_mechanism_for_digest(self, md_algo):
        return algos.SignedDigestAlgorithm({'algorithm': 'sha256WithRSAEncryption'})
    async def async_sign_raw(self, data, digest_algorithm, dry_run=False):
        return b'\x00' * 256


# ─── PREPARE ─────────────────────────────────────────────────────────────────
async def prepare_pdf(input_path: str, output_path: str, signer_name: str = ''):
    """
    Prepara o PDF com campo no rodapé e estilo sem borda.
    Neste estágio ainda não temos o certificado real, então usamos o nome do banco.
    """
    try:
        with open(input_path, 'rb+') as f:
            w = IncrementalPdfFileWriter(f)

            # Imagem do Selo ICP-Brasil Qualificada
            base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
            img_path = os.path.join(base_dir, 'storage', 'imagens', 'selo-qualificada-icp.png')

            bg_img = None
            if os.path.exists(img_path):
                try:
                    from PIL import Image
                    logo = Image.open(img_path).convert('RGBA')

                    # box=(40, 5, 300, 35) → altura útil ≈ 30pt, largura ≈ 260pt
                    # Mantemos a proporção real da imagem para não distorcer
                    orig_w, orig_h = logo.size
                    target_h = 100
                    target_w = int(target_h * (260 / 30.0))  # canvas na proporção da box

                    # Redimensiona o logo mantendo aspecto
                    logo_h = target_h
                    logo_w = int(logo_h * orig_w / orig_h)
                    logo = logo.resize((logo_w, logo_h), Image.Resampling.LANCZOS)

                    canvas = Image.new('RGBA', (target_w, target_h), (255, 255, 255, 0))
                    canvas.paste(logo, (0, 0), logo)

                    temp_img_path = '/tmp/pades_seal_padded.png'
                    canvas.save(temp_img_path)

                    bg_img = PdfImage(temp_img_path)
                except Exception:
                    pass  # Fallback sem imagem

            # Estilo: Sem borda, com logo posicionada à esquerda via Canvas Transparente
            style = TextStampStyle(
                stamp_text="ASSINATURA DIGITAL ICP-Brasil\n%(signer)s\n%(ts)s\nValide em: https://validar.iti.gov.br",
                border_width=0,
                background=bg_img,
                background_opacity=1.0,
                text_box_style=TextBoxStyle(font_size=6)
            )

            meta = PdfSignatureMetadata(
                field_name='SignatureProfessional',
                md_algorithm='sha256',
                name=signer_name or 'Profissional',
                reason='Assinatura Digital ICP-Brasil',
                location='Brasil'
            )

            signer = PdfSigner(meta, signer=MockSigner())

            with open(output_path, 'wb') as outf:
                prep_digest, _, _ = await signer.async_digest_doc_for_signing(
                    w, bytes_reserved=32768, output=outf
                )

            print(json.dumps({
                'ok': True, 
                'hash': prep_digest.document_digest.hex()
            }))

    except Exception as e:
        import traceback
        print(json.dumps({'ok': False, 'error': str(e), 'trace': traceback.format_exc()}))


# ─── INJECT ──────────────────────────────────────────────────────────────────
def inject_cms(prepared_path: str, final_path: str, raw_sig_b64: str, cert_pem_b64: str):
    """
    Extrai info do certificado real e reconstrói o CMS.
    """
    try:
        with open(prepared_path, 'rb') as f:
            pdf_data = bytearray(f.read())

        raw_sig_bytes  = base64.b64decode(raw_sig_b64)
        cert_pem_bytes = base64.b64decode(cert_pem_b64)
        if b'-----BEGIN' in cert_pem_bytes:
            _, _, cert_der = asn1pem.unarmor(cert_pem_bytes)
        else:
            cert_der = cert_pem_bytes
        cert_obj = x509.Certificate.load(cert_der)

        # 1. EXTRAIR IDENTIDADE REAL DO CERTIFICADO
        real_name, real_id = extract_icp_brasil_info(cert_obj)
        
        # 2. TIMESTAMP
        tsr_der = None
        try:
            from urllib.request import urlopen, Request as RawRequest
            digest = hashlib.sha256(raw_sig_bytes).digest()
            tsq = tsp.TimeStampReq({
                'version': 1,
                'message_imprint': tsp.MessageImprint({
                    'hash_algorithm': algos.DigestAlgorithm({'algorithm': 'sha256'}),
                    'hashed_message': digest,
                }),
                'cert_req': True,
                'nonce': core.Integer(int.from_bytes(os.urandom(8), 'big')),
            })
            req = RawRequest('http://freetsa.org/tsr', data=tsq.dump(), headers={'Content-Type': 'application/timestamp-query'})
            tsr_der = urlopen(req, timeout=5).read()
        except: pass

        unsigned_attrs = None
        if tsr_der and len(tsr_der) > 20:
            tsr_obj = tsp.TimeStampResp.load(tsr_der)
            unsigned_attrs = cms.CMSAttributes([
                cms.CMSAttribute({
                    'type': cms.CMSAttributeType('1.2.840.113549.1.9.16.2.14'),
                    'values': cms.SetOfContentInfo([tsr_obj['time_stamp_token']]),
                })
            ])

        signer_info = cms.SignerInfo({
            'version': 'v1',
            'sid': cms.SignerIdentifier({
                'issuer_and_serial_number': cms.IssuerAndSerialNumber({
                    'issuer': cert_obj['tbs_certificate']['issuer'],
                    'serial_number': cert_obj['tbs_certificate']['serial_number'],
                })
            }),
            'digest_algorithm': algos.DigestAlgorithm({'algorithm': 'sha256'}),
            'signature_algorithm': algos.SignedDigestAlgorithm({'algorithm': 'sha256_rsa'}),
            'signature': raw_sig_bytes,
            'unsigned_attrs': unsigned_attrs
        })

        signed_data = cms.SignedData({
            'version': 'v1',
            'digest_algorithms': cms.DigestAlgorithms([{'algorithm': 'sha256'}]),
            'encap_content_info': {'content_type': 'data'},
            'certificates': cms.CertificateSet([cms.CertificateChoices(name='certificate', value=cert_obj)]),
            'signer_infos': cms.SignerInfos([signer_info]),
        })

        cms_der = cms.ContentInfo({'content_type': 'signed_data', 'content': signed_data}).dump()
        cms_hex = cms_der.hex().upper().encode('ascii')

        # 3. LOCALIZAR O PLACEHOLDER
        matches = list(re.finditer(b'/Contents <([0-9A-F]{100,})>', pdf_data))
        if not matches:
            raise Exception("Nao foi possivel localizar o placeholder /Contents no PDF preparado.")
        
        last_match = matches[-1]
        hex_start = last_match.start(1)
        hex_end   = last_match.end(1)
        avail     = hex_end - hex_start

        if len(cms_hex) > avail:
            raise Exception(f"CMS ({len(cms_hex)}) maior que o espaco ({avail}).")

        padded = cms_hex + b'0' * (avail - len(cms_hex))
        pdf_data[hex_start:hex_end] = padded

        # 4. SALVAR E LOGAR INFO (O controller pode ler isso se quiser)
        with open(final_path, 'wb') as out:
            out.write(pdf_data)

        # Retornamos o nome real para o Controller salvar no banco se quiser
        print(json.dumps({
            'ok': True, 
            'signer_name': real_name, 
            'signer_id': real_id,
            'timestamped': bool(tsr_der)
        }))

    except Exception as e:
        import traceback
        print(json.dumps({'ok': False, 'error': str(e), 'trace': traceback.format_exc()}))


if __name__ == '__main__':
    cmd = sys.argv[1]
    if cmd == 'prepare':
        asyncio.run(prepare_pdf(sys.argv[2], sys.argv[3], sys.argv[4] if len(sys.argv)>4 else ''))
    elif cmd == 'inject':
        inject_cms(sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
