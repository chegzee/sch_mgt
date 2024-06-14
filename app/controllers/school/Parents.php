<?php

class Parents extends Controller
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
        $this->view('school/parents', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $parents = $this->model('SystemData')->getParents(array());
        echo json_encode(array('data' => $parents));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_parent WHERE parent_code = '{$post->parent_code}' LIMIT 1";
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
        $fields = array('parent_code','first_name', 'last_name', 'title', 'gender', 'religion', 'email','phone', 'address','state','status','picture', 'short_bio', 'blood_group', 'submit_by', 'modified_by');
        // var_dump($fields);exit;
        $post->submit_by = $post->modified_by = $this->user->username;
         if(!empty($post->picture)) {
            $post->status = "1";
        }
        // if(empty($post->email)) $post->email = "noemail@default.com";
    
        // check record
        $parent = $this->model('SystemData')->getParents(array('_option'=>'parent_code', 'parent_code' => $post->parent_code));
        $sql = '';
        if (empty($parent)) {

            if(!empty($post->email)){
                $parent = $this->model('SystemData')->getParent(array("email"=> $post->email));
                if($parent) {
                    echo json_encode(array('status' => false, 'message' => 'EMAIL ALREADY EXIST ' . $parent->email));
                    exit;
                }

            }
            $post->parent_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->parent_code, 'digit' => '000', 'prefix' => 'PRT/', 'suffix' => '/' .date('y'), 'serial' => 'PARENT', 'field' => 'parent_code', 'table' => 'sch_parent'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_parent', 'option' => ""));
            //  var_dump($sql);exit;
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_parent', 'option' => "WHERE parent_code = '" . addslashes($post->parent_code ?? '') . "' LIMIT 1"));
        }
        // var_dump($sql);exit;
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$parent ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }

    
    //for saving student from excel
    public function __save()
    {
        $post = json_decode($this->post->data);
        $key = '';
        // var_dump($post);exit;
        
        foreach($post as $k => $v){
           
            $term = $this->model("SystemData")->getTerms(array("_option" => "current"));
            // INSERT
            $key = $k;
            $fields = array('parent_code','first_name', 'last_name', 'gender', 'religion', 'email', 'phone', 'address', 'status', 'submit_by', 'modified_by');
            $v->submit_by = $v->modify_by = $this->user->username;
            $sql = "SELECT t1.* FROM sch_parent t1 WHERE t1.first_name = '". $v->first_name ."' AND t1.last_name = '". $v->last_name ."' ";
            
            $this->db->query($sql);
            $rowCount = $this->db->rowCount();

            $sql = '';
            if (empty($rowCount)) {
                
                if(empty($v->first_name)){
                    echo json_encode(array('status' => false, 'message' => 'FIRST NAME REQUIRED'));
                    exit;
    
                }
                if(empty($v->last_name)){
                    echo json_encode(array('status' => false, 'message' => 'LAST NAME REQUIRED'));
                    exit;
    
                }
                $code_auto = "AUTO";
                $this->code = $this->model('SystemData')->newAuto(array('auto_item' => $code_auto, 'digit' => '000000', 'prefix' => 'PRT/', 'suffix' => '/' .date('y'), 'serial' => 'PARENT', 'field' => 'parent_code', 'table' => 'sch_parent'));
                $v->parent_code = $this->code;
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_parent', 'option' => ""));
                $this->db->query($sql);
                $result = $this->db->execute();


                if ($result !== true) {
                    echo json_encode(array('status' => false, 'key'=> $key, 'message' => $result));
                    exit;
                }
            }else if($rowCount > 0){
                echo json_encode(array('status' => false, 'message' => "PARENT ALREADY EXIST", "rowNo"=> $key));
                exit;
            }
            
        }
        
        echo json_encode(array('status' => true, 'data' => $result));
        // exit;
    }
}