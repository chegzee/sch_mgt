<?php

// require_once APP_ROOT . '/controllers/technical/TechnicalSetting.php';

class Techsetup extends Controller
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
    
        // verify risk
        // $this->data['riskObj'] = $this->technical->getRiskObj();
        
        $this->data['techsetupObj'] = array('invoice', 'invoice-vat', 'cashbook', 'receipt', );
    
        // Ledger
        $this->data['accountObj'] = $this->model('AccountData')->getAccounts(array('_option' => 'object'));
        
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
        $this->view('finance/techsetup', $this->data);
        //
        $this->view('system/footer', $this->data);
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
    
        // if (empty($post->branch_code)) {
        //     echo json_encode(array('status' => false, 'message' => 'BRANCH CODE REQUIRED'));
        //     exit;
        // }
        
        // INSERT
        $fields = array('item', 'debit_account', 'credit_account', 'branch_code', 'submit_by', 'modified_by');
    
        $post->submit_by = $post->modified_by = $this->user->username;
        
        // delete previous
        $this->db->query("DELETE FROM sys_tec_act WHERE branch_code='".$post->branch_code."' ");
        $this->db->execute();
        
        // insert
        $sql = "";
        $sql_option = '';
        $sql_array = (object)array();
        
        // if (!empty($this->data['riskObj'])) foreach ($this->data['riskObj'] as $k => $v) {
        if (!empty($this->data['techsetupObj'])) foreach ($this->data['techsetupObj'] as $k_) {

            //$k . '-' . $k_ . '--dr'
            $sql_array->item = $k_;
            $sql_array->branch_code = $post->branch_code;
            // $sql_array->risk_code = $k;
            $sql_array->debit_account = $post->{ $k_ . '--dr'};
            $sql_array->credit_account = $post->{ $k_ . '--cr'};
            $sql_array->submit_by = $post->submit_by;
            $sql_array->modified_by = $post->modified_by;

            if (empty($k_) || empty($post->{$k_ . '--dr'}) || empty($post->{$k_ . '--cr'})) continue;
            
            $sql .= $this->model('SystemData')->sqlQuery(array('array' => array($sql_array), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sys_tec_act', 'option' => $sql_option));

            $sql_option = 'ADD';
        }
        // }
        // echo json_encode(array('status' => false, 'message' => $sql)); exit;
        
        if (!empty($sql)) {
            $this->db->query($sql);
            $result = $this->db->execute();
    
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
        }
    
        //
        $url = URL_ROOT . '/account/bank/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => $url));
        exit;
    }
}