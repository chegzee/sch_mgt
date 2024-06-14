<?php

class Currency extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        if ($this->user->access->finance->setup !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('finance/currency', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $currencies = $this->model('AccountData')->getCurrencies();
        //var_dump($currencies); exit;
        echo json_encode(array('data' => $currencies));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['finance']['admin'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
        $sql = "DELETE FROM cod_currency WHERE currency_code = '{$post->currency_code}' LIMIT 1";
        //var_dump($sql); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
        
        //
        echo json_encode(array('status' => true, 'data' => array('message' => 'Currency deleted')));
        exit;
    }
    
    public function _save()
    {
        $post = $this->post;
    
        if (!empty($post)) {
            foreach ($post as $k => $v) {
                // int
                if (in_array($k, $this->column_int)) {
                    $post->{$k} = (int)preg_replace('/[^0-9.]/', '', $v);
                }
                // float
                else if (in_array($k, $this->column_float)) {
                    $post->{$k} = (float)preg_replace('/[^0-9.]/', '', $v);
                }
                // string
                else
                    $post->{$k} = trim($v);
            }
        }
        //echo json_encode(array('status' => false, 'message' => $_POST)); exit;
    
        if (empty(trim($post->currency_code)) && empty($post->currency_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'CURRENCY CODE REQUIRED'));
            exit;
        }
    
        if (empty($post->currency_main)) {
            echo json_encode(array('status' => false, 'message' => 'CURRENCY MAIN REQUIRED'));
            exit;
        }
    
        if (empty($post->currency_sub)) {
            echo json_encode(array('status' => false, 'message' => 'CURRENCY SUB REQUIRED'));
            exit;
        }
    
        if ((float)preg_replace('/[^\d.]/', '', $post->currency_rate) <= 0) {
            echo json_encode(array('status' => false, 'message' => 'CURRENCY RATE REQUIRED'));
            exit;
        }
    
        if (empty($post->html_code)) {
            echo json_encode(array('status' => false, 'message' => 'CURRENCY SYMBOL REQUIRED'));
            exit;
        }
    
        if (empty($post->remarks)) {
            echo json_encode(array('status' => false, 'message' => 'REMARKS REQUIRED'));
            exit;
        }
        
        // INSERT
        $fields = array('currency_code', 'currency_main', 'currency_sub', 'currency_rate', 'html_code', 'remarks');
        
        // check record
        $currency = $this->model('AccountData')->getCurrency(array('currency_code' => $post->currency_code_old));
        
        $sql = '';
        if (!$currency) {
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'cod_currency', 'option' => ""));
        }
        // UPDATE
        else {
    
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'cod_currency', 'option' => "WHERE currency_code = '" . addslashes($post->currency_code_old ?? '') . "' LIMIT 1"));
        }
        //echo json_encode(array('status' => false, 'message' => $sql)); exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        // log
        $post_log = [];
        foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$currency ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        //
        $currency = $this->model('AccountData')->getCurrency(array('currency_code' => $post->currency_code));
        $currency->url = URL_ROOT . '/account/currency/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $currency));
        exit;
    }
}