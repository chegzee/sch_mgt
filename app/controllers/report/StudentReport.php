<?php
// Include autoloader
require_once APP_ROOT . '/helpers/dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

class StudentReport extends Controller
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
        if(empty($this->user)){
            $this->user = $this->model('SystemData')->verifyStudent(array('user_log' => $this->data['params']['user_log']));

        }
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
        $this->examGrade = $this->model('SystemData')->getAllExamGrade(array());
        $this->socialKey = $this->model('SystemData')->getSocialKey(array());
        $this->socialBehaviour = $this->model('SystemData')->getSocialBeh(array());
        // $this->post = (object)filter_input_array(INPUT_POST);
        $this->post = (object)filter_var_array(json_decode(file_get_contents("php://input"), true));
        
    }
    
    public function getResult()
    {
        $students=null;
        $html = '';
        
        $post = ($this->post);
        $std_code = implode("', '", $post->std_code);
        $term_code = implode("', '", $post->term);
        // $sql = "SELECT t1.std_code, t1.term FROM sch_students t1 WHERE t1.std_code IN  ( '{$std_code}' ) AND t1.term IN  ( '{$term_code}' ) ";
        if($post->db === 'student'){
            $students = $this->model("SystemData")->getStudents(array("_option"=>"print", "std_code"=> $std_code));
            
        }else if($post->db === 'history'){
            $students = $this->model("SystemData")->getStudentsHistory(array("_option"=>"print", "std_code"=> $std_code, "term_code"=> $term_code));

        }
        // var_dump($students);exit;
        $examRate = $this->model('SystemData')->getExamRate(array("status" => "1"));
        $examName = $this->model('SystemData')->getExamName(array("status" => "1"));
        
        // var_dump($examRate);exit;
        $grade = null;
        $rating = null;
        
        $classWork = $examRate->class_work > 0 ? '
        <th class="container_flip">
            <div>'.$examName->first_name.' </div>
            <div>'.$examRate->class_work.'%</div>
        </th>' : '';

        $midTermExam = $examRate->mid_term_exam > 0 ? '
        <th class="container_flip">
            <div> '.$examName->second_name.' </div>
            <div>'.$examRate->mid_term_exam .'%</div>
        </th>
        ' : '';

        foreach($this->examGrade as $k1 => $v){
            $grade .= $v->grade_name .'. '.''.$v->comment.'='. ''.$v->percent_from.'% -'. ''.$v->percent_upto.'%' ;
            $grade .= "<br/>";
 
         }
         
         foreach($this->socialKey as $k => $v){
            $rating .= '<div style="padding-left:4px;"><strong style="line-height:1.5em;">'.$v->key_value.' '.'</strong><span style="width:60%">'.$v->key_name .'</span></div>';
            
        }
        $gradeKey = '';

        foreach($examGrade as $k => $examGrade){
            $gradeKey .= $examGrade->grade_name .'. '.''.$examGrade->comment.' = '. ''.$examGrade->percent_from.'%  - '. ''.$examGrade->percent_upto.'%' ;
            $gradeKey .= "<br/>";
        }
        // $rr = intval($examRate->class_work ?? 0);
        $examRateLength = [];
        foreach($examRate as $k => $v){
            if(($v ?? 0) > 0){
                $examRateLength[$k] = $v;
            }
        }
        foreach($students as $k => $std){
            if(empty($std->subjects)) continue;
            if ($k > 0) $html .= '<div style="page-break-before: always"></div>';
            // $term = $this->model('SystemData')->getTerms(array("_option" => "current"));
            $subjects = json_decode($std->subjects ?? '{}');
            $subjectResult = json_decode($std->subject_result ?? '{}');
            $attendance = json_decode($std->attendance ?? '{}');
            $socialBeh = json_decode($std->social_beh ?? '{}');
            $attendance = json_decode($std->attendance ?? '{}');
            $social = null;
            $stdResult = null;
            // var_dump($subjectResult);exit;
            ///////////get subject result////////////////
            foreach($subjects as $k => $v){
                $result = (array)$subjectResult->{$k} ?? '';
                // $finalGrade =  $result['Final Grade'];
                $this->finalScore = $this->finalScore + intval($result['Final Score']);
                $this->numberOfExam++;
                $cwrk = $examRate->class_work > 0 ? '
                <td class="" style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">'. ($result[$examName->first_name]) .'</td>' : '';
                $midTermExm = $examRate->mid_term_exam > 0 ? '
                <td class="" style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">'. ($result[$examName->second_name]) .'</td>' : '';
    
                    $stdResult .= '
                    <tr style="border-bottom:1px solid black;width:160m;">
                        <td style="border-right:1px solid black;border-bottom:1px solid black;padding-left:2px">'. $k. '</td>
                        '.$cwrk.'
                        '.$midTermExm.'
                        <td class="" style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">'. $result[$examName->third_name].'</td>
                        <td class="" style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">'. $result['Final Score'].'</td>
                        <td class="" style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">'. $result['Final Grade']. '</td>
                    </tr>';
                    // echo json_encode(array("message"=>$stdResult));exit;
            }
            foreach($this->socialBehaviour as $k => $social_v){
                $social .= '
                <tr>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;padding-left:4px">'.$social_v->behaviour.'</td>
                    <td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">'.($socialBeh->{$social_v->behaviour} ?? '').'</td>
                </tr>';
    
            }
            // var_dump();exit;
            /////////////average value//////////////////
            $this->average = $this->finalScore / $this->numberOfExam;
            ///////////get average grade//////////////
            foreach($this->examGrade as $k2 => $exmGrade_v){
                $from = $exmGrade_v->percent_from;
                $upto = $exmGrade_v->percent_upto;
                if($this->average >= $from && $this->average <= $upto){
                    $this->finalGrade = $exmGrade_v->grade_name;
    
                 }
    
            }
            // var_dump($this->numberOfExam);exit;
            $html .= '<DOCTYPE html><html>
                <head>
                <meta http-equiv="content-type" content="text/html;charset=UTF-8">
                <style type="text/css">
                    @page { margin: 10px; }
                    body { margin: 20px; font-family: DejaVu Sans, sans-serif; font-size: 9pt;border:1px solid black }
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
                    <table style="width:100%;">
                        <tr> 
                            <td style="border:1px solid black;width:110mm;">
                                <strong style="margin-right:2mm;">Name: </strong><span>'.$std->last_name.' '.$std->first_name.'</span>
                            </td> 
                            <td rowspan="3" style="border-bottom:1px solid black;width:30mm;">
                                <img src="'.$std->logo.'" style="width: 30mm; height: 20mm">
                                <div style="position:absolute;right:8px;bottom:2px;"><strong>Report Sheet</strong></div>
                            </td> 
                            <td style="text-align:right;padding-right:1mm">
                                <span>ISOSIT TECHNICAL INSTITUTE</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid black;width:110mm;">
                                <strong style="margin-right:2mm;">Department: </strong><span>'.$std->cat_name.' '.$std->department.'</span>
                            </td> 
                            <td style="text-align:right;padding-right:1mm">
                                <span>'.$std->branch_address.'</span>
                            </td>
                        </tr>
                        <tr> 
                            <td style="border:1px solid black;width:110mm;">
                                <strong style="margin-right:2mm;">Term: </strong><span>'.$std->term_name.' '.$std->year.'</span>
                            </td>
                            <td style="border-bottom:1px solid black;text-align:right;padding-right:1mm">
                                <span>'.$std->branch_state.' state, '.$std->branch_country.'</span>
                            </td>
                        </tr>
                    </table>
                    
                        <table style="position:relative;left:0px;width:150mm;border-bottom:1px solid black;">
                            <thead>
                                <tr>
                                    <th id="th-exam-rate" class="" style="text-align:left;padding-left:4px;border-right:1px solid black;">
                                        <div style="">KEY TO GRADE</div>
                                        <div>'.$grade.'</div>
                                    </th>
                                    '.$classWork.'
                                    '.$midTermExam.'
                                    <th class="container_flip">
                                        <div> '.$examName->third_name .' </div>
                                        <div>'.$examRate->terminal_exam .'%</div>
                                    </th>
                                    <th class="container_flip">
                                        <div>Final Score </div>
                                        <div>'.($examRate->class_work ?? 0) + ($examRate->mid_term_exam ?? 0) + ($examRate->terminal_exam ?? 0) .'%</div>
                                    </th>
                                    <th class="container_flip">
                                        <div>Final Grade </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border:1px solid black;height:12mm;text-align:center">Subjects</td>
                                    <td style="border:1px solid black;" colspan="'.sizeof($examRateLength) + 2 .'"></td>
                                </tr>
                                '.$stdResult.'
                                <tr>
                                    <td style="border-right:1px solid black;border-bottom:1px solid black;"></td>
                                    <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;" colspan="'.sizeof($examRateLength).'">Average</td>
                                    <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;width:15mm">'.number_format($this->average, 2).'</td>
                                    <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;width:15mm">'.$this->finalGrade.'</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid black;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td colspan="2" style="height:10mm;border-bottom:1px solid black;text-align:center;">ATTENDANCE RECORDS</td>
                                            </tr>
                                            <tr>
                                                <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:left;">Number of days present</td>
                                                <td style="width:15mm;border-bottom:1px solid black;text-align:center;padding-left:2px;">'.$attendance->no_of_days_present.'</td>
                                            </tr>
                                            <tr>
                                                <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:left;">Number of days absent</td>
                                                <td style="width:15mm;border-bottom:1px solid black;text-align:center;padding-left:2px;">'.$attendance->no_of_days_absent.'</td>
                                            </tr>
                                            <tr>
                                                <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:left;">Number of days late</td>
                                                <td style="width:15mm;border-bottom:1px solid black;text-align:center;padding-left:2px;">'.$attendance->no_of_days_late.'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="border-right:1px solid black;" colspan="'.sizeof($examRateLength) + 2 .'"></td>
                                </tr>
                            </tbody>
                        </table>

                        
                        <table style="position:absolute;left:150mm;top:22mm;width:62mm;">
                            <thead>
                                <tr>
                                    <td style="text-align:left;border:1px solid black;padding-left:4px">
                                        SOCIAL BEHAVIOUR
                                    </td>
                                    <td style="text-align:center;border:1px solid black;width:12mm;">
                                        RATE
                                    </td>
                                </tr>
                            
                            </thead>
                            <tr>
                                <td colspan="2" style="border:1px solid black;padding-left:4px;">
                                    '.$rating.'
                                </td>
                            </tr>
                            '.$social.'
                        </table>
                        <table style="position:absolute;left:213mm;top:22mm;width:62mm;">
                            <thead>
                                <tr>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        

                </div></body></html>
            ';
        }

                
        // echo json_encode(array("data"=> "scared"));exit;
        $this->sendPDF(array("html" => $html));
        
        
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
        $this->dompdf->setPaper("A4", "landscape");
        
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