<?php

class SocialBehaviour extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/sch_social_beh', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $terms = $this->model('SystemData')->getSocialBeh(array());
        //var_dump($users); exit;
        echo json_encode(array('data' => $terms));
        exit;
    }

    //social rate
    public function __list()
    {
        //
        $socialKey = $this->model('SystemData')->getSocialKey(array());
        //var_dump($users); exit;
        echo json_encode(array('data' => $socialKey));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_social WHERE code = '{$post->code}' LIMIT 1";
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
        
    }
    //social rate table
    public function __delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM social_beh_key WHERE key_name = '{$post->key_name}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'SCHOOL SOCIAL KEY deleted')));
            exit;
            
        }else{
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;

        }
    
        // if ($this->userAccess['system']['admin'] !== '1') {
        //     echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
        //     exit;
        // }
        
    }
    //
    public function _save()
    {
        $post = $this->post;
        // var_dump($post);exit;
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
    
        if (empty(trim($post->code)) && !empty($post->code_old)){
            echo json_encode(array('status' => false, 'message' => 'CODE VALUE REQUIRED'));
            exit;
        }
        if (empty(trim($post->behaviour))) {
            echo json_encode(array('status' => false, 'message' => 'START DATE REQUIRED'));
            exit;
        }
        
        
        // var_dump($post);exit;
        // INSERT
        $fields = array('code','behaviour','status', 'submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        // if(empty($post->email)) $post->email = "noemail@default.com";
        
        // check record
        $socialBehaviour = $this->model('SystemData')->getSocialBeh(array("option" => "object",'code' => $post->code_old));
        // var_dump($socialBehaviour);exit;
        
        $sql = '';
        if (empty($socialBehaviour)) {
            //
            $post->code = $this->model('SystemData')->newAuto(array('auto_item' => $post->code, 'digit' => '000', 'prefix' => 'SB/', 'suffix' => '', 'serial' => 'SOCIAL_BEHAVIOUR', 'field' => 'code', 'table' => 'sch_social'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_social', 'option' => ""));
        }
        // UPDATE
        else {
    
            // echo json_encode(array('status' => false, 'message' => $user)); exit;
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_social', 'option' => "WHERE code = '" . addslashes($post->code_old ?? '') . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$socialBehaviour ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        // if ($post->status === '1') {
        //     $this->db->query("UPDATE sch_term SET status = '0' WHERE NOT code = '" . $post->code_old . "'");
        //     $this->db->execute();
        // }
        // //
        // $this->db->query("UPDATE sys_user SET access = '" . html_entity_decode($post->access) . "' WHERE username = '" . $post->username . "' LIMIT 1");
        // $this->db->execute();
        
        //
        // $user = $this->model('SystemData')->getUser(array('username' => $post->username));
        // $user->url = URL_ROOT . '/system/user/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
    //social rate table
    public function ___save()
    {
        $post = $this->post;
        // var_dump($post);exit;
    
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
    
        if (empty(trim($post->key_name))) {
            echo json_encode(array('status' => false, 'message' => 'KEY NAME REQUIRED'));
            exit;
        }
        //
        if (empty(trim($post->key_value))) {
            echo json_encode(array('status' => false, 'message' => 'KEY VALUE REQUIRED'));
            exit;
        }
        
        
        // INSERT
        $fields = array('key_name','key_value','submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        $socialrate = $this->model('SystemData')->getSocialKey(array("option" =>"object", "code"=>$post->key_name));
        // var_dump($socialrate);exit;
        $sql = '';
        if (empty($socialrate)) {
            // $post->rate_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->rate_code, 'digit' => '000', 'prefix' => 'EXMRATE/', 'suffix' => '/'. date('y'), 'serial' => 'SCH_EXAMRATE', 'field' => 'rate_code', 'table' => 'sch_exam_rate'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'social_beh_key', 'option' => ""));
            // var_dump($sql);exit;
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'social_beh_key', 'option' => "WHERE key_name = '" . addslashes($post->key_name)  . "' LIMIT 1"));
            // var_dump($sql);exit;
        }

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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$socialrate ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        // if ($post->status === '1') {
        //     $this->db->query("UPDATE sch_exam_rate SET status = '0' WHERE NOT rate_code = '" . $post->rate_code_old . "'");
        //     $this->db->execute();
        // }

        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}