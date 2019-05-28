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
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML('Hello World');
        $mpdf->Output();
    }
}
