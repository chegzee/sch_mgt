<?php

class Reconcile extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    protected $params_;
    protected $module_access;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        //
        $this->params_ = $this->data['params'] ?? [];
        unset($this->params_['url']);
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(['user_log' => $this->data['params']['user_log']]);
        if (!$this->user) {
            if ('xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' )) { echo json_encode(['status' => false, 'message' => 'SESSION EXPIRED', 'data' => ['url' => 'Location: ' . URL_ROOT . '/system/login']]); exit; }
            header('Location: ' . URL_ROOT . '/system/login');
        }
        if ($this->user->access->finance->receivable < '1') header('Location: ' . URL_ROOT . '/system/dashboard/?' . urldecode(http_build_query($this->params_)));
        $this->data['user'] = (array)$this->user;
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        
        $this->post = (object)filter_input_array(INPUT_POST);
    
        // access
        $this->module_access = $this->user->access;
    
        // branchObj
        $this->data['branchObj'] = $this->model('SystemData')->getBranches(['_option' => 'object']);
        // currencies
        $this->data['currencies'] = $this->model('AccountData')->getCurrencies(['_option' => 'object']);
        //
        $this->data['accountObj'] = $this->model('AccountData')->getLedgerAccounts(['_option' => 'object']);
        
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', []);
        // body
        $this->view('account/reconciliation', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        echo json_encode($this->model('AccountData')->getCashbooks((array)$this->post));
        exit;
    }

    public function _delete()
    {
        $post = $this->post;
        $cashbook = $this->model('AccountData')->getCashbooks(["_option"=> "object", 'trans_code' => $post->trans_code, 'trans_type' => TRANS_TYPE_REC]);
        
        if(!empty($cashbook->trans_code)){
            $sql = "DELETE FROM act_cashbook WHERE trans_code = '{$post->trans_code}' LIMIT 1";
            $this->db->query($sql);
            $result = $this->db->execute();
        }
        //
        // delete from gl 
        $this->db->query("DELETE FROM act_gledger WHERE trans_code = '{$post->trans_code}'");
        $this->db->execute();
        echo json_encode(array('status' => true, 'data' => array('message' => 'Deleted')));
        exit;
        
    }

    public function saveBankStatement()
    {
        $post =(object) $this->post;
        // var_dump($post);exit;
        $bankStatement = json_decode($post->bank_statement);
        $bankStatementDate = $bankStatement[0]->date;
        $month = date('m', strtotime($bankStatementDate));
        $year = date('Y', strtotime($bankStatementDate));
       

        if (empty($post->account_code) ) {
            echo json_encode(['status' => false, 'message' => 'ACCOUNT NAME REQUIRED']);
            exit;
        } 
        ////////////////////////
        $sql = "DELETE FROM bank_statement t1 WHERE MONTH(t1.date)='". $month . "' AND YEAR(t1.date)='" . $year . "' AND t1.account_code = '" . $post->account_code . "' ";
        
        $this->db->query($sql);
        $result = $this->db->execute();
        // var_dump($sql);exit;

        
        $fields = ['date', 'account_code', 'account_name','reference_no', 'debit', 'credit', 'balance', 'reconcile', 'submit_on', 'modified_on', 'submit_by', 'modified_by'];
        $ar = array();
        $sql = '';
        foreach($bankStatement as $k => $v){
            $v->account_code = $post->account_code;
            $v->account_name = $post->account_name;
            $v->submit_by = $v->modified_by = $this->user->username;
            if(empty($v->date)) continue;
            if($k > 0){
                $sql .= $this->model('SystemData')->sqlQuery(['array' => [$v], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'bank_statement', 'option' => "ADD"]);
                continue;
            }
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$v], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'bank_statement', 'option' => ""]);

            // array_push($ar, $k);
        }
        
        $this->db->query($sql);
        $result = $this->db->execute();
        if($result !== true){
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
        
        echo json_encode(['status' => true, 'message' => "SUCCESSFUL"]);
        // var_dump($result);exit;

    }

    public function getBankStatement(){
        $post = (object)$this->post;
        
        $month = date('m', strtotime($post->date));
        $year = date('Y', strtotime($post->date));
        $sql = "SELECT * FROM bank_statement WHERE MONTH(date)='" . $month.  "' AND YEAR(date)='" . $year . "' AND account_code = '" .$post->account_code . "' ";
        
        $this->db->query($sql);
        $bankStatement = $this->db->resultSet();
        // var_dump($bankStatement);exit;
        echo json_encode(array("status"=> true, "data"=>$bankStatement));
    }

    public function getAccountLedgerList(){
        $post = (object)$this->post;
        
        $month = date('m', strtotime($post->date));
        $year = date('Y', strtotime($post->date));
        
        $sql = "SELECT t1.*, left(t1.trans_date, 10)trans_date FROM act_gledger t1 WHERE MONTH(left(t1.trans_date, 10))='" . $month . "' AND YEAR(left(t1.trans_date, 10))='" . $year . "' AND account_code = '" . $post->account_code . "' ";
        $this->db->query($sql);
        $gl = $this->db->resultSet();
        // var_dump($bankStatement);exit;
        echo json_encode(array("status"=> true, "data"=>$gl));
    }

    public function reconcileBankStatement(){

        $post = $this->post;
        $sql = "UPDATE bank_statement SET reconcile = '".$post->_val."' WHERE id = '".$post->_id."' ";
        // var_dump($sql);exit;
        $this->db->query($sql);
        $result = $this->db->execute();
        if($result === true){
            echo json_encode(array("status" => true, "message"=> "SUCCESSFUL", "res"=>$res));
        }
    }

    public function reconcileLedger(){

        $post = $this->post;
        $sql = "UPDATE act_gledger SET reconcile = '".$post->_val."' WHERE auto_id = '".$post->_id."' ";
        // var_dump($sql);exit;
        $this->db->query($sql);
        $result = $this->db->execute();
        if($result === true){
            echo json_encode(array("status" => true, "message"=> "SUCCESSFUL", "res"=>$result));
        }
    }

    public function reconcileReport(){
        
        // head
        $this->view('system/header', []);
        // body
        $this->view('report/reconcileReport', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
}