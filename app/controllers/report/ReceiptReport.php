<?php
// Include autoloader
require_once APP_ROOT . '/helpers/dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

class ReceiptReport extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    
    protected $post;
    protected $grade;
    protected $finalScore = 0;
    protected $numberOfExam = 0;
    protected $average = 0;
    protected $finalGrade = '';
    // protected $student = null;
    // protected $html = null;
    
    public function __construct()
    {
        $this->db = new Database;
        
        // var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
        
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if($this->user === false){
            $this->user = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));

        } 
        // var_dump($this->user); exit;
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
        // $this->post = (object)filter_input_array(INPUT_POST);
        $this->post = (object)filter_var_array(json_decode(file_get_contents("php://input"), true));
        
    }

    public function getReceipt(){
        $students=null;
        $html = '';
        
        $post = ($this->post);
        $receipt_code = implode("', '", $post->receipt_code);
        
        // $sql = "SELECT t1.* FROM sch_invoice t1 WHERE t1.invoice_code IN ( '{$invoice_code}' ) ";
        $receipts = $this->model('AccountData')->getReceipts(array("_option" => "multiple", "receipt_code" => $receipt_code));
        // var_dump($receipts);exit;
        foreach($receipts as $k => $rcp){
            if ($k > 0) $html .= '<div style="page-break-before: always"></div>';

            $payments = '';
            foreach(json_decode($rcp->receipt_obj) as $k => $v){
                $tr = '<tr style="border:1px solid black;">
                    <td style="text-align:left">'.$v->trans_date.'</td>
                    <td style="text-align:left">'.$v->invoice_code.'</td>
                    <td style="text-align:left">'.$v->receipt_code.'</td>
                    <td style="text-align:center">'.number_format($v->amount, 2).'</td>
                    <td style="text-align:left">'.$v->client_fullname.'</td>
                </tr>';
                $payments .= $tr;
            }
            // var_dump($rcp->trans_date);exit;
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
                .table-border th, .table-border td { border-bottom: 1px solid #b8b8b8; }
                .table-noborder th, .table-noborder td { border: none; }

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
                    <div style="width:100%;text-align:right;font-size:24;padding-right:4px;">RECEIPT</div>
                    <table style="width:100%;margin-top:5mm">
                        <tr> 
                            <td rowspan="4" style="">
                                <img src="'.$rcp->branch_logo.'" style="width: 40mm; height: 20mm;">
                            </td>
                            <td style="text-align:right;padding-right:4px;width:70mm">
                            '.$rcp->branch_name.'
                            </td>
                        </tr>
                        <tr> 
                            <td style="text-align:right;padding-right:4px;">
                            '.$rcp->branch_address.'
                            </td>
                        </tr>
                        <tr> 
                            <td style="text-align:right;padding-right:4px;">
                            '.$rcp->branch_state.',state '.$rcp->branch_country.'
                            </td>
                        </tr>
                    </table>
                    <table style="width:100%;margin-top:2mm">
                        <tr>
                            <td style="width:50mm" colspan="2">TO: </td>
                        </tr>
                        <tr>
                            <td>'.$rcp->std_name.'</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:100mm">'.$rcp->std_address.'</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>'.$rcp->cat_name.'</td>
                            <td></td>
                        </tr>
                    </table>
                    <table style="width:90mm;margin-top:15mm">
                        <tr>
                            <td style="width:35mm"> Invoice Amount: </td>
                            <td>'.$rcp->currency_code.' '.number_format($rcp->invoice_amount, 2).'</td>
                        </tr>
                    </table>
                    <table class="table-border" style="width:100%;margin-top:10mm">
                        <thead>
                            <tr style="background-color:blue;color:white;">
                                <th style="text-align:left">Date</th>
                                <th style="text-align:left">Invoice no.</th>
                                <th style="text-align:left">Receipt no.</th>
                                <th style="text-align:center;">Amount Paid</th>
                                <th style="text-align:left">Paid By</td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$payments.'
                        <tbody>
                    </table>
                    <table style="width:100%;margin-top:15mm">
                        <tr>
                            <td style="text-align:right;">Amount Paid: </td>
                            <td style="width:30%;text-align:left">'.$rcp->currency_code.' '.number_format($rcp->total_receipt_amount, 2).'</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">Balance Due: </td>
                            <td style="width:30%;text-align:left">'.$rcp->currency_code.' '.number_format($rcp->balance_due, 2).'</td>
                        </tr>
                    
                    </table>
                    <div style="position:absolute;left:0mm;bottom:1mm;font-size:8px">
                        printed on: '.date('Y-m-d H:i:s').'
                    </div>
                </div></body></html>
            ';

        }
        // var_dump($receipts);exit;


        $this->sendPDF(array("html" => $html));
        // var_dump($sql);exit;

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