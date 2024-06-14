<?php

class ActBank extends Controller
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
        $this->view('finance/actBank', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $actBank = $this->model('AccountData')->getBanks(array());
        //var_dump($currencies); exit;
        echo json_encode(array('data' => $actBank));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['finance']['admin'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
        $sql = "DELETE FROM act_bank WHERE account_code = '{$post->account_code}' LIMIT 1";
        //var_dump($sql); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
        
        //
        echo json_encode(array('status' => true, 'data' => array('message' => 'Account deleted')));
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
        // echo json_encode(array('status' => false, 'message' => $post)); exit;
        // var_dump($post);exit;
    
        if (empty(trim($post->account_code)) && empty($post->account_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT CODE REQUIRED'));
            exit;
        }
    
        if (empty($post->account_number)) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT NUMBER REQUIRED'));
            exit;
        }
    
        if (empty($post->account_name)) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT NAME REQUIRED'));
            exit;
        }
    
        if (empty($post->bank_name)) {
            echo json_encode(array('status' => false, 'message' => 'BANK NAME REQUIRED'));
            exit;
        }
        // var_dump($post);exit;
        // INSERT
        $fields = array('account_code', 'account_number', 'account_name', 'bank_name', 'submit_by');
        $this->post->submit_by = $this->user->username;
        // check record
        $actBank = $this->model('AccountData')->getBanks(array('_option'=>'account_code', 'account_code' => $post->account_code_old));
        // var_dump($actBank);exit;
        $sql = '';
        if (!$actBank) {
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_bank', 'option' => ""));
        }
        // UPDATE
        else {
    
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'act_bank', 'option' => "WHERE account_code = '" . addslashes($post->account_code_old ?? '') . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$actBank ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        //
        $actBank = $this->model('AccountData')->getBanks(array('account_code' => $post->account_code));
        //$actBank->url = URL_ROOT . '/finance/actBank/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $actBank));
        exit;
    }
}