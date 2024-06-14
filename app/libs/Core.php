<?php
class Core {
    protected $currentFolder = 'system';
    protected $currentController = 'Login'; // Err
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();
        $url[0] = $url[0] ?? $this->currentFolder;
        $url[1] = $url[1] ?? $this->currentController;

        //echo '<pre>' . print_r($_GET, true) . '</pre>'; exit;
        if (file_exists('../app/controllers/' . $url[0] . '/' . ucwords($url[1]) . '.php')) {

            // Set a new folder
            $this->currentFolder = ucwords($url[0]);
            unset($url[0]);

            // Set a new controller
            $this->currentController = ucwords($url[1]);
            unset($url[1]);
            
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentFolder . '/' . $this->currentController . '.php';      
        $this->currentController = new $this->currentController;

        // Check for the second part of the URL
        if (isset($url[2])) {
            if (method_exists($this->currentController, $url[2])) {
                $this->currentMethod = $url[2];
                unset($url[2]);
            }
        }

        // Get parameters
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            // filter variables as string/number
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // breaking into array
            $url = explode('/', $url);

            return $url;
        }
    }
}