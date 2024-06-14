<?php

class Branch extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        // var_dump($this->userAccess); exit;
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (empty($this->user)) header('Location: ' . URL_ROOT . '/system/login');
        // if ($this->user->access->system->technical !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
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
        $this->view('system/branch', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $branches = $this->model('SystemData')->getBranches();
        // var_dump($branches); exit;
        echo json_encode(array('data' => $branches));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM cod_branch WHERE branch_code = '{$post->branch_code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'User deleted')));
            exit;
            
        }else{
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;

        }
        // if ($this->userAccess['system']['admin'] !== '1') {
        //     echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
        //     exit;
        // }
        
        // $sql = "DELETE FROM cod_branch WHERE branch_code = '{$post->branch_code}' LIMIT 1";
        // //var_dump($sql); exit;
        
        // $this->db->query($sql);
        // $result = $this->db->execute();
        
        // if ($result !== true) {
        //     echo json_encode(array('status' => false, 'message' => $result));
        //     exit;
        // }
        
        // //
        // echo json_encode(array('status' => true, 'data' => array('message' => 'Branch deleted')));
        // exit;
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
    
        if (empty($post->branch_code) && empty($post->branch_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'BRANCH CODE REQUIRED'));
            exit;
        }
        
        if (empty($post->account_code)) {
            echo json_encode(array('status' => false, 'message' => 'BANK CODE REQUIRED'));
            exit;
        }
        if (empty(trim($post->branch_name))) {
            echo json_encode(array('status' => false, 'message' => 'BRANCH NAME REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->alpha))) {
            echo json_encode(array('status' => false, 'message' => 'ALPHA CODE REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->digit))) {
            echo json_encode(array('status' => false, 'message' => 'DIGIT CODE REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->contact))) {
            echo json_encode(array('status' => false, 'message' => 'BRANCH CONTACT REQUIRED'));
            exit;
        }
        
        if (!preg_match($this->regexp_email, $post->email)) {
            echo json_encode(array('status' => false, 'message' => 'CONTACT EMAIL INVALID'));
            exit;
        }
        
        if (!preg_match($this->regexp_phone, $post->phone)) {
            echo json_encode(array('status' => false, 'message' => 'CONTACT PHONE INVALID'));
            exit;
        }
        
        if (!empty($post->phone2) && !preg_match($this->regexp_phone, $post->phone2)) {
            echo json_encode(array('status' => false, 'message' => 'CONTACT PHONE2 INVALID'));
            exit;
        }
    
        if (empty(trim($post->address))) {
            echo json_encode(array('status' => false, 'message' => 'BRANCH ADDRESS REQUIRED'));
            exit;
        }
        if (empty(trim($post->state))) {
            echo json_encode(array('status' => false, 'message' => 'STATE REQUIRED'));
            exit;
        }
        if (empty(trim($post->country))) {
            echo json_encode(array('status' => false, 'message' => 'COUNTRY REQUIRED'));
            exit;
        }
        
        // INSERT
        $fields = array('branch_code', 'picture', 'branch_name', 'alpha', 'digit', 'contact', 'email', 'phone', 'phone2', 'address', 'account_code', 'state', 'country', 'submit_by', 'modified_by');
        
        $post->submit_by = $post->modified_by = $this->user->username;
        
        // check record
        $branch = $this->model('SystemData')->getBranch(array('branch_code' => $post->branch_code_old));
        
        $sql = '';
        if (!$branch) {
            
            // LAG/01/001
            $post->branch_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->branch_code, 'digit' => '000', 'prefix' => 'BR', 'suffix' => '', 'serial' => 'BRANCH', 'field' => 'branch_code', 'table' => 'cod_branch'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'cod_branch', 'option' => ""));
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'cod_branch', 'option' => "WHERE branch_code = '" . addslashes($post->branch_code_old ?? '') . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$branch ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        //
        $branch = $this->model('SystemData')->getBranch(array('branch_code' => $post->branch_code));
        $branch->url = URL_ROOT . '/system/branch/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $branch));
        exit;
    }
}