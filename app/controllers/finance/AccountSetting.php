<?php

class AccountSetting extends Controller
{
    //
    public function getBanks($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $banks = $this->model('AccountData')->getBanks((array)$post);
        // var_dump($banks); exit;
        
        echo json_encode($banks);
        exit;
        
    }
    //
    public function getBank($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $bank = $this->model('AccountData')->getBank((array)$post);
        // var_dump($bank); exit;
        
        echo json_encode($bank);
        exit;
        
    }

    public function getClients(){
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $std = $this->model('SystemData')->getStudents((array)$post);
        // var_dump($std); exit;
        
        echo json_encode($std);
        exit;
    } 

    
    //
    public function getParents($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $parents = $this->model('SystemData')->getParents((array)$post);
        // var_dump($parents); exit;
    
        if ($post->_option === 'select') {
            echo json_encode($parents);
            exit;
        }
        
        echo json_encode(array('data' => $parents));
        exit;
    }
    
    public function getStudentForInvoice(){
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $std = $this->model('SystemData')->getStudentForInvoice((array)$post);
        echo json_encode($std);
        exit;
    } 

    public function getInvoices(){
        //
        $post = (array) filter_input_array(INPUT_POST);
        //
        // var_dump($post); exit;
        $invoices = $this->model('AccountData')->getInvoices($post);
        // if($post['search']['value'] === ""){
        //     echo json_encode(array("data" => $invoices));
        //     exit;
        //     // var_dump("scared");exit;
        // }
        // echo json_encode($invoices);
        echo json_encode(array("data" => $invoices));
        exit;
        
        // echo json_encode($std);
        // exit;
    } 
    
    //
    public function getCompanyBanks($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $banks = $this->model('AccountData')->getCompanyBanks((array)$post);
        // var_dump($banks); exit;
        
        echo json_encode($banks);
        exit;
        
    }
    //
    public function getCompanyBank($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $bank = $this->model('AccountData')->getCompanyBank((array)$post);
        // var_dump($bank); exit;
        
        echo json_encode($bank);
        exit;
        
    }
    //
    public function getCurrencies($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $currencies = $this->model('AccountData')->getCurrencies((array)$post);
        // var_dump($currencies); exit;
        
        echo json_encode($currencies);
        exit;
    }
    //
    public function getTransModes($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $trans_mode = $this->model('AccountData')->getTransModes((array)$post);
        // var_dump($currencies); exit;
        
        echo json_encode($trans_mode);
        exit;
    }
    //
    public function getCurrency($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $currency = $this->model('AccountData')->getCurrency((array)$post);
        // var_dump($currency); exit;
        
        echo json_encode($currency);
        exit;
    }
    //
    public function getAccounts($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        // var_dump($post);exit;
        //
        $accounts = $this->model('AccountData')->getAccounts((array)$post);
        // var_dump($accounts); exit;
    
        echo json_encode($accounts);
        exit;
        
    }
    
    //
    public function getCurrencyObj($arg = array()) {
        //
        $currencies = $this->model('AccountData')->getCurrencies($arg);
        // var_dump($currencies); exit;
        
        $currencyObj = (object)array();
        if (!empty($currencies)) {
            foreach ($currencies as $v) {
                $currencyObj->{$v->currency_code} = $v;
            }
        }
    
        return $currencyObj;
    
    }
    //
    public function getAccount($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $account = $this->model('AccountData')->getAccount((array)$post);
        // var_dump($account); exit;
    
        echo json_encode($account);
        exit;
    }
    //
    public function getActgroups($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $actgroups = $this->model('AccountData')->getActgroups((array)$post);
        // var_dump($actgroups); exit;
    
        echo json_encode($actgroups);
        exit;
    }
    //
    public function getActgroup($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $actgroup = $this->model('AccountData')->getActgroup((array)$post);
        // var_dump($actgroup); exit;
        
        echo json_encode($actgroup);
        exit;
    }
    //
    public function getActbases($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $actbase = $this->model('AccountData')->getActbases((array)$post);
        // var_dump($actbase); exit;
    
        echo json_encode($actbase);
        exit;
    }
    //
    public function getActbase($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $actbase = $this->model('AccountData')->getActgroup((array)$post);
        // var_dump($actbase); exit;
        
        echo json_encode($actbase);
        exit;
    }

    public function getAllBanksName($arg = array()){
        $post = (object) filter_input_array(INPUT_POST);
        //
        $banksName = $this->model('AccountData')->getAllBanksName((array)$post);
        // var_dump($actbase); exit;
        
        echo json_encode($banksName);
        exit;

    }
    //
    public function getTechsetup($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $techsetup = $this->model('AccountData')->getTechsetup((array)$post);
        // var_dump($techsetup); exit;
        
        echo json_encode($techsetup);
        exit;
    }
    //
    public function getOpenbalance($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $openbalance = $this->model('AccountData')->getOpenbalance((array)$post);
        // var_dump($openbalance); exit;
        
        echo json_encode($openbalance);
        exit;
    }
    
    //
    public function getReceipts($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $receipts = $this->model('AccountData')->getReceipts((array)$post);
        // var_dump($receipts); exit;
        
        echo json_encode(array('data' => $receipts));
        exit;
    }
    
    //
    public function getPayments($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $payments = $this->model('AccountData')->getPayments((array)$post);
        // var_dump($payments); exit;
        
        echo json_encode(array('data' => $payments));
        exit;
    }
    
    //
    public function getBusTypes($arg = array()) {
        //
        $busTypes = false;
        // _option -> receivable
        if (($arg['_option'] ?? '') === 'receivable') {
            $busTypes = array(
                (object)array('id' => 'RCP', 'text' => 'Broker'),
                (object)array('id' => 'RCP2', 'text' => 'Insurance'),
            );
        }
        // _option -> payable
        else if (($arg['_option'] ?? '') === 'payable') {
            $busTypes = array(
                (object)array('id' => 'PYM', 'text' => 'Direct'),
                //(object)array('id' => 'PYM2', 'text' => 'Insurance'),
            );
        }
        // var_dump($busTypes); exit;
        
        return $busTypes;
    }
    
    public function getJournals($arg = array())
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $journals = $this->model('AccountData')->getJournals((array)$post);
        //var_dump($journals); exit;
        
        echo json_encode($journals);
        exit;
    }
    
    //
    public function getTransModeObj($arg = array()) {
        //
        $transModes = $this->model('SystemData')->getTransModes($arg);
        //var_dump($transModes); exit;
        
        $transModeObj = (object)array();
        if (!empty($transModes)) {
            foreach ($transModes as $v) {
                $transModeObj->{$v->trans_mode} = $v;
            }
        }
    
        return $transModeObj;
    
    }
    
    ///////////////
    public function getLedgerAccounts($arg = []) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $accounts = $this->model('AccountData')->getLedgerAccounts((array)$post);
        
        echo json_encode($accounts);
        exit;
        
    }
    // /////////////////////////////////////////////////////////////
    public function getCashbookBalance($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        // var_dump($post);exit;
        //
        echo json_encode($this->model('AccountData')->getCashbookBalance((array)$post));
        exit;
    }

    public function getCashbooks($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        echo json_encode($this->model('AccountData')->getCashbooks((array)$post));
        exit;
    }
    //
    public function getCashbook($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        echo json_encode($this->model('AccountData')->getCashbook((array)$post));
        exit;
    }

    
    //
    public function getRequisitions($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->getRequisitions((array)$post));
        exit;
    }
    //
    public function getRequisition($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->getRequisition((array)$post));
        exit;
    }
    //
    public function requisitionStatuses($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->requisitionStatuses((array)$post));
        exit;
    }
    //
    public function requisitionActivities($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->requisitionActivities((array)$post));
        exit;
    }
    //
    public function getRequisitionGroups($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->getRequisitionGroups((array)$post));
        exit;
    }
    //
    public function getRequisitionGroup($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->getRequisitionGroup((array)$post));
        exit;
    }
    //
    public function getRequisitionBudgets($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        // var_dump($post);exit;
        echo json_encode($this->model('AccountData')->getRequisitionBudgets((array)$post));
        exit;
    }
    //
    public function getRequisitionBudget($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->getRequisitionBudget((array)$post));
        exit;
    }
    //
    public function getBudgetTypes($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model('AccountData')->getBudgetTypes((array)$post));
        exit;
    }
    //
    public function getBalance($arg = [])
    {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        echo json_encode($this->model("AccountData")->getBalance((array)$post));
        exit;
    }
}