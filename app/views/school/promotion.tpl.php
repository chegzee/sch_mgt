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
$classesobj = $data['classesobj'] ?? [];
$levelObjDigit = $data['levelObjDigit'] ?? [];
// $classrooms = $data['classrooms'] ?? [];
// $classroomsObj = $data['classroomsObj'] ?? [];
// var_dump($levelObjDigit);exit;
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
            
            <!-- <button onclick="modalStudent({table: '#table-std', row: ''}); $('#modal-title').html('New student')"><i class="fa fa-plus"></i> New</button> -->
            <!-- <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'table_std_schedule', row: '', modal: '#modal-std_schedule'})"><i class="fa fa-file-import"></i>Upload exam schedule</button> -->
            <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                Action<i class="fas fa-tasks"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="background-color:#ccc;z-index:99">
                <a class="dropdown-item" href="javascript:void(0)" onclick="promotion(event)">
                    <i class="fas fa-bullseye text-orange-green"></i>Promotion
                </a>
            </div>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-std" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th>
                                    <!-- <i class="material-icons">build </i> -->
                                    <input type="checkbox" class="" onclick="$('input.std-list:not(:disabled)').prop({checked: $(this).prop('checked')})">
                                </th>
                                <th>Date</th>
                                <th>Code</th>
                                <th>Class</th>
                                <th>Class Name</th>
                                <th>Picture</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Admission ID</th>
                                <th>Birthday</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- studentrModal -->
<div id="modal-std" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <!-- <div style="position:absolute;top:4px;left:200px;">+120days</div> -->
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Student New/Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Page One</a>
                    <a class="nav-link has-icon other-link" href="#page_2" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Page Two</span></a>
                    <a class="nav-link has-icon other-link" href="#page_3" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Page Three</span></a>
                </nav>
                <div class="tab-content">
                    
                    <div class="tab-pane show active" id="page_1">
                        <div class="row">
                            
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="std_code">ID *</label>
                                <input type="text" class="form-control form-control-lg" id="std_code" style="width: 100%"/>
                                <code class="small text-danger" id="std_code--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="first_name">First name </label>
                                <input type="text" class="form-control form-control-lg" id="first_name" style="width: 100%"/>
                                <code class="small text-danger" id="first_name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="last_name">Last name </label>
                                    <input type="text" class="form-control form-control-lg" id="last_name" style="width: 100%"/>
                                <code class="small text-danger" id="last_name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="gender">Gender </label>
                                <select class="form-control form-control-lg" id="gender" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="gender--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="birthday">Date of Birth </label>
                                    <input class="form-control form-control-lg" id="birthday" style="width: 100%"/>
                                <code class="small text-danger" id="birthday--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="roll">Roll <span class="small text-primary"> (Optional)</span></label>
                                    <input type="text" class="form-control form-control-lg" id="roll" style="width: 100%"/>
                                <code class="small text-danger" id="roll--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="blood_group">Blood Group <span class="small text-primary"> (Optional)</span></label>
                                    <select class="form-control form-control-lg" id="blood_group" style="width: 100%">
                                    </select>
                                <code class="small text-danger" id="blood_group--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="religion">Religion </label>
                                 <select class="form-control form-control-lg" id="religion" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="religion--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="email">Email<span class="small text-primary"> (Optional)</span> </label>
                                <input type="email" class="form-control form-control-lg" id="email" style="width: 100%"/>
                                <code class="small text-danger" id="email--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="cat_code">Class </label>
                                <select class="form-control form-control-lg" id="cat_code" style="width: 100%"></select>
                                <code class="small text-danger" id="class--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group" id="dept_container" style="display: none;">
                                <label for="cat_code">Department </label>
                                <select class="form-control form-control-lg" id="department" style="width: 100%">
                                    <option></option>
                                    <option>SCIENCE</option>
                                    <option>COMMERCIAL</option>
                                    <option>ART</option>
                                </select>
                                <code class="small text-danger" id="department--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="class_name_code">Class Name </label>
                                <select class="form-control form-control-lg" id="class_name_code" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="class_name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="admission_id">Admission id </label>
                                <input type="text" class="form-control form-control-lg" id="admission_id" style="width: 100%"/>
                                <code class="small text-danger" id="admission_id--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="section">Section </label>
                                 <select class="form-control form-control-lg" id="section" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="section--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="phone">Phone <span class="small text-primary"> (Optional)</span></label>
                                <input type="text" class="form-control form-control-lg" id="phone" style="width: 100%"/>
                                <code class="small text-danger" id="phone--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="address">Address </label>
                                <input class="form-control form-control-lg" id="address" type="text" style="width: 100%"/>
                                <code class="small text-danger" id="address--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="parent_name">Parent name </label>
                                <input type="text" class="form-control form-control-lg" id="parent_name" style="width: 100%"/>
                                <code class="small text-danger" id="parent_name--help">&nbsp;</code>
                            </div>
                            <div class=" col-lg-6 px-3 mt-4" style="position: relative;">
                                <div class="form-group row">
                                    <!-- <label style="position: absolute;top:0px;left:8px;"> <br><span class="small text-warning">Click to upload picture</span></label> -->
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture" style="height: auto; width: 100%; color: #9999ff">
                                                </div>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="picture--help">&nbsp;</code>
                                    </div>
                                    <input type="file" id="picture-file" accept="image/*" onchange="imageChange({'event': event, 'preview':'picture', 'items': [$('#first_name').val(), $('#last_name').val()]}); modalLoadingDiv = $('#modal-std'); googleDriveUpload({doc_path: 'picture', directory: 'users/', item: '#picture-file', newName: $('#first_name').val().toLowerCase()+ '-' + $('#last_name').val().toLowerCase().slice(0, ($('#first_name').val() + '@').indexOf('@')) + '-picture','items': [$('#first_name').val(), $('#last_name').val()], unique: false })" style="display:none">
                                    <input type="hidden" id="picture" readonly>
                                </div>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <!-- <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalStudent({table: '#table-std', row: ''}); $('#modal-title').html('New Student')"><i class="fa fa-refresh"></i> Reset</button>
                                <button id="save-student" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveStudent({})" disabled ><i class="fa fa-save"></i> Save </button> -->
                            </div>
                            <input id="std_code_old" style="display:none"/>
                        </div>
                    </div>
                    <div class="tab-pane" id="page_2">
                        
                        <div class="row">
                            <div class="table-responsive">
                                <div id="subj_senior" class="table_subject dataTables_wrapper">
                                    <!-- <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveStudent({action: 'saveSubject', elem: event});" ><i class="fa fa-plus"></i> Update</button> -->
                                    <table id="table-subj_senior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <!-- <th>Department</th> -->
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_senior", "action": "all", "elem": event})' disabled/></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div id="subj_junior" class="table_subject dataTables_wrapper">
                                    <!-- <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveStudent({action: 'saveSubject', elem: event});" disabled ><i class="fa fa-plus"></i> Update</button> -->
                                    <table id="table-subj_junior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_junior", "action": "all", "elem": event})' disabled/></th>
                                                
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="tab-pane" id="page_3">
                
                        <div id="" class="dataTables_wrapper row">
                            <table id="std_profile_table" class="table table-striped table-bordered table-sm nowrap w-100 datatableList col-lg-8">
                                <tbody>
                                    <tr><td>Name</td><td id="std_name"></td><tr>
                                    <tr><td id="dept"></td><td id="dept_name"></td><tr>
                                    <tr>
                                        <td>Year</td><td><?php echo $data['term']['year'] ?></td>
                                    <tr>
                                        <tr>
                                            <td>Term</td><td><?php echo $data['term']['term'] ?></td>
                                        </tr>
                                </tbody>
                            </table>
                            <div class="col-lg-4" style="">
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
                            <div class="col-lg-5" style="position:relative;border:1px solid black;height:220px">
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
                            <div class="col-lg-3" style=height:220px;>
                                <div class="row" style="height:100%;">
                                    <div class="container_flip">
                                        <h6 class="rotate flipvertical">Class work summary </h6>
                                        <div style="position:absolute;bottom:0px;right:8px;"> <?php echo($examRate->class_work ?? '') ?>%</div>
                                    </div>
                                    <div class="container_flip"> 
                                        <h6 class="rotate flipvertical">Mid Term Exam </h6>
                                        <div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->mid_term_exam ?? '') ?>%</div>
                                    </div>
                                    <div class="container_flip">
                                         <h6 class="rotate flipvertical">Terminal Exam </h6>
                                        <div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->terminal_exam ?? '') ?>%</div>
                                    </div>
                                    <div class="container_flip">
                                        <h6 class="rotate flipvertical">Final Score </h6>
                                        <div style="position:absolute;bottom:0px;right:8px;"><?php echo(($examRate->class_work ?? 0) + ($examRate->mid_term_exam ?? 0) + ($examRate->terminal_exam ?? 0) ) ?>%</div>
                                    </div>
                                    <div class="container_flip">
                                        <h6 class="rotate flipvertical">Final Grade </h6>
                                        <div style="position:absolute;bottom:0px;right:8px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4" style="position:absolute;right:2px;z-index:999">
                                <table style="border:1px solid black;font-weight:bold" id="table-social_beh">
                                    
                                </table>

                            </div>
                            <div style="position:relative;text-align:center;width:717.5px;height:50px;border:1px solid black;line-height:50px;font-size:18px;font-weight:bold;">
                                <!-- <button id="performance_btn" style="position:absolute;left:0px;top:0p;height:45px;width:100px" onclick="savePerformance(event)" disabled><i class="fa fa-save"></i> Save</button> -->
                                Subjects
                            </div>
                            <div style="position:relative;width:717.5px;">
                                <table id="performance_table" style="border-left:1px solid black;border-right:1px solid black;">
                                </table>
                            </div>
                            
                        </div>       
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- promotion -->
<div id="modal-promotion" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <!-- <div style="position:absolute;top:4px;left:200px;">+120days</div> -->
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Student PROMOTION</h5>
                <button style="margin-left:4px;border:none;width:100px;backgroud-color:#ccc;" onclick="auto_promotion()">Auto</button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="" style="position:relative;min-height:500px;">
                    <div style="position:absolute;bottom:4px;right:4px;text-align:right;padding:12px;">
                        <button id="save-promote" onclick="savePromotion({elem: event, table: '#table-promotion'})"><i class="fa fa-save"></i> Save</button>
                    </div>
                
                    <dialog id="myFirstDialog" style="position:absolute;top:50px;width:50%;background-color:#F4FFEF;border:1px dotted black;text-align:center">  
                        <p> <q></q> No students selected<q></q></p>
                    </dialog>  
                    <div class="table-responsive">
                        <table id="table-promotion">
                            <thead>
                                <tr style="text-align:center;background-color:#042954;color:white">
                                    <th style="min-width:200px;border:1px solid black;">Current Session</th>
                                    <th style="min-width:100px;border:1px solid black;">ID</th>
                                    <!-- <th style="min-width:200px;border:1px solid black;">Department</th> -->
                                    <th style="min-width:200px;border:1px solid black;">Name</th>
                                    <th style="min-width:200px;border:1px solid black;">Promote from class</th>
                                    <th style="min-width:200px;border:1px solid black;">Promote to class</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
            
            </div>
        </div><!-- /.promotion-content -->
    </div><!-- /.promotion-dialog -->
</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>

    // (function() {    
        
    //     document.getElementById('hide').onclick = function() {    
    //         dialog.close();    
    //     };    
    // })(); 
    
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
    let classesobj = <?php echo json_encode($data['classesobj']) ?>;
    let allClasses = <?php echo json_encode($data['classes']) ?>;
    let tableStudents = null;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    // console.log(classesobj);

    let modalStudent = (json) => { 
        
        // console.log(json.row);return;
        tableStudents = $(json.table).DataTable();
        // let data = json.row === '' ? {} : ( json.modalAuto ? tableStudents.row(json.row).data() : json.row); // data["colName"]
        let data = json.row === '' ? {} : json.row.data(); // data["colName"]
        // console.log(data);return;
        // localStorage.setItem("selected-std", data['std_code'] ?? '');
        // localStorage.setItem("std-period", period);
        // period = (json.period ?? '') < 0;
        // console.log(json.period);
        // console.log(json.days, json.period);
        // console.log(data)
        
            $('#modalNav').find('a.non-active').addClass('d-none');
            
            $('#std_code').val(data['std_code'] ?? 'AUTO');
            $('#std_code_old').val(data['std_code'] ?? '');
            $('#department').append(new Option(data['department'] ?? '', data['department'] ?? '' , true, true)).trigger('change');
            $('#class_name_code').append(new Option(data['class_name'] , data['class_name_code'] , true, true)).trigger('change');
            $('#first_name').val(data['first_name'] ?? '');
            $('#last_name').val(data['last_name']);
            $('#gender').append(new Option(data['gender'] ?? "" , data['gender'] ?? "", true, true)).trigger('change');
            $('#birthday').val( (data['birthday'] ?? '') ).prop({disabled: (data['birthday'] ?? '') != '' });
            // $('#birthday').val( (data['birthday'] ?? moment().format('YYYY-MM-DD')).slice(0, 10) ).prop({disabled: (data['birthday'] ?? '') != '' });
            $('#roll').val(data['roll']);
            $('#blood_group').append(new Option(data['blood_group'] ?? '', data['blood_group'] ?? '', true, true)).trigger('change');
            $('#religion').append(new Option(data['religion'] ?? "", data['religion'] ?? "" , true, true)).trigger('change');
            $('#email').val(data['email']);
           // $('#term').append(new Option(data['term_name'] ?? '', data['term'] ?? '', true, true)).trigger('change');
           // $('#year').append(new Option(data['year'] ?? '', data['year'] ?? '', true, true)).trigger('change');
            $('#section').append(new Option(data['section'] ?? "", data['section'] ?? "", true, true)).trigger('change');
            $('#cat_code').append(new Option(data['cat_name'] ?? "", data['cat_code'] ?? "", true, true)).trigger('change');
            $('#admission_id').val(data['admission_id']);
            $('#phone').val(data['phone']);
            $('#parent_name').val(data['parent_name']);
            $('#address').val(data['address']);
            let pics = data['picture'] ?? '';
            pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
            //
            $('#picture--preview').attr('src', pics);
            //
            $('#picture').val(data['picture'] ?? '');
            //
            loadSubject(data);
            $('#modal-std').modal('show');

            $('.other-link').each((k, v)=>{
                // console.log(v);
                $(v).prop({disabled: period}).css({backgroundColor: "#cfcfcf"});
            })
            //
            // if(json.page === "3"){
            //     $('#modalNav a[href="#page_3"]').tab((period ? 'hide' : 'show'));
            //     return;
            // }
            // if(json.page === "2"){
            //     $('#modalNav a[href="#page_2"]').tab( (period ? 'hide' : 'show'));
            //     return;
            // }
            $('#modalNav a[href="#page_1"]').tab('show');
            
            // })
    }

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

    let savePromotion = (json)=>{
        let element = json.elem.target;
         let table =json.table
        let tr_collection = $(table).find('tbody tr')
        let rows = [];
        $.each(tr_collection, (k, v)=>{
            let obj = {};
            let std_code = $($(v).find('td:eq(1)')).html()
            let level_select = $($(v).find('td:eq(4)')).find('select#promote_select')['0'].selectedOptions['0'];
            let class_code = level_select.value ?? '';
            let dept_ = $($(v).find('td:eq(4)')).find('select#promote_select')['0'].nextElementSibling.selectedOptions[0] ?? '' ;
            obj['std_code'] = std_code;
            obj['class_code'] = class_code;
            obj['department'] = dept_.value ?? '';
            rows.push(obj)
        })
        let data_= {data: JSON.stringify(rows)};
        // console.log(rows);return;
        let url = "<?php echo URL_ROOT ?>/school/promotion/_save/?user_log=<?php echo $data['params']['user_log'] ?> ";
        $.post(url, data_, (data)=>{
            // console.log(data)
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.data, timeout: 10000}).show();
                    return false;
                    //
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                // console.log(data)
        }, 'JSON')
    }
    //promotion table
    let promotion = (e)=>{
        let gg = $($($($('#table-std tbody').find('tr')).find('td:eq(0)')).find('input:checked'));
        
        if(gg.length < 1) {
            var dialog = document.getElementById('myFirstDialog');    
            dialog.show(); 
        }
        //
        $('#table-promotion tbody').find('tr').remove();
        let id = '';
        $.each(gg, (k, v)=>{
            id = "promote_to"+k
           let elem =  $(v)['0'];
           let std_code = $(v).val();
           let first_name = elem.dataset.first_name ?? ''
           let last_name = elem.dataset.last_name ?? ''
           let cat_name = elem.dataset.cat_name ?? ''
           let class_name = elem.dataset.class_name ?? ''
           let end_date = elem.dataset.end_date ?? ''
           let digit = elem.dataset.digit ?? ''
           let department = elem.dataset.department

           let html = '<tr style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">\
                <td style="border-right:1px solid black;white-space:nowrap;">'+end_date+'</td>\
                <td style="border-right:1px solid black;white-space:nowrap;">'+std_code+'</td>\
                <td style="border-right:1px solid black;white-space:nowrap;font-weight:bold;font-size:18px;">'+first_name+' '+last_name+''+((department !== '') ? ' ('+ department + ')' : '')+' </td>\
                <td style="border-right:1px solid black;white-space:nowrap;">'+cat_name+' <span style="color:red;">('+class_name+')</span></td>\
                <td style="border-right:1px solid black;white-space:nowrap;" style="">\
                    <select id="promote_select" onchange="displaydept(event)">\
                        <option value="" disabled selected hidden class="custom-option">Choose a class</option>\
                    </select>\
                    <select style="display:none;color: blue;">\
                    </select>\
                </td>\
            </tr>';
            $('#table-promotion').append(html);
            
        })
        //
        let tr_collection = $('#table-promotion tbody').find('tr');
        // console.log(tr_collection)
        $.each(tr_collection, (k, v_)=>{
            let level = $($(v_).find('td:eq(4)')).find('select#promote_select');
            $.each(classesobj, (k, vv)=>{
                let html = '<option class="custom-option" value='+vv.class_code+' data-digit="'+ vv.digit +'">'+vv.cat_name+ '-'+vv.class_name +'</option>';
                $(level).append(html);
            })
        })
        
        $('#modal-promotion').modal("show");
    } 

    let auto_promotion = ()=>{
        
        let tr_collection = $('#table-promotion tbody').find('tr');
        let gg = $($($($('#table-std tbody').find('tr')).find('td:eq(0)')).find('input:checked'));
        // console.log(gg);return
        $.each(gg, (k, v)=>{
           let tr = $('#table-promotion tbody').find('tr:eq('+ k +')');
           id = "promote_to"+k
           let elem =  $(v)['0']
           let std_code = $(v).val();
           let first_name = elem.dataset.first_name ?? ''
           let last_name = elem.dataset.last_name ?? ''
           let cat_name = elem.dataset.cat_name ?? ''
           let class_name = elem.dataset.class_name ?? ''
           let end_date = elem.dataset.end_date ?? ''
           let digit = elem.dataset.digit ?? ''
           let department = elem.dataset.department ?? ''
           let level_ = parseInt(digit);
            //    console.log(level_)
           if(level_ < 14){
                level_ += 1;
                // console.log(level_)
                $.each(allClasses, (k, v)=>{ 
                    if(parseInt(v.digit) === level_ && v.class_name === class_name){
                        // let studentLevel = levelObjDigit[level_];
                        let level_name = v.cat_name;
                        let c_name = v.class_name;
                        let c_code = v.class_code;
                        let td = $(tr).find("td:eq(4)");
                        let sel = $(td).find("select#promote_select");
                        sel.append(new Option(level_name + " " + c_name, c_code, true, true)).trigger("change");
                        if(department !== ''){
                            let nextElem = $(sel)["0"].nextElementSibling;
                            
                            $(nextElem).html('');
                            $(nextElem).css("display", "");
                            console.log(nextElem);
                            let fields = ['SCIENCE', 'COMMERCIAL', 'ART'];
                            $.each(fields, (k, v)=>{
                                $(nextElem).append(new Option(v, v));
                                
                            })
                             $(nextElem).append(new Option(department, department, true, true)).trigger('change');
                            //$(nextElement).append(new Option(department, department, true, true)).trigger("change");

                        }

                    }
                })
                // console.log(sel);
           }
        //   console.log(studentLevel);
            
        })
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = (json) => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        
        let selectedStd = localStorage.getItem('selected-std');
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        if (selectedStd !== '' && modalOpen) {
            localStorage.setItem('selected-row', '');
        
                tableStudents = $('#table-std').DataTable();
            
                tableStudents.rows().every(function () {
                    let data = this.data();
                    if(data.std_code === selectedStd){
                        modalStudent({table: '#table-std', row: this, page: json.page, modalAuto: true});
                        // console.log(this);
                    }
                });
            
        } else modalStudent({table: '#table-std', row: ''});
        
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
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/getMapping/?user_log=<?php echo $data['params']['user_log'] ?> ";
        
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
                        return (data ?? '') + "<input type=\"checkbox\" id="+data +" "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : "" ) +"/> ";
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
                }
            });
            ////
            $.each(subject, (k, v)=>{
                let result = subject_result[k] ?? '';

                let html = '\
                <tr style="border-bottom:1px solid black">\
                    <td style="border-right:1px solid black;" width="462.5px">'+ k +'</td>\
                    <td id="scar" style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50">'+ (result['class_work'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50">'+ (result['mid_term_exam'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50">'+ (result['terminal_exam'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;border-right:1px solid black;max-width:50px;overflow:hidden" width="50">'+ (result['final_score'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;max-width:50px;overflow:hidden" width="50">'+ (result['final_grade'] ?? '' )+ '</td>\
                </tr>';
                $('#performance_table').append(html);
                // console.log(sub)
            })
            //
            let rows_ = $('#performance_table').find('tr');
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
                    // {"data":"", "render": function(data, type, row, meta){
                    //     return json.department;
                    // }},
                    {"data": "subject_name"},
                    {"data": "teacher"},
                    {"data": "subject_name", "render": function(data, type, row, meta){
                        var status = '';
                       // if(json.department === 'SCIENCE'){
                             status=  row['science'];
                        // }else if(json.department === 'COMMERCIAL'){
                        //     status= (row['comm_compulsory'] ?? '') !== '' ? row['comm_compulsory'] : row['comm_elective']
                        //     // compulsory = row['comm_compulsory']
                        // }else if(json.department === 'ART'){
                        //     status= (row['art_compulsory'] ?? '') !== '' ? row['art_compulsory'] : row['art_elective']
                        //     // compulsory = row['art_compulsory']
                        // }else{
                        //     status = ""
                        // }
                        return (status ?? '') === "" ? "unchecked" : status + "<input type=\"checkbox\" id="+status +" "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : "" ) +" disabled/> ";
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
                }
            });
            ////
            $.each(subject, (k, v)=>{
                let result = subject_result[k] ?? '';
                // console.log(result); 
                let html = '\
                <tr style="border-bottom:1px solid black">\
                    <td style="border-right:1px solid black;" width="462.5px">'+ k +'</td>\
                    <td id="scar" style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="51">'+ (result['class_work'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="51">'+ (result['mid_term_exam'] ?? '') +'</td>\
                    <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="51">'+ (result['terminal_exam'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;border-right:1px solid black;max-width:50px;overflow:hidden" width="51">'+ (result['final_score'] ?? '' )+'</td>\
                    <td style="text-align:center;font-weight:bold;max-width:50px;overflow:hidden" width="51">'+ (result['final_grade'] ?? '' )+ '</td>\
                </tr>';
                $('#performance_table').append(html);
                // console.log(sub)
            })
            //
            let rows_ = $('#performance_table').find('tr');
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

    let displaydept = (json)=>{
        let elem = json.target.nextElementSibling;
        let digit = parseInt(json.target.selectedOptions[0].dataset.digit)
        let class_code = json.target.selectedOptions[0].value;
        let text = json.target.selectedOptions[0].text;
        if(digit >= 12){ 

            $(elem).css("display", "");
            $(elem).html('');
            let fields = ['SCIENCE', 'COMMERCIAL', 'ART'];
            $(elem).append(new Option("Choose an option", "", true, true)).trigger('change');
            $.each(fields, (k, v)=>{
                $(elem).append(new Option(v, v));
                
            })
        }else{

            $(elem).css("display", "none");
            $(elem).html('');
            $(elem).append(new Option("Choose an option", "")).trigger('change');

        }
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });  
        //
        $('#modal-promotion').on('hidden.bs.modal', function () {
            var dialog = document.getElementById('myFirstDialog');    
            dialog.close(); 
            tableStudents.ajax.reload(null, false);
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        tableStudents = $("#table-std").DataTable();
    
        let loadStudent = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/school/students/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableStudents.destroy();
        
            tableStudents = $('#table-std').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "std_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<input type="checkbox" style="width:20px;height:30px;" class="std-list" value="'+row['std_code']+'" data-first_name="'+row['first_name']+'" data-last_name="'+row['last_name']+'" data-cat_name="'+row['cat_name']+'" data-class_name="'+row['class_name']+'" data-end_date="'+(row['end_date'] ?? '')+'" data-department="'+(row['department'] ?? '')+'" data-digit="'+row['digit']+'"/>';
                        }
                    },
                    {"data": "create_date"},
                    {"data": "std_code"},
                    {"data": "cat_name"},
                    {"data": "class_name"}, 
                    {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                        return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                     }},
                    {"data": "first_name"},
                    {"data": "last_name"},
                    {"data": "gender"},
                    {"data": "phone"},
                    {"data": "admission_id"},
                    {"data": "birthday"},
                    {"data": "email"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "desc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  console.log(json);
                }
            });
        }
    
        loadStudent({});
    
        //
        tableStudents.search('', false, true);
        //
        tableStudents.row(this).remove().draw(false);
    
        //
        $('#table-std tbody').on('click', 'td', function () {
            //
            let data = tableStudents.row(this);
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
        
            if (!data) return;
            //
            if (this.cellIndex != 0) {
                modalStudent({table: '#table-std', row: data});
            }
        });

        
        //
        let checkForm = new timer();
        checkForm.start(function () {
            // console.log("scared")
        }, 5000, true);
    
    });

</script>