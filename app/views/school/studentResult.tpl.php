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
        /* select:required:invalid {
        color: #666;
        }
        option[value=""][disabled] {
            display: none;
        }
        option {
            color: #000;
        } */
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
    </style>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body" style="padding-left:0px;">
            <div id="" class="dataTables_wrapper row">
                <div class="col-lg-8" style="position:relative;height:220px">
                    <div style="position:absolute;top:20px;left:80px;">KEY TO GRADE</div>
                    <div style="position:absolute;bottom:0px;">
                        <?php 
                            foreach($examGrade as $k => $examGrade){
                                echo $examGrade->grade_name .'. '.''.$examGrade->comment.' = '. ''.$examGrade->percent_from.'%  - '. ''.$examGrade->percent_upto.'%' ;
                                echo "<br/>";

                            }
                        ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <table>
                        <tr><td colspan="2" style="">KEY TO RATING</td></tr>
                        <?php 
                            foreach($socialKey as $k => $v){
                                echo '<tr><td style="width:40%;">'.$v->key_value.'</td><td style="width:60%">'.$v->key_name .'</td></tr>';

                            }
                        ?>
                    </table>
                </div>
            </div>
            <div class="row" style="position:relative;">
                <div style="" class="col-lg-8">
                    <table id="performance_table" style="border-left:1px solid black;border-right:1px solid black;width:100%">
                        <thead style=height:220px;>
                            <tr>
                                <th style="border:1px solid black;height:100%">
                                </th>
                                <th class="container_flip" style="height:100%;width:51px;">
                                    <h6 class="rotate flipvertical">Class work </h6>
                                    <div style="position:absolute;bottom:0px;right:8px;"> <?php echo($examRate->class_work ?? '') ?>%</div>
                                </th>
                                <th class="container_flip" style="height:100%;width:51px;">
                                    <h6 class="rotate flipvertical">Mid Term Exam </h6>
                                    <div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->mid_term_exam ?? '') ?>%</div>
                                </th>
                                <th class="container_flip" style="height:100%;width:51px;">
                                    <h6 class="rotate flipvertical">Terminal Exam </h6>
                                    <div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->terminal_exam ?? '') ?>%</div>

                                </th>
                                <th class="container_flip" style="height:100%;width:51px;">
                                    <h6 class="rotate flipvertical">Final Score </h6>
                                    <div style="position:absolute;bottom:0px;right:8px;"><?php echo(($examRate->class_work ?? 0) + ($examRate->mid_term_exam ?? 0) + ($examRate->terminal_exam ?? 0) ) ?>%</div>
                                </th>
                                <th class="container_flip" style="height:100%;width:51px;">
                                    <h6 class="rotate flipvertical">Final Grade </h6>
                                    <div style="position:absolute;bottom:0px;right:8px;"></div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                
                <div class="col-lg-4" style="position:absolute;right:2px;z-index:999">
                    <table style="border:1px solid black;font-weight:bold" id="table-social_beh">
                        
                    </table>

                </div>
                
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
    let tableStudents = null;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    // console.log(student);

    let loadSocialBehaviour = (json)=>{
        let social_beh = JSON.parse((json.social_beh ?? '') === '' ? '{}' : json.social_beh);
        $('#table-social_beh').html('');
        $('#table-social_beh').append('<tr><td style="position:relative;text-align:center;width:350px;border:1px solid black"><button style="position:absolute;left:0px;top:0px;" onclick="saveSocialBehaviour(event)" id="save_social" disabled><i class="fa fa-save"></i> </button>SOCIAL BEHAVIOUR</td><td style="text-align:center;border:1px solid black">RATING</td></tr>')
        // console.log(socialBehaviour_)
        $.each(socialBehaviour_, (k, v)=>{
            let html = '<tr><td style="border:1px solid black;">'+v['behaviour']+'</td><td style="text-align:center;border:1px solid black">'+(social_beh[v['behaviour']] ?? '')+'</td></tr>';
            $('#table-social_beh').append(html);
        })
        
        // console.log(json);
    }

    //// load subject and result
    let loadSubject = (json) =>{
        console.log(json);
        // return;
        let subject = (json.subjects ?? '') === '' ? {} : JSON.parse(json.subjects);
        // console.log(json);
        let subject_result = (json.subject_result ?? '') === '' ? {} : JSON.parse(json.subject_result);
        // console.log(subject_result)
        // $('.table_subject').css("display", "none");
        let data = {cat_code: json.cat_code, class_code: json.class_code, department: json.department};
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/getMapping/?user_log=<?php echo $data['params']['user_log'] ?> ";
        
        // $('#modalNav a[href="#page_1"]').tab('show');
        
        if((json.department ?? '') === ''){
            // $('#subj_junior').css("display", "");
            // $('#performance_table').html('');
            // $('#std_name').html(json.first_name + " "+ json.last_name);
            // $('#dept').css('display', 'none');
            // $('#dept_name').css('display', 'none');
            let i = 0;
            // let table_subj_junior = $("#table-subj_junior").DataTable();
            // table_subj_junior.destroy();
            // table_subj_junior = $('#table-subj_junior').DataTable({
            //     "processing": true,
            //     //"serverSide": true,
            //     "ajax": {
            //         "url": url,
            //         "type": "POST",
            //         "data": data,
            //     },
            //     "columns": [
            //         {"data": "subject_name"},
            //         {"data": "teacher"},
            //         {"data": "jnr_status_subj", "render": function(data, type, row, meta){
            //             return (data ?? '') + "<input type=\"checkbox\" id="+data +" "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : "" ) +"/> ";
            //         }},
            //     ],
            //     "columnDefs": [
            //         {"targets": [2], "sortable": false, "searchable": false},
            //     ],
            //     "aaSorting": [[0, "asc"]],
            //     "initComplete": function (settings, json) {
            //         $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
            //         //  console.log(json);
            //         //  modalAuto();
            //     }
            // });
            ////
            $.each(subject, (k, v)=>{
                let result = subject_result[k] ?? '';

                let html = '\
                <tr style="border-bottom:1px solid black">\
                    <td style="border-right:1px solid black;">'+ k +'</td>\
                    <td id="scar" style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden">'+ (result['class_work'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden">'+ (result['mid_term_exam'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden">'+ (result['terminal_exam'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;border-right:1px solid black;max-width:50px;overflow:hidden">'+ (result['final_score'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;max-width:50px;overflow:hidden">'+ (result['final_grade'] ?? '' )+ '</td>\
                </tr>';
                $('#performance_table tbody').append(html);
                // console.log(sub)
            })
            //
            let rows_ = $('#performance_table tbody').find('tr');
            $.each(rows_, (k, v)=>{
                // console.log(v)
                let text = parseFloat(String($(v).find('td:eq(5)')["0"].textContent));
                // console.log(text)
                $.each(examGrade_, (k, vv)=>{
                    let from = parseFloat(vv.percent_from)
                    let upto = parseFloat(vv.percent_upto)
                    if(text >= from && text <= upto){
                       let td__  = $(v).find('td:eq(5)')['0'];
                       td__.innerHTML = vv.grade_name
                        // console.log(td__);
                        return false;

                    }
                })
                // console.log(text);

            })
            loadSocialBehaviour(json);

        }else{
            // console.log(data);
            // $('#subj_senior').css("display", "");
            // $('#performance_table tbody').html('');
            // // let table_subj_senior = $("#table-subj_senior").DataTable();
            // $('#std_name').html(json.first_name + " "+ json.last_name);
            // $('#dept').css('display', '').html('Department');
            // $('#dept_name').css('display', '').html(json.department);
            
            // table_subj_senior.destroy();
            // table_subj_senior = $('#table-subj_senior').DataTable({
            //     "processing": true,
            //     //"serverSide": true,
            //     "ajax": {
            //         "url": url,
            //         "type": "POST",
            //         "data": data,
            //     },
            //     "columns": [
            //         // {"data":"", "render": function(data, type, row, meta){
            //         //     return json.department;
            //         // }},
            //         {"data": "subject_name"},
            //         {"data": "teacher"},
            //         {"data": "subject_name", "render": function(data, type, row, meta){
            //             var status = '';
            //             if(json.department === 'SCIENCE'){
            //                 status= (row['sci_compulsory'] ?? '') !== '' ? row['sci_compulsory'] : row['sci_elective']
            //             }else if(json.department === 'COMMERCIAL'){
            //                 status= (row['comm_compulsory'] ?? '') !== '' ? row['comm_compulsory'] : row['comm_elective']
            //                 // compulsory = row['comm_compulsory']
            //             }else if(json.department === 'ART'){
            //                 status= (row['art_compulsory'] ?? '') !== '' ? row['art_compulsory'] : row['art_elective']
            //                 // compulsory = row['art_compulsory']
            //             }else{
            //                 status = ""
            //             }
            //             return (status ?? '') === "" ? "unchecked" : status + "<input type=\"checkbox\" id="+status +" "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : "" ) +"/> ";
            //         }},
            //     ],
            //     "columnDefs": [
            //         {"targets": [2], "sortable": false, "searchable": false},
            //     ],
            //     "aaSorting": [[0, "asc"]],
            //     "initComplete": function (settings, json) {
            //         $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
            //         //  console.log(json);
            //         //  modalAuto();
            //     }
            // });
            ////
            $.each(subject, (k, v)=>{
                let result = subject_result[k] ?? '';
                // console.log(result); 
                let html = '\
                <tr style="border-bottom:1px solid black;">\
                    <td style="border-right:1px solid black;">'+ k +'</td>\
                    <td id="scar" style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50">'+ (result['class_work'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden"  width="50">'+ (result['mid_term_exam'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden"  width="50">'+ (result['terminal_exam'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;border-right:1px solid black;max-width:50px;overflow:hidden" width="50">'+ (result['final_score'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;max-width:50px;overflow:hidden" width="50">'+ (result['final_grade'] ?? '' )+ '</td>\
                </tr>';
                $('#performance_table tbody').append(html);
                // console.log(sub)
            })
            //
            let rows_ = $('#performance_table tbody').find('tr');
            $.each(rows_, (k, v)=>{
                // console.log(v)
                let text = parseFloat(String($(v).find('td:eq(5)')["0"].textContent));
                // console.log(text)
                $.each(examGrade_, (k, vv)=>{
                    let from = parseFloat(vv.percent_from)
                    let upto = parseFloat(vv.percent_upto)
                    if(text >= from && text <= upto){
                       let td__  = $(v).find('td:eq(5)')['0'];
                       td__.innerHTML = vv.grade_name
                        // console.log(td__);
                        return false;

                    }
                })
                // console.log(text);

            })
            loadSocialBehaviour(json);
        }
        
        
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });  
        loadSubject(student[0])

        
        //
        // let checkForm = new timer();
        // checkForm.start(function () {
        //     // console.log("scared")
        // }, 5000, true);
    
    });

</script>