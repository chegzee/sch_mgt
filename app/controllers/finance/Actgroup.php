<?php

class Actgroup extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $technical;
    
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
        $this->view('finance/actgroup', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $actgroups = $this->model('AccountData')->getActgroups();
        //var_dump($actgroups); exit;
        echo json_encode(array('data' => $actgroups));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['finance']['admin'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
        
        $sql = "DELETE FROM act_group WHERE group_code = '{$post->group_code}' LIMIT 1";
        //var_dump($sql); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result !== true) {
            echo json_encode(array('status' => false, 'message' => $result));
            exit;
        }
        
        //
        echo json_encode(array('status' => true, 'data' => array('message' => 'Account group deleted')));
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
    
        if (empty($post->group_code) && empty($post->group_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT GROUP CODE REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->group_name))) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT GROUP NAME REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->base_code))) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT BASE CODE REQUIRED'));
            exit;
        }
        
        if (substr($post->group_code, 0, 2) !== $post->base_code) {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT BASE|GROUP CODE INVALID'));
            exit;
        }
        if (substr($post->range_min, 0, 4) != $post->group_code || $post->range_min != $post->group_code . '001') {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT GROUP|RANGE MIN CODE INVALID'));
            exit;
        }
        if (substr($post->range_max, 0, 4) != $post->group_code || $post->range_max != $post->group_code . '999') {
            echo json_encode(array('status' => false, 'message' => 'ACCOUNT GROUP|RANGE MAX CODE INVALID'));
            exit;
        }
        if ($post->range_min >= $post->range_max) {
            echo json_encode(array('status' => false, 'message' => 'MIN|MAX RANGE INVALID'));
            exit;
        }
    
        $actgroups = $this->model('AccountData')->getActgroups(array());
        $group_codes = array_diff(
            array_map(
                function($v) use ($post) {
                    return $post->group_code !== $v->group_code && ($post->range_min >= $v->range_min && $post->range_min <= $v->range_max || $post->range_max >= $v->range_min && $post->range_max <= $v->range_max);
                    }, $actgroups
            )
            , array(false)
        );
        if (!empty($group_codes)) {
            echo json_encode(array('status' => false, 'message' => 'RANGE OVERLAP INVALID'));
            exit;
        }
        
        // INSERT
        $fields = array('group_code', 'group_name', 'base_code', 'range_min', 'range_max', 'submit_by', 'modified_by');
        
        $post->submit_by = $post->modified_by = $this->user->username;
        
        // check record
        $actgroup = $this->model('AccountData')->getActgroup(array('group_code' => $post->group_code_old));
        
        $sql = '';
        if (!$actgroup) {
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_group', 'option' => ""));
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, array('submit_by'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'act_group', 'option' => "WHERE group_code = '" . addslashes($post->group_code_old ?? '') . "' LIMIT 1"));
        }
        //echo json_encode(array('status' => false, 'message' => $sql)); exit;
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$actgroup ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    
        //
        $actgroup = $this->model('AccountData')->getActgroup(array('group_code' => $post->group_code));
        $actgroup->url = URL_ROOT . '/account/actgroup/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $actgroup));
        exit;
    }
}