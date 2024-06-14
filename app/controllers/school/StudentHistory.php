<?php

class StudentHistory extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $examGrade;
    protected $student;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        $menu = $this->getHeader();
        if(empty($this->user)){
            $this->user = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));
            $this->std_code = $this->user->std_code;
            $menu = $this->getStdSidebar();
            // var_dump($this->std_code);exit;
        }
        // var_dump($this->std_code ?? '');exit;
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->examGrade = $this->model('SystemData')->getAllExamGrade(array());
        $this->examGradeobj = $this->model('SystemData')->getAllExamGrade(array("_option" => "object"));
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->terms= $this->model('SystemData')->getTerms(array());
        $this->examRate = $this->model('SystemData')->getExamRate(array("status" => "1"));
        $this->examName = $this->model('SystemData')->getExamName(array("status" => "1"));
        $this->socialBehaviour = $this->model('SystemData')->getSocialBeh(array());
        $this->socialKey = $this->model('SystemData')->getSocialKey(array());
        //max value in social behaviour table
        $this->max_key_val = $this->model('SystemData')->getSocialKey(array("option"=>"max_key_val"));
        //max value in exam grade percent upto table
        $this->max_percent_upto = $this->model('SystemData')->getExamGrade(array("option"=>"max_percent_upto"));
        
        //menu content
        $this->data['menu'] = $menu;
        $this->data['examGrade'] = $this->examGrade;
        $this->data['examGradeObj'] = $this->examGradeobj;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        $this->data['terms'] = (array)$this->terms;
        $this->data['examRate'] = $this->examRate;
        $this->data['examName'] = $this->examName;
        $this->data['socialBehaviour'] = $this->socialBehaviour;
        $this->data['socialKey'] = $this->socialKey;
        $this->data['max_key_val'] = $this->max_key_val;
        $this->data['max_percent_upto'] = $this->max_percent_upto;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/student_history', $this->data);
        //
        $this->view('system/footer', $this->data);
    }

    public function getStudent(){
        $this->student =$this->model("SystemData")->getStudents(array("_option"=>"std_code", "std_code"=> $this->post->std_code));
        $stdStartDate = date_create($this->student->start_date);
        $stdEndDate = date_create($this->student->end_date);
        $currentDate = date_create(date('Y-m-d'));
        $ddd = date_diff($stdStartDate, $stdEndDate);
        // var_dump(array("start"=>$this->student->start_date, "end"=> $this->student->end_date, "current"=> $currentDate));exit;
        // $days = abs((int)$ddd->format("%R%a days"));
        $days = $ddd->format("%R%a days");
        $timestampCurrent = strtotime(date('Y-m-d'));
        $timestampEnd = strtotime($this->student->end_date);
        $period = $timestampEnd - $timestampCurrent;
        // var_dump(strtotime($student->end_date));exit;
        echo json_encode(array('data' => $this->student, "period" => $period, "days"=> $days, "end_date"=>$stdEndDate));
        // exit;
        
    }
    
    public function _list()
    {
        //
        $post = $this->post;
        $studentsHistory = $this->model('SystemData')->getStudentsHistory(array("term_code"=>$post->term_code, "std_code_"=> $this->std_code));
        echo json_encode(array('data' => $studentsHistory));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_students WHERE std_code = '{$post->std_code}' LIMIT 1";
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

    public function saveSubject(){
        $post = json_decode($this->post->data);
        $std_code = $post->std_code;
        unset($post->std_code);
        $subject = json_encode($post);
        $sql = "UPDATE sch_students SET subjects = '".$subject."' WHERE std_code = '".$std_code."'";
        // var_dump($sql);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


    }
    //////
    public function saveSubjResult(){
        $post = json_decode($this->post->data);
        
        // $std_code = $post->std_code;
        // var_dump($post);exit;
        // unset($post->std_code);
        $subject_result = json_encode($post);
        $sql = "UPDATE sch_students SET subject_result = '".$subject_result."' WHERE std_code = '".$this->post->std_code."'";
        // var_dump($sql);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


    }
    //////
    public function saveSocialBehaviour(){
        
        $sql = "UPDATE sch_students SET social_beh = '".$this->post->data."' WHERE std_code = '".$this->post->std_code."'";
        // var_dump($sql);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


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

    
        if (!empty($this->post->email)  && !preg_match($this->regexp_email, $post->email)) {
            echo json_encode(array('status' => false, 'message' => 'VALID EMAIL REQUIRED'));
            exit;
        }
        if (!empty($this->post->phone)  && !preg_match($this->regexp_phone, $post->phone)) {
            echo json_encode(array('status' => false, 'message' => 'VALID PHONE REQUIRED'));
            exit;
        }
    
        if (empty(trim($post->std_code)) && !empty($post->std_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'STUDENT CODE REQUIRED'));
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
        if(empty(trim(($post->birthday)))){
            echo json_encode(array('status' => false, 'message' => 'BIRTHDAY REQUIRED'));
            exit;
        }
        if(empty(trim($post->class_name_code))) {
            echo json_encode(array('status' => false, 'message' => 'CLASS NAME REQUIRED'));
            exit;

        }
        if(empty(trim(($post->cat_code)))){
            echo json_encode(array('status' => false, 'message' => 'CLASS REQUIRED'));
            exit;

        }
        if(empty(trim(($post->parent_name)))){
            echo json_encode(array('status' => false, 'message' => 'PARENT REQUIRED'));
            exit;

        }
        if(empty(trim(($post->address)))){
            echo json_encode(array('status' => false, 'message' => 'ADDRESS REQUIRED'));
            exit;

        }
        if(empty(trim(($post->admission_id)))){
            echo json_encode(array('status' => false, 'message' => 'ADMISSION ID REQUIRED'));
            exit;

        }
        // if(empty(trim(($post->year)))){
        //     echo json_encode(array('status' => false, 'message' => 'YEAR REQUIRED'));
        //     exit;

        // }
        if(empty(trim(($post->term)))){
            echo json_encode(array('status' => false, 'message' => 'TERM REQUIRED'));
            exit;

        }
        
        // INSERT
        $fields = array('std_code','first_name', 'last_name', 'gender', 'roll', 'blood_group', 'religion', 'email', 'section','admission_id','phone','parent_name', 'address','picture','birthday','class_name_code','cat_code','department','term', 'year', 'status', 'start_date', 'end_date', 'period', 'submit_by', 'modified_by');

        $post->submit_by = $post->modified_by = $this->user->username;
         if(!empty($post->picture)) {
            $post->status = "1";
        }
        // if(empty($post->email)) $post->email = "noemail@default.com";
    
        // check record
        $student = $this->model('SystemData')->getStudent(array('std_code' => $post->std_code_old));
        // $term = $this->model('SystemData')->getTerms(array('_option' => "current"));
        $term = $this->model('SystemData')->getTerms(array("code"=> $post->term));
        $post->start_date = $term->start_date;
        $post->end_date = $term->end_date;
        $post->period = strtotime($post->end_date) - strtotime($post->start_date);
        $sql = '';
        if (empty($student)) {
            if(!empty($post->email)){
                $stdt = $this->model('SystemData')->getStudent2(array("email"=> $post->email));
                if($stdt) {
                    echo json_encode(array('status' => false, 'message' => 'EMAIL ALREADY EXIST ' . $stdt->email));
                    exit;
                }

            }
            $post->std_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->std_code, 'digit' => '000000', 'prefix' => 'STDT/', 'suffix' => '/' .date('Y'), 'serial' => 'STUDENTHISTORY', 'field' => 'std_code', 'table' => 'sch_std_history'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => ""));
            
        }
        // UPDATE
        else {
    
            // echo json_encode(array('status' => false, 'message' => $user)); exit;
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => "WHERE std_code = '" . addslashes($post->std_code_old ?? '') . "' LIMIT 1"));
            // var_dump($sql);exit;
        }
        //echo json_encode(array('status' => false, 'message' => $sql)); exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        // log
        // $post_log = [];
        // foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        // //
        // $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$student ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        // //
        // $this->db->query("UPDATE sys_user SET access = '" . html_entity_decode($post->access) . "' WHERE username = '" . $post->username . "' LIMIT 1");
        // $this->db->execute();
        
        //
        // $user = $this->model('SystemData')->getUser(array('username' => $post->username));
        // $user->url = URL_ROOT . '/system/user/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}