<?php

class ClassRoutine extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    
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
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['levelsobj'] = $this->model('SystemData')->getCategories(array("_option" => "object"));
        $this->data['classesobj'] = $this->model('SystemData')->getClasses(array("_option" => "object"));
        $this->data['subjectsobj'] = $this->model('SystemData')->getSubjects(array("_option" => "object"));
        $this->data['teachersobj'] = $this->model('SystemData')->getTeachers(array("_option" => "object"));
        
        $this->post = (object)filter_input_array(INPUT_POST);
        // $this->post =(object)json_decode(file_get_contents("php://input")) ;
        //   var_dump($this->data['subjectsobj']); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/classRoutine', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $classRoutine = $this->model('SystemData')->getAllClassRoutine(array());
        
        echo json_encode(array('data' => $classRoutine));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_class_routine WHERE code = '{$post->code}' LIMIT 1";
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
        $timetable = $this->post->timetable;
        $post = $this->post;
        // echo json_encode(array("data"=>$timetable));exit;
    
    
        if (empty(trim($post->class_code))) {
            echo json_encode(array('status' => false, 'message' => 'CLASS REQUIRED'));
            exit;
        }
        if (empty(trim($post->term_code))) {
            echo json_encode(array('status' => false, 'message' => 'TERM NAME REQUIRED'));
            exit;
        }
        
        
        // INSERT
        $fields = array('class_code','term_code', 'submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        $classRoutine = $this->model('SystemData')->getClassRoutine(array("class_code"=>$post->class_code, "term_code"=> $post->term_code));
        
        $sql = '';
        if (empty($classRoutine)) {
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_class_routine', 'option' => ""));
            $this->db->query($sql);
            $result = $this->db->execute();
            ////////////////////////////////////////////////
            $sql = "UPDATE sch_class_routine SET timetable ='".$timetable."' WHERE class_code= '".$post->class_code."'  AND term_code='".$post->term_code."' ";
            $this->db->query($sql);
            $result = $this->db->execute();
            echo json_encode(array('status' => $result));
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = "UPDATE sch_class_routine SET timetable ='".$timetable."' WHERE class_code= '".$post->class_code."'  AND term_code='".$post->term_code."' ";
            $this->db->query($sql);
            $result = $this->db->execute();
            echo json_encode(array('status' => $result));
        }
    
        // log
        $post_log = [];
        foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$classRoutine ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    }
    //
    public function __save()
    {
        $post = json_decode($this->post->data);
        $key = '';
        // var_dump($post);exit;

        foreach($post as $k => $v){
            // INSERT
            $key = $k;
            $fields = array('code','day','cat_code','class_name_code','subject_name_code','teacher_code','start_time','end_time', 'submit_by', 'modify_by');
            $v->submit_by = $v->modify_by = $this->user->username;
            $sql = "SELECT t1.* FROM sch_class_routine t1 WHERE t1.day = '". $v->day ."' AND t1.cat_code = '". $v->cat_code ."' AND t1.class_name_code = '".$v->class_name_code."' AND t1.subject_name_code = '".$v->subject_name_code."' AND t1.teacher_code = '".$v->teacher_code."' AND t1.start_time = '". $v->start_time ."' AND t1.end_time = '". $v->end_time ."' ";
            $this->db->query($sql);
            $row_count = $this->db->rowCount();
            $obj = $this->db->single();
            // var_dump($obj->code);exit;

            $sql = '';
            if (empty($row_count)) {
                $v->code = "AUTO";
                $v->code = $this->model('SystemData')->newAuto(array('auto_item' => $v->code, 'digit' => '000000', 'prefix' => $v->class_name_code .'/', 'suffix' => '/' .date('y'), 'serial' => 'CLASSROUTINE', 'field' => 'code', 'table' => 'sch_class_routine'));
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_class_routine', 'option' => ""));
                
            }
            // UPDATE
            else {
                // var_dump("update");exit;
                $v->code = $obj->code;
                $fields = array_diff($fields, array('submit_by'));
                
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_class_routine', 'option' => "WHERE code = '" . $obj->code . "' LIMIT 1"));
            }
            $this->db->query($sql);
            
            $result = $this->db->execute();
            //
            if ($result !== true) {
                echo json_encode(array('status' => false, 'key'=> $key, 'message' => $result));
                exit;
            }

        }
        
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}