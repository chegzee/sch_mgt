<?php

class Subject extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $result;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->category = $this->model('SystemData')->getCategories(array());
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['category'] = $this->category;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        // $this->post =(object)json_decode(file_get_contents("php://input")) ;
        //   var_dump($this->post->subject_type); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/subject', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $students = $this->model('SystemData')->getSubjects(array());
        //var_dump($users); exit;
        echo json_encode(array('data' => $students));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post);exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_subject WHERE sub_code = '{$post->subj_code}' LIMIT 1";
            //var_dump($sql); exit;
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'SUBJECT deleted')));
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
        $post = json_decode($this->post->subject);
        $fields = array('sub_code', 'class_code','subject_name', 'submit_by', 'modified_by');
        /////fields for mapping table//////////////////////
        $fields_ = array('class_code','subject_name_code', 'submit_by', 'modify_by');

        foreach($post as $k => $v){
            if(empty($v->subject_name)) continue;
            $v->modified_by = $this->user->username;
            $v->submit_by = $this->user->username;

            // var_dump($v);exit;
            // $subject = $this->model('SystemData')->getSubject(array("sub_code"=> $v->sub_code));
            $subject = $this->model('SystemData')->getSubject(array("sub_code"=> $v->sub_code));
            // $subject = $this->model('SystemData')->getSubject(array("subject_name"=> $v->subject_name, "class_code"=> $v->class_code));
            // var_dump($subject);exit;
            if(!empty($subject)){
                $fields = array_diff($fields, array('submit_by'));
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_subject', 'option' => "WHERE sub_code = '".$v->sub_code."' LIMIT 1"));
                $this->db->query($sql);
                $this->result = $this->db->execute();
                if($this->result === true){
                    $v->subject_name_code = $v->sub_code;
                    $v->modify_by = $this->user->username;
                    $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'UPDATE', 'fields' => $fields_, 'table' => 'sch_mapping', 'option' => "WHERE subject_name_code = '".$v->subject_name_code."' LIMIT 1"));
                    $this->db->query($sql);
                    $this->result = $this->db->execute();
                }
                // else{
                //     echo json_encode(array("status"=>$this->result, "message"=>$this->result));exit;
                // }
                continue;
                // var_dump($sql);exit;
            } 
            
            $v->sub_code = $this->model('SystemData')->newAuto(array('auto_item' => $v->sub_code, 'digit' => '000', 'prefix' => '#', 'suffix' => '/'. date('y'), 'serial' => 'SUBJECT', 'field' => 'sub_code', 'table' => 'sch_subject'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_subject', 'option' => ""));
            $this->db->query($sql);
            $this->result = $this->db->execute();
            
            $v->subject_name_code = $v->sub_code;
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields_, 'table' => 'sch_mapping', 'option' => ""));
            $this->db->query($sql);
            $this->result = $this->db->execute();
            // var_dump($result);exit;
        }
        
        echo json_encode(array("status"=>$this->result, "data"=>$this->result));
        
    
    }
}