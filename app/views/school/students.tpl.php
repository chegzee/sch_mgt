<?php
$data = $data ?? [];
$term = $data['term'] ?? [];
$termObj = $data['termObj'] ?? [];
$examGrade = $data['examGrade'] ?? [];
$examRate = $data['examRate'] ?? [];
$examName = $data['examName'] ?? [];
$socialBehaviour = $data['socialBehaviour'] ?? [];
$socialKey = $data['socialKey'] ?? [];
$max_key_val = $data['max_key_val'] ?? [];
$max_percent_upto = $data['max_percent_upto'] ?? [];
$products = $data['products'] ?? [];

$start_date = strtotime($term['start_date']);
$end_date = strtotime($term['end_date']);
$milisec_day = 60 * 60 * 24;
$day = ($end_date - $start_date)/$milisec_day;
// $classrooms = $data['classrooms'] ?? [];
// $classroomsObj = $data['classroomsObj'] ?? [];
// $valggk = $classrooms[0];
// var_dump($examName);exit;
echo $data['menu'];
?>

<div class="main-body">
    <style>
        .container_flip{
            position:relative;
            border:1px solid black;
            height:100%;
            width:51px;
        }
        .container_flip2{
            width:51px;

        }
        .flipvertical{
            position:absolute;
            bottom:15px;
            writing-mode: vertical-lr;
            font-size:14px;
            width:100%;
            padding: 8px;
            font-weight: bold;
            transform: rotate(180deg);
        }
        select:required:invalid {
        color: #666;
        }
        option[value=""][disabled] {
            display: none;
        }
        option {
            color: #000;
        }

        .spaction > button {
            font-size: 16px;
            border: 2px solid #4CAF50;
            padding: 8px 16px;
            border-radius: 2px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }
        
        .pList{
            width: 100%;
            max-width: 100%;
            display: flex;
            gap: 5px;
        }
        .bpItem {
            width: 250px;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
            padding: 4px;
            float:left;
            }
        
        .fpImg{
            width: 100%;
            height: 100px;
            /* object-fit: cover; */
        }
        .fpName{
            color: #333;
            font-weight: bold;
        }
  
        .fpCity{
            font-weight: 300;
        }
        
        .fpPrice{
            font-weight: 500;
        }
        
        .detail{
            display: flex;
            flex-direction: column;

        }
        td{
            overflow: hidden
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
            
            <button onclick="modalStudent({table: '#table-std', row: ''}); $('#modal-title').html('New student')"><i class="fa fa-plus"></i> New</button>
            <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'table_std_schedule', row: '', modal: '#modal-std_schedule'})"><i class="fa fa-file-import"></i> Excel</button>
            <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-cog"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99">
                <a class="dropdown-item std_report_btn" href="javascript:void(0)" onclick="printStudentReport({std_code: $('input.student-list:checked').map(function(v){return $(this).val();}).get(), db: 'student' })" >
                    <i class="fas fa-print"></i> Report sheet
                </a>
                <a class="dropdown-item" href="#" onclick="createResult({std_code: $('input.student-list:checked').map(function(v){return $(this).val();}).get(), db: 'student' })">
                    <i class="fas fa-cogs text-dark-pastel-green"></i> Bulk result
                </a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="printInvoice({std_code: $('input.student-list:checked').map(function(v){return $(this).val();}).get(), db: 'student' })" >
                    <i class="fas fa-print"></i> Invoice
                </a>
                <a class="dropdown-item" href="#"  onclick="deleteStudent({std_code: $('input.student-list:checked').map(function(v){return $(this).val();}).get() })">
                <i class="fas fa-trash text-orange-peel"></i> Delete
                </a>
            </div>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-std" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="" onclick="$('input.student-list:not(:disabled)').prop({checked: $(this).prop('checked')})"></th>
                                <th>Date</th>
                                <th>ID</th>
                                <th>Class</th>
                                <th>Fees</th>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Birthday</th>
                                <th>Email</th>
                                <th>Digit</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- std schedule modal -->
    <div id="modal-std_schedule" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-size:18px;">Students</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-small btn-outline-primary mb-3 import_btn" onclick="$('#std_schedule_file').click()"><i class="fa fa-file-import"></i>Import</button>
                    <button class="btn btn-small btn-outline-primary mb-3" onclick="scheduleExportV2({theadRows: '#table-std_schedule_upload thead tr', tbodyRows: '#table-std_schedule_upload tbody', filename: 'student_schedule'})" ><i class="fa fa-file-import"></i>Export</button>
                    <button class="btn btn-small btn-outline-primary mb-3" id="save-import_std_schedule" onclick="saveStdScheduleUpload({table: '#table-std_schedule_upload'})"><i class="fa fa-save"></i>Save</button>
                    <!-- <input type="hidden" id="doc_path" maxlength="250" readonly> -->
                    <input type="file" id="std_schedule_file" accept="application/vmd.openxmlformats.officedocumnet.spreadsheet.sheet, application/vmd.ms.excel" onchange="scheduleImport({excelfile: '#std_schedule_file', table: '#table-std_schedule_upload', action: 'stdSchedule'}, event)" style="display:none">
                    <div class="table-responsive">
                        
                        <div id="" class="dataTables_wrapper">
                            <!-- <div class="dataTables_wrapper dt-bootstrap4 no-footer"> -->
                                <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" role="grid" id="table-std_schedule_upload">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons">build</i></th>
                                            <th>ID</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Class</th>
                                            <th>Gender</th>
                                            <th>Birthday</th>
                                            <th>Religion</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Parent name</th>
                                            <th>Branch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>

                                        </tr>
                                    </tbody>
                                </table>
                            <!-- </div> -->
                        </div>
                    </div>
                
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- std schedule -->

    <!-- std result modal -->
    <div id="modal-std_result" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-size:18px;">Students</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper">
                            <!-- <div class="dataTables_wrapper dt-bootstrap4 no-footer"> -->
                                <table class="table table-striped" id="table-std_result">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Class</th>
                                            <th style="100px">Result</th>
                                            <th>Subject</th>
                                            <th><button id="saveMultipleResult_id" onclick="saveMultipleResult()"><i class="fa fa-save"></i> Submit</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            <!-- </div> -->
                        </div>
                    </div>
                
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- std schedule -->

    <!-- std small result modal -->
    <div id="modal-small_std_result" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-size:18px;">Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="" id="table-small_std_result">
                        <thead>
                            <tr>
                                <th id="th-exam-rate" class="col-xl-5 col-lg-5 col-12" style="position:relative;border-bottom:1px solid black;height:200px;">
                                </th>
                                <?php $rr = intval($examRate->class_work ?? 0); if($rr > 0) { ?> <th class="container_flip"><div class="flipvertical"><?php echo $examName->first_name ?> </div><div style="position:absolute;bottom:0px;right:8px;"> <?php echo($examRate->class_work ?? '') ?>%</div></th> <?php } ?>

                                <?php $rr_ = intval($examRate->mid_term_exam ?? 0); if($rr_ > 0) { ?> <th class="container_flip"><div class="flipvertical"> <?php echo $examName->second_name ?> </div><div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->mid_term_exam ?? '') ?>%</div></th> <?php } ?>
                                
                                <th class="container_flip">
                                    <div class="flipvertical"><?php echo $examName->third_name ?> </div>
                                    <div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->terminal_exam ?? '') ?>%</div>
                                </th>
                                <th class="container_flip">
                                    <div class="flipvertical">Final Score </div>
                                    <div style="position:absolute;bottom:0px;right:8px;"><?php echo(($examRate->class_work ?? 0) + ($examRate->mid_term_exam ?? 0) + ($examRate->terminal_exam ?? 0) ) ?>%</div>
                                </th>
                                <th class="container_flip">
                                    <div class="flipvertical">Final Grade </div>
                                    <div style="position:absolute;bottom:0px;right:8px;"></div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
                <h5 style="font-size:18px;">Student New/Edit</h5>
                <div id="period_name" style="padding-left:12px;padding-right:12px;font-size:18px;font-wight:bold;background-color:#cfcfcf;color:black;margin-left:8px;"></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Profile</a>
                    <a class="nav-link has-icon other-link" href="#page_2" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Subjects</span></a>
                    <a class="nav-link has-icon other-link" href="#page_3" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Result</span></a>
                    <a class="nav-link has-icon other-link" href="#page_4" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Document</span></a>
                    <a class="nav-link has-icon other-link" href="#page_5" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Attendance</span></a>
                    <a class="nav-link has-icon other-link" href="#page_6" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Services</span></a>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane show active" id="page_1">
                        <div class="row">
                            
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="std_code">ID *</label>
                                <input type="text" class="form-control form-control-lg" id="std_code" style="width: 100%"/>
                                <input type="hidden" id="doc_path" value="">
                                <input type="hidden" id="term_code" value="">
                                <input type="hidden" id="invoice_code" value="">
                                <input type="hidden" id="receipt_code" value="">
                                <input type="hidden" id="level_digit" value="">
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
                                <label for="class_name_code">Class </label>
                                <select class="form-control form-control-lg" id="class_code" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="class_name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group" id="dept_container" style="display: none;">
                                <label for="department">Department </label>
                                <select class="form-control form-control-lg" id="department" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="department--help">&nbsp;</code>
                            </div>
                            <!-- <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="admission_id">Admission id </label>
                                <input type="text" class="form-control form-control-lg" id="admission_id" style="width: 100%"/>
                                <code class="small text-danger" id="admission_id--help">&nbsp;</code>
                            </div> -->
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="section">Section </label>
                                 <select class="form-control form-control-lg" id="section" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="section--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="branch_code">Branch </label>
                                 <select class="form-control form-control-lg" id="branch_code" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="branch_code--help">&nbsp;</code>
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
                                <label for="parent_code">Parent </label>
                                <select type="text" class="form-control form-control-lg" id="parent_code" style="width: 100%"></select>
                                <code class="small text-danger" id="parent_code--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 px-3 mt-4 form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture" style="width:100%">
                                                </div>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="picture--help">&nbsp;</code>
                                    </div>
                                    <input type="file" id="picture-file" accept="image/*" onchange="imageChange({'event': event, preview:'picture', 'items': [$('#first_name').val(), $('#last_name').val()]})" style="display:none">
                                    <input type="hidden" id="picture" readonly>
                                </div>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalStudent({table: '#table-std', row: ''}); $('#modal-title').html('New Student')"><i class="fa fa-refresh"></i> Reset</button>
                                <button id="save-student" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveStudent({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                            <input id="std_code_old" style="display:none"/>
                        </div>
                    </div>
                    <div class="tab-pane" id="page_2">
                        
                        <div class="row">
                            <div class="table-responsive">
                                <div id="subj_senior" class="table_subject dataTables_wrapper">
                                    <button id="saveSubject_btn" class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveStudent({action: 'saveSubject', elem: event});"><i class="fa fa-plus"></i> Update</button>
                                    <table id="table-subj_senior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <!-- <th>Department</th> -->
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_senior", "action": "all", "elem": event})' /></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div id="subj_junior" class="table_subject dataTables_wrapper">
                                    <button id="saveSubject_btn" class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveStudent({action: 'saveSubject', elem: event});"><i class="fa fa-plus"></i> Update</button>
                                    <table id="table-subj_junior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_junior", "action": "all", "elem": event})' /></th>
                                                
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="tab-pane" id="page_3">
                        
                        <button type="button" class="std_report_btn" onclick="printStudentReport({std_code: [$('#std_code').val()], term: [$('#term_code').val()], export: 'PDF', db: 'student', single: '1' })" style=""><i class="fa fa-print"></i> Print</button>
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
                        <div class="row">
                            <div class="col-lg-4" style="position:absolute;right:2px;z-index:999">
                                <table style="border:1px solid black;font-weight:bold" id="table-social_beh">
                                </table>
                            </div>
                            <table id="table-exam-rate" class="col-12">
                                <thead>
                                    <tr>
                                        <th id="th-exam-rate" class="col-xl-5 col-lg-5 col-12" style="position:relative;">
                                            <div style="">KEY TO GRADE</div>
                                            <div>
                                                <?php 
                                                    foreach($examGrade as $k => $examGrade){
                                                        echo $examGrade->grade_name .'. '.''.$examGrade->comment.' = '. ''.$examGrade->percent_from.'%  - '. ''.$examGrade->percent_upto.'%' ;
                                                        echo "<br/>";

                                                    }
                                                ?>
                                            </div>
                                        
                                        </th>
                                        <?php $rr = intval($examRate->class_work ?? 0); if($rr > 0) { ?> <th class="container_flip"><div class="flipvertical"><?php echo $examName->first_name ?> </div><div style="position:absolute;bottom:0px;right:8px;"> <?php echo($examRate->class_work ?? '') ?>%</div></th> <?php } ?>

                                        <?php $rr_ = intval($examRate->mid_term_exam ?? 0); if($rr_ > 0) { ?> <th class="container_flip"><div class="flipvertical"> <?php echo $examName->second_name ?> </div><div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->mid_term_exam ?? '') ?>%</div></th> <?php } ?>
                                        
                                        <th class="container_flip">
                                            <div class="flipvertical"><?php echo $examName->third_name ?> </div>
                                            <div style="position:absolute;bottom:0px;right:8px;"><?php echo($examRate->terminal_exam ?? '') ?>%</div>
                                        </th>
                                        <th class="container_flip">
                                            <div class="flipvertical">Final Score </div>
                                            <div style="position:absolute;bottom:0px;right:8px;"><?php echo(($examRate->class_work ?? 0) + ($examRate->mid_term_exam ?? 0) + ($examRate->terminal_exam ?? 0) ) ?>%</div>
                                        </th>
                                        <th class="container_flip">
                                            <div class="flipvertical">Final Grade </div>
                                            <div style="position:absolute;bottom:0px;right:8px;"></div>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="" colspan="" style="position:relative;height:50px;border:1px solid black">
                                            <!-- <div style="position:relative;text-align:center;width:717.5px;height:50px;border:1px solid black;line-height:50px;font-size:18px;font-weight:bold;"> -->
                                               <button id="performance_btn" style="width:100px;height:100%;" onclick="savePerformance(event)"><i class="fa fa-save"></i> Save</button>
                                               <span style="position:absolute;left: 220px;top:15px">
                                                    Subjects
                                                </span>
                                            <!-- </div> -->
                                        </td>
                                        <td class="td_subject" style="border-right:1px solid black;border-bottom:1px solid black;" colspan="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="td_performance" colspan="">
                                            <table id="performance_table" style="border-left:1px solid black;border-right:1px solid black;width:100%">
                                            </table>
                                        </td>
                                        <td></td>
                                    </tr> 
                                    <tr>
                                        <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;height:50px;">
                                        
                                            ATTENDANCE RECORDS
                                        </td>
                                        <td class="td_subject" colspan="">

                                        </td>
                                        <td></td>
                                    </tr> 
                                    <tr>
                                        <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">
                                        
                                        </td>
                                        <td class="td_subject" colspan="">

                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="page_4">
                        
                        <a href="javascript:void(0)" onclick="$('#doc_path_file').click()" class="btn btn-bg btn-outline-primary mb-3"><i class="fa fa-plus"></i> Select File</a>
                        
                        <div class="dataTables_wrapper">
                            <table id="table-doc" class="table table-striped table-bordered table-sm nowrap w-100" style="cursor: pointer">
                                <thead>
                                    <tr>
                                        <th><i class="material-icons">build</i></th>
                                        <th>Filename</th>
                                        <th>Filesize</th>
                                        <th>Last Update</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <input type="file" id="doc_path_file" accept="<?php echo ACCEPT_FILE_TYPE ?>" onchange="if (($('#doc_path').val() ?? '') === '') alert('Save Changes to proceed'); else { modalLoadingDiv = $('#modal-std'); azureUpload({doc_path: uuidv4(), directory: 'mgt/' + $('#doc_path').val() + '/', item: '#doc_path_file', callback: 'loadDoc'}) }" style="display:none">
                    </div>
                    
                    <div class="tab-pane" id="page_5">
                        <!-- attendance -->
                        <div class="row">
                            <div class="col-lg-4 mt-2" style="">
                                <button id="save-attendance" onclick=saveTableAttendance(event)><i class="fa fa-save"></i> Save</button>
                                <table style="border:1px solid black;font-weight:bold;width:300px" id="table-attendance">
                                    <thead>
                                        <tr style="border-bottom:1px solid black">
                                            <!-- <th style="border-right:1px solid black;" width="462.5px">No. of days for the term</th>
                                            <th id="no_of_days_for_the_term" style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50" contenteditable="false"></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="border-bottom:1px solid black" id="no_of_days_absent">
                                            <td style="border-right:1px solid black;">No. of days absent</td>
                                            <td style="text-align:center;border-right:1px solid black;width:30px;overflow:hidden" contenteditable="true"></td>
                                        </tr>
                                        <tr style="border-bottom:1px solid black" id="no_of_days_present">
                                            <td style="border-right:1px solid black;">No. of days present</td>
                                            <td style="text-align:center;border-right:1px solid black;width:30px;overflow:hidden" contenteditable="true"></td>
                                        </tr>
                                        <tr style="border-bottom:1px solid black" id="no_of_days_late">
                                            <td style="border-right:1px solid black;">No. of days late</td>
                                            <td style="text-align:center;border-right:1px solid black;width:30px;overflow:hidden" contenteditable="true"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div> 
                        </div>
                        <!-- attendance -->

                    </div>
                    <div class="tab-pane" id="page_6">
                        <div>
                            <button id="save_activity_btn" class="btn-fill-lg bg-blue-dark btn-hover-yellow" style="float:left;" onclick="saveActivities();"><i class="fa fa-save"></i> Update</button>
                            <!-- <button type="button" id="print_invoice_btn" class="invoice_print" style="float:left;"  onclick="printInvoice({invoice_code: [$('#invoice_code').val()], term: [$('#term_code').val()], export: 'PDF', db: 'student', single: '1' })"><i class="fa fa-print"></i> Invoice</button> -->
                            <button type="button" id="print_invoice_btn" class="invoice_print" style="float:left;"  onclick="printInvoice({invoice_code: [$('#invoice_code').val()], term: [$('#term_code').val()], export: 'PDF', db: 'student', single: '1' })"><i class="fa fa-print"></i> Invoice</button>
                            <button type="button" id="gen_invoice_btn" class="gen_invoice_btn" style="float:right;"  onclick="createInvoiceSingle({std_code: [$('#std_code').val()] })" ><i class="fa fa-bible"></i> Create Invoice</button>
                            <!-- <button type="button" id="create_receipt_btn" class="gen_invoice_btn" style="float:right;"  onclick="createInvoiceSingle({receipt_code: [$('#receipt_code').val()] })" ><i class="fa fa-print"></i> Print Receipt</button> -->
                        </div>
                        <!-- attendance -->
                        <div class="row">
                            <div class="plist">
                                        
                            </div>
                        </div>
                        <!-- attendance -->
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    let examGrade_ = <?php echo json_encode($data['examGrade']) ?>;
    let socialBehaviour_ = <?php echo json_encode($data['socialBehaviour']) ?>;
    let examRate_ = <?php echo json_encode($data['examRate']) ?>;
    let examName = <?php echo json_encode($data['examName'] ?? []) ?>;
    let max_key_val_ = <?php echo json_encode($data['max_key_val']) ?>;
    let max_percent_upto_ = <?php echo json_encode($data['max_percent_upto']) ?>;
    let levels = <?php echo json_encode($data['levelsobj']) ?>;
    let classes = <?php echo json_encode($data['classesobj']) ?>;
    let classes2 = <?php echo json_encode($data['classesobj2']) ?>;
    let parentObj = <?php echo json_encode($data['parentObj']) ?>;
    let branchObj = <?php echo json_encode($data['branch']) ?>;
    let term = <?php echo json_encode($data['term']) ?>;
    let termObj = <?php echo json_encode($data['termObj']) ?>;
    let classObj = <?php echo json_encode($data['classesobj']) ?>;
    let params = <?php echo json_encode($data['params']) ?>;
    let tableStudents = null;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    let classWork = examRate_.class_work ?? 0;
    let midTermExm = examRate_.mid_term_exam ?? 0;
    let student_level_number = 0;
    let rowIndex = 0;
    // console.log(term)
    
    
    //
    let deleteStudent = (json) => {
        // console.log(json);return;
        tableStudents = $("#table-std").DataTable();
        
        if (!confirm('You are about to delete ' + json.std_code.length + ' student(s)') || json.std_code.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/students/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', json, function (data) {
            //console.log(data);
            if (data.status === true) {
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                tableStudents.ajax.reload(null, false);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.status, timeout: 10000}).show();
            //
            
        }, 'JSON');
    }

    let register = (e) =>{
        let id = e.target.id

        if(id === "" || id === "cancel"){
            $(e.target).attr("id", "register");
            $(e.target).attr("class", "register");
            $(e.target).html("Cancel");
        }else if(id === "register"){
            $(e.target).attr("id", "cancel");
            $(e.target).attr("class", "cancel");
            $(e.target).html("Register");

        }
        // console.log(e)
    }

    let saveMultipleResult = ()=>{
        let elems = $("#table-std_result tbody").find('tr');
        let students = [];
        $.each(elems, (k, tr)=>{
            let obj = {};
            let std_code = $(tr).find('td:eq(0)').html();
            let result = String(decodeURI($(tr).find('td:eq(4) input').val())).replace(/[\/]/, '');
            obj['std_code'] = std_code;
            obj['term_code'] = term.code;
            obj['subj_result'] = result;
            students.push(obj);
        })
        let data_ = {result: JSON.stringify(students)}
        // console.log(data_)
        $("#saveMultipleResult_id").html('<i class="fa fa-spinner fa-spin"></i> Save').prop({disabled: true});
        // return;
        $.post("<?php echo URL_ROOT ?>/school/students/saveMultipleResult/?user_log=<?php echo $data['params']['user_log'] ?>", data_, (data)=>{
            console.log(data);
            if(data.status){
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                $("#saveMultipleResult_id").html('<i class="fa fa-save"></i> Save').prop({disabled: false});
                return;
                
            }
            new Noty({type: 'warning', text: '<h5>WARNING!</h5>'+data.message, timeout: 10000}).show();
            $("#saveMultipleResult_id").html('<i class="fa fa-save"></i> Save').prop({disabled: false});
            
        }, "JSON")
    }

    let createResult = (json)=>{
        // console.log(json)
        $("#table-std_result tbody tr").remove();
        tableStudents = $('#table-std').DataTable();
        let students = [];
        tableStudents.rows().every(function () {
            let data = this.data();
            $.each(json.std_code, (k, v)=>{
                if(data.std_code === v){
                    // console.log(v);
                    students.push(data);
                    // console.log(this);
                }
            })
        });
        let html = '<tr>';
        $.each(students, (k, v)=>{
            // console.log(v);
            let subjects_result = String(v.subject_result).replace(/[\/]/, '');
            html += '<td style="width:200px">'+ (v.std_code) +'</td>\
            <td style="width:200px">'+ (v.first_name) +'</td>\
            <td style="width:200px">'+ (v.last_name) +'</td>\
            <td style="width:200px">'+ (v.cat_name + " "+ v.class_name) +'</td>\
            <td style="width:200px;"><input type="text" value='+encodeURI(v.subject_result ?? '')+' /></td>\
            <td><input type="text" value='+encodeURI(v.subjects ?? '')+'/></td>\
            <td><i class="fa fa-link" onclick="setSubjectResult({row: $(this).closest(\'tr\').index()})" style="cursor:pointer"></i></td>\
            </tr>';
        });
        $("#table-std_result tbody").append(html);
        $("#modal-std_result").modal("show");
    }

    let setSubjectResult = (json)=>{
        rowIndex = json.row;
        // console.log(rowIndex);
        let row = $("#table-std_result tbody").find('tr:eq('+json.row+')');
        let subj = String($(row).find('td:eq(4) input').val()).replace(/[\/]/, '');
        let res = String($(row).find('td:eq(5) input').val()).replace(/[\/]/, '');
        if(subj === '' || res === '')return;
        // console.log(JSON.res);
        // return;
        let subject_res = JSON.parse(decodeURI(subj));
        let subjects = JSON.parse(decodeURI(res));
        $('#table-small_std_result tbody tr').remove();
        // console.log(subject_res);
        ////
        $.each(subjects, (k, v)=>{
            let result = subject_res[k] ?? '';
            // console.log(result);
            let td = classWork > 0 ? '<td class="container_flip2" id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="calcgrade(event)">'+ (result[examName.first_name] ?? '') +'</td>' : '';
            let td_ = midTermExm > 0 ? '<td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="calcgrade(event)">'+ (result[examName.second_name] ?? '') +'</td>' : '';
            
            let html = '\
            <tr style="border-bottom:1px solid black">\
                <td style="border-right:1px solid black;font-weight:bold;">'+ k +'</td>'+td+'\
                '+td_+'\
                <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true" onkeyup="calcgrade(event)">'+ (result[examName.third_name] ?? '' )+'</td>\
                <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result['Final Score'] ?? '' )+'</td>\
                <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden;border-right:1px solid black;" contenteditable="true">'+ (result['Final Grade'] ?? '' )+ '</td>\
            </tr>';
            $('#table-small_std_result tbody').append(html);
            //
        })
        // console.log(rowIndex)
        $('#modal-std_result').modal('hide');
        $('#modal-small_std_result').modal('show');
        
    }

    let saveActivities = ()=>{
        let obj = {};
        let std = $("#std_code").val();
        let elems = $(".register");
        $.each(elems, (k, v)=>{
            console.log(v)
            obj[v.dataset.product_code] = {
                product_name: v.name,
                product_code: v.dataset.product_code,
                product_price: v.dataset.product_price,
                desc: v.value,
            }
        })

        // console.log(obj);return;

        $.post('<?php echo URL_ROOT ?>/school/students/activities/?user_log=<?php echo $data['params']['user_log'] ?>', {std_code: std, activities: JSON.stringify(obj)}, (data)=>{
            // console.log(data);
            if(data.status){
                new Noty({type: 'success', text:'<h5>SUCCESSFUL</h5>', timeout: 10000}).show();
                return;

            }
            new Noty({type: 'warning', text: '<h5>WARNING</h5> '+data.message, timeout: 10000}).show();
        }, 'JSON')
        // console.log(obj);
    }

    let modalStudent = (json) => { 
        tableStudents = $(json.table).DataTable();
        //let data = json.row === '' ? {} : ( json.modalAuto ? tableStudents.row(json.row).data() : json.row); // data["colName"]
        let data = json.row === '' ? {} : json.row.data(); // data["colName"]
        // console.log(data)
        $('#period_name').html('');
        if((data.period_name ?? '') !== '')
         $('#period_name').html(data.period_name +" remains: "+ ((data.days_remains < 0) ? 'no ' : data.days_remains) + " days")
        $('#std_code').val(data['std_code'] ?? 'AUTO').prop("disabled", (((data['std_code'] ?? '') !== '') && ((data['std_code'] ?? '') !== 'AUTO'))  ? true : false );
        $('#std_code_old').val(data['std_code'] ?? '');
        $('#term_code').val(data['term'] ?? '');
        $('#invoice_code').val(data['invoice_code'] ?? '');
        $('#receipt_code').val(data['receipt_code_table'] ?? '');
        if((data['department'] ?? '') === ''){
            $("#dept_container").css("display", "none");
        }else{
            // $("#dept_container").css("display", "none");
            $("#dept_container").css("display", "block");
        }
        $('#level_digit').val(parseInt(data['digit'] ?? 0));
        $('#department').append(new Option(data['department'] ?? '', (data['department'] ?? '' ), true, true)).trigger('change');
        $('#class_code').append(new Option((data['cat_name'] ?? '') +'-'+ (data['class_name'] ?? '') , (data['class_code'] ?? '' ), true, true)).trigger('change');
        $('#first_name').val(data['first_name'] ?? '');
        $('#last_name').val(data['last_name'] ?? '');
        $('#gender').append(new Option(data['gender'] ?? "" , data['gender'] ?? "", true, true)).trigger('change');
        $('#birthday').val( (data['birthday'] ?? moment().format('YYYY-MM-DD')).slice(0, 10) ).prop({disabled: (data['birthday'] ?? '') != '' });
        $('#roll').val(data['roll']);
        $('#blood_group').append(new Option(data['blood_group'] ?? '', data['blood_group'] ?? '', true, true)).trigger('change');
        $('#religion').append(new Option(data['religion'] ?? "", data['religion'] ?? "" , true, true)).trigger('change');
        $('#email').val(data['email'] ?? '');
        $('#section').append(new Option(data['section'] ?? "", data['section'] ?? "", true, true)).trigger('change');
        $('#branch_code').append(new Option(data['branch_name'] ?? "", data['branch_code'] ?? "", true, true)).trigger('change');
       // $('#admission_id').val(data['admission_id']);
        $('#phone').val(data['phone']);
        $('#parent_code').append(new Option(data['parent_name'] ?? '', data['parent_code'] ?? '', true, true)).trigger('change');
        $('#address').val(data['address'] ?? '');
        $('#doc_path').val(data['doc_path'] ?? '');
        
        let pics = data['picture'] ?? '';
        pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
        //
        $('#picture--preview').attr('src', pics);
        //
        $('#picture').val(data['picture'] ?? '');
        //
        // console.log(data);return;
        $('#modal-std').modal('show');

        if((data['receipt_code_table'] ?? '') !== ''){
            $('#gen_invoice_btn').css({display: 'none'})
            $('#print_invoice_btn').css({display: 'none'})
            $('#save_activity_btn').css({display: 'none'})
            $('#create_receipt_btn').css({display: ''})
        }else{
            $('#gen_invoice_btn').css({display: ''})
            $('#print_invoice_btn').css({display: ''})
            $('#save_activity_btn').css({display: ''})
            $('#create_receipt_btn').css({display: 'none'})

        }

        $('#modalNav a[href="#page_1"]').tab('show');
        // console.log(data['std_code']);return
        $('.other-link').each((k, v)=>{
            $(v).css({display: data['std_code'] === undefined ? 'none' : 'inline-block'})
            $(v).prop({disabled: data.days_remains < 0})
        })
        $("#invoice_btn").css({display: data['std_code'] === undefined ? 'none' : ''});
        
        if(data['std_code'] === undefined)return;
        $("#no_of_days_for_the_term").html(term.days_remains);
        loadSubject(data);
        // console.log(no_of_days)
        
        //
    }

    let total_score = 0;
    let calcgrade = (e)=>{
        // rowIndex = $(e.target).closest('tr').index();
        let elems = [...$(e.target).closest('tr').children()]; 
        let len = elems.length-1;
        let len2 = len-1;
        let last_td = $(e.target).closest('tr').find("td:last");
        total_score = 0;
        $(e.target).closest('tr').find('td:eq('+len2+')').html('');
        $.each(elems, (k, td)=>{
            if(k === 0 || k === len)return;
            let text = $(td).html();

            let v = String(text).replace(/[^0-9\.]/g, '');
            let score = parseFloat("0"+ v);
            total_score += score;

            if(k === len2){
                $(e.target).closest('tr').find('td:eq('+len2+')').html(total_score);

            }
            
            $.each(examGrade_, (k, vv)=>{
                let from = parseFloat(vv.percent_from)
                let upto = parseFloat(vv.percent_upto)
                if(total_score >= from && total_score <= upto){
                    let td__  = $(e.target).closest('tr').find('td:last')['0'];
                    td__.innerHTML = vv.grade_name
                    // console.log(td__);
                    return false;
                }
            })

        })
    }

    let saveTableAttendance = (e)=>{
        let table_tr = $("#table-attendance").find("tr");
        let std_code = $("#std_code").val();
        let data = {std_code: std_code};
        let attendance = {};
        $.each(table_tr, (k, v)=>{
            let vv = $(v).attr("id");
            let vvv = $(v).find("td:eq(1)").html();
            attendance[vv] = vvv
        })
        data['attendance'] = JSON.stringify(attendance);
        // console.log(data);return;
        $("#save-attendance").html('<i class="fa fa-spinner fa-spin"></i>');
        $("#save-attendance").prop({disabled: true});
        $.post("<?php echo URL_ROOT ?>/school/students/saveTableAttendance/?user_log=<?php echo $data['params']['user_log'] ?>", data, (data)=>{
            // console.log(data);
            if(!data.status){
                new Noty({type:'warning', text: '<h5>WARNING</h5>'+data.message, timeout: 10000}).show();
                
                $("#save-attendance").html('<i class="fa fa-save"></i> Save');
                $("#save-attendance").prop({disabled: false});
                return;
            }
            new Noty({type: 'success', text:'<h5>SUCCESS</h5>SUCCESSFULLY SAVED', timeout: 10000}).show();
            $("#save-attendance").html('<i class="fa fa-save"></i> Save');
            $("#save-attendance").prop({disabled: false});

        }, 'JSON');
    }

    let loadProducts = (json)=>{
        // console.log(json.activities);return
        let activities = JSON.parse(json.activities);
        
            let html = '';
            $.post('<?php echo URL_ROOT ?>/system/systemSetting/getProducts', {_option: 'cat_code', cat_code: json.cat_code}, (data)=>{
                $.each(data.data, (k, v)=>{
                    let elems = '\
                        <div class="bpItem">\
                        <img src="'+v.picture+'" class="fpImg"/>\
                        <div class="fpName">"'+v.product_name+'"</div>\
                        <div class="fpPrice">'+number_format(v.price)+'</div>\
                        <p class="detail">\
                        '+ v.description +'\
                        </p>\
                        <div class="spaction">\
                            <button id="'+ ((activities[v.product_code] ?? '') !== '' ? 'register' : '') +'" data-product_code='+v.product_code+' value="'+v.description+'" data-product_price='+v.price+' name="'+v.product_name+'" class="'+ ((activities[v.product_code] ?? '') !== '' ? 'register' : '') +'" onclick="register(event)">'+ ((activities[v.product_code] ?? '') !== '' ? 'Cancel' : 'Register')+'</button>\
                        </div>\
                    </div> ';
                    html += elems;
                })
                $(".plist").html(html);
                
            }, 'JSON')
            // console.log(html)
            
        loadDoc(json);
    }

    let loadTableAttendance = (json)=>{
        let fff = (json.attendance ?? '') === '' ? '{}' : json.attendance;
        let attendance = JSON.parse(fff);
        // console.log(attendance);return;
        
        $.each(attendance, (k, v)=>{
           let ddd = $("#"+k);
           $(ddd).find("td:eq(1)").html(v)
            // console.log(k, v);
        })
        loadProducts(json);

    }

    let loadSocialBehaviour = (json)=>{
        let social_beh = JSON.parse((json.social_beh ?? '') === '' ? '{}' : json.social_beh);
        $('#table-social_beh').html('');
        $('#table-social_beh').append('<tr><td style="position:relative;text-align:center;width:350px;border:1px solid black"><button style="position:absolute;left:0px;top:0px;" onclick="saveSocialBehaviour(event)" id="save_social"><i class="fa fa-save"></i> </button>SOCIAL BEHAVIOUR</td><td style="text-align:center;border:1px solid black">RATING</td></tr>')
        // console.log(socialBehaviour_)
        $.each(socialBehaviour_, (k, v)=>{
            let html = '\
            <tr>\
                <td style="border:1px solid black;">'+v['behaviour']+'</td>\
                <td style="text-align:center;border:1px solid black" contenteditable="true">'+(social_beh[v['behaviour']] ?? '')+'</td>\
            </tr>';
            $('#table-social_beh').append(html);
        })
        loadTableAttendance(json);
    }

    let saveSocialBehaviour = (e)=>{
        let id = $(e.currentTarget).prop('id')
        $('#'+id).html('<i class="fa fa-spinner fa-spin" style="white"></i>');
        $('#'+ id).prop({disabled: true});
        // console.log(id);return;
        let std = $('#std_code').val();
        let std_code = $('#std_code').val();
        let class_code = $('#class_code').val();
        let year = $('#year').val();
        let term = $('#term').val();
        let student = {std_code: std_code};
        let elem = [...$($(e.currentTarget).closest('table')).find('tr')];
        elem.shift()
        let obj = {};
        $.each(elem, (k, v)=>{
            let behaviour = $(v).find('td:eq(0)')["0"].innerText;
            let value = String($(v).find('td:eq(1)')["0"].innerText).replace(/[^0-9\.]/g, '');
            obj[behaviour] = value;
        })
        let data_ = {social_beh: JSON.stringify(obj), std: student};
        
        $.post('<?php echo URL_ROOT ?>/school/students/saveSocialBehaviour/?user_log=<?php echo $data['params']['user_log'] ?>', data_, (data)=>{
            // console.log(data);
            if (!data.status) {
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    $('#'+id).html('<i class="fa fa-save"></i> ');
                    $('#'+ id).prop({disabled: false});
                    return false;
             }
                //
                // console.log(data.status)
                // saving = true;
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 1000}).show();
                $('#'+id).html('<i class="fa fa-save"></i> ');
                $('#'+ id).prop({disabled: false});
    
        }, 'JSON')
        // console.log(obj);
    }

    let createstdscheduleRow = (json) => {
        // console.log(json.data);return
        let tbody = $(json.table).find('tbody');
        // let keys = Object.keys(json.data);
        // console.log(keys)
        let id, first_name, last_name, gender, birthday, blood_group, religion, email, class_, address, parent_name;
        id = (json.data['ID'] ?? '') !== '' ? json.data['ID'].replace(/[^0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        first_name = (json.data['First name'] ?? '') !== '' ? json.data['First name'].replace(/[^0-9a-zA-Z_\s\.]/g, '').trim().toUpperCase() : '';
        last_name = (json.data['Last name'] ?? '') !== '' ? json.data['Last name'].toString().replace(/[^0-9a-zA-Z_\s\.]/g, '').trim().toUpperCase() : ''; '';
        gender = (json.data['Gender'] ?? '') !== '' ? json.data['Gender'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        birthday = (json.data['Birthday'] ?? '') !== '' ? json.data['Birthday'].toString().replace(/[^:0-9a-zA-Z_\s-\/]/g, '').trim().toUpperCase() : '';
        //roll = (json.data['Roll'] ?? '') !== '' ? json.data['Roll'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        blood_group = (json.data['Blood group'] ?? '') !== '' ? json.data['Blood group'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        religion = (json.data['Religion'] ?? '') !== '' ? json.data['Religion'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        email = (json.data['Email'] ?? '') !== '' ? json.data['Email'].toString().replace(/[^:0-9a-zA-Z_\s@\.]/g, '').trim().toUpperCase() : '';
        class_ = (json.data['Class'] ?? '') !== '' ? json.data['Class'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
       // admission_id = (json.data['Admission id'] ?? '') !== '' ? json.data['Admission id'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        address = (json.data['Address'] ?? '') !== '' ? json.data['Address'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        parent_name = (json.data['Parent name'] ?? '') !== '' ? json.data['Parent name'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        branch_name = (json.data['Branch'] ?? '') !== '' ? json.data['Branch'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        let classObj = classes2[class_] ?? {};
        let parent = parentObj[parent_name] ?? {};
        let branch = branchObj[branch_name] ?? {};
        // console.log(parent_name)

        // birthday = moment(birthday, 'DD/MM/YYYY').format('YYYY-MM-DD');
        //
        let html = '<tr>\
        <td>\
            <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">\
                <i class="fa fa-cog"></i>\
            </a>\
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">\
                <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                <a class="dropdown-item" href="#"  onclick="removeRow({table: \''+json.table+'\', elem: event})">\
                    <i class="fas fa-trash text-orange-peel"></i>Remove\
                </a>\
            </div>\
        </td>';
        $.each(upload_fields, function (k, v) {
            if(v === 'ID'){
                 html += '<td><input id="std_code" class="" value="'+ id +'" /></td>';

            }else if(v === 'First name'){
                 html += '<td><input style="width:200px" id="first_name" value="'+ first_name +'" readonly/></td>';

            } else if(v === 'Last name'){
                 html += '<td><input style="width:200px" id="last_name" value="'+ last_name +'" readonly></td>';

            }else if(v === 'Gender'){
                 html += '<td><select style="width:100%" id="gender-'+json.row+''+k+'"><option value="'+gender+'" selected >'+gender+'</option></select></td>';

            }else if(v === 'Birthday' ){
                 html += '<td><input id="birthday-'+json.row+''+k+'" value="'+birthday+'" /></td>';

            }else if(v === 'Religion'){
                 html += '<td><select id="religion-'+json.row+''+k+'" style="width:100%"><option value="'+religion+'" selected >'+religion+'</option></select></td>';

            }else if(v === 'Email'){
                 html += '<td><input id="email" value="'+ email +'" /></td>';

            }
            else if(v === 'Class'){
                 html += '<td><select style="width:100%" id="class_code-'+json.row+''+k+'"><option value="'+(classObj.class_code ?? '')+'" selected >'+class_+'</option></select></td>';

            }
            else if(v === 'Address'){
                 html += '<td><input id="address" value="'+ address +'" /></td>';

            }else if(v === 'Parent name'){
                 html += '<td><select id="parent_code-'+json.row+''+k+'" style="width:100%" ><option value="'+parent.parent_code+'" selected >'+parent_name+'</option></select></td>';

            }else if(v === 'Branch'){
                 html += '<td><select id="branch_code-'+json.row+''+k+'" style="width:100%" ><option value="'+(branch.branch_code ?? '')+'" selected >'+branch_name+'</option></select></td>';

            }
        })
        html += '</tr>';
        tbody.append(html);
        // //////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(4) > select#class_code-'+json.row+'3').select2({
            placeholder: "Please Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log(params)
                    return {
                        searchTerm: params.term,
                        _option2: 'select'
                    };
                },
                processResults: function (response) {
                    //console.log(response);
                    return { results: response };
                },
                cache: true
            }
        });

        //////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(7) > select#religion-'+json.row+'6').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getReligions/?user_log=<?php echo $data['params']['user_log'] ?>",
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

        ////////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(10) > select#parent_code-'+json.row+'9').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getParents",
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
        ////////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(5) > select#gender-'+json.row+'4').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getGenders",
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
        
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(11) > select#branch_code-'+json.row+'10').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getBranches",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        _option: 'select',
                    };
                },
                processResults: function (response) {
                    //console.log(response);
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        ////
        flatpickr($(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(6) > input#birthday-'+json.row+'5'), {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            // maxDate: new Date().fp_incr(0), // -92
        });

    }

    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");

        $("#"+table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    let saveStdScheduleUpload = (json) =>{
        $('#save-import_std_schedule').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');

        if(!confirm("Do you want to perform this operation")) return;
        // console.log("scared");return;
        let tr = $(json.table + ' tbody').find('tr');
        tr.each((k, v)=>{
            $(v).find('td:eq(0)').find('a').css('color', '#167bea');
        })
        let rows = [];
        $(json.table + ' tbody > tr').each((k, tr)=>{
            //
            // console.log();
            let row = {};
            let td = [...tr.children];
            td.forEach((v, k)=>{
                
                let tag_name = v.children[0].tagName;
                let id = String(v.children[0].id);
                let key = id.substring(0, (id.indexOf('-') === -1 ) ? id.length :  id.indexOf('-') );
                if(tag_name === 'A'){

                }else if(tag_name === 'INPUT'){
                    row[key] = String(v.children[0].value).trim();
                }else if(tag_name === 'SELECT'){
                    let select_ = v.children[0] ?? '<select></select>';
                    let option_ = $(select_)["0"].selectedOptions[0] ?? '<option></option>';
                    let vvv = $(option_)["0"].value ?? '';
                    
                    row[key] = String(vvv).trim();
                    // console.log(vvv)
                    // row[key] = String(v.children[0].selectedOptions[0].value).trim();

                }
            })
            rows.push(row);
            
        });
        // console.log(rows);return;
        let data = {data: JSON.stringify(rows)};
        $.post('<?php echo URL_ROOT ?>/school/students/__save/?user_log=<?php echo $data['params']['user_log'] ?>', data, (data)=>{
                if(!data.status){
                    $(json.table + ' tbody > tr:eq('+data.key+') td:eq(0) a').css("color", "red");
                    $('#save-import_std_schedule').html('<i class="fa fa-save"></i>Save');
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    $($(json.table + ' tbody').find('tr:eq('+data.rowNo+') td:eq(0)')).css("background-color", "red");
                    return false;
                }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            $('#save-import_std_schedule').html('<i class="fa fa-save"></i>Save');
                // console.log(data.key);
        }, 'json')
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = (json) => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        
        let selectedStd = localStorage.getItem('selected-std');
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        if (selectedStd !== '' && modalOpen) {
            // localStorage.setItem('modalOpen', '');
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
            // console.log(json);
        let subject = (json.subjects ?? '') === '' ? {} : JSON.parse(json.subjects);
        // console.log(json);
        let subject_result = (json.subject_result ?? '') === '' ? {} : JSON.parse(json.subject_result);
        // console.log(subject_result)
        $('.table_subject').css("display", "none");
        let data = {class_code: json.class_code, department: json.department};
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/getMapping/?user_log=<?php echo $data['params']['user_log'] ?> ";
        if((json.department ?? '') === ''){
            $('#subj_junior').css("display", "");
            if((json['receipt_code_table'] ?? '') !== ''){
                $('#subj_junior').find('button#saveSubject_btn').css({display: 'none'})
            }else{
                $('#subj_junior').find('button#saveSubject_btn').css({display: ''})

            }
            
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
                    //  console.log(json);
                    //  modalAuto();
                    
                    ////
                    $.each(subject, (k, v)=>{
                        let result = subject_result[k] ?? '';
                        let td = classWork > 0 ? '<td class="container_flip2" id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result[examName.first_name] ?? '') +'</td>' : '';
                        let td_ = midTermExm > 0 ? '<td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result[examName.second_name] ?? '') +'</td>' : '';
                        // console.log(result);
                        let html = '\
                        <tr style="border-bottom:1px solid black">\
                            <td style="border-right:1px solid black;font-weight:bold;">'+ k +'</td>'+td+'\
                            '+td_+'\
                            <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result[examName.third_name] ?? '' )+'</td>\
                            <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result['final_score'] ?? '' )+'</td>\
                            <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden" contenteditable="true">'+ (result['final_grade'] ?? '' )+ '</td>\
                        </tr>';
                        $('#performance_table').append(html);
                        // console.log(sub)
                    })
                }
            });
            
            loadSocialBehaviour(json);

        }else{
            $('#subj_senior').css("display", "");
            if((json['receipt_code_table'] ?? '') !== ''){
                $('#subj_senior').find('button#saveSubject_btn').css({display: 'none'});
            }else{
                $('#subj_senior').find('button#saveSubject_btn').css({display: ''});
            }
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
                    {"data": "science", "render": function(data, type, row, meta){
                        return (data ?? '') + "<input type=\"checkbox\" id="+data +"  "+((subject[row['subject_name']] ?? '') !== "" ? "checked" : (data === "COMPULSORY" ? "checked" : '') ) +" onclick='adder({\"row\": "+ meta['row'] +", \"table\": \"#table-subj_senior\", \"elem\": event})'/> ";
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
                    $.each(subject, (k, v)=>{
                        let result = subject_result[k] ?? '';
                        let td = classWork > 0 ? '<td class="container_flip2" id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result[examName.first_name] ?? '') +'</td>' : '';
                        let td_ = midTermExm > 0 ? '<td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result[examName.second_name] ?? '') +'</td>' : '';
                        // console.log(result);
                        let html = '\
                        <tr style="border-bottom:1px solid black">\
                            <td style="border-right:1px solid black;font-weight:bold;">'+ k +'</td>'+td+'\
                            '+td_+'\
                            <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result[examName.third_name] ?? '' )+'</td>\
                            <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden" contenteditable="true">'+ (result['final_score'] ?? '' )+'</td>\
                            <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden" contenteditable="true">'+ (result['final_grade'] ?? '' )+ '</td>\
                        </tr>';
                        $('#performance_table').append(html);
                        // console.log(sub)
                    })
                }
            });
            loadSocialBehaviour(json);
        }
        
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saveStudent = (json) => {
        let dept = $("#department").val() ?? '';
        let dept_cont = $("#dept_container").css("display");
        // return;
        //
        if(json.action === 'saveSubject'){
            
            let rows = {};
            let el, tr;
            let std_code = $('#std_code').val();
            let student = {std_code: std_code};
            el = $($(json.elem.target).closest('div')).find('table');
            let table = $(el).DataTable();
            tr = $(el).find('tbody tr');
            if(!table.data().any()) return;
            $.each(tr, (k, v)=>{
                //save the subject name only
                obj = {};
                let td_subject = $(v).find('td:eq(0)')[0];
                let td_checkbox = $($(v).find('td:eq(2)')).find('input')[0];

                let status = $($(v).find('td:eq(2)'))[0].childNodes[0].data ?? '';
                if(!td_checkbox.checked)return;
                // console.log(status);return;
                if(status === ''){
                    rows[td_subject.innerText] = (td_checkbox.checked ?? '') ? 'COMPULSORY' : 'ELECTIVE';
                    return;
                }
                rows[td_subject.innerText] = (td_checkbox.checked ?? '') ? status : '';
                // rows.push(obj);
            })
            saving = true;
            // console.log(rows);return;
            $.post('<?php echo URL_ROOT ?>/school/students/saveSubject/?user_log=<?php echo $data['params']['user_log'] ?>',{data: JSON.stringify(rows), std: student}, function (data) {
                //
                if (!data.status) {
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                }
                saving = false;
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $('#modal-std').modal("hide");
                setTimeout(() => {
                    modalAuto({page: "2"});
                }, 1000);
            
            },'JSON');

            return
        }
        
        if(dept === '' && dept_cont === 'block'){
            // console.log(dept_cont)
            new Noty({type:"warning", text: "<h5>WARNING</h5>Department cannot be empty", timeout:10000}).show();
            return;
        }
        let form_data = new FormData()
        $.each($('#modal-std #page_1').find('input, select, textarea'), function (i, obj) {
            //
            // console.log(obj);return;
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                // form_data.append(obj['id'].replace('user', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
                form_data.append(obj['id'], ($('#' + obj['id']).prop('checked') ? "1" : "0"));
            }
            //
            else if ($('#' + obj['id']).prop('type') == 'file') {
                //
                $.each(obj.files, function (j, file) {
                    //
                    form_data.append(obj['id'], file);
                })
            }
            //
            else {
                form_data.append(obj['id'], obj['value']);
            }
            
        });
        tableStudent = $(json.table).DataTable();
        
        if ($('#save-student').prop('disabled')) return false;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/students/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-student').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-student').prop({disabled: true});
                //
                saving = true;
            }
        })
        // using the done promise callback
        .done(function (data, textStatus, jqXHR) {
            //
            saving = false;
            //
            $('#save-student').html('Save Changes');
            $('#save-student').prop({disabled: false});
            
            if (data.status) {
                //
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                $('#modal-std').modal("hide");
                localStorage.setItem("modalOpen", "1");
                localStorage.setItem("selected-std", data.std_code ?? '');
                setTimeout(() => {
                    modalAuto({page: "1"});
                }, 1000);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
            // loadStudent({});
            // $('#modal-std').modal("hide");
            // setTimeout(() => {
            //     modalAuto({page: "1"});
            // }, 1000);
            
        }) 
        // process error information
        .fail(function (jqXHR, textStatus, errorThrown) {
            
            saving = false;
            
            // log data to the console so we can see
            //console.log(errorThrown);
            $('#save-student').html('Save Changes');
            $('#save-student').prop({disabled: false});
            //
            new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
            
        });
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
    //
    let std_schedule_upload_fields = ()=>{
        let td_collection = [...$("#table-std_schedule_upload thead").find('tr')["0"].children];
        td_collection.shift();
        $.each(td_collection, (k, v)=>{
            let v_ = $(v).html();
            upload_fields.push(v_)

        })
    }

    //
    let savePerformance = (e)=>{
        
        let id = $(e.target).prop('id')
        // console.log(id);return;
        
        let std = $('#std_code').val();
        let std_code = $('#std_code').val();
        // let cat_code = $('#cat_code').val();
        let class_code = $('#class_code').val();
        let year = $('#year').val();
        let term = $('#term').val();
        let student = {std_code: std_code};
        let obj = {};
        // console.log(std);return;
        let tr = $('#performance_table').find('tr');
        let thead_th = $('#table-exam-rate thead tr').find('th:not(:last-child)');
        let thead = [];
        $.each(thead_th, (k, v)=>{
            if(k === 0)return;
            let c = $(v).find('div:first-child').html().trim();
            thead.push(c)
            // console.log(c)
        })
        // console.log(thead);return;
        //
        $.each(tr, (k, v)=>{
            let td_body = $(v).find('td');
            let o = '';
            $.each(td_body, (k, tbody_v)=>{
                if(k === 0){
                    o = $(tbody_v).html();
                    obj[o] = {};
                    return;
                };
                obj[o][thead[k-1]] = $(tbody_v).html()
                // console.log($(tbody_v).html());

            })
        })
            // console.log(obj, student);
            // return;
        $('#'+id).html('<i class="fa fa-spinner fa-spin" style="white"></i> Save Changes');
        $('#'+ id).prop("disabled", false);
        saving = true;
        ////
        $.post('<?php echo URL_ROOT ?>/school/students/saveSubjResult/?user_log=<?php echo $data['params']['user_log'] ?>', {data: JSON.stringify(obj), std: student}, (data)=>{
             //
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                $('#'+id).html('<i class="fa fa-save"></i> Save');
                $('#'+ id).prop({disabled: false});
                saving = false;
                return false;
             }
                //
            // console.log(data.status)
            saving = false;
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 1000}).show();
            $('#'+id).html('<i class="fa fa-save"></i> Save');
            $('#'+ id).prop({disabled: false});
            $('#modal-std').modal("hide");
            
            setTimeout(() => {
                modalAuto({page: "3"});
                
            }, 1000);

        }, 'JSON')
    }
    
    let createInvoiceSingle = (json)=>{
        modalLoadingDiv = '';
        
        if (!confirm('You are about to create ' + json.std_code.length + ' invoices(s)') || json.std_code.length === 0) {
            return false;
        }
        modalLoadingDiv = $("#modal-std");
        modalLoading({status: "show"})
        
       // $('.gen_invoice_btn').html('<i class="fa fa-spinner fa-spin"></i> Print').prop({disabled: true});
        $.post('<?php echo URL_ROOT ?>/finance/invoices/saveMultipleInvoice/?user_log=<?php echo $data['params']['user_log'] ?>', json, function (data) {
            // console.log(data);
            // modalLoadingDiv = '';
            if (data.status) {
                // modalLoadingDiv = $("#modal-student");
                modalLoading({status: ""});
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                $("#modal-std").modal('hide');
                // tableInvoice.ajax.reload(null, false);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
            modalLoading({status: ""});
            //
            
        }, 'JSON');
        
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    tableStudents = $("#table-std").DataTable();

    var loadStudent = (json) => {
    
        // dataTables
        let url = "<?php echo URL_ROOT ?>/school/students/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
    
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
                        return '<input type="checkbox" class="student-list" value="' + row['std_code'] + '">';
                    }
                },
                {"data": "create_date"},
                {"data": "std_code"},
                {"data": "cat_name", "render": function(data, type, row, meta){
                    return row['cat_name'] + ' ' + row['class_name'];
                }},
                {"data": "fees", "render": function(data, type, row, meta){
                    return number_format(data);
                }}, 
                {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                    return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                    }},
                {"data": "first_name", "render": function(data, type, row, meta){
                    return row.first_name + " " + row.last_name;
                }},
                {"data": "gender"},
                {"data": "phone"},
                {"data": "birthday"},
                {"data": "email"},
                {"data": "digit"}
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[11, "asc"]],
            "initComplete": function (settings, json) {
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                //  console.log(json);
                
                // let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableBank.search(this.previousElementSibling.value).draw() });
                //     $("#table-bank_filter.dataTables_filter input")
                //     .unbind()
                //     .bind("input, keyup", function(e) {
                //         if( (e.charCode || e.keyCode || e.which) === 13) tableBank.search(this.value).draw();
                //         e.preventDefault();
                //     }).prop({placeholder: 'Press [Enter] Key'})
                //     .after(searchButton).prop({autocomplete: 'off'});
            }
        });
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let tableDoc = $("#table-doc").DataTable();
    
    let loadDoc = (json) => {
        // console.log(json);
        
        $('#modalNav a[href="#page_4"]').removeClass('d-none');
        
        $.post('<?php echo URL_ROOT ?>/system/azurestorage', {
            action: 'listFiles',
            containerName: AZURE_CONTAINER,
            directory: 'mgt/' + $('#doc_path').val()
        }, function (data) {
            // console.log(data.data);
            if ($('#doc_path').val() === '') data.data = [];
            
            tableDoc.destroy();
            
            tableDoc = $('#table-doc').DataTable({
                "data": data.data,
                "columns": [{
                    "data": "name",
                    "render": function (data, type, row, meta) {
                        return '<span onclick="if (confirm(\'Delete file?\')) { azureDelete({directory: \'mgt/\' + $(\'#doc_path\').val(), file: \'' + row['name'] + '\', callback: \'loadDoc\'}) }" class="btn btn-xs btn-outline-danger"><i class="fa fa-trash"></i></span>'
                    }
                },
                    {
                        "data": "name",
                        "className": "dt-body-nowrap",
                        "render": function (data, type, row, meta) {
                            return '<a href="' + row['url'] + '" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size:2rem;">' + data + '</a>'
                        }
                    },
                    {
                        "data": "getContentLength",
                        "render": function (data, type, row, meta) {
                            return fancyFileSize(data);
                        }
                    },
                    {
                        "data": "getLastModified"
                    },
                ],
                "columnDefs": [{
                    "targets": [0],
                    "sortable": false,
                    "searchable": false
                },],
                "aaSorting": [
                    [3, "desc"]
                ],
                "initComplete": function (settings, json_) {
                    
                    // if(student_level_number > 11){
                    //     let btn = $("#subj_senior").find('button');
                    //     $(btn).click();
                    // }else{
                    //     let btn = $("#subj_junior").find('button');
                    //     $(btn).click();
                    // }
                    
                }
            });
        }, 'JSON');

    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });  

       let len = $("#table-exam-rate thead > tr").find('th').length
        $(".td_subject").attr("colspan", len-2);
        $(".td_performance").attr("colspan", len-1);
        // $("#td_performance_").attr("colspan", len-1);
        let r = $("#th-exam-rate")
        // console.log(len)
        
        ////
        flatpickr('#birthday', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            // maxDate: new Date().fp_incr(0), // -92
        });
        
        ////
        $('#department').select2({
            placeholder: "please select option",
            data: [
            {id: "SCIENCE", text: "SCIENCE"}, 
            {id: "COMMERCIAL", text: "COMMERCIAL"}, 
            {id:"ART", text: "ART"}
        ]})
        
        ////
        $('#gender').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getGenders/?user_log=<?php echo $data['params']['user_log'] ?>",
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

        ////
        $('#religion').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getReligions/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        ////
        $('#section').select2({
            placeholder: "please select option",
            allowClear: true,
            data: [{id: "BLUE", text: "BLUE"}, {id: "BIRD", text: "BIRD"}, {id:"ROSE", text: "ROSE"}, {id: "PINK", text: "PINK"}]}
        )
        
        ////
        $('#blood_group').select2({
            placeholder: "please select option",
            allowClear: true,
            data: [
                {id: "A+", text: "A+"},
                {id: "A-", text: "A-"}, 
                {id:"B+", text: "B+"},
                {id: "B-", text: "B-"},
                {id: "O+", text: "O+"},
                {id: "O-", text: "O-"},
                {id: "AB-", text: "AB-"},
                {id: "AB+", text: "AB+"}
                
                ]}
        )

        ////
        $('#class_code').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        }).on("select2:select", (v)=>{
            $("#department").val('').trigger("change");
            let class_code = v.target.selectedOptions[0].value;
            let obj = classObj[class_code];
            let digit = parseInt(obj.digit)
            $('#level_digit').val(digit)
            
            // $("#dept_container").css("display", digit)
        });
        //
        $('#branch_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getBranches",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        _option: 'select',
                    };
                },
                processResults: function (response) {
                    //console.log(response);
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        //
        $('#parent_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getParents",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        _option: 'select',
                    };
                },
                processResults: function (response) {
                    //console.log(response);
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        //
        $('#modal-std').on('hidden.bs.modal', function () {
            tableStudents.ajax.reload(null, false);
            // localStorage.setItem("modalOpen", "");
        });

        $('#modal-small_std_result').on('hidden.bs.modal', function () {
            // tableStudents.ajax.reload(null, false);
            // localStorage.setItem("modalOpen", "");
            $('#modal-std_result').modal('show');
            $('#modal-small_std_result').modal('hide');
            let rows = $('#table-small_std_result tbody tr');
            let thead_th = $('#table-small_std_result thead tr').find('th');
            let thead = [];
            let obj = {};
            $.each(thead_th, (k, v)=>{
                if(k === 0)return;
                let c = $(v).find('div:first-child').html().trim();
                thead.push(c)
            })
            // console.log(thead);return;
            //
            $.each(rows, (k, v)=>{
                let td_body = $(v).find('td');
                let o = '';
                $.each(td_body, (k, tbody_v)=>{
                    if(k === 0){
                        o = $(tbody_v).html();
                        obj[o] = {};
                        return;
                    };
                    obj[o][thead[k-1]] = $(tbody_v).html()
                    // console.log($(tbody_v).html());

                })

            })
            let new_res = JSON.stringify(obj);
            let elem =  $("#table-std_result tbody").find('tr:eq('+rowIndex+')').find('td:eq(4) input')["0"];
            $(elem).val(new_res)
            // $(elem).val(new_res);
            
            // console.log(elem);
        });

        ///////////////////////
        $('#modal-std_schedule').on('hidden.bs.modal', function () {
            tableStudents.ajax.reload(null, false);
            // localStorage.setItem("modalOpen", "");
        });
        ///////////////////////
        std_schedule_upload_fields();
    
        loadStudent({});
        //
        tableStudents.search('', false, true);
        //
        tableStudents.row(this).remove().draw(false);
        //
        $('#table-std tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableStudents.row(this);
            let rowId = $(this).parent('tr').index();
            // console.log(data);
    
            localStorage.setItem('selected-row', rowId);
            localStorage.setItem('modalOpen', '1');
            localStorage.setItem('selected-std', data.data()['std_code']);
            
            if (!data) return;
            if (this.cellIndex != 0) {
                modalStudent({table: '#table-std', row: data});
            }
        });
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
            // console.log($('#department').val())
            // user
            if ($('#modal-std').hasClass('show')) {
            
                // first_name
                if ($('#std_code').val().trim() === '') {
                    // disabled = true;
                    $('#std_code--help').html('FIELD NAME REQUIRED')
                } else {
                    $('#std_code--help').html('&nbsp;')
                }
                // first_name
                if ($('#first_name').val().trim() === '') {
                    // disabled = true;
                    $('#first_name--help').html('FIRST NAME REQUIRED')
                } else {
                    $('#first_name--help').html('&nbsp;')
                }
            
                // last_name
                if ($('#last_name').val().trim() === '') {
                    // disabled = true;
                    $('#last_name--help').html('LAST NAME REQUIRED')
                } else {
                    $('#last_name--help').html('&nbsp;')
                }
    
                // address
                if ($('#address').val().trim() === '') {
                    // disabled = true;
                    $('#address--help').html('ADDRESS REQUIRED')
                } else {
                    $('#address--help').html('&nbsp;')
                }
                
                // username
                if ($('#email').val().trim() !== '' && !regexp_email.test($('#email').val())) {
                    // disabled = true;
                    $('#email--help').html('VALID EMAIL REQUIRED')
                } else {
                    $('#email--help').html('&nbsp;')
                }
                // username
                if ($('#phone').val().trim() !== '' && !regexp_phone.test($('#phone').val())) {
                    // disabled = true;
                    $('#phone--help').html('VALID PHONE REQUIRED')
                } else {
                    $('#phone--help').html('&nbsp;')
                }
                // level name
                if ($('#class_code').val().trim() === '') {
                    // disabled = true;
                    $('#class_name--help').html('CLASS NAME REQUIRED')
                } else {
                    $('#class_name--help').html('&nbsp;')
                }
                // admission_id
                // if ($('#admission_id').val().trim() === '') {
                //     // disabled = true;
                //     $('#admission_id--help').html('ADMISSION ID REQUIRED')
                // } else {
                //     $('#admission_id--help').html('&nbsp;')
                // }
                // parent
                // if ($('#parent_name').val().trim() === '') {
                //     // disabled = true;
                //     $('#parent_name--help').html('PARENT NAME REQUIRED')
                // } else {
                //     $('#parent_name--help').html('&nbsp;')
                // }
                // birthday
                if ($('#birthday').val().trim() === '') {
                    // disabled = true;
                    $('#birthday--help').html('BIRTHDAY REQUIRED')
                } else {
                    $('#birthday--help').html('&nbsp;')
                }
                // gender
                if ($('#gender').val().trim() === '') {
                    // disabled = true;
                    $('#gender--help').html('GENDER REQUIRED')
                } else {
                    $('#gender--help').html('&nbsp;')
                }
                // gender
                if ($('#branch_code').val().trim() === '') {
                    // disabled = true;
                    $('#branch_code--help').html('BRANCH REQUIRED')
                } else {
                    $('#branch_code--help').html('&nbsp;')
                }
                // religion
                if ($('#religion').val().trim() === '') {
                    // disabled = true;
                    $('#religion--help').html('RELIGION REQUIRED')
                } else {
                    $('#religion--help').html('&nbsp;')
                }
                
                //////////////////////////////////////////////////////////////
                let tr = $('#performance_table').find('tr');
                /////////////////////////////
                $.each(tr, (k, v)=>{
                    let td_elem = $(v).find("td:not(:last-child)");
                    let len = td_elem.length-1;
                    // console.log(td_elem.length-1)
                    let last_td = $(v).find("td:last");
                    let total_score = 0;
                    $.each(td_elem, (k, td)=>{
                        if(k === 0)return;
                        if(k === len)return;
                        let text = $(td).html();
                        // console.log(k)
                        let reg_exp = new RegExp(/^\d{1,3}$|^\d{1,3}(\.\d{1,2})$/);
                        let reg = reg_exp.test(text);
                        if(!reg){
                            $(td).css("color", "red");
                            let v = String(text).replace(/[^0-9\.]/g, '');
                            $(td).html(v);
                            
                        }else{
                            $(td).css("color", "");
                            let score = parseFloat("0"+ text);
                            total_score += score;
                            $(v).find('td:eq('+len+')').html(total_score);
                            
                            $.each(examGrade_, (k, vv)=>{
                                let from = parseFloat(vv.percent_from)
                                let upto = parseFloat(vv.percent_upto)
                                if(total_score >= from && total_score <= upto){
                                    let td__  = $(v).find('td:last')['0'];
                                    td__.innerHTML = vv.grade_name
                                    // console.log(td__);
                                    return false;

                                }
                            })
                            // console.log(total_score)

                        }

                    })

                })
                // let class_code = $('#class_code').find(':selected').val();
                // let class_code = v.target.selectedOptions[0].value;
                // let obj = classObj[class_code];
                if($('#level_digit').val() >= 12){
                    $("#dept_container").css("display", "")
                    // first_name
                    if ($('#department').val().trim() === '') {
                        //disabled = true;
                        $('#department--help').html('FIRST NAME REQUIRED')
                    }
                     else {
                        $('#department--help').html('&nbsp;')
                    }
                }else{
                    $("#dept_container").css("display", "none")
                    $("#department").val('').trigger("change");

                }
                // let digit = parseInt(obj.digit) >= 12 ? "block" : "none";
                
                //
                if (saving) disabled = true;
                $('#save-student').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true);
    });

</script>