<?php

class TeacherStd extends Controller
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
    protected $classesTch;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        // verify user
        $this->teacher = $this->model('SystemData')->verifyTeacher(array('user_log' => $this->data['params']['user_log']));
        // var_dump($this->teacher->identity_no);exit;
        $teacherClasses = $this->model('SystemData')->getTeacherClass(array("teacher_id"=> $this->teacher->identity_no));
        $this->menu = $this->getTchSidebar();
        $this->classesTch = implode("', '", $teacherClasses);
        //$ddd = $this->model('SystemData')->getStudents(array("_option"=>"class_code", "class_code"=> $classesTch));
        //var_dump($ddd);exit;
        if (empty($this->teacher)) header('Location: ' . URL_ROOT . '/system/login');
       
        $this->data['teacher'] = (array)$this->teacher;
        
        // $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
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
        // 
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/teacherStd', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $students = $this->model('SystemData')->getStudents(array("_option"=>"class_code", "class_code"=> $this->classesTch));
        echo json_encode(array('data' => $students));
        exit;
    }

}