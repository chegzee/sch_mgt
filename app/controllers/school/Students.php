<?php

class Students extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $examGrade;
    protected $products;
    protected $rowCount;
    protected $code;
    protected $sql;
    protected $result;
    protected $student_code;
    protected $menu;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        $this->menu = $this->getHeader();
        if(empty($this->user)){
            $this->user = $this->model('SystemData')->verifyTeacher(array('user_log' => $this->data['params']['user_log']));
        }
        // var_dump($this->student_code);exit;
        if (empty($this->user)) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
        
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->examGrade = $this->model('SystemData')->getAllExamGrade(array());
        $this->examGradeobj = $this->model('SystemData')->getAllExamGrade(array("_option" => "object"));
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->examRate = $this->model('SystemData')->getExamRate(array("status" => "1"));
        $this->examName = $this->model('SystemData')->getExamName(array("status" => "1"));
        $this->socialBehaviour = $this->model('SystemData')->getSocialBeh(array());
        $this->socialKey = $this->model('SystemData')->getSocialKey(array());
        $this->products = $this->model('SystemData')->getProducts(array());

        //max value in social behaviour table
        $this->max_key_val = $this->model('SystemData')->getSocialKey(array("option"=>"max_key_val"));
        //max value in exam grade percent upto table
        $this->max_percent_upto = $this->model('SystemData')->getExamGrade(array("option"=>"max_percent_upto"));
        $this->data['levelsobj'] = $this->model('SystemData')->getCategories(array("_option" => "object"));
        $this->data['classesobj'] = $this->model('SystemData')->getClasses(array("_option" => "object"));
        $this->data['classesobj2'] = $this->model('SystemData')->getClasses(array("_option2" => "object"));
        $this->data['parentObj'] = $this->model('SystemData')->getParents(array("_option2" => "object"));
        $this->data['branch'] = $this->model('SystemData')->getBranches(array("_option2" => "object"));
        // $this->data['classroomsObj'] = $this->model('SystemData')->getclassrooms(array("_option" => "object"));
        // $this->data['classrooms'] = $this->model('SystemData')->getClassrooms(array());
        
        //menu content
        $this->data['menu'] = $this->menu;
        $this->data['examGrade'] = $this->examGrade;
        $this->data['examGradeObj'] = $this->examGradeobj;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        $this->data['examRate'] = $this->examRate;
        $this->data['socialBehaviour'] = $this->socialBehaviour;
        $this->data['products'] = $this->products;
        $this->data['socialKey'] = $this->socialKey;
        $this->data['max_key_val'] = $this->max_key_val;
        $this->data['max_percent_upto'] = $this->max_percent_upto;
        $this->data['examName'] = $this->examName;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/students', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        // $students = $this->model('SystemData')->getStudents(array( "student_code" => $this->student_code ?? '[]'));
        $students = $this->model('SystemData')->getStudents(array( ));
        echo json_encode(array('data' => $students));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $std_code = implode("', '", $post->std_code);
            
            $sql = "SELECT t1.std_code, t1.term FROM sch_students t1 WHERE t1.std_code IN ('{$std_code}')";
            $this->db->query($sql);
            // var_dump($this->db->resultSet());exit;
            foreach ($this->db->resultSet() ?? [] as $k => $v) {
                $sql = "DELETE FROM sch_students WHERE std_code = '{$v->std_code}' LIMIT 1";
                $this->db->query($sql);
                $this->result = $this->db->execute();
                // var_dump($this->result);exit;
                // if(){

                // }
                // $sql = "DELETE FROM sch_std_history WHERE std_code = '{$v->std_code}' AND term = '{$v->term}' LIMIT 1";
                // $this->db->query($sql);
                // $this->result = $this->db->execute();
                // if ($result !== true) continue;
                
                if ($this->result !== true) {
                    echo json_encode(array('status' => $this->result));
                    exit;
                }
             }
            //var_dump($sql); exit;
            
            echo json_encode(array('status' => false, 'data' => $this->result ));
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
        $student_code = $this->post->std;
        
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $student_code['std_code']));
        $subject = json_encode($post);
        $sql = "UPDATE sch_students SET subjects = '".$subject."' WHERE std_code = '".$student->std_code."'";
        // var_dump($sql);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if($result === true){
            $sql_history = "UPDATE sch_std_history t1  SET subjects = '".$subject."' WHERE t1.std_code = '".$student->std_code."' AND t1.term = '".$student->term."' ";
        //    var_dump($sql_history);exit;
            $this->db->query($sql_history);
            $this->db->execute();
        }
         //
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


    }
    //////
    public function saveSubjResult(){
        // var_dump($this->post);exit;
        $post = json_decode($this->post->data);
        $student_code = $this->post->std;
        
        $subject_result = json_encode($post);
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $student_code['std_code']));
        $sql = "UPDATE sch_students SET subject_result = '".$subject_result."' WHERE std_code = '".$student->std_code."'";
        // 
        $this->db->query($sql);
        
        $result = $this->db->execute();
        // var_dump($result);exit;
        
        $sql_history = "UPDATE sch_std_history t1  SET subject_result = '".$subject_result."' WHERE t1.std_code = '".$student->std_code."' AND t1.term = '".$student->term."' ";
        $this->db->query($sql_history);
         $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


    }
    //////
    public function saveSocialBehaviour(){
        $student_code = $this->post->std;
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $student_code['std_code']));
        $sql = "UPDATE sch_students SET social_beh = '".$this->post->social_beh."' WHERE std_code = '".$student->std_code."'";
        
        $this->db->query($sql);
        $result = $this->db->execute();
        //update history
        $sql_history = "UPDATE sch_std_history t1  SET social_beh = '".$this->post->social_beh."' WHERE t1.std_code = '".$student->std_code."' AND t1.term = '".$student->term."' ";
        $this->db->query($sql_history);
         $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));

    }

    public function saveTableAttendance(){
        $post = $this->post;
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $post->std_code));
        // var_dump($student);exit;
        $sql = "UPDATE sch_students SET attendance = '".$this->post->attendance."' WHERE std_code = '".$post->std_code."'";
        
        $this->db->query($sql);
        $result = $this->db->execute();
        //update history
        $sql_history = "UPDATE sch_std_history t1  SET attendance = '".$this->post->attendance."' WHERE t1.std_code = '".$student->std_code."' AND t1.term = '".$student->term."' ";
        $this->db->query($sql_history);
         $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => $result));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));
        // var_dump($post);

    }
    
    ////////save student///////////////////
    public function _save()
    {
        $post = $this->post;
        $post->term = $this->term->code;
        $post->year = $this->term->year;
        // var_dump($post->term);exit;
    
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
        if (empty(trim($post->branch_code))) {
            echo json_encode(array('status' => false, 'message' => 'BRANCH NAME REQUIRED'));
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
        if(empty(trim($post->class_code))) {
            echo json_encode(array('status' => false, 'message' => 'CLASS NAME REQUIRED'));
            exit;

        }

        // if(empty(trim(($post->parent_name)))){
        //     echo json_encode(array('status' => false, 'message' => 'PARENT REQUIRED'));
        //     exit;

        // }
        if(empty(trim(($post->address)))){
            echo json_encode(array('status' => false, 'message' => 'ADDRESS REQUIRED'));
            exit;

        }
        // if(empty(trim(($post->admission_id)))){
        //     echo json_encode(array('status' => false, 'message' => 'ADMISSION ID REQUIRED'));
        //     exit;
        // }
        
        if (empty(trim($post->doc_path))) {
            $post->doc_path = $this->model('SystemData')->GUIDv4();
        }
        
        // INSERT
        $fields = array('std_code', 'branch_code', 'first_name', 'last_name', 'gender', 'roll', 'blood_group', 'religion', 'email', 'section','admission_id','phone', 'parent_code', 'address','picture','birthday','class_code','department', 'status', 'doc_path', 'term', 'submit_by', 'modified_by');
       
        //please dont remove this
        // if(($timestampEnd - $timestampCurrent) < 0){
        //     $fields = array("std_code", "year", "term", "submit_by");
        //     $this->student->sbumit_by = $this->user->username;
        //     if(empty($this->student->subject_result)){
        //         echo json_encode(array('data' => $this->student));exit;

        //     }
        //     $sql = $this->model('SystemData')->sqlQuery(array('array' => array($this->student), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => ""));
        //     // var_dump($this->student->subject_result);exit;
        //     $this->db->query($sql);
        //     $res = $this->db->execute();
        //     if($res === true){
        //         $sql_ = "UPDATE sch_std_history SET subjects = '".$this->student->subjects."', subject_result = '".$this->student->subject_result."', social_beh= '".$this->student->social_beh."' WHERE std_code = '".$this->post->std_code."' ";
        //         $this->db->query($sql_);
        //         $res_ = $this->db->execute();
        //         $sql__ = "UPDATE sch_students SET subject_result = NULL, social_beh= NULL WHERE std_code = '".$this->post->std_code."' ";
        //         $this->db->query($sql__);
        //         $res_ = $this->db->execute();
        //         $this->student =$this->model("SystemData")->getStudents(array("_option"=>"std_code", "std_code"=> $this->post->std_code));

        //     }

        // }
       
        $post->submit_by = $post->modified_by = $this->user->username;
        //  if(!empty($post->picture)) {
        //     $post->status = "1";
        // }
        $post->status = "1";
        // check record
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", 'std_code' => $post->std_code_old));
        $sql = '';
        
       //INSERT STUDENT & HISTORY
        if (empty($student)) {
            if(!empty($post->email)){
                ////////return student by email///////////////
                $stdt = $this->model('SystemData')->getStudent2(array("email"=> $post->email));
                if($stdt) {
                    echo json_encode(array('status' => false, 'message' => 'EMAIL ALREADY EXIST ' . $stdt->email));
                    exit;
                }

            }
            $post->std_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->std_code, 'digit' => '000000', 'prefix' => 'STDT/', 'suffix' => '/' .date('Y'), 'serial' => 'STUDENTS', 'field' => 'std_code', 'table' => 'students'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_students', 'option' => ""));
            $this->db->query($sql);
            $this->result = $this->db->execute();
            ////////////////////////////////////////////
            if($this->result === true){
                $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", 'std_code' => $post->std_code));
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($student), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => ""));
                $this->db->query($sql);
                $this->result = $this->db->execute();

            }
            ////////////////////////////////////////////
            $post_log = [];
            foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
            $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'INSERT', 'remarks' => json_encode($post_log)]);
            ///////////////////////////////////////////////
            if($this->result === true){
                echo json_encode(array('status' => $this->result, "std_code"=>$student->std_code));
                exit;

            }
            echo json_encode(array('status' => false, 'message' => $this->result, "std_code"=>$student->std_code));
            exit;
        }
        // UPDATE STUDENT & HISTORY
        else {
            
            $fields = array_diff($fields, array('submit_by'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_students', 'option' => "WHERE std_code = '" . addslashes($post->std_code_old ?? '') . "' LIMIT 1"));
            $this->db->query($sql);
            $this->result = $this->db->execute();
            ////////////////////////////////////////////////
            if($this->result === true){
                $sql_history = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => "WHERE std_code = '" . addslashes($post->std_code_old ?? '') . "' AND term='".addslashes($post->term ?? '')."' LIMIT 1"));
                $this->db->query($sql_history);
                $this->result = $this->db->execute();

            }
            /////////////////////////////////////////////////
            foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
            $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'UPDATE', 'remarks' => json_encode($post_log)]);
            /////////////////////////////////////////////////
            if($this->result === true){
                echo json_encode(array('status' => $this->result, "std_code"=>$student->std_code));
                exit;

            }
            echo json_encode(array('status' => false, 'message' => $this->result, "std_code"=>$post->std_code_old));
            exit;
        }
        // $this->db->query($sql);
        // $result = $this->db->execute();
        
        // $sql_history = "UPDATE sch_std_history t1  SET t1.first_name = '".$post->first_name."', t1.last_name = '".$post->last_name."',
        //  t1.gender = '".$post->gender."', t1.roll = '".$post->roll."', t1.blood_group = '".$post->blood_group."',
        //   t1.religion = '".$post->religion."', t1.email = '".$post->email."', t1.section = '".$post->section."',
        //    t1.admission_id = '".$post->admission_id."', t1.parent_name = '".$post->parent_name."', t1.address = '".$post->address."',
        //     t1.picture = '".$post->picture."', t1.birthday = '".$post->birthday."', t1.class_code = '".$post->class_code."',
        //      t1.cat_code = '".$post->cat_code."', t1.phone = '".$post->phone."', t1.department = '".$post->department."',
        //       t1.status = '".$post->status."', t1.submit_by = '".$post->submit_by."'  WHERE t1.std_code = '".$post->std_code."' ";
              
        // $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", 'std_code' => $post->std_code_old));
        // $history = clone $student;
        // if($result === true){
        //    // $sql_history = $this->model('SystemData')->sqlQuery(array('array' => array($student), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => "WHERE std_code = '" . addslashes($post->std_code_old ?? '') . "' LIMIT 1"));
        //     $this->db->query($sql_history);
        //     $result = $this->db->execute();
        //     // var_dump($result);exit;
        // }
        // //
        // if ($result !== true) {
        //     echo json_encode(array('status' => false, 'message' => $result));
        //     exit;
        // }
    
        // log
        // $post_log = [];
        // foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        // //
        // $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$student ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
    
        // echo json_encode(array('status' => true, 'data' => $result));
        // exit;
    }

    //for saving student from excel
    public function __save()
    {
        $post = json_decode($this->post->data);
        $key = '';
        // var_dump($post);exit;
        
        foreach($post as $k => $v){
           
            $term = $this->model("SystemData")->getTerms(array("_option" => "current"));
            // $term = $this->model("SystemData")->getTerm(array("code"=>$v->term));"_option" => "current"
            // var_dump($v);exit;
            // $v->start_date = $term->start_date;
            // $v->end_date = $term->end_date;
            // $v->period = strtotime($v->end_date) - strtotime($v->start_date);
            // INSERT
            $key = $k;
            $fields = array('std_code','first_name', 'last_name', 'gender', 'roll', 'blood_group', 'religion', 'email', 'section','phone','parent_code', 'address','picture','birthday','class_code','department','branch_code','term', 'status', 'submit_by', 'modified_by');
            $v->submit_by = $v->modify_by = $this->user->username;
            $v->term = $term->code;
            $sql = "SELECT t1.* FROM sch_students t1 WHERE t1.first_name = '". $v->first_name ."' AND t1.last_name = '". $v->last_name ."' AND t1.class_code='".$v->class_code."' AND t1.term = '".$term->code."' ";
            
            $this->db->query($sql);
            $rowCount = $this->db->rowCount();

            $sql = '';
            if (empty($rowCount)) {
                
                if(empty($v->class_code)){
                    echo json_encode(array('status' => false, 'message' => 'CLASS VALUE REQUIRED'));
                    exit;
    
                }
                $code_auto = "AUTO";
                $this->code = $this->model('SystemData')->newAuto(array('auto_item' => $code_auto, 'digit' => '000000', 'prefix' => 'STD/', 'suffix' => '/' .date('y'), 'serial' => 'STUDENTS', 'field' => 'std_code', 'table' => 'sch_students'));
                $v->std_code = $this->code;
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_students', 'option' => ""));
                $this->db->query($sql);
                $result = $this->db->execute();

                ////////////for sch std history/////////////////////
                if($result === true){
                    $sql_history = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => ""));
                    $this->db->query($sql_history);
                    $this->db->execute();
                }

                if ($result !== true) {
                    echo json_encode(array('status' => false, 'key'=> $key, 'message' => $result));
                    exit;
                }
            }else if($rowCount > 0){
                continue;
                // echo json_encode(array('status' => false, 'message' => "STUDENT ALREADY EXIST", "rowNo"=> $key));
                // exit;
            }
            
        }
        
        echo json_encode(array('status' => true, 'data' => $result));
        // exit;
    }

    public function activities(){
        $post = $this->post;
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $post->std_code));
        
        $sql = "UPDATE sch_students SET activities ='".$post->activities."' WHERE std_code='".$student->std_code."' ";
        $this->db->query($sql);
        $res = $this->db->execute();
        if($res === true){
            $sql = "UPDATE sch_std_history SET activities ='".$post->activities."' WHERE std_code='".$post->std_code."'  AND term = '".$student->term."' ";
            $this->db->query($sql);
            $res = $this->db->execute();
            // var_dump($res);exit;
            echo json_encode(array('status' => $res)); 
            exit;
        }
        echo json_encode(array('status' => false, "message"=>$res)); 
        // var_dump($res);exit;
    }

    public function saveMultipleResult(){
        $post = $this->post->result;
        $res = json_decode($post);
        // var_dump($res);exit;
        foreach($res as $k => $v){
            //$student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $v['std_code']));
            $sql = "UPDATE sch_students SET subject_result = '".$v->subj_result."' WHERE std_code = '".$v->std_code."'";
            // 
            $this->db->query($sql);
            $this->result = $this->db->execute();
            // var_dump($result);exit;
            if($this->result == true){
                $sql_history = "UPDATE sch_std_history t1  SET subject_result = '".$v->subj_result."' WHERE t1.std_code = '".$v->std_code."' AND t1.term = '".$v->term_code."' ";
                $this->db->query($sql_history);
                $this->db->execute();

            }

        }
        echo json_encode(array("status"=>$this->result, "message"=> $this->result));
        exit;
    }

}