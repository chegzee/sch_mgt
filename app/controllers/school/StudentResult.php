<?php

class StudentResult extends Controller
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
        $this->student = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));
        if (empty($this->student)) header('Location: ' . URL_ROOT . '/system/login');
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
        $this->data['student'] = $this->student;
        // $this->data['student'] = $this->model('SystemData')->getStudents(array("_option" => "email", "email"=> $this->user->username));
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
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/studentResult', $this->data);
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
        $post = json_decode($this->post->data);
        foreach($post as $k => $v){
           
            $term = $this->model("SystemData")->getTerm(array("code"=>$v->term));
            $v->modified_by = $this->user->username;
            $sql = "UPDATE sch_students t1 SET t1.class_name_code = '".$v->class_code."', t1.cat_code='".$v->cat_code."', t1.modified_by='".$v->modified_by."',  t1.department ='". $v->department ."'  WHERE t1.std_code='".$v->std_code."' ";
            // if($v->digit < 12){
            //     $sql .= ", t1.department ='' ";
            // }

            // $sql .= "  WHERE t1.std_code='".$v->std_code."' ";
            // var_dump($sql);exit;
            $this->db->query($sql);
            $result = $this->db->execute();
            if($result !== true){
                echo json_encode(array('status' => false, 'data' => $result));
                exit;

            }
            
        }
        
        echo json_encode(array('status' => true, 'data' => $result));
        // var_dump($post);
    }
}