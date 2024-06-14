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
// var_dump($student);exit;
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
        <div class="card-body">
            
            <div class="row">
                <div class="table-responsive">
                    <div id="subj_senior" style="display:none" class="table_subject dataTables_wrapper">
                        <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveSubject({action: 'saveSubject', elem: event});" ><i class="fa fa-plus"></i> Update</button>
                        <table id="table-subj_senior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                            <thead>
                                <tr>
                                    <!-- <th>Department</th> -->
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_senior", "action": "all", "elem": event})'/></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div id="subj_junior" style="display:none" class="table_subject dataTables_wrapper">
                        <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveSubject({action: 'saveSubject', elem: event});" ><i class="fa fa-plus"></i> Update</button>
                        <table id="table-subj_junior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_junior", "action": "all", "elem": event})'/></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
    ////
    let loadSubject = (json) =>{
        // return;
        let subject = (json.subjects ?? '') === '' ? {} : JSON.parse(json.subjects);
        // console.log(json);
        let subject_result = (json.subject_result ?? '') === '' ? {} : JSON.parse(json.subject_result);
        // console.log(subject_result)
        $('.table_subject').css("display", "none");
        let data = {class_code: json.class_code, department: json.department};
        let url = "<?php echo URL_ROOT ?>/system/systemSetting/getStudentSubjects";
        if((json.department ?? '') === ''){
            $('#subj_junior').css("display", "");
            $('#performance_table').html('');
            $('#std_name').html(json.first_name + " "+ json.last_name);
            $('#dept').css('display', 'none');
            $('#dept_name').css('display', 'none');
            let i = 0;
            let table_subj_junior = $("#table-subj_junior").DataTable();
            table_subj_junior.destroy();
            table_subj_junior = $('#table-subj_junior').DataTable({
                "processing": true,
                "paging": false,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": data,
                },
                "columns": [
                    {"data": "subject_name"},
                    {"data": "teacher"},
                    {"data": "jnr_status_subj", "render": function(data, type, row, meta){
                        return (data ?? '') + "<input type=\"checkbox\" id="+data +" "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : (data === "COMPULSORY" ? "checked" : '') ) +" onclick='adder({\"row\": "+ meta['row'] +", \"table\": \"#table-subj_junior\", \"elem\": event})'/> ";
                    }},
                ],
                "columnDefs": [
                    {"targets": [2], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[0, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                     console.log(json);
                    //  modalAuto();
                    
                    ////
                    // $.each(subject, (k, v)=>{
                    //     let result = subject_result[k] ?? '';
                    //     let td = classWork > 0 ? '<td class="container_flip2" id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result[examName.first_name] ?? '') +'</td>' : '';
                    //     let td_ = midTermExm > 0 ? '<td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result[examName.second_name] ?? '') +'</td>' : '';
                    //     // console.log(result);
                    //     let html = '\
                    //     <tr style="border-bottom:1px solid black">\
                    //         <td style="border-right:1px solid black;font-weight:bold;">'+ k +'</td>'+td+'\
                    //         '+td_+'\
                    //         <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result[examName.third_name] ?? '' )+'</td>\
                    //         <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['final_score'] ?? '' )+'</td>\
                    //         <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['final_grade'] ?? '' )+ '</td>\
                    //     </tr>';
                    //     $('#performance_table').append(html);
                    //     // console.log(sub)
                    // })
                }
            });
            
            loadSocialBehaviour(json);

        }else{
            // console.log(data);
            $('#subj_senior').css("display", "");
            $('#performance_table').html('');
            let table_subj_senior = $("#table-subj_senior").DataTable();
            $('#std_name').html(json.first_name + " "+ json.last_name);
            $('#dept').css('display', '').html('Department');
            $('#dept_name').css('display', '').html(json.department);
            
            table_subj_senior.destroy();
            table_subj_senior = $('#table-subj_senior').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": data,
                },
                "columns": [
                    {"data": "subject_name"},
                    {"data": "teacher"},
                    {"data": "subject_name", "render": function(data, type, row, meta){
                        let status = '';
                        status=  row['science'] ?? '';
                        return (status ?? '') === "" ? "unchecked" : status + "<input type=\"checkbox\" id="+status +" "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : "" ) +" onclick='adder({\"row\": "+ meta['row'] +", \"table\": \"#table-subj_senior\", \"elem\": event})'/> ";
                    }},
                ],
                "columnDefs": [
                    {"targets": [2], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[0, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  console.log(json);
                    //  modalAuto();
                    ////
                    // $.each(subject, (k, v)=>{
                    //     let result = subject_result[k] ?? '';
                    //     let td = classWork > 0 ? '<td id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['class_work'] ?? '') +'</td>' : '';
                    //     let html = '\
                    //     <tr style="border-bottom:1px solid black">\
                    //         <td class="" style="border-right:1px solid black;">'+ k +'</td>'+td+'\
                    //         <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['mid_term_exam'] ?? '') +'</td>\
                    //         <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['terminal_exam'] ?? '' )+'</td>\
                    //         <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['final_score'] ?? '' )+'</td>\
                    //         <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden" contenteditable="true" onkeyup="contenteditablechange({elem: event})">'+ (result['final_grade'] ?? '' )+ '</td>\
                    //     </tr>';
                    //     $('#performance_table').append(html);
                    //     // console.log(sub)
                    // })
                }
            });
            loadSocialBehaviour(json);
        }
        
    }
    let saveSubject = (json) => {
        let rows = {};
        let el, tr;
        let student_ = {std_code: student[0].std_code};
        
        el = $($(json.elem.target).closest('div')).find('table');
        let table = $(el).DataTable();
        tr = $(el).find('tbody tr');
        if(!table.data().any()) return;
        
        $.each(tr, (k, v)=>{
            //save the subject name only
            obj = {};
            let td_subject = $(v).find('td:eq(0)')[0];
            let td_checkbox = $($(v).find('td:eq(2)')).find('input')[0];
            let status = $($(v).find('td:eq(2)'))[0].childNodes[0].data;
            
            if(!td_checkbox.checked)return;
            rows[td_subject.innerText] = (td_checkbox.checked ?? '') ? status : '';
        })
        // console.log(rows);return;
        saving = true;
        $.post('<?php echo URL_ROOT ?>/school/students/saveSubject/?user_log=<?php echo $data['params']['user_log'] ?>',{data: JSON.stringify(rows), std: student_}, function (data) {
            //
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            // console.log(data.status)
            saving = false;
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
        },'JSON');
    }

    
    let adder = (json)=>{
        
        // console.log(json.action);return
        if((json.action ?? '') === "all"){
            if(json.elem.target.checked){
                let elem = $(json.table+ " tbody").find('tr');
                $($(elem).find('td:eq(0)')).css("color","green")
                let inp = $(elem).find('input:not(:checked)');
                $(inp).prop("checked", "true");
                // return;

            }else if(!json.elem.target.checked){
                let elem = $(json.table+ " tbody").find('tr');
                $($(elem).find('td:eq(0)')).css("color","")
                let inp = $(elem).find('input');
                $(inp).prop("checked", false);
                // console.log(inp);
                // return;

            }

        }

       if(json.elem.target.checked){
            let el = $($(json.elem.target).closest('tr')).find('td:eq(0)');
            $(el).css("color", "green");
       }else if(!json.elem.target.checked){
            let el = $($(json.elem.target).closest('tr')).find('td:eq(0)');
            $(el).css("color", "");

       }
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });  
        loadSubject(student)
    
    });

</script>