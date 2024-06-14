<?php

class Actlock extends Controller
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
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        if ($this->user->access->finance->setup !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        
        $this->data['year_status'] = array('invoice', 'receipt', 'receivable', 'payable', 'journal', 'cashbook', 'requisition');
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);
    }
    
    public function index()   
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('finance/actlock', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $actlocks = $this->model('AccountData')->getActlocks();
        //var_dump($actlocks); exit;
        echo json_encode(array('data' => $actlocks));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['finance']['admin'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
        $sql = "DELETE FROM sys_period WHERE auto_id = '{$post->auto_id}' LIMIT 1";
        //var_dump($sql); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
        
        //
        echo json_encode(array('status' => true, 'data' => array('message' => 'Account lock deleted')));
        exit;
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
        //echo json_encode(array('status' => false, 'message' => $_POST)); exit;
    
        $year_start = date_create_from_format('Y-m-d', $post->year_start);
        if (!$year_start) {
            echo json_encode(array('status' => false, 'message' => 'START DATE INVALID'));
            exit;
        }
    
        $year_end = date_create_from_format('Y-m-d', $post->year_end);
        if (!$year_end) {
            echo json_encode(array('status' => false, 'message' => 'END DATE INVALID'));
            exit;
        }
    
        $actlocks = $this->model('AccountData')->getActlocks(array());
        $actlocks = array_diff(array_map(function($v) use ($post) { return $post->auto_id !== $v->auto_id && (strtotime($post->year_start) >= strtotime($v->year_start) && strtotime($post->year_start) <= strtotime($v->year_end) || strtotime($post->year_end) >= strtotime($v->year_start) && strtotime($post->year_end) <= strtotime($v->year_end)); }, $actlocks), array(false));
        if (!empty($actlocks)) {
            echo json_encode(array('status' => false, 'message' => 'DATE OVERLAP INVALID'));
            exit;
        }
        
        // INSERT
        $fields = array('year_start', 'year_end', 'submit_by', 'modified_by');
        
        $post->submit_by = $post->modified_by = $this->user->username;
        
        // check record
        $actlock = $this->model('AccountData')->getActlock(array('auto_id' => $post->auto_id));
        //echo json_encode(array('status' => false, 'message' => $actlock)); exit;
        
        $sql = '';
        if (!$actlock) {
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sys_period', 'option' => ""));
            
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sys_period', 'option' => "WHERE auto_id = '" . addslashes($post->auto_id ?? '') . "' LIMIT 1"));
        }
        $this->db->query($sql);
            //echo json_encode(array('status' => false, 'message' => $sql)); exit;
        
        $result = $this->db->execute();
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
    
        // log
        $post_log = [];
        foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$actlock ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        $this->db->query("UPDATE sys_period SET year_status = '" . html_entity_decode($post->year_status) . "' WHERE year_start = '" . $post->year_start . "' AND year_start = '" . $post->year_start . "' LIMIT 1");
        $this->db->execute();
        
        //
        echo json_encode(array('status' => true, 'data' => $post));
        exit;
    }
}