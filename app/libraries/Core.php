<?php

class Core {//this is the router
    //URL format ---> /controller/method/params
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        // print_r($this->getURL());

        $url = $this->getURL();
        $controllerName = isset($url[0]) && $url[0] !== '' ? ucwords($url[0]) : 'Pages';

        if(file_exists('../app/controllers/' . $controllerName . '.php')) {
            //if the controller exits, then load it
            $this->currentController = $controllerName;

            // unset the controller in the URL
            unset($url[0]);
        }
        //call the controller
        require_once '../app/controllers/' . $this->currentController .'.php';

        //instantiate controller class
        $this->currentController = new $this->currentController;

        // check whether the method exists in the controller or not
        if(isset($url[1])) {
            if(method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // get the parameter lists
        $this->params = $url ? array_values($url) : [];

        // call the method and pass the parameters
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getURL() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            return $url;
        }
        return [];
    }
}

?>