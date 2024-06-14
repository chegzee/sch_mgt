<?php
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
$student = $data['student'] ?? [];
$question = $data['question'] ?? [];
// var_dump($max_key_val);exit;
// echo $data['menu'];
?>

<div class="main-body">
    <style>
        * {
            box-sizing: border-box;
        }
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
        p{
            color:black;
            font-family: "Times, New Roman", Times, serif;
            font-size:20px;
            font-weight:bold;
        }
        table{
            width:100%;
        }
        p{
            width:100%;
        }
        
        .overlay2{
            position:fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            width:100%;
            height: 100%;
            background-color:rgba(0,0,0,0.2);
            z-index:9999;
        }
        .overlaychild{
            margin-top:100px;
            background-color:rgba(0,0,0,0.1);
            height:fit-content;
        }
        .overlaychild2{
            position:relative;
            background-color:white;
            height:450px;
            width:450px;

        }
    </style>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    <!-- /Breadcrumb -->
    <div class="overlay2" style="display:none;">
        <div class="overlaychild">
            <div class="modal-loading text-center py-5 text-white"><i class="fa fa-spinner fa-spin"></i></div>
        </div>
    </div>
    
    <div class="card card-style-1">
        <div class="card-body" style="padding-left:0px;">
            <div class="table-responsive" style="height:700px;">
                <table style="width:100%;">
                    <tr>
                        <td colspan="2">
                            <div>
                                <span>Question <span id="current_question">&nbsp;</span> of <span id="question_length">&nbsp;</span></span>
                                <span>&nbsp;</span><span id="subject_span" style="font-weight:bold;"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td id="question_td" width="50%" style="position:relative;">
                            
                        </td>
                        <td>
                            <form>
                                <div class="row" style="margin-bottom:8px;">
                                    <div class="col-lg-5" style="font-size:18px;font-style:helvetical sansSerif">
                                        <button id="prev-btn" type="button" onclick="nextQuestion(-1)">
                                            <span>Previous question</span>
                                         </button>
                                    </div>
                                    <div class="col-lg-7" style="">
                                        <div style="display:flex;justify-content:space-between">
                                            <div>
                                                <span id="timer_span" style="font-size:24px;font-weight:bold">00:34:09</span>
                                            </div>
                                            <button id="next-btn" type="button" onclick="nextQuestion(1)">
                                                <span>Next</span>
                                            </button>
                                            <button id="submit-btn" type="button" onclick="submitQuestion()" style="display: none">
                                                <span>Submit Question</span>
                                            </button>
                                        </div>
                                    </div> 
                                </div>
                                <div id="div_answer">
                                </div>
                            </form> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="ant-alert-content"><div class="ant-alert-message">Quick Note</div><div class="ant-alert-description">If the question is same as the last one or showing blank content, please click "Reload Question"</div></div>
                            <div class="ant-row" style="row-gap: 0px;"><div class="ant-col"><br><button class="turing-button secondary"><span class="btn-text">Reload question</span></button>&nbsp;<button class="turing-button secondary" style="margin: 0px 4px;"><span class="btn-text">Report a problem</span></button></div></div>
                        </td>
                    </tr>
                </table>

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
    let student = <?php echo json_encode($data['student']) ?>;
    let question = <?php echo json_encode($data['question']) ?>;
    let question_meta = <?php echo json_encode($data['question_meta']) ?>;
    let tableStudents = null;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    let sch_question = JSON.parse(question);
    // console.log(student);

    let optionsButton = (e)=>{
        let elem = e.currentTarget.closest('div');
        let ccc = $(elem).children();
        $.each(ccc, (k, v)=>{
            let div = $($(v).children("div")).children("div")["0"]; 
            div.setAttribute("stdans", "0");
            // console.log(div)
        })
        // console.log(ccc)
        // console.log( )
        $($($($(elem).find("button")).css("border", "")).find("h1")).css("color", "");
        $($($(e.currentTarget).css("border", "2px solid blue")).find("h1")).css("color", "blue");
        let rrr = $(e.currentTarget).children("div").children("div")["0"];
        rrr.setAttribute("stdans", "1")
        // console.log(rrr);
    }
    /////////////////////////////////////////////////
    let n = 0;
    let div = $('#question_td').prepend(decodeURI(sch_question[n].question));
    let totalQuestion = sch_question.length ;
    // console.log(decodeURI(sch_question[1].a))
    let nextQuestion = (v)=>{

        let q = encodeURI($("#question")["0"].outerHTML);
        let a = $("#div_answer").children();
        let o = {question: q};

        if(a[0] !== undefined){
            let answer = encodeURI($($(a[0]).children("div")).children("div")["0"].outerHTML);
            o["a"] = answer;
        }

        if(a[1] !== undefined){
            let answer = encodeURI($($(a[1]).children("div")).children("div")["0"].outerHTML);
            o["b"] = answer;
        }

        if(a[2] !== undefined){
            let answer =encodeURI($($(a[2]).children("div")).children("div")["0"].outerHTML);
            o["c"] = answer;
        }

        if(a[3] !== undefined){
            let answer = encodeURI($($(a[3]).children("div")).children("div")["0"].outerHTML);
            o["d"] = answer;
        }

        if(a[4] !== undefined){
            let answer = encodeURI($($(a[4]).children("div")).children("div")["0"].outerHTML);
            o["e"] = answer;
        }

        sch_question[n] = o;

        ////////////////////////////////////////////////
        let res = n + v
        n = res;
        if(n === totalQuestion ) {
            // n = totalQuestion - 1
            $("#next-btn").css("display", "none");
            $("#submit-btn").css("display", "block");
            return;
        }

         if(n <= 0){
            // n = 0
            $("#prev-btn").css("display", "none");
            $("#next-btn").css("display", "block");
            $("#submit-btn").css("display", "none");

        }else if(n >= 0){
            $("#prev-btn").css("display", "block");
            $("#next-btn").css("display", "block");
            $("#submit-btn").css("display", "none");

        }

        $('#question_td').html('');
        $('#question_td').prepend(decodeURI(sch_question[n].question));
        $("#current_question").html(n + 1);
        $("#div_answer").html("");
        
        if(sch_question[n].a !== undefined){
            let a = '\
                    <button value="A" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%;">\
                            '+decodeURI(sch_question[n].a)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">A</h1>\
                    </button>';
            $("#div_answer").append(a)
        }

        if(sch_question[n].b !== undefined){
            let b = '\
                    <button value="B" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%;">\
                            '+decodeURI(sch_question[n].b)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">B</h1>\
                    </button>';
            $("#div_answer").append(b)
        }

        if(sch_question[n].c !== undefined){
            let c = '\
                    <button value="C" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%">\
                            '+decodeURI(sch_question[n].c)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">C</h1>\
                    </button>';
            $("#div_answer").append(c)
        }

        if(sch_question[n].d !== undefined){
            let d = '\
                    <button value="D" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%">\
                            '+decodeURI(sch_question[n].d)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">D</h1>\
                    </button>';
            $("#div_answer").append(d)
        }

        if(sch_question[n].e !== undefined){
            let e = '\
                    <button value="D" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%">\
                            '+decodeURI(sch_question[n].e)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">D</h1>\
                    </button>';
            $("#div_answer").append(e)
        }
                // console.log(ans)
    }

    let submitQuestion = ()=>{
        $(".overlay2").css("display", "block");
        let score = 0;
        let std_name = student.last_name + " "+ student.first_name;
        const search =  new URLSearchParams(location.search);
        let questionMetaData = {
            posted_date: question_meta.posted_date,
            subject_name: question_meta.subject_name,
            exam_name: question_meta.exam_name,
            student_name: std_name,
            question_code: question_meta.code,
            total_question: totalQuestion,
            std_code: search.get("stdcode")
            }
        // console.log(questionMetaData);return;
        $.each(sch_question, (k, v)=>{
            let a = $(decodeURI(v.a ?? '<div></div>'))["0"];
            let b = $(decodeURI(v.b ?? '<div></div>'))["0"];
            let c = $(decodeURI(v.c ?? '<div></div>'))["0"];
            let d = $(decodeURI(v.d ?? '<div></div>'))["0"];
            let e = $(decodeURI(v.e ?? '<div></div>'))["0"];
            //////////////////////////////////////////////////
            if((a.getAttribute("ans") ?? '0') === "1"){
                 score += parseInt((a.getAttribute("stdans")?? '') === "" ? 0 : a.getAttribute("stdans"));
            }
            else if((b.getAttribute("ans") ?? '0') === "1"){
                 score += parseInt((b.getAttribute("stdans")?? '') === "" ? 0 : b.getAttribute("stdans"));

            }else if((c.getAttribute("ans")?? "0" )=== "1"){
                 score += parseInt((c.getAttribute("stdans")?? '') === "" ? 0 : c.getAttribute("stdans"));

            }else if((d.getAttribute("ans")?? "0" )=== "1"){
                 score += parseInt((d.getAttribute("stdans")?? '') === "" ? 0 : d.getAttribute("stdans"));

            }else if((e.getAttribute("ans")?? "0" )=== "1"){
                 score += parseInt((e.getAttribute("stdans")?? '') === "" ? 0 : e.getAttribute("stdans"));
            }else{
                score += 1
            }

        })

        questionMetaData["scores"] = score;
            // console.log(questionMetaData);return;
        //
        let url = "<?php echo URL_ROOT ?>/school/questionAnswer/_save/?user_log=<?php echo $data["params"]["user_log"] ?> ";
        // console.log(url)
        $.post(url, questionMetaData, (data)=>{
            if(!data.status){
             new Noty({type: "warning", text: "<h5>WARNING</h5>"+ data.message, timeout: 10000}).show();
                return;
            }
             new Noty({type: "success", text: "<h5>SUCCESS</h5>"+ data.message, timeout: 10000}).show();
             setTimeout(()=>{
                $(".overlay2").css("display", "block");
                parent.location.assign("<?php echo URL_ROOT ?>/school/schQuestionLink/?user_log=<?php echo $data["params"]["user_log"] ?> ")
             }, 2000)
            // console.log(data);
        }, 'JSON')
        // console.log(questionMetaData)
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        $("#question_length").html(totalQuestion);
        $("#current_question").html(n + 1);
        $("#subject_span").html(question_meta.subject_name)

        // console.log(decodeURI(sch_question[n].a))
        if(sch_question[n].a !== undefined){

            let a = '\
                    <button value="A" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%;">\
                            '+decodeURI(sch_question[n].a)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">A</h1>\
                    </button>';
            $("#div_answer").append(a)
        }

        let b = '\
                <button value="B" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                    <div style="height:fit-content;width:100%;">\
                        '+decodeURI(sch_question[n].b)+'\
                    </div>\
                    <h1 style="height:fit-content;margin-bottom:0px">B</h1>\
                </button>';

        $("#div_answer").append(b)

        if(sch_question[n].c !== undefined){
            let c = '\
                    <button value="C" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%">\
                            '+decodeURI(sch_question[n].c)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">C</h1>\
                    </button>';
            $("#div_answer").append(c)
        }

        if(sch_question[n].d !== undefined){
            let d = '\
                    <button value="D" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                        <div style="height:fit-content;width:100%">\
                            '+decodeURI(sch_question[n].d)+'\
                        </div>\
                        <h1 style="height:fit-content;margin-bottom:0px">D</h1>\
                    </button>';
            $("#div_answer").append(d)
        }

        ///
        if(sch_question[n].e !== undefined){
            let d = '\
            <button value="D" type="button" style="display:flex;justify-content:space-between;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:18px;overflow:hidden" onclick="optionsButton(event)">\
                <div style="height:fit-content;width:100%">\
                    '+decodeURI(sch_question[n].d)+'\
                </div>\
                <h1 style="height:fit-content;margin-bottom:0px">D</h1>\
            </button>';
            $("#div_answer").append(d);

        }

        
        let checkForm = new timer();
        checkForm.start(function () {
            let answers = $("#div_answer").children();
            // console.log(answers)
            $.each(answers, (k, v)=>{
                let ck = $($($(v).children("div")).children("div"))["0"].getAttribute("stdans");
                let h1 = $(v).children("h1")["0"];
                if(ck === "1"){
                    $(h1).css("color", "blue");
                }
                // console.log(h1)
            })

        }, 100, true)
        //////////////////////////////////////////////
        let checkQuestion = new timer();
        var timer_number = 0;
        checkQuestion.start(function () {
            timer_number++;
            let sec = timer_number % 60;
            let min = Math.floor(timer_number / 60) % 60;
            let hr = Math.floor(timer_number / (60 * 60)) % 24;
            let time =  (hr < 10 ? "0" + hr : hr) + ":" +  (min < 10 ? "0" + min : min) + ":" + (sec < 10 ? "0" + sec : sec);
            $("#timer_span").html(time);
            if(parseInt(question_meta.exam_timer) < timer_number){
                submitQuestion();
            }
            
            // console.log(time)

        },1000, true)
    
    });

</script>