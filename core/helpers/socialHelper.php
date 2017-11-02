<?php
defined('_ACCESS') or die;
use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\Authentication\OAuth2Client;

class socialHelper
{
    private static $instance;

    public static function instance()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    public function facebooklink()
    {
        $fb = new Facebook([
            'app_id' => '1790911861239584',
            'app_secret' => '135acb2ab490d800ed1ac5e61adc9ae3',
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        return $helper->getLoginUrl('http://'. $_SERVER['HTTP_HOST'] .'/account/signinFacebook/', $permissions);

    }

    public function googlelink()
    {
        $client_id = "260082803045-08ppmrcqi8qaqi5kji6qtfmsdde48io2.apps.googleusercontent.com";
        $client_secret = "GtcSHNuhnfbsqUaLxWta1mbK";
        $redirect_uri = 'http://'. $_SERVER['HTTP_HOST'] .'/account/signinGoogle/';

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");
        $service = new Google_Service_Oauth2($client);
       return $client->createAuthUrl();
    }

    public function facebook()

    {
        // initializing lib
        require_once(CORE_ROOT . 'lib/Facebook/polyfills.php');

        $fb = new Facebook([
            'app_id' => '1790911861239584',
            'app_secret' => '135acb2ab490d800ed1ac5e61adc9ae3',
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            if (isset($_SESSION['facebook_access_token'])) {
                $accessToken = $_SESSION['facebook_access_token'];
            } else {
                $accessToken = $helper->getAccessToken();
            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if (isset($accessToken)) {
            if (isset($_SESSION['facebook_access_token'])) {

                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            } else {
                // Put short-lived access token in session
                $_SESSION['facebook_access_token'] = (string)$accessToken;

                // OAuth 2.0 client handler helps to manage access tokens
                $oAuth2Client = $fb->getOAuth2Client();

                // Exchanges a short-lived access token for a long-lived one
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;

                // Set default access token to be used in script
                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            }

            // Redirect the user back to the same page if url has "code" parameter in query string
//            if (isset($_GET['code'])) {
//                header('Location: ' . filter_var('http://'. $_SERVER['HTTP_HOST'] .'/account/account/', FILTER_SANITIZE_URL));
//            }
            // Getting user facebook profile info
            try {
                $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
                $fbUserProfile = $profileRequest->getGraphNode()->asArray();
                $fbUserProfile['network']='facebook';
                return $fbUserProfile;

            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                session_destroy();
                // Redirect user back to app login page
                header("Location: http://". $_SERVER['HTTP_HOST'] ."/account");
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        } else {
            $this->facebooklink();
        }
    }

    public function google()
    {
        $client_id = "260082803045-08ppmrcqi8qaqi5kji6qtfmsdde48io2.apps.googleusercontent.com";
        $client_secret = "GtcSHNuhnfbsqUaLxWta1mbK";
        $redirect_uri = 'http://'. $_SERVER['HTTP_HOST'] .'/account/signinGoogle/';

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");

        $service = new Google_Service_Oauth2($client);

        //If $_GET['code'] is empty, redirect user to google authentication page for code.
        //Code is required to aquire Access Token from google
        //Once we have access token, assign token to session variable
        //and we can redirect user back to page and login.
        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();

            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
            exit;
        }

        //if we have access_token continue, or else get login URL for user
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);

        } else {
            $authUrl = $client->createAuthUrl();
            // $this->link['google']=$authUrl;

        }

        //Display user info or display login url as per the info we have.
        if (isset($authUrl)) {
            $this->googlelink();

        } else {

            $user = $service->userinfo->get(); //get user info
            $user->network='google';

            return $user;
        }
    }
}