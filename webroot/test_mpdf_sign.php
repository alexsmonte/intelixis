<?php
require '/var/www/html/painel/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('<h1>Hello World</h1>');

// Create a dummy private key and cert
$dn = array("countryName" => "BR", "stateOrProvinceName" => "SP", "localityName" => "SP", "organizationName" => "Intelixis", "organizationalUnitName" => "IT", "commonName" => "Dummy", "emailAddress" => "dummy@example.com");
$privkey = openssl_pkey_new(array("private_key_bits" => 2048, "private_key_type" => OPENSSL_KEYTYPE_RSA));
$csr = openssl_csr_new($dn, $privkey, array('digest_alg' => 'sha256'));
$x509 = openssl_csr_sign($csr, null, $privkey, 365, array('digest_alg' => 'sha256'));
openssl_x509_export($x509, $certout);
openssl_pkey_export($privkey, $pkeyout);

file_put_contents('/tmp/dummy.crt', $certout);
file_put_contents('/tmp/dummy.key', $pkeyout);

$mpdf->SetSignature('file:///tmp/dummy.crt', 'file:///tmp/dummy.key', '', '', 2, '');
$mpdf->Output('/tmp/signed_mpdf.pdf', 'F');

$content = file_get_contents('/tmp/signed_mpdf.pdf');
if (preg_match('/\/ByteRange\s*\[(\d+) (\d+) (\d+) (\d+)\]/', $content, $m)) {
    echo "ByteRange Found: " . print_r($m, true);
} else {
    echo "No ByteRange found";
}
