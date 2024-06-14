<?php

class User extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $technical;
    protected $userAccess;
    protected $department;
    protected $student;
    protected $teacher;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        $this->data["student_user"] = $this->model('SystemData')->getStudents(array("_option"=>"student_user"));
        // var_dump($this->data["student_user"]); exit;
        if (empty($this->user)) header('Location: ' . URL_ROOT . '/system/login');
        if ($this->user->access->system->user !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
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
        $this->view('system/user', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $users = $this->model('SystemData')->getusers(array());
        //var_dump($users); exit;
        echo json_encode(array('data' => $users));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['system']['technical'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
        $sql = "DELETE FROM sys_user WHERE username = '{$post->username}' LIMIT 1";
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
    
        if (!preg_match($this->regexp_email, $post->username)) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME/EMAIL REQUIRED'));
            exit;
        }
    
        if (empty($post->password ?? ''))
            $post->password = $this->model('SystemData')->passwordGenerator();
        if (!preg_match($this->regexp_password, $post->password) && $post->password !== $post->password1) {
            echo json_encode(array('status' => false, 'message' => 'PASSWORD INVALID'));
            exit;
        }
        if ($post->password !== $post->password1)
            $post->password = password_hash($post->password, PASSWORD_DEFAULT);
    
        if (empty(trim($post->first_name))) {
            echo json_encode(array('status' => false, 'message' => 'FIRST NAME REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->last_name))) {
            echo json_encode(array('status' => false, 'message' => 'LAST NAME REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->address))) {
            echo json_encode(array('status' => false, 'message' => 'ADDRESS REQUIRED'));
            exit;
        }
    
        if (!preg_match($this->regexp_phone, $post->phone)) {
            echo json_encode(array('status' => false, 'message' => 'TELEPHONE INVALID'));
            exit;
        }
    
        if (empty(trim($post->group_code))) {
            echo json_encode(array('status' => false, 'message' => 'USER GROUP REQUIRED'));
            exit;
        }

        $this->department = $this->model('SystemData')->getUserGroup(array("group_code" => $post->group_code));
        
        if(!empty($this->department) && $this->department->group_name == 'STUDENTS'){
           $this->student = $this->model('SystemData')->getStudent2(array("email"=> $post->username));
           
        }
        if(empty($this->student) && $this->department->group_name == 'STUDENTS'){
            echo json_encode(array('status' => false, 'message' => 'STUDENT EMAIL DOES NOT EXIST'));
            exit;

        }
        if(!empty($this->department) && $this->department->group_name == 'TEACHERS'){
           $this->teacher = $this->model('SystemData')->getTeacher2(array("email"=> $post->username));
           
        }
        if(empty($this->teacher) && $this->department->group_name == 'TEACHERS'){
            // var_dump($this->teacher);exit;
            echo json_encode(array('status' => false, 'message' => 'TEACHER EMAIL DOES NOT EXIST'));
            exit;

        }

        // INSERT
        $fields = array('username','password', 'password_reset', 'password_def', 'password_date', 'password_attempt', 'password_wait', 'user_log', 'user_session', 'status', 'submit_by', 'modified_by', 'first_name', 'last_name', 'address', 'phone', 'picture', 'signature', 'group_code');
        
        $post->submit_by = $post->modified_by = $this->user->username;
        
        $post->password_reset = '';
        $post->password_def = $post->password;
        $post->password_date = date('Y-m-d', time() - 365 * 24 * 60 * 60);
        $post->password_attempt = 0;
        $post->password_wait = date('Y-m-d H:i:s');
         $post->user_log = $this->data['params']['user_log'];
        // $post->user_session = '';
    
        // check record
        $user = $this->model('SystemData')->getUser(array('username' => $post->username_old));
        
        $sql = '';
        if (!$user) {
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sys_user', 'option' => ""));
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sys_user', 'option' => "WHERE username = '" . addslashes($post->username_old ?? '') . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$user ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        $this->db->query("UPDATE sys_user SET access = '" . html_entity_decode($post->access) . "' WHERE username = '" . $post->username . "' LIMIT 1");
        $this->db->execute();
        
        //
        $user = $this->model('SystemData')->getUser(array('username' => $post->username));
        $user->url = URL_ROOT . '/system/user/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $user));
        exit;
    }
}