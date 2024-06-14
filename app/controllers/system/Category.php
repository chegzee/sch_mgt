<?php

class Category extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    protected $userAccess;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        if ($this->user->access->system->technical !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['categories'] = $this->model('SystemData')->getCategories();
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {   
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/category', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $categories = $this->model('SystemData')->getCategories_(array());
        // var_dump($clients); exit;
        
        echo json_encode(array('data' => $categories));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['system']['technical'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
        $sql = "DELETE FROM sch_category WHERE cat_code = '{$post->cat_code}' LIMIT 1";
        //var_dump($sql); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
    
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        //
        echo json_encode(array('status' => true, 'data' => array('message' => 'Class deleted')));
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
    
        if (empty($post->cat_code) && !empty($post->cat_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'CATEGORY CODE REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->cat_name))) {
            echo json_encode(array('status' => false, 'message' => 'LEVEL NAME REQUIRED'));
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
        if (empty(trim($post->fees))) {
            echo json_encode(array('status' => false, 'message' => 'FEES REQUIRED'));
            exit;
        }
        // var_dump(SCHMGT_CATEGORY_DISABLED);exit;
        if(SCHMGT_CATEGORY_DISABLED){
            echo json_encode(array('status' => false, 'message' => 'AUTHORIZATION DISABLED'));
            exit;

        }
        
        // INSERT
        $fields = array('cat_code', 'cat_name', 'alpha', 'digit', 'status', 'fees', 'submit_by', 'modified_by');
        
        $post->submit_by = $post->modified_by = $this->user->username;
        
        // check record
        $category = $this->model('SystemData')->getCategory(array('cat_code' => $post->cat_code_old));
        
        $sql = '';
        if (!$category) { 
            
            $post->cat_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->cat_code, 'digit' => '00', 'prefix' => 'C', 'suffix' => '', 'serial' => 'category', 'field' => 'cat_code', 'table' => 'category'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_category', 'option' => ""));
        } // UPDATE
        else {
            
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_category', 'option' => "WHERE cat_code = '" . addslashes($post->cat_code_old ?? '') . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$category ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        //
        $category = $this->model('SystemData')->getCategory(array('cat_code' => $post->cat_code));
        $category->url = URL_ROOT . '/system/category/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $category));
        exit;
    }

    public function updataCategory(){
        $post = $this->post;
        $fields = ['status'];
        $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_category', 'option' => "WHERE cat_name = '" . addslashes($post->code ?? '') . "' LIMIT 1"));
        $this->db->query($sql);
        $result = $this->db->execute();
        echo json_encode(["status"=> $result, "message"=> $result]);
        // var_dump($sql);
    }
}