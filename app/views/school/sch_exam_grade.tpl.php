<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subject</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    <!-- content -->
    <div class="card card-style-1">
        <div class="card-body">
            <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'table_exam_grade', row: '', modal: '#modal-exam_grade'})" disabled><i class="fa fa-file-import"></i>Upload exam schedule</button>
             <button onclick="modalExamRate({table: '#table-exam-rate', row: ''});"><i class="fa fa-plus"></i> Exam Rate</button>
             <button onclick="modalExamName({table: '#table-exam-name', row: ''});"><i class="fa fa-plus"></i> Exam Name</button>
            <!-- <button class="btn btn-small btn-outline-primary mb-3" onclick="$('#exam_schedule_file').click()"><i class="fa fa-file-import"></i>Import</button>
            <button class="btn btn-small btn-outline-primary mb-3"><i class="fa fa-file-import"></i>Export</button>
            <input type="hidden" id="doc_path" maxlength="250" readonly>
            <input type="file" id="exam_schedule_file" accept="application/vmd.openxmlformats.officedocumnet.spreadsheet.sheet, application/vmd.ms.excel" onchange="scheduleImport({excelfile: '#exam_schedule_file'}, event)" style="display:none"> -->
            <div class="row">
                <div class="col-4-xxxl col-12" data-select2-id="16">
                    <div class="card height-auto" data-select2-id="15">
                        <div class="card-body" data-select2-id="14">
                            <div class="heading-layout1">
                                <div class="item-title">
                                    <h3>Add New</h3>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">...</a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                                    </div>
                                </div>
                            </div>
                            <form class="new-added-form" id="exam_grade_form">
                                <div class="row">
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="code">Code *</label>
                                        <input type="text" id="code" class="form-control" placeholder="" value="" />
                                        <input type="text" id="code_old" class="form-control" style="display: none;"/>
                                        <code class="small text-danger" id="code--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="grade_name">Grade name *</label>
                                        <input type="text" id="grade_name" class="form-control" placeholder="" value="" />
                                        <code class="small text-danger" id="grade_name--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group" style="display:none;">
                                        <label for="grade_point">Grade point *</label>
                                        <select id="grade_point" class="form-control" title=""></select>
                                        <code class="small text-danger" id="grade_point--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="percent_from">Percentage from *</label>
                                        <input type="text" id="percent_from" class="form-control" placeholder=""/>
                                        <code class="small text-danger" id="percent_from--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="percent_upto">Percentage upto *</label>
                                        <input type="text" id="percent_upto" class="form-control" placeholder=""/>
                                        <code class="small text-danger" id="percent_upto--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label>Comments<span class="small text-primary"> (Option)</span></label>
                                        <textarea type="text" class="textarea form-control" name="message" id="comment" cols="10" rows="6"></textarea>
                                    </div>
                                    <div class="col-12 form-group mg-t-8">
                                        <button type="button" id="save-exam_grade" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" onclick="saveExamGrade({})"><i class="fa fa-save"></i> Save</button>
                                        <button type="button" class="btn-fill-lg bg-blue-dark btn-hover-yellow"  onclick="modalExamGrade({table: '#table-exam_grade', row: ''})"><i class="fa fa-refresh"></i>  Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8-xxxl col-12">
                    <div class="card height-auto">
                        <div class="card-body">
                            <div class="heading-layout1">
                                <div class="item-title">
                                    <h3>Exams Grade List</h3>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">...</a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div id="" class="dataTables_wrapper">
                                    <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList" id="table-exam_grade">
                                        <thead>
                                            <tr>
                                                <th><i class="material-icons">build</i></th>
                                                <th>Grade name</th>
                                                <th>Percentage from</th>
                                                <th>Percentage upto</th>
                                                <th>Comment</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content -->
    
<!-- Exam Rate -->
<div id="modal-exam-rate" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Exam Rate New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Page One</a>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane show active" id="page_1">
                        <div class="row" id="exam_rate_form">
                            <div class="form-group row">
                                <label for="rate_code" class="col-md-4 col-form-label text-sm-right">Code <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <div class="custom-control custom-control-nolabel custom-checkbox">
                                                    <input type="checkbox" id="status">
                                                </div>
                                            </div>
                                        </div>
                                         <input type="text" class="form-control form-control-lg" id="rate_code" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="rate_code--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class_work" class="col-md-4 col-form-label text-sm-right">Class Work <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg" id="class_work" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="class_work--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mid_term_exam" class="col-md-4 col-form-label text-sm-right">Mid Term Exam <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg" id="mid_term_exam" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="mid_term_exam--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="terminal_exam" class="col-md-4 col-form-label text-sm-right">Terminal Exam <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg" id="terminal_exam" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="terminal_exam--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalExamRate({table: '#table-exam-rate', row: '', action: 'reset'}); $('#modal-title').html('New Exam Rate')"><i class="fa fa-refresh"></i> Reset</button>
                                <button id="save-examRate" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveExamRate({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                            <input id="rate_code_old" style="display:none"/>
                        </div>
                    </div>
                    
                    <div class="card card-style-1">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <div class="dataTables_wrapper">
                                    <table id="table-exam-rate" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th><i class="material-icons">build</i></th>
                                                <th>Class Work</th>
                                                <th>Mid Term Exam</th>
                                                <th>Terminal exam</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Exam Rate -->

<!-- Exam Name -->
<div id="modal-exam-name" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Exam Name New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Page One</a>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane show active" id="page_1">
                        <div class="row" id="exam_name_form">
                            <div class="form-group row">
                                <label for="exam_name_code" class="col-md-4 col-form-label text-sm-right">Code <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <div class="custom-control custom-control-nolabel custom-checkbox">
                                                    <input type="checkbox" id="status_">
                                                </div>
                                            </div>
                                        </div>
                                         <input type="text" class="form-control form-control-sm" id="exam_name_code" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="exam_name_code--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-sm-right">First Name <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg" id="first_name" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="first_name--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="second_name" class="col-md-4 col-form-label text-sm-right">Second Name <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="second_name" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="second_name--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="third_name" class="col-md-4 col-form-label text-sm-right">Third Name <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="third_name" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="third_name--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalExamName({table: '#table-exam-name', row: '', action: 'reset'}); $('#modal-title').html('New Exam Name')"><i class="fa fa-refresh"></i> Reset</button>
                                <button id="save-examName" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveExamName({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                            <input id="exam_name_code_old" style="display:none"/>
                        </div>
                    </div>
                    
                    <div class="card card-style-1">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <div class="dataTables_wrapper">
                                    <table id="table-exam-name" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th><i class="material-icons">build</i></th>
                                                <th>First name</th>
                                                <th>Second Name</th>
                                                <th>Third Name</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Exam Name -->

</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    let table_exam_grade = null;
    let tableExamRate = null;
    let tableExamName = null;
    
    // console.log(userAccess);
    
    //
    let deleteExamGrade = (json) => {
        // console.log(json)
        let table_exam_schedule = $(json.table).DataTable();
        let data = table_exam_schedule.row(json.row).data(); // data["colName"]
        //  console.log(data);return;
        if (!confirm('Delete record: ' + data['code'] )) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/examGrade/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            table_exam_schedule.ajax.reload(null, false);
            
        }, 'JSON');
    }
    
    let deleteExamRate = (json) => {
        // console.log(json);return
        tableExamRate = $(json.table).DataTable();
        let data = tableExamRate.row(json.row).data(); // data["colName"]
        //  console.log(data);return;
        if (!confirm('Delete record: ' + data['code'] )) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/examGrade/__delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableExamRate.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let deleteExamName = (json) => {
        // console.log(json);return
        tableExamName = $(json.table).DataTable();
        let data = tableExamName.row(json.row).data(); // data["colName"]
        //  console.log(data);return;
        if (!confirm('Delete record: ' + data['code'] )) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/examGrade/deleteExamName/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableExamName.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        $(json.table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    let modalExamGrade = (json) => { 
        let table_exam_grade = $(json.table).DataTable();
        let data = json.row === '' ? {} : table_exam_grade.row(json.row).data(); // data["colName"]
        // $('#modalNav').find('a.non-active').addClass('d-none');
        // console.log(data);return;
        
        $('#code').val(data['code']?? 'AUTO');
        $('#code_old').val(data['code'] ?? '');
        $('#grade_name').val(data['grade_name'] ?? '');
        $('#grade_point').append(new Option(data['grade_point'] ?? '', data['grade_point'] ?? '', true, true)).trigger('change');
        $('#percent_from').val(data['percent_from'] ?? '');
        $('#percent_upto').val(data['percent_upto'] ?? '');
        $('#comment').val(data['comment'] ?? '');

        // console.log( $('#code_old').val())
        
        //
    }

    let modalExamName = (json) => { 
        tableExamName = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableExamName.row(json.row).data(); // data["colName"]
        // $('#modalNav').find('a.non-active').addClass('d-none');
        // console.log(data);return;
        
        $('#exam_name_code').val(data['exam_name_code']?? 'AUTO');
        $('#exam_name_code_old').val(data['exam_name_code'] ?? '');
        $('#status_').prop("checked", (data['status'] ?? '') === '1' ? true : false);
        $('#first_name').val(data['first_name'] ?? '');
        $('#second_name').val(data['second_name'] ?? '');
        $('#third_name').val(data['third_name'] ?? '');

        // console.log( $('#code_old').val())
        if(json.action === 'reset') return;
        if(json.action === 'edit') return;

        // let tableExamRate = $('#table-exam-rate').DataTable();
        tableExamName.destroy();
        let url = "<?php echo URL_ROOT ?>/school/examGrade/getExamNameList/?user_log=<?php echo $data['params']['user_log'] ?> ";
        
        tableExamName = $('#table-exam-name').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {},
            },
            "columns": [
                {
                    "data": "exam_name_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalExamName({table: \'#table-exam-name\', row: \'' + meta['row'] + '\', action: \'edit\' })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteExamName({table: \'#table-exam-name\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                    }
                },
                {"data": "first_name"},
                {"data": "second_name"},
                {"data": "third_name"}, 
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "asc"]],
            "initComplete": function (settings, json) {
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                // console.log(json);
                //  modalAuto();
            }
        });

        
        $('#modal-exam-name').modal('show');
        
        //
    }

    let modalExamRate = (json)=>{
        // console.log(json);
        tableExamRate = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableExamRate.row(json.row).data(); // data["colName"]
        // console.log(data['status'] ?? '');
        $('#rate_code').val(data['rate_code'] ?? 'AUTO');
        $('#rate_code_old').val(data['rate_code'] ?? '');
        $('#status').prop("checked", (data['status'] ?? '') === '1' ? true : false);
        $('#class_work').val(data['class_work'] ?? '');
        $('#mid_term_exam').val(data['mid_term_exam'] ?? '');
        $('#terminal_exam').val(data['terminal_exam'] ?? '');
        if(json.action === 'reset') return;
        if(json.action === 'edit') return;

        // let tableExamRate = $('#table-exam-rate').DataTable();
            tableExamRate.destroy();
            let url = "<?php echo URL_ROOT ?>/school/examGrade/__list/?user_log=<?php echo $data['params']['user_log'] ?> ";
            
            tableExamRate = $('#table-exam-rate').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "rate_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalExamRate({table: \'#table-exam-rate\', row: \'' + meta['row'] + '\', action: \'edit\' })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteExamRate({table: \'#table-exam-rate\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                        }
                    },
                    {"data": "class_work"},
                    {"data": "mid_term_exam"},
                    {"data": "terminal_exam"}, 
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    // console.log(json);
                    //  modalAuto();
                }
            });

        
        $('#modal-exam-rate').modal('show');
    }
    //
    let saveExamGrade = (json)=>{
        
        if ($('#save-exam_grade').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        // //
        $.each($('#exam_grade_form').find('input, select, textarea'), function (i, obj) {
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
        // console.log(form_data);return;
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/examGrade/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-exam_grade').html('<i class="fa fa-spinner fa-spin" style=""></i> Save Changes');
                $('#save-exam_grade').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                // console.log(data);
                //
                saving = false;
                //
                $('#save-exam_grade').html('Save Changes');
                $('#save-exam_grade').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $('#save-exam_grade').html('<i class="fa fa-save"></i> Save');
                table_exam_grade.ajax.reload(null, false);

                // $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-exam_grade').html('Save Changes');
                $('#save-exam_grade').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    //
    let saveExamRate = (json)=>{
        // console.log(json);return;
    
        let form_data = new FormData();
        // //
        $.each($('#exam_rate_form').find('input, select, textarea'), function (i, obj) {
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
        // console.log(form_data);return;
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/examGrade/___save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-examRate').html('<i class="fa fa-spinner fa-spin" style=""></i> Save Changes');
                $('#save-examRate').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                // console.log(data);
                //
                saving = false;
                //
                $('#save-examRate').html('Save Changes');
                $('#save-examRate').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $('#save-examRate').html('<i class="fa fa-save"></i> Save');
                tableExamRate.ajax.reload(null, false);

                // $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-examRate').html('Save Changes');
                $('#save-examRate').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    //
    let saveExamName = (json)=>{
        // console.log(json);return;
    
        let form_data = new FormData();
        // //
        $.each($('#exam_name_form').find('input, select, textarea'), function (i, obj) {
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
        console.log(form_data);
        // return;
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/examGrade/saveExamName/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-examName').html('<i class="fa fa-spinner fa-spin" style=""></i> Save Changes');
                $('#save-examName').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                // console.log(data);
                //
                saving = false;
                //
                $('#save-examName').html('Save Changes');
                $('#save-examName').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $('#save-examName').html('<i class="fa fa-save"></i> Save');
                tableExamName.ajax.reload(null, false);

                // $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-examName').html('Save Changes');
                $('#save-examName').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        // $('#grade_point').append(new Option('', '', true, true)).trigger('change');

        // $('#modal-exam_schedule').on("hide.bs.modal", ()=>{
        //     table_exam_schedule.ajax.reload(null, false);
        // })
        

        $('#code').val('AUTO');
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        table_exam_grade = $("#table-exam_grade").DataTable();
    
        let loadExamBrade = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/school/examGrade/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            table_exam_grade.destroy();
        
            table_exam_grade = $('#table-exam_grade').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-success' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalExamGrade({table: \'#table-exam_grade\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteExamGrade({table: \'#table-exam_grade\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a> </div>'
                            ;
                        }

                    },
                    {"data": "grade_name"},
                    {"data": "percent_from"},
                    {"data": "percent_upto"},
                    {"data": "comment"},
                ],
                "columnDefs": [
                    {"targets": [1], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  modalAuto();
                }
            });
        }
    
        //
        loadExamBrade({});
        //
        table_exam_grade.search('', false, true);
        //
        table_exam_grade.row(this).remove().draw(false);
    
        //
        $('#table-exam_grade tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = table_exam_grade.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalExamGrade({table: '#table-exam_grade', row: data});
                //
                // $('#modalNav a[href="#page_1"]').tab('show');
            }
        });


        // $('.timepicker').ejDateTimePicker({
        //     dateHeaderFormat: 'showHeaderShort',
        //     width: '200px'
        // })


    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        // $('#modal-std').on('hidden.bs.modal', function () {
        //     tableStudents.ajax.reload(null, false);
        // });

        
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // user
            // if ($('#modal-std').hasClass('show')) {
                // day
                if ($('#code').val().trim() === '') {
                    disabled = true;
                    $('#code--help').html('FIELD REQUIRED')
                } else {
                    $('#code--help').html('&nbsp;')
                }
                // // class
                if ($('#grade_name').val().trim() === '') {
                    disabled = true;
                    $('#grade_name--help').html('GRADE NAME REQUIRED')
                } else {
                    $('#grade_name--help').html('&nbsp;')
                }
                // // subject name
                //  if ($('#grade_point').val().trim() === '') {
                //      disabled = true;
                //      $('#grade_point--help').html('GRADE POINT REQUIRED')
                //  } else {
                //      $('#grade_point--help').html('&nbsp;')
                //  }
                // // subject name
                if ($('#percent_from').val().trim() === '') {
                    disabled = true;
                    $('#percent_from--help').html('FIELD NAME REQUIRED')
                } else {
                    $('#percent_from--help').html('&nbsp;')
                }
                // // subject name
                if ($('#percent_upto').val().trim() === '') {
                    disabled = true;
                    $('#percent_upto--help').html('FIELD NAME REQUIRED')
                } else {
                    $('#percent_upto--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-exam_grade').prop({disabled: disabled});
            
            // }
        
            checkForm.start();
        
        }, 500, true);
    
    });

</script>