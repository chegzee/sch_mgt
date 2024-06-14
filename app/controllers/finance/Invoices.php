<?php

class Invoices extends Controller
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
        // if(empty($this->user)){
        //     $this->user = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));
        // }

        // var_dump($this->user);exit;
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        if ($this->user->access->finance->setup !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->data['branchObj'] = $this->model('SystemData')->getBranches(['_option' => 'object']);
        // currencies
        $this->data['currencies'] = $this->model('AccountData')->getCurrencies(['_option' => 'object']);      
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->terms= $this->model('SystemData')->getTerms(array());
        // $this->data['students'] = $this->model('SystemData')->getStudentForInvoice([]);
        
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
        $this->view('finance/invoices', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $post = (array)$this->post;
        // var_dump($post);exit;
        $invoices = $this->model('AccountData')->getInvoices($arg = $post);
        echo json_encode(array('data' => $invoices));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $invoice_code = implode("', '", $post->invoice_code);
            
            $sql = "SELECT t1.invoice_code FROM sch_invoice t1 WHERE t1.invoice_code IN ('{$invoice_code}')";
            $this->db->query($sql);
            // var_dump($this->db->resultSet());exit;
            foreach ($this->db->resultSet() ?? [] as $k => $v) {
                $sql = "DELETE FROM sch_invoice WHERE invoice_code = '{$v->invoice_code}' LIMIT 1";
                $this->db->query($sql);
                $this->result = $this->db->execute();

                if($this->result === true){
                    $sql_gl = "DELETE FROM act_gledger WHERE trans_code = '{$v->invoice_code}' LIMIT 100";
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
    ////////////save invoice//////////////
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
        // echo json_encode(array('status' => false, 'message' => $_POST)); exit;
    
        
        // period
        $period = $this->model('AccountData')->getAccountPeriod(array('trans_date' => $post->trans_date, 'username' => $this->user->username));
        if (!$period) {
            echo json_encode(array('status' => false, 'message' => 'LEDGER POSTING NOT ALLOWED'));
            exit;
        }
        else if ($period->invoice !== '1') {
            echo json_encode(array('status' => false, 'message' => 'LEDGER JOURNAL NOT ALLOWED'));
            exit;
        }

        if (empty($post->invoice_code) && !empty($post->invoice_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'INVOICE CODE REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->client_code))) {
            echo json_encode(array('status' => false, 'message' => 'CLIENT NAME REQUIRED'));
            exit;
        }
        
        if (empty(trim($post->level_fees))) {
            echo json_encode(array('status' => false, 'message' => 'LEVEL FEES REQUIRED'));
            exit;
        }
        if (empty(trim($post->amount))) {
            echo json_encode(array('status' => false, 'message' => 'AMOUNT REQUIRED'));
            exit;
        }

        if (empty(trim($post->bank_account))) {
            echo json_encode(array('status' => false, 'message' => 'BANK REQUIRED'));
            exit;
        }

        // var_dump($post);exit;
        
        // INSERT
        $fields = array('invoice_code', 'receipt_code', 'client_code', 'amount', 'level_fees', 'trans_date', 'currency_code', 'currency_rate', 'bank_account', 'branch_code', 'debit_account', 'credit_account', 'submit_by', 'modified_by');
        
        $fields_gl = ['account_code', 'account_name', 'debit', 'credit', 'trans_code', 'trans_type', 'ref_code', 'branch_code', 'trans_date', 'trans_detail', 'currency_code', 'currency_rate', 'submit_by', 'approved_by'];
        $post->submit_by = $post->modified_by = $this->user->username;
        $post->trans_type = 'REC';
        // var_dump($post->trans_info);exit;  
        // check record
        $accounts = $this->model('AccountData')->getAccounts(array("_option"=>'object'));
        $student = $this->model('SystemData')->getStudentForInvoice(array('_option' => 'std_code', 'std_code' => $post->client_code));
        $invoice = $this->model('AccountData')->getInvoices(array('_option' => 'invoice_code', 'invoice_code' => $post->invoice_code_old));
        $accountMappingObj = $this->model('AccountData')->getAccountMapping(array("_option"=> 'object', 'branch_code' => $student->branch_code));
        $branch = $this->model('SystemData')->getBranch(array('branch_code' => $student->branch_code));
        $post->branch_code = $student->branch_code;
        $post->debit_account = $accountMappingObj->invoice->debit_account;
        $post->credit_account = $accountMappingObj->invoice->credit_account;
        // var_dump($invoice);exit;
        // 
             
        $sql = '';
        if (!$invoice) {
            //////create new invoice/////////////////
            $post->invoice_code = $this->model('SystemData')->newAuto(['auto_item' => $post->invoice_code, 'digit' => '000000', 'prefix' => 'INV/' . $branch->alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'INVOICE-' . date('Ym'), 'field' => 'invoice_code', 'table' => 'sch_std_history']);
            $post->receipt_code = $this->model('SystemData')->newAuto(['auto_item' => $post->receipt_code, 'digit' => '000000', 'prefix' => 'R/' . $branch->alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'RECEIPT-' . date('Ym'), 'field' => 'receipt_code', 'table' => 'sch_std_history']);
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_invoice', 'option' => ""));
            $this->db->query($sql);
            $result = $this->db->execute();
            ////////////update student invoice/////////////////
            $sql = "UPDATE sch_students SET invoice_code ='".$post->invoice_code."', receipt_code ='".$post->receipt_code."' WHERE std_code = '".$student->std_code."' ";
            $this->db->query($sql);
            $result = $this->db->execute();
            ////////////update student history invoice/////////////////
            $sql = "UPDATE sch_std_history SET invoice_code ='".$post->invoice_code."', receipt_code ='".$post->receipt_code."' WHERE std_code = '".$student->std_code."' AND term = '".$student->term."' ";
            $this->db->query($sql);
            $result = $this->db->execute();

            ////////////set other fees in invoice/////////////////
            $sql = "UPDATE sch_invoice SET other_fees ='".$post->other_fees."' WHERE  invoice_code = '" . addslashes($post->invoice_code_old ?? '') . "' ";
            $this->db->query($sql);
            $result = $this->db->execute();

            $post->ref_code = $post->invoice_code;
            $post->trans_code = $post->invoice_code;

            $gl = clone $post;
            // DR
            $gl->account_code = $post->debit_account; 
            $gl->account_name = $accounts->{$post->debit_account}->account_name;
            $gl->debit = $post->amount;
            $gl->credit = '0';

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => '']);
            
            // CR: 
            $gl->account_code = $post->credit_account;
            $gl->account_name = $accounts->{$post->credit_account}->account_name;
            $gl->debit = '0';
            $gl->credit = $post->amount;

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => 'ADD']);
            $this->db->query($sql_gl);
            $result = $this->db->execute();
            // var_dump($sql_gl);exit;
            echo json_encode(array('status' => $result, 'message' => $result));
            exit;
        }
        // UPDATE
        else { 
            $fields = array_diff($fields, array('submit_by'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_invoice', 'option' => "WHERE invoice_code = '" . addslashes($post->invoice_code_old ?? '') . "' LIMIT 1"));
            $this->db->query($sql);
            $result = $this->db->execute();
            
            ////////////set other fees in invoice/////////////////
            $sql = "UPDATE sch_invoice SET other_fees ='".$post->other_fees."' WHERE  invoice_code = '" . addslashes($post->invoice_code_old ?? '') . "' ";
            $this->db->query($sql);
            $result = $this->db->execute();
            ////////////update student history invoice/////////////////
            $sql = "UPDATE sch_std_history SET invoice_code ='".$post->invoice_code."' WHERE std_code = '".$student->std_code."' AND term = '".$student->term."' ";
            $this->db->query($sql);
            $result = $this->db->execute();

            
            // delete existing
            $this->db->query("DELETE FROM act_gledger WHERE trans_code LIKE '{$post->invoice_code_old}%' AND trans_type='REC' ");
            $this->db->execute();

            
            $post->ref_code = $post->invoice_code;
            $post->trans_code = $post->invoice_code;

            $gl = clone $post;
            // DR
            $gl->account_code = $post->debit_account; 
            $gl->account_name = $accounts->{$post->debit_account}->account_name;
            $gl->debit = $post->amount;
            $gl->credit = '0';

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => '']);
            
            // CR: 
            $gl->account_code = $post->credit_account;
            $gl->account_name = $accounts->{$post->credit_account}->account_name;
            $gl->debit = '0';
            $gl->credit = $post->amount;

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => 'ADD']);
            $this->db->query($sql_gl);
            $result = $this->db->execute();
             
            echo json_encode(array('status' => $result, 'message' => $result));
        }
    }

    public function saveMultipleInvoice(){
        
        // period
        $period = $this->model('AccountData')->getAccountPeriod(array('trans_date' => date('Y-m-d'), 'username' => $this->user->username));
        if (!$period) {
            echo json_encode(array('status' => false, 'message' => 'LEDGER POSTING NOT ALLOWED'));
            exit;
        }

        else if ($period->invoice !== '1') {
            echo json_encode(array('status' => false, 'message' => 'LEDGER JOURNAL NOT ALLOWED'));
            exit;
        }

        // var_dump($period);exit;


        $post = $this->post;
        $std_code = implode("', '", $post->std_code);
        $invoices = array();
        // $sql = "SELECT t1.std_code, t1.invoice_code FROM sch_students t1 WHERE t1.std_code IN ('{$std_code}')";
        
        // $bank = $this->model("AccountData")->getBanks(array( "status"=> "1"));
        
        $students = $this->model("SystemData")->getStudentForInvoice(array("_option"=> "multiple", "std_code"=> $std_code));
        // var_dump($students);exit;
        foreach ($students as $k => $v) {
            $invoice = (object)array();
            $invoice->invoice_code = $v->invoice_code ?? '';
            $invoice->receipt_code = $v->receipt_code ?? '';
            $invoice->submit_by = $this->user->username;
            $invoice->modified_by = $this->user->username;
            $invoice->trans_type = 'REC';
            $invoice->branch_alpha = $v->alpha ?? '';
            $invoice->term = $v->term ?? '';
            $invoice->client_code = $v->std_code;
            $invoice->trans_date = date('Y-m-d H:i:s');
            $invoice->client_name = $v->std_name ?? '';
            $invoice->branch = $v->branch_code ?? 0;
            $invoice->bank_account = $v->account_code;
            // $invoice->bank_account = $bank->account_code;
            $invoice->currency_code = BASE_CURRENCY;
            $invoice->currency_rate = BASE_RATE;
            $invoice->level_fees = $v->fees ?? 0;
            $amount = (float)$v->fees;
            $arr = array();
            foreach(json_decode($v->activities) as $kk => $vv){
                $amount = $amount + (float)$vv->product_price;
                $obj = (object)array();
                $obj->{$vv->product_name} = $vv->product_price;
                array_push($arr, $obj);
            }
            $invoice->amount = $amount;
            $invoice->other_fees = json_encode($arr);
            array_push($invoices, $invoice);
        }
        // echo json_encode(array("status"=>true, "data"=>$invoices));exit;

        foreach($invoices as $k => $v){
            if(empty($v->invoice_code)){
                $v->invoice_code = $this->model('SystemData')->newAuto(['auto_item' => $v->invoice_code, 'digit' => '000000', 'prefix' => 'INV/' . $v->branch_alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'INVOICE-' . date('Ym'), 'field' => 'invoice_code', 'table' => 'sch_std_history']);
                $v->receipt_code = $this->model('SystemData')->newAuto(['auto_item' => $v->receipt_code, 'digit' => '000000', 'prefix' => 'R/' . $branch->alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'RECEIPT-' . date('Ym'), 'field' => 'receipt_code', 'table' => 'sch_std_history']);
                // var_dump($v->invoice_code);exit;
                ////////////update student invoice/////////////////
                $sql = "UPDATE sch_students SET invoice_code ='".$v->invoice_code."', receipt_code ='".$v->receipt_code."' WHERE std_code = '".$v->client_code."' ";
                $this->db->query($sql);
                $result = $this->db->execute();
                ////////////update student history invoice/////////////////
                $sql = "UPDATE sch_std_history SET invoice_code ='".$v->invoice_code."', receipt_code ='".$v->receipt_code."' WHERE std_code = '".$v->client_code."' AND term = '".$v->term."' ";
                $this->db->query($sql);
                $result = $this->db->execute();
                
            }

            $this->res = $this->createInvoice(array("data"=>$v));
        }
        echo json_encode(array("status"=>true, "data"=>$this->res));
        
    }

    public function createInvoice($arg = array()){
        $invoice = $arg['data'];
        // echo json_encode(array("status"=>true, "data"=>$invoice));exit;
        
        // INSERT
        $fields = array('invoice_code', 'client_code', 'receipt_code', 'amount', 'level_fees', 'trans_date', 'currency_code', 'currency_rate', 'bank_account', 'branch_code', 'debit_account', 'credit_account', 'submit_by', 'modified_by');
        
        $fields_gl = ['account_code', 'account_name', 'debit', 'credit', 'trans_code', 'trans_type', 'ref_code', 'branch_code', 'trans_date', 'trans_detail', 'currency_code', 'currency_rate', 'submit_by', 'approved_by'];
        
        // var_dump($post->trans_info);exit;  
        // check record
        $accounts = $this->model('AccountData')->getAccounts(array("_option"=>'object'));
        // $student = $this->model('SystemData')->getStudentForInvoice(array('_option' => 'std_code', 'std_code' => $invoice->client_code));
        $invoice_ = $this->model('AccountData')->getInvoices(array('_option' => 'invoice_code', 'invoice_code' => $invoice->invoice_code));
        $accountMappingObj = $this->model('AccountData')->getAccountMapping(array("_option"=> 'object', 'branch_code' => $invoice->branch));
        // $branch = $this->model('SystemData')->getBranch(array('branch_code' => $invoice->branch));
        $invoice->branch_code = $invoice->branch;
        $invoice->debit_account = $accountMappingObj->invoice->debit_account;
        $invoice->credit_account = $accountMappingObj->invoice->credit_account;
        // var_dump($invoice);exit;
        // 
             
        $sql = '';
        if (!$invoice_) {
            //////create new invoice/////////////////
            //$post->invoice_code = $this->model('SystemData')->newAuto(['auto_item' => $post->invoice_code, 'digit' => '000000', 'prefix' => 'INV/' . $branch->alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'INVOICE-' . date('Ym'), 'field' => 'invoice_code', 'table' => 'sch_invoice']);
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($invoice), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'sch_invoice', 'option' => ""));
            $this->db->query($sql);
            $result = $this->db->execute();

            ////////////set other fees in invoice/////////////////
            $sql = "UPDATE sch_invoice SET other_fees ='".$invoice->other_fees."' WHERE  invoice_code = '" . addslashes($invoice->invoice_code ?? '') . "' ";
            $this->db->query($sql);
            $result = $this->db->execute();

            $invoice->ref_code = $invoice->invoice_code;
            $invoice->trans_code = $invoice->invoice_code;

            $gl = clone $invoice;
            // DR
            $gl->account_code = $invoice->debit_account; 
            $gl->account_name = $accounts->{$invoice->debit_account}->account_name;
            $gl->debit = $invoice->amount;
            $gl->credit = '0';

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => '']);
            
            // CR: 
            $gl->account_code = $invoice->credit_account;
            $gl->account_name = $accounts->{$invoice->credit_account}->account_name;
            $gl->debit = '0';
            $gl->credit = $invoice->amount;

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => 'ADD']);
            $this->db->query($sql_gl);
            $result = $this->db->execute();
            return array("msg"=>$result);
            // var_dump($sql_gl);exit;
            // echo json_encode(array('status' => $result, 'message' => $result));
            // exit;
        }
        // UPDATE
        else { 
            $fields = array_diff($fields, array('submit_by'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($invoice), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'sch_invoice', 'option' => "WHERE invoice_code = '" . addslashes($invoice->invoice_code ?? '') . "' LIMIT 1"));
            $this->db->query($sql);
            $result = $this->db->execute();
            
            ////////////set other fees in invoice/////////////////
            $sql = "UPDATE sch_invoice SET other_fees ='".$invoice->other_fees."' WHERE  invoice_code = '" . addslashes($invoice->invoice_code ?? '') . "' ";
            $this->db->query($sql);
            $result = $this->db->execute();

            
            // delete existing
            $this->db->query("DELETE FROM act_gledger WHERE trans_code LIKE '{$invoice->invoice_code}%' AND trans_type='REC' ");
            $this->db->execute();

            
            $invoice->ref_code = $invoice->invoice_code;
            $invoice->trans_code = $invoice->invoice_code;

            $gl = clone $invoice;
            // DR
            $gl->account_code = $invoice->debit_account; 
            $gl->account_name = $accounts->{$invoice->debit_account}->account_name;
            $gl->debit = $invoice->amount;
            $gl->credit = '0';

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => '']);
            
            // CR: 
            $gl->account_code = $invoice->credit_account;
            $gl->account_name = $accounts->{$invoice->credit_account}->account_name;
            $gl->debit = '0';
            $gl->credit = $invoice->amount;

            $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => 'ADD']);
            $this->db->query($sql_gl);
            $result = $this->db->execute();
            return array("msg"=>$result);
             
            // echo json_encode(array('status' => $result, 'message' => $result));
        }
    }
}