<?php

class Teachers extends Controller
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
        $this->view('school/teachers', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $teachers = $this->model('SystemData')->getTeachers(array());
        echo json_encode(array('data' => $teachers));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_teacher WHERE identity_no = '{$post->identity_no}' LIMIT 1";
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
    
        if (!empty($this->post->email)  && !preg_match($this->regexp_email, $post->email)) {
            echo json_encode(array('status' => false, 'message' => 'EMAIL REQUIRED'));
            exit;
        }
    
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
    
        if (!empty($post->phone) && !preg_match($this->regexp_phone, $post->phone)) {
            echo json_encode(array('status' => false, 'message' => 'TELEPHONE INVALID'));
            exit;
        }
    
        if (empty(trim($post->gender))) {
            echo json_encode(array('status' => false, 'message' => 'GENDER VALUE REQUIRED'));
            exit;
        }
        // if(empty(trim(($post->birthday)))){
        //     echo json_encode(array('status' => false, 'message' => 'BIRTHDAY REQUIRED'));
        //     exit;
        // }
        if(empty(trim(($post->address)))){
            echo json_encode(array('status' => false, 'message' => 'ADDRESS REQUIRED'));
            exit;

        }
        
        // INSERT
        $fields = array('identity_no','first_name', 'last_name', 'title', 'gender', 'blood_group', 'religion', 'email','phone', 'address','state','short_bio','status','picture','birthday', 'submit_by', 'modified_by');
        // var_dump($fields);exit;
        $post->submit_by = $post->modified_by = $this->user->username;
        $post->status = "1";
        //  if(!empty($post->picture)) {
        //     $post->status = "1";
        // }
        // if(empty($post->email)) $post->email = "noemail@default.com";
    
        // check record
        $teacher = $this->model('SystemData')->getTeacher(array('identity_no' => $post->identity_no));
        $sql = '';
        if (empty($teacher)) {

            if(!empty($post->email)){
                $stdt = $this->model('SystemData')->getTeacher2(array("email"=> $post->email));
                if($stdt) {
                    echo json_encode(array('status' => false, 'message' => 'EMAIL ALREADY EXIST ' . $stdt->email));
                    exit;
                }

            }
            $post->identity_no = $this->model('SystemData')->newAuto(array('auto_item' => $post->identity_no, 'digit' => '000', 'prefix' => 'TCH/', 'suffix' => '/' .date('y'), 'serial' => 'TEACHER', 'field' => 'identity_no', 'table' => 'sch_teacher'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_teacher', 'option' => ""));
            //  var_dump($sql);exit;
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_teacher', 'option' => "WHERE identity_no = '" . addslashes($post->identity_no ?? '') . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$teacher ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}