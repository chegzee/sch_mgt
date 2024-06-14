<?php

class SystemData
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database;
    }
    
    // User ////////////////////////////////////////////////////
    
    public function verifyUser($arg = array())
    {
        $this->db->query("SELECT t1.username, t1.password, t1.password_reset, t1.address, t1.last_log, t1.first_name, t1.last_name, CONCAT(t1.first_name, ' ', t1.last_name) fullname, t1.phone, t1.status, t1.picture, t1.signature, t1.group_code, t2.group_name, t1.access FROM sys_user t1 LEFT JOIN (SELECT t2_.group_code, t2_.group_name FROM sys_user_group t2_) t2 ON t1.group_code = t2.group_code WHERE t1.user_log = '" . addslashes($arg['user_log'] ?? '') . "' AND DATEDIFF(NOW(), t1.last_log) < 1 AND NOT (t1.user_log = '' OR t1.user_log IS NULL) LIMIT 1");
        
        //

        if ($this->db->rowCount() < 1) {
            return false;
        }
        
    
        $user = $this->db->single();
        if(!empty($user)){
            if ($user->status != '1') {
                return false;
            }
        
            // access
            $user->access = json_decode($user->access);
            return $user;

        }else{
            return false;

        }
        
        // var_dump($user);exit;
        // valid session
       // $user->user_log = hash("sha256", $user->username . $user->password . session_id()); // . date('Ymd')
        
        // $user->user_log = substr($user->user_log, 0, 16) . '-'
        //     . substr($user->user_log, 16, 8) . '-'
        //     . substr($user->user_log, 24, 8) . '-'
        //     . substr($user->user_log, 32, 8) . '-'
        //     . substr($user->user_log, 40, 24);
        // //echo json_encode(array('status' => false, 'message' => array($user->username, $user->password, session_id()))); exit;
        // //
        // if ($user->user_log != addslashes($arg['user_log'] ?? '')) {
        //     var_dump($user->user_log);
        //     return false;
        // }
    
        // if (!file_exists($user->picture)) {
        //     //$result->picture = AZURE_BLOB . '/images/a' . rand(1, 9) . '.jpg';
        // }
    
        //
    
        // check password_reset
        // if (!empty($user->password_reset)) {
        //     $this->db->query("UPDATE sys_user SET password_reset = NULL, user_log = NULL WHERE username = '" . addslashes($user->username ?? '') . "' LIMIT 1");
        //     //
        //     $this->db->execute();
        
        //     return false;
        // }
    
    }
    public function verifyStudent($arg = array())
    {
        $this->db->query("SELECT t1.std_code, t1.status FROM sch_students t1 WHERE t1.user_log = '" . addslashes($arg['user_log'] ?? '') . "' LIMIT 1");
        if ($this->db->rowCount() < 1) {
            return false;
        }
        
    
        $student = $this->db->single();
        if(!empty($student)){
            if ($student->status != '1') {
                return false;
            }
           $res = $this->getStudents(array("_option"=>"std_code", "std_code"=> $student->std_code));
            return $res;

        }else{
            return false;

        }
    
    }
    public function verifyTeacher($arg = array())
    {
        $this->db->query("SELECT t1.identity_no, t1.status FROM sch_teacher t1 WHERE t1.user_log = '" . addslashes($arg['user_log'] ?? '') . "' LIMIT 1");
        if ($this->db->rowCount() < 1) {
            return false;
        }
        
    
        $teacher = $this->db->single();
        if(!empty($teacher)){
            if ($teacher->status != '1') {
                return false;
            }
           $res = $this->getTeacher(array("identity_no"=> $teacher->identity_no));
            return $res;

        }else{
            return false;

        }
    
    }
    
    public function getUsers($arg = array())
    {
        
        $sql = "SELECT t1.*, t2.group_name, LEFT(t1.submit_on, 10)submit_on FROM sys_user t1 LEFT JOIN sys_user_group t2 on t1.group_code = t2.group_code";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.username id, TRIM(CONCAT(t1.first_name,' ',COALESCE(t1.last_name, ''))) text FROM sys_user t1 WHERE t1.first_name LIKE '%".$arg["searchTerm"]."%'  OR t1.last_name LIKE '%".$arg["searchTerm"]."%' ORDER BY text";
        }
        if(($arg['_option'] ?? '') === "select_group"){
            $sql = "SELECT t1.username id, TRIM(CONCAT(t1.first_name,' ',COALESCE(t1.last_name, ''))) text FROM sys_user t1 LEFT JOIN sys_user_group t2 ON t1.group_code = t2.group_code WHERE t1.first_name LIKE '%".$arg["searchTerm"]."%' AND NOT t2.group_name = 'STUDENTS' ";
        }
        if(($arg['_option'] ?? '') === "student"){
            $sql = "SELECT t1.username id, TRIM(CONCAT(t1.first_name,'-',COALESCE(t1.last_name, ''))) text FROM sys_user t1 LEFT JOIN sys_user_group t2 ON t1.group_code = t2.group_code WHERE t2.group_name = '".$arg["group_name"]."' ";
        }
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    public function getUser($arg = array())
    {
        $this->db->query("SELECT t1.*, t2.group_name FROM sys_user t1 LEFT JOIN sys_user_group t2 on t1.group_code = t2.group_code WHERE t1.username = '" . $arg['username'] . "' LIMIT 1");
        
        return $this->db->single();
    }
    
    public function getUsergroups($arg = array()) {
        $sql = "SELECT t1.* FROM sys_user_group t1";
    
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.group_code id, t1.group_name text FROM sys_user_group t1";
        }
    
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    public function getUserGroup($arg = array()){
        $sql = "SELECT t1.* FROM sys_user_group t1 WHERE t1.group_code ='". $arg['group_code']."' LIMIT 1";
        $this->db->query($sql);
        return $this->db->single();

    }
    
    public function getAuditlogs($arg = array())
    {
        
        $sql = "SELECT t1.*, t2.first_name, t2.last_name FROM sys_user_log t1 LEFT JOIN (SELECT t21.username, t21.first_name, t21.last_name FROM sys_user t21) t2 on t1.username = t2.username WHERE (t1.event_log = 'INSERT' || t1.event_log = 'UPDATE' || t1.event_log = 'DELETE') AND NOT (t1.username = '' OR t1.username IS NULL) ORDER BY t1.log_time DESC";
        
        $this->db->query($sql);
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
    
    public function getBusTypes($arg = array()) {
        $sql = "SELECT t1.auto_id, t1.bus_type, t1.bus_group, t1.description FROM cod_bus_type t1 WHERE t1.bus_group LIKE '%" . $arg['bus_group'] . "%' ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.bus_type id, t1.description text FROM cod_bus_type t1 WHERE t1.bus_group LIKE '%" . $arg['bus_group'] . "%' AND t1.bus_type LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        
        $sql .= "ORDER BY t1.auto_id ";
        
        //
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    // Branch ////////////////////////////////////////////////////
    
    public function getBranches($arg = array())
    {
        
        $sql = "SELECT t1.*, t2.* FROM cod_branch t1 LEFT JOIN (SELECT t21.* FROM act_bank t21)t2 ON t1.account_code = t2.account_code";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.branch_code id, t1.branch_name text FROM cod_branch t1 WHERE t1.branch_code LIKE '%" . $arg['searchTerm'] . "%'";
        }
        
        $this->db->query($sql);
        if (($arg['_option'] ?? '') === 'default')
            return $this->db->single();
            
        //
        $obj = (object)[];
        if (($arg['_option2'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->branch_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        
        return $this->db->resultSet();
    }
    
    public function getBranch($arg = array())
    {
        
        $sql = "SELECT t1.* FROM cod_branch t1 WHERE t1.branch_code = '" . $arg['branch_code'] . "' LIMIT 1";
        
        $this->db->query($sql);
        return $this->db->single();
    }
    public function getSubjectType($arg = array())
    {
        
        $sql = "SELECT t1.* FROM subject_type t1 WHERE 1=1";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.subject_type id, t1.subject_type text FROM subject_type t1 WHERE t1.status = '1' AND t1.subject_type LIKE '%" . $arg['searchTerm'] . "%'";
            //
            $this->db->query($sql);
            $data = $this->db->resultSet();
            // var_dump($data);exit;
            return $data;
        }

        if (($arg['_option'] ?? '') === 'object') {
            $sql .= " AND t1.subject_type='".$arg['subject_type']."' ";
            //
            $this->db->query($sql);
            $data = $this->db->single();
            // var_dump($data);exit;
            return $data;
        }



        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    // risk, subrisk ////////////////////////////////////////////////////
    
    public function getCategories($arg = array())
    {
        
        $sql = "SELECT t1.*, LEFT(t1.submit_on, 10) submit_on FROM sch_category t1 WHERE 1=1 AND t1.status = '1' ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.cat_code id, t1.cat_name text FROM sch_category t1 WHERE t1.status = '1' AND t1.cat_name LIKE '%" . $arg['searchTerm'] . "%'";
        }
    
        $sql .= " ORDER BY t1.digit";
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->cat_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        return $this->db->resultSet();
    }
    ////
    public function getCategories_($arg = array())
    {
        
        $sql = "SELECT t1.*, LEFT(t1.submit_on, 10) submit_on FROM sch_category t1 WHERE 1=1 ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.cat_code id, t1.cat_name text FROM sch_category t1 WHERE t1.status = '1' AND t1.cat_code LIKE '%" . $arg['searchTerm'] . "%'";
        }
    
        $sql .= " ORDER BY t1.digit";
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->cat_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        return $this->db->resultSet();
    }
    
    public function getCategory($arg = array())
    {
        
        $sql = "SELECT t1.* FROM sch_category t1 WHERE t1.cat_code = '" . $arg['cat_code'] . "' LIMIT 1";

        if(($arg['level'] ?? '' ) === 'obj'){
            $sql = "SELECT t1.* FROM sch_category t1 WHERE t1.cat_name = '" . $arg['cat_name'] . "' LIMIT 1";
            $this->db->query($sql);
            return $this->db->single();

        }
        
        $this->db->query($sql);
        return $this->db->single();
    }
    
    //
    public function getQuestions($arg = array()){

        $sql = "SELECT t1.code, t1.level_list, t1.exam_name ,t1.posted_date, t1.subject
        FROM sch_question t1 LEFT JOIN subject_type t2 ON t1.subject = t2.subject_type WHERE t1.term_code = '".$arg['term_code']."' LIMIT 100";
        // $sql = "SELECT t1.code, t1.level_list, t1.exam_name ,t1.posted_date, t2.subject_name FROM sch_question t1 LEFT JOIN sch_subject t2 ON t1.subject = t2.sub_code WHERE t1.term = '" . $arg['term'] . "' AND t1.term_code = '".$arg['term_code']."' AND t1.year = '".$arg['year']."' LIMIT 30";
        //
        if($arg['option'] === 'obj'){
            $sql = "SELECT t1.code, t1.level_list, t1.exam_name, t1.exam_timer, t1.posted_date, t1.subject, t1.questions FROM sch_question t1 LEFT JOIN subject_type t2 ON t1.subject = t2.subject_type WHERE t1.code = '" . $arg['code'] . "'";
            
            $this->db->query($sql);
            return $this->db->single();
        } //
        if($arg['option'] === 'all'){
            $sql = "SELECT t1.code, t1.level_list, t1.exam_name, t1.exam_timer,t1.term, t1.year, t1.students, t1.posted_date, t2.subject_name, t1.submit_on, t1.submit_by FROM sch_question t1 LEFT JOIN sch_subject t2 ON t1.subject = t2.sub_code WHERE 1=1";
            
            $this->db->query($sql);
            return $this->db->resultSet();
        } //
        if($arg['option'] === 'obj2'){ 
            $sql = "SELECT coalesce(t1.students, '[]')students FROM sch_question t1 LEFT JOIN sch_subject t2 ON t1.subject = t2.sub_code WHERE t1.code = '" . $arg['code'] . "'";
            
            $this->db->query($sql);
            return $this->db->single();
        }
        
        $this->db->query($sql);
        return $this->db->resultSet();

    }

    //
    public function getStudentsHistory($arg = array()) {
        $sql = "
        SELECT t1.*, DATEDIFF(t4.end_date, CURRENT_DATE())AS days_remains, t2.class_name, 
        t2.cat_name, t2.cat_code, t4.term_name, t2.digit, CONCAT(t4.term_name, ' ', t4.year)period_name,
        left(t1.submit_on, 10)create_date, t4.start_date, t4.end_date, t2.fees, coalesce(t1.activities, '[]')activities, 
        t5.branch_code, t5.branch_name, t5.phone branch_phone, t5.phone2 branch_phone2, t5.address branch_address,
        t5.contact branch_contact_person, t5.email branch_email, t5.state branch_state, t5.country branch_country, t4.year,
        t6.parent_code, t6.email parent_emal, t6.first_name parent_first_name, t6.last_name parent_last_name, t6.title parent_title, CONCAT(COALESCE(t6.title, ''), ' ', COALESCE(t6.first_name, ''), ' ', COALESCE(t6.last_name), '')parent_name
        ,t7.receipt_code receipt_code_table, t7.total_receipt_amount, t7.invoice_amount FROM sch_std_history t1 
        LEFT JOIN (
		  SELECT t21.class_code, t21.cat_code, t21.class_name, t3.cat_name, t3.digit, t3.fees, t3.status cat_status FROM sch_classes t21
		  LEFT JOIN (SELECT t31.* FROM sch_category t31)t3 ON t21.cat_code = t3.cat_code
		  ) t2 ON t1.class_code = t2.class_code 
        
        LEFT JOIN (SELECT t41.code, t41.term term_name, t41.start_date, t41.end_date , t41.year
        FROM sch_term t41)t4 ON t1.term = t4.code LEFT JOIN (SELECT t55.* FROM cod_branch t55)t5 ON t1.branch_code = t5.branch_code 
        LEFT JOIN (
		  SELECT t66.parent_code, t66.email, t66.first_name, t66.last_name, t66.address parent_address, t66.title 
		  FROM sch_parent t66
		  )t6 ON t1.parent_code=t6.parent_code LEFT JOIN
		   (SELECT t71.invoice_code, t71.amount invoice_amount, t8.receipt_code, t8.total_receipt_amount FROM sch_invoice t71
			LEFT JOIN (SELECT t81.invoice_code, t81.receipt_code, SUM(t81.amount)total_receipt_amount FROM sch_receipt t81 GROUP BY t81.receipt_code, t81.invoice_code)t8
			ON t71.invoice_code = t8.invoice_code
			)t7 ON t1.invoice_code = t7.invoice_code
		   
        WHERE 1=1 AND t2.cat_status = '1'";
            // var_dump($sql);exit;
        if (($arg['_option'] ?? '') === 'std_code') {
            $sql .= " AND t1.std_code = '" . $arg['std_code'] . "' ";
            $this->db->query($sql);
            return $this->db->single();
        }

        if (!empty($arg['std_code_'])) {
            $sql .= " AND t1.std_code = '" . $arg['std_code_'] . "' ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }
        
        //////////get student by std code and term code///////////////////
        if (($arg['_option'] ?? '') === 'print') {
            $sql .= " AND  t1.std_code IN ('{$arg['std_code']}') AND  t1.term IN ('{$arg['term_code']}') ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }
        
        if (!empty($arg['std_code']) && !empty($arg['term'])) {
            $sql = "SELECT coalesce(t1.online_question, '[]')online_question FROM sch_std_history t1 WHERE t1.std_code ='".$arg["std_code"]."' AND t1.term = '".$arg['term']."' ";
            $this->db->query($sql);
            return $this->db->single();
        }

        if (($arg['_option'] ?? '') === 'email') {
            $sql .= " AND t1.email = '" . $arg['email'] . "'  ";
            // $this->db->query($sql);
            // return $this->db->resultset();
        }
        
        // if (($arg['_option'] ?? '') === 'select') {
        //     $sql = "SELECT t1.std_code id, t1.first_name text FROM sch_students t1 WHERE t1.std_code LIKE '" . $arg['std_code'] . "' AND t1.std_code LIKE '%" . $arg['searchTerm'] . "%'";
        // }
    
        $sql .= "  AND t1.term LIKE '%".$arg['term_code']."%' ORDER BY t1.submit_on DESC";
        //return $sql;
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    public function getStudents($arg = array())
    {
        $sql = "
        SELECT t1.*, DATEDIFF(t4.end_date, CURRENT_DATE())AS days_remains, t2.class_name, 
        t2.cat_name, t2.cat_code, t4.term_name, t2.digit, CONCAT(t4.term_name, ' ', t4.year)period_name,
        left(t1.submit_on, 10)create_date, t4.start_date, t4.end_date, t2.fees, coalesce(t1.activities, '[]')activities, 
        t5.branch_code, t5.branch_name, t5.phone branch_phone, t5.phone2 branch_phone2, t5.address branch_address,
        t5.contact branch_contact_person, t5.email branch_email, t5.state branch_state, t5.country branch_country, t4.year,
        t6.parent_code, t6.email parent_emal, t6.first_name parent_first_name, t6.last_name parent_last_name, t6.title parent_title,
		  CONCAT(COALESCE(t6.title, ''), ' ', COALESCE(t6.first_name, ''), ' ', COALESCE(t6.last_name), '')parent_name
        ,t7.receipt_code receipt_code_table, coalesce(t7.total_receipt_amount, 0)total_receipt_amount,
		   coalesce(t7.invoice_amount, 0)invoice_amount, coalesce(t7.invoice_amount, 0) - coalesce(t7.total_receipt_amount, 0)balance_due,
		   LEFT(t7.trans_date, 10)invoice_date, YEAR(t7.trans_date)invoice_year, MONTH(t7.trans_date)invoice_month,
			t5.branch_account_number, t5.branch_account_name, t5.branch_bank_name, t5.picture logo,
			CONCAT('(', t5.branch_account_number, ')' ,  t5.branch_account_name,  t5.branch_bank_name)bank_account, t2.cat_status
        
		  FROM sch_students t1 
            LEFT JOIN (
                SELECT t21.class_code, t21.cat_code, t21.class_name, t3.cat_name, t3.digit, t3.fees, t3.status cat_status
					 FROM sch_classes t21
                LEFT JOIN (SELECT t31.* FROM sch_category t31)t3 ON t21.cat_code = t3.cat_code
            ) t2 ON t1.class_code = t2.class_code 
        
        LEFT JOIN (SELECT t41.code, t41.term term_name, t41.start_date, t41.end_date , t41.year
        FROM sch_term t41)t4 ON t1.term = t4.code LEFT JOIN 
		  (SELECT t55.*, t56.account_number branch_account_number, t56.account_name branch_account_name, 
		  t56.bank_name branch_bank_name
		  FROM cod_branch t55 LEFT JOIN (SELECT t561.* FROM act_bank t561)t56 
		  ON t56.account_code = t55.account_code
		  
		  )t5 ON t1.branch_code = t5.branch_code 
        LEFT JOIN (
		  SELECT t66.parent_code, t66.email, t66.first_name, t66.last_name, t66.address parent_address, t66.title 
		  FROM sch_parent t66
		  )t6 ON t1.parent_code=t6.parent_code LEFT JOIN
		   (SELECT t71.invoice_code, t71.trans_date, t71.amount invoice_amount, t8.receipt_code, t8.total_receipt_amount FROM sch_invoice t71
				LEFT JOIN (
					SELECT t81.invoice_code, t81.receipt_code, SUM(t81.amount)total_receipt_amount FROM sch_receipt t81 GROUP BY t81.receipt_code, t81.invoice_code
				)t8 ON t71.invoice_code = t8.invoice_code
			)t7 ON t1.invoice_code = t7.invoice_code
		   
        WHERE 1=1 AND t2.cat_status = '1'
        ";
        ///////student by its code number/////////////
        if (($arg['_option'] ?? '') === 'std_code') {
            $sql .= " AND t1.std_code = '" . $arg['std_code'] . "'";
            $this->db->query($sql);
            return $this->db->single();
        }

        ///////student by its code number/////////////
        if (($arg['_option2'] ?? '') === 'std_code') {
            $sql .= " AND t1.std_code = '" . $arg['std_code'] . "'";
            $this->db->query($sql);
            return $this->db->resultSet();
        }

        //////////get student by std code and term code///////////////////
        if (($arg['_option'] ?? '') === 'print') {
            $sql .= " AND  t1.std_code IN ('{$arg['std_code']}') ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }
        //////////get student by class code///////////////////
        if (($arg['_option'] ?? '') === 'class_code') {
            $sql .= " AND  t1.class_code IN ('{$arg['class_code']}') ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }
        //////total number of student per term////////////
        if (($arg['_option'] ?? '') === 'totalStd') {
            $sql = "SELECT COUNT(t1.std_code)total_students FROM sch_students t1";
            $this->db->query($sql);
            return $this->db->single();
        }
        //////////total number of student in gender//////////////////////
        if (($arg['_option'] ?? '') === 'gender') {
            $sql = "SELECT COUNT(t1.std_code)total FROM sch_students t1 WHERE t1.gender = '".$arg['gender']."' ";
            $this->db->query($sql);
            return $this->db->single();
        }
        /////////student by email//////////////////
        if (($arg['_option'] ?? '') === 'email') {
            $sql .= " AND t1.email = '" . $arg['email'] . "'  ";
            $this->db->query($sql);
            return $this->db->resultset();
        }
        /////////////for select api////////////////////
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.std_code id, CONCAT(t1.first_name, ' ', t1.last_name)text FROM sch_students t1 WHERE 
            t1.first_name LIKE '%" . $arg['searchTerm'] . "%' OR t1.last_name LIKE '%" . $arg['searchTerm'] . "%'";
            $this->db->query($sql);
            return $this->db->resultSet();
            //var_dump($res);exit;
            
        }
    
        if(($arg['_option'] ?? '') === "student_user"){
            $sql = "SELECT coalesce(t1.email, '') id, CONCAT(t1.first_name, '-', t1.last_name) text FROM sch_students t1 WHERE 1=1";
            $this->db->query($sql);
            return $this->db->resultset();
        }

        $sql .= " ORDER BY t2.digit ASC";
        //return $sql;
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getStudentForInvoice($arg = array()){
        $sql = "
        SELECT t1.term, t1.receipt_code, coalesce(t3.fees, 0)fees, t1.std_code, t2.class_code, CONCAT(t1.last_name, ' ', t1.first_name)std_name,
        if(JSON_VALID(t1.activities), t1.activities, JSON_OBJECT())activities, t2.class_name, t3.cat_name, t2.cat_code,
        t4.term_name, t3.digit, t4.start_date, t4.end_date, t5.branch_code, t5.branch_name, t5.phone branch_phone,
        t5.phone2 branch_phone2, t5.address branch_address, t5.alpha, t5.contact branch_contact_person, t5.email branch_email,
        t5.state branch_state, t5.country branch_country, t4.year, t1.invoice_code, t5.account_number, t5.account_name, t5.bank_name, t5.account_code FROM 
        sch_students t1 LEFT JOIN
        (
		  SELECT t21.class_code,t21.cat_code, t21.class_name FROM sch_classes t21)t2 ON t1.class_code = t2.class_code 
            LEFT JOIN (SELECT t31.* FROM sch_category t31)t3 ON t2.cat_code = t3.cat_code
            LEFT JOIN (SELECT t41.code, t41.term term_name, t41.start_date, t41.end_date , t41.year
            FROM sch_term t41)t4 ON t1.term = t4.code LEFT JOIN 
			(
				SELECT t55.*, t56.account_number, t56.account_name, t56.bank_name FROM cod_branch t55 LEFT JOIN (SELECT t561.* FROM act_bank t561)t56 ON t55.account_code = t56.account_code
        )t5 ON t1.branch_code = t5.branch_code WHERE 1=1";
        
        ///////student by its code number/////////////
        if (($arg['_option'] ?? '') === 'std_code') {
            $sql .= " AND t1.std_code = '" . $arg['std_code'] . "'";
            $this->db->query($sql);
            return $this->db->single();
        }

        ///////student by its code number/////////////
        if (($arg['_option'] ?? '') === 'multiple') {
            $sql .= " AND t1.std_code IN ('{$arg['std_code']}') ";
            // $this->db->query($sql);
            // return $this->db->single();
        }

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    ////
    public function getStudent($arg = array())
    {
        $sql = "SELECT t1.*, t2.class_name, t3.cat_name, t4.term  term_name, DATEDIFF(t1.end_date, CURRENT_DATE())AS days_remains, coalesce(t1.department, '') 
        FROM sch_students t1 LEFT JOIN (SELECT t22.* FROM sch_classes t22)t2 ON t1.class_name_code = t2.class_code 
        LEFT JOIN (SELECT t33.* FROM sch_category t33)t3 ON t1.cat_code = t3.cat_code 
        LEFT JOIN (SELECT t44.* FROM sch_term t44)t4 ON t1.term = t4.code  WHERE t1.std_code = '".$arg['std_code']."' LIMIT 1";

        //////get student online question/////////////
        if($arg["option"] === "obj2"){
            $sql = "SELECT coalesce(t1.online_question, '[]')online_question FROM sch_students t1 WHERE t1.std_code ='".$arg["std_code"]."' LIMIT 1 ";
            $this->db->query($sql);
            return $this->db->single();
        }
        
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getStudentSubjects($arg = array()){
        $sql = "SELECT t1.*, CONCAT(t3.first_name, ' ', t3.last_name) teacher, t2.cat_name, t4.class_name, t5.subject_name, t2.alpha FROM sch_mapping t1
         LEFT JOIN (SELECT t41.* FROM sch_classes t41)t4 ON t1.class_code = t4.class_code 
         LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t4.cat_code = t2.cat_code 
         LEFT JOIN (SELECT t51.* FROM sch_subject t51)t5 ON t1.subject_name_code = t5.sub_code 
         LEFT JOIN (SELECT t31.identity_no, t31.first_name, t31.last_name FROM sch_teacher t31)t3 ON t1.teacher_code = t3.identity_no
         WHERE 1=1 AND t1.class_code = '".$arg['class_code']."' ";
        $this->db->query($sql);

        return $this->db->resultSet();
    }

    ///////////classes where teachers are giving lecture////////////////
    public function getTeacherClass($arg = array()){
        $sql = "
        SELECT t1.*, CONCAT(t3.first_name, ' ', t3.last_name) teacher, t2.cat_name, t4.class_name, t5.subject_name, t2.alpha FROM sch_mapping t1
         LEFT JOIN (SELECT t41.* FROM sch_classes t41)t4 ON t1.class_code = t4.class_code 
         LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t4.cat_code = t2.cat_code 
         LEFT JOIN (SELECT t51.* FROM sch_subject t51)t5 ON t1.subject_name_code = t5.sub_code 
         LEFT JOIN (SELECT t31.identity_no, t31.first_name, t31.last_name FROM sch_teacher t31)t3 ON t1.teacher_code = t3.identity_no 
         WHERE 1=1 AND t3.identity_no = '".$arg['teacher_id']."'";
        $this->db->query($sql);
        $classesTeacher = [];
        foreach($this->db->resultSet() as $k => $v){
            array_push($classesTeacher, $v->class_code);
        }

        return $classesTeacher;
    }
    //get student by email
    public function getStudent2($arg = array())
    {
        $sql = "SELECT t1.* FROM sch_students t1 WHERE t1.email = '" . $arg['email'] . "' LIMIT 1";
        
        $this->db->query($sql);
        // var_dump($this->db->single());exit;
        return $this->db->single();
    }
    //
    public function getTeachers($arg = array())
    {
        
        $sql = "SELECT t1.*,  TRIM(CONCAT(COALESCE(t1.first_name, ''), ' ', COALESCE(t1.last_name, ''))) teacher FROM sch_teacher t1 WHERE 1=1 ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.identity_no id, TRIM(CONCAT(COALESCE(t1.first_name, ''), ' ', COALESCE(t1.last_name, ''))) text FROM sch_teacher t1 WHERE t1.identity_no LIKE '%" . $arg['searchTerm'] . "%'";
        }
    
        $sql .= " ORDER BY t1.first_name";
        //return $sql;
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->teacher} = $v; }, $this->db->resultSet());
            return $obj;
        }
        
        if (($arg['_option'] ?? '') === 'totalTeacher') {
            $sql = "SELECT COUNT(t1.identity_no)total_teachers FROM sch_teacher t1";
            $this->db->query($sql);
            return $this->db->single();
        }
        return $this->db->resultSet();
    }
    //
    public function getTeacher($arg = array())
    {
        $sql = "SELECT t1.* FROM sch_teacher t1 WHERE t1.identity_no = '".$arg['identity_no']."' LIMIT 1";
        
        $this->db->query($sql);
        // var_dump($this->db->single());exit;
        return $this->db->single();
    } 
    //teacher by email
    public function getTeacher2($arg = array())
    {
        $sql = "SELECT t1.* FROM sch_teacher t1 WHERE t1.email = '".$arg['email']."' LIMIT 1";
        
        $this->db->query($sql);
        // var_dump($this->db->single());exit;
        return $this->db->single();
    } 
    //
    public function getParents($arg = array())
    {
        
        $sql = "SELECT t1.*,  TRIM(CONCAT(COALESCE(t1.title, ''), ' ', COALESCE(t1.first_name, ''), ' ', COALESCE(t1.last_name, ''))) parent FROM sch_parent t1 WHERE 1=1 ";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.parent_code id, TRIM(CONCAT(COALESCE(t1.title, ''), ' ', COALESCE(t1.first_name, ''), ' ', COALESCE(t1.last_name, ''))) text FROM sch_parent t1 WHERE t1.first_name LIKE '%" . $arg['searchTerm'] . "%' OR  t1.last_name LIKE '%" . $arg['searchTerm'] . "%'  OR  t1.title LIKE '%" . $arg['searchTerm'] . "%' ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }

        if (($arg['_option'] ?? '') === 'parent_code') {
            $sql .= " AND t1.parent_code='".$arg['parent_code']."' ";
        }
    
        $sql .= " ORDER BY t1.first_name";
        //return $sql;
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->parent_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        //
        $obj = (object)[];
        if (($arg['_option2'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->first_name. ' '. $v->last_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        
        if (($arg['_option'] ?? '') === 'totalParent') {
            $sql = "SELECT COUNT(t1.parent_code)total_parent FROM sch_parent t1";
            $this->db->query($sql);
            return $this->db->single();
        }
        return $this->db->resultSet();
    }

    //
    public function getSubject($arg = array()){
        $sql = "SELECT t1.* FROM sch_subject t1 WHERE 1=1 AND  t1.sub_code ='". $arg['sub_code'] ."' ";
        // $sql = "SELECT t1.* FROM sch_subject t1 WHERE t1.subject_name ='". $arg['subject_name'] ."' AND  t1.class_code ='". $arg['class_code'] ."' ";
        // $sql = "SELECT t1.* FROM sch_subject t1 WHERE t1.sub_code ='". $arg['sub_code'] ."' ";

        $this->db->query($sql);
        // var_dump($this->db->single());exit;
        return $this->db->single();
    }
    //
    public function getSubjects($arg = array())
    {
        
        $sql = "SELECT t1.*, LEFT(t1.submit_on, 10)submit_on FROM sch_subject t1 WHERE 1=1";
        
        // if (($arg['_option'] ?? '') === 'select') {
        //     $sql = "SELECT t1.sub_code id, t1.subject text FROM sch_subject t1 WHERE t1.level = 'ALL' OR t1.level LIKE '%" . $arg['searchTerm'] . "%'";
        // }
        if (($arg['_option2'] ?? '') === 'class_code') {
            $sql .= " AND t1.class_code ='".$arg['class_code']."' ";
            // $this->db->query($sql);
            // $this->db->resultSet();
        }
    
        // $sql .= " ORDER BY t1.subject";
        
        //return $sql;
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->sub_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        return $this->db->resultSet();
    }
    //
    public function getClasses($arg = array()){
        $sql = "SELECT t1.*, t2.* FROM sch_classes t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code WHERE 1=1 AND t2.status='1' ";
        
        if (($arg['_option'] ?? '') === 'select') {
           // $sql = "SELECT t1.class_code id, t1.class_name text FROM sch_classes t1 WHERE t1.class_name LIKE '%" . $arg['searchTerm'] . "%' ";
            $sql = "SELECT t1.class_code id, CONCAT(t2.cat_name , '-' , t1.class_name) text FROM sch_classes t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code WHERE t2.status='1' AND t2.cat_name LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        
        if (($arg['_option2'] ?? '') === 'select') {
            // $sql = "SELECT t1.class_code id, t1.class_name text FROM sch_classes t1 WHERE t1.class_name LIKE '%" . $arg['searchTerm'] . "%' ";
             $sql = "SELECT t1.class_code id, CONCAT(t2.cat_name , ' ' , t1.class_name) text FROM sch_classes t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code WHERE t2.status='1' AND t2.cat_name LIKE '%" . $arg['searchTerm'] . "%' OR  t1.class_name LIKE '%" . $arg['searchTerm'] . "%' ";
         }
        if (($arg['_option'] ?? '') === 'class_name') {
            $sql = "SELECT t1.class_code id, t1.class_name text, t2.cat_name, t2.digit FROM sch_classes t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code WHERE t2.status='1' AND t2.cat_name LIKE '%" . $arg['searchTerm'] . "%' ";
        }
        if (!empty($arg['cat_code'])) {
            $sql = "SELECT t1.*, t2.* FROM sch_classes t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code WHERE t1.cat_code ='".$arg['cat_code']."' ";
        }
        if (!empty($arg['class_code'])) {
            $sql = "SELECT t1.*, t2.* FROM sch_classes t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code WHERE t1.class_code ='".$arg['class_code']."' ";
        }
    
        $sql .= " ORDER BY t2.digit";
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->class_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        //
        $obj = (object)[];
        if (($arg['_option2'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->cat_name .' '.$v->class_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        ///get object with digit key///////////////////
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object2') {
            array_map(function ($v) use ($obj) { $obj->{$v->digit} = $v; }, $this->db->resultSet());
            return $obj;
        }
        return $this->db->resultSet();
    }

    public function getClassSubject($arg = array()){
        $sql = "SELECT t1.sub_code id, t1.subject_name text FROM sch_subject t1 WHERE t1.class_code = '".$arg['_option']."' ";
        $this->db->query($sql);
         $result = $this->db->resultSet();
        //  var_dump($result);exit;
         return $result;
    }
    //
    public function getClass($arg = array()){ 
        
        $sql = "SELECT t1.*, LEFT(t1.submit_on, 10) submit_on FROM sch_classes t1 WHERE 1=1 AND t1.class_code = '" .$arg['class_code']. "' ";
    
        $sql .= " ORDER BY t1.class_name";
        //return $sql;
        $this->db->query($sql);
        return $this->db->resultSet();

    }
    public function getclassrooms($arg= array()){
        $sql = "SELECT t1.*, CONCAT(t3.first_name, ' ', t3.last_name) teacher, t2.cat_name, t4.class_name, t5.subject_name,GROUP_CONCAT(DISTINCT t4.class_name)AS n_classes
         FROM sch_mapping t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t1.cat_code = t2.cat_code 
         LEFT JOIN (SELECT t41.* FROM sch_classes t41)t4 ON t1.class_name_code = t4.class_code 
         LEFT JOIN (SELECT t51.* FROM sch_subject t51)t5 ON t1.subject_name_code = t5.sub_code 
         LEFT JOIN (SELECT t31.identity_no, t31.first_name, t31.last_name FROM sch_teacher t31)t3 ON t1.teacher_code = t3.identity_no 
         GROUP BY t2.cat_name";
        
        $this->db->query($sql);
        //
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->cat_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        $res = $this->db->resultSet();
        // var_dump($sql); exit;
        return $res;
    }
    //
    public function getClassRoutine($arg = array()){
        $sql = "SELECT t1.*, CONCAT(t2.term, ' ', t2.year)term_name  FROM sch_class_routine t1 LEFT JOIN sch_term t2 ON t1.term_code = t2.code WHERE 1=1";
        //return $sql;
        if(($arg["class_code"] ?? '') !== '' && ($arg["term_code"] ?? '') !== '' ){
            $sql .= ' AND t1.class_code = "'.$arg["class_code"].'" AND t1.term_code="'.$arg["term_code"].'" LIMIT 1';
            
            $this->db->query($sql);
            return $this->db->single();
        }
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    //
    public function getAllClassRoutine($arg = array()){
       // $sql = "SELECT t1.*, LEFT(t1.submit_on, 10) submit_on FROM sch_class_routine t1 WHERE 1=1";
        // $sql = "SELECT t1.*, TRIM(CONCAT(COALESCE(LEFT(t1.start_time, 5),''),' to ',COALESCE(LEFT(t1.end_time, 5),''))) period,  LEFT(t1.submit_on, 10) submit_on FROM sch_class_routine t1 WHERE 1=1";
        $sql = "SELECT t1.*, TRIM(CONCAT(COALESCE(t1.start_time,''),' to ',COALESCE(t1.end_time,''))) period, 
        TRIM(CONCAT(coalesce(t2.first_name, ''), ' ' , coalesce(t2.last_name, '') )) teacher_name , 
        LEFT(t1.submit_on, 10) submit_on, t3.digit, t3.cat_name, t4.class_name, t5.subject_name FROM
        sch_class_routine t1 LEFT JOIN (SELECT t21.identity_no, t21.first_name, t21.last_name FROM 
        sch_teacher t21)t2 ON t1.teacher_code = t2.identity_no LEFT JOIN (SELECT t31.* FROM sch_category t31)t3 ON 
        t3.cat_code = t1.cat_code LEFT JOIN (SELECT t41.* FROM sch_classes t41)t4 ON t4.class_code = t1.class_name_code 
        LEFT JOIN (SELECT t51.* FROM sch_subject t51)t5 ON t5.sub_code = t1.subject_name_code WHERE 1=1
        ";

        if($arg["_options"] === "report"){
            $sql .= 'AND t1.class_name_code ="'.$arg["class_name_code"].'" AND t1.cat_code ="'.$arg["cat_code"].'" ';
        }
        $sql .= " ORDER BY t1.day";
        //return $sql;
        $this->db->query($sql);
        return $this->db->resultSet();

    }
    //
    public function getAllExamSchedule(){
        $sql = "SELECT t1.*, TRIM(CONCAT(COALESCE(t1.start_time,''),' - ',COALESCE(t1.end_time,''))) period, LEFT(t1.submit_on, 10) submit_on, t2.digit, t2.cat_name, t3.class_name, t4.subject_name FROM sch_exam_schedule t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t2.cat_code = t1.cat_code LEFT JOIN (SELECT t31.* FROM sch_classes t31)t3 ON t1.class_name_code = t3.class_code LEFT JOIN (SELECT t41.* FROM sch_subject t41)t4 ON t1.subject_name_code = t4.sub_code WHERE 1=1";
        $sql .= " ORDER BY t2.digit";
        //return $sql;
        $this->db->query($sql);
        return $this->db->resultSet();

    }
    //
    public function getExamSchedule($arg=array()){
        $sql = "SELECT t1.*, TRIM(CONCAT(COALESCE(t1.start_time,''),' - ',COALESCE(t1.end_time,''))) period, LEFT(t1.submit_on, 10) submit_on, t2.digit, t2.cat_name, t3.class_name, t4.subject_name FROM sch_exam_schedule t1 LEFT JOIN (SELECT t21.* FROM sch_category t21)t2 ON t2.cat_code = t1.cat_code LEFT JOIN (SELECT t31.* FROM sch_classes t31)t3 ON t1.class_name_code = t3.class_code LEFT JOIN (SELECT t41.* FROM sch_subject t41)t4 ON t1.subject_name_code = t4.sub_code WHERE 1=1 AND code = '".$arg['code']."' ";
        //return $sql;
        $this->db->query($sql);
        return $this->db->single();

    }
    //
    public function getAllExamGrade($arg=array()){
        $sql = "SELECT t1.* FROM sch_exam_grade t1  ORDER BY t1.digit";
        //
        $this->db->query($sql);//
        $obj = (object)[];
        if (($arg['_option'] ?? '') === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->grade_name} = $v; }, $this->db->resultSet());
            return $obj;
        }
        return $this->db->resultSet();

    }
    //
    public function getExamGrade($arg=array()){
        $sql = "SELECT t1.* FROM sch_exam_grade t1 WHERE t1.code = '".$arg['code']."' ";
        //return $sql;
        
        if($arg['option'] === 'max_percent_upto'){
            $sql = "SELECT max(t1.percent_upto)max_percent_upto FROM sch_exam_grade t1 ";
            $this->db->query($sql);
            return $this->db->single();
        }
        $this->db->query($sql);
        return $this->db->single();

    }
    //
    public function getSchNotice($arg=array()){
        $sql = "SELECT t1.*, DATE(t1.date_posted)date_posted, TIME(t1.date_posted)time_posted FROM sch_notice t1";

        if($arg['_option'] === 'code'){
            $sql = "SELECT t1.* FROM sch_notice t1 WHERE t1.code ='".$arg["code"]."' ";
            $this->db->query($sql);
            return $this->db->single();
        }
        if($arg['_option'] === 'perTerm'){
            $sql = "SELECT t1.* FROM sch_notice t1 WHERE t1.term_code ='".$arg["term_code"]."' ";
            // $this->db->query($sql);
            // return $this->db->single();
        }

        $sql .= ' ORDER BY t1.date_posted DESC';
        $this->db->query($sql);
        return $this->db->resultSet();

    }

    public function getTerms($arg=array()){
        $sql = "SELECT t1.*, t1.year year_ FROM sch_term t1 WHERE 1=1";
        
        if (($arg['_option'] ?? '') === 'select') {
            $sql = "SELECT t1.code id, t1.term text FROM sch_term t1 WHERE t1.code LIKE '%" . $arg['searchTerm'] . "%'";
        }
        if (($arg['_option'] ?? '') === 'select2') {
            $sql = "SELECT t1.year id, t1.year text FROM sch_term t1 WHERE t1.status = '1' AND t1.code LIKE '%" . $arg['searchTerm'] . "%'";
        }
        ///////////////////////////
        if (($arg['_option'] ?? '') === 'year') {
            $sql = "SELECT t1.* FROM sch_term t1 WHERE 1=1 AND t1.year = '".$arg['year']."' ORDER BY t1.start_date DESC, t1.end_date DESC LIMIT 1 ";
        }
        ////////////////
        if (($arg['_option'] ?? '') === 'all_select2') {
            $sql = "SELECT t1.code id,  TRIM(CONCAT(COALESCE(t1.term, ''), ' ', COALESCE(t1.year, ''))) text FROM sch_term t1 WHERE t1.code LIKE '%" . $arg['searchTerm'] . "%'";
        }
        //
        if (($arg['_option'] ?? '') === 'current') {
            $sql = "SELECT t1.*, DATEDIFF(t1.end_date, t1.start_date)AS days_, DATEDIFF(t1.end_date, CURRENT_DATE())AS days_remains FROM sch_term t1 WHERE t1.status = '1' LIMIT 1";
            $this->db->query($sql);
            return $this->db->single();
            // var_dump($this->db->single());
            // exit;
        }
        $this->db->query($sql);
        //
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        // $sql .= ' ORDER BY t1.start_date DESC';
        // var_dump($this->db->single());exit;
        return $this->db->resultSet();

    }
    //
    public function getTerm($arg=array()){
        $sql = "SELECT t1.* FROM sch_term t1 WHERE 1=1 AND t1.code =  '".$arg['code']."'  ";
    
        $this->db->query($sql);
        return $this->db->single();
        // return $this->db->resultSet();

    }
    //
    public function getExamRate($arg=array()){
        $sql = "SELECT t1.* FROM sch_exam_rate t1 WHERE 1=1";

        if(($arg["_option"] ?? '') === "object"){
            $sql .= " AND t1.rate_code = '".$arg['rate_code']."' ";
            $this->db->query($sql);
            return $this->db->single();

        }
        if(($arg['status'] ?? '') !== ''){
            $sql = "SELECT t1.class_work, t1.mid_term_exam, t1.terminal_exam FROM sch_exam_rate t1 WHERE 1=1";
            $sql .= " AND t1.status = '1' ";
            $this->db->query($sql);
            return $this->db->single();
        }

        $this->db->query($sql);
        // return $this->db->single();
        return $this->db->resultSet();
    }
    //
    public function getExamName($arg=array()){
        $sql = "SELECT t1.* FROM sch_exam_name t1 WHERE 1=1";

        if(($arg["_option"] ?? '') === "object"){
            $sql .= " AND t1.exam_name_code = '".$arg['exam_name_code']."' ";
            $this->db->query($sql);
            return $this->db->single();

        }
        if(($arg['status'] ?? '') !== ''){
            $sql .= " AND t1.status = '1' ";
            $this->db->query($sql);
            return $this->db->single();
        }

        $this->db->query($sql);
        // return $this->db->single();
        return $this->db->resultSet();
    }
    //
    public function getSocialBeh($arg=array()){
        $sql = "SELECT t1.* FROM sch_social t1 WHERE 1=1";

        if($arg['option'] === 'object'){
            $sql .= " AND t1.code = '".$arg['code']."' ";
            $this->db->query($sql);
            return $this->db->single();
        }


        $this->db->query($sql);
        // return $this->db->single();
        return $this->db->resultSet();
    }
    //
    public function getSocialKey($arg=array()){
        $sql = "SELECT t1.* FROM social_beh_key t1 WHERE 1=1";

        if($arg['option'] === 'object'){
            $sql .= " AND t1.key_name = '".$arg['code']."' ";
            $this->db->query($sql);
            return $this->db->single();
        }
        if($arg['option'] === 'max_key_val'){
            $sql = "SELECT max(t1.key_value)max_key_value FROM social_beh_key t1 ";
            $this->db->query($sql);
            return $this->db->single();
        }
        $sql .= " ORDER BY t1.key_value DESC";
        $this->db->query($sql);
        // return $this->db->single();
        return $this->db->resultSet();
    }
    public function getImages($arg=array()){
        $sql = "SELECT t1.* FROM product_images t1 WHERE 1=1";
        //
        if (($arg['_option'] ?? '') === 'image_code') { 
            $sql = " AND ti.image_code ='".$arg['image_code']."' LIMIT 1";
            $this->db->query($sql);
            return $this->db->single();
            // var_dump($this->db->single());
            // exit;
        }
        $this->db->query($sql);
        //
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->image_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        // var_dump($this->db->single());exit;
        return $this->db->resultSet();

    }

    public function getProducts($arg=array()){
        $sql = "SELECT t1.*, t2.cat_name FROM products t1 LEFT JOIN (SELECT t2_.cat_name, t2_.cat_code FROM sch_category t2_)t2 ON t1.`level` = t2.cat_code WHERE 1=1";
        
        if($arg['_option'] === 'cat_code'){
            $sql .= " AND t1.level ='".$arg['cat_code']."' ";
            // var_dump($sql);exit;
        }

        $this->db->query($sql);
        //
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->product_code} = $v; }, $this->db->resultSet());
            return $obj;
        }
        // var_dump($this->db->single());exit;
        return $this->db->resultSet();
    }
    //
    public function getProduct($arg=array()){
        $sql = "SELECT t1.* FROM products t1 WHERE 1=1 AND t1.product_code ='".$arg['product_code']."' ";
        $this->db->query($sql);
        return $this->db->single();
    }
    //
    public function getImage($arg=array()){
        $sql = "SELECT t1.image_code, t1.type, t1.size, t1.name FROM product_images t1 WHERE 1=1 AND t1.image_code =  '".$arg['image_code']."'  ";
        $this->db->query($sql);
        return $this->db->single();
        // return $this->db->resultSet();

    }

    
    // log ////////////////////////////////////////////////////
    
    public function userLog($arg = array()) {
        $sql = "INSERT INTO sys_user_log (username, user_ip, event_log, remarks) VALUES ('" . $arg['username'] . "', '" . $arg['user_ip'] . "', '" . $arg['event_log'] . "', '" . str_ireplace('"[', '[', (str_ireplace('"{', '{', (str_ireplace('}"', '}', (str_ireplace(']"', ']', $arg['remarks']))))))) . "')";
        $this->db->query($sql);
        $this->db->execute();
    }
    
    public function getTitles($arg = array()) {
        $this->db->query("SELECT t1.title id, t1.title text FROM cod_title t1 WHERE t1.title LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getBusinesses($arg = array()) {
        $this->db->query("SELECT t1.business id, t1.business text FROM cod_business t1 WHERE t1.business LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getStates($arg = array()) {
        $this->db->query("SELECT t1.state id, t1.state text FROM cod_state t1 WHERE t1.state LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getCountries($arg = array()) {
        $this->db->query("SELECT t1.country id, t1.country text FROM cod_country t1 WHERE t1.country LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getOccupations($arg = array()) {
        $this->db->query("SELECT t1.occupation id, t1.occupation text FROM cod_occupation t1 WHERE t1.occupation LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getSectors($arg = array()) {
        $this->db->query("SELECT t1.sector id, t1.sector text FROM cod_sector t1 WHERE t1.sector LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getGenders($arg = array()) {
        $this->db->query("SELECT t1.gender id, t1.gender text FROM cod_gender t1 WHERE t1.gender LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getMaritalStatuses($arg = array()) {
        $this->db->query("SELECT t1.marital_status id, t1.marital_status text FROM cod_marital_status t1 WHERE t1.marital_status LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }
    
    public function getReligions($arg = array()) {
        $this->db->query("SELECT t1.religion id, t1.religion text FROM cod_religion t1 WHERE t1.religion LIKE '%" . $arg['searchTerm'] . "%'");
        
        return $this->db->resultSet();
    }

    
    public function getCurrencies($arg = []) {
    
        if ($arg['_option'] === 'select') {
            $sql = "SELECT t1.currency_code id, t1.currency_code text FROM cod_currency t1 WHERE t1.currency_code LIKE '%{$arg['searchTerm']}%' ORDER BY text ";
            $this->db->query($sql);
            return $this->db->resultSet();
        }
    
        $dataTable = $this->dataTable([
            'search' => preg_split('/[ +]/', $arg['search']['value'] ?? ''),
            'fields' => ['t1.currency_code', 't1.currency_main', 't1.currency_sub', 't1.currency_rate', 't1.remarks']
        ]);
        
        $sql = "SELECT t1.auto_id, t1.currency_code, t1.currency_main, t1.currency_sub, t1.currency_rate, t1.html_code, t1.remarks, t1.currency_code id, t1.currency_code text FROM cod_currency t1 WHERE 1=1 {$dataTable} ";
        
        $sql .= "ORDER BY t1.currency_code ";
        //
        $sql .= " LIMIT " . DATATABLE_LIMIT;
    
        $this->db->query($sql);
        $obj = (object)[];
        if ($arg['_option'] === 'object') {
            array_map(function ($v) use ($obj) { $obj->{$v->currency_code} = $v; }, $this->db->resultSet());
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
    
    public function getCurrency($arg = [])
    {
        
        $sql = "SELECT t1.* FROM cod_currency t1 WHERE t1.currency_code = '{$arg['currency_code']}' LIMIT 1";
        
        $this->db->query($sql);
        return $this->db->single();
    }

    
    private function dataTable($arg = []) {
        $_return = '';
        foreach ($arg['search'] as $v) {
            $_return .= " AND (" . implode(" LIKE '%{$v}%' OR ", $arg['fields']) . " LIKE '%{$v}%') ";
        }
        return $_return;
    }
    
    // System ////////////////////////////////////////////////////
    
    /**
     * Returns a GUIDv4 string
     *
     * Uses the best cryptographically secure method
     * for all supported platforms with fallback to an older,
     * less secure version.
     *
     * @param bool $trim
     * @return string
     */
    public function GUIDv4($trim = true): string
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }
        
        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        
        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace .
            substr($charid, 0, 8) . $hyphen .
            substr($charid, 8, 4) . $hyphen .
            substr($charid, 12, 4) . $hyphen .
            substr($charid, 16, 4) . $hyphen .
            substr($charid, 20, 12) .
            $rbrace;
        return $guidv4;
    }
    
    /**
     * @param string[] $arg [#,##0.00, INT|FLOAT]
     * @return float
     */
    public function getNumber($arg = array('num' => '0', 'type' => 'FLOAT'))
    {
        $num = preg_replace('/[^0-9\.]/i', '', $arg['num']);
        return $arg['type'] == 'INT' ? (int)$num : (float)$num;
    }
    
    public function passwordGenerator() {
        //
        $number = "0123456789";
        $lower = "abcdefghijklmnopqrstuvwxyz";
        $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $special = "@#$%^*/?";
        //
        $x = str_shuffle(
            substr(str_shuffle($lower), 0, 4) .
            substr(str_shuffle($upper), 0, 2) .
            substr(str_shuffle($number), 0, 3) .
            substr(str_shuffle($special), 0, 1)
        );
    
        return str_shuffle($x);
    }
    
    /*
     * Remove empty [fields: key] from [data: key => value] object
     */
    public function removeEmptyFields($arg = array()) {
        
        $fields = $arg['fields'];
        $data = $arg['data'];
        
        $empty_value = [];
        foreach ($data ?? [] as $k => $v) {
            if (empty($v)) {
                $empty_value[] = $k;
            }
        }
        if (!empty($empty_value)) $fields = array_diff($fields, $empty_value);
        
        return $fields;
    }
    
    /**
     * Generate new serial number
     *
     * @param array $arg [auto_item, digit, prefix, suffix, serial, field, table]
     * @return string
     */
    public function newAuto($arg = array())
    {
        
        $this->db->query("DELETE FROM sys_auto WHERE TRIM(`key`) = '' OR TRIM(`key`) IS NULL");
        //
        $this->db->execute();
        
        if (empty($arg['auto_item']) || strtoupper($arg['auto_item']) == "AUTO") {
            
            $arg['auto_item'] = str_ireplace('AUTO', '', $arg['auto_item'] ?? '');
            
            //---fetch serial no-------
            $this->db->query("SELECT * FROM sys_auto WHERE TRIM(`key`) = '" . $arg['serial'] . "' AND NOT (`key` = '' OR `key` IS NULL) LIMIT 1");
            //
            if ($this->db->rowCount() <= 0) {
                $this->db->query("INSERT INTO sys_auto (`key`, `val`) VALUES ('" . $arg['serial'] . "', '0')");
                //
                $this->db->execute();
            }
            
            $this->db->query("SELECT * FROM sys_auto WHERE `key` = '" . $arg['serial'] . "' AND NOT (`key` = '' OR `key` IS NULL) LIMIT 1");
            //
            $row_auto = $this->db->single();
            $row_auto->val += 1;
    
            $arg['auto_item'] = $arg['prefix'] . ($arg['digit'] == '' ? $row_auto->val : substr($arg['digit'] . $row_auto->val, -strlen($arg['digit']))) . $arg['suffix']; //4
            
            $auto_exists = true;
            while ($auto_exists) {
                
                $this->db->query("SELECT " . $arg['field'] . " FROM " . $arg['table'] . " WHERE " . $arg['field'] . " = '" . $arg['auto_item'] . "' AND NOT (" . $arg['field'] . " = '' OR " . $arg['field'] . " IS NULL) LIMIT 1"); //5
                //
                $row_item = $this->db->rowCount();
                
                if ($row_item > 0) {
                    $arg['auto_item'] = $arg['prefix'] . ($arg['digit'] == '' ? $row_auto->val += 1 : substr($arg['digit'] . $row_auto->val += 1, -strlen($arg['digit']))) . $arg['suffix']; //6
                    $auto_exists = true;
                } else
                    $auto_exists = false;
            }
            
            //-save last sys_auto
            $this->db->query("UPDATE sys_auto SET `val` = '" . $row_auto->val . "' WHERE `key` = '" . $arg['serial'] . "' LIMIT 1");
            //
            $this->db->execute();
        }
        
        return $arg['auto_item'];
    }
    
    /**
     *
     * @param array $arg [array(object, ..., object), action, fields, table, option]
     * @return string
     */
    public function sqlQuery($arg = array())
    {
        
        //return gettype($arg['array'][0]);
        //
        if (!is_array($arg['array'])) return "Data not specified";
        //
        else if (!is_object($arg['array'][0])) return "Data[0] not Object";
        //
        else if (!in_array($arg['action'], array('INSERT', 'UPDATE'))) return "Insert/update not specified";
        //
        else if (!is_array($arg['fields'])) return "Fields not specified";
        //
        else if (!is_string($arg['table'])) return "Database table not specified";
        
        //
        if (strtoupper($arg['action']) == 'INSERT') {
            $insert = $values = "";
            foreach ($arg['fields'] as $key) {
                $insert .= ", `" . $key . "`";
            }
            
            //
            $values = "";
            foreach ($arg['array'] as $val) {
                if (is_object($val)) {
                    $value = "";
                    foreach ($arg['fields'] as $key) {
                        $value .= ", '" . addslashes($val->{$key} ?? '') . "'";
                    }
                    $values .= ", (" . substr($value, 2) . ")";
                }
            }
            
            // additional
            if ($arg['option'] == 'ADD')
                $sql = ", " . str_ireplace('\'NULL\'', "NULL", substr($values ?? '', 2));
            else
                $sql = "INSERT INTO " . $arg['table'] . " (" . substr($insert, 2) . ") VALUES " . str_ireplace('\'NULL\'', "NULL", substr($values, 2));
        } //
        else if (strtoupper($arg['action']) == 'UPDATE') {
            $update = "";
            foreach ($arg['fields'] as $key) {
                $update .= ", `" . $key . "` = '" . addslashes($arg['array'][0]->{$key} ?? '') . "'";
            }
            
            $sql = "UPDATE " . $arg['table'] . " SET " . str_ireplace('\'NULL\'', "NULL", substr($update, 2));
            if (!empty($arg['option']))
                $sql .= " " . $arg['option'];
        } //
        else {
            $sql = "Unknown error";
        }
        
        return str_ireplace('\'\'', "NULL", str_ireplace('\\\\', '\\', str_ireplace('\\\\', '\\', str_ireplace('\"', '\'', str_ireplace('\'', '\"', $sql)))));
    }
    
    /**
     * @param array $arg ($number, [$num_suffix], [$dec_suffix])
     * @return string
     */
    public function numberToWord($arg = array())
    {
        
        $number = round((float)preg_replace('/[^0-9\+\-\.]/', '', $arg['number']), 2);
        $num_suffix = $arg['num_suffix'];
        $dec_suffix = $arg['dec_suffix'];
        
        // array of possible number to words
        $word_array = array(1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five", 6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten", 11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 14 => "Fourteen", 15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 18 => "Eighteen", 19 => "Nineteen", 20 => "Twenty", 21 => "Twenty-One", 22 => "Twenty-Two", 23 => "Twenty-Three", 24 => "Twenty-Four", 25 => "Twenty-Five", 26 => "Twenty-Six", 27 => "Twenty-Seven", 28 => "Twenty-Eight", 29 => "Twenty-Nine", 30 => "Thirty", 31 => "Thirty-One", 32 => "Thirty-Two", 33 => "Thirty-Three", 34 => "Thirty-Four", 35 => "Thirty-Five", 36 => "Thirty-Six", 37 => "Thirty-Seven", 38 => "Thirty-Eight", 39 => "Thirty-Nine", 40 => "Forty", 41 => "Forty-One", 42 => "Forty-Two", 43 => "Forty-Three", 44 => "Forty-Four", 45 => "Forty-Five", 46 => "Forty-Six", 47 => "Forty-Seven", 48 => "Forty-Eight", 49 => "Forty-Nine", 50 => "Fifty", 51 => "Fifty-One", 52 => "Fifty-Two", 53 => "Fifty-Three", 54 => "Fifty-Four", 55 => "Fifty-Five", 56 => "Fifty-Six", 57 => "Fifty-Seven", 58 => "Fifty-Eight", 59 => "Fifty-Nine", 60 => "Sixty", 61 => "Sixty-One", 62 => "Sixty-Two", 63 => "Sixty-Three", 64 => "Sixty-Four", 65 => "Sixty-Five", 66 => "Sixty-Six", 67 => "Sixty-Seven", 68 => "Sixty-Eight", 69 => "Sixty-Nine", 70 => "Seventy", 71 => "Seventy-One", 72 => "Seventy-Two", 73 => "Seventy-Three", 74 => "Seventy-Four", 75 => "Seventy-Five", 76 => "Seventy-Six", 77 => "Seventy-Seven", 78 => "Seventy-Eight", 79 => "Seventy-Nine", 80 => "Eighty", 81 => "Eighty-One", 82 => "Eighty-Two", 83 => "Eighty-Three", 84 => "Eighty-Four", 85 => "Eighty-Five", 86 => "Eighty-Six", 87 => "Eighty-Seven", 88 => "Eighty-Eight", 89 => "Eighty-Nine", 90 => "Ninety", 91 => "Ninety-One", 92 => "Ninety-Two", 93 => "Ninety-Three", 94 => "Ninety-Four", 95 => "Ninety-Five", 96 => "Ninety-Six", 97 => "Ninety-Seven", 98 => "Ninety-Eight", 99 => "Ninety-Nine", 100 => "One Hundred", 200 => "Two Hundred", 300 => "Three Hundred", 400 => "Four Hundred", 500 => "Five Hundred", 600 => "Six Hundred", 700 => "Seven Hundred", 800 => "Eight Hundred", 900 => "Nine Hundred");
        
        //
        if ($number > 0) {
            
            // convert to number format
            $number = number_format($number, '2', ',', ',');
            
            // split to array
            $number_array = explode(",", $number ?? '');
            
            // separate decimal
            $dec_value = intval($number_array[count($number_array) - 1]);
            if ($dec_value > 0) {
                
                // convert decimal part to word;
                $dec_word = $word_array[$dec_value] . " " . $dec_suffix;
            }
            
            // thousand array,
            $thousand = array("", "Thousand ", "Million ", "Billion ", "Trillion ", "Quadrillion ", "Quintillion ");
            
            // loop through all numbers in array
            $t = 0;
            $num_word = "";
            for ($i = count($number_array) - 2; $i >= 0; $i--) {
                
                // separate each number in the array to 1 + 2 digits
                $num_value = intval($number_array[$i]);
                
                // if empty number then no word
                if ($num_value == 0) {
                    $num_word = "" . $num_word;
                } // strip off 0 hundred
                elseif (strlen($num_value . "") <= 2) {
                    $num_word = $word_array[$num_value] . " " . $thousand[$t] . $num_word;
                    
                    // add 'and' if not last number
                    if ($i == 1) {
                        $num_word = " and " . $num_word;
                    }
                } // set the hundred word
                else {
                    $num_word = $word_array[substr($num_value, 0, 1) . "00"] . (intval(substr($num_value, 1, 2)) > 0 ? " and " : "") . $word_array[intval(substr($num_value, 1, 2))] . " " . $thousand[$t] . $num_word;
                }
                $t++;
            }
            
            // add currency to word
            if (!empty($num_word)) {
                $num_word .= " " . $num_suffix;
                // add "Only" if decimal does not exists
                if ($dec_value <= 0) $num_word .= ' Only';
            }
        }
        
        // return word value
        return $num_word . " " . $dec_word;
    }
    
}