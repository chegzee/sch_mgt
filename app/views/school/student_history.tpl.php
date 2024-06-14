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
$classrooms = $data['classrooms'] ?? [];
$classroomsObj = $data['classroomsObj'] ?? [];
$valggk = $classrooms[0];
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
            <div class="d-flex">
                <div class="ml-1">
                    <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)" onclick="printStudentReport({std_code: $('input.student-list:checked').map(function(v){return $(this).val();}).get(), db: 'history' })" >
                            <i class="fas fa-print"></i> Report sheet
                        </a>
                    </div>
                </div>
                <div class="ml-1">
                    <button class="mb-3 dropdown-toggle" type="button" id="dropdownAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i> Selected</button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"  onclick="loadStudent({term_code: ''})">
                            <i class="text-orange-peel"></i> All
                        </a>
                        <?php 
                            foreach($data['terms'] as $k => $v){
                                echo "
                                    <a class='dropdown-item' href='#' onclick='loadStudent({term_code: \"".$v->code."\"})'>
                                        <i class='text-orange-peel'></i> ".$v->term." ".$v->year."
                                    </a>
                                ";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-std" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="" onclick="$('input.student-list:not(:disabled)').prop({checked: $(this).prop('checked')})"></th>
                                <th>Date</th>
                                <th>Term</th>
                                <th>Class</th>
                                <th>Picture</th>
                                <th>First Name</th>
                                <th>Surname Name</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Birthday</th>
                                <th>Email</th>
                                <th>Term</th>
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
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1 mb-8">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Profile</a>
                    <!-- <a class="nav-link has-icon other-link" href="#page_2" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Subject</span></a> -->
                    <a class="nav-link has-icon other-link" href="#page_3" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Result</span></a>
                    <a class="nav-link has-icon other-link" href="#page_4" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Document</span></a>
                    <a class="nav-link has-icon other-link" href="#page_5" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Attendance</span></a>
                    <!-- <a class="nav-link has-icon other-link" href="#page_6" data-toggle="tab"><i class="fa fa-table mr-2 fs-10"></i><span style="color: black;">Activities</span></a> -->
                </nav>
                
                <div class="tab-content">
                        <button type="button" class="std_report_btn" onclick="printStudentReport({std_code: [$('#std_code').val()], term: [$('#term_code').val()], export: 'PDF', db: 'history', single: '1' })"><i class="fa fa-print"></i> Report sheet</button>
                        <button type="button" id="print_invoice_btn" class="invoice_print" onclick="printInvoice({invoice_code: [$('#invoice_code').val()], term: [$('#term_code').val()], export: 'PDF', db: 'student', single: '1' })"><i class="fa fa-print"></i> Invoice</button>
                        <button type="button" id="create_receipt_btn"  onclick="printReceipt({receipt_code: [$('#receipt_code').val()] })"><i class="fa fa-print"></i> Print Receipt</button>
                    
                    <div class="tab-pane show active" id="page_1">
                        <div class="row">
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
                            <input id="std_code_old" style="display:none"/>
                        </div>
                    </div>
                        </div>
                    </div>

                    <!-- <div class="tab-pane" id="page_2">
                        
                        <div class="row">
                            <div class="table-responsive">
                                <div id="subj_senior" class="table_subject dataTables_wrapper">
                                    <table id="table-subj_senior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_senior", "action": "all", "elem": event})' disabled /></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div id="subj_junior" class="table_subject dataTables_wrapper">
                                    <table id="table-subj_junior" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Status<input type="checkbox" onclick='adder({"table":"#table-subj_junior", "action": "all", "elem": event})' disabled /></th>
                                                
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
                    </div> -->

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
                                                <!-- <button id="performance_btn" style="width:100px;height:100%;" onclick="savePerformance(event)"><i class="fa fa-save"></i> Save</button> -->
                                               <span style="position:absolute;left: 220px;top:15px">
                                                    Subjects
                                                </span>
                                            <!-- </div> -->
                                        </td>
                                        <td id="td_subject" style="border-right:1px solid black;border-bottom:1px solid black;" colspan="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td id="td_performance" colspan="">
                                            <table id="performance_table" style="border-left:1px solid black;border-right:1px solid black;width:100%">
                                            </table>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="page_4">
                        
                        <!-- <a href="javascript:void(0)" onclick="$('#doc_path_file').click()" class="btn btn-bg btn-outline-primary mb-3"><i class="fa fa-plus"></i> Select File</a> -->
                        
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
                        <!-- <input type="file" id="doc_path_file" accept="<?php echo ACCEPT_FILE_TYPE ?>" onchange="if (($('#doc_path').val() ?? '') === '') alert('Save Changes to proceed'); else { modalLoadingDiv = $('#modal-std'); azureUpload({doc_path: uuidv4(), directory: 'mgt/' + $('#doc_path').val() + '/', item: '#doc_path_file', callback: 'loadDoc'}) }" style="display:none"> -->
                    </div>
                    
                    <div class="tab-pane" id="page_5">
                        <!-- attendance -->
                        <div class="row">
                            <div class="col-lg-4 mt-2" style="">
                                <!-- <button id="save-attendance" onclick=saveTableAttendance(event)><i class="fa fa-save"></i> Save</button> -->
                                <table style="border:1px solid black;font-weight:bold" id="table-attendance">
                                    <!-- <tr style="border-bottom:1px solid black" id="no_of_days_for_the_term">
                                        <td style="border-right:1px solid black;" width="462.5px">No. of days for the term</td>
                                        <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50" contenteditable="" onkeyup=""></td>
                                    </tr> -->
                                    <tr style="border-bottom:1px solid black" id="no_of_days_absent">
                                        <td style="border-right:1px solid black;" width="462.5px">No. of days absent</td>
                                        <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50"  onkeyup=""></td>
                                    </tr>
                                    <tr style="border-bottom:1px solid black" id="no_of_days_present">
                                        <td style="border-right:1px solid black;" width="462.5px">No. of days present</td>
                                        <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50" onkeyup=""></td>
                                    </tr>
                                    <tr style="border-bottom:1px solid black" id="no_of_days_late">
                                        <td style="border-right:1px solid black;" width="462.5px">No. of days late</td>
                                        <td style="text-align:center;border-right:1px solid black;max-width:50px;overflow:hidden" width="50"  onkeyup=""></td>
                                    </tr>
                                </table>
                            </div> 
                        </div>
                        <!-- attendance -->

                    </div>

                    <!-- <div class="tab-pane" id="page_6">
                        <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="saveActivities();"><i class="fa fa-save"></i> Update</button>
                        <div class="row">
                            <div class="plist">
                                        
                            </div>
                        </div>
                    </div> -->
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
    let term = <?php echo json_encode($data['term']) ?>;
    let termObj = <?php echo json_encode($data['termObj']) ?>;
    let classrooms = <?php echo json_encode($data['classrooms']) ?>;
    let tableStudents = null;
    let saving = false;
    let period = 0;
    let upload_fields = [];
    let classWork = examRate_.class_work ?? 0;
    let midTermExm = examRate_.mid_term_exam ?? 0;
    console.log(term)
                                        
    let modalStudent = (json) => { 
        tableStudents = $(json.table).DataTable();
        //let data = json.row === '' ? {} : ( json.modalAuto ? tableStudents.row(json.row).data() : json.row); // data["colName"]
        let data = json.row === '' ? {} : json.row.data(); // data["colName"]
        console.log(data);
        // return;
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
        $('#admission_id').val(data['admission_id']);
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
        $('#modalNav a[href="#page_1"]').tab('show');

        //////////check if student have made a payment atleast once////////////
        if((data['receipt_code_table'] ?? '') !== ''){
            // $('#gen_invoice_btn').css({display: 'none'})
            $('#print_invoice_btn').css({display: 'none'})
            $('#save_activity_btn').css({display: 'none'})
            $('#saveSubject_btn').css({display: 'none'})
            $('#create_receipt_btn').css({display: ''})
        }else{
            // $('#gen_invoice_btn').css({display: ''})
            $('#print_invoice_btn').css({display: ''})
            $('#save_activity_btn').css({display: ''})
            $('#saveSubject_btn').css({display: ''})
            $('#create_receipt_btn').css({display: 'none'})

        }

        if(data['std_code'] === undefined)return;
        // console.log(parseInt(data.days_remains));return;
        $('.other-link').each((k, v)=>{
            $(v).css({display: data['std_code'] === undefined ? 'none' : 'inline-block'})
            // $(v).prop({disabled: parseInt(data.days_remains) < 0})
        })
        $("#no_of_days_for_the_term").html(term.days_remains);
        loadSubject(data);
        // console.log(no_of_days)
        
        //
    }

    let loadSocialBehaviour = (json)=>{
        let social_beh = JSON.parse((json.social_beh ?? '') === '' ? '{}' : json.social_beh);
        $('#table-social_beh').html('');
        $('#table-social_beh').append('<tr><td style="position:relative;text-align:center;width:350px;border:1px solid black">SOCIAL BEHAVIOUR</td><td style="text-align:center;border:1px solid black">RATING</td></tr>')
        // console.log(socialBehaviour_)
        $.each(socialBehaviour_, (k, v)=>{
            let html = '\
            <tr>\
                <td style="border:1px solid black;">'+v['behaviour']+'</td>\
                <td style="text-align:center;border:1px solid black" onkeyup="contenteditablechange2({elem: event})">'+(social_beh[v['behaviour']] ?? '')+'</td>\
            </tr>';
            $('#table-social_beh').append(html);
        })
        // loadDoc(json)
        loadTableAttendance(json)
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
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/getMapping/?user_log=<?php echo $data['params']['user_log'] ?> ";
        if((json.department ?? '') === ''){
            $('#subj_junior').css("display", "");
            $('#performance_table').html('');
            $('#std_name').html(json.first_name + " "+ json.last_name);
            $('#dept').css('display', 'none');
            $('#dept_name').css('display', 'none');
            let i = 0;
            
            ////
            $.each(subject, (k, v)=>{
                let result = subject_result[k] ?? '';
                let td = classWork > 0 ? '<td class="container_flip2" id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden">'+ (result[examName.first_name] ?? '') +'</td>' : '';
                let td_ = midTermExm > 0 ? '<td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden">'+ (result[examName.second_name] ?? '') +'</td>' : '';
                // console.log(result);
                let html = '\
                <tr style="border-bottom:1px solid black">\
                    <td style="border-right:1px solid black;font-weight:bold;">'+ k +'</td>'+td+'\
                    '+td_+'\
                    <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden">'+ (result[examName.third_name] ?? '' )+'</td>\
                    <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden" >'+ (result['final_score'] ?? '' )+'</td>\
                    <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden">'+ (result['final_grade'] ?? '' )+ '</td>\
                </tr>';
                $('#performance_table').append(html);
                // console.log(sub)
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
            
                    ////
                    $.each(subject, (k, v)=>{
                        let result = subject_result[k] ?? '';
                        let td = classWork > 0 ? '<td class="container_flip2" id="scar" style="text-align:center;border-right:1px solid black;overflow:hidden">'+ (result[examName.first_name] ?? '') +'</td>' : '';
                        let td_ = midTermExm > 0 ? '<td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden">'+ (result[examName.second_name] ?? '') +'</td>' : '';
                        // console.log(result);
                        let html = '\
                        <tr style="border-bottom:1px solid black">\
                            <td style="border-right:1px solid black;font-weight:bold;">'+ k +'</td>'+td+'\
                            '+td_+'\
                            <td class="container_flip2" style="text-align:center;border-right:1px solid black;overflow:hidden">'+ (result[examName.third_name] ?? '' )+'</td>\
                            <td class="container_flip2" style="text-align:center;font-weight:bold;border-right:1px solid black;overflow:hidden">'+ (result['final_score'] ?? '' )+'</td>\
                            <td class="container_flip2" style="text-align:center;font-weight:bold;overflow:hidden">'+ (result['final_grade'] ?? '' )+ '</td>\
                        </tr>';
                        $('#performance_table').append(html);
                        // console.log(sub)
                    })
            loadSocialBehaviour(json);
        }
        
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
        // loadProducts(json);
        loadDoc(json)

    }

    // ////////////////////////////////////////// ///
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
                    // console.log(json_);
                }
            });
        }, 'JSON');
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////

    tableStudents = $("#table-std").DataTable();

    let loadStudent = (json) => {
    
        // dataTables
        let url = "<?php echo URL_ROOT ?>/school/studentHistory/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        // $.post(url, {}, function(data) { console.log(data) }); return;
    
        tableStudents.destroy();
    
        tableStudents = $('#table-std').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {term_code: json.term_code},
            },
            "columns": [
                {
                    "data": "std_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<input type="checkbox" class="student-list" value="' + row['std_code'] + '">';
                    }
                },
                {"data": "create_date"},
                {"data": "term_name", "render": function(data, type, row, meta){
                    return row['term_name'] + " "+ row['year'];
                }},
                {"data": "class_name", "render": function(data, type, row, meta){
                    return row['cat_name'] + " "+ row['class_name']
                }}, 
                {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                    return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                    }},
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "gender"},
                {"data": "phone"},
                {"data": "birthday"},
                {"data": "email"},
                {"data": "term"},
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

    loadStudent({term_code: term.code});

    //
    tableStudents.search('', false, true);
    //
    tableStudents.row(this).remove().draw(false);

    //
    $('#table-std tbody').on('click', 'td', function () {
        //
        //let data = tableUser.row($(this)).data(); // data["colName"]
        let data = tableStudents.row($(this));
        let rowId = $(this).parent('tr').index();

        localStorage.setItem('selected-row', rowId);
        localStorage.setItem('modalOpen', '1');
        localStorage.setItem('selected-std', data.data()['std_code']);
    
        if (!data) return;
        if (this.cellIndex != 0) {
                modalStudent({table: '#table-std', row: data});
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////

    $(function () {

       let len = $("#table-exam-rate thead > tr").find('th').length
        $("#td_subject").attr("colspan", len-2);
        $("#td_performance").attr("colspan", len-1);
        let r = $("#th-exam-rate")

        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
            // console.log($('#department').val())
            // user
            if ($('#modal-std').hasClass('show')) {
                
                //////////////////////////////////////////////////////////////
                let tr = $('#performance_table').find('tr');
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
                //
                if (saving) disabled = true;
                $('#save-student').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 1000, true);

    });

</script>