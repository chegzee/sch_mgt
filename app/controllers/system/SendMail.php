<?php

require_once 'SystemSetting.php';
require_once 'Azurestorage.php';
require_once 'Mail.php';

class SendMail extends SystemSetting
{
    
    private object $post;
    private Database $db;
    private $riskObj;
    
    public function index()
    {
        $this->db = new Database;
        
        //
        $this->post = (object) filter_input_array(INPUT_POST);
        $this->post->items = json_decode($this->post->items);
        //echo json_encode(['status' => false, 'message' => $this->post]); exit;
        
        // action
        $method = $this->post->action;
        if (!method_exists($this, $method)) {
            echo json_encode(['status' => false, 'message' => 'ACTION REQUIRED']); exit;
        }
        
        $this->riskObj = $this->model('TechnicalData')->getRisks(array('_option' => 'object'));
        
        //
        echo json_encode($this->{$method}($this->post));
        
    }
    
    //
    function proposal($post) {
        
        if (empty($post->items)) return ['status' => false, 'message' => 'ITEMS REQUIRED'];
        
        $sql = "";
        foreach ($post->items as $v) {
            $sql .= "OR t1.proposal_code = '" . $v . "' ";
        }
        $sql = "
        SELECT t1.*, t2.client_name, t2.client_email, t2.client_address, t2.client_phone, t3.agent_name, t4.subrisk_name, t4.risk_code, t4.risk_name, t4.risk_email
        FROM tec_proposal t1
        LEFT JOIN (SELECT t21.client_code, if(t21.business = 'Corporate',COALESCE(t21.first_name,''),TRIM(CONCAT(COALESCE(t21.title,''),' ',COALESCE(t21.last_name,''),' ',COALESCE(t21.first_name,'')))) client_name, t21.username client_email, t21.address client_address, t21.phone client_phone FROM tec_client t21) t2 ON t1.client_code = t2.client_code
        LEFT JOIN (SELECT t31.agent_code, t31.agent_name FROM tec_agent t31) t3 ON t1.agent_code = t3.agent_code
        LEFT JOIN (SELECT t41.subrisk_code, t41.subrisk_name, t41.risk_code, t42.risk_name, t42.risk_email FROM cod_subrisk t41 LEFT JOIN (SELECT t42_.risk_code, t42_.risk_name, t42_.email risk_email FROM cod_risk t42_) t42 ON t42.risk_code = t41.risk_code) t4 ON t1.subrisk_code = t4.subrisk_code
        WHERE (" . substr($sql, 3) . ")"; //
        
        $this->db->query($sql);
        if ($this->db->rowCount() <= 0) return ['status' => false, 'message' => 'ITEMS NOT FOUND'];
        
        $rows = $this->db->resultSet();
        
        $doc_path = new Azurestorage();
        $message = '';
        $attachment = '';
        
        foreach ($rows as $k => $v) {
            $attachment = '';
            // azure_blob
            if (!empty($v->doc_path)) {
                $result = $doc_path->index2(['action' => 'listFiles', 'containerName' => AZURE_CONTAINER, 'directory' => 'proposals/' . $v->doc_path]);
                if ($result['status']) {
                    foreach ($result['data'] ?? [] as $k1 => $v1) {
                        $attachment .= '<div style="border: 1px solid; padding: 5px; margin: 3px"><a href="' . $v1['url'] . '" target="_blank" style="text-decoration: none">' . $v1['name'] . '</a></div>';
                    }
                }
            }
            $schedule = '';
            // schedule
            if (!empty($v->schedule)) {
                $schedule_array = json_decode($v->schedule);
                $first = true;
                foreach ($schedule_array as $k1 => $v1) {
                    // header
                    if ($first) {
    
                        $schedule .= '<tr>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>#</strong></td>';
                        // V
                        if ($v->risk_code === 'V') {
                            $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>' . $this->riskObj->{$v->risk_code}->schedule->veh_make->label . '</strong></td>';
                            $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>' . $this->riskObj->{$v->risk_code}->schedule->veh_model->label . '</strong></td>';
                        }
                        //
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>Sum Insured</strong></td>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>Premium</strong></td>';
                        $schedule .= '</tr>';
                        
                        $first = false;
                    }
                    
                    $schedule .= '<tr>';
                    $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->sn . '</td>';
                    // V
                    if ($v->risk_code === 'V') {
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->veh_make . '</td>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->veh_model . '</td>';
                    }
                    //
                    $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->sum_insured . '</td>';
                    $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->gross_premium . '</td>';
                    $schedule .= '</tr>';
                }
            }
            if (!empty($schedule))
                $schedule = '<table style="border-collapse: collapse; width: 100%; margin-bottom: 10px">' . $schedule . '</table>';
    
            // send email $row->client_email
            $mail = (new Mail())->sendMail(rawurlencode(json_encode(array('from' => $v->risk_email, 'contact' => $v->client_name, 'email' => $v->client_email, 'subject' => 'QUOTATION', 'body' => htmlentities('<div style="text-align: right; border-bottom: 1px solid; margin-bottom: 10px"><span style="font-size: 18pt">QUOTATION</span><p>Being premium due on proposal (' . $v->proposal_code . ')</p><p><strong>' . SITE_NAME . '</strong><br>' . ADDRESS . '<br>' . ADDRESS2 . '<br>' . ADDRESS3 . '</p><p>Phone: ' . PHONE . '<br>' . WWW . '</p></div><table style="width: 100%"><tr><td><span style="color: #333">BILL TO:</span><br><strong>' . $v->client_name . '</strong><br>' . $v->client_address . '<br><br>' . $v->client_phone . '<br>' . $v->client_email . '</td><td style="vertical-align: top"><table style="width: 100%"><tr><td style="text-align: right"><strong>Proposal #:</strong></td><td>' . $v->proposal_code . '</td></tr><tr><td style="text-align: right"><strong>Date:</strong></td><td>' . date('d-M-Y', strtotime($v->trans_date)) . '</td></tr></table></td></tr></table><div style="margin-top: 10px; margin-bottom: 10px; border-top: 1px solid; border-bottom: 1px solid"><table style="width: 100%"><tr><td><strong>' . $v->subrisk_name . '</strong></td><td style="text-align: right"><strong>' . number_format($v->gross_premium, 2) . '</strong></td></tr></table><p>Being premium due on proposal ' . $v->proposal_code . ', with SUM INSURED of: ' . number_format($v->sum_insured, 2) . '<br>. ' . $v->description . '<br>Period of cover: from ' . date('d-M-Y', strtotime($v->start_date)) . ' to ' . date('d-M-Y', strtotime($v->end_date)) . '<br>' . $attachment . ' ' . $schedule . '</p></div><table style="width: 100%"><tr><td style="vertical-align: top"><strong>Notes</strong><br>Make cheque payable to:<br>' . SITE_NAME . '<br>or pay into:<br>' . DEFAULT_BANK . '</td><td style="vertical-align: top"><strong>Amount Due (' . $v->currency_code . ') ' . number_format($v->gross_premium, 2) . '</strong><br><br><!--<span style="color: #fff; background-color: #4cc065; border-color: #4cc065; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem;"><a href="#" target="_black">Pay Now</a></span>--></td></tr></table>')))));
    
            $message .= $mail->message . ', ';
        }
        $message = substr($message, 0, -2);
        
        return ['status' => empty($message), 'message' => empty($message) ? 'Email sent to recipients' : $message];
    }
    
    //
    function debit($post) {
        
        if (empty($post->items)) return ['status' => false, 'message' => 'ITEMS REQUIRED'];
        
        $sql = "";
        foreach ($post->items as $v) {
            $sql .= "OR t1.debit_code = '" . $v . "' ";
        }
        $sql = "
        SELECT t1.*, t2.client_name, t2.client_email, t2.client_address, t2.client_phone, t3.agent_name, t4.risk_code, t4.subrisk_name, t4.risk_name, t4.risk_email
        FROM tec_policy_debit t1
        LEFT JOIN (SELECT t21.client_code, if(t21.business = 'Corporate',COALESCE(t21.first_name,''),TRIM(CONCAT(COALESCE(t21.title,''),' ',COALESCE(t21.last_name,''),' ',COALESCE(t21.first_name,'')))) client_name, t21.username client_email, t21.address client_address, t21.phone client_phone FROM tec_client t21) t2 ON t1.client_code = t2.client_code
        LEFT JOIN (SELECT t31.agent_code, t31.agent_name FROM tec_agent t31) t3 ON t1.agent_code = t3.agent_code
        LEFT JOIN (SELECT t51.policy_code, t51.subrisk_code FROM tec_policy t51) t5 ON t1.policy_code = t5.policy_code
        LEFT JOIN (
            SELECT t41.subrisk_code, t41.subrisk_name, t41.risk_code, t42.risk_name, t42.risk_email
            FROM cod_subrisk t41
            LEFT JOIN (SELECT t42_.risk_code, t42_.risk_name, t42_.email risk_email FROM cod_risk t42_) t42 ON t42.risk_code = t41.risk_code
        ) t4 ON t4.subrisk_code = t5.subrisk_code
        WHERE (" . substr($sql, 3) . ")";
        
        $this->db->query($sql);
        if ($this->db->rowCount() <= 0) return ['status' => false, 'message' => 'ITEMS NOT FOUND'];
        
        $rows = $this->db->resultSet();
        //echo json_encode(array('status' => true, 'message' => $this->riskObj)); exit;
        
        $doc_path = new Azurestorage();
        $message = '';
        
        foreach ($rows as $k => $v) {
            $attachment = '';
            // azure_blob
            if (!empty($v->doc_path)) {
                $result = $doc_path->index2(['action' => 'listFiles', 'containerName' => AZURE_CONTAINER, 'directory' => 'invoices/' . $v->doc_path]);
                if ($result['status']) {
                    foreach ($result['data'] ?? [] as $k1 => $v1) {
                        $attachment .= '<div style="border: 1px solid; padding: 5px; margin: 3px"><a href="' . $v1['url'] . '" target="_blank" style="text-decoration: none">' . $v1['name'] . '</a></div>';
                    }
                }
            }
            $schedule = '';
            // schedule
            if (!empty($v->schedule)) {
                $schedule_array = json_decode($v->schedule);
                $first = true;
                foreach ($schedule_array as $k1 => $v1) {
                    // header
                    if ($first) {
                
                        $schedule .= '<tr>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>#</strong></td>';
                        // V
                        if ($v->risk_code === 'V') {
                            $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>' . $this->riskObj->{$v->risk_code}->schedule->veh_make->label . '</strong></td>';
                            $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>' . $this->riskObj->{$v->risk_code}->schedule->veh_model->label . '</strong></td>';
                            $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>' . $this->riskObj->{$v->risk_code}->schedule->tppd_value->label . '</strong></td>';
                            $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>' . $this->riskObj->{$v->risk_code}->schedule->premium_rate->label . '</strong></td>';
                        }
                        //
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>Sum Insured</strong></td>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px"><strong>Premium</strong></td>';
                        $schedule .= '</tr>';
                
                        $first = false;
                    }
            
                    $schedule .= '<tr>';
                    $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->sn . '</td>';
                    // V
                    if ($v->risk_code === 'V') {
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->veh_make . '</td>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->veh_model . '</td>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->tppd_value . '</td>';
                        $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->premium_rate . '</td>';
                    }
                    //
                    $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->sum_insured . '</td>';
                    $schedule .= '<td style="border: 1px solid; padding: 2px 5px">' . $v1->gross_premium . '</td>';
                    $schedule .= '</tr>';
                }
            }
            //  echo json_encode(array('status' => true, 'message' => $v->risk_code));
            //  exit;
            if (!empty($schedule))
                $schedule = '<table style="border-collapse: collapse; width: 100%; margin-bottom: 10px">' . $schedule . '</table>';
    
            // send email $row->client_email
            $mailHTML = rawurlencode(json_encode(array('from' => $v->risk_email, 'contact' => $v->client_name, 'email' => $v->client_email, 'subject' => 'INVOICE', 'body' => htmlentities('<div style="text-align: right; border-bottom: 1px solid; margin-bottom: 10px"><span style="font-size: 18pt">INVOICE</span><p>Being premium due on policy (' . $v->policy_code . ')</p><p><strong>' . SITE_NAME . '</strong><br>' . ADDRESS . '<br>' . ADDRESS2 . '<br>' . ADDRESS3 . '</p><p>Phone: ' . PHONE . '<br>' . WWW . '</p></div><table style="width: 100%"><tr><td><span style="color: #333">BILL TO:</span><br><strong>' . $v->client_name . '</strong><br>' . $v->client_address . '<br><br>' . $v->client_phone . '<br>' . $v->client_email . '</td><td style="vertical-align: top"><table style="width: 100%"><tr><td style="text-align: right"><strong>Policy #:</strong></td><td>' . $v->policy_code . '</td></tr><tr><td style="text-align: right"><strong>Reference #:</strong></td><td>' . $v->debit_code . '</td></tr><tr><td style="text-align: right"><strong>Date:</strong></td><td>' . date('d-M-Y', strtotime($v->trans_date)) . '</td></tr></table></td></tr></table><div style="margin-top: 10px; margin-bottom: 10px; border-top: 1px solid; border-bottom: 1px solid"><table style="width: 100%"><tr><td><strong>' . $v->subrisk_name . '</strong></td><td style="text-align: right"><strong>' . number_format($v->gross_premium, 2) . '</strong></td></tr></table><p>Being premium due on proposal ' . $v->proposal_code . ', with SUM INSURED of: ' . number_format($v->sum_insured, 2) . '<br>. ' . $v->description . '<br>Period of cover: from ' . date('d-M-Y', strtotime($v->start_date)) . ' to ' . date('d-M-Y', strtotime($v->end_date)) . '<br>' . $attachment . $schedule . '</p></div><table style="width: 100%"><tr><td style="vertical-align: top"><strong>Notes</strong><br>Make cheque payable to:<br>' . SITE_NAME . '<br>or pay into:<br>' . DEFAULT_BANK . '</td><td style="vertical-align: top"><strong>Amount Due (' . $v->currency_code . ') ' . number_format($v->gross_premium, 2) . '</strong><br><br><!--<span style="color: #fff; background-color: #4cc065; border-color: #4cc065; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem;"><a href="#" target="_black">Pay Now</a></span>--></td></tr></table>'))));
            //echo json_encode(array('status' => true, 'message' => $mailHTML));exit;
            
            $mail = (new Mail())->sendMail($mailHTML);
    
            $message .= $mail->message . ', ';
        }
        $message = substr($message, 0, -2);
        
        return ['status' => empty($message), 'message' => empty($message) ? 'Email sent to recipients' : $message];
    }
    
}