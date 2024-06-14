<?php

class ExamSchedule extends Controller
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
        
        $this->post = (object)filter_input_array(INPUT_POST);
        // $this->post =(object)json_decode(file_get_contents("php://input")) ;
        //   var_dump($this->post->subject_type); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/sch_exam_schedule', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $examSchedule = $this->model('SystemData')->getAllExamSchedule(array());
        
        // var_dump($examSchedule); exit;
        echo json_encode(array('data' => $examSchedule));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_exam_schedule WHERE code = '{$post->code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'EXAM deleted')));
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
    
        if (empty(trim($post->exam_name))) {
            echo json_encode(array('status' => false, 'message' => 'EXAM NAME REQUIRED'));
            exit;
        }
        if (empty(trim($post->cat_code))) {
            echo json_encode(array('status' => false, 'message' => 'CLASS REQUIRED'));
            exit;
        }
        if (empty(trim($post->class_name_code))) {
            echo json_encode(array('status' => false, 'message' => 'CLASS NAME REQUIRED'));
            exit;
        }
        if (empty(trim($post->subject_name_code))) {
            echo json_encode(array('status' => false, 'message' => 'SUBJECT NAME REQUIRED'));
            exit;
        }
        if (empty(trim($post->start_time))) {
            echo json_encode(array('status' => false, 'message' => 'START TIME REQUIRED'));
            exit;
        }
        if (empty(trim($post->end_time))) {
            echo json_encode(array('status' => false, 'message' => 'END TIME REQUIRED'));
            exit;
        }
        
        
        // INSERT
        $fields = array('code','exam_name','cat_code','class_name_code','subject_name_code','start_time','exam_date','end_time', 'submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        // $post->start_time = substr($post->start_time, 0, 5);
        // $post->end_time = substr($post->end_time, 0, 5);
        $examSchedule = $this->model('SystemData')->getExamSchedule(array("code"=>$post->code_old));
        
        //   var_dump($examSchedule);exit;
        //  var_dump($post);exit;
        $sql = '';
        if (empty($examSchedule)) {
            $post->code = $this->model('SystemData')->newAuto(array('auto_item' => $post->code, 'digit' => '0000', 'prefix' => 'EXM', 'suffix' => '/'. date('y'), 'serial' => 'SCH_EXAMSCHEDULE', 'field' => 'code', 'table' => 'sch_exam_schedule'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_exam_schedule', 'option' => ""));
            // var_dump($sql);exit;
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_exam_schedule', 'option' => "WHERE code = '" . addslashes($post->code_old)  . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$examSchedule ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }

    
    public function __save()
    {
        $post = json_decode($this->post->data);
        $key = '';
        

        foreach($post as $k => $v){
            // INSERT
            $key = $k;
            $fields = array('code','exam_name','cat_code','class_name_code','subject_name_code','start_time','exam_date','end_time', 'submit_by', 'modify_by');
            $v->submit_by = $v->modify_by = $this->user->username;
            $sql = "SELECT t1.* FROM sch_exam_schedule t1 WHERE t1.exam_name = '". $v->exam_name ."' AND t1.cat_code = '". $v->cat_code ."' AND t1.class_name_code = '".$v->class_name_code."' AND t1.subject_name_code = '".$v->subject_name_code."' AND t1.start_time = '". $v->start_time ."' AND t1.end_time = '". $v->end_time ."' AND t1.exam_date = '". $v->exam_date ."' ";
            $this->db->query($sql);
            $row_count = $this->db->rowCount();
            $obj = $this->db->single();
            // var_dump($obj->code);exit;

            $sql = '';
            if (empty($row_count)) {
                $v->code = "AUTO";
                $v->code = $this->model('SystemData')->newAuto(array('auto_item' => $v->code, 'digit' => '000000', 'prefix' => 'EXM/', 'suffix' => '/' .date('y'), 'serial' => 'EXAMSCHEDULE', 'field' => 'code', 'table' => 'sch_exam_schedule'));
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_exam_schedule', 'option' => ""));
                
            }
            // UPDATE
            else {
                // var_dump("update");exit;
                $v->code = $obj->code;
                $fields = array_diff($fields, array('submit_by'));
                
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_exam_schedule', 'option' => "WHERE code = '" . $obj->code . "' LIMIT 1"));
            }
            $this->db->query($sql);
            
            $result = $this->db->execute();
            //
            if ($result !== true) {
                echo json_encode(array('status' => false, 'key'=> $key, 'message' => $result));
                exit;
            }

        }
        
    
        // log
        // $post_log = [];
        // foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        // //
        // $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$student ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}