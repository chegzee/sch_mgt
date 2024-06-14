<?php

class SysMapping extends Controller
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
        $this->data['categories'] = $this->model('SystemData')->getCategories();
        $this->data['categories_object'] = $this->model('SystemData')->getCategories(array("_option"=> "object"));
        $this->data['classes'] = $this->model('SystemData')->getClasses(array());
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['term'] = (array)$this->term;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        // var_dump($this->post);exit;
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/sysMapping', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $post = $this->post;
        $res = $this->model('SystemData')->getStudentSubjects(array("class_code"=> $post->class_code));
        echo json_encode(array('data' => $res));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql2 = "";
            $sql = "DELETE FROM sch_mapping  WHERE auto_id = '" . $post->auto_id . "' LIMIT 1 ";
            $this->db->query($sql);
            $result = $this->db->execute();
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            $sql = "DELETE FROM sch_subject  WHERE sub_code = '" . $post->subj_code . "' LIMIT 1 ";
            $this->db->query($sql);
            $result2 = $this->db->execute();
            if ($result2 !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'DELETION SUCCESSFUL')));
            exit;
            
        }else{
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
    }

    public function getMapping()
    {
        //
        $post = $this->post;
        $sql = "SELECT t1.*, CONCAT(t3.first_name, ' ', t3.last_name) teacher, t2.cat_name, t4.class_name, t5.subject_name FROM sch_mapping t1 
        LEFT JOIN (SELECT t41.* FROM sch_classes t41)t4 ON t1.class_code = t4.class_code 
        LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t4.cat_code = t2.cat_code 
        LEFT JOIN (SELECT t51.* FROM sch_subject t51)t5 ON t1.subject_name_code = t5.sub_code 
        LEFT JOIN (SELECT t31.identity_no, t31.first_name, t31.last_name FROM sch_teacher t31)t3 ON t1.teacher_code = t3.identity_no
         WHERE 1=1 AND t1.class_code = '". $post->class_code . "' ";
        
        // if($post->department === 'SCIENCE'){
        //     $sql .= "AND t1.science = '1' ";
        //     // -- AND t1.sci_compulsory = 'compulsory' OR t1.sci_elective = 'elective' ";
        // }
        // if($post->department === 'COMMERCIAL'){
        //     $sql .= "AND t1.commercial = '1' ";
        //     // AND t1.comm_compulsory = 'compulsory' OR t1.comm_elective = 'elective'  ";
        // }
        // if($post->department === 'ART'){
        //     $sql .= "AND t1.art = '1'";
        //     // -- AND t1.art_compulsory = 'compulsory' OR t1.art_elective = 'elective'  ";
        // }

        $this->db->query($sql);
        $res = $this->db->resultSet();
        // var_dump($sql); exit;
        echo json_encode(array('data' => $res));
        exit;

    }
    
    public function _save()
    {
        $post = json_decode($this->post->data);
        $compulsory_subject = ($this->post->compulsory_subj);
        // var_dump($compulsory_subject);exit;

        foreach($post as $k => $v){
            // INSERT
            $fields = array('class_code', 'subject_name_code', 'teacher_code','science', 'jnr_status_subj', 'submit_by', 'modify_by');
            $v->modify_by = $this->user->username;
            
            $fields = array_diff($fields, array('submit_by'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_mapping', 'option' => "WHERE class_code = '".$v->class_code."' AND subject_name_code = '".$v->subject_name_code."' LIMIT 1"));
            $this->db->query($sql);
            $this->result = $this->db->execute();
        }
        
        if ($this->result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
        // $sql = "UPDATE sch_students SET subjects ='".$compulsory_subject."' WHERE class_code ='".$this->post->class_code."' ";
        // $this->db->query($sql);
        // $this->db->execute();

        // $sql = "UPDATE sch_std_history SET subjects ='".$compulsory_subject."' WHERE class_code ='".$this->post->class_code."' AND term='".$this->post->term."' ";
        // $this->db->query($sql);
        // $this->db->execute();

        echo json_encode(array('status' => true, 'data' => $result));
        exit;
    }
}