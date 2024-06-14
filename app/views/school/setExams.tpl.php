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
// var_dump($examGrade);exit;
echo $data['menu'];
?>
<style>
        * {
            box-sizing: border-box;
        }
        th{
            border-bottom:2px solid elelel;
            border-top:none;
            font-size:16px;
            font-weight:500;
            color:#fff;
            padding:14px 15px;
            background-color:#042954;

        }
        p, h1, h2, h3,h4,h5,h6 {
            margin:0
        }
        .ck-body-wrapper{
            position:fixed;
            z-index: 1034;
        }
        .my-align-left{
            text-align: left
        }
        .my-align-right{
            text-align: right
        }
        table{
            width:100%;
        }
    </style>

<div class="main-body">
    
    
    <div class="card card-style-1">
       
        <div class="card-body" style="padding-left:0px;">
            <div class="table-responsive" style="min-height:200px;">
                <button  style="text-align:left;margin-bottom:2px;" onclick="resetQuestion(event)"><i class="fa fa-refresh"></i><span> Reset</span></button>
                <button  style="text-align:left;margin-bottom:2px;" onclick="setQuestion(event)"><i class="fa fa-plus"></i><span> Set Question</span></button>
                <button id="question-code" style="padding:4px;background-color:#ccc;border:2px solid black;color:black;font-weight:bold;">question code</button>
                <div style="text-align:right;margin-bottom:2px;"><button onclick="addOption(event)">Add option</button></div>  
                <div id="presence-list-container" style="display:none"></div>
                <div id="outline" class="document-outline-container" style="display:none"></div>
                <table id="table-setQuestion" style="width:100%;">
                    <tr>
                        <td width="40%" style="position:relative;min-height:100px;">
                            <div id="question" name="question" style="position:absolute;top:0px;font-size:18px;padding-left:8px;border:1px solid black;min-width:100%;height:100%;font-weight:bold;margin-top:0px">
                            </div>
                        </td>
                        <td id="answers" width="40%">
                            <div value="A" style="border:1px solid black;line-height:2rem">
                                 <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">A</span>
                            </div>
                            <div value="B" style="border:1px solid black;line-height:2rem">
                                 <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">B</span>
                            </div>
                            <div value="C" style="border:1px solid black;line-height:2rem">
                                 <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">C</span>
                            </div>
                            <div value="D" style="border:1px solid black;line-height:2rem" >
                                 <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">D</span>
                            </div>
                        </td> 
                    </tr>
                </table>
            </div>
            
            <div class="table-responsive">
                <div>
                    <button id="exam-post-btn" style="font-size:18px;" onclick="examPost(event)">Post your question  <i class="fa fa-signs-post"></i></button>
                </div>
                <div>
                    <table id="table-display-question" style="width:100%">
                        <thead >
                            <th style="width:5%"><i class="material-icons">build</i></th>
                            <th style="width:5%;">S/N</th>
                            <th style="width:40%;">Questions</th>
                            <th style="width:40%;">Answers</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <dialog id="exampost-dialog" style="position:absolute;top:20%;left:230px;min-height:70%;width:80%;margin-left:auto;background-color:#ccc;text-align:center;z-index:999;">  
            
        <div style='width:100%;text-align:right;margin-bottom:24px;'><span style='font-size:18px;cursor:pointer;' onclick="document.getElementById('exampost-dialog').close()">x</span></div>
        <div class="row">
            <div class="col-lg-4">
                <label style="width:100%;font-size:18px;font-weight:bold">Select Classes</label>
                <select id="class_code" style="width:100%;" multiple>
                </select>
                <code class="small text-danger" id="level--help">&nbsp;</code>
            </div>
            <div class="col-lg-3">
                <label for="subject" style="width:100%;font-size:18px;font-weight:bold">Subject </label>
                <select id="subject" style="width:100%;height:33px;padding-left:8px">
                    <option value="" selected disabled>Select Subject</option>
                </select>
                <code class="small text-danger" id="subject--help">&nbsp;</code>
            </div>
            <div class="col-lg-3">
                <label for="exam_name" style="width:100%;font-size:18px;font-weight:bold">Exam Name </label>
                <select id="exam_name" style="width:100%;height:33px;padding-left:8px">
                    <option value="" selected disabled>Select an option</option>
                </select>
                <code class="small text-danger" id="exam_name--help">&nbsp;</code>
            </div>
            <div class="col-lg-2">
                <label for="exam_name" style="width:100%;font-size:18px;font-weight:bold">PERIOD </label>
                <select id="exam_timer" style="width:100%;height:33px;padding-left:8px">
                    <option value="" selected disabled>Select an option</option>
                </select>
                <code class="small text-danger" id="exam_timer--help">&nbsp;</code>
            </div>

            <!-- <div class="col-lg-3">
                <label style="width:100%;font-size:18px;font-weight:bold">Question Type</label>
                <select id="questionType" style="width:100%;height:33px;padding-left:8px">
                    <option value="" hidden selected>Select question Type</option>
                    <option class="custom-option">OBJECTIVE</option>
                    <option class="custom-option">THEORY</option>
                    <option class="custom-option"></option>
                </select>
                <code class="small text-danger" id="examType--help">&nbsp;</code>
            </div>   -->
        </div>
        <div class="row">
            <div class="col-lg-6"><button onclick='examPost({"level":$("#class_code")["0"].selectedOptions, action: "post", exam_name: $("#exam_name")["0"].selectedOptions["0"], subject: $("#subject")["0"].selectedOptions["0"], exam_timer: $("#exam_timer")["0"].selectedOptions["0"], code: $("#question-code").html()})'>Submit</button></div>
            <div class="col-lg-6"><button  onclick="document.getElementById('exampost-dialog').close()">Close</button></div>
        </div>

    </dialog>  
        
    <!-- <dialog id="myFirstDialog" style="position:absolute;top:20%;min-height:70%;width:80%;margin-left:auto;background-color:#cfcfcf;border:1px dotted black;text-align:center;z-index:999;">  
        <div style='display:flex;justify-content:space-between;width:100%;text-align:right;margin-bottom:24px;'><button onclick="updateAnswers(event)"><i class="fa fa-save"></i></button><span style='font-size:18px;cursor:pointer;' onclick="refreshdialog(event)">x</span></div>
    </dialog>  -->
<!-- </div> -->



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
    // let classrooms = <?php echo json_encode($data['classrooms']) ?>;
    let classesObj = <?php echo json_encode($data['classesobj']) ?>;
    let student = <?php echo json_encode($data['student']) ?>;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    let rowIndex_ = 0;
    // let questions = [];
    // console.log(term);

    let optionsButton = (e)=>{
        let elem = e.currentTarget.closest('div');
        $($($($(elem).find("button")).css("border", "")).find("h1")).css("color", "");
        $($($(e.currentTarget).css("border", "2px solid blue")).find("h1")).css("color", "blue")
        // console.log($(elem).find('button'));
    }
    // let divelem = '';

    let addOption = (e)=>{
        let alpha_Letter = ["A", "B", "C", "D", "E"];
       let div_coll = $('#table-setQuestion > tbody > tr').find('td:eq(1)')["0"].children;
       let last_div = div_coll[div_coll.length-1];
       let lastletter = last_div.getAttribute("value");
        let last_letter = alpha_Letter.indexOf(lastletter);
        let nextLetter = alpha_Letter[last_letter+1]
        if(nextLetter === undefined) return;

        let html = '\
        <div value="'+nextLetter+'" style="border:1px solid black;line-height:2rem">\
        <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">'+nextLetter+'</span>\
         </div>\
         ';
        $('#answers').append(html);
       let answers = [...document.querySelector("#answers").children];
        $.each(answers, (k, v)=>{
            // console.log(v)
            InlineEditor.create(v)
                .catch(error =>{
                    console.log(error)
                });
        });

    }

    let removeAnswer = (e)=>{
        console.log(e);return;
        let elem = $(e.currentTarget).remove()
        let alpha_Letter = ["A", "B", "C", "D", "E", "F", "G", "H"];
       let elements = $($('#table-setQuestion').find("tr")).find('td:eq(1)')["0"].children;
       $.each(elements, (k, v)=>{
        // $($(v).find('div')).setA(alpha_Letter[k])
        v.setAttribute("value", alpha_Letter[k])
        // console.log(k, v)
       })
    }

    let resetQuestion = (e)=>{
        let domEditableElement = document.querySelector('.ck-editor__editable');
        const editorInstance = domEditableElement.ckeditorInstance;
        editorInstance.setData('');
        let html = '<div value="A" style="border:1px solid black;line-height:2rem">\
         <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">A</span>\
        </div>\
        <div value="B" style="border:1px solid black;line-height:2rem">\
         <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">B</span>\
        </div>\
        <div value="C" style="border:1px solid black;line-height:2rem">\
         <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">C</span>\
        </div>\
        <div value="D" style="border:1px solid black;line-height:2rem">\
         <span style="position:absolute;bottom:2px;right:12px;font-size:18px;font-weight:bold">D</span>\
        </div>\
        ';
        $('#answers').html('')
        $('#answers').append(html);
       
        let answers = [...document.querySelector("#answers").children];
        $.each(answers, (k, v)=>{
            // console.log(v)
            InlineEditor.create(v)
                .catch(error =>{
                    console.log(error)
                });
        });

    }
    
    function setCorrectAnswer(e){
        let elem = e.currentTarget;
        let td = $(elem).closest('td');
        let elements = $(td).children();
        // console.log(td)
        $.each(elements, (k, v)=>{
            $(v).css('border', '1px solid black');
            $(v).attr('ans', '');
            // console.log(v);
        })
        $(elem).css('border', '2px solid red');
        $(elem).attr('ans', '1')
    }

    let setQuestion = ()=>{
        let elements = document.querySelector('#table-setQuestion > tbody > tr').children[1].innerHTML;
       let div_container = $($('#table-setQuestion > tbody > tr').children()["0"]).children()["0"].outerHTML
       let cont = String(div_container).substring(0, String(div_container).indexOf('>') + 1);
       let question = cont;
       let _question = [...$('#table-setQuestion ').find("tr").find('td:eq(0)')["0"].children[[0]].children];
         //    console.log(_question);
        // let p = '<p>';
        _question.forEach((v, k)=>{
            if(v.tagName === 'P'){
                // console.log(v);
                // let nodes = [...v.childNodes];
                // console.log(nodes.length);
                // if(len === 1){
                //     // console.log("scared")
                //     question += v.outerHTML
                //     return;
                // }
                // nodes.forEach((v, k)=>{
                //     let img = $(v).find('img');
                //     let i = $(v).text();
                //     console.log(i)
                //     if(img.length > 0){
                //         p += img[0].outerHTML;
                //     }
                //     if(i !== ''){
                //         p += i;
                //     }
                // })
                // p += '</p>';
                question += v.outerHTML;
                
            }
            // console.log(p);
            // return;
            v.removeAttribute('class');
            if(v.tagName === 'FIGURE'){
                v.removeAttribute('class');
                let str = String(v.outerHTML).substring(0, String(v.outerHTML).indexOf('>') + 1);
                // console.log(v);
                [...v.children].forEach((v, k)=>{
                    if(v.tagName === 'TABLE' || v.tagName === 'IMG'){
                        question += str + v.outerHTML + '</figure>';
                    }

                })
                return;
            }
            // question += v.outerHTML
            
        })
            question += '</div>';
        // console.log(question);return;
       let len = $('#table-display-question > tbody').children().length + 1;
        //    console.log(tbody_);return;
       let html = '<tr style="border:solid black 1px"">\
            <td>\
                <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">\
                    <i class="fa fa-cog"></i>\
                </a>\
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">\
                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red">\
                    </i>Close</a>\
                    <a class="dropdown-item" href="#" onclick="editQuestion(event)"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit Answer</a>\
                    <a class="dropdown-item" href="#" onclick="removeRow({elem: event})"><i class="fas fa-trash text-orange-peel"></i>Remove</a>\
                </div>\
            </td>\
            <td style="font-size:18px;font-weight:bolder;">'+ len +'</td>\
            <td style="position:relative;">'+ question +'</td>\
            <td>'+ elements +'</td>\
        </tr>';
       let tbody = $('#table-display-question > tbody');
       tbody.append(html);
       let div_quest = $('#table-display-question > tbody > tr:eq('+(len - 1)+') > td:eq(2)').children()["0"];
       div_quest.removeAttribute('aria-label');
       div_quest.removeAttribute('dir');
       div_quest.removeAttribute('lang');
       div_quest.removeAttribute('role');
       div_quest.removeAttribute('contentEditable');
       div_quest.removeAttribute('class');
       div_quest.setAttribute('class', 'ck ck-editor__editable ck-editor__editable_inline')
        //    console.log(div_quest)
       let div_answer = $('#table-display-question > tbody > tr:eq('+(len - 1)+') > td:eq(3)').children();
       $.each(div_answer, (k, v)=>{
            v.ondblclick = setCorrectAnswer
            v.removeAttribute('aria-label');
            v.removeAttribute('dir');
            v.removeAttribute('lang');
            v.removeAttribute('role');
            v.removeAttribute('contentEditable');
            v.removeAttribute('class');
            v.setAttribute('class', 'ck ck-editor__editable ck-editor__editable_inline')
            //    console.log(v);
       })
        resetQuestion();
    }

    let examPost = (e)=>{
        if(e.action === "post"){
            let list = [...e.level];
            let classes = {};
            list.forEach((v, k)=>{
                let ccc = v.value;
                classes[ccc] = 1;
            })
            let sch_question = {};
            let subject = e.subject.value ?? '';
            let exam_name = e.exam_name.value ?? '';
            let exam_timer = e.exam_timer.value ?? '';
            if(subject === '' || exam_name === '' || exam_timer === ''){
                new Noty({type: "warning", text: "<h5>WARNING</h5>Invalid input field", timeout:10000}).show();
                return;
            }
            sch_question["level_list"] = JSON.stringify(classes);
            sch_question['subject'] = subject;
            sch_question['exam_name'] = exam_name;
            sch_question['exam_timer'] = exam_timer;
            sch_question['term_code'] = term.code;
            sch_question['code'] = ((e.code === 'question code') ? '' : e.code);
            sch_question['term'] = term.term;
            sch_question['year'] = term.year;
            let rows = [];
            // //
            let elements = [...$('#table-display-question > tbody > tr')];
            $.each(elements, (k, v)=>{
                let q = $(v).children()["2"].innerHTML;
                let options = [...$($(v).children()["3"]).children()];
                let row =  {};
                row["question"] = encodeURI(q).replace(/[()]/g, '');
                options.forEach((v, k)=>{
                    let key = String(v.getAttribute("value")).toLowerCase();
                    $(v).css("border", "");
                    row[key] = encodeURI(v.outerHTML);
        
                })
                rows.push(row)
                    
            })
            sch_question['questions'] = JSON.stringify(rows); 
            // return;
            $.post('<?php echo URL_ROOT ?>/school/setExams/_save/?user_log=<?php echo $data['params']['user_log'] ?>', {data: sch_question, test: "scared"}, (data)=>{
                // console.log(data.data);
                $('#question-code').html(data.code);
                if(!data.status){
                    new Noty({type: "warning", text: "<h5>WARNING</h5>"+ data.message, timeout: 10000}).show();
                    return false;
                }
                
                new Noty({type: "success", text: "<h5>SUCCESS</h5>"+ data.message, timeout: 10000}).show();
            }, 'JSON')
            // return;
        }
        let row_number = $('#table-display-question tbody').find('tr').length
        if(row_number === 0)return;
        document.getElementById('exampost-dialog').show();
        $('#examName').val('').trigger('change')
        $('#questionType').val('').trigger('change')
        $('#level').val('').trigger('change')   
    }
        ////
    $('#subject').select2({
        allowClear: true,
        // placeholder: "select an option",
        ajax: {
            url: "<?php echo URL_ROOT ?>/system/systemSetting/getSubjectType/?user_log=<?php echo $data['params']['user_log'] ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term,
                    _option: 'select'
                };
            },
            processResults: function (response) {
                // console.log(response);
                return { results: response };
            },
            cache: true
        }
    });
    //
    $("#exam_name").select2({
        data: [
            {id:"TEST", text: "TEST"},
            {id:"CLASS WORK", text: "CLASS WORK"},
            {id:"MID TERM EXAM", text: "MID TERM EXAM"},
            {id:"TERMINAL EXAM", text: "TERMINAL EXAM"},
        ]
    })
    //
    $("#exam_timer").select2({
        data:[
            {id: "900", text: "15 MINS"},
            {id:"1800", text: "30 MINS"},
            {id:"2700", text: "45 MINS"},
            {id:"3600", text: "1 HOUR"},
            {id:"4800", text: "1 HOUR 30 MINS"},
            {id:"6300", text: "1 HOUR 45 MINS"},
            {id:"7200", text: "1 HOUR"}
        ]
    })
    
    //populate the class rooms
    let resetClassRoom = ()=>{
        let level = $('#class_code');
        $($(level).find('option')).remove();
        level.append('<option value="" hidden selected>Select class name</option>')
        $.each(classesObj, (k, vv)=>{
            let html = '<option class="custom-option" value="'+vv.class_code+'">'+vv.cat_name+'-'+vv.class_name+'</option>';
            $(level).append(html);

        })

    }
    let updateAnswers = (e)=>{
        let dialog = [...$("#myFirstDialog")["0"].children];
        dialog.shift()
        let answers = {};
        $.each(dialog, (k, v)=>{
            let answer_label = String($($(v).find('div')).html()).toLowerCase();
            let answer_content = String($(v)["0"].childNodes[0].data).trim();
            answers[answer_label] = answer_content
            
        })
        let fff = '';
       ($.each(answers, (k , v)=>{ fff +='<option class="custom-option" data-label='+k+'>'+v+'</option>' }));
       let table_display_question = $('#table-display-question');
         //    console.log(table_set_question["0"].children.length);
       let html = '<select style="width:100%;height:33px">'+fff+'</select>';
       let ddd = $(table_display_question).find('tr:eq('+rowIndex_+')').find('td:eq(3)');
       $(ddd).find('select').remove();
       $(ddd).append(html);
       refreshdialog({});

        // console.log(ddd);
    }

    let refreshdialog = (e)=>{
        let elem = $($("dialog").find("div:not(:first-child)")).remove();
        document.getElementById('myFirstDialog').close();
    }
  
    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");
        $("#"+table + '> tbody > tr:eq(\''+ row_index +'\')').remove();
        let table_rows = $("#"+table + '> tbody > tr');
        // console.log(table_rows);return
        $.each(table_rows, (k, v)=>{
            let td = $(v).find('td:eq(1)')
            $(td).html(k + 1)
        })
    }

    let resetExamName = ()=>{
        let html = '<option value="" selected hidden>Select Exam Type</option>\
                        <option class="custom-option">MID TERM EXAM</option>\
                        <option class="custom-option">TERMINAL EXAM</option>\
                        <option class="custom-option"></option>';
        let examType = $('#examName');
        $($(examType).find('option')).remove();
        $(examType).append(html)


    }
    //
    let resetQuestionType = ()=>{
        let html = '<option value="" hidden selected>Select Question Type</option>\
                        <option class="custom-option">OBJECTIVE</option>\
                        <option class="custom-option">THEORY</option>';
        let examName = $('#questionType');
        $($(examName).find('option')).remove();
        $(examName).append(html)
    }

    // let editQuestion = (e)=>{
    //     let tr = $(e.target).closest('tr');
    //     rowIndex_ = $(tr)["0"].rowIndex
    //     // console.log(rowIndex_)
    //     let td = $(tr).find('td:eq(3)');
    //     let select = [...$($(td).find('select'))["0"].children];
    //     let dialog = document.getElementById('myFirstDialog');
    //     $.each(select, (k, v)=>{
    //         let vv = $(v).val();
    //         let answer_label = String($(v)["0"].dataset.label).toUpperCase();
    //         // console.log(answer_label)
    //         let html = '\
    //         <div style="position:relative;width:100%;background-color:#fcfcfc;margin-bottom:4px;padding:4px;font-size:18px;border:1px solid black;overflow:hidden;font-weight:bold;" contentEditable="true">\
    //             '+vv+'\
    //             <div style="position:absolute;bottom:2px;right:12px;font-size:24px;font-weight:bold" contentEditable="false">'+answer_label+'</div>\
    //         </div>\
    //         ';
    //         $('#myFirstDialog').append(html)
    //     })
    //     dialog.show();
    //     // console.log(select);
        
    //     // if(gg.length < 1) {
    //         // var dialog = document.getElementById('myFirstDialog');    
    //         // dialog.show(); 
    //     // }
    // }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        resetClassRoom();

        //
        InlineEditor.create(document.querySelector("#question"), {
            extraAllowedContent: "style;*(*)",
            // plugins:['MathType'],
            // toolbar: {
            //     items: [
            //         'MathType',
            //         'ChemType',
            //     ]
            // },
            // cloudServices: {
            //     // Be careful - do not use the development token endpoint on production systems!
            //     tokenUrl: 'https://104267.cke-cs.com/token/dev/lN5xJwHJFEUutVoIWrGWWz6K1cjj93x9y32V?limit=10',
            //     webSocketUrl: 'wss://104267.cke-cs.com/ws',
            //     uploadUrl: 'https://104267.cke-cs.com/easyimage/upload/'
            //  }
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            }
            // language: 'en',
            //     // MathType Parameters
            //  mathTypeParameters : {
            //     serviceProviderProperties : {
            //         URI : '/sch_mgt/php-services/wiris/integration/',
            //         server : 'php'
            //     }
            // }

        })
        .catch(error =>{
            console.log(error);
        });

       let answers = [...document.querySelector("#answers").children];
        $.each(answers, (k, v)=>{
            // console.log(v)
            InlineEditor.create(v)
                .catch(error =>{
                    console.log(error)
                });
        });
       
         let checkForm = new timer();
        checkForm.start(function () {
            // first_name
            // if (($('#class_code')["0"].selectedOptions["0"].value ?? '') === '') {
            //     // disabled = true;
            //     $('#level--help').html('FIELD VALUE REQUIRED')
            // } else {
            //     $('#level--help').html('&nbsp;')
            // }

            if ($('#subject')["0"].selectedOptions["0"].value === '') {
                // disabled = true;
                $('#subject--help').html('FIELD VALUE REQUIRED')
            } else {
                $('#subject--help').html('&nbsp;')
            }
            //
            if ($('#exam_name')["0"].selectedOptions["0"].value === '') {
                // disabled = true;
                $('#exam_name--help').html('FIELD VALUE REQUIRED')
            } else {
                $('#exam_name--help').html('&nbsp;')
            }
            //
            if ($('#exam_timer')["0"].selectedOptions["0"].value === '') {
                // disabled = true;
                $('#exam_timer--help').html('FIELD VALUE REQUIRED')
            } else {
                $('#exam_timer--help').html('&nbsp;')
            }
            // console.log("scared")
        }, 500, true);
    
    });

</script>