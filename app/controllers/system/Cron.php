<?php

class Cron extends Controller
{
    private $db;
    protected $dashUpdate;
    
    public function __construct()
    {
        $this->db = new Database;
        $this->dashUpdate = 60 * 30;
    }
    
    public function index()
    {
        echo json_encode(array('status' => false, 'message' => 'METHOD REQUIRED'));
        exit;
    }
    
    public function dashboard()
    {
        
        $post = (object)filter_input_array(INPUT_POST);
        
        //
        $jsonMap = array();
        
        // last update
        $sql = "SELECT COALESCE(TIMESTAMPDIFF(SECOND, MAX(log_time), NOW()) , " . $this->dashUpdate . ") `lapse` FROM cod_analytic WHERE `key` LIKE '%-" . $post->branch_code . "'";
        //echo json_encode($sql); exit;
        $this->db->query($sql);
        $row = $this->db->single();
        
        // ///////////////////////////////////////////////////////////////////////////////////////////
        
        //
        if ((int)$row->lapse >= $this->dashUpdate) {
            // reset dateTime
            $sql = "DELETE FROM cod_analytic WHERE 1=1 AND `key` LIKE '%-" . $post->branch_code . "'";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $this->db->execute();
            
            // currency html codes
            $sql = "SELECT cc.currency_code, cc.currency_rate, cc.html_code FROM cod_currency cc";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $rows = $this->db->resultSet();
            
            //
            $currencyCode = array();
            if ($rows) {
                foreach ($rows as $val) {
                    $currencyCode[$val->currency_code] = (array)$val;
                }
            }
            $sql = "INSERT INTO cod_analytic (`key`, `val`) VALUES ('currencyCode-" . $post->branch_code . "', '" . json_encode($currencyCode) . "')";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $this->db->execute();
            
            // policyIncome // dr.branch_code = '" . $post->branch_code . "' AND
            $sql = "
            SELECT g1.`year`, g1.`month`, g1.commission, if(g1.receipt IS NULL, commission, 0) receipt, g1.policy_code, g1.debit_code, g1.ref_code, g1.risk_name, g1.branch_name FROM (
                SELECT CAST(YEAR(dr.trans_date) AS CHAR) `year`, CAST(MONTH(dr.trans_date) AS CHAR) `month`, dr.gross_premium * (1 - COALESCE(cr.bus_prop, 0) / 100) * dr.com_rate / 100 * dr.currency_rate `commission`, dr.policy_code, dr.debit_code, dr.ref_code, rp.ref_code receipt, sr.risk_name, br.branch_name
	            FROM tec_policy_debit dr
	            LEFT JOIN (SELECT cr1.ref_code, cr1.bus_prop FROM tec_policy_credit cr1 WHERE cr1.bus_type = 'REV') cr ON dr.ref_code = cr.ref_code
	            LEFT JOIN (SELECT rp1.ref_code FROM tec_policy_receipt rp1) rp ON dr.ref_code = rp.ref_code
	            LEFT JOIN (SELECT sr1.subrisk_code, sr1.subrisk_name, sr1.risk_code, sr2.risk_name FROM cod_subrisk sr1 LEFT JOIN cod_risk sr2 ON sr1.risk_code = sr2.risk_code) sr ON dr.subrisk_code = sr.subrisk_code
	            LEFT JOIN (SELECT br1.branch_code, br1.branch_name FROM cod_branch br1) br ON dr.branch_code = br.branch_code
                WHERE (YEAR(dr.trans_date) = YEAR(NOW()) OR YEAR(dr.trans_date) = YEAR(NOW())-1)
            ) g1 WHERE g1.commission > 0";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $rows = $this->db->resultSet();
            
            //
            $policyIncome = array();
            if ($rows) {
                foreach ($rows as $val) {
                    array_push($policyIncome, (array)$val);
                }
            }
            $sql = "INSERT INTO cod_analytic (`key`, `val`) VALUES ('policyIncome-" . $post->branch_code . "', '" . json_encode($policyIncome) . "')";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $this->db->execute();
            
            // claim status
            $sql = "SELECT cm.claim_code, cm.loss_amount, cm.claim_status, DATEDIFF(NOW(), cm.trans_date) age, cm.modified_on, dr.currency_code FROM tec_claim cm LEFT JOIN (SELECT dr1.debit_code, dr1.currency_code FROM tec_policy_debit dr1) dr ON cm.debit_code = dr.debit_code ORDER BY cm.modified_on LIMIT 10";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $rows = $this->db->resultSet();
            
            //
            $claimHistory = array();
            if ($rows) {
                foreach ($rows as $val) {
                    array_push($claimHistory, (array)$val);
                }
            }
            $sql = "INSERT INTO cod_analytic (`key`, `val`) VALUES ('claimHistory-" . $post->branch_code . "', '" . json_encode($claimHistory) . "')";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $this->db->execute();
    
            // currentClient
            $sql = "SELECT t1.client_code, t1.trans_date, t3.first_name, t3.last_name, t3.username, t3.phone FROM tec_policy_debit t1 LEFT JOIN (SELECT t21.client_code, t21.trans_date FROM tec_policy_debit t21) t2 ON t1.client_code = t2.client_code AND t1.trans_date < t2.trans_date LEFT JOIN (SELECT t31.client_code, t31.first_name, t31.last_name, t31.username, t31.phone FROM tec_client t31) t3 ON t1.client_code = t3.client_code WHERE t2.trans_date IS NULL LIMIT 6";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $rows = $this->db->resultSet();
    
            //
            $currentClient = array();
            if ($rows) {
                foreach ($rows as $val) {
                    array_push($currentClient, (array)$val);
                }
            }
            $sql = "INSERT INTO cod_analytic (`key`, `val`) VALUES ('currentClient-" . $post->branch_code . "', '" . json_encode($currentClient) . "')";
            //echo json_encode($sql); exit;
            $this->db->query($sql);
            $this->db->execute();
            
        }
        
        // ///////////////////////////////////////////////////////////////////////////////////////////
        
        // fetch dashboard
        $sql = "SELECT * FROM cod_analytic WHERE `key` LIKE '%-" . $post->branch_code . "'";
        //echo json_encode($sql); exit;
        $this->db->query($sql);
        $rows = $this->db->resultSet();
        
        //
        if (!empty($rows)) {
            foreach ($rows as $val) {
                $jsonMap[str_ireplace('-' . $post->branch_code, '', $val->key ?? '')] = $val->val;
            }
        }
        
        echo json_encode($jsonMap);
        exit;
    }
}
