<?php

class RequisitionGroup extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        // var_dump($_REQUEST); exit;
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
        if ($this->user->access->finance->requisition_setup !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?' . urldecode(http_build_query($this->params_)));
        $this->data['user'] = (array)$this->user;
        // var_dump($this->user);exit;
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        //echo '<pre>'.print_r($this->data['user'], true).'</pre>'; exit;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
        // access
        $this->module_access = $this->user->access;
    
        // budgetTypes
        $this->data['budgetTypes'] = $this->model('AccountData')->getBudgetTypes([]);

    
        // accountObj
        $this->data['accountObj'] = $this->model('AccountData')->getLedgerAccounts(['_option' => 'object']);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', []);
        // body
        $this->view('finance/requisitionGroup', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        echo json_encode($this->model('AccountData')->getRequisitionGroups((array)$this->post));
        exit;
    }

    public function _process() {

        $post = $this->post;

        if ( $this->module_access->finance->requisition_setup !== '1') {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }

        $success = '';
        $error = '';

        $requisition_group = implode("', '", json_decode($post->requisition_group ?? '',true));
        $sql = "SELECT arg.requisition_group, arg.submit_by, arg.modify_by FROM act_requisition_group arg WHERE arg.auto_id IN ('{$requisition_group}')";
        $this->db->query($sql);

        foreach ($this->db->resultSet() ?? [] as $k => $v) {

            // reserve
            if ($post->func === 'reverse') {
                //
                $sql = "DELETE FROM act_requisition_group WHERE requisition_group = '{$v->requisition_group}' LIMIT 10";
                $this->db->query($sql);
                $result = $this->db->execute();

                if ($result !== true) {
                    $error .= '<br>' . $v->requisition_group . ' HAS DETAIL.';
                    continue;
                }
                //
                $success .= '<br>' . $v->requisition_group . ' REVERSED';
            }
        }

        echo json_encode(['status' => true, 'message' => '', 'data' => ['success' => $success, 'error' => $error]]);
        exit;
    }
    
    public function _delete()
    {
    
        if ($this->module_access->finance->requisition->setup < 3) {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }
        
        $post = $this->post;
        
        $sql = "DELETE FROM act_requisition_group WHERE requisition_group = '{$post->requisition_group}' LIMIT 1";
        //var_dump($sql); exit;
        
        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => 'DELETE', 'remarks' => json_encode($post)]);
        
        //
        echo json_encode(['status' => true, 'data' => ['message' => 'Requisition Group reversed']]);
        exit;
    }
    
    public function _save()
    {
    
        if ($this->module_access->finance->requisition_setup !== '1') {
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
    
        if (empty($post->requisition_group) && !empty($post->requisition_group_old)) {
            echo json_encode(['status' => false, 'message' => 'REQUISITION GROUP REQUIRED']);
            exit;
        }
        // var_dump(array_values($this->data['budgetTypes']));exit;
        if (!in_array($post->budget_type, array_values((array)$this->data['budgetTypes']))) {
            echo json_encode(['status' => false, 'message' => 'BUDGET TYPE INVALID']);
            exit;
        }
        
        // INSERT
        $fields = ['requisition_group', 'budget_type', 'debit_account', 'credit_account', 'submit_by'];
        
        $post->submit_by = $post->modify_by = $this->user->username;
    
        // check record
        $requisition_group = $this->model('AccountData')->getRequisitionGroup(['requisition_group' => $post->requisition_group_old]);
        
        if (!$requisition_group) {
    
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_requisition_group', 'option' => ""]);
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, ['submit_by']);
            $fields[] = 'modify_by';
            $fields[] = 'modify_on';
            $post->modify_on = date('Y-m-d H:i:s');
            
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'act_requisition_group', 'option' => "WHERE requisition_group = '" . addslashes($post->requisition_group_old ?? '') . "' LIMIT 1"]);
        }
        //echo json_encode(['status' => false, 'message' => $sql]); exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$requisition_group ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post)]);
    
        echo json_encode(['status' => true, 'message' => 'Requisition Group ' . $post->requisition_group . ' saved', 'data' => ['requisition_group' => $post->requisition_group]]);
        exit;
    }
}