<?php

class Cashbook extends Controller
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
        if ($this->user->access->finance->tool !=='1') header('Location: ' . URL_ROOT . '/system/dashboard/?' . urldecode(http_build_query($this->params_)));
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
        $this->data['parentObj'] = $this->model('SystemData')->getParents(array("_option2" => "object"));
        $this->data['branches'] = $this->model('SystemData')->getBranches(array("_option2" => "object"));
        $this->data['currenciesObj'] = $this->model('SystemData')->getCurrencies(array("_option" => "object"));
        // var_dump($this->data['branch']);exit;
        
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', []);
        // body
        $this->view('finance/cashbook', $this->data);
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
            $cashbookBalance = $this->model('AccountData')->getCashbookBalance(array("client_code"=> $cashbook->client_code));
            $creditBalance  = $cashbookBalance['balance']; 
            $amount = $creditBalance - $cashbook->amount;
            if($amount < 0){
                echo json_encode(array("status"=> "LOW CREDIT BALANCE"));
                exit;

            }
            // var_dump($balance);exit;
            // if($cashbook->status > 0 ){
            //     echo json_encode(array("status"=> "TRANSACTION HAS BEEN APPROVED"));
            //     exit;

            // }

            $sql = "DELETE FROM act_cashbook WHERE trans_code = '{$post->trans_code}' LIMIT 1";
            
            $this->db->query($sql);
            $result = $this->db->execute();
            // var_dump($result);exit;
            if($result !== true){
                echo json_encode(array('status' => $result));exit;
            }
        }
        $this->db->query("DELETE FROM act_gledger WHERE trans_code = '{$post->trans_code}'");
        $this->db->execute();
        echo json_encode(array('status' => true));
        
        
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

        if (empty($post->trans_code) && empty($post->trans_code_old)) {
            echo json_encode(['status' => false, 'message' => 'TRANSACTION CODE REQUIRED']);
            exit;
        }

        $trans_date = date_create_from_format('Y-m-d', $post->trans_date);
        if (!$trans_date) {
            echo json_encode(['status' => false, 'message' => 'TRANSACTION DATE INVALID']);
            exit;
        }

        // if (empty($post->trans_type)) {
        //     echo json_encode(['status' => false, 'message' => 'TRANSACTION TYPE INVALID']);
        //     exit;
        // }

        if (empty($post->trans_mode)) {
            echo json_encode(['status' => false, 'message' => 'TRANSACTION TYPE INVALID']);
            exit;
        }

        if (empty($post->branch_code)) {
            echo json_encode(['status' => false, 'message' => 'BRANCH REQUIRED']);
            exit;
        }
        //
        if (empty($post->client_code)) {
            echo json_encode(['status' => false, 'message' => 'CLIENT REQUIRED']);
            exit;
        }

        if ($post->amount <= 0) {
            echo json_encode(['status' => false, 'message' => 'AMOUNT REQUIRED']);
            exit;
        }

        // if (empty($post->debit_account)) {
        //     echo json_encode(['status' => false, 'message' => 'BANK REQUIRED']);
        //     exit;
        // }

        // if (empty($post->credit_account)) {
        //     echo json_encode(['status' => false, 'message' => 'DEPOSIT ACCOUNT REQUIRED']);
        //     exit;
        // }

        if (empty($post->currency_code)) {
            echo json_encode(['status' => false, 'message' => 'CURRENCY REQUIRED']);
            exit;
        }

        if ($post->currency_rate <= 0) {
            echo json_encode(['status' => false, 'message' => 'CURRENCY RATE REQUIRED']);
            exit;
        }

        // INSERT
        $fields = ['trans_code', 'client_code', 'client_name', 'trans_date', 'trans_type', 'ref_code', 'trans_mode', 'trans_detail', 'branch_code', 'amount', 'debit_account', 'credit_account', 'status', 'currency_code', 'currency_rate', 'payer', 'submit_by', 'modify_by'];

        // check record
        $cashbook = $this->model('AccountData')->getCashbooks(["_option"=> "object", 'trans_code' => $post->trans_code_old, 'trans_type' => TRANS_TYPE_REC]);
        // var_dump($cashbook->client_code);exit;
        if ($cashbook->status > 0) {
            echo json_encode(['status' => false, 'message' => 'CASHBOOK HAS BEEN APPROVED']);
            exit;
        }

        $sql = '';
        if (!$cashbook) {
            $post->submit_by = $this->user->username;

            // branch
            $branch = $this->model('SystemData')->getBranch(['branch_code' => $post->branch_code]);
            // CB/R/LAG/2209001
            $post->trans_code = $this->model('SystemData')->newAuto(['auto_item' => $post->trans_code, 'digit' => '000', 'prefix' => 'DEP/' . $branch->alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'CASHBOOK-RECEIPT-' . date('Ym'), 'field' => 'trans_code', 'table' => 'act_cashbook']);

            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_cashbook', 'option' => ""]);
        }
        // UPDATE
        else { 
            $fields = array_diff($fields, ['submit_by']);

            $post->modify_by = $this->user->username;

            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'act_cashbook', 'option' => "WHERE trans_code = '" . addslashes($post->trans_code_old ?? '') . "' LIMIT 1"]);
        }
        //echo json_encode(['status' => false, 'data' => $sql]); exit;
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if($result === true){
                
            // echo json_encode(array('status' => $result));exit;
            echo json_encode(['status' => $result, 'data' => $cashbook]);
            exit;
        }
        echo json_encode(['status' => false, "message"=> $result]);
        //
    }

    //for saving student from excel
    public function __save()
    {
        $post = json_decode($this->post->data);
        
        // $key = '';
        $count = 0;
        // $sql = '';
        // var_dump($post);exit;
        $fields = ['trans_code', 'client_code', 'client_name', 'trans_date', 'trans_type', 'ref_code', 'trans_mode', 'trans_detail', 'branch_code', 'amount', 'status', 'currency_code', 'currency_rate', 'payer', 'submit_by', 'modify_by'];
        
        foreach($post as $k => $v){
            // var_dump($v->client_code);exit;
            // INSERT
            $key = $k;
            $count++;

            if(empty($v->client_code)){
                $count--;
                continue;
            }
            $cashbook = clone $v;
            $cashbook->amount = preg_replace('/[^0-9.]/', '', $v->amount);
            $cashbook->trans_type= 'CLT';
            $cashbook->submit_by = $this->user->username;
            $cashbook->modify_by = $this->user->username;
            $cashbook->status = '0';
            $cashbook->trans_code = $this->model('SystemData')->newAuto(['auto_item' => $cashbook->trans_code, 'digit' => '000', 'prefix' => 'DEP/' . $branch->alpha . '/' . date('ym'), 'suffix' => '', 'serial' => 'CASHBOOK-RECEIPT-' . date('Ym'), 'field' => 'trans_code', 'table' => 'act_cashbook']);
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$cashbook], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_cashbook', 'option' => '']);
            
            // var_dump($sql);exit;
            // $sql .= $this->model('SystemData')->sqlQuery(['array' => [$cashbook], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_cashbook', 'option' => $count > 1 ? 'ADD' : '']);
            // $this->db->query($sql);
            $this->db->query($sql);
            $result = $this->db->execute();

            if ($result !== true) {
                echo json_encode(array('status' => false, 'key'=> $key, 'message' => $result));
                exit;
            }
            
        }
        // $this->db->query($sql);
        // $result = $this->db->execute();
        // if($result === true){
        //     echo json_encode(array('status' => true, 'data' => $result));exit;

        // }
        echo json_encode(array('status' => true, 'data' => $result));exit;
        // var_dump($result);exit;
        // exit;
    }

    public function _process()
    {
    
        $post = $this->post;
        // var_dump($post);exit;
    
        if (
            // approve
            $this->module_access->finance->cashbook_approve !== '1'
        ) {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }
        
        // [Process] -> Cashbook
        if ($post->list_option == '0') {
            
            $fields_gl = ['account_code', 'account_name', 'debit', 'credit', 'trans_code', 'trans_type', 'ref_code', 'branch_code', 'trans_date', 'trans_detail', 'currency_code', 'currency_rate', 'submit_by', 'approved_by'];
            
            //$accountMappingObj = $this->model('AccountData')->getAccountMapping(array("_option"=> 'object', 'branch_code' => $student->branch_code));
            // $post->debit_account = $accountMappingObj->invoice->debit_account;
            // $post->credit_account = $accountMappingObj->invoice->credit_account;

            $count = 0;
            //
            $sql_gl = "";
            $count_gl = 0;
            //
            foreach ($post->trans_code as $trans_code) {
                
                $count++;
                $count_gl++;
                
                $accounts = $this->model('AccountData')->getAccounts(array("_option"=>'object'));
                $cashbook = $this->model('AccountData')->getCashbooks(["_option"=>"object", 'trans_code' => $trans_code, 'trans_type'=>TRANS_TYPE_REC, 'list_option' => '']);
                $accountMappingObj = $this->model('AccountData')->getAccountMapping(array("_option"=> 'object', 'branch_code' => $cashbook->branch_code));
                $post->debit_account = $accountMappingObj->cashbook->debit_account;
                $post->credit_account = $accountMappingObj->cashbook->credit_account;
               // $cashbook = $this->model('AccountData')->getCashbook(['trans_code' => $trans_code, 'trans_type' => TRANS_TYPE_REC]);
                // echo json_encode(['status' => false, 'message' => $cashbook]); exit;
                if (!$cashbook) {
                    $count--;
                    $count_gl--;
                    continue;
                }

                $gl = clone $cashbook;
    
                // YYMM00000000
                //$cashbook->trans_code = $this->model('SystemData')->newAuto(['auto_item' => $cashbook->trans_code, 'digit' => '00000000', 'prefix' => date('ym'), 'suffix' => '', 'serial' => 'CASHBOOK-' . date('Ym'), 'field' => 'trans_code', 'table' => 'act_cashbook']);
                // DR: Expense
                $gl->account_code = $post->debit_account; 
                $gl->account_name = $accounts->{$post->debit_account}->account_name;
                $gl->debit = $cashbook->amount;
                $gl->credit = '0';
    
                //
                $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => $count_gl > 1 ? 'ADD' : '']);
                
                // CR: Liability
                $gl->account_code = $post->credit_account;
                $gl->account_name = $accounts->{$post->credit_account}->account_name;
                $gl->debit = '0';
                $gl->credit = $cashbook->amount;
    
                //
                $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => 'ADD']);
                
            }
            //echo json_encode(['status' => false, 'message' => $sql, 'message2' => $sql_payment, 'message3' => $sql_gl]); exit;
            if ($sql_gl != "") {
                $this->db->query($sql_gl);
                $flag = $this->db->execute();
                // var_dump($flag);exit;
                if($flag === true){
                    $sql = "UPDATE act_cashbook SET status = '" . ($post->list_option + 1) . "', approve_by = '".$this->user->username."' WHERE trans_code IN ('" . implode('\', \'', $post->trans_code ?? []) . "')";
                    $this->db->query($sql);
                    $result = $this->db->execute();
                    
                    if ($result !== true) {
                        echo json_encode(['status' => false, 'message' => $result]);
                        exit;
                    }
                    // log
                    $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'UPDATE', 'remarks' => json_encode($post)]);
                    
                    //
                    echo json_encode(['status' => true, 'data' => ['message' => 'Approve ' . implode(', ', $post->trans_code ?? [])]]);

                }else{
                    echo json_encode(['status' => false, 'data' => ['message' => $flag]]);

                }
            }
        }
        exit;
    }

}