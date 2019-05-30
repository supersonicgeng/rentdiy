<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mpdf\Mpdf;
use setasign\Fpdi\PdfParser\StreamReader;

class TestController extends Controller
{
    public function test()
    {
        $ip = "{$_SERVER['SERVER_NAME']}";
        $dashboard_pdf_file = "http://".$ip."/pdf/4.pdf";
        $fileContent = file_get_contents($dashboard_pdf_file,'rb');
        $mpdf = new Mpdf();
        $pagecount = $mpdf->setSourceFile(StreamReader::createByString($fileContent));
        for($i=1; $i<=$pagecount;$i++){
            $import_page = $mpdf->importPage($i);
            $mpdf->useTemplate($import_page);
            if($i == 1){
                $mpdf->WriteText('30',45,'leo');
            }
            if($i < $pagecount){
                $mpdf->AddPage();
            }
        }
        $mpdf->Output();
    }
}
