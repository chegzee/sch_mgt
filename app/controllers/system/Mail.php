<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mail
{
    private $regexp_email = '/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/i';
    
    private $header =
        '<div style="min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #ffffff;">' .
            '<table style="table-layout: fixed; vertical-align: top; min-width: 320px; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9; width: 100%;">' .
                '<tbody>' .
                    '<tr style="vertical-align: top;" valign="top">' .
                        '<td style="word-break: break-word; vertical-align: top;" valign="top">' .
                            '<div style="background-color:transparent;">' .
                                '<div style="min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #ffffff;">' .
                                    '<img src="' . AZURE_BLOB . '/images/app-logo.png" style="height: 100px; width: 300px">' .
                                '</div>' .
                            '</div>' .
                            '<div style="background-color:transparent;">' .
                                '<div style="min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; margin: 0 auto; padding: 20px; background-color: #ffffff;">';
    private $footer = '        </div>' .
                            '</div>' .
                        '</td>' .
                    '</tr>' .
                '</tbody>' .
            '</table>' .
        '</div>';
    
    private $note = '<p></p><p></p><strong style="color: #ff0000">IMPORTANT:</strong> <em style="color: #777777">This e-mail together with any attachments is intended only for the recipient(s) named above. It may contain confidential or privileged information and should not be read, copied or otherwise used by any other person. If you are not the intended recipient, please notify the sender immediately by e-mail or telephone and delete the mail, all attachments and copies there from your device. Any use, copying, disclosure, alteration or reliance on the contents of this mail for any purpose without the named sender’s express authorization is strictly prohibited.</em>';

    /**
     * @param $param {contact, email, email_cc, subject, body}
     * @return object
     */
    function sendMail($param)
    {
       // echo json_encode(['status' => false, 'message' => $param]); exit;
        $return = '';

        $param = str_ireplace('\\', '', rawurldecode($param ?? ''));
        $param = json_decode($param);

        $email = new stdClass();

        foreach ($param as $key => $val)
            $email->{$key} = addslashes($val ?? '');
        //return array('status' => false, 'message' => http_response_code(200), 'data' => (array)$param);

        if (!preg_match($this->regexp_email, $email->email))
            return (object)array('status' => false, 'message' => $email->email . ' INVALID EMAIL');

        if (empty($email->contact))
            $email->contact = '<' . $email->email . '>';
    
        $email->body = $this->header . (DEPLOYMENT === 'production' ? '' : '<p style="color: #f00; font-size: 24px">Demo Only</p>') . $email->body . $this->footer;
    
        $email->email_cc = explode(',', $email->email_cc ?? '');
        $email->cc = array();
    
        if (!empty($email->email_cc)) {
            foreach ($email->email_cc as $key => $val) {
                //
                if ($email->email == $val) continue;
                //
                if (preg_match($this->regexp_email, $val)) {
                    $email->cc[] = array('email' => $val);
                }
            }
        }
    
        $email->from = preg_match($this->regexp_email, $email->from) ? $email->from : EMAIL;
        $email->bcc = array(array('email' => 'techteam@safamdigital.com'));
        
        $email->attachments = array(
            array(
                'content' => $email->attachment_content,
                'filename' => $email->attachment_filename,
                'type' => $email->attachment_type,
                'disposition' => 'attachment',
            ),
            array(
                'content' => $email->attachment_content2,
                'filename' => $email->attachment_filename2,
                'type' => $email->attachment_type2,
                'disposition' => 'attachment',
            )
        );
    
        $email->post = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array(
                            'email' => $email->email,
                            'name' => $email->contact
                        )
                    ),
                    'cc' => $email->cc,
                    'bcc' => $email->bcc,
                )
            ),
            'subject' => $email->subject,
            'content' => array(
                array(
                    'type' => 'text/html',
                    'value' => html_entity_decode($email->body)
                )
            ),
            'from' => array(
                'email' => $email->from,
                'name' => SITE_NAME
            ),
            'reply_to' => array(
                'email' => REPLY_TO,
                'name' => SITE_NAME
            ),
            'attachments' => $email->attachments
        );
        // clean up email
        if (empty($email->post['personalizations'][0]['cc'])) {
            unset($email->post['personalizations'][0]['cc']);
        }
        if (empty($email->post['personalizations'][0]['bcc'])) {
            unset($email->post['personalizations'][0]['bcc']);
        }
        for ($i = 0; $i <= 10; $i++) {
            if (empty($email->post['attachments'][$i]['content'])) {
                unset($email->post['attachments'][$i]);
            }
        }
        $email->post['attachments'] = array_values($email->post['attachments']);
        if (empty($email->post['attachments'])) {
            unset($email->post['attachments']);
        }
        //return $email->post;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            //CURLOPT_POSTFIELDS => "{\"personalizations\":[{\"to\":[{\"email\":\"femi.fapohunda@gmail.com\",\"name\":\"John Doe\"}],\"dynamic_template_data\":{\"verb\":\"\",\"adjective\":\"\",\"noun\":\"\",\"currentDayofWeek\":\"\"},\"subject\":\"Hello, World!\"}],\"from\":{\"email\":\"noreply@domain.com\",\"name\":\"John Doe\"},\"reply_to\":{\"email\":\"noreply@domain.com\",\"name\":\"John Doe\"},\"template_id\":\"d-48ad145081b84899bd63a4d748b58dfe\"}",
            CURLOPT_POSTFIELDS => json_encode($email->post),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . SEND_GRID,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            //echo "cURL Error #:" . $err;
            return (object)array('status' => false, 'message' => $err);
        } else {
            $response = json_decode($response);
            $response = !empty($response) ? $response->errors[0]->message : '';
            
            return (object)array('status' => empty($response), 'message' => $response);
        }

    }

}
