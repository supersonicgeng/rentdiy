<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mpdf\Mpdf;

class TestController extends Controller
{
    public function test()
    {
        $ip = "{$_SERVER['SERVER_NAME']}";
        $dashboard_pdf_file = $ip."/Public/pdf/1.pdf";
        $pdf = new Mpdf();
        $pagecount = $pdf->SetDocTemplate($dashboard_pdf_file);
        for ($i=1; $i<=$pagecount; $i++) {
            $import_page = $pdf->ImportPage();
            $pdf->UseTemplate($import_page);
            if ($i < $pagecount)
                $pdf->AddPage();
        }
        $pdf->Output();

    }
}
