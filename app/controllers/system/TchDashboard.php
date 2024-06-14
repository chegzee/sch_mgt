<?php

class TchDashboard extends Controller
{
    protected $data;
    protected $user;
    protected $db;
    protected $teacher;
    
    protected $post;

    public function __construct()
    {
        $this->db = new Database;

        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        $this->teacher = $this->model('SystemData')->verifyTeacher(array('user_log' => $this->data['params']['user_log']));
        // echo json_encode($this->user);exit;
        if (empty($this->teacher)) header('Location: ' . URL_ROOT . '/system/login');
        // var_dump($teacherClasses);exit;
        $this->data['teacher'] = (array)$this->teacher;
        

        //menu content
        $menu = $this->getTchSidebar();
        
        $this->data['term'] = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->data['sch_notice'] = $this->model('SystemData')->getSchNotice();
        $this->data['total_students'] = $this->model('SystemData')->getStudents(array("_option"=>"totalStd"));
        $this->data['female_students'] = $this->model('SystemData')->getStudents(array("_option"=>"gender", "gender"=> "FEMALE"));
        $this->data['male_students'] = $this->model('SystemData')->getStudents(array("_option"=>"gender", "gender"=> "MALE"));
        $this->data['total_teachers'] = $this->model('SystemData')->getTeachers(array("_option"=>"totalTeacher"));
        $this->data['total_parents'] = $this->model('SystemData')->getParents(array("_option"=>"totalParent"));
        $this->data['sch_noticePerTerm'] = $this->model('SystemData')->getSchNotice(array("_option"=>"perTerm", "term_code"=>$this->data['term']->code));
        // $this->data['sch_noticePerTerm'] = $this->model('SystemData')->getSchNotice(array("option"=>"perTerm", "term_name"=>$this->data['term']->term, "term_year"=>$this->data['term']->year));
        $this->data['menu'] = $menu;
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);

    }

    public function index()
    {
    
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/tch_dashboard', $this->data);
        //
        $this->view('system/footer', $this->data);
    }

}