<?php

function get_time_ago( $time )
{
    $time_difference = time() - $time;
    // echo($time);

    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );
    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;

        if( $d >= 1 )
        {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}
$data = $data ?? [];
$term = $data['term'] ?? [];
$termObj = $data['termObj'] ?? [];
$examGrade = $data['examGrade'] ?? [];
$examRate = $data['examRate'] ?? [];
$socialBehaviour = $data['socialBehaviour'] ?? [];
$socialKey = $data['socialKey'] ?? [];
$max_key_val = $data['max_key_val'] ?? [];
$max_percent_upto = $data['max_percent_upto'] ?? [];
$classrooms = $data['classrooms'] ?? [];
$classroomsObj = $data['classroomsObj'] ?? [];
$question = $data['question'] ?? [];
$student = (object)$data['user'];
// var_dump($question);exit;

$std_onlineQuestion = json_decode($student->online_question);
$std_onlineQuestionObj = (object)array();
foreach($std_onlineQuestion as $k => $v){
    $std_onlineQuestionObj->{$v->question_code} = $v;
}
// var_dump($std_onlineQuestionObj);exit;
echo $data['menu'];
?>

<div class="main-body">
    <style>
        .container_flip{
            position:relative;
            border:1px solid black;
            height:100%;
            width:51px;
            text-align:center;
        }
        .rotate{
            position:absolute;
            bottom:15px;
            writing-mode: vertical-lr;
        }
        .flipvertical{
            font-size:18px;
            font-weight: bold;
            transform: rotate(180deg);
        }
        select {
            position: relative;
            display: flex;
            flex-direction: column;
            border-width: 2px 2px 2px 2px;
            border-style: solid;
            border-color: #394a6d;
            padding-left: 2px;
            padding-right: 2px;
        }
        .custom-option {
            position: relative;
            display: block;
            padding: 0 22px 0 22px;
            font-size: 22px;
            font-weight: 300;
            color: #3b3b3b;
            line-height: 60px;
            cursor: pointer;
            transition: all 0.5s;
        }
        td{
            padding-left: 4px;
            padding-right: 4px;
        }
        .overlay2{
            position:fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            width:100%;
            height: 100%;
            background-color:rgba(0,0,0,0.5);
            z-index:100;
        }
        .overlaychild{
            margin-top:100px;
            background-color:rgba(0,0,0,0.2);
            height:fit-content;
        }
        .overlaychild2{
            position:relative;
            background-color:white;
            height:450px;
            width:450px;

        }
        /* @keyframes example{
            from {height:450px;}
            to{height:0px;}
        } */
    </style>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    <div class="overlay2" style="display:none;">
        <div class="overlaychild">
            <div class="overlaychild2">
                <h2 style="position:absolute;top:40px;width:100%;text-align:center;">You about to answer this question ?</h2>
                <div style="position:absolute;top:220px;width:100%;text-align:center">
                    <button onclick="openQuestion()">YES</button>
                    <button onclick="closeDialog()">NO</button> 
                </div>   
            </div>
        </div>
    </div>
    
    <div class="card card-style-1">
        <div class="card-body" style="padding-left:0px;">
            <div class="row">
                <?php
                    foreach($question as $v){
                        $done = '';
                        $lock = '';
                        if(!empty($std_onlineQuestionObj->{$v->code} )){
                            $done = "COMPLETED";
                            $lock = "hidden";
                        }
                        echo ' 
                        <div class="col-lg-3" style="position:relative;height:200px;background-color:#ccc;margin:2px">
                            <h6 style="position:absolute;top:50px;">'.$v->subject.'</h6>
                            <h6 style="position:absolute;top:80px;font-weight:bold;">'.$v->exam_name.'</h6>
                            <h6 style="position:absolute;top:100px;font-weight:bold;color:green;">'.$done .'</h6>
                            <span style="font-weight:bold;">'.get_time_ago(strtotime($v->posted_date)).'</span>
                            <div style="position:absolute;top:150px;left:0px;background-color:#042954;border-radius:8px;width:100%;text-align:center;"><a style="color:white;visibility:'.$lock.'" href="javascript:void(0)" onclick=questionLink('.json_encode($v->code).')>question</a></div>
                            <div  style="position:absolute;top:90px;left:50px"></div>
                        </div>';

                    }
                ?>
            </div>
            
        </div>
    </div>

</div>



<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    let examGrade_ = <?php echo json_encode($data['examGrade']) ?>;
    let socialBehaviour_ = <?php echo json_encode($data['socialBehaviour']) ?>;
    let examRate_ = <?php echo json_encode($data['examRate']) ?>;
    let max_key_val_ = <?php echo json_encode($data['max_key_val']) ?>;
    let max_percent_upto_ = <?php echo json_encode($data['max_percent_upto']) ?>;
    let levels = <?php echo json_encode($data['levelsobj']) ?>;
    let classes = <?php echo json_encode($data['classesobj']) ?>;
    let term = <?php echo json_encode($data['term']) ?>;
    let termObj = <?php echo json_encode($data['termObj']) ?>;
    let classrooms = <?php echo json_encode($data['classrooms']) ?>;
    let classesobj = <?php echo json_encode($data['classesobj']) ?>;
    let question = <?php echo json_encode($data['question']) ?>;
    let student = <?php echo json_encode($data['user']) ?>;
    let params = <?php echo json_encode($data['params']["user_log"]) ?>;
    let tableStudents = null;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    // console.log(student);
    // console.log(student[0].online_question);
    let url = '';

    let questionLink = (e)=>{

        ///////////////////////////////
        url ="<?php echo URL_ROOT ?>/school/questionAnswer/?q_code="+e+"&user_log="+params+" &stdcode="+student.std_code +"";
        let left =Math.abs((window.innerWidth - 700)/2);
        $('.overlaychild2').css("margin-left", left);
        $('.overlay2').show();
        
    }

    ///////////////////////////////////
    let openQuestion = (e)=>{
        parent.location.assign(url);
    }

    //////////////////////////////////
    let closeDialog = (e)=>{
        $('.overlay2').hide(500)

    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });  
    
    });

</script>