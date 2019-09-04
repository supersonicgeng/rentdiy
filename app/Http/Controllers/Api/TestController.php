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
        return service('Help')->test();
    }

    public function testLogin()
    {
        $client = new \Google_Client(['client_id' => '288789996790-8j11as1hninv897nor26l6aulu6v1chr.apps.googleusercontent.com']);

        $payload = $client->verifyIdToken("eyJhbGciOiJSUzI1NiIsImtpZCI6IjA1YTAyNjQ5YTViNDVjOTBmZGZlNGRhMWViZWZhOWMwNzlhYjU5M2UiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiMjg4Nzg5OTk2NzkwLThqMTFhczFobmludjg5N25vcjI2bDZhdWx1NnYxY2hyLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMjg4Nzg5OTk2NzkwLThqMTFhczFobmludjg5N25vcjI2bDZhdWx1NnYxY2hyLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTA5NzA5MzkyMDA3MzE0ODcwMzA3IiwiZW1haWwiOiJwZW5namluY21AZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiJmZHlYZ09Tb0FlZnhOV3F1RUVGajRnIiwibmFtZSI6IuW9remUpiIsInBpY3R1cmUiOiJodHRwczovL2xoNi5nb29nbGV1c2VyY29udGVudC5jb20vLUxEaU5UWk1MbmNvL0FBQUFBQUFBQUFJL0FBQUFBQUFBQUFBL0FDSGkzcmZHVGwyd2Rsc0xieTFUTWlHeGdJNGxFeHpwTEEvczk2LWMvcGhvdG8uanBnIiwiZ2l2ZW5fbmFtZSI6IumUpiIsImZhbWlseV9uYW1lIjoi5b2tIiwibG9jYWxlIjoiemgtQ04iLCJpYXQiOjE1Njc1NzgwMzMsImV4cCI6MTU2NzU4MTYzMywianRpIjoiZmM0NDc2YWExZThiYTRhMTYwNGM4MGEwNmIzZTM5YjA5N2U3ZTY0YSJ9.X0PkLpxSOXSfR3Q7H0996PkpWw03wCOlAQ-JeRz1tkxIEoeIg2nWnWbwZSd0baoWYRkg2OFV_92D-UhkDCTcNfgR-daFjT7xsOJuLBw1Ls1ZMM5AWqFKWwqrAVjyYWTXDAMJlZH_nUPozA1_FzR5B6jCO53Hoda6-9FFMTvFY8gxtlPlCaZmytZQeasqAacokGQ58W-GGT4IH1FzG1oBC3WZgIYNzDaXK4OaGPnUYVc1a8bE4zWamxNxIRHVBi3QwJkkF1NXFT3rZllvIRyRcg5_5YF--8PJwLEwNnpRWaAZGMFqSMcPRoTMAf0NMYjJhFqb79bn6jUD_tfZYxJaGw");
        if ($payload) {

            return json_encode($payload);

            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            // Invalid ID token
            return 123;
        }
    }
}
