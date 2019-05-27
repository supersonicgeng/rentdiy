<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;

class test extends Controller
{
    public function test()
    {
        $PHPWord = new PhpWord();
        $tempPlete = $PHPWord->loadTemplate('./Public/doc/test.docx');
        $tempPlete->setValue('input',233);
        $tempPlete->save('./Public/doc/upload.docx'); // 文件通过浏览器下载
        $ip = "{$_SERVER['SERVER_NAME']}";
        $dk = "{$_SERVER['SERVER_PORT']}";
        echo  json_encode(array("src"=>$ip.":".$dk."/Public/doc/upload.docx"));
    }
}
