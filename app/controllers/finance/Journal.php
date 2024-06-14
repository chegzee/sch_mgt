<?php


require_once 'AccountSetting.php';
class Journal extends AccountSetting
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
        if ($this->user->access->finance->tool !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?user_log=' . $this->data['params']['user_log']);
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        
        // currencyObj
        $this->data['currencyObj'] = $this->getCurrencyObj();
    
        // accountObj
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
        $this->view('finance/journal', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $journals = $this->model('AccountData')->getJournals(array('_option' => 'summary', 'trans_type' => 'JNL'));
        //var_dump($journals); exit;
        
        echo json_encode(array('data' => $journals));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
    
        if ($this->userAccess['finance']['admin'] !== '1') {
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;
        }
    
        $journal = $this->model('AccountData')->getJournals(array('_option' => 'summary-detail', 'trans_type' => 'JNL', 'trans_code' => $post->trans_code));
        if (!$journal) {
            echo json_encode(array('status' => false, 'message' => 'Journal not found'));
            exit;
        }
    
        // period
        $period = $this->model('AccountData')->getAccountPeriod(array('trans_date' => $journal->trans_date, 'username' => $this->user->username));
        if (!$period) {
            echo json_encode(array('status' => false, 'message' => 'LEDGER POSTING NOT ALLOWED'));
            exit;
        }
        else if ($period->journal !== '1') {
            echo json_encode(array('status' => false, 'message' => 'LEDGER JOURNAL NOT ALLOWED'));
            exit;
        }
    
        // ledger
        $this->db->query("DELETE FROM act_gledger WHERE trans_code LIKE '{$post->trans_code}%' AND ref_code = '{$post->ref_code}' AND trans_type LIKE 'JNL%'");
        $this->db->execute();
    
        //
        echo json_encode(array('status' => true, 'data' => array('message' => 'journal deleted')));
        exit;
    }
    
    /**
     * @throws Exception
     */
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
        
        $post->journal = json_decode(html_entity_decode($post->journal));
        if (empty($post->journal)) {
            echo json_encode(array('status' => false, 'message' => 'JOURNAL ENTRIES REQUIRED'));
            exit;
        }
    
        // period
        $period = $this->model('AccountData')->getAccountPeriod(array('trans_date' => $post->journal[0]->trans_date, 'username' => $this->user->username));
        if (!$period) {
            echo json_encode(array('status' => false, 'message' => 'LEDGER POSTING NOT ALLOWED'));
            exit;
        }
        else if ($period->journal !== '1') {
            echo json_encode(array('status' => false, 'message' => 'LEDGER JOURNAL NOT ALLOWED'));
            exit;
        }
        
        // default branch
        $branches = $this->model('SystemData')->getBranches(array('_option' => 'default'));
        
        if (empty($post->branch_code))
            $post->branch_code = $branches->branch_code;
        
        // INSERT
        $fields = array('account_code', 'account_name', 'debit', 'credit', 'trans_code', 'trans_type', 'ref_code', 'branch_code', 'trans_date', 'trans_detail', 'beneficiary', 'currency_code', 'currency_rate', 'submit_by', 'modified_by', 'approved_by');
    
        // check record
        $journal = $this->model('AccountData')->getJournals(array('_option' => 'summary', 'trans_type' => 'JNL', 'trans_code' => $post->trans_code));
        
        if (!empty($journal) && $post->trans_code !== $post->trans_code_old) {
            echo json_encode(array('status' => false, 'message' => 'TRANS CODE EXISTS'));
            exit;
        }
    
        $post->submit_by = empty($journal->trans_code_old) ? $this->user->username : $journal->submit_by;
        $post->modified_by = $post->approved_by = $this->user->username;
    
        // JN/2022/000002
        $post->trans_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->trans_code, 'digit' => '000000', 'prefix' => 'JN/' . date('Y') . '/', 'suffix' => '', 'serial' => 'JOURNAL-' . date('y'), 'field' => 'trans_code', 'table' => 'act_gledger'));
    
        if (!empty($post->trans_code_old)) {
            $this->db->query("DELETE FROM act_gledger WHERE trans_code = '" . $post->trans_code_old . "' AND trans_type = 'JNL'");
            $this->db->execute();
        }
        
        $sql = '';
        $add = '';
    
        foreach ($post->journal as $v) {
            
            $v->trans_code = $post->trans_code;
            $v->trans_type = 'JNL';
            $v->branch_code = $post->branch_code;
            $v->submit_by = $post->submit_by;
            $v->modified_by = $post->modified_by;
            $v->approved_by = $post->approved_by;
            
            $sql .= $this->model('SystemData')->sqlQuery(array('array' => array($v), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_gledger', 'option' => $add));
            $add = 'ADD';
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
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$journal ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post_log)]);
        
        //
        $journal = $this->model('AccountData')->getJournals(array('_option' => 'summary', 'trans_type' => 'JNL', 'trans_code' => $post->trans_code));
        $url = URL_ROOT . '/finance/journal/?user_log=' . $this->data['params']['user_log'];
    
        echo json_encode(array('status' => true, 'data' => array('url' => $url, 'trans_code' => $post->trans_code), 'journal' => $journal));
        exit;
    }
}