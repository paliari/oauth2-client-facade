<?php

namespace Paliari\Oauth2ClientFacade;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Storage\Session;
use OAuth\ServiceFactory;

class Controller
{
    protected $credentials = array();

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    public function run()
    {
        $path = $this->getPath() ?: 'form';
        @list($action, $id) = explode("/", $path);
        $method = "action_$action";
        if ( method_exists($this, $method) ) {
            $this->{$method}($id);
        }
    }

    public function pathTo($action, $id)
    {
        return sprintf("%s/%s/%s", $this->getBasePath(), $action, $id);
    }

    public function getLoginPath($id)
    {
        return $this->pathTo('login', $id);
    }

    protected function action_login($id)
    {
        $service = $this->getService($id);
        $params = array();

        if ($id == 'twitter') {
            $token = $service->requestRequestToken();
            $params = array('oauth_token' => $token->getRequestToken());
        }

        $url = $service->getAuthorizationUri($params);
        header("Location: $url");
    }

    protected function getStorage()
    {
        return new Session();
    }

    protected function action_callback($id)
    {
        if ( isset($_GET['code']) ) {

            $service = $this->getService($id);
            echo $service->requestAccessToken($_GET['code'])->getAccessToken();

        } elseif (isset($_GET['oauth_token'], $_GET['oauth_verifier'])) {

            $token = $this->getStorage()->retrieveAccessToken(ucfirst($id));
            $this->getService($id)->requestAccessToken(
                $_GET['oauth_token'],
                $_GET['oauth_verifier'],
                $token->getRequestTokenSecret()
            );
            echo $token->getAccessToken();
        } else {
            // google/github error=access_denied
            // twitter denied=BSJNxyo5pD5JEtR2Rj70RapHEjNkgeDiW7hVrRUVw
            throw new \DomainException("Missing 'code' parameter for Oauth2 or oauth_token/oauth_verifier from Oauth1!");
        }

        /*
        $result = json_decode($service->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

        echo 'Your unique google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
        header("Content-Type: text/plain");
        var_dump($_REQUEST);
        */
    }

    /**
     * @param $id
     * @return \OAuth\OAuth2\Service\AbstractService
     */
    protected function getService($id)
    {
        $credential = $this->getCredentials($id);

        // Setup the credentials for the requests
        $credentials = new Credentials(
            $credential['id'],
            $credential['secret'],
            $this->getRootUri() . $this->getCallbackPath($id)
        );

        $serviceFactory = new ServiceFactory();
        $service = $serviceFactory->createService($id, $credentials, $this->getStorage(), $credential['scopes']);

        return $service;
    }

    protected function getCredentials($id = null)
    {
        return $id ? $this->credentials[$id] : $this->credentials;
    }

    protected function getRootUri()
    {
        return sprintf("%s://%s:%s", $this->getUri()->getScheme(), $this->getUri()->getHost(), $this->getUri()->getPort());
    }

    protected function getCallbackPath($id)
    {
        return $this->pathTo('callback', $id);
    }

    protected function action_form()
    {
        foreach ($this->getCredentials() as $service => $credentials) {
            $url = $this->getLoginPath($service);
            echo " <a href='$url'>$service</a> ";
        }
    }

    protected function getPath()
    {
        $path = $this->getUri()->getPath();
        $basePath = $this->getBasePath();
        $path = substr($path, strlen($basePath));
        $path = trim($path, '/');

        return $path;
    }

    protected function getBasePath()
    {
        return pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
    }

    protected function getUri()
    {
        $uriFactory = new UriFactory();
        return $uriFactory->createFromSuperGlobalArray($_SERVER);
    }
}
