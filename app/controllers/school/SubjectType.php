<?php

class SubjectType extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    
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
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/subject_type', $this->data);
        //
        $this->view('system/footer', $this->data);
    }

    public function _list()
    {
        //
        $subjectsType = $this->model('SystemData')->getSubjectType(array());
        echo json_encode(array('data' => $subjectsType));
        exit;
    }

    
    public function _save()
    {
        $post = $this->post;
        // var_dump($post);exit;
    
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
    
        if (empty(trim($post->subject_type))) {
            echo json_encode(array('status' => false, 'message' => 'SUBJECT NAME REQUIRED'));
            exit;
        }
        
        // INSERT
        $fields = array('subject_type', 'status');
        
        // if(empty($post->email)) $post->email = "noemail@default.com";
    
        // check record
        $subject_type = $this->model('SystemData')->getSubjectType(array("_option"=>"object", 'subject_type' => $post->subject_type));
        $sql = '';
        if (empty($subject_type)) {
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'subject_type', 'option' => ""));
            //  var_dump($sql);exit;
        }
        // UPDATE
        else {
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'subject_type', 'option' => "WHERE subject_type = '" . addslashes($post->subject_type ?? '') . "' LIMIT 1"));
        }
        $this->db->query($sql);
        
        $result = $this->db->execute();
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
    
}