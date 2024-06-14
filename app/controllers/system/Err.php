<?php
Class Err {
    public function __construct()
    {
        
    }

    public function index() {
        echo '404 Page Not Found<br>';
        echo 'Click : <a href="'.URL_ROOT.'/system/login">Login</a>';
    }
}