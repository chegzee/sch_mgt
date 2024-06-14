<?php

class Term extends Controller
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
        $this->view('system/sch_term', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $terms = $this->model('SystemData')->getTerms(array());
        //var_dump($users); exit;
        echo json_encode(array('data' => $terms));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_term WHERE code = '{$post->code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'User deleted')));
            exit;
            
        }else{
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;

        }
    
        // if ($this->userAccess['system']['admin'] !== '1') {
        //     echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
        //     exit;
        // }
        
    }
    
    public function _save()
    {
        $post = $this->post;
    
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
        // var_dump($post);exit;
        if (empty($post->term)) {
            echo json_encode(array('status' => false, 'message' => 'TERM VALUE REQUIRED'));
            exit;
        }
    
        if (empty(trim($post->code)) && !empty($post->code_old)){
            echo json_encode(array('status' => false, 'message' => 'CODE VALUE REQUIRED'));
            exit;
        }
        if (empty(trim($post->start_date))) {
            echo json_encode(array('status' => false, 'message' => 'START DATE REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->end_date))) {
            echo json_encode(array('status' => false, 'message' => 'END DATE REQUIRED'));
            exit;
        }
        
        // var_dump($post);exit;
        // INSERT
        $fields = array('code','year', 'term', 'start_date', 'end_date','status', 'submit_by', 'modify_by');
        
        $post->submit_by = $post->modify_by = $this->user->username;
        
        // check record
        $terms = $this->model('SystemData')->getTerms(array());
        $terms = array_diff(array_map(function($v) use ($post) { 
            return $post->code !== $v->code && (strtotime($post->start_date) >= strtotime($v->start_date) && strtotime($post->start_date) <= strtotime($v->end_date) || strtotime($post->end_date) >= strtotime($v->start_date) && strtotime($post->end_date) <= strtotime($v->end_date));
        }, $terms), array(false));
        // var_dump($terms);exit;

        if (!empty($terms)) {
            echo json_encode(array('status' => false, 'message' => 'DATE OVERLAP INVALID'));
            exit;
        }
        // var_dump($terms);exit;
        ////////
        $term = $this->model('SystemData')->getTerm(array('code' => $post->code_old));
        
        $sql = '';
        if (empty($term)) {
            //
            $post->code = $this->model('SystemData')->newAuto(array('auto_item' => $post->code, 'digit' => '000', 'prefix' => $post->term_code_prefix.'/', 'suffix' => '', 'serial' => 'TERM', 'field' => 'code', 'table' => 'sch_term'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_term', 'option' => ""));
        }
        // UPDATE
        else {

            $fields = array_diff($fields, array('submit_by'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_term', 'option' => "WHERE code = '" . addslashes($post->code_old ?? '') . "' LIMIT 1"));
        }

        $this->db->query($sql);
        $result = $this->db->execute();
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        // log
        $post_log = [];
        foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$term ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        if ($post->status === '1') {
            ////////check for term code in the history table////////////////////
            $sql = "SELECT t1.std_code FROM sch_std_history t1 WHERE t1.term = '".$post->code."' LIMIT 1 ";
            $this->db->query($sql);
            if($this->db->rowCount() > 0){
                echo json_encode(array("status"=>false, "message"=> "TERM HAD BEEN SET"));
                exit;
            }
            
            $this->db->query("UPDATE sch_term SET status = '0' WHERE NOT code = '" . $post->code_old . "'");
            $res = $this->db->execute();

            //update students table for the current term
            if($res === true){
                $period = strtotime($post->end_date) - strtotime($post->start_date);
                
                // var_dump($checkterm);exit;
                $this->db->query("UPDATE sch_students t1 SET t1.term = '".$post->code."', t1.receipt_code='', t1.invoice_code='', t1.subject_result='{}', t1.subjects='{}', t1.social_beh='{}', t1.online_question='{}',  t1.activities='{}',  t1.attendance='{}' ");
                $res = $this->db->execute();
                //
                if($res === true){
                    $students = $this->model('SystemData')->getStudents();
                    foreach($students as $k => $v){
                        
                        //check for student history row
                        $v->submit_by = $v->modified_by = $post->submit_by;
                        $sql_history = "SELECT t1.* FROM sch_std_history t1 WHERE t1.std_code = '".$v->std_code."' AND t1.term = '".$v->term."' ";
                        $this->db->query($sql_history);
                        $student = $this->db->single();
                        // var_dump($v);exit;
                    
                        ////////save student history///////////////
                        if(empty($student)){
                            // $fields = array('std_code','branch_code','first_name', 'last_name', 'gender', 'roll', 'blood_group', 'religion', 'email', 'section','phone','parent_name', 'address','picture','birthday','class_code', 'department','term', 'status', 'submit_by');
                            $fields = array('std_code', 'branch_code', 'first_name', 'last_name', 'gender', 'roll', 'blood_group', 'religion', 'email', 'section','admission_id','phone', 'parent_code', 'address','picture','birthday','class_code','department', 'status', 'doc_path', 'term', 'receipt_code', 'invoice_code', 'submit_by', 'modified_by');
                            $sqlHistory_insert = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_std_history', 'option' => ""));
                            $this->db->query($sqlHistory_insert);
                            $this->rowCount = $this->db->execute();
                            
                        }
                    }
                }
            }
        }
        // //
        // $this->db->query("UPDATE sys_user SET access = '" . html_entity_decode($post->access) . "' WHERE username = '" . $post->username . "' LIMIT 1");
        // $this->db->execute();
        
        //
        // $user = $this->model('SystemData')->getUser(array('username' => $post->username));
        // $user->url = URL_ROOT . '/system/user/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}