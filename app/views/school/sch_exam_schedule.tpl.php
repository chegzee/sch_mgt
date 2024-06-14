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
            <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'table_exam_schedule', row: '', modal: '#modal-exam_schedule'})"><i class="fa fa-file-import"></i>Upload exam schedule</button>
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
                            <form class="new-added-form" id="exam_schedule_form">
                                <div class="row">
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="exam_name">Exam name *</label>
                                        <input type="text" id="exam_name" class="form-control" placeholder="" value="" />
                                        <code class="small text-danger" id="exam_name--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <div class="row">
                                            <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label for="cat_code">Class *</label>
                                                <select id="cat_code" class="form-control" title=""></select>
                                                <code class="small text-danger" id="class--help">&nbsp;</code>
                                            </div>
                                            <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label for="class_name_code">Class Name *</label>
                                                <select id="class_name_code" class="form-control"></select>
                                                <code class="small text-danger" id="class_name--help">&nbsp;</code>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="subject_name_code">Subject Name *</label>
                                        <select id="subject_name_code" class="form-control"></select>
                                        <code class="small text-danger" id="subject_name--help">&nbsp;</code>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <div class="row">
                                            <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label for="start_time">Start time *</label>
                                                <input id="start_time" class="form-control timepicker" readonly>
                                                <code class="small text-danger" id="start_time--help">&nbsp;</code>
                                            </div>
                                            
                                            <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label for="end_time">End time *</label>
                                                <input id="end_time" class="form-control timepicker" readonly>
                                                <code class="small text-danger" id="end_time--help">&nbsp;</code>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <div class="row">
                                            <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label for="code">Exam Code</label>
                                                <input type="text" class="form-control form-control-lg" id="code" style="width: 100%"/>
                                                <code class="small text-danger" id="sub_code--help">&nbsp;</code>
                                                <input id="code_old" style="display: none;"/>
                                            </div>
                                            <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label for="exam_date">Date (YYYY-MM-DD)</label>
                                                <input type="text" class="form-control form-control-lg" id="exam_date" style="width: 100%"/>
                                                <code class="small text-danger" id="exam_date--help">&nbsp;</code>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-12 form-group mg-t-8">
                                        <button type="button" id="save-exam_schedule" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" onclick="saveExamSchedule({})">Save</button>
                                        <button type="button" class="btn-fill-lg bg-blue-dark btn-hover-yellow"  onclick="modalExamSchedule({table: '#table-exam_schedule', row: ''})"><i class="fa fa-refresh"></i>Reset</button>
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
                                    <h3>All Exams schedule</h3>
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
                                    <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList" id="table-exam_schedule">
                                        <thead>
                                            <tr>
                                                <th><i class="material-icons">build</i></th>
                                                <th>Exam name</th>
                                                <th>Class</th>
                                                <th>Class name</th>
                                                <th>Subject</th>
                                                <th>Period</th>
                                                <th>Date</th>
                                                <th>Digit</th>
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

    <!-- exam scedule modal -->
    <div id="modal-exam_schedule" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Exam schedule New/Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-small btn-outline-primary mb-3" onclick="$('#exam_schedule_file').click()"><i class="fa fa-file-import"></i>Import</button>
                    <button class="btn btn-small btn-outline-primary mb-3" onclick="scheduleExportV2({theadRows: '#table-exam_schedule_upload thead tr', tbodyRows: '#table-exam_schedule_upload tbody', filename: 'exam_schedule'})" ><i class="fa fa-file-import"></i>Export</button>
                    <button class="btn btn-small btn-outline-primary mb-3" id="save-import_exam_schedule" onclick="saveExamScheduleUpload({table: '#table-exam_schedule_upload'})"><i class="fa fa-save"></i>Save</button>
                    <input type="hidden" id="doc_path" maxlength="250" readonly>
                    <input type="file" id="exam_schedule_file" accept="application/vmd.openxmlformats.officedocumnet.spreadsheet.sheet, application/vmd.ms.excel" onchange="scheduleImport({excelfile: '#exam_schedule_file', table: '#table-exam_schedule_upload', action: 'examSchedule'}, event)" style="display:none">
                    <div class="table-responsive">
                        
                        <div id="" class="dataTables_wrapper">
                            <!-- <div class="dataTables_wrapper dt-bootstrap4 no-footer"> -->
                                <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" role="grid" id="table-exam_schedule_upload">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons">build</i></th>
                                            <th>Exam name</th>
                                            <th>Class</th>
                                            <th>Class name</th>
                                            <th>Subject</th>
                                            <th>Start time</th>
                                            <th>End time</th>
                                            <th>Date</th>
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
    <!-- exam schedule -->
</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    let levels = <?php echo json_encode($data['levelsobj']) ?>;
    let classes = <?php echo json_encode($data['classesobj']) ?>;
    let subjects = <?php echo json_encode($data['subjectsobj']) ?>;
    let table_exam_schedule = null;
    
    // console.log(levels, classes, subjects);
    
    //
    let deleteExamSchedule = (json) => {
        let table_exam_schedule = $(json.table).DataTable();
        let data = table_exam_schedule.row(json.row).data(); // data["colName"]
        //  console.log(data);return;
        if (!confirm('Delete record: ' + data['code'] )) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/examSchedule/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
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

    let createExamScheduleRow = (json) => {
        // console.log(json.data['End time']);return
        let tbody = $(json.table).find('tbody');
        let keys = Object.keys(json.data);
        let class_, class_name, subject, start_time, end_time;
        class_ = (json.data['Class'] ?? '') !== '' ? json.data['Class'].replace(/[^0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        class_name = (json.data['Class name'] ?? '') !== '' ? json.data['Class name'].replace(/[^0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        subject = (json.data['Subject'] ?? '') !== '' ? json.data['Subject'].replace(/[^0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : ''; '';
        start_time = (json.data['Start time'] ?? '') !== '' ? json.data['Start time'].toString().replace(/[^:0-9a-zA-Z]/g, '').trim().toUpperCase() : '';
        end_time = (json.data['End time'] ?? '') !== '' ? json.data['End time'].toString().replace(/[^:0-9a-zA-Z-/]/g, '').trim().toUpperCase() : '';
        json.data['Start time'] = start_time; 
        json.data['End time'] = end_time;
        // console.log(class_, class_name, subject, start_time, end_time);return
        if(class_ === '' || class_name  === '' || subject === '')return;
        //
        // console.log(json.data);return
        //
        // if(levels[class_] === undefined || classes[class_name] === undefined || subjects[subject] === undefined)return;
        //
        let html = '<tr><td><a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalExamSchedule({table: \'#table-exam_schedule_upload\', row: \''+ json.row +'\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="removeRow({table: \''+json.table+'\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Remove</a>  </div></td>';
        $.each(keys, function (k, v) {
            if(v === 'Exam name'){
                 html += '<td><input id="exam_name" class="" value="'+ json.data[v].toUpperCase().trim() +'" /></td>';

            }else if(v === 'Class'){
                 html += '<td><select style="width:200px" id="cat_code-'+json.row+''+k+'"></select></td>';

            } else if(v === 'Class name'){
                 html += '<td><select style="width:200px" id="class_name_code-'+json.row+''+k+'"></select></td>';

            }else if(v === 'Subject'){
                 html += '<td><select style="width:200px" id="subject_name_code-'+json.row+''+k+'"></select></td>';

            }else if(v === 'Start time' ){
                 html += '<td><input id="start_time" value="'+ json.data[v].toUpperCase().trim() +'" /></td>';

            }else if(v === 'End time'){
                 html += '<td><input id="end_time" value="'+ json.data[v].toUpperCase().trim() +'"/></td>';

            }else if(v === 'Date'){
                 html += '<td><input id="exam_date" value="'+ json.data[v] +'" /></td>';

            }
            // console.log(v)
        })
        html += '</tr>';
        tbody.append(html);
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(2) > select').append(new Option(class_ , ((levels[class_]??'')['cat_code'] ?? ''), true, true)).trigger('change');
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(3) > select').append(new Option(class_name , ((classes[class_name]?? '')['class_code'] ?? '') , true, true)).trigger('change');
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(4) > select').append(new Option(subject , ((subjects[subject]??'')['sub_code'] ?? '') , true, true)).trigger('change');
        
        //
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(2) > select#cat_code-'+json.row+'1').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getCategories/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                    //console.log(response);
                    return { results: response };
                },
                cache: true
            }
        });
        //
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(3) > select#class_name_code-'+json.row+'2').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log(params)
                    return {
                        searchTerm: params.term,
                        _option: 'select'
                    };
                },
                processResults: function (response) {
                    //console.log(response);
                    return { results: response };
                },
                cache: true
            }
        });
        //
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(4) > select#subject_name_code-'+json.row+'3').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getSubjects/?user_log=<?php echo $data['params']['user_log'] ?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log(params)
                    return {
                        searchTerm: params.term,
                        _option: 'select'
                    };
                },
                processResults: function (response) {
                    //console.log(response);
                    return { results: response };
                },
                cache: true
            }
        });
        //
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(5) > input#start_time').timepicker({
            timeFormat: 'hh:mm p',
            interval: 5,
            minTime: '06',
            maxTime: '5:00pm',
            startTime: '06:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        //
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(6) > input#end_time').timepicker({
            timeFormat: 'hh:mm p',
            interval: 5,
            minTime: '06',
            maxTime: '5:00pm',
            // defaultTime: '6',
            startTime: '06:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        //
        flatpickr($(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(7) > input'), {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });

        //  console.log($(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(2) > select#class'), json.data['Class'])
        //
        // if (json.row < 0) {
        //     tbody.prepend(html);
        // } else if (Object.keys(json.data).length > 0) {
        //     tbody.find('tr:eq(' + json.row + ')').before(html);
        // } else {
        //     tbody.find('tr:eq(' + json.row + ')').after(html);
        // }
    }
    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        $(json.table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    let modalExamSchedule = (json) => { 
        let table_class_routine = $(json.table).DataTable();
        let data = json.row === '' ? {} : table_class_routine.row(json.row).data(); // data["colName"]
        // $('#modalNav').find('a.non-active').addClass('d-none');
        // console.log(data);return;
        
        $('#code').val(data['code']?? 'AUTO');
        $('#code_old').val(data['code'] ?? 'AUTO');
        $('#exam_name').val(data['exam_name'] ?? '');
        $('#cat_code').append(new Option(data['cat_name'], data['cat_code'], true, true)).trigger('change');
        $('#class_name_code').append(new Option(data['class_name'], data['class_name_code'], true, true)).trigger('change');
        $('#subject_name_code').append(new Option(data['subject_name'], data['subject_name_code'], true, true)).trigger('change');
        $('#start_time').val(data['start_time'] ?? '');
        $('#end_time').val(data['end_time'] ?? '');
        $('#exam_date').val(data['exam_date'] ?? '');
        
        //
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let username = '<?php echo $data['params']['username'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (username !== '') {
            
                let tableUser = $('#table-user').DataTable();
            
                tableUser.columns(1).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === username) {
                            //console.log(v, i);
                            modalUser({table: '#table-user', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalUser({table: '#table-user', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveExamSchedule = (json) => {
        
        // console.log(json);return;
        
        if ($('#save-exam_schedule').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        // //
        $.each($('#exam_schedule_form').find('input, select, textarea'), function (i, obj) {
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
            url: '<?php echo URL_ROOT ?>/school/examSchedule/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-exam_schedule').html('<i class="fa fa-spinner fa-spin" style=""></i> Save Changes');
                $('#save-exam_schedule').prop({disabled: true});
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
                $('#save-exam_schedule').html('Save');
                $('#save-exam_schedule').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                table_exam_schedule.ajax.reload(null, false);

                // $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-exam_schedule').html('Save Changes');
                $('#save-exam_schedule').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    let saveExamScheduleUpload = (json) =>{
             // console.log("json");return
            $('#save-import_exam_schedule').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');

            if(!confirm("Do you want to perform this operation")) return;
            // console.log("scared");return;
            let tr = $(json.table + ' tbody').find('tr');
            tr.each((k, v)=>{
                $(v).find('td:eq(0)');
                $(v).find('td:eq(0)').find('a').css('color', '#167bea');
            })
            // console.log("true");
        let rows = [];
        $(json.table + ' tbody > tr').each((k, tr)=>{
            //
            let row = {};
            let td = [...tr.children]
            td.forEach((v,k)=>{
                let tag_name = v.children[0].tagName;
                let id = String(v.children[0].id);
                let key = id.substring(0, (id.indexOf('-') === -1 ) ? id.length :  id.indexOf('-') )
                if(tag_name === 'A'){

                }else if(tag_name === 'INPUT'){
                    row[key] = String(v.children[0].value).trim();
                }else if(tag_name === 'SELECT'){
                    row[key] = String(v.children[0].selectedOptions[0].value).trim();

                }
                // console.log(id.indexOf('-'))
                // console.log(id.substring(0, (id.indexOf('-') === -1 ) ? id.length :  id.indexOf('-') ))
            })
            rows.push(row);
            
        });
        // console.log(rows)
        let data = {data: JSON.stringify(rows)};
        $.post('<?php echo URL_ROOT ?>/school/examSchedule/__save/?user_log=<?php echo $data['params']['user_log'] ?>', data, (data)=>{
                if(!data.status){
                    $(json.table + ' tbody > tr:eq('+data.key+') td:eq(0) a').css("color", "red");
                    $('#save-import_exam_schedule').html('<i class="fa fa-save"></i>Save');
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                }
            $('#save-import_exam_schedule').html('<i class="fa fa-save"></i>Save');
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                console.log(data.key);
        }, 'json')
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        $('#modal-exam_schedule').on("hide.bs.modal", ()=>{
            table_exam_schedule.ajax.reload(null, false);
        })

        $('#code').val('AUTO');

        $('#cat_code').append(new Option('', '', true, true)).trigger('change');
        $('#class_name_code').append(new Option('', '', true, true)).trigger('change');
        $('#subject_name_code').append(new Option('', '', true, true)).trigger('change');
        
        //
        $('#class_name_code').select2({
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
        });
        //
        $('#cat_code').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getCategories/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        $('#subject_name_code').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getSubjects/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        $('.timepicker').timepicker({
            timeFormat: 'hh:mm p',
            interval: 5,
            minTime: '06',
            maxTime: '5:00pm',
            defaultTime: '',
            startTime: '06:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        //
        flatpickr('#exam_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });
        // $('#sub_code').val('AUTO');
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        table_exam_schedule = $("#table-exam_schedule").DataTable();
    
        let loadExamSchedule = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/school/examSchedule/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            table_exam_schedule.destroy();
        
            table_exam_schedule = $('#table-exam_schedule').DataTable({
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
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalExamSchedule({table: \'#table-class_routine\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteExamSchedule({table: \'#table-exam_schedule\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                            ;
                        }

                    },
                    {"data": "exam_name"},
                    {"data": "cat_name"},
                    {"data": "class_name"},
                    {"data": "subject_name"},
                    {"data": "period"},
                    {"data": "submit_on"},
                    {"data": "digit"}
                ],
                "columnDefs": [
                    {"targets": [7], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[7, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  console.log(json);
                    //  modalAuto();
                }
            });
        }
        //
        loadExamSchedule({});
        //
        table_exam_schedule.search('', false, true);
        //
        table_exam_schedule.row(this).remove().draw(false);
    
        //
        $('#table-exam_schedule tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = table_exam_schedule.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalExamSchedule({table: '#table-exam_schedule', row: data});
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
        // let checkForm = new timer();
        // checkForm.start(function () {
        //     //
        //     checkForm.stop();
        //     //
        //     let disabled = false;
        
        //     // user
        //     // if ($('#modal-std').hasClass('show')) {
        //         // day
        //         if ($('#day').val().trim() === '') {
        //             disabled = true;
        //             $('#day--help').html('DAY NAME REQUIRED')
        //         } else {
        //             $('#day--help').html('&nbsp;')
        //         }
        //         // // class
        //         if ($('#class').val().trim() === '') {
        //             disabled = true;
        //             $('#class--help').html('CLASS LEVEL REQUIRED')
        //         } else {
        //             $('#class--help').html('&nbsp;')
        //         }
        //         // // subject name
        //          if ($('#class_name').val().trim() === '') {
        //              disabled = true;
        //              $('#class_name--help').html('CLASS NAME REQUIRED')
        //          } else {
        //              $('#class_name--help').html('&nbsp;')
        //          }
        //         // // subject name
        //         if ($('#subject_name').val().trim() === '') {
        //             disabled = true;
        //             $('#subject_name--help').html('SUBJECT NAME REQUIRED')
        //         } else {
        //             $('#subject_name--help').html('&nbsp;')
        //         }
        //         // // subject name
        //         if ($('#teacher').val().trim() === '') {
        //             disabled = true;
        //             $('#teacher--help').html('TEACHER NAME REQUIRED')
        //         } else {
        //             $('#teacher--help').html('&nbsp;')
        //         }
        //         // subject name
        //         if ($('#start_time').val().trim() === '') {
        //             disabled = true;
        //             $('#start_time--help').html('START TIME REQUIRED')
        //         } else {
        //             $('#start_time--help').html('&nbsp;')
        //         }
        //         // subject name
        //         if ($('#end_time').val().trim() === '') {
        //             disabled = true;
        //             $('#end_time--help').html('END TIME REQUIRED')
        //         } else {
        //             $('#end_time--help').html('&nbsp;')
        //         }
                
        //         //
        //         if (saving) disabled = true;
        //         $('#save-class_routine').prop({disabled: disabled});
            
        //     // }
        
        //     checkForm.start();
        
        // }, 500, true);
    
    });

</script>