<?php


require_once 'AccountSetting.php';
class Receipt extends AccountSetting
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    protected $technical;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        if ($this->user->access->finance->receipt !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        
        // currencyObj
        $this->data['currencyObj'] = $this->getCurrencyObj();
        // $this->data['currencyObj'] = $this->getCurrencyObj();
    
        // transModeObj
        $this->data['transModeObj'] = $this->getTransModeObj();
    
        // busTypeObj
        $this->data['busTypeObj'] = $this->getBusTypes(array('_option' => 'receivable'));      
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->terms= $this->model('SystemData')->getTerms(array());
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        $this->data['terms'] = (array)$this->terms;
        //echo '<pre>'.print_r($this->data, true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('finance/receipt', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        // var_dump(); exit;
        $post = $this->post;
        $receipts = $this->model('AccountData')->getReceipts(array("term_code"=> $post->term));
        // 
        
        echo json_encode(array('data' => $receipts));
        exit;
    }
    ///////////delete Receipt Payment//////////////////
    public function __delete()
    {
        $post = $this->post;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $ref_code = implode("', '", $post->ref_code);
            
            $sql = "SELECT t1.ref_code FROM sch_receipt t1 WHERE t1.ref_code IN ('{$ref_code}')";
            $this->db->query($sql);
            // var_dump($this->db->resultSet());exit;
            foreach ($this->db->resultSet() ?? [] as $k => $v) {
                $sql = "DELETE FROM sch_receipt WHERE ref_code = '{$v->ref_code}' LIMIT 1";
                $this->db->query($sql);
                $this->result = $this->db->execute();

                if($this->result === true){
                    $sql_gl = "DELETE FROM act_gledger WHERE ref_code = '{$v->ref_code}' LIMIT 100";
                    $this->db->query($sql_gl);
                    $this->result = $this->db->execute();

                }
                // if ($result !== true) continue;
            }
            //var_dump($sql); exit;
            
            if ($this->result === true) {
                echo json_encode(array('status' => $this->result));
                exit;
            }
            
            //
            echo json_encode(array('status' => $result, 'message' => $this->result ));
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

    ////////delete receipt //////////////////
    public function _delete()
    {
        $post = $this->post;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $receipt_code = implode("', '", $post->receipt_code);
            
            $sql = "SELECT t1.receipt_code FROM sch_receipt t1 WHERE t1.receipt_code IN ('{$receipt_code}')";
            $this->db->query($sql);
            // var_dump($this->db->resultSet());exit;
            foreach ($this->db->resultSet() ?? [] as $k => $v) {
                $sql = "DELETE FROM sch_receipt WHERE receipt_code = '{$v->receipt_code}' LIMIT 1";
                $this->db->query($sql);
                $this->result = $this->db->execute();

                if($this->result === true){
                    $sql_gl = "DELETE FROM act_gledger WHERE trans_code = '{$v->receipt_code}' LIMIT 100";
                    $this->db->query($sql_gl);
                    $this->result = $this->db->execute();

                }
                // if ($result !== true) continue;
            }
            //var_dump($sql); exit;
            
            if ($this->result === true) {
                echo json_encode(array('status' => $this->result));
                exit;
            }
            
            //
            echo json_encode(array('status' => $result, 'message' => $this->result ));
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
        //echo json_encode(array('status' => false, 'message' => $_POST)); exit;
        // var_dump($post->trans_date);exit;
    
        // period

        
        if (empty($post->trans_date)) {
            echo json_encode(array('status' => false, 'message' => 'DATE REQUIRED'));
            exit;
        }
        $period = $this->model('AccountData')->getAccountPeriod(array('trans_date' => $post->trans_date, 'username' => $this->user->username));

        if (!$period) {
            echo json_encode(array('status' => false, 'message' => 'LEDGER POSTING NOT ALLOWED'));
            exit;
        }
        else if ($period->receipt !== '1') {
            echo json_encode(array('status' => false, 'message' => 'LEDGER RECEIPT NOT ALLOWED'));
            exit;
        }
            
        if (empty($post->receipt_code) && empty($post->receipt_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'RECEIPT CODE REQUIRED'));
            exit;
        }
        
        if (empty($post->invoice_code)) {
            echo json_encode(array('status' => false, 'message' => 'REFERENCE REQUIRED'));
            exit;
        }
    
        if (empty($post->client_code)) {
            echo json_encode(array('status' => false, 'message' => 'CLIENT REQUIRED'));
            exit;
        }
        
        $trans_date = date_create_from_format('Y-m-d', $post->trans_date);
        if (!$trans_date) {
            echo json_encode(array('status' => false, 'message' => 'TRANSACTION DATE INVALID'));
            exit;
        }
        
        if ($post->amount <= 0) {
            echo json_encode(array('status' => false, 'message' => 'AMOUNT REQUIRED'));
            exit;
        }

        $invoice = $this->model('AccountData')->getInvoices(array( '_option'=>'invoice_code', 'invoice_code' => $post->invoice_code));
        //////////cashbook balance/////////////////
        $balance = $this->model('AccountData')->getCashbookBalance(array( 'client_code' => $post->client_code));

        $invoiceAmount = $invoice->invoice_amount;
        $receiptAmount = $invoice->total_receipt_amount;
        
        
        if (!$invoice) {
            echo json_encode(array('status' => false, 'message' => 'REFERENCE REQUIRED'));
            exit;
        }

        if (($balance['balance'] - $post->amount) < 0){
            echo json_encode(array("status"=> false, "message"=> "LOW CREDIT BALANCE "));
            exit;
        }
        
        if ($receiptAmount + $post->amount > $invoiceAmount) { 
            echo json_encode(array('status' => false, 'message' => 'TOTAL RECEIPT: ' . number_format($receiptAmount + $post->amount, 2) . ' EXCEED INVOICE: ' . number_format($invoice->invoice_amount, 2)));
            exit;
        }
    
        // account
        // $account = $this->model('AccountData')->getTechnicalAccount(array('_option' => 'risk_code', 'branch_code' => $post->branch_code, 'risk_code' => $post->risk_code));
        $accounts = $this->model('AccountData')->getAccounts(array("_option"=>'object'));
        $accountMappingObj = $this->model('AccountData')->getAccountMapping(array("_option"=> 'object', 'branch_code' => $post->branch_code));
            
        // debit-rcp
        if (empty($accountMappingObj->{'invoice'}->debit_account)) {
            echo json_encode(array('status' => false, 'message' => 'AMOUNT LEDGER DEBIT|CREDIT REQUIRED'));
            exit;
        }

        $post->credit_account = $accountMappingObj->{'invoice'}->debit_account;
        $post->debit_account = $accountMappingObj->{'cashbook'}->credit_account; 
        $post->currency_code = $invoice->currency_code;
        $post->currency_rate = $invoice->currency_rate;
        // var_dump($post);exit;
            
        // INSERT
        $fields = array('receipt_code', 'invoice_code', 'client_fullname', 'ref_code', 'client_code', 'debit_account', 'credit_account', 'amount', 'trans_date', 'trans_mode', 'trans_info', 'submit_by', 'modified_by');
        
        $post->submit_by = $post->modified_by = $post->approved_by = $this->user->username;
        // check record
        $sql = '';
        if(!empty($post->ref_code)){

        }
        else {
            // R/1/000059/21
            $post->receipt_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->receipt_code, 'digit' => '000000', 'prefix' => 'R/', 'suffix' => '/' . date('y'), 'serial' => 'RECEIPT-' . date('y'), 'field' => 'receipt_code', 'table' => 'sch_receipt'));
            $post->invoice_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->invoice_code, 'digit' => '000000', 'prefix' => 'INV/', 'suffix' => '/' . date('y'), 'serial' => 'INVOICE-' . date('y'), 'field' => 'invoice_code', 'table' => 'sch_receipt'));
            $post->ref_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->ref, 'digit' => '000000', 'prefix' => 'REF/', 'suffix' => '/' . date('y'), 'serial' => 'REF-' . date('y'), 'field' => 'ref_code', 'table' => 'sch_receipt'));
            
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_receipt', 'option' => ""));
            $this->db->query($sql);
            $result = $this->db->execute();
            // var_dump($result);exit;

            $sql = "UPDATE sch_parent SET receipt_code = '".$post->receipt_code."', invoice_code = '".$post->invoice_code."', ref_code = '".$post->ref_code."' WHERE parent_code = '".$post->client_code."' ";
            $this->db->query($sql);
            $result = $this->db->execute();

            $gl = clone $post;
        
            $fields = array('account_code', 'account_name', 'debit', 'credit', 'invoice_code', 'trans_code', 'trans_type', 'ref_code', 'ref_detail', 'branch_code', 'trans_date', 'trans_detail', 'currency_code', 'currency_rate', 'submit_by', 'modified_by', 'approved_by');
        
            // debit-rcp | DR
            $gl->account_code = $post->debit_account;
            $gl->account_name = $accounts->{$gl->account_code}->account_name;
            $gl->debit = $post->amount;
            $gl->credit = '0';
            $gl->trans_code = $post->receipt_code;
            $gl->ref_code = $post->ref_code;
            $gl->trans_type = "RCP";
            $gl->trans_detail = $post->trans_info;
            // $gl->client_code = $post->client_code;
        
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($gl), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_gledger', 'option' => ""));
        
            // debit-rcp | CR
            $gl->account_code = $post->credit_account;
            $gl->account_name = $accounts->{$gl->account_code}->account_name;
            $gl->debit = '0';
            $gl->credit = $post->amount;
        
            $sql .= $this->model('SystemData')->sqlQuery(array('array' => array($gl), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_gledger', 'option' => "ADD"));
        
            $this->db->query($sql);
            $result = $this->db->execute();
            echo json_encode(array("status"=>$result, "message"=>$result, "data"=>$post->receipt_code));
            // var_dump($result);
            exit;
        }
            
        // // log
        // $post_log = [];
        // foreach ($fields ?? [] as $k) $post_log[$k] = $post->{$k};
        //
        //$this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$receipt ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
    }
}