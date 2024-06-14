<?php

class Dashboard extends Controller
{
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;

    public function __construct()
    {
        $this->db = new Database;

        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();

        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        // echo json_encode($this->user);
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;

        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['term'] = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->data['sch_notice'] = $this->model('SystemData')->getSchNotice();
        $this->data['total_students'] = $this->model('SystemData')->getStudents(array("_option"=>"totalStd"));
        $this->data['female_students'] = $this->model('SystemData')->getStudents(array("_option"=>"gender", "gender"=> "FEMALE"));
        $this->data['male_students'] = $this->model('SystemData')->getStudents(array("_option"=>"gender", "gender"=> "MALE"));
        $this->data['total_teachers'] = $this->model('SystemData')->getTeachers(array("_option"=>"totalTeacher"));
        $this->data['total_parents'] = $this->model('SystemData')->getParents(array("_option"=>"totalParent"));
        $this->data['sch_noticePerTerm'] = $this->model('SystemData')->getSchNotice(array("_option"=>"perTerm", "term_code"=>$this->data['term']->code));
        $this->data['students'] = $this->model('SystemData')->getStudents(array());
        // $term = $this->data['term'];
        // var_dum($term);exit
        $this->data['payable'] = $this->model('AccountData')->getFinancialStatement(array("start_date"=> $this->data['term']->start_date, "end_date"=>$this->data['term']->end_date, "dr_cr"=>"BS", "group_name"=>"account payable"));
        $this->data['receivable'] = $this->model('AccountData')->getFinancialStatement(array("start_date"=> $this->data['term']->start_date, "end_date"=>$this->data['term']->end_date, "dr_cr"=>"BS", "group_name"=>"account receivable"));
        $this->data['sales'] = $this->model('AccountData')->getFinancialStatement(array("start_date"=> $this->data['term']->start_date, "end_date"=>$this->data['term']->end_date, "dr_cr"=>"IS", "group_name"=>"sales"));
        // var_dump($this->data['sch_noticePerTerm']);exit;
        // $this->data['sch_noticePerTerm'] = $this->model('SystemData')->getSchNotice(array("option"=>"perTerm", "term_name"=>$this->data['term']->term, "term_year"=>$this->data['term']->year));
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);

    }

    public function index()
    {
    
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/dashboard', $this->data);
        //
        $this->view('system/footer', $this->data);
    }

}