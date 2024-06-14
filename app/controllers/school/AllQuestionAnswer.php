<?php

class AllQuestionAnswer extends Controller
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
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        // var_dump($this->data['student']);exit;
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        
        $this->post = (object)filter_input_array(INPUT_GET);
        
        // var_dump($this->data['question_meta']); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/allQuestionAnswer', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $questions = $this->model('SystemData')->getQuestions(array("option"=>"all"));
        //var_dump($users); exit;
        echo json_encode(array('data' => $questions));
        exit;
    }

    public function _save(){
        // $post = json_encode($this->post);
        $post = (object)filter_input_array(INPUT_POST);
        $post->submit_date = date("y-m-d H:i:s");
        $std_code = $post->std_code;
        $question_code = $post->question_code;
        $question = $this->model('SystemData')->getQuestions(array("option"=>"obj2", "code"=>$question_code));
        $student = $this->model('SystemData')->getStudent(array("option"=>"obj2", "std_code"=>$std_code));
        // var_dump($student);exit;
        /////
        if(!empty($question->students)){
            $student_participant = json_decode($question->students);
            foreach($student_participant as $k =>$v){
                if($v->std_code === $std_code){
                    echo json_encode(array("status"=> false, "message"=>"THIS QUESTION HAS BEEN DONE"));
                    exit;
                }
                array_push($student_participant, $post);
                // var_dump($v);

            }
            $sql = "UPDATE sch_question t1 SET students='".json_encode($student_participant)."'  WHERE t1.code ='".$question_code."'  ";
            $this->db->query($sql);
            $result = $this->db->execute();
            if($result !== true){
                echo json_encode(array("status"=> false, "message"=> $result));
                exit;
    
            }

        }else if(empty($question->students)){
            $a = array();
            array_push($a, $post);
            $sql = "UPDATE sch_question t1 SET students='".json_encode($a)."'  WHERE t1.code ='".$question_code."'  ";
            $this->db->query($sql);
            $result = $this->db->execute();
            if($result !== true){
                echo json_encode(array("status"=> false, "message"=> $result));
                exit;
    
            }

        }
        ////////////////////////////////////////////////////////////////////////
        if(empty($student->online_question)){
            $a = array();
            array_push($a, $post);
            $sql = "UPDATE sch_students t1 SET online_question='".json_encode($a)."'  WHERE t1.std_code ='".$std_code."'  ";
            $this->db->query($sql);
            $result = $this->db->execute();
            if($result !== true){
                echo json_encode(array("status"=> false, "message"=> $result));
                exit;
            }

        }
        else if(!empty($student->online_question)){
            $online = $student->online_question;
            $participant = json_decode($online);
            // var_dump($online);exit;
            
            array_push($participant, $post);
            //
            $sql = "UPDATE sch_students t1 SET online_question='".json_encode($participant)."'  WHERE t1.std_code ='".$std_code."'  ";
            $this->db->query($sql);
            $result = $this->db->execute();
            if($result !== true){
                echo json_encode(array("status"=> false, "message"=> $result));
                exit;
    
            }

        }
        echo json_encode(array("status"=> true, "message"=> "SUCCESSFUL", "data"=> ""));
    }

    public function onlineResult(){
        $post = (object)filter_input_array(INPUT_POST);
        
        $student = $this->model('SystemData')->getStudent(array("option"=>"obj2", "std_code"=>$post->std_code));
        $data = json_decode($student->online_question);

        echo json_encode(array('data' => $data));
        // var_dump($data);exit;
    }
}