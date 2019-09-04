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

    public function testLogin(Request $request)
    {
        $client = new \Google_Client(['client_id' => '288789996790-8j11as1hninv897nor26l6aulu6v1chr.apps.googleusercontent.com']);
        $token = $request->token;
        $payload = $client->verifyIdToken($token);
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
