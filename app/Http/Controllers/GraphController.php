<?php

namespace App\Http\Controllers;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GraphController extends Controller
{
    //

    private $api;
    public function __construct(Facebook $fb)
    {
        $this->middleware(function ($request, $next) use ($fb) {
            $fb->setDefaultAccessToken(Auth::user()->token);
            $this->api = $fb;
            return $next($request);
        });
    }

    public function retrieveUserProfile(Facebook $fb){
        /*try {

            $params = "first_name,last_name,email";

            //$user = $this->api->get('/me?fields='.$params)->getGraphUser();
            $user = $this->api->get('/me?fields='.$params)->getGraphUser();

            dd($user);

        } catch (FacebookSDKException $e) {

        }*/



        try {
            // Returns a `FacebookFacebookResponse` object
            $response = $fb->get(
                '/me/accounts',
                Auth::user()->token
            );
        } catch(FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookExceptionsFacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }



        //dd($response) ;
        //$graphNode = $response->getGraphNode();
        $graphNode = $response->getGraphEdge();

        //return $graphNode[0]['access_token'];
        return $graphNode;
        //echo $graphNode->items;
        dd($graphNode) ;

    }
}
