<?php

class ExamGrade extends Controller
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
        
        $this->post = (object)filter_input_array(INPUT_POST);
        // $this->post =(object)json_decode(file_get_contents("php://input")) ;
        //   var_dump($this->post->subject_type); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/sch_exam_grade', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $examGrade = $this->model('SystemData')->getAllExamGrade(array());
        
        // var_dump($examGrade); exit;
        echo json_encode(array('data' => $examGrade));
        exit;
    }

    //exam rate table
    public function __list()
    {
        //
        $examRate = $this->model('SystemData')->getExamRate(array());
        
        // var_dump($examGrade); exit;
        echo json_encode(array('data' => $examRate));
        exit;
    }
    //exam rate table
    public function getExamNameList()
    {
        //
        $examName = $this->model('SystemData')->getExamName(array());
        
        // var_dump($examGrade); exit;
        echo json_encode(array('data' => $examName));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_exam_grade WHERE code = '{$post->code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'SCHOOL EXAM GRADE deleted')));
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

    //exam rate table
    public function __delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_exam_rate WHERE rate_code = '{$post->rate_code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'SCHOOL EXAM GRADE deleted')));
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

    public function deleteExamName(){
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_exam_name WHERE exam_name_code = '{$post->exam_name_code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'SCHOOL EXAM NAME deleted')));
            exit;
            
        }else{
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;

        }

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
    
        if (empty(trim($post->code)) && !empty(trim($post->code_old))) {
            echo json_encode(array('status' => false, 'message' => 'CODE FIELD REQUIRED'));
            exit;
        }
        if (empty(trim($post->grade_name))) {
            echo json_encode(array('status' => false, 'message' => 'GRADE NAME REQUIRED'));
            exit;
        }
        // if (empty(trim($post->grade_point))) {
        //     echo json_encode(array('status' => false, 'message' => 'GRADE POINT REQUIRED'));
        //     exit;
        // }
        if (empty(trim($post->percent_upto))) {
            echo json_encode(array('status' => false, 'message' => 'PERCENT UPTO REQUIRED'));
            exit;
        }
        if (trim($post->percent_from) === '') {
            echo json_encode(array('status' => false, 'message' => 'PERCENT FROM REQUIRED'));
            exit;
        }
        
        
        // INSERT
        $fields = array('code','grade_name','grade_point','percent_from','percent_upto','comment', 'submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        // $post->start_time = substr($post->start_time, 0, 5);
        // $post->end_time = substr($post->end_time, 0, 5);
        $examGrade = $this->model('SystemData')->getExamGrade(array("code"=>$post->code_old));
        
        //   var_dump($examGrade);exit;
        //  var_dump($post);exit;
        $sql = '';
        if (empty($examGrade)) {
            $post->code = $this->model('SystemData')->newAuto(array('auto_item' => $post->code, 'digit' => '0000', 'prefix' => 'EXM/', 'suffix' => '/'. date('y'), 'serial' => 'SCH_EXAMGRADE', 'field' => 'code', 'table' => 'sch_exam_grade'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_exam_grade', 'option' => ""));
            // var_dump($sql);exit;
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_exam_grade', 'option' => "WHERE code = '" . addslashes($post->code_old)  . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$examGrade ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }

    //exam rate table
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
    
        if (empty(trim($post->rate_code)) && !empty(trim($post->rate_code_old))) {
            echo json_encode(array('status' => false, 'message' => 'CODE FIELD REQUIRED'));
            exit;
        }
        // if (empty(trim($post->class_work))) {
        //     echo json_encode(array('status' => false, 'message' => 'CLASS WORK REQUIRED'));
        //     exit;
        // }
        // if (empty(trim($post->grade_point))) {
        //     echo json_encode(array('status' => false, 'message' => 'GRADE POINT REQUIRED'));
        //     exit;
        // }
        // if (empty(trim($post->mid_term_exam))) {
        //     echo json_encode(array('status' => false, 'message' => 'MID TERM REQUIRED'));
        //     exit;
        // }
        if (trim($post->terminal_exam) === '') {
            echo json_encode(array('status' => false, 'message' => 'TERMINAL EXAM REQUIRED'));
            exit;
        }
        
        
        // INSERT
        $fields = array('rate_code','class_work','mid_term_exam','terminal_exam', 'status', 'submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        $examrate = $this->model('SystemData')->getExamRate(array("_option" =>"object", "rate_code"=>$post->rate_code_old));
        
        $sql = '';
        if (empty($examrate)) {
            $post->rate_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->rate_code, 'digit' => '000', 'prefix' => 'EXMRATE/', 'suffix' => '/'. date('y'), 'serial' => 'SCH_EXAMRATE', 'field' => 'rate_code', 'table' => 'sch_exam_rate'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_exam_rate', 'option' => ""));
            // var_dump($sql);exit;
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_exam_rate', 'option' => "WHERE rate_code = '" . addslashes($post->rate_code_old)  . "' LIMIT 1"));
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$examrate ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        if ($post->status === '1') {
            $this->db->query("UPDATE sch_exam_rate SET status = '0' WHERE NOT rate_code = '" . $post->rate_code_old . "'");
            $this->db->execute();
        }

        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }

    public function saveExamName(){
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
    
        if (empty(trim($post->exam_name_code)) && !empty(trim($post->exam_name_code_old))) {
            echo json_encode(array('status' => false, 'message' => 'CODE FIELD REQUIRED'));
            exit;
        }
        // if (empty(trim($post->first_name))) {
        //     echo json_encode(array('status' => false, 'message' => 'FIRST NAME REQUIRED'));
        //     exit;
        // }
        // if (trim($post->second_name) === '') {
        //     echo json_encode(array('status' => false, 'message' => 'SECOND NAME REQUIRED'));
        //     exit;
        // }
        if (empty(trim($post->third_name))) {
            echo json_encode(array('status' => false, 'message' => 'THIRD NAME REQUIRED'));
            exit;
        }
        // INSERT
        $fields = array('exam_name_code','first_name', 'second_name','third_name', 'status', 'submit_by');
        
        $post->submit_by = $this->user->username;
        $post->status = $post->status_;
        // 
        $examName = $this->model('SystemData')->getExamName(array("_option" =>"object", "exam_name_code"=>$post->exam_name_code_old));
        
        $sql = '';
        if (empty($examName)) {
            $post->exam_name_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->exam_name_code, 'digit' => '000', 'prefix' => 'EXMNAME/', 'suffix' => '/'. date('y'), 'serial' => 'SCH_EXAMNAME', 'field' => 'exam_name_code', 'table' => 'sch_exam_name'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_exam_name', 'option' => ""));
            
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_exam_name', 'option' => "WHERE exam_name_code = '" . addslashes($post->exam_name_code_old)  . "' LIMIT 1"));
            // var_dump($sql);exit;
        }

        $this->db->query($sql);
        
        $result = $this->db->execute();
        // var_dump($result);exit;
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        // log
        $post_log = [];
        foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$examrate ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        if ($post->status === '1') {
            $this->db->query("UPDATE sch_exam_name SET status = '0' WHERE NOT exam_name_code = '" . $post->exam_name_code_old . "'");
            $r = $this->db->execute();
            
        }

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
            $fields = array('code','exam_name','class','class_name','subject_name','start_time','exam_date','end_time', 'submit_by', 'modify_by');
            $v->submit_by = $v->modify_by = $this->user->username;
            $sql = "SELECT t1.* FROM sch_exam_schedule t1 WHERE t1.exam_name = '". $v->exam_name ."' AND t1.class = '". $v->class ."' AND t1.class_name = '".$v->class_name."' AND t1.subject_name = '".$v->subject_name."' AND t1.start_time = '". $v->start_time ."' AND t1.end_time = '". $v->end_time ."' AND t1.exam_date = '". $v->exam_date ."' ";
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