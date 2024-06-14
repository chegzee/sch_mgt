<?php

class Requisition extends Controller
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
        //
        $this->params_ = $this->data['params'] ?? [];
        unset($this->params_['url']);
        unset($this->params_['requisition_code']);
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(['user_log' => $this->data['params']['user_log']]);
        if (!$this->user) {
            if ('xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' )) { echo json_encode(['status' => false, 'message' => 'SESSION EXPIRED', 'data' => ['url' => 'Location: ' . URL_ROOT . '/system/login']]); exit; }
            header('Location: ' . URL_ROOT . '/system/login');
        }
        if ($this->user->access->requisition->_main !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?' . urldecode(http_build_query($this->params_)));
        $this->data['user'] = (array)$this->user;
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        //echo '<pre>'.print_r($this->data['user'], true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
        // access
        $this->module_access = $this->user->access;
    
        // requisitionGroups
        $this->data['requisitionGroups'] = $this->model('AccountData')->getRequisitionGroups(['_option' => 'object']);
    
        // userGroups
        $this->data['userGroups'] = $this->model('SystemData')->getUserGroups(['_option' => 'object']);
    
        // allBanks
        $this->data['allBanks'] = $this->model('AccountData')->getAllBanksName(['_option' => 'object']);
    
        // requisitionStatuses
        $this->data['requisitionStatuses'] = $this->model('AccountData')->getRequisitionStatuses([]);
    
        // requisitionActivities
        $this->data['requisitionActivities'] = $this->model('AccountData')->getRequisitionActivities([]);
    
        // currencyObj
        $this->data['currencyObj'] = $this->model('SystemData')->getCurrencies(['_option' => 'object']);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', []);
        // body
        $this->view('system/requisition', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        echo json_encode($this->model('AccountData')->getRequisitions((array)$this->post));
        exit;
    }
    
    public function _delete()
    {
    
        if ($this->module_access->requisition->_main !== '1') {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }
        
        $post = $this->post;
        
        $sql = "DELETE FROM act_requisition WHERE (status = '' OR status = '0' OR status IS NULL) AND requisition_code IN ('" . implode('\', \'', $post->requisition_code ?? []) . "')";
        //echo json_encode(['status' => false, 'message' => $sql]); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'DELETE', 'remarks' => json_encode($post)]);
        
        //
        echo json_encode(['status' => true, 'data' => ['message' => 'Requisition: ' . implode(', ', $post->requisition_code ?? []) . ' reversed']]);
        exit;
    }
    
    public function _return()
    {
    
        if ($this->module_access->requisition->_main !== '1') {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }
        
        $post = $this->post;
        
        $sql = "UPDATE act_requisition SET status = '0', notification = CONCAT(COALESCE(notification, ''), ',{\"event\":\"REJECT\",\"dateTime\":\"" . date('Y-m-d H:i:s') . "\",\"username\":\"" . $this->user->username . "\",\"reason\":\"" . $post->return_reason . "\"}') WHERE requisition_code IN ('" . implode('\', \'', $post->requisition_code ?? []) . "')";
        // echo json_encode(['status' => false, 'message' => $sql]); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        // var_dump($result);exit;
        
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'UPDATE', 'remarks' => json_encode($post)]);
        
        //
        echo json_encode(['status' => true, 'data' => ['message' => 'Requisition: ' . implode(', ', $post->requisition_code ?? []) . ' rejected']]);
        exit;
    }
    
    public function _process()
    {
    
        $post = $this->post;
        // var_dump($this->module_access);exit;
    
        if (
            // check
            $this->module_access->requisition->_main !== '1' && $post->list_option == '0' ||
            // authorise
            $this->module_access->requisition->authorise !== '1' && $post->list_option == '1' ||
            // verify
            // $this->module_access->requisition->verify !== '1' && $post->list_option == '2' ||
            // approve
            $this->module_access->requisition->approve !== '1' && $post->list_option == '2'
        ) {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }
        // var_dump($post);exit;
        
        //
        switch ($post->list_option) {
            case '0':
                $event = "CHECK";
                $process_by = "check_by = '{$post->username}', check_on = '" . date('Y-m-d H:i:s') . "'";
                break;
            case '1':
                $event = "AUTHORISE";
                $process_by = "authorise_by = '{$post->username}', authorise_on = '" . date('Y-m-d H:i:s') . "'";
                break;
            // case '2':
            //     $event = "VERIFY";
            //     $process_by = "verify_by = '{$post->username}', verify_on = '" . date('Y-m-d H:i:s') . "'";
            //     break;
            case '2':
                $event = "APPROVE";
                $process_by = "approve_by = '{$post->username}', approve_on = '" . date('Y-m-d H:i:s') . "'";
                break;
        }
    
        $post->requisition_error = [];
        foreach ($post->requisition_code as $requisition_code) {
            $requisition = $this->model('AccountData')->getRequisition(['requisition_code' => $requisition_code]);
            // var_dump($post->list_option);exit;
            // AUTHORISE
            if (!DISABLE_MAKER_CHECKER && $post->list_option == '1' && ($post->username == $requisition->submit_by ?? $requisition->modify_by ?? '')) {
                $post->requisition_code = array_diff($post->requisition_code, [$requisition_code]);
                $post->requisition_error[] = $requisition_code;
            } // APPROVE
            else if (!DISABLE_MAKER_CHECKER && $post->list_option == '2' && ($post->username == $requisition->authorise_by ?? '')) {
                $post->requisition_code = array_diff($post->requisition_code, [$requisition_code]);
                $post->requisition_error[] = $requisition_code;
                // var_dump($post->requisition_error);exit;
            } 
            // APPROVE
            // else if (!DISABLE_MAKER_CHECKER && $post->list_option == '3' && ($post->username == $requisition->verify_by ?? '')) {
            //     $post->requisition_code = array_diff($post->requisition_code, [$requisition_code]);
            //     $post->requisition_error[] = $requisition_code;
            // }
            //  // PROCESS
            // else if (!DISABLE_MAKER_CHECKER && $post->list_option == '4' && ($post->username == $requisition->approve_by ?? '')) {
            //     $post->requisition_code = array_diff($post->requisition_code, [$requisition_code]);
            //     $post->requisition_error[] = $requisition_code;
            // }
        }
        // echo json_encode(['status' => false, 'message' => $post->requisition_code]); exit;
        if (empty($post->requisition_code)) {
            echo json_encode(['status' => false, 'message' => $event . ' NOT ALLOWED']);
            exit;
        }
    
        $sql = "UPDATE act_requisition SET status = '" . ($post->list_option + 1) . "', notification = CONCAT(COALESCE(notification, ''), ',{\"event\":\"{$event}\",\"dateTime\":\"" . date('Y-m-d H:i:s') . "\",\"username\":\"" . $this->user->username . "\",\"reason\":\"\"}'), {$process_by} WHERE requisition_code IN ('" . implode('\', \'', $post->requisition_code ?? []) . "')";
        //echo json_encode(['status' => false, 'message' => $post]); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        // var_dump($result);exit;
        
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'UPDATE', 'remarks' => json_encode($post)]);
        
        //
        echo json_encode(['status' => true, 'data' => ['message' => $event . ' ' . implode(', ', $post->requisition_code ?? [])], 'error' => ['status' => !empty($post->requisition_error), 'message' => 'NOT ' . $event . ' ' . implode(', ', $post->requisition_error ?? [])]]);
    
        // [Process] -> Cashbook
        
        // [Process] -> Cashbook
        if ($post->list_option == '2') {
            $accounts = $this->model('AccountData')->getAccounts(array("_option"=>'object'));
            
           // $fields = ['trans_code', 'trans_date', 'trans_type', 'trans_mode', 'trans_detail', 'branch_code', 'payment_code', 'currency_code', 'currency_rate', 'amount', 'debit_account', 'credit_account', 'submit_by', 'approve_by'];
        
            $fields_payment = ['trans_code', 'requisition_code', 'beneficiary_name', 'account_bank', 'account_name', 'account_number', 'prop_rate', 'prop_amount'];
            
            $fields_gl = ['account_code', 'account_name', 'debit', 'credit', 'trans_code', 'trans_type', 'ref_code', 'branch_code', 'trans_date', 'trans_detail', 'currency_code', 'currency_rate', 'submit_by', 'approved_by'];
            
            $count = 0;
            // $sql = "";
            $sql_payment = "";
            //
            $sql_gl = "";
            $count_gl = 0;
            //
            foreach ($post->requisition_code as $requisition_code) {
                
                $count++;
                $count_gl++;
                
                $requisition = $this->model('AccountData')->getRequisition(['requisition_code' => $requisition_code, 'list_option' => '5', 'username' => $this->user->username, 'user_group' => $this->user->group_code]);
                //echo json_encode(['status' => false, 'message' => $requisition]); exit;
                if (empty($requisition)) {
                    $count--;
                    $count_gl--;
                    continue;
                }
    
                // YYMM00000000
                //$requisition->trans_code = $this->model('SystemData')->newAuto(['auto_item' => $requisition->trans_code, 'digit' => '00000000', 'prefix' => date('ym'), 'suffix' => '', 'serial' => 'REQUISITION-' . date('Ym'), 'field' => 'trans_code', 'table' => 'act_requisition']);
                $requisition->trans_code = $requisition->requisition_code;
            
                $requisition->payment_code = $requisition->ref_code = $requisition->requisition_code;
                $requisition->trans_date = date('Y-m-d H:i:s');
                $requisition->trans_type = 'PAY';
                $requisition->trans_mode = 'TRF';
                $requisition->trans_detail = $requisition->description;
            
                //$sql .= $this->model('SystemData')->sqlQuery(['array' => [$requisition], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_cashbook', 'option' => $count > 1 ? 'ADD' : '']);
                
                $requisition_payment = clone $requisition;
                
                $requisition_payment->account_name = $requisition_payment->beneficiary_name;
                $requisition_payment->prop_rate = '100';
                $requisition_payment->prop_amount = $requisition_payment->amount;
                
                $sql_payment .= $this->model('SystemData')->sqlQuery(['array' => [$requisition_payment], 'action' => 'INSERT', 'fields' => $fields_payment, 'table' => 'act_cashbook_payment', 'option' => $count > 1 ? 'ADD' : '']);
                
                // Ledger
                $gl = clone $requisition_payment;
                // var_dump($gl->account_name);exit;
                
                // DR: Expense
                $gl->account_code = $requisition->debit_account; 
                $gl->account_name = $accounts->{$requisition->debit_account}->account_name;
                $gl->debit = $requisition->amount;
                $gl->credit = '0';
    
                //
                $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => $count_gl > 1 ? 'ADD' : '']);
                
                // CR: Liability
                $gl->account_code = $requisition->credit_account;
                $gl->account_name = $accounts->{$requisition->credit_account}->account_name;
                $gl->debit = '0';
                $gl->credit = $requisition->amount;
    
                //
                $sql_gl .= $this->model('SystemData')->sqlQuery(['array' => [$gl], 'action' => 'INSERT', 'fields' => $fields_gl, 'table' => 'act_gledger', 'option' => 'ADD']);
            }
            if ($sql_payment != "") {
                $this->db->query($sql_payment);
                $sss = $this->db->execute();
            }
            if ($sql_gl != "") {
                $this->db->query($sql_gl);
                $sss = $this->db->execute();
                // var_dump($sss);exit;
            }
        }
        exit;
    }
    
    public function _save()
    {
    
        if ($this->module_access->requisition->_main !== '1') {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }
        
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
        //echo json_encode(['status' => false, 'message' => $_POST]); exit;
    
        if (empty($post->requisition_code) && !empty($post->requisition_code_old)) {
            echo json_encode(['status' => false, 'message' => 'REQUISITION CODE REQUIRED']);
            exit;
        }
    
        $trans_date = date_create_from_format('Y-m-d', $post->trans_date);
        if (!$trans_date) {
            echo json_encode(['status' => false, 'message' => 'TRANSACTION DATE INVALID']);
            exit;
        }
    
        if (empty($post->requisition_group)) {
            echo json_encode(['status' => false, 'message' => 'BUDGET GROUP REQUIRED']);
            exit;
        }
    
        if ($post->amount <= 0) {
            echo json_encode(['status' => false, 'message' => 'AMOUNT REQUIRED']);
            exit;
        }
    
        if (empty($post->currency_code) || $post->currency_rate <= 0) {
            echo json_encode(['status' => false, 'message' => 'CURRENCY REQUIRED']);
            exit;
        }
    
        if (empty($post->priority)) {
            echo json_encode(['status' => false, 'message' => 'PRIORITY REQUIRED']);
            exit;
        }
    
        if (empty($post->description)) {
            echo json_encode(['status' => false, 'message' => 'DESCRIPTION REQUIRED']);
            exit;
        }
        //
        $requisition_budget = $this->model('AccountData')->getRequisitionBudget(['_option' => 'group_year', 'user_group' => $this->data['user']['group_code'], 'year' => $trans_date->format('Y'), 'requisition_code' => $post->requisition_code_old]);
        
        // var_dump($requisition_budget);exit;
        // echo json_encode(['status' => false, 'message' => $requisition_budget]); exit;
        
        $balance = (float)($requisition_budget->capex ?? '') + (float)($requisition_budget->opex ?? '') - (float)($requisition_budget->amount ?? '');
        
        // var_dump($balance);exit;
        if ($balance - $post->amount < 0) {
            echo json_encode(['status' => false, 'message' => 'LOW BUDGET BALANCE: ' . number_format($balance)]);
            exit;
        }
    
        // INSERT
        $fields = ['requisition_code', 'requisition_group', 'user_group', 'branch_code', 'trans_date', 'status', 'amount', 'currency_code', 'currency_rate', 'description', 'priority', 'beneficiary_name', 'account_bank', 'account_number', 'submit_by'];
        
        $post->submit_by = $post->modify_by = $this->user->username;
        $post->user_group = $this->data['user']['group_code'];
        $post->branch_code = $this->data['user']['branch_code'];
        $post->status = '0';
    
        // check record
        $requisition = $this->model('AccountData')->getRequisition(['requisition_code' => $post->requisition_code_old]);
        
        if (!$requisition) {
    
            // ICT/YYMMDD/000004
            $post->requisition_code = $this->model('SystemData')->newAuto(['auto_item' => $post->requisition_code, 'digit' => '000000', 'prefix' => $this->data['user']['group_code'] . '/' . date('Ymd') . '/', 'serial' => 'REQUISITION-' . date('Y'), 'field' => 'requisition_code', 'table' => 'act_requisition']);
            
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_requisition', 'option' => ""]);
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, ['submit_by']);
            $fields[] = 'modify_by';
            $fields[] = 'modify_on'; $post->modify_on = date('Y-m-d H:i:s');
            
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'act_requisition', 'option' => "WHERE requisition_code = '" . addslashes($post->requisition_code_old ?? '') . "' LIMIT 1"]);
        }
        //echo json_encode(['status' => false, 'message' => $sql]); exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        // var_dump($result);exit;
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$requisition ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post)]);
        
        echo json_encode(['status' => true, 'message' => 'Requisition ' . $post->requisition_code . ' saved', 'data' => ['requisition_code' => $post->requisition_code]]);
        exit;
    }
}