<?php

class QuestionAnswer extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $examGrade;
    protected $rowCount;
    protected $code;
    protected $sql;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        // var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->student = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));
        if (!$this->student) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['student'] = (array)$this->student;
    
        // $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->examGrade = $this->model('SystemData')->getAllExamGrade(array());
        $this->examGradeobj = $this->model('SystemData')->getAllExamGrade(array("_option" => "object"));
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->examRate = $this->model('SystemData')->getExamRate(array("status" => "1"));
        $this->socialBehaviour = $this->model('SystemData')->getSocialBeh(array());
        $this->socialKey = $this->model('SystemData')->getSocialKey(array());
        //max value in social behaviour table
        $this->max_key_val = $this->model('SystemData')->getSocialKey(array("option"=>"max_key_val"));
        //max value in exam grade percent upto table
        $this->max_percent_upto = $this->model('SystemData')->getExamGrade(array("option"=>"max_percent_upto"));
        $this->data['levelsobj'] = $this->model('SystemData')->getCategories(array("_option" => "object"));
        $this->data['classesobj'] = $this->model('SystemData')->getClasses(array("_option" => "object"));
        $this->data['student'] = $this->model('SystemData')->getStudents(array("_option" => "email", "email"=> $this->user->username));
        $this->data['student'] = $this->student;
        $this->data['classrooms'] = $this->model('SystemData')->getClassrooms(array());
        // var_dump($this->data['student']);exit;
        
        //menu content
        $menu = $this->getStdSidebar();
        $this->data['menu'] = $menu;
        $this->data['examGrade'] = $this->examGrade;
        $this->data['examGradeObj'] = $this->examGradeobj;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        $this->data['examRate'] = $this->examRate;
        $this->data['socialBehaviour'] = $this->socialBehaviour;
        $this->data['socialKey'] = $this->socialKey;
        $this->data['max_key_val'] = $this->max_key_val;
        $this->data['max_percent_upto'] = $this->max_percent_upto;
        // var_dump($this->post);exit;
        $this->post = (object)filter_input_array(INPUT_GET);
        $question = $this->model('SystemData')->getQuestions(array("option"=>"obj", "code"=>$this->post->q_code));
        // var_dump($question->subject);exit;
        $this->data['question'] = ($question->questions);
        $this->data['question_meta'] = array("code"=> $question->code, "level_list" => $question->level_list, "exam_name" => $question->exam_name, "exam_timer" => $question->exam_timer, "posted_date"=> $question->posted_date, "subject_name"=> $question->subject);
        // var_dump($this->data['question_meta']); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/question_answer', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $students = $this->model('SystemData')->getStudents(array());
        //var_dump($users); exit;
        echo json_encode(array('data' => $students));
        exit;
    }

    public function _save(){
        // $post = json_encode($this->post);
        $post = (object)filter_input_array(INPUT_POST);
        $post->submit_date = date("y-m-d H:i:s");
        $std_code = $post->std_code;
        $question_code = $post->question_code;
        $questionStudents = $this->model('SystemData')->getQuestions(array("option"=>"obj2", "code"=>$question_code));
        $studentOnlineQuestion = $this->model('SystemData')->getStudent(array("option"=>"obj2", "std_code"=>$std_code));
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code"=>$std_code));
        $student_history_onlineQuestion = $this->model('SystemData')->getStudentsHistory(array("term"=>$student->term, "std_code"=>$student->std_code));
        // var_dump($student_history_onlineQuestion->online_question);exit;
        ////////////////////////////////////////////////////////////////////////
        if(!empty($questionStudents->students)){
            $question_students = json_decode($questionStudents->students);
            // var_dump($question_students);exit;
            $student_OnlineQuestion = json_decode($studentOnlineQuestion->online_question);
            $student_HistoryOnlineQuestion = json_decode($student_history_onlineQuestion->online_question);
            // var_dump($student_participant);exit;
            foreach($questionStudents as $k =>$v){
                if($v->std_code === $std_code){
                    echo json_encode(array("status"=> false, "message"=>"THIS QUESTION HAS BEEN DONE"));
                    exit;
                }
            }
            array_push($question_students, $post);
            array_push($student_OnlineQuestion, $post);
            array_push($student_HistoryOnlineQuestion, $post);
            $sql = "UPDATE sch_question t1 SET students='".json_encode($question_students)."'  WHERE t1.code ='".$question_code."'  ";
            $this->db->query($sql);
            $result = $this->db->execute();
            if($result !== true){
                echo json_encode(array("status"=> false, "message"=> $result));
                exit;
    
            }
            ///////////////////////////////////////////////
            $sql = "UPDATE sch_students t1 SET online_question='".json_encode($student_OnlineQuestion)."'  WHERE t1.std_code ='".$student->std_code."'  ";
            $this->db->query($sql);
            $result = $this->db->execute();
            ///////////////////////////////////////////////
            $sql = "UPDATE sch_std_history t1 SET online_question='".json_encode($student_HistoryOnlineQuestion)."'  WHERE t1.std_code ='".$student->std_code."' AND t1.term = '".$student->term."' ";
            $this->db->query($sql);
            $result = $this->db->execute();
            

        }
        echo json_encode(array("status"=> true, "message"=> "SUCCESSFUL", "data"=> ""));
    }

    //return all the question with scores student has done online
    public function onlineResult(){
        $post = (object)filter_input_array(INPUT_POST);
        
        $student = $this->model('SystemData')->getStudent(array("option"=>"obj2", "std_code"=>$post->std_code));
        $data = json_decode($student->online_question);

        echo json_encode(array('data' => $data));
        // var_dump($data);exit;
    }
}