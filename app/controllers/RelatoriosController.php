<?php

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 21/06/2017
 * Time: 09:11
 */

require_once __DIR__ . '/../vendor/mpdf60/mpdf.php';
use Phalcon\Mvc\View;

class RelatoriosController extends ControllerBase
{
    public function indexAction(){

    }

    public function generateReportAction(){
        ob_start();
        $this->view->disable();

        $dados = $this->request->getPost();

        $startDate = new DateTime(str_replace('/', '-', $dados['startDate']));
        $endDate = new DateTime(str_replace('/', '-', $dados['endDate']));

        $template = "relatorio";
        $params = array(
            'title' => "Relatório",
            'params' => array(
                array(
                    'name' => "Período",
                    'value' => $dados['startDate'] . ' até ' . $dados['endDate']
                )
            ),
            'data' => array()
        );


        $html = $this->view->getRender('relatorioTemplates', $template, $params, function($view){
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        //http://mpdf1.com/manual/index.php?tid=184
        $mpdf = new mPDF(
            'utf-8', //mode
            'A4-L',  //format
            '',      //font-size
            '',      //font
            15,      //margin-left
            15,      //margin-right
            30,      //margin-top
            20,      //margin-bottom
            9,       //margin-header
            9,       //margin-footer
            'L'      //orientation
        );

        $mpdf->defaultheaderfontsize=11;
        $mpdf->defaultheaderfontstyle='N';
        $mpdf->defaultheaderline=1;
        $mpdf->defaultfooterfontsize=10;
        $mpdf->defaultfooterfontstyle='N';
        $mpdf->defaultfooterline=1;

        date_default_timezone_set('America/Sao_Paulo');

        $mpdf->SetHeader('SmartHome');
        $mpdf->SetFooter(date('d/m/Y H:i:s') . '||' . 'página ' .  ' {PAGENO} ' . 'de'. ' {nbpg}');

        $mpdf->WriteHTML($html);

        $mpdf->Output();
    }
}