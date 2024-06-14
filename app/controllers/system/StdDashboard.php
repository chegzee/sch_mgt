<?php

class StdDashboard extends Controller
{
    protected $data;
    protected $user;
    protected $db;
    protected $student;
    
    protected $post;

    public function __construct()
    {
        $this->db = new Database;

        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        // echo json_encode(array("message"=> $this->data['params']));

        // verify user
        $this->student = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));
        
        
        if (empty($this->student)) header('Location: ' . URL_ROOT . '/system/login');
        // $this->student = $this->model('SystemData')->getStudentsHistory(array("_option"=>"email", "email" => $this->student->std_code));
        // if (!$this->student) header('Location: ' . URL_ROOT . '/system/login');
        // $this->data['user'] = (array)$this->student;
        $this->data['student'] = (array)$this->student;
        $this->data['term'] = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->data['sch_notice'] = $this->model('SystemData')->getSchNotice();
        $this->data['sch_noticePerTerm'] = $this->model('SystemData')->getSchNotice(array("option"=>"perTerm", "term_name"=>$this->data['term']->term, "term_year"=>$this->data['term']->year));
        //  var_dump($this->data['student']);exit;

        //menu content
        $menu = $this->getStdSidebar();
        $this->data['menu'] = $menu;
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);

    }

    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/std_dashboard', $this->data);
        //
        $this->view('system/footer', $this->data);
    }

}