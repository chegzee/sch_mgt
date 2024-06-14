<?php

class RequisitionBudget extends Controller
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
        unset($this->params_['requisition_budget']);
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(['user_log' => $this->data['params']['user_log']]);
        // var_dump($this->user->access->finance->requisition);exit;
        if (!$this->user) {
            if ('xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' )) { echo json_encode(['status' => false, 'message' => 'SESSION EXPIRED', 'data' => ['url' => 'Location: ' . URL_ROOT . '/system/login']]); exit; }
            header('Location: ' . URL_ROOT . '/system/login');
        }
        if ($this->user->access->finance->budget_expense !== '1') header('Location: ' . URL_ROOT . '/system/dashboard/?' . urldecode(http_build_query($this->params_)));
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
        // var_dump($this->data['requisitionGroups']);exit;
        // userGroups
        $this->data['userGroups'] = $this->model('SystemData')->getUserGroups(['_option' => 'object']);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', []);
        // body
        $this->view('finance/requisitionBudget', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        echo json_encode($this->model('AccountData')->getRequisitionBudgets((array)$this->post));
        exit;
    }

    public function _process() {

        $post = $this->post;

        if ($this->module_access->finance->budget_expense !== '1') {
            echo json_encode(['status' => false, 'message' => 'ACCESS DENIED']);
            exit;
        }

        $success = '';
        $error = '';

        $requisition_budget = implode("', '", json_decode($post->requisition_budget ?? '',true));
        $sql = "SELECT arb.requisition_budget, arb.submit_by, arb.modify_by FROM act_requisition_budget arb WHERE arb.auto_id IN ('{$requisition_budget}')";
        $this->db->query($sql);

        foreach ($this->db->resultSet() ?? [] as $k => $v) {

            // reserve
            if ($post->func === 'reverse') {
                //
                $sql = "DELETE FROM act_requisition_budget WHERE requisition_budget = '{$v->requisition_budget}' LIMIT 10";
                $this->db->query($sql);
                $result = $this->db->execute();

                if ($result !== true) {
                    $error .= '<br>' . $v->requisition_budget . ' HAS DETAIL.';
                    continue;
                }
                //
                $success .= '<br>' . $v->requisition_budget . ' REVERSED';
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
        
        $sql = "DELETE FROM act_requisition_budget WHERE requisition_budget = '{$post->requisition_budget}' LIMIT 1";
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
        echo json_encode(['status' => true, 'data' => ['message' => 'Requisition budget reversed']]);
        exit;
    }
    
    public function _save()
    {
    
        if ($this->module_access->finance->budget_expense !== '1') {
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
    
        if (empty($post->requisition_budget) && !empty($post->requisition_budget_old)) {
            echo json_encode(['status' => false, 'message' => 'REQUISITION BUDGET REQUIRED']);
            exit;
        }
    
        if (empty($post->user_group)) {
            echo json_encode(['status' => false, 'message' => 'USER GROUP REQUIRED']);
            exit;
        }
    
        if (empty($post->year)) {
            echo json_encode(['status' => false, 'message' => 'BUDGET YEAR REQUIRED']);
            exit;
        }
        
        // INSERT
        $fields = ['requisition_budget', 'user_group', 'year', 'capex', 'opex', 'submit_by'];
        
        $post->submit_by = $post->modify_by = $this->user->username;
    
        // check record
        $requisition_budget = $this->model('AccountData')->getRequisitionBudget(['requisition_budget' => $post->requisition_budget_old]);
        if (!$requisition_budget) {
            
            $user_group = $this->model('SystemData')->getUserGroup(['group_code' => $post->user_group]);
    
            // BGT/YYYY/ICT/000004
            $post->requisition_budget = $this->model('SystemData')->newAuto(['auto_item' => $post->requisition_budget, 'digit' => '000000', 'prefix' => 'BGT/' . date('Y') . '/' . $user_group->group_code . '/', 'serial' => 'BUDGET-' . date('Y'), 'field' => 'requisition_budget', 'table' => 'act_requisition_budget']);
            
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'INSERT', 'fields' => $fields, 'table' => 'act_requisition_budget', 'option' => ""]);
            // var_dump($sql);exit;
        }
        // UPDATE
        else {
    
            $fields = array_diff($fields, ['submit_by']);
            $fields[] = 'modify_by';
            $fields[] = 'modify_on'; $post->modify_on = date('Y-m-d H:i:s');
            
            $sql = $this->model('SystemData')->sqlQuery(['array' => [$post], 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'act_requisition_budget', 'option' => "WHERE requisition_budget = '" . addslashes($post->requisition_budget_old ?? '') . "' LIMIT 1"]);
        }
        //echo json_encode(['status' => false, 'message' => $sql]); exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if ($result !== true) {
            echo json_encode(['status' => false, 'message' => $result]);
            exit;
        }
    
        // log
        $this->model('SystemData')->userLog(['username' => $this->user->username, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'event_log' => (!$requisition_budget ? 'INSERT' : 'UPDATE'), 'remarks' => json_encode($post)]);
        
        echo json_encode(['status' => true, 'message' => 'Requisition Budget ' . $post->requisition_budget . ' saved', 'data' => ['requisition_budget' => $post->requisition_budget]]);
        exit;
    }
}