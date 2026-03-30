<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\BadRequestException;
use Psr\Http\Message\UploadedFileInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;


/**
 * Anexos Controller
 *
 * @property \App\Model\Table\AnexosTable $Anexos
 */
class AnexosController extends AppController
{

    public function index()
    {
        $this->request->allowMethod(['get']);

        $dadosAnexo = $this->request->getQuery();

        $entidade   = $dadosAnexo['entidade'];
        $entidadeId = $dadosAnexo['entidade_id'];

        $anexos = $this->Anexos->find()
            ->where([
                'entidade' => $entidade,
                'entidade_id' => $entidadeId,
                'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ])
            ->orderBy(['created' => 'DESC'])
            ->all();

        $this->viewBuilder()->setOption('serialize', ['anexos']);
        $this->set(compact('anexos'));
    }


    public function upload()
    {
        $this->request->allowMethod(['post']);

        $entidade     = $this->request->getData('entidade');
        $entidadeId   = $this->request->getData('entidade_id');
        $arquivos     = $this->request->getUploadedFiles()['arquivos'] ?? [];

        if (!$entidade || !$entidadeId || empty($arquivos)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Dados inválidos'
                ]));
        }

        $basePath = ROOT . DS . 'storage' . DS . 'anexos'
            . DS . strtolower($entidade)
            . DS . $entidadeId;

        $this->criarDiretorio($basePath);

        foreach ($arquivos as $arquivo) {

            if ($arquivo->getError() !== UPLOAD_ERR_OK) {
                continue;
            }

            $nomeOriginal = $arquivo->getClientFilename();
            $nomeSeguro   = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $nomeOriginal);
            $pathFinal = $basePath . DS . $nomeSeguro;

            $arquivo->moveTo($pathFinal);

            $anexo = $this->Anexos->newEntity([
                'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                'entidade'       => $entidade,
                'entidade_id'    => $entidadeId,
                'nome_arquivo'   => $nomeOriginal,
                'caminho_arquivo'=> $pathFinal,
                'tipo_mime'      => $arquivo->getClientMediaType(),
                'tamanho'        => $arquivo->getSize()
            ]);

            $this->Anexos->save($anexo);
        }

        $anexos = $this->Anexos->find()
                            ->where([
                                'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                                'entidade' => 'Procedimentos',
                                'entidade_id' => $entidadeId
                            ])->orderBy(['created' => 'DESC'])->all()->toArray();


        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'message' => 'Arquivo enviado com sucesso.',
                'anexos' => $anexos
            ]));
    }


    public function download($id)
    {
        $this->request->allowMethod(['get']);

        $anexo = $this->Anexos->find()
            ->where([
                'id' => $id,
                'entidade' => 'Procedimentos',
                'estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')
            ])
            ->firstOrFail();

        $path = $anexo->caminho_arquivo;

        if (!file_exists($path)) {
            throw new NotFoundException();
        }

        return $this->response->withFile($path, [
            'download' => true,
            'name' => $anexo->nome_arquivo
        ]);
    }




    public function delete($id)
    {
        $this->request->allowMethod(['delete', 'post']);

        $anexo = $this->Anexos->find()
            ->where([
                'id' => $id,
                'entidade' => 'Procedimentos',
                'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
            ])
            ->first();

        if (!$anexo) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Anexo não encontrado.'
                ]));
        }

        $path = $anexo->caminho_arquivo;

        // remove arquivo físico
        if (file_exists($path)) {
            unlink($path);
        }

        // remove registro
        $this->Anexos->delete($anexo);

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'message' => 'Anexo removido com sucesso.'
            ]));
    }

    private function criarDiretorio(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

}
