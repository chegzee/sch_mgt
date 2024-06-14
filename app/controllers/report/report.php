<?php
// Include autoloader
require_once APP_ROOT . '/helpers/dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

class Report extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    protected $reportURL;
    
    protected $report;
    protected $detail;
    protected $output;
    PROTECTED $default_bank;
    
    public function __construct()
    {
        $this->db = new Database;
        
        $this->reportURL = '';
        $this->default_bank = DEFAULT_BANK;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
        // $this->post = (object)filter_input_array(INPUT_POST);
        $this->post = (object)filter_var_array(json_decode(file_get_contents("php://input"), true));
        // var_dump($this->post->items['term_code']);exit;
        if (!is_array($this->post->items) || empty($this->post->items) || empty($this->post->report)) {
            echo json_encode(array('status' => false, 'message' => 'ITEM|REPORT NOT FOUND')); exit;
        }
        // var_dump(array("report"=> $this->post->report));exit;
        if (method_exists($this, $this->post->report)) $this->{$this->post->report}();
        else $this->index();
        
    }
    
    public function index()
    {
        echo json_encode(array('status' => false, 'message' => 'REPORT: ' . $this->post->report . ' NOT FOUND')); exit;
    }
    
    protected function printURL($sql) {

        $this->db->query($sql);
        $result = $this->db->execute();
        
        if ($result === false) {
            echo json_encode(array('status' => false, 'message' => $result)); exit;
        }
        $url = str_ireplace(' ', '.', str_ireplace('__report__', $this->report, str_ireplace('__user_log__', $this->user->username, str_ireplace('__detail__', $this->detail, str_ireplace('__output__', $this->output, JASPER_URL ?? ''))))) . '&' . rand();
        
        //echo json_encode(array('status' => false, 'message' => $sql)); exit;
        echo json_encode(array('status' => true, 'data' => array('url' => $url))); exit;
        
    }

    public function invoice_list(){
        $term_code = $this->post->items['term_code'];
        if($term_code === 'all') $term_code = '';
        // var_dump($term_code);exit;
        // $term = 
       $data = $this->model('AccountData')->getInvoices(array("term_code"=>$term_code));
       
       $tableRow = '';
       foreach($data as $k => $v){
            $tableRow .= '<tr>
                <td>'.$v->std_name.'</td>
                <td>'.$v->cat_name.' '.$v->class_name.'</td>
                <td>'.number_format($v->invoice_amount, 2).'</td>
                <td>'.number_format($v->balance_due, 2).'</td>
                <td>'.$v->term_name.' '.$v->year.'</td>
            </tr>';

       }
        // var_dump($data[0]->branch_name);exit;

        // var_dump($this->numberOfExam);exit;
        $html .= '<DOCTYPE html><html>
        <head>
            <meta http-equiv="content-type" content="text/html;charset=UTF-8">
            <style type="text/css">
            @page { margin: 10px; }
            body { margin: 20px; font-family: DejaVu Sans, sans-serif; font-size: 9pt; }
            table { border-collapse: collapse; }
            /* table th, table td { font-family: sans-serif; font-size: 9pt; border: 0px solid #dddddd; padding: 2px }*/
            input[type=radio] { -ms-transform: scale(0.7); -webkit-transform: scale(0.7); transform: scale(0.7); }
            
            /* .table-01 th, .table-01 td { border: 1px solid #b8b8b8; } */
            /* .table-01 tr:nth-child(odd) { background-color: #f6f6f6; } */
            
            .table-01 th, .table-01 td { font-size: 7pt; vertical-align: top; }
            .table-border th, .table-border td { border: 1px solid #b8b8b8; }
            .table-noborder th, .table-noborder td { border: none; }
            .container_flip{
                position:relative;
                border-right:1px solid black;
                text-align:center;
                width:15mm;
            }

            input[type=checkbox]:before { font-family: DejaVu Sans; }
            input[type=checkbox] { display: inline; }
            input[type=radio] { display: inline; }
            input[type=radio]:before { font-family: DejaVu Sans; }
            
            
            .watermark {
                position: fixed;

                /**
                    Set a position in the page for your image
                    This should center it vertically
                **/
                top:   110mm;
                left:     75mm;

                /** Change image dimensions **/
                width:    5cm;
                height:   8cm;
                font: times;

                /** Your watermark should be behind every content **/
                z-index:  -1000;
            }

            </style>
        </head>
        <body>
        ';
        $html .= '
            <div style="position:relative;width:100%;">
                <table style="width:100%;margin-top:1mm">
                    <tr> 
                        <td rowspan="4" style="">
                            <img src="'.ASSETS_ROOT.'/images/app_logo.jpg" style="width: 40mm; height: 20mm;">
                        </td>
                        <td style="text-align:right;padding-right:4px;width:70mm">
                        </td>
                    </tr>

                </table>
                <table style="width:100%;margin-top:1mm">
                    <thead>
                    <tr>
                        <th style="text-align:left;">Name</th>
                        <th style="text-align:left;">Class</th>
                        <th style="text-align:left;">Inv.Amt.</th>
                        <th style="text-align:left;">Balance due</th>
                        <th style="text-align:left;">Term</th>
                    
                    </tr>
                    </thead>
                    <tbody>
                    '.$tableRow.'
                    </tbody>
                </table>
                <div style="position:absolute;left:0mm;bottom:1mm;font-size:8px">
                    printed on: '.date('Y-m-d H:i:s').'
                </div>
            </div></body></html>
        ';

        $this->sendPDF(array("html" => $html));
        // var_dump(array("scared"=> $data));
        // exit;
    }
    
    public function payment()
    {
        $post = $this->post;
        
        // Japerreport settings
        $this->report = 'payment_print_m';
        $this->detail = 'PAYMENTS';
        $this->output = 'pdf';
        
        $heading = "'" . $this->user->username . "' user_log, '" . ADDRESS . "' c01, '" . ADDRESS2 . "' c02, '" . ADDRESS3 . "' c03, '" . PHONE . "' c04, '" . WWW . "' c05, '" . EMAIL . "' c06";
        
        $sql = "
        SELECT
            {$heading}, t1.payment_code c07, t1.ref_code c08, t1.debit_account c09, t1.credit_account c10, t1.trans_mode c11, t4.company_name c12, t4.contact c13, t4.phone c14, t2.policy_code c15, t2.subrisk_name c16, t2.risk_name c17, t2.currency_code c18, t2.client_name c19, t2.agent_name c20, t2.branch_name c21, t3.account_name c22, t4.description_cn c23, t4.bank_account c24,
            LEFT(t1.trans_date, 10) a01, t2.start_date a02, t2.end_date a03, t4.trans_date_cn a04,
            t4.bus_prop f01, t2.currency_rate f02,
            t1.gross_premium d01, t2.sum_insured * t4.bus_prop / 100 d02, t2.gross_premium * t4.bus_prop / 100 d03, t2.commission_dn * t4.bus_prop / 100 d04, t2.vat_amount_dn * t4.bus_prop / 100 d05, t2.other_charge_dn * t4.bus_prop / 100 d06, (t2.gross_premium - t2.commission_dn - t2.vat_amount_dn - t2.other_charge_dn) * t4.bus_prop / 100 d07,
            t1.trans_info t01, t1.description t02
        FROM tec_policy_payment t1
        LEFT JOIN (
            SELECT LEFT(t41.trans_date, 10) trans_date_cn, t41.credit_code, t41.ref_code, t41.company_code, t41.bus_prop, t41.description description_cn, t42.company_name, t42.contact, t42.phone, t42.username, t41.bank_account
            FROM tec_policy_credit t41
            LEFT JOIN (SELECT t421.company_code, t421.company_name, t421.contact, t421.phone, t421.username FROM tec_company t421) t42 on t41.company_code = t42.company_code
        ) t4 ON t1.ref_code = t4.credit_code
        LEFT JOIN (
            SELECT t21.policy_code, t21.debit_code, LEFT(t21.start_date, 10) start_date, LEFT(t21.end_date, 10) end_date, t21.currency_code, FORMAT(t21.currency_rate, 2) currency_rate, t21.sum_insured, t21.gross_premium, t21.com_rate / 100 * t21.gross_premium commission_dn, t21.vat_rate / 100 * t21.com_rate / 100 * t21.gross_premium vat_amount_dn, COALESCE(t21.other_charge, '0') other_charge_dn, t21.subrisk_code, t22.subrisk_name, t22.risk_code, t22.risk_name, t23.client_code, t23.client_name, t24.agent_code, t24.agent_name, t27.branch_code, t27.branch_name
            FROM tec_policy_debit t21
            LEFT JOIN (SELECT t221.subrisk_code, t221.subrisk_name, t221.risk_code, t222.risk_name FROM cod_subrisk t221 LEFT JOIN cod_risk t222 ON t221.risk_code = t222.risk_code) t22 ON t21.subrisk_code = t22.subrisk_code
            LEFT JOIN (SELECT t231.client_code, if(t231.business = 'Corporate',COALESCE(t231.first_name,''),TRIM(CONCAT(COALESCE(t231.title,''),' ',COALESCE(t231.first_name,''),' ',COALESCE(t231.last_name,'')))) client_name FROM tec_client t231) t23 ON t21.client_code = t23.client_code
            LEFT JOIN (SELECT t241.agent_code, t241.agent_name FROM tec_agent t241) t24 ON t21.agent_code = t24.agent_code
            LEFT JOIN (SELECT t271.branch_code, t271.branch_name FROM cod_branch t271) t27 ON t21.branch_code = t27.branch_code
        ) t2 ON t4.ref_code = t2.debit_code
        LEFT JOIN (SELECT t31.account_code, t31.account_name FROM act_account t31) t3 on t1.credit_account = t3.account_code
        WHERE 1=1 ";
        
        //
        if (!empty($post->items)) {
            foreach ($post->items as $k => $v) {
                $sql .= ($k == 0 ? "AND (" : "OR ") . "t1.payment_code = '" . $v . "' ";
            }
            $sql .= ") ";
        }
        //
        // user_log, c01: site_address, c02: site_address2, c03: site_address3, c04: site_phone, c05: site_website, c06: site_email,
        // c07: payment_code, c08: ref_code, c09: debit_account, c10: credit_account, c11: trans_mode, c12: company_name, c13: contact, c14: phone, c15: policy_code, c16: subrisk_name, c17: risk_name, c18: currency_code, c19: client_name, c20: agent_name, c21: branch_name, c22: account_name,
        // a01: trans_date_pym, a02: start_date, a03: end_date, a04: trans_date_cn
        // f01: bus_prop, f02: currency_rate,
        // d01: gross_premium, d02: sum_insured_cn, d03: gross_premium_cn, d04: commission_cn, d05: vat_amount_cn, d06: other_charge_cn, d07: net_amount d07,
        // t01: trans_info, t02: description
        //
        $sql = "INSERT INTO rpt_1 (user_log, c01, c02, c03, c04, c05, c06, c07, c08, c09, c10, c11, c12, c13, c14, c15, c16, c17, c18, c19, c20, c21, c22, c23, c24, a01, a02, a03, a04, f01, f02, d01, d02, d03, d04, d05, d06, d07, t01, t02) " . $sql;
        //echo json_encode($sql); exit;
    
        $this->printURL($sql);
    }
    
    // TODO report ->claim_age
    
    // /////////////////////////////////////////////////////////////////////////
    
    public function ledger_entry()
    {
        $post = $this->post;
        $post->items = (object)$post->items;
        
        // Japerreport settings
        $this->report = 'ledger_entry';
        $this->detail = 'LEDGER ENTRIES FROM: ' . $post->items->rpt_start_date . ' TO: ' . $post->items->rpt_end_date;
        $this->output = '';
        
        $sql = "SELECT '" . $this->user->username . "' user_log, SUBSTR(gl.trans_date, 1, 10) a01, gl.account_code c01, gl.account_name c02, 
        gl.currency_code c03, gl.trans_code c04, gl.trans_type c05, gl.ref_code c06, gl.submit_by c07, 
        gl.approved_by c08, gl.modified_by c09, aa.group_code c10, aa.group_name c11, 
        aa.base_code c12, aa.base_name c13, aa.dr_cr c14, gl.currency_rate f01, gl.debit * gl.currency_rate d01, 
        gl.credit * gl.currency_rate d02, gl.debit - gl.credit d03, gl.trans_detail t01 FROM act_gledger gl
        LEFT JOIN (SELECT aa1.account_code, aa2.group_code, aa2.group_name, aa2.base_code, aa3.base_name, aa3.dr_cr 
        FROM act_account aa1 LEFT JOIN (SELECT aa2_.group_code, aa2_.group_name, aa2_.base_code FROM act_group aa2_) aa2 on aa1.group_code = aa2.group_code 
        LEFT JOIN (SELECT aa3_.base_code, aa3_.base_name, aa3_.dr_cr FROM act_base aa3_) aa3 on aa2.base_code = aa3.base_code) aa 
        ON gl.account_code = aa.account_code
        WHERE SUBSTR(gl.trans_date, 1, 10) >= '" . $post->items->rpt_start_date . "' AND SUBSTR(gl.trans_date, 1, 10) <= '" . $post->items->rpt_end_date . "' ";
    
        //
        if (!empty($post->items->rpt_group_code)) {
            $sql .= " AND aa.group_code = '" . $post->items->rpt_group_code . "' ";
            $this->detail .= '. ' . $post->items->rpt_group_code;
        }
    
        //
        if (!empty($post->items->rpt_account_code)) {
            $sql .= " AND gl.account_code = '" . $post->items->rpt_account_code . "' ";
            $this->detail .= ' ' . $post->items->rpt_account_code;
        }
        
        $this->printURL($sql);
    }
    
    public function journal_entry()
    {
        $post = $this->post;
        $post->items = (object)$post->items;
        
        // Japerreport settings
        $this->report = 'journal_entry';
        $this->detail = 'JOURNAL ENTRIES FROM: ' . $post->items->rpt_start_date . ' TO: ' . $post->items->rpt_end_date;
        $this->output = '';

        $sql = "SELECT gl.trans_date, gl.account_code, gl.account_name, 
        gl.currency_code, gl.trans_code, gl.trans_type, gl.ref_code, gl.submit_by, 
        gl.approved_by, gl.modified_by, aa.group_code, aa.group_name, aa.base_code, 
        aa.base_name, aa.dr_cr, gl.currency_rate, gl.debit, gl.amount, gl.trans_detail 
        FROM (SELECT SUBSTR(gl_.trans_date, 1, 10) trans_date, gl_.account_code, gl_.account_name, gl_.currency_code,
        gl_.trans_code, gl_.trans_type, gl_.ref_code, gl_.submit_by, gl_.approved_by, gl_.modified_by, 
        gl_.currency_rate, SUM(gl_.debit) * gl_.currency_rate debit, SUM(gl_.debit) amount, gl_.trans_detail 
        FROM act_gledger gl_ 
		  WHERE (gl_.trans_type = 'CSH' OR gl_.trans_type = 'JNL' OR gl_.trans_type = 'CHB' OR gl_.trans_type = 'OPB') 
        AND SUBSTR(gl_.trans_date, 1, 10) >= '" . $post->items->rpt_start_date . "' AND SUBSTR(gl_.trans_date, 1, 10) <= '" . $post->items->rpt_end_date . "' 
        GROUP BY gl_.trans_code, gl_.trans_date, gl_.account_code, gl_.account_name, gl_.currency_code,gl_.trans_type,
		   gl_.ref_code, gl_.submit_by, gl_.approved_by, gl_.modified_by, gl_.currency_rate, gl_.trans_detail) gl LEFT JOIN (SELECT aa1.account_code, aa2.group_code, aa2.group_name, aa2.base_code, aa3.base_name, aa3.dr_cr 
        FROM act_account aa1 LEFT JOIN (SELECT aa2_.group_code, aa2_.group_name, aa2_.base_code FROM act_group aa2_) aa2 on aa1.group_code = aa2.group_code 
        LEFT JOIN (SELECT aa3_.base_code, aa3_.base_name, aa3_.dr_cr FROM act_base aa3_) aa3 on aa2.base_code = aa3.base_code) aa ON gl.account_code = aa.account_code 
        WHERE 1=1 
        ";
    
        //
        if (!empty($post->items->rpt_group_code)) {
            $sql .= " AND aa.group_code = '" . $post->items->rpt_group_code . "' ";
            $this->detail .= '. ' . $post->items->rpt_group_code;
        }
    
        //
        if (!empty($post->items->rpt_account_code)) {
            $sql .= " AND gl.account_code = '" . $post->items->rpt_account_code . "' ";
            $this->detail .= ' ' . $post->items->rpt_account_code;
        }
    }
    
    public function financial_statement()
    {
        $post = $this->post;
        $post->items = (object)$post->items;
        
        $post->items->rpt_start_date = ($post->items->rpt_ref_date ?? '') === '' ? $post->items->rpt_start_date : date('Y-01-01', strtotime($post->items->rpt_ref_date));
        $post->items->rpt_end_date =  ($post->items->rpt_ref_date ?? '') === '' ? $post->items->rpt_end_date : date('Y-m-d', strtotime($post->items->rpt_start_date . ' +' . (int)$post->items->rpt_act_period . ' month -1 day'));
        //echo json_encode($post); exit;
        
        //
        $dr_cr = (object)array(
            'trial_balance' => array('TRIAL BALANCE', 'trial_balance', ''),
            'balance_sheet' => array('BALANCE SHEET', 'financial_statement', 'BS'),
            'income_statement' => array('INCOME STATEMENT', 'financial_statement', 'IS'),
            'financial_position' => array('FINANCIAL POSITION', 'financial_statement', ''),
        );
        
        // Japerreport settings
        $this->report = $dr_cr->{$post->items->rpt_act_type}[1];
        
        $this->detail = $dr_cr->{$post->items->rpt_act_type}[0] . ' FROM: ' . $post->items->rpt_start_date . ' TO: ' . $post->items->rpt_end_date;
        $this->output = '';
        
        $sql = "
        SELECT '{$this->user->username}' user_log, t1.account_code c01, t1.account_name c02, t1.group_code c03, t2.group_name c04, t2.base_code c05, t2.base_name c06, t2.dr_cr c07, SUM(COALESCE(t3.debit_o, '0')) d01, SUM(COALESCE(t3.credit_o, '0')) d02, SUM(COALESCE(t3.debit_o, '0')) - SUM(COALESCE(t3.credit_o, '0')) d03, SUM(COALESCE(t3.debit_m, '0')) d04, SUM(COALESCE(t3.credit_m, '0')) d05, SUM(COALESCE(t3.debit_m, '0')) - SUM(COALESCE(t3.credit_m, '0')) d06, SUM(COALESCE(t3.debit_o, '0')) + SUM(COALESCE(t3.debit_m, '0')) d07, SUM(COALESCE(t3.credit_o, '0')) + SUM(COALESCE(t3.credit_m, '0')) d08, SUM(COALESCE(t3.debit_o, '0')) + SUM(COALESCE(t3.debit_m, '0')) - SUM(COALESCE(t3.credit_o, '0')) - SUM(COALESCE(t3.credit_m, '0')) d09 
        FROM act_account t1 
        INNER JOIN (
            SELECT t21.group_code, t21.group_name, t21.base_code, t22.base_name, t22.dr_cr FROM act_group t21 
            INNER JOIN (SELECT t221.base_code, t221.base_name, t221.dr_cr FROM act_base t221) t22 ON t21.base_code = t22.base_code
        ) t2 ON t1.group_code = t2.group_code 
        INNER JOIN (
            SELECT t31.account_code, t32.dr_cr
                 , SUM(IF(t31.trans_type = 'OPB', t31.debit * t31.currency_rate, '0')) debit_o
                 , SUM(IF(t31.trans_type = 'OPB', t31.credit * t31.currency_rate, '0')) credit_o
                 , SUM(IF(t31.trans_type = 'OPB', '0', t31.debit * t31.currency_rate)) debit_m
                 , SUM(IF(t31.trans_type = 'OPB', '0', t31.credit * t31.currency_rate)) credit_m 
            FROM act_gledger t31 
            INNER JOIN (
                SELECT t321.account_code, t321.group_code, t322.dr_cr FROM act_account t321 
                INNER JOIN (
                    SELECT t3221.group_code, t3221.base_code, t3222.dr_cr FROM act_group t3221 
                    INNER JOIN (SELECT t32221.base_code, t32221.dr_cr FROM act_base t32221) t3222 ON t3221.base_code = t3222.base_code
                ) t322 ON t321.group_code = t322.group_code WHERE t322.dr_cr LIKE '%" . $dr_cr->{$post->items->rpt_act_type}[2] . "%'
            ) t32 ON t31.account_code = t32.account_code 
            WHERE SUBSTR(t31.trans_date, 1, 10) >= '{$post->items->rpt_start_date}' AND SUBSTR(t31.trans_date, 1, 10) <= '{$post->items->rpt_end_date}' AND t32.dr_cr LIKE '%" . $dr_cr->{$post->items->rpt_act_type}[2] . "%' 
            GROUP BY t31.account_code
        ) t3 ON t1.account_code = t3.account_code WHERE t2.dr_cr LIKE '%%' GROUP BY t2.dr_cr, t2.base_code, t2.group_code, t1.account_code";
    
        //
        if ($dr_cr->{$post->items->rpt_act_type}[1] === 'financial_statement') {
            $sql = "
            SELECT '{$this->user->username}' user_log, t1.group_code c01, t1.group_name c02, t2.base_code c03, t2.base_name c04, t2.dr_cr c05, COALESCE(t3.balance_0, '0') d01 
            FROM act_group t1 
            INNER JOIN (SELECT t21.base_code, t21.base_name, t21.dr_cr FROM act_base t21) t2 ON t1.base_code = t2.base_code 
            INNER JOIN (
                SELECT t32.group_code, t32.dr_cr, SUM(t31.debit * t31.currency_rate) - SUM(t31.credit * t31.currency_rate) balance_0 
                FROM act_gledger t31 
                INNER JOIN (
                    SELECT t321.account_code, t321.group_code, t322.dr_cr FROM act_account t321 
                    INNER JOIN (
                        SELECT t3221.group_code, t3221.base_code, t3222.dr_cr 
                        FROM act_group t3221 
                        LEFT JOIN (SELECT t32221.base_code, t32221.dr_cr FROM act_base t32221) t3222 ON t3221.base_code = t3222.base_code
                    ) t322 ON t321.group_code = t322.group_code 
                    WHERE t322.dr_cr LIKE '%" . $dr_cr->{$post->items->rpt_act_type}[2] . "%'
                ) t32 ON t31.account_code = t32.account_code 
                WHERE SUBSTR(t31.trans_date, 1, 10) >= '{$post->items->rpt_start_date}' AND SUBSTR(t31.trans_date, 1, 10) <= '{$post->items->rpt_end_date}' AND t32.dr_cr LIKE '%" . $dr_cr->{$post->items->rpt_act_type}[2] . "%' 
                GROUP BY t32.group_code
            ) t3 ON t1.group_code = t3.group_code 
            WHERE t2.dr_cr LIKE '%" . $dr_cr->{$post->items->rpt_act_type}[2] . "%'";
            // var_dump($sql);exit;
        }
        //echo json_encode($sql); exit;
        
    }

    //generate pdf and send it to client
    public function sendPDF($arg = array()){
        // Instantiate and use the dompdf class
        // $this->dompdf = new Dompdf();
        // var_dump($arg["html"]);exit;
        $options = new Options();
        $options->set('isPhpEnabled', "true");
        $options->set('enable_remote', "true");
        // $this->dompdf = new Dompdf(['enable_remote' => true]);
        $this->dompdf = new Dompdf($options);
        
        // Load HTML content
        $this->dompdf->loadHtml($arg["html"]); //'<h1>Welcome to CodexWorld.com</h1>'
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper("A4", "portrait");
        
        // Render the HTML as PDF
        $this->dompdf->render();
        // $canvas = $this->dompdf->getCanvas();
        // $fontMetrics = new FontMetrics($canvas, $options);
        // $image_url = ASSETS_ROOT . "/images/logo.svg";
        // $w = $canvas->get_width();
        // $h = $canvas->get_height();
        // $font = $fontMetrics->getFont("times");
        // $text = "CONFIDENTIAL";
        // $textHeight = $fontMetrics->getFontHeight($font, 75);
        // $textWidth = $fontMetrics->getTextWidth($text, $font, 75);
        // $canvas->set_opacity(.1);
        // $x = (($w-$textWidth)/2);
        // $x = (($h-$textHeight)/2);
        // $canvas->text(120, 280, $text, $font, 75);
        // $imgWidth = 200;
        // $imgHeight = 20;

        // $x = (($w-$imgWidth)/2);
        // $y = (($h-$imgHeight)/2);
        // $canvas->image($image_url, $x, $y, $imgWidth, $imgHeight);
        
        //Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream('Report ' . date('Y-m-d H-i-s' . '.pdf'), ["Attachment" => 0]);

    }
}