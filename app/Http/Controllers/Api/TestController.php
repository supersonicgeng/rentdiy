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

            dd($payload);

            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            // Invalid ID token
            return 123;
        }
    }

    public function testFacebookLogin(Request $request){
        $fb = new \Facebook\Facebook([
            'app_id' => '2336245916486482',
            'app_secret' => 'ecc22a01d55efc66d860159b60963b1a',
            'default_graph_version' => 'v3.2',
            //'default_access_token' => '{access-token}', // optional
        ]);
        $token = $request->token;
// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', $token);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        dd($response);
    }
}
