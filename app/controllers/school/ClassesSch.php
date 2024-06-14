<?php

class ClassesSch extends Controller
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
        // $this->post =(object)json_decode(file_get_contents("php://input")) ;
        //   var_dump($this->post->subject_type); exit;
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/classes_sch', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $classes = $this->model('SystemData')->getClasses(array());
        // var_dump($classes); exit;
        echo json_encode(array('data' => $classes));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM sch_classes WHERE class_code = '{$post->class_code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'CLASS deleted')));
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
        $post = json_decode($this->post->classes);
        $fields = array('class_code', 'class_name','cat_code', 'submit_by', 'modified_by');
        // var_dump($post);exit;

        foreach($post as $k => $v){
            if(empty($v->class_name)) continue;
            $v->modified_by = $this->user->username;
            $v->submit_by = $this->user->username;
            $v->class_code = $this->model('SystemData')->newAuto(array('auto_item' => $v->class_code, 'digit' => '0000', 'prefix' => 'CL', 'suffix' => '/'. date('y'), 'serial' => 'SCH_CLASSES', 'field' => 'class_code', 'table' => 'sch_classes'));
            // var_dump($v);exit;
            $class = $this->model('SystemData')->getClasses(array("class_code"=> $v->class_code));
            if(!empty($class)){
                $fields = array_diff($fields, array('submit_by'));
                $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_classes', 'option' => "WHERE class_code = '".$v->class_code."' LIMIT 1"));
                $this->db->query($sql);
                $this->result = $this->db->execute();
                continue;
                // var_dump($sql);exit;
            } 

            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_classes', 'option' => ""));
            $this->db->query($sql);
            $this->result = $this->db->execute();
            // var_dump($result);exit;
        }
        
        echo json_encode(array("status"=>$this->result));
        
    
    }
    
    // public function _save()
    // {
    //     $post = $this->post;
    //     // var_dump($post);exit;
    
    //     if (!empty($post)) {
    //         foreach ($post as $k => $v) {
    //             // int
    //             if (in_array($k, $this->column_int)) {
    //                 $post->{$k} = (int)preg_replace('/[^0-9.]/', '', $v);
    //             }
    //             // float
    //             else if (in_array($k, $this->column_float)) {
    //                 $post->{$k} = (float)preg_replace('/[^0-9.]/', '', $v);
    //             }
    //             // string
    //             else
    //                 $post->{$k} = trim($v);
    //         }
    //     }
    
    //     if (empty(trim($post->class_name))) {
    //         echo json_encode(array('status' => false, 'message' => 'CLASS NAME REQUIRED'));
    //         exit;
    //     }
        
        
    //     // INSERT
    //     $fields = array('class_code', 'cat_code', 'class_name', 'submit_by', 'modified_by');
        
    //     $post->submit_by = $post->modified_by = $this->user->username;
    //     $class = $this->model('SystemData')->getClass(array("class_code"=>$post->class_code_old));
        
    //     // var_dump($class);exit;
    //     //  var_dump($post);exit;
    //     $sql = '';
    //     if (empty($class)) {
    //         $post->class_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->class_code, 'digit' => '0000', 'prefix' => 'CL', 'suffix' => '/'. date('y'), 'serial' => 'SCH_CLASSES', 'field' => 'class_code', 'table' => 'sch_classes'));
    //         $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_classes', 'option' => ""));
    //         // var_dump($post->sub_code);exit;
    //     }
    //     // UPDATE
    //     else {

    //         $fields = array_diff($fields, array('submit_by')); 
    //         $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_classes', 'option' => "WHERE class_code = '" . addslashes($post->class_code_old)  . "' LIMIT 1"));
    //         // var_dump($sql);exit;
    //     }

    //     $this->db->query($sql);
        
    //     $result = $this->db->execute();
    //     if ($result !== true) {
    //         echo json_encode(array('status' => false, 'message' => $result));
    //         exit;
    //     }
    
    //     // log
    //     $post_log = [];
    //     foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
    //     //
    //     $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$class ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
    //     echo json_encode(array('status' => true, 'data' => $result));
    //     exit;
    // }
}