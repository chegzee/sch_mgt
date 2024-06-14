<?php

class AccountData extends Controller
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database;
    }
    
    // Account /////////////////////////////////////////
    
    public function getBanks($arg = array())
    {
        $sql = "SELECT t1.* FROM act_bank t1 LEFT JOIN (SELECT t21.account_code, t21.account_name FROM act_account t21)t2 ON t1.account_code=t2.account_code WHERE 1=1";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.account_code id, CONCAT('(', t1.account_number, ') (', t1.account_name, ') (', t1.bank_name, ')') text FROM act_bank t1 WHERE t1.account_code LIKE '%" . $arg['searchTerm'] . "%' AND t1.status = '1' ";
        }

        if(!empty($arg['status'])){
            $sql = "SELECT t1.* FROM act_bank t1 WHERE t1.status = '".$arg['status']."' ";
            $this->db->query($sql);
            return $this->db->single();

        }

        if(($arg['_option'] ?? '') === 'account_code'){
            $sql .= ' AND t1.account_code = "'.$arg['account_code'].'" LIMIT 1';
            $this->db->query($sql);
            return $this->db->single();

        }
        
        $sql .= " ORDER BY t1.bank_name";
        //return $sql;
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    
    public function getAllBanksName($arg = [])
    {
    
        if ($arg['_option'] === 'select') {
            $sql = "SELECT t1.bank_name id, t1.bank_name text FROM cod_bank t1 WHERE t1.bank_name LIKE '%{$arg['searchTerm']}%' ORDER BY text ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }
    
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['t1.bank_name']
        ]);
        
        $sql = "SELECT t1.auto_id, t1.bank_name, t1.digit, t1.bank_name id, t1.bank_name text FROM cod_bank t1 WHERE 1=1 {$dataTable} ";
        
        $sql .= "ORDER BY t1.bank_name";
        //
        $sql .= " LIMIT " . DATATABLE_LIMIT;
    
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->bank_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
    
        // all
        $rowCount = $this->db->rowCount();
        // limit
        $sql = substr($sql, 0, stripos($sql, ' LIMIT ')) . " LIMIT {$arg['start']}, {$arg['length']}";
        $this->db->query($sql);
        $rows = $this->db->resultSet();
    
        return [
            "recordsTotal" => $rowCount,
            "recordsFiltered" => $rowCount,
            "data" => $rows,
        ];
        
    }

    
    private function dataTable($arg = []) {
        
        $_return = '';
        foreach ($arg['search'] as $v) {
            $_return .= " AND (" . implode(" LIKE '%{$v}%' OR ", $arg['fields']) . " LIKE '%{$v}%') ";
        }
        return $_return;
    }
    
    //
    public function getBank($arg = array()) {
        $this->db->query("SELECT t1.* FROM act_bank t1 WHERE t1.bank_account = '" . $arg['bank_account'] . "' LIMIT 1");
        
        return $this->db->single();
    }
    
    public function getCompanyBanks($arg = array())
    {
        $sql = "SELECT t1.auto_id, t1.bank_account, t1.account_code, t2.account_name FROM act_company t1 LEFT JOIN (SELECT t21.account_code, t21.account_name FROM act_account t21) t2 ON t1.account_code = t2.account_code WHERE 1=1 ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.bank_account id, t1.bank_account text FROM act_company t1 WHERE t1.bank_account LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        //select for bank statement
        if (($arg['_option'] ?? '') === 'select2') {
            $sql = "SELECT t1.account_code id, t1.bank_account text FROM act_company t1 WHERE t1.bank_account LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        
        if (($arg['_option'] ?? '') === 'account_code') {
            $sql = "SELECT t1.account_code id, t1.bank_account text FROM act_company t1 WHERE t1.bank_account LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        
        $sql .= "ORDER BY t1.bank_account";
        //return $sql;
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    //
    public function getRequisitions($arg = []) {
        
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['ar.requisition_code']
        ]);
        
        $status = "";
        $list_option = (int)$arg['list_option'];
        switch ($list_option) {
            case 0:
                $status .= "ar.status = '' OR ar.status >= '0'";
                break;
            case 1:
            case 2:
            case 3:
            case 4:
                $status .= "ar.status = '{$list_option}'";
                break;
            default:
                $status .= "ar.status > 4";
        }
        
        $sql = "
        SELECT arg.*, sug.*, su.*, ar.*, ar.requisition_code id, ar.requisition_code text
        FROM act_requisition ar
        LEFT JOIN (SELECT sug_.group_code, sug_.group_name FROM sys_user_group sug_) sug ON sug.group_code = ar.user_group
        LEFT JOIN (SELECT arg_.requisition_group, arg_.debit_account, arg_.credit_account FROM act_requisition_group arg_) arg ON arg.requisition_group = ar.requisition_group
        LEFT JOIN (SELECT su_.username, TRIM(CONCAT(COALESCE(su_.first_name, ''), ' ', COALESCE(su_.last_name, ''))) fullname FROM sys_user su_) su ON su.username = ar.submit_by
        WHERE ar.requisition_code LIKE '%{$arg['searchTerm']}%' {$dataTable} AND ({$status}) " . ($list_option <= 1 ? "AND ar.user_group = '{$arg['user_group']}'" : "") . " ";
        //return [$sql];

        // var_dump($sql);exit;
        
        if ($arg['sql']) {
            $sql = substr($sql, 0, stripos($sql, 'WHERE')) . " WHERE ar.requisition_code = '{$arg['requisition_code']}' ";
            return ['sql' => $sql];
        }
        
        $sql .= "ORDER BY ar.trans_date DESC ";
        //
        $sql .= " LIMIT " . DATATABLE_LIMIT;
        
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->requisition_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        
        // all
        $rowCount = $this->db->rowCount();
        // limit
        $sql = substr($sql, 0, stripos($sql, ' LIMIT ')) . " LIMIT {$arg['start']}, {$arg['length']}";
        $this->db->query($sql);
        $rows = $this->db->resultSet();
        
        return [
            "recordsTotal" => $rowCount,
            "recordsFiltered" => $rowCount,
            "data" => $rows,
        ];
    }
    //
    public function getRequisition($arg = []) {
        
        $arg = array_merge($arg, ['sql' => true, 'requisition_code' => $arg['requisition_code'] ?? '']);
        
        $this->db->query($this->getRequisitions($arg)['sql']);
        return $this->db->single();
    }
    //
    public function getRequisitionStatuses($arg = []) {
        
        return json_decode(json_encode([
            '0' => 'Pending',
            '1' => 'Checked',
            '2' => 'Authorised',
            '3' => 'Approved',
        ]));
    }
    //
    public function getRequisitionActivities($arg = []) {
        
        return json_decode(json_encode([
            '0' => 'Return requisition?',
            '1' => 'Proceed for authorisation?',
            '2' => 'Authorise selected?',
            '3' => 'Verify selected?',
            '4' => 'Approve selected?',
            '5' => 'Make payment on selected?',
        ]));
    }
    //
    public function getRequisitionGroups($arg = []) {
    
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['arg.requisition_group']
        ]);
        
        $sql = "
        SELECT aa1.debit_account_name, aa2.credit_account_name, arg.*, arg.requisition_group id, arg.requisition_group text
        FROM act_requisition_group arg
        LEFT JOIN (SELECT aa_.account_code, aa_.account_name debit_account_name FROM act_account aa_) aa1 ON aa1.account_code = arg.debit_account
        LEFT JOIN (SELECT aa_.account_code, aa_.account_name credit_account_name FROM act_account aa_) aa2 ON aa2.account_code = arg.debit_account
        WHERE arg.requisition_group LIKE '%{$arg['searchTerm']}%' {$dataTable} ";
        //return [$sql];
    
        if ($arg['sql']) {
            $sql .= "AND arg.requisition_group = '{$arg['requisition_group']}' ";
            return ['sql' => $sql];
        }
    
        $sql .= "ORDER BY arg.requisition_group ";
        //
        $sql .= " LIMIT " . DATATABLE_LIMIT;
    
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->requisition_group} = $v; }, $this->db->resultSet());
            return $obj;
        }
    
        // all
        $rowCount = $this->db->rowCount();
        // limit
        $sql = substr($sql, 0, stripos($sql, ' LIMIT ')) . " LIMIT {$arg['start']}, {$arg['length']}";
        $this->db->query($sql);
        $rows = $this->db->resultSet();
    
        return [
            "recordsTotal" => $rowCount,
            "recordsFiltered" => $rowCount,
            "data" => $rows,
        ];
    }
    //
    public function getRequisitionGroup($arg = []) {
        
        $arg = array_merge($arg, ['sql' => true, 'requisition_group' => $arg['requisition_group'] ?? '']);
        
        $this->db->query($this->getRequisitionGroups($arg)['sql']);
        return $this->db->single();
    }
    //
    public function getRequisitionBudgets($arg = []) {
    
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['arb.requisition_budget']
        ]);
        
        $budget_spent = "";
        if ($arg['_option'] === 'group_year') {
            $budget_spent = "SUM(ar.amount) amount";
        }
        
        $sql = "SELECT sug.*, arb.*, arb.requisition_budget id, arb.requisition_budget text FROM act_requisition_budget arb
            LEFT JOIN (SELECT sug_.group_code, sug_.group_name FROM sys_user_group sug_) sug ON sug.group_code = arb.user_group
            WHERE arb.requisition_budget LIKE '%{$arg['searchTerm']}%' {$dataTable} ";
        //return [$sql];
        if ($arg['sql']) {
            $sql .= "AND arb.requisition_budget = '{$arg['requisition_budget']}' ";
            if ($arg['_option'] === 'group_year') {
                $sql = str_ireplace('sug.*, ', 'sug.*, SUM(ar.amount) amount, ', $sql);
                $sql = substr($sql, 0, stripos($sql, 'WHERE')) . " LEFT JOIN (SELECT ar_.requisition_code, ar_.user_group, LEFT(ar_.trans_date, 4) `year`, ar_.amount * ar_.currency_rate amount FROM act_requisition ar_ WHERE NOT ar_.requisition_code = '{$arg['requisition_code']}') ar ON ar.user_group = arb.user_group AND ar.`year` = arb.`year` WHERE arb.user_group = '{$arg['user_group']}' AND arb.year = '{$arg['year']}' ";
                
            }
            return ['sql' => $sql];
        }
    
        $sql .= "ORDER BY arb.requisition_budget ";
        //
        $sql .= " LIMIT " . DATATABLE_LIMIT;
    
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->requisition_budget} = $v; }, $this->db->resultSet());
            return $obj;
        }
    
        // all
        $rowCount = $this->db->rowCount();
        // limit
        $sql = substr($sql, 0, stripos($sql, ' LIMIT ')) . " LIMIT {$arg['start']}, {$arg['length']}";
        $this->db->query($sql);
        $rows = $this->db->resultSet();
    
        return [
            "recordsTotal" => $rowCount,
            "recordsFiltered" => $rowCount,
            "data" => $rows,
        ];
    }
    //
    public function getRequisitionBudget($arg = []) {
        
        $arg = array_merge($arg, ['sql' => true, 'requisition_budget' => $arg['requisition_budget'] ?? '']);
        // return $this->getRequisitionBudgets($arg)['sql'];
        $this->db->query($this->getRequisitionBudgets($arg)['sql']);
        return $this->db->single();
    }
    //
    public function getBudgetTypes($arg = []) {
        
        return json_decode(json_encode([
            'CAPEX' => 'CAPEX',
            'OPEX' => 'OPEX',
        ]));
    }
    
    // currency ////////////////////////////////////////////////////
    
    public function getCurrencies($arg = array()) 
    {
        $sql = "SELECT t1.auto_id, t1.currency_code, t1.currency_main, t1.currency_sub, t1.currency_rate, t1.html_code, t1.remarks FROM cod_currency t1 WHERE 1=1 ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.currency_code id, t1.currency_code text FROM cod_currency t1 WHERE t1.currency_code LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        
        $sql .= "ORDER BY t1.currency_code ";
        
        //
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->currency_code} = $v; }, $this->db->resultSet());
            return $obj;
        }

        
        return $this->db->resultSet();
    }
    
    public function getTransModes($arg = array()) {
        $sql = "SELECT t1.auto_id, t1.trans_mode FROM act_trans_mode t1 WHERE 1=1 ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.trans_mode id, t1.trans_mode text FROM act_trans_mode t1 WHERE t1.trans_mode LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        
        $sql .= "ORDER BY t1.trans_mode ";
        
        //
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    public function getCurrency($arg = array())
    {
        
        $sql = "SELECT t1.* FROM cod_currency t1 WHERE t1.currency_code = '" . $arg['currency_code'] . "' LIMIT 1";
        
        $this->db->query($sql);
       
        return $this->db->single();
    }
    
    //
    public function getAccountMapping($arg = array())
    {
        $sql = "SELECT t1.auto_id, t1.item, t1.branch_code, t1.debit_account, t1.credit_account FROM sys_tec_act t1 WHERE t1.branch_code = '" . $arg['branch_code'] . "' ";
        
        $this->db->query($sql);
        $row = $this->db->resultSet();
        
        if (empty(($arg['_option'] ?? ''))) {
            return $row;
        }
        
        $response = (object)array();
        if (!empty($row)) {
            foreach ($row as $v) {
                $response->{$v->item} = $v;
            }
        }
        
        return $response;
    }

    public function getInvoices($arg = array()){
        // var_dump($arg);exit;
        $sql = "
        SELECT left(t1.trans_date, 10)trans_date, t1.bank_account, t1.invoice_code, t1.receipt_code, t1.amount invoice_amount, t1.currency_code, 
        t1.currency_rate, t1.level_fees, if(JSON_VALID(t1.other_fees), t1.other_fees, JSON_OBJECT())other_fees, 
        t2.std_code, CONCAT(t2.student_first_name, ' ', t2.student_last_name)std_name, t2.student_first_name, t2.student_last_name, t2.department, 
        t2.address std_address, t2.activities, t2.invoice_code std_invoice_code, t2.receipt_code std_receipt_code, t2.branch_code,t2.branch_name, t2.branch_address, t2.branch_logo,
        t2.class_code, t2.class_name, t2.cat_name, t2.fees, t2.cat_code, t2.term_code, t2.`year`, t2.term_name, t2.start_date, t2.end_date, 
        t2.branch_state, t2.branch_country,t3.receipt_obj, t3.total_receipt_amount, t1.amount - COALESCE(t3.total_receipt_amount, 0)balance_due,
        t2.parent_code, t2.parent_first_name, t2.parent_last_name, t2.parent_email, t2.parent_title,
        t2.parent_phone, t2.parent_gender, t2.parent_address, t2.parent_state, t2.parent_country, tbank.account_number, tbank.account_name, tbank.bank_name, CONCAT('(', tbank.account_number, ')(', tbank.account_name ,')', tbank.bank_name)bank
        FROM sch_invoice t1 LEFT JOIN (SELECT tb.* FROM act_bank tb)tbank ON tbank.account_code=t1.bank_account
        
        INNER JOIN (
            SELECT t22.std_code, if(JSON_VALID(t22.activities), t22.activities, JSON_OBJECT())activities, 
            t22.first_name student_first_name, t22.last_name student_last_name, t22.address, t22.department, t22.invoice_code, t22.receipt_code, t22.branch_code,
            t44.class_code, t44.class_name, t44.cat_name, t44.fees, t44.cat_code, t77.code term_code, t77.`year`, t77.term term_name,
            t77.start_date, t77.end_date, t33.branch_name, t33.alpha, t33.email, t33.phone, t33.phone2, t33.address branch_address, t33.picture branch_logo,
            t33.state branch_state, t33.country branch_country, t88.parent_code, t88.first_name parent_first_name,
            t88.last_name parent_last_name, t88.email parent_email, t88.title parent_title, t88.phone parent_phone,
            t88.gender parent_gender, t88.address parent_address, t88.state parent_state, t88.country parent_country
            FROM sch_std_history t22 LEFT JOIN 
            (
                SELECT t33.branch_code, t33.branch_name,t33.alpha, t33.email, t33.phone, t33.phone2, t33.address, t33.state, t33.country, t33.picture
                FROM cod_branch t33
                )t33 ON t22.branch_code = t33.branch_code LEFT JOIN 
            (
                SELECT t44.class_code, t66.cat_code, t44.class_name, t66.cat_name, t66.fees
                FROM sch_classes t44 LEFT JOIN sch_category t66 ON t44.cat_code = t66.cat_code 
            )t44 ON t22.class_code = t44.class_code LEFT JOIN 
            (
                SELECT t77.code, t77.`year`, t77.term, t77.start_date, t77.end_date FROM sch_term t77
            )t77 ON t22.term = t77.code LEFT JOIN 
            (
                SELECT t88.parent_code, t88.first_name, t88.last_name, t88.email, t88.title, t88.phone, t88.gender,
                t88.address, t88.state, t88.country FROM sch_parent t88
            )t88 ON t22.parent_code = t88.parent_code
        )t2 ON t1.invoice_code = t2.invoice_code
         
        LEFT JOIN(
            SELECT t33.invoice_code, t33.receipt_code, SUM(COALESCE(t33.amount, 0))total_receipt_amount, JSON_ARRAYAGG(JSON_OBJECT('receipt_code', t33.receipt_code, 'trans_date', LEFT(t33.trans_date, 10), 'ref_code', t33.ref_code, 'invoice_code',t33.invoice_code, 'client_fullname', t33.client_fullname, 'client_code', t33.client_code ,'amount', t33.amount))receipt_obj 
				FROM sch_receipt t33 GROUP BY t33.invoice_code , t33.receipt_code
        )t3 ON t1.invoice_code = t3.invoice_code
         
        WHERE 1=1 AND t2.term_code LIKE '%".$arg['term_code']."%'";
            
        // var_dump($sql);exit;
            
        ///////invoice object/////////////
        if (($arg['_option'] ?? '') === 'invoice_code') {
            $sql .= " AND t1.invoice_code = '" . $arg['invoice_code'] . "'";
            $this->db->query($sql);
            return $this->db->single();
        }
        

        ///////student by its code number/////////////
        if (($arg['_option'] ?? '') === 'std_code') {
            $sql .= " AND t2.std_code = '" . $arg['std_code'] . "'";
            $this->db->query($sql);
            $res =  $this->db->single();
            return $res;
            // var_dump($res); exit;
        }
        
        ///////student by its code number/////////////
        if (($arg['_option'] ?? '') === 'multiple') {
            $sql .= " AND t1.invoice_code IN ('{$arg['invoice_code']}') ";
            // $this->db->query($sql);
            // return $this->db->single();
        }

        $this->db->query($sql);
        $res = $this->db->resultSet();
        return $res;
    }
    //
    public function getReceipts($arg = array())
    {
        //
        $sql = "
        SELECT left(t1.trans_date, 10)trans_date, t1.invoice_code, t1.receipt_code, t1.amount invoice_amount, t1.currency_code, 
        t1.currency_rate, t1.level_fees, if(JSON_VALID(t1.other_fees), t1.other_fees, JSON_OBJECT())other_fees, 
        t2.std_code, CONCAT(t2.student_first_name, ' ', t2.student_last_name)std_name, t2.student_first_name, t2.student_last_name, t2.department, 
        t2.address std_address, t2.activities, t2.invoice_code std_invoice_code, t2.receipt_code std_receipt_code, t2.branch_code,t2.branch_name, t2.branch_address, t2.branch_logo,
        t2.class_code, t2.class_name, t2.cat_name, t2.fees, t2.cat_code, t2.term_code, t2.`year`, t2.term_name, t2.start_date, t2.end_date, 
        t2.branch_state, t2.branch_country,t3.receipt_obj, t3.total_receipt_amount, t1.amount - COALESCE(t3.total_receipt_amount, 0)balance_due,
        t2.parent_code, t2.parent_first_name, t2.parent_last_name, t2.parent_email, t2.parent_title,
        t2.parent_phone, t2.parent_gender, t2.parent_address, t2.parent_state, t2.parent_country
        FROM sch_invoice t1
        
        INNER JOIN (
            SELECT t22.std_code, if(JSON_VALID(t22.activities), t22.activities, JSON_OBJECT())activities, 
            t22.first_name student_first_name, t22.last_name student_last_name, t22.address, t22.department, t22.invoice_code, t22.receipt_code, t22.branch_code,
            t44.class_code, t44.class_name, t44.cat_name, t44.fees, t44.cat_code, t77.code term_code, t77.`year`, t77.term term_name,
            t77.start_date, t77.end_date, t33.branch_name, t33.alpha, t33.email, t33.phone, t33.phone2, t33.address branch_address, t33.picture branch_logo,
            t33.state branch_state, t33.country branch_country, t88.parent_code, t88.first_name parent_first_name,
            t88.last_name parent_last_name, t88.email parent_email, t88.title parent_title, t88.phone parent_phone,
            t88.gender parent_gender, t88.address parent_address, t88.state parent_state, t88.country parent_country
            FROM sch_std_history t22 LEFT JOIN 
            (
                SELECT t33.branch_code, t33.branch_name,t33.alpha, t33.email, t33.phone, t33.phone2, t33.address, t33.state, t33.country, t33.picture
                FROM cod_branch t33
            )t33 ON t22.branch_code = t33.branch_code LEFT JOIN 
            (
                SELECT t44.class_code, t66.cat_code, t44.class_name, t66.cat_name, t66.fees
                FROM sch_classes t44 LEFT JOIN sch_category t66 ON t44.cat_code = t66.cat_code 
            )t44 ON t22.class_code = t44.class_code LEFT JOIN 
            (
                SELECT t77.code, t77.`year`, t77.term, t77.start_date, t77.end_date FROM sch_term t77
            )t77 ON t22.term = t77.code LEFT JOIN 
            (
                SELECT t88.parent_code, t88.first_name, t88.last_name, t88.email, t88.title, t88.phone, t88.gender,
                t88.address, t88.state, t88.country FROM sch_parent t88
            )t88 ON t22.parent_code = t88.parent_code
        )t2 ON t1.invoice_code = t2.invoice_code
         
        INNER JOIN(
            SELECT t33.invoice_code, t33.receipt_code, SUM(COALESCE(t33.amount, 0))total_receipt_amount, JSON_ARRAYAGG(JSON_OBJECT('receipt_code', t33.receipt_code, 'trans_date', LEFT(t33.trans_date, 10), 'ref_code', t33.ref_code, 'invoice_code',t33.invoice_code, 'client_fullname', t33.client_fullname, 'client_code', t33.client_code ,'amount', t33.amount))receipt_obj 
				FROM sch_receipt t33 GROUP BY t33.invoice_code , t33.receipt_code
        )t3 ON t1.invoice_code = t3.invoice_code
        
        WHERE 1=1";

        // receipt
        if (!empty($arg['term_code'])) {
            //
            $sql .= " AND t2.term_code = '" . $arg['term_code'] . "' ";
            // $this->db->query($sql);
            // return $this->db->resulSet();
        }
        
        ///////student by its code number/////////////
        if (($arg['_option'] ?? '') === 'multiple') {
            // $sql = substr($sql, 0, stripos($sql, 'WHERE')) . " WHERE 1=1 ";
            $sql .= " AND t1.receipt_code IN ('{$arg['receipt_code']}')";
            
            // var_dump($sql);exit;
            $this->db->query($sql);
            return $this->db->resultSet();
        }

        // receipt
        if (!empty($arg['receipt_code']) && !empty($arg['invoice_code'])) {
            //
            $sql .= " AND t1.receipt_code = '" . $arg['receipt_code'] . "' AND t1.invoice_code = '".$arg['invoice_code']."'  ";
            $this->db->query($sql);
            return $this->db->single();
        }
        // receipt
        if (($arg['_option'] ?? '') === 'total') {
            //
            $sql = "SELECT t1.client_code, SUM(t1.amount)total_receipt_amount FROM sch_receipt t1 WHERE t1.client_code = '".$arg['client_code']."' GROUP BY t1.client_code";
            // var_dump($sql);exit;
            $this->db->query($sql);
            return $this->db->single();
        }

        
        $sql .= " ORDER BY t1.trans_date DESC";
        // var_dump($sql); exit;
        //return $sql;
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    //
    public function getAccountPeriod($arg = array())
    {
        // create if not exists
        $sql = "SELECT MAX(year_end) year_end FROM sys_period HAVING year_end < '" . date('Y-m-01') . "' LIMIT 1";
        $this->db->query($sql);
        if ($this->db->rowCount() >= 1) {
            $sql = "INSERT INTO sys_period (year_start, year_end, year_status, submit_by, modified_by) VALUES ('" . date('Y-m-01') . "', '" . date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day')) . "', '{\"invoice\":\"1\",\"receipt \":\"1\",\"receivable\":\"1\",\"payable\":\"1\",\"journal\":\"1\"}', '" . $arg['username'] . "', '" . $arg['username'] . "')";
            $this->db->query($sql);
            $this->db->execute();
        }
        
        $sql = "SELECT year_status FROM sys_period WHERE year_start <= '" . $arg['trans_date'] . "' AND year_end >= '" . $arg['trans_date'] . "' LIMIT 1 ";
        //return $sql;
        
        $this->db->query($sql);
        if ($this->db->rowCount() < 1) return false;
        
        return json_decode(($this->db->single())->year_status);
    }
    
    //
    public function getAccounts($arg = array())
    {
        $sql = "SELECT t1.auto_id, t1.account_code, t1.account_name, t1.group_code, t2.group_name, t2.range_min, 
        t2.range_max, t2.base_code, t2.base_name, t2.dr_cr FROM act_account t1 LEFT JOIN (SELECT t21.group_code, 
        t21.group_name, t21.range_min, t21.range_max, t22.base_code, t22.base_name, t22.dr_cr FROM act_group t21 
        LEFT JOIN act_base t22 ON t21.base_code = t22.base_code) t2 ON t1.group_code = t2.group_code WHERE 1=1 ";
    
        // base_code
        if (($arg['_option'] ?? '') === 'base_code') {
            $sql .= "AND t2.base_code = '" . $arg['base_code'] . "' ";
        }
        // group_code
        else if (($arg['_option'] ?? '') === 'group_code') {
            $sql .= "AND t2.group_code = '" . $arg['group_code'] . "' ";
        }
    
        // _option -> select
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.account_code id, CONCAT('(',t1.account_code,') ',t1.account_name) text FROM act_account t1 
            LEFT JOIN (SELECT t21.group_code, t21.group_name, t21.range_min, t21.range_max, t22.base_code, t22.base_name, t22.dr_cr FROM act_group t21 
            LEFT JOIN act_base t22 ON t21.base_code = t22.base_code) t2 ON t1.group_code = t2.group_code 
            WHERE (t1.account_code LIKE '%" . $arg['searchTerm'] . "%' OR t1.account_name LIKE '%" . $arg['searchTerm'] . "%') ";
    
            // base_code
            if ($arg['_option1'] === 'base_code') {
                $sql .= "AND t2.base_code = '" . $arg['base_code'] . "' ";
            }
            // group_code
            if (!empty($arg['group_code'])) {
                $sql .= "AND t2.group_code = '" . $arg['group_code'] . "' ";
                // var_dump($sql);exit;
            }
        }

    
        $sql .= "ORDER BY t1.account_code";
        //return $sql;
        
        $this->db->query($sql);
        $row = $this->db->resultSet();
    
        if (($arg['_option'] ?? '') === 'object') {
            $response = (object)array();
            if (!empty($row)) {
                foreach ($row as $v) {
                    $response->{$v->account_code} = $v;
                }
            }
            return $response;
        }
        
        return $row;
        
    }
    
    public function getAccount($arg = array())
    {
        $this->db->query("SELECT t1.* FROM act_account t1 WHERE t1.account_code = '" . $arg['account_code'] . "' LIMIT 1");
        
        return $this->db->single();
    }
    
    //
    public function getActgroups($arg = array())
    {
        $sql = "SELECT t1.auto_id, t1.group_code, t1.group_name, t1.range_min, t1.range_max, t2.base_code, t2.base_name, t2.dr_cr FROM act_group t1 LEFT JOIN (SELECT t21.base_code, t21.base_name, t21.dr_cr FROM act_base t21) t2 ON t1.base_code = t2.base_code WHERE 1=1 ";
    
        // base_code
        if (($arg['_option'] ?? '') === 'base_code') {
            $sql .= "AND t2.base_code = '" . $arg['base_code'] . "' ";
        }
    
        // _option -> select
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.group_code id, CONCAT('(',t1.group_code,') ',t1.group_name) text FROM act_group t1 LEFT JOIN (SELECT t21.base_code, t21.base_name, t21.dr_cr FROM act_base t21) t2 ON t1.base_code = t2.base_code WHERE (t1.group_code LIKE '%" . $arg['searchTerm'] . "%' OR t1.group_name LIKE '%" . $arg['searchTerm'] . "%') ";
    
            // base_code
            if ($arg['_option2'] === 'base_code') {
                $sql .= "AND t2.base_code = '" . $arg['base_code'] . "' ";
            }
        }
    
        $sql .= "ORDER BY t1.group_code";
        
        $this->db->query($sql);
        $row = $this->db->resultSet();
    
        if (($arg['_option'] ?? '') === 'object') {
            $response = (object)array();
            if (!empty($row)) {
                foreach ($row as $v) {
                    $response->{$v->group_code} = $v;
                }
            }
            return $response;
        }
        
        return $row;
        
    }
    
    public function getActgroup($arg = array())
    {
        $this->db->query("SELECT t1.*, t2.actaccount FROM act_group t1 LEFT JOIN (SELECT CONCAT('[',GROUP_CONCAT(CONCAT('{\"account_code\":\"',t21.account_code,'\",\"account_name\":\"',t21.account_name,'\"}')),']') actaccount, t21.group_code FROM act_account t21 GROUP BY t21.group_code) t2 ON t1.group_code = t2.group_code WHERE t1.group_code = '" . $arg['group_code'] . "' LIMIT 1");
        
        return $this->db->single();
    }
    
    //
    public function getActbases($arg = array())
    {
        $sql = "SELECT t1.base_code, t1.base_name, t1.dr_cr FROM act_base t1 WHERE 1=1 ";
    
        // _option -> select
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.base_code id, CONCAT('(',t1.base_code,') ',t1.base_name) text FROM act_base t1 WHERE (t1.base_code LIKE '%" . $arg['searchTerm'] . "%' OR t1.base_name LIKE '%" . $arg['searchTerm'] . "%') ";
        }
    
        $sql .= "ORDER BY t1.base_code";
        
        $this->db->query($sql);
        $row = $this->db->resultSet();
    
        if (($arg['_option'] ?? '') === 'object') {
            $response = (object)array();
            if (!empty($row)) {
                foreach ($row as $v) {
                    $response->{$v->base_code} = $v;
                }
            }
            return $response;
        }
        
        return $row;
        
    }
    
    public function getActbase($arg = array())
    {
        $this->db->query("SELECT t1.*, t2.actgroup FROM act_base t1 LEFT JOIN (SELECT CONCAT('[',GROUP_CONCAT(CONCAT('{\"group_code\":\"',t21.group_code,'\",\"group_name\":\"',t21.group_name,'\"}')),']') actgroup, t21.base_code FROM act_group t21 GROUP BY t21.base_code) t2 ON t1.base_code = t2.base_code WHERE t1.base_code = '" . $arg['base_code'] . "' LIMIT 1");
        return $this->db->single();
    }
    
    //
    public function getLedgerGroups($arg = [])
    {
        // _option -> select
        if ($arg['_option'] === 'select') {
            $sql = "SELECT t1.group_code id, CONCAT('(',t1.group_code,') ',t1.group_name) text FROM act_group t1 LEFT JOIN (SELECT t21.base_code, t21.base_name, t21.base_account, t21.dr_cr FROM act_base t21) t2 ON t1.base_code = t2.base_code WHERE (t1.group_code LIKE '%{$arg['searchTerm']}%' OR t1.group_name LIKE '%{$arg['searchTerm']}%') ";
        
            // base_code
            if ($arg['_option2'] === 'base_code') {
                $sql .= "AND t2.base_code = '{$arg['base_code']}' ";
            }
    
            $this->db->query($sql);
            return $this->db->resultSet();
        }
        
        // var_dump( $arg);exit;
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['t1.group_code', 't1.group_name', 't1.base_code', 't2.base_name']
        ]);
        
        $sql = "SELECT t1.auto_id, t1.group_code, t1.group_name, t1.range_min, t1.range_max, t2.base_code, t2.base_name, t2.base_account, t2.dr_cr FROM act_group t1 LEFT JOIN (SELECT t21.base_code, t21.base_name, t21.base_account, t21.dr_cr FROM act_base t21) t2 ON t1.base_code = t2.base_code WHERE 1=1 {$dataTable} ";
        
        // base_code
        if ($arg['_option'] === 'base_code') {
            $sql .= "AND t2.base_code = '{$arg['base_code']}' ";
        }
        
        $sql .= "ORDER BY t1.group_code";
        
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->group_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
    
        // all
        $rowCount = $this->db->rowCount();
        // limit
        $sql .= " LIMIT {$arg['start']}, {$arg['length']}";
        $this->db->query($sql);
        $rows = $this->db->resultSet();
    
        return [
            "recordsTotal" => $rowCount,
            "recordsFiltered" => $rowCount,
            "data" => $rows,
        ];
        
    }
    
    //
    public function getLedgerGroup($arg = [])
    {
        $sql = "SELECT t1.*, t2.actaccount FROM act_group t1 LEFT JOIN (SELECT CONCAT('[',GROUP_CONCAT(CONCAT('\"',t21.account_code,'\"')),']') actaccount, t21.group_code FROM act_account t21 GROUP BY t21.group_code) t2 ON t1.group_code = t2.group_code WHERE t1.group_code = '{$arg['group_code']}' LIMIT 1";
        
        $this->db->query($sql);
        $row = $this->db->single();
        
        if ($row) {
            $row->actsubgroup = json_decode($row->actsubgroup ?? '');
        }
        
        return $row;
    }
    //
    public function getLedgerAccounts($arg = [])
    {
    
        // _option -> select
        if ($arg['_option'] === 'select') {
            $sql = "
            SELECT t1.auto_id, t1.account_code, t1.account_code id, t1.account_name, CONCAT('(', t1.account_code, ') ', t1.account_name) text, t1.group_code, t1.lock, t2.range_min, t2.range_max, t2.group_name, t2.base_code, t2.base_name, t2.base_account, t2.dr_cr
            FROM act_account t1 LEFT JOIN (
                SELECT t21.group_code, t21.group_name, t22.base_code, t22.base_name, t22.base_account, t22.dr_cr
                FROM act_group t21
                LEFT JOIN (
                    SELECT t221.base_code, t221.base_name, t221.base_account, t221.dr_cr FROM act_base t221
                ) t22 ON t21.base_code = t22.base_code
            ) t2 ON t1.group_code = t2.group_code
            WHERE (t1.account_code LIKE '%{$arg['searchTerm']}%' OR t1.account_name LIKE '%{$arg['searchTerm']}%') ";
           
            // base_code
            if ($arg['_option2'] === 'base_code') {
                $sql .= "AND t2.base_code = '{$arg['base_code']}' ";
            } // group_code
            else if ($arg['_option2'] === 'group_code') {
                $sql .= "AND t2.group_code = '{$arg['group_code']}' ";
            }
    
            $this->db->query($sql);
            return $this->db->resultSet();
        }
    
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['t1.account_code', 't1.account_name', 't1.group_code', 't2.group_name', 't2.base_code', 't2.base_name']
        ]);
        
        $sql = "
        SELECT t1.auto_id, t1.account_code, t1.account_name, t1.group_code, t2.group_name, t2.range_min, t2.range_max, t2.group_name, t2.base_code, t2.base_name, t2.base_account, t2.dr_cr
        FROM act_account t1 LEFT JOIN (
            SELECT t21.group_code, t21.group_name, t21.range_min, t21.range_max, t22.base_code, t22.base_name, t22.base_account, t22.dr_cr
            FROM act_group t21
            LEFT JOIN (
                SELECT t221.base_code, t221.base_name, t221.base_account, t221.dr_cr FROM act_base t221
            ) t22 ON t21.base_code = t22.base_code
        ) t2 ON t1.group_code = t2.group_code 
        WHERE 1=1 AND (t1.account_code LIKE '%{$arg['searchTerm']}%' OR t1.account_name LIKE '%{$arg['searchTerm']}%') {$dataTable} ";
        // base_code
        if ($arg['_option'] === 'base_code') {
            $sql .= "AND t2.base_code = '{$arg['base_code']}' ";
        } // group_code
        else if ($arg['_option'] === 'group_code') {
            $sql .= "AND t2.group_code = '{$arg['group_code']}' ";
        }
        
        $sql .= "ORDER BY t1.account_code";
        //return $sql;
    
        $this->db->query($sql);
        $fff = $this->db->resultSet();
        // var_dump($fff); exit;
        // 
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->account_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        //
        if ($arg['_option'] === 'select-group') {
            $rows = $this->db->resultSet();
            $ledgerGroup = $this->getLedgerGroups(['_option' => 'object']);
            $ledgerGroupAccount = [];
            
            foreach ($ledgerGroup ?? (object)[] as $v) {
                $children = [];
                foreach ($rows ?? (object)[] as $v_) {
                    if ($v->group_code !== $v_->group_code) continue;
                    $children[] = ['id' => $v_->account_code, 'text' => '(' . $v_->account_code . ') ' . $v_->account_name];
                }
                if (!empty($children)) {
                    $ledgerGroupAccount[] = ['text' => $v->group_name, 'children' => $children];
                }
            }
            // var_dump($ledgerGroupAccount);exit;
            return $ledgerGroupAccount;
        }
    
        // all
        $rowCount = $this->db->rowCount();
        // limit
        $sql .= " LIMIT {$arg['start']}, {$arg['length']}";
        $this->db->query($sql);
        $rows = $this->db->resultSet();
    
        return [
            "recordsTotal" => $rowCount,
            "recordsFiltered" => $rowCount,
            "data" => $rows,
        ];
    }
    
    // cashbook /////////////////////////////////////////
    // CLT, AGT, BRK, REC | CLM, COM, FAC, RTN, PAY
    public function getCashbooks($arg = []) {
        
        // $dataTable = $this->dataTable([
        //     'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
        //     'fields' => ['ac.trans_code', 'ac.trans_detail']
        // ]);
        
        ////////////Total cashbook////////////////
        if($arg['_option'] === 'select'){
            $sql = "
                SELECT t1.client_code id,
                if(t2.parent_code != NULL OR t2.parent_code != '', CONCAT(t2.title, ' ', t2.first_name, ' ', t2.last_name), if(t3.std_code != NULL OR t3.std_code != '', CONCAT(t3.first_name, ' ', t3.last_name), '') )text
                FROM act_cashbook t1 
                LEFT JOIN (SELECT t21.* FROM sch_parent t21)t2 ON t1.client_code = t2.parent_code 
                LEFT JOIN (SELECT t31.* FROM sch_students t31)t3 ON t1.client_code = t3.std_code
                WHERE t2.first_name LIKE '%".$arg['searchTerm']."%' OR t2.last_name LIKE '%".$arg['searchTerm']."%' OR t3.first_name LIKE '%".$arg['searchTerm']."%' OR t3.last_name LIKE '%".$arg['searchTerm']."%'
                GROUP BY t1.client_code, t2.title, t2.first_name, t2.last_name
            ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }

        
        ////////////Total cashbook////////////////
        if($arg['_option'] === 'total'){
            //$sql = "SELECT t1.client_code, t1.client_name, t1.trans_code, SUM(COALESCE(t1.amount, 0))total_amount_cashbook FROM act_cashbook t1 WHERE t1.client_code = '".$arg['client_code']."' AND t1.`status`= '1' GROUP BY t1.client_code";
            $sql = "
            SELECT t1.client_code, SUM(COALESCE(t1.amount, 0))total_amount_cashbook FROM act_cashbook t1 
            WHERE t1.client_code = '".$arg['client_code']."' AND t1.`status`= '1' GROUP BY t1.client_code";
            $this->db->query($sql);
            return $this->db->single();
        }

        $sql = "
        SELECT ac.client_code, ac.trans_code, ac.trans_date, ac.trans_type, 
        ac.ref_code, ac.payer, ac.ref_detail, ac.trans_mode, ac.branch_code, ac.amount, ac.`status`, 
        ac.currency_code, ac.currency_rate, t2.parent_code, std_code,
            if(t2.parent_code != NULL OR t2.parent_code != '',CONCAT(t2.title, ' ', t2.first_name, ' ', t2.last_name), CONCAT(t3.first_name, ' ', t3.last_name) )client_name 
        FROM act_cashbook ac 
            LEFT JOIN (SELECT t21.* FROM sch_parent t21)t2 ON ac.client_code = t2.parent_code
            LEFT JOIN (SELECT t31.* FROM sch_students t31)t3 ON ac.client_code = t3.std_code
        WHERE 1=1 AND ac.status LIKE '%".$arg['list_option']."%'
          ";
        //  var_dump($sql);exit;
        if ($arg['sql']) {
            $sql .= "AND ac.trans_code = '{$arg['trans_code']}' GROUP BY ac.trans_code ";
            // var_dump($sql);exit;
            return ['sql' => $sql];
        }

        if ($arg['_option'] === 'object') {
            $sql .= " AND ac.trans_code = '{$arg['trans_code']}' ";
            // $sql .= "GROUP BY ac.trans_code, t2.first_name, t2.last_name, t2.title";
            $this->db->query($sql);
            return $this->db->single();
        }
        
        $sql .= "GROUP BY ac.trans_code, t2.first_name, t2.last_name, t2.title
        ORDER BY ac.trans_date DESC";
        
        $this->db->query($sql);
        $rows = $this->db->resultSet();
        
        return ["data" => $rows];
    }
    //
    public function getCashbook($arg = []) {
        $arg = array_merge($arg, ['sql' => true, 'trans_code' => $arg['trans_code'] ?? '', 'list_option' => '']);
        //return $this->getCashbooks($arg)['sql'];
        $this->db->query($this->getCashbooks($arg)['sql']);
        // var_dump($this->getCashbooks($arg)['sql']);exit;
        $row = $this->db->single();

        if ($row) {
            $row->receipts = json_decode($row->receipts ?? '[]');
        }

        return $row;
    }
    
    //
    public function getActlocks($arg = array())
    {
        $sql = "SELECT t1.auto_id, t1.year_start, t1.year_end, t1.year_status FROM sys_period t1 WHERE 1=1 ";
    
        $sql .= "ORDER BY t1.year_start desc, t1.year_end desc";
        
        $this->db->query($sql);
        return $this->db->resultSet();
        
    }
    
    public function getActlock($arg = array()) {
        $this->db->query("SELECT t1.* FROM sys_period t1 WHERE t1.auto_id = '" . $arg['auto_id'] . "' LIMIT 1");
        
        return $this->db->single();
    }
    
    //
    public function getTechsetup($arg = array()) {
        // $this->db->query("SELECT t1.* FROM sys_tec_act t1 ");
        $this->db->query("SELECT t1.* FROM sys_tec_act t1 WHERE t1.branch_code = '" . $arg['branch_code'] . "'");
        
        return $this->db->resultSet();
    }
    
    //
    public function getOpenbalance($arg = array()) {
        $sql = "SELECT t1.account_code, t1.account_name, t2.group_name, t2.base_name, t2.dr_cr, SUM(COALESCE(t3.debit_o, '0')) debit_o, SUM(COALESCE(t3.credit_o, '0')) credit_o, SUM(COALESCE(t3.debit_o, '0')) - SUM(COALESCE(t3.credit_o, '0')) balance_o, SUM(COALESCE(t3.debit_m, '0')) debit_m, SUM(COALESCE(t3.credit_m, '0')) credit_m, SUM(COALESCE(t3.debit_m, '0')) - SUM(COALESCE(t3.credit_m, '0')) balance_m, SUM(COALESCE(t3.debit_o, '0')) + SUM(COALESCE(t3.debit_m, '0')) debit_c, SUM(COALESCE(t3.credit_o, '0')) + SUM(COALESCE(t3.credit_m, '0')) credit_c, SUM(COALESCE(t3.debit_o, '0')) + SUM(COALESCE(t3.debit_m, '0')) - SUM(COALESCE(t3.credit_o, '0')) - SUM(COALESCE(t3.credit_m, '0')) balance_c FROM act_account t1 LEFT JOIN (SELECT t21.group_code, t21.group_name, t21.base_code, t22.base_name, t22.dr_cr FROM act_group t21 LEFT JOIN (SELECT t221.base_code, t221.base_name, t221.dr_cr FROM act_base t221) t22 ON t21.base_code = t22.base_code) t2 ON t1.group_code = t2.group_code LEFT JOIN (SELECT t31.account_code, t32.dr_cr, SUM(IF(t31.trans_type = 'OPB', t31.debit * t31.currency_rate, '0')) debit_o, SUM(IF(t31.trans_type = 'OPB', t31.credit * t31.currency_rate, '0')) credit_o, SUM(IF(t31.trans_type = 'OPB', '0', t31.debit * t31.currency_rate)) debit_m, SUM(IF(t31.trans_type = 'OPB', '0', t31.credit * t31.currency_rate)) credit_m FROM act_gledger t31 LEFT JOIN (SELECT t321.account_code, t321.group_code, t322.dr_cr FROM act_account t321 LEFT JOIN (SELECT t3221.group_code, t3221.base_code, t3222.dr_cr FROM act_group t3221 LEFT JOIN (SELECT t32221.base_code, t32221.dr_cr FROM act_base t32221) t3222 ON t3221.base_code = t3222.base_code) t322 ON t321.group_code = t322.group_code WHERE t322.dr_cr LIKE '%" . $arg['trans_type'] . "%') t32 ON t31.account_code = t32.account_code WHERE SUBSTR(t31.trans_date, 1, 10) >= '" . substr($arg['date_min'], 0, 10) . "' AND SUBSTR(t31.trans_date, 1, 10) <= '" . substr($arg['date_max'], 0, 10) . "' AND t32.dr_cr LIKE '%" . $arg['trans_type'] . "%' GROUP BY t31.account_code) t3 ON t1.account_code = t3.account_code WHERE t2.dr_cr LIKE '%" . $arg['trans_type'] . "%' GROUP BY t2.dr_cr, t2.base_code, t2.group_code, t1.account_code";
        //return $sql;
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    // Receipts /////////////////////////////////////////
    

    public function getCashbookBalance($arg= array()){
        $cashbook = $this->model('AccountData')->getCashbooks(array( '_option'=>'total', 'client_code' => $arg['client_code']));
        // var_dump($cashbook);exit;
        $receipt = $this->model('AccountData')->getReceipts(array( '_option'=>'total', 'client_code' => $arg['client_code']));
        $total_cashbook = $cashbook->total_amount_cashbook ?? 0;
        $total_receipt = $receipt->total_receipt_amount ?? 0;
        $balance = $total_cashbook - $total_receipt;
        // var_dump($total_receipt);exit;
        return array("balance"=>$balance, "total_receipt_amount"=>$total_receipt, "total_amount_cashbook"=> $total_cashbook);
    }
    
    // Payments /////////////////////////////////////////
    //
    //
    public function getPayments($arg = array()) {
        //
        $sql = "
        SELECT t1.*, LEFT(t1.trans_date, 10) trans_date, t4.credit_code, t4.company_code, t4.company_name, t4.bus_prop, t2.policy_code, t2.subrisk_code, t2.subrisk_name, t2.risk_code, t2.risk_name, t2.start_date, t2.end_date, t2.currency_code, t2.currency_rate, t2.sum_insured * t4.bus_prop / 100 sum_insured_cn, t2.gross_premium * t4.bus_prop / 100 gross_premium_cn, t2.commission_dn * t4.bus_prop / 100 commission_cn, t2.vat_amount_dn * t4.bus_prop / 100 vat_amount_cn, t2.other_charge_dn * t4.bus_prop / 100 other_charge_cn, t2.client_name, (t2.gross_premium - t2.commission_dn - t2.vat_amount_dn - t2.other_charge_dn) * t4.bus_prop / 100 net_amount_cn, t2.agent_name, t2.branch_code, t2.branch_name, t3.account_name
        FROM tec_policy_payment t1
        LEFT JOIN (
            SELECT t41.credit_code, t41.ref_code, t41.company_code, t41.bus_prop, t42.company_name
            FROM tec_policy_credit t41
            LEFT JOIN (SELECT t421.company_code, t421.company_name FROM tec_company t421) t42 on t41.company_code = t42.company_code
        ) t4 ON t1.ref_code = t4.credit_code
        LEFT JOIN (
            SELECT t21.policy_code, t21.debit_code, LEFT(t21.start_date, 10) start_date, LEFT(t21.end_date, 10) end_date, t21.currency_code, FORMAT(t21.currency_rate, 2) currency_rate, t21.sum_insured, t21.gross_premium, t21.com_rate / 100 * t21.gross_premium commission_dn, t21.vat_rate / 100 * t21.com_rate / 100 * t21.gross_premium vat_amount_dn, COALESCE(t21.other_charge, '0') other_charge_dn, t21.subrisk_code, t22.subrisk_name, t22.risk_code, t22.risk_name, t23.client_code, t23.client_name, t24.agent_code, t24.agent_name, t27.branch_code, t27.branch_name
            FROM tec_policy_debit t21
            LEFT JOIN (SELECT t221.subrisk_code, t221.subrisk_name, t221.risk_code, t222.risk_name FROM cod_subrisk t221 LEFT JOIN cod_risk t222 ON t221.risk_code = t222.risk_code) t22 ON t21.subrisk_code = t22.subrisk_code
            LEFT JOIN (SELECT t231.client_code, if(t231.business = 'Corporate',COALESCE(t231.first_name,''),TRIM(CONCAT(COALESCE(t231.title,''),' ',COALESCE(t231.last_name,''),' ',COALESCE(t231.first_name,'')))) client_name FROM tec_client t231) t23 ON t21.client_code = t23.client_code
            LEFT JOIN (SELECT t241.agent_code, t241.agent_name FROM tec_agent t241) t24 ON t21.agent_code = t24.agent_code
            LEFT JOIN (SELECT t271.branch_code, t271.branch_name FROM cod_branch t271) t27 ON t21.branch_code = t27.branch_code
        ) t2 ON t4.ref_code = t2.debit_code
        LEFT JOIN (SELECT t31.account_code, t31.account_name FROM act_account t31) t3 on t1.credit_account = t3.account_code
        WHERE 1=1 ";
        
        // payment
        if (($arg['_option'] ?? '') === 'payment_code') {
            //
            $sql .= "AND t1.payment_code = '" . $arg['payment_code'] . "' ";
        }
        
        // credit
        if (($arg['_option'] ?? '') === 'credit_code') {
            //
            $sql .= "AND t1.ref_code = '" . $arg['credit_code'] . "' ";
        }
        
        // payment & credit
        if (($arg['_option'] ?? '') === 'payment_credit') {
            //
            $sql .= "AND t1.payment_code = '" . $arg['payment_code'] . "' AND t1.ref_code = '" . $arg['ref_code'] . "' ";
        }
        
        // policy_code
        if (($arg['_option'] ?? '') === 'policy_code') {
            //
            $sql .= "AND t2.policy_code = '" . $arg['policy_code'] . "' ";
        }
    
        // company
        if (($arg['_option'] ?? '') === 'company_code') {
            //
            $sql .= "AND t4.company_code = '" . $arg['company_code'] . "' ";
        }
        
        $sql .= "ORDER BY t1.trans_date DESC";
        //return $sql;
        
        $this->db->query($sql);
    
        if (($arg['_option'] ?? '') === 'payment_credit')
            return $this->db->single();
        
        return $this->db->resultSet();
    }
    
    // Journals ////////////////////////////////////////
    
    public function getJournals($arg = array()) {
        
        $sql = "";
        // _option -> summary
        if (($arg['_option'] ?? '') === 'summary' || ($arg['_option'] ?? '') === 'summary-detail')
            //$sql = "SELECT t1.*, LEFT(t1.trans_date, 10) trans_date, SUM(t1.debit) debit_total, SUM(t1.credit) credit_total FROM act_gledger t1 WHERE t1.trans_type = 'JNL' " . (!empty($arg['trans_code']) ? " AND t1.trans_code = '" . $arg['trans_code'] . "' " : "") . " GROUP BY t1.trans_code, t1.debit, t1.credit, t1.trans_type, t1.trans_date, t1.account_code, t1.ref_code";
            $sql = "SELECT t1.trans_code,t1.ref_code, t1.trans_detail, ANY_VALUE(t1.account_code)account_code, t1.beneficiary, t1.currency_code, t1.trans_date, 
            SUM(t1.debit)debit_total, SUM(t1.credit)credit, ANY_VALUE(t1.account_name)account_name
            FROM act_gledger t1 WHERE 1=1 AND t1.trans_type='JNL' " . (!empty($arg['trans_code']) ? " AND t1.trans_code = '" . $arg['trans_code'] . "' " : "") . "
            GROUP BY t1.trans_code, t1.ref_code, t1.trans_detail,t1.beneficiary, t1.currency_code, t1.trans_date
            ";
            // var_dump($sql);exit;
        // _option -> detail
        if (($arg['_option'] ?? '') === 'detail')
            $sql = "SELECT t1.*, LEFT(t1.trans_date, 10) trans_date FROM act_gledger t1 WHERE t1.trans_type = 'JNL' " . (!empty($arg['trans_code']) ? " AND t1.trans_code = '" . $arg['trans_code'] . "' " : "");
        
        if (empty($sql))
            return false;
        
        $sql .= " ORDER BY t1.trans_code DESC";
        
        //return $sql;
        
        $this->db->query($sql);
        if (($arg['_option'] ?? '') === 'summary-detail')
            return $this->db->single();
        
        return $this->db->resultSet();
        
    }

    public function getBalance($arg = array())
    {
        // cashbook
        $sql = "
        SELECT ac.*, tc.client_code, tc.client_name, COALESCE(SUM(ac.amount), 0) total_deposit 
        FROM act_cashbook ac 
        INNER JOIN (SELECT tc.client_code,  CONCAT(tc.first_name, ' ', tc.last_name)client_name FROM tec_client tc) tc ON ac.client_code = tc.client_code 
        WHERE ac.client_code = '".$arg["client_code"]."'
        ";
        $this->db->query($sql);
        $cashbook = $this->db->single();

        // receipt
        $sql = "
        SELECT t1.*, LEFT(t1.trans_date, 10) trans_date, t2.policy_code, t2.subrisk_code, t2.subrisk_name, t2.risk_code, t2.risk_name, t2.start_date, t2.end_date, t2.currency_code, t2.currency_rate, t2.total_deduction, t2.client_name, t2.agent_name, t2.branch_code, t2.branch_name, t2.client_code, SUM(t1.gross_premium) total_premium 
        FROM tec_policy_receipt t1 
        INNER JOIN (
            SELECT t21.policy_code, t21.debit_code, LEFT(t21.start_date, 10) start_date, LEFT(t21.end_date, 10) end_date, t21.currency_code, FORMAT(t21.currency_rate, 2) currency_rate, (t21.gross_premium * t21.com_rate / 100 + t21.gross_premium * t21.com_rate / 100 * t21.vat_rate / 100 + t21.other_charge) total_deduction, t21.subrisk_code, t22.subrisk_name, t22.risk_code, t22.risk_name, t23.client_code, t23.client_name, t24.agent_code, t24.agent_name, t27.branch_code, t27.branch_name 
            FROM tec_policy_debit t21 
            INNER JOIN (
                SELECT t221.subrisk_code, t221.subrisk_name, t221.risk_code, t222.risk_name 
                FROM cod_subrisk t221
                LEFT JOIN cod_risk t222 ON t221.risk_code = t222.risk_code
            ) t22 ON t21.subrisk_code = t22.subrisk_code 
            INNER JOIN (SELECT t231.client_code, if(t231.business = 'Corporate',COALESCE(t231.first_name,''),TRIM(CONCAT(COALESCE(t231.title,''),' ',COALESCE(t231.last_name,''),' ',COALESCE(t231.first_name,'')))) client_name FROM tec_client t231) t23 ON t21.client_code = t23.client_code 
            INNER JOIN (SELECT t241.agent_code, t241.agent_name FROM tec_agent t241) t24 ON t21.agent_code = t24.agent_code 
            INNER JOIN (SELECT t271.branch_code, t271.branch_name FROM cod_branch t271) t27 ON t21.branch_code = t27.branch_code
        ) t2 ON t1.ref_code = t2.debit_code 
        WHERE 1=1 AND t1.bus_type = 'RCP' AND t2.client_code = '" . $arg["client_code"] . "'
            ";

        $this->db->query($sql);
        $receipt = $this->db->single();

        return json_decode(json_encode(array(
            'cashbook' => $cashbook,
            'receipt' => $receipt,
        )));
    }

    public function getFinancialStatement($arg = array()){
        $sql = "
        SELECT t1.group_code, t1.group_name, t2.base_code, t2.base_name, t2.dr_cr, COALESCE(t3.balance_0, '0') balance 
        FROM act_group t1 
        INNER JOIN (SELECT t21.base_code, t21.base_name, t21.dr_cr FROM act_base t21) t2 ON t1.base_code = t2.base_code 
        INNER JOIN (
            SELECT t32.group_code, t32.dr_cr,
            SUM(t31.debit * t31.currency_rate) - SUM(t31.credit * t31.currency_rate)balance_0 
            FROM act_gledger t31 
            INNER JOIN (
                SELECT t321.account_code, t321.group_code, t322.dr_cr FROM act_account t321 
                INNER JOIN (
                    SELECT t3221.group_code, t3221.base_code, t3222.dr_cr 
                    FROM act_group t3221 
                    LEFT JOIN (SELECT t32221.base_code, t32221.dr_cr FROM act_base t32221) t3222 ON t3221.base_code = t3222.base_code
                ) t322 ON t321.group_code = t322.group_code 
                WHERE t322.dr_cr LIKE '%".$arg['dr_cr']."%'
            ) t32 ON t31.account_code = t32.account_code 
            WHERE SUBSTR(t31.trans_date, 1, 10) >= '".$arg['start_date']."' AND SUBSTR(t31.trans_date, 1, 10) <= '".$arg['end_date']."' AND t32.dr_cr LIKE '%".$arg['dr_cr']."%' 
            GROUP BY t32.group_code
        ) t3 ON t1.group_code = t3.group_code 
        WHERE t2.dr_cr LIKE '%".$arg['dr_cr']."%' AND t1.group_name = '".$arg['group_name']."' ";
                    // var_dump($sql);exit
        $this->db->query($sql);
        return $this->db->single();
    }
    
}