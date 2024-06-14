<?php

class Notice extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        $this->data['params'] = $this->getParams();
    
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
        // var_dump($this->user);exit;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->data['term'] = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->data['tremObj']= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->data['sch_notice'] = $this->model('SystemData')->getSchNotice();
        $this->data['sch_noticePerTerm'] = $this->model('SystemData')->getSchNotice(array("option"=>"perTerm", "term_name"=>$this->data['term']->term, "term_year"=>$this->data['term']->year));
        // var_dump($this->data['term']);exit;
        
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
        $this->view('school/sch_notice', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    // public function _list()
    // {
    //     //
    //     $examGrade = $this->model('SystemData')->getAllExamGrade(array());
        
    //     // var_dump($examGrade); exit;
    //     echo json_encode(array('data' => $examGrade));
    //     exit;
    // }
    
    // public function _delete()
    // {
    //     $post = $this->post;
    //     //  var_dump($post );exit;
    //     if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
    //         $sql = "DELETE FROM sch_exam_schedule WHERE code = '{$post->code}' LIMIT 1";
    //         //var_dump($sql); exit;
            
    //         $this->db->query($sql);
    //         $result = $this->db->execute();
            
    //         if ($result !== true) {
    //             echo json_encode(array('status' => false, 'message' => $result));
    //             exit;
    //         }
            
    //         //
    //         echo json_encode(array('status' => true, 'data' => array('message' => 'EXAM deleted')));
    //         exit;
            
    //     }else{
    //         echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
    //         exit;

    //     }
    
    //     // if ($this->userAccess['system']['admin'] !== '1') {
    //     //     echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
    //     //     exit;
    //     // }
        
    // }
    
    public function _save()
    {
        $post = $this->post;
        // var_dump($post->title);exit;
    
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
    
        if (empty(trim($post->title))) {
            echo json_encode(array('status' => false, 'message' => 'TITLE NAME REQUIRED'));
            exit;
        }
        if (empty(trim($post->posted_by))) {
            echo json_encode(array('status' => false, 'message' => 'POSTED BY REQUIRED '));
            exit;
        }
        // if (empty(trim($post->date_posted))) {
        //     echo json_encode(array('status' => false, 'message' => 'POSTED DATE REQUIRED'));
        //     exit;
        // }

        // $termName = $this->data['term'] ->term ;
        // $termYear = $this->data['term'] ->year  ;
        // var_dump($term);exit;
        
        // INSERT
        $fields = array('code','title','date_posted','posted_by', 'term_name','term_year','term_code', 'details', 'color','submit_by', 'modify_by');
        $post->term_name = $this->data['term'] ->term ;
        $post->term_year = $this->data['term'] ->year ;
        $post->term_code = $this->data['term'] ->code ;
        $post->date_posted = date('y-m-d h:i:s');
        $post->posted_by = $post->posted_by_name;
        $post->submit_by = $post->modify_by = $this->user->username;
        $post->color = '#'. substr(uniqid(), -6);
        // var_dump($post->date_posted);exit;
        $schNotice = $this->model("SystemData")->getSchNotice(array("_option"=> "code", "code"=>$post->code));
        // var_dump($schNotice);exit;
        
        $sql = '';
        if (empty($schNotice)) {
            $post->code = $this->model('SystemData')->newAuto(array('auto_item' => $post->code, 'digit' => '0000', 'prefix' => 'EXM/', 'suffix' => '/'. date('y'), 'serial' => 'NOTICE', 'field' => 'code', 'table' => 'sch_notice'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_notice', 'option' => ""));
            // var_dump($sql);exit;
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by')); 
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_notice', 'option' => "WHERE code = '" . addslashes($post->code)  . "' LIMIT 1"));
            // var_dump($sql);exit;
        }

        $this->db->query($sql);
        
        $result = $this->db->execute();
        // var_dump($result);exit;
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        // log
        $post_log = [];
        foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$schNotice ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        echo json_encode(array('status' => true, 'data' => $result, "message"=> "SUCCESSFUL"));
        exit;
    }

    public function _delete(){
        $post = $this->post;
        $sql = "DELETE FROM sch_notice WHERE code = '{$post->code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => $result, "message"=> "SUCCESFUL"));
            exit;
        // var_dump($post);
    }

}