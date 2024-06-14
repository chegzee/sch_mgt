<?php

class SetExams extends Controller
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
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
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
        // $this->data['classrooms'] = $this->model('SystemData')->getClassrooms(array());
        
        // $this->data['classesobj'] = $this->model('SystemData')->getClasses(array("_option" => "object"));
        // var_dump($this->data['student']);exit;
        
        //menu content
        $menu = $this->getHeader();
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
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/setExams', $this->data);
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
        $post = (object)$this->post->data; 
       
        $fields = array('code','posted_date', 'exam_name', 'exam_timer', 'subject', 'term_code', 'term', 'year', 'submit_by', 'modified_by');
        // array_diff($fields, array('level_list', 'questions'));
        // var_dump($post->code);exit;
        $post->submit_by = $post->modified_by = $this->user->username;
        $post->posted_date = date('Y-m-d H:i:s');
        if(!empty($post->code)){
            $this->db->query("UPDATE sch_question SET subject = '" . $post->subject . "' WHERE code = '" . $post->code . "' LIMIT 1");
            $this->db->execute();

            $this->db->query("UPDATE sch_question SET level_list = '" . html_entity_decode($post->level_list) . "' WHERE code = '" . $post->code . "' LIMIT 1");
            $this->db->execute();
    
            $this->db->query("UPDATE sch_question SET questions = '" . html_entity_decode($post->questions) . "' WHERE code = '" . $post->code . "' LIMIT 1");
            $res = $this->db->execute();
            
            if($res === true){
                echo json_encode(array('status' => true, 'message' => "SUCCESSFUL UPDATE", 'code'=> $post->code));

            }else{
                echo json_encode(array('status' => false, 'message' => $res, 'code'=> ''));

            }

            exit;

        }
        $post->code = $this->model('SystemData')->newAuto(array('auto_item' => $post->code, 'digit' => '0000', 'prefix' => 'Q/' , 'suffix' => '/'. date('y'), 'serial' => 'QUESTION', 'field' => 'code', 'table' => 'sch_question'));
        $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_question', 'option' => ""));
        // var_dump($sql);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        //
        $this->db->query("UPDATE sch_question SET level_list = '" . html_entity_decode($post->level_list) . "' WHERE code = '" . $post->code . "' LIMIT 1");
        $res1 = $this->db->execute();

        $this->db->query("UPDATE sch_question SET questions = '" . html_entity_decode($post->questions) . "' WHERE code = '" . $post->code . "' LIMIT 1");
        $res = $this->db->execute();
        
        if($result === true && $res === true && $res1 === true){
            echo json_encode(array('status' => true, 'message' => "SUCCESSFUL", 'code'=> $post->code));

        }else{
            echo json_encode(array('status' => false, 'message' => $res . ' '. $result. ' ' . $res1, 'code'=> ''));

        }

        // var_dump($post);
    }
}