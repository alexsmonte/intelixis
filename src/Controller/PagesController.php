<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/5/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    public function index()
    {

        if ($this->request->getAttribute('identity')->tipo == 'master')
        {
            $assinaturas        =   $this->fetchTable('Assinaturas');
            $queryAssinaturas   =   $assinaturas->find('verificar', $this->request->getAttribute('identity')->conta_id)->toArray();


            $existeAssinatura       =   count($queryAssinaturas)>0?true:false;
            $existeEstabelecimento  =   false;

            if(!empty($queryAssinaturas[0]->estabelecimentos))
                $existeEstabelecimento   =   count($queryAssinaturas[0]->estabelecimentos)>0?true:false;

            if($existeAssinatura == false){
                return $this->redirect('/planos/escolher-plano', 301);
            }elseif($existeEstabelecimento == false){
                return $this->redirect('/Estabelecimentos/configuracoes', 301);
            }

        }

    }
}
