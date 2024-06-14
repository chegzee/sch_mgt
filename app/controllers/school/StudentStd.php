<?php

class StudentStd extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $examGrade;
    protected $products;
    protected $rowCount;
    protected $code;
    protected $sql;
    protected $result;
    protected $student_code;
    protected $menu;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        // var_dump($this->post);exit;
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->student = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));
        $this->menu = $this->getStdSidebar();
        // var_dump($this->data);exit;
        if (empty($this->student)) header('Location: ' . URL_ROOT . '/system/login');
       
        $this->data['student'] = (array)$this->student;
        
        // $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->examGrade = $this->model('SystemData')->getAllExamGrade(array());
        $this->examGradeobj = $this->model('SystemData')->getAllExamGrade(array("_option" => "object"));
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        $this->examRate = $this->model('SystemData')->getExamRate(array("status" => "1"));
        $this->examName = $this->model('SystemData')->getExamName(array("status" => "1"));
        $this->socialBehaviour = $this->model('SystemData')->getSocialBeh(array());
        $this->socialKey = $this->model('SystemData')->getSocialKey(array());
        $this->products = $this->model('SystemData')->getProducts(array());

        //max value in social behaviour table
        $this->max_key_val = $this->model('SystemData')->getSocialKey(array("option"=>"max_key_val"));
        //max value in exam grade percent upto table
        $this->max_percent_upto = $this->model('SystemData')->getExamGrade(array("option"=>"max_percent_upto"));
        $this->data['levelsobj'] = $this->model('SystemData')->getCategories(array("_option" => "object"));
        $this->data['classesobj'] = $this->model('SystemData')->getClasses(array("_option" => "object"));
        // $this->data['classroomsObj'] = $this->model('SystemData')->getclassrooms(array("_option" => "object"));
        // $this->data['classrooms'] = $this->model('SystemData')->getClassrooms(array());
        
        //menu content
        $this->data['menu'] = $this->menu;
        $this->data['examGrade'] = $this->examGrade;
        $this->data['examGradeObj'] = $this->examGradeobj;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        $this->data['examRate'] = $this->examRate;
        $this->data['socialBehaviour'] = $this->socialBehaviour;
        $this->data['products'] = $this->products;
        $this->data['socialKey'] = $this->socialKey;
        $this->data['max_key_val'] = $this->max_key_val;
        $this->data['max_percent_upto'] = $this->max_percent_upto;
        $this->data['examName'] = $this->examName;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        // 
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('school/studentStd', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        // var_dump($this->student);exit;
        // $students = $this->model('SystemData')->getStudents(array( "student_code" => $this->student_code ?? '[]'));
        $students = $this->model('SystemData')->getStudents(array("_option2"=>"std_code", "std_code"=> $this->student->std_code ));
        echo json_encode(array('data' => $students));
        exit;
    }

    public function saveSubject(){
        $post = json_decode($this->post->data);
        $student_code = $this->post->std;
        
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $student_code['std_code']));
        $subject = json_encode($post);
        $sql = "UPDATE sch_students SET subjects = '".$subject."' WHERE std_code = '".$student->std_code."'";
        // var_dump($sql);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        if($result === true){
            $sql_history = "UPDATE sch_std_history t1  SET subjects = '".$subject."' WHERE t1.std_code = '".$student->std_code."' AND t1.term = '".$student->term."' ";
        //    var_dump($sql_history);exit;
            $this->db->query($sql_history);
            $this->db->execute();
        }
         //
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


    }
    //////
    public function saveSubjResult(){
        // var_dump($this->post);exit;
        $post = json_decode($this->post->data);
        $student_code = $this->post->std;
        
        $subject_result = json_encode($post);
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $student_code['std_code']));
        $sql = "UPDATE sch_students SET subject_result = '".$subject_result."' WHERE std_code = '".$student->std_code."'";
        // var_dump($student);exit;
        $this->db->query($sql);
        
        $result = $this->db->execute();
        
        $sql_history = "UPDATE sch_std_history t1  SET subject_result = '".$subject_result."' WHERE t1.std_code = '".$student->std_code."' AND t1.term = '".$student->term."' ";
        $this->db->query($sql_history);
         $this->db->execute();
        if($result === true){
            echo json_encode(array('status' => true, 'data' => ''));
            exit;
        }
            echo json_encode(array('status' => false, 'data' => $result));


    }

    public function activities(){
        $post = $this->post;
        $student = $this->model('SystemData')->getStudents(array("_option"=>"std_code", "std_code" => $post->std_code));
        
        $sql = "UPDATE sch_students SET activities ='".$post->activities."' WHERE std_code='".$student->std_code."' ";
        $this->db->query($sql);
        $res = $this->db->execute();
        if($res === true){
            $sql = "UPDATE sch_std_history SET activities ='".$post->activities."' WHERE std_code='".$post->std_code."'  AND term = '".$student->term."' ";
            $this->db->query($sql);
            $res = $this->db->execute();
            // var_dump($res);exit;
            echo json_encode(array('status' => $res)); 
            exit;
        }
        echo json_encode(array('status' => false, "message"=>$res)); 
        // var_dump($res);exit;
    }

    public function saveMultipleInvoice(){
        $post = $this->post;
        
        
        // period
        $period = $this->model('AccountData')->getAccountPeriod(array('trans_date' => date('Y-m-d'), 'username' => $this->student->std_code));
        if (!$period) {
            echo json_encode(array('status' => false, 'message' => 'LEDGER POSTING NOT ALLOWED'));
            exit;
        }

        else if ($period->invoice !== '1') {
            echo json_encode(array('status' => false, 'message' => 'LEDGER JOURNAL NOT ALLOWED'));
            exit;
        }

        $std_code = implode("', '", $post->std_code);
        $invoices = array();
        // $sql = "SELECT t1.std_code, t1.invoice_code FROM sch_students t1 WHERE t1.std_code IN ('{$std_code}')";
        
        $bank = $this->model("AccountData")->getBanks(array( "status"=> "1"));
        
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
            $invoice->bank_account = $bank->account_code;
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