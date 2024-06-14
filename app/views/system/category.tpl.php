<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a>
            </li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Level</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalcategory({table: '#table-cat', row: ''}); $('#modal-title').html('New class')" class=""><i class="fa fa-plus"></i> Add</button>
            
            <div class="table-responsive">
                <table id="table-cat" class="table table-striped table-bordered table-sm nowrap w-100" style="cursor: pointer">
                    <thead>
                        <tr>
                            <th><i class="material-icons">build</i></th>
                            <th>Level</th>
                            <th>Alpha</th>
                            <th>Digit</th>
                            <th>Fees</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- categoryModal -->
<div id="modal-cat" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Level New/Edit</h5>
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
                        
                        <button onclick="modalCategory({table: '#table-cat', row: ''}); $('#modal-title').html('New Class')" class=""><i class="fa fa-ban"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
                                
                                <div class="form-group row">
                                    <label for="cat_code" class="col-md-4 col-form-label text-sm-right">Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="cat_code" maxlength="20">
                                        </div>
                                        <code class="small text-danger" id="cat_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="cat_code_old" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="cat_name" class="col-md-4 col-form-label text-sm-right">Level Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="cat_name" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="cat_name--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="alpha" class="col-md-4 col-form-label text-sm-right">Alpha Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="alpha" maxlength="10">
                                        </div>
                                        <code class="small text-danger" id="alpha--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="digit" class="col-md-4 col-form-label text-sm-right">Digit | Fees <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="col-xl-3 col-lg-6 col-12">
                                                <input class="form-control form-control-sm" type="text" id="digit" maxlength="10">
                                            </div>
                                            <input class="form-control form-control-sm money" type="text" id="fees" placeholder="Fees" maxlength="20">
                                        </div>
                                        <code class="small text-danger" id="digit--help">&nbsp;</code>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-lg-6 px-3">
                                
                            </div>
                        
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-category" class="" type="button" style="margin-left: auto" onclick="saveCategory({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
                        </div>
                    
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
    let tableCategory = null;
    // console.log(userAccess);
    
    //
    let deleteCategory = (json) => {
        let tableCategory = $(json.table).DataTable();
        let data = tableCategory.row(json.row).data(); // data["colName"]
        // console.log(data);return;
        
        if (!confirm('Delete class: ' + data['cat_code'] + ' : ' + data['cat_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/category/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableCategory.ajax.reload(null, false);
            
        }, 'JSON');
    }
    
    let modalcategory = (json) => {
        let tableCategory = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableCategory.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        
        $('#cat_code_old').val(data['cat_code'] ?? '');
        $('#cat_code').val(data['cat_code'] ?? 'AUTO');
        $('#cat_name').val(data['cat_name']);
        $('#fees').val(number_format(data['fees']));
        $('#status').prop({checked: data['status'] === '1' || !data['cat_code']});
        //
        $('#alpha').val(data['alpha']);
        $('#digit').val(data['digit']);
        
        //
        $('#modal-cat').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
        
        // if (data['risk_code'] !== undefined) loadSubrisk(data);
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    // let tableSubrisk = $("#table-subrisk").DataTable();
    
    // let loadSubrisk = (json) => {
    //     //console.log(json);
        
    //     $('#modalNav a[href="#page_2"]').removeClass('d-none');
        
    //     // dataTables
    //     let url = "<?php echo URL_ROOT ?>/system/systemSetting/getSubrisks/?user_log=<?php echo $data['params']['user_log'] ?>";
    //     //$.post(url, {agent_code: json.agent_code}, function(data) { console.log(data) }); return;
        
    //     tableSubrisk.destroy();
        
    //     tableSubrisk = $('#table-subrisk').DataTable({
    //         "processing": true,
    //         //"serverSide": true,
    //         "ajax": {
    //             "url": url,
    //             "type": "POST",
    //             "data": {
    //                 _option: 'risk_code',
    //                 risk_code: json.risk_code
    //             },
    //         },
    //         "columns": [
    //             {"data": "subrisk_code", "render": function (data, type, row, meta) {
    //                     return '<input type="checkbox" class="subrisk" value="' + data + '">'
    //                 }},
    //             {"data": "subrisk_name"},
    //             { "data": "subrisk_code" },
    //             { "data": "risk_name" },
    //             { "data": "alpha" },
    //             { "data": "digit" },
    //             { "data": "modified_on" }
    //         ],
    //         "columnDefs": [
    //             {"targets": [0], "sortable": false, "searchable": false},
    //         ],
    //         "aaSorting": [[1, "asc"]],
    //         "initComplete": function (settings, json_) {
    //             //console.log(json);
    //         }
    //     });
    // }
    
    // tableSubrisk.search('', false, true);
    // //
    // tableSubrisk.row(this).remove().draw(false);
    
    // //
    // $('#table-subrisk tbody').on('click', 'td', function () {
    //     //
    //     let data = tableSubrisk.row($(this)).data(); // data["colName"]
    //     //console.log(data)
    //     let rowId = $(this).parent('tr').index();
    //     //console.log(rowId)
    
    //     localStorage.setItem('selected-row', rowId);
        
    //     if (!data) return;
        
    //     //console.log(this.cellIndex);
    //     if (this.cellIndex != 0) {
    //         //
    //         localStorage.setItem('modalOpen', '1');
    //         parent.location.assign('<?php echo URL_ROOT ?>/system/subrisk/?user_log=<?php echo $data['params']['user_log'] ?>&subrisk_code=' + data['subrisk_code'] + '#page_1');
    //     }
    // });
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let risk_code = '<?php echo $data['params']['risk_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
            
            if (risk_code !== '') {
                
                let tableRisk = $('#table-risk').DataTable();
                
                tableRisk.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === risk_code) {
                            //console.log(v, i);
                            modalRisk({table: '#table-risk', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                            
                            return false;
                        }
                    });
                });
                
            } else modalRisk({table: '#table-risk', row: ''});
        }
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveCategory = (json) => {
        //console.log(json);
        let tableCategory = $(json.table).DataTable();
        
        if ($('#save-cat').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-cat').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('risk', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        // console.log(form_data); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/category/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-category').html('<i class="fa fa-spinner fa-spin" style="white"></i> Save Changes');
                $('#save-category').prop({disabled: true});
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
                $('#save-category').html('Save Changes');
                $('#save-category').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                //
                tableCategory.ajax.reload(null, false);
                //
                $('#cat_code').val(data.data.cat_code);
                $('#cat_code_old').val(data.data.cat_code);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-category').html('Save Changes');
                $('#save-category').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    let updateStatus = (json)=>{
        let target ,status, code, rw;
        let url = "<?php echo URL_ROOT ?>/system/category/updataCategory/?user_log=<?php echo $data['params']['user_log'] ?>"
        let data = {};
        rw = $($(json.elem.target).closest('tr')['0']).find('td:eq(0)')['0'];
        // console.log($($(rw)['0']).find(':first-child'));return;
        target = $($(json.elem.target).closest('tr')['0']).find('td:eq(1)')['0'];
        status = json.elem.target.checked ? '1' : "0";
        code = target.textContent || target.innerText
        data['status'] = status;
        data['code'] = code;
        $.post(url, data, function(data){
            if(!data.status){
                
                new Noty({type: 'Warning', text: '<h5>Warning</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            tableCategory.ajax.reload(null, false);
            // $($(rw)['0']).css("background-color", status === '1' ? 'green' : 'red' );;
            // $($(rw)['0']).find(':first-child').css("background-color", status === '1' ? 'green' : 'white' );
            // new Noty({type: 'success', text: '<h5>success</h5>', timeout: 10000}).show();

        }, 'json')
    }
    
    //
    $(() => {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        tableCategory = $("#table-cat").DataTable();
        
        let loadCategory = (json) => {
            
            // dataTables
            let vv = false;
            let url = "<?php echo URL_ROOT ?>/system/category/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
            
            tableCategory.destroy();
            
            tableCategory = $('#table-cat').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "cat_name", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalcategory({table: \'#table-cat\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteCategory({table: \'#table-cat\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                            ;
                            }
                    },
                    {"data": "cat_name"},
                    {"data": "alpha"},
                    {"data": "digit"},
                    {"data": "fees", "render": function(data, type, row, meta){
                        return number_format(data);
                    }},
                    {"data": "submit_on"},
                    {"data": "status", "render": function(data,type,row,meta){
                        // console.log(data)
                        return '<input type="checkbox" '+(data === "1" ? "checked": "")+' onclick="updateStatus({elem: event})">'
                    }}
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[3, "asc"]],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    // modalAuto();
                }
            });
        }
        
        loadCategory({});
        
        //
        tableCategory.search('', false, true);
        //
        tableCategory.row(this).remove().draw(false);
        
        //
        $('#table-cat tbody').on('click', 'td', function () {
            //
            //let data = tableRisk.row($(this)).data(); // data["colName"]
            let data = tableCategory.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
            
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                if(this.cellIndex != 6){
                    modalcategory({table: '#table-cat', row: data});
                    //
                    $('#modalNav a[href="#page_1"]').tab('show');

                }
            }
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        $('#modal-cat').on('hidden.bs.modal', function () {
            tableCategory.ajax.reload(null, false);
        });
        
        // ////////////////////////////////////////////////////////////////////////////////////////
        
        //
        // let checkForm = new timer();
        // checkForm.start(function () {
        //     //
        //     checkForm.stop();
        //     //
        //     let disabled = false;
            
        //     // risk
        //     if ($('#modal-cat').hasClass('show')) {
                
        //         // risk_code
        //         if ($('#cat_code').val().trim() === '' && $('#cat_code_old').val().trim() !== '') {
        //             disabled = true;
        //             $('#cat_code--help').html('CLASS CODE REQUIRED')
        //         } else {
        //             $('#cat_code--help').html('&nbsp;')
        //         }
                
        //         // risk_name
        //         if ($('#cat_name').val().trim() === '') {
        //             disabled = true;
        //             $('#cat_name--help').html('CLASS NAME REQUIRED')
        //         } else {
        //             $('#cat_name--help').html('&nbsp;')
        //         }
                
        //         // alpha
        //         if ($('#alpha').val().trim() === '') {
        //             disabled = true;
        //             $('#alpha--help').html('ALPHA CODE REQUIRED')
        //         } else {
        //             $('#alpha--help').html('&nbsp;')
        //         }
                
        //         // digit
        //         if ($('#digit').val().trim() === '') {
        //             disabled = true;
        //             $('#digit--help').html('DIGIT CODE REQUIRED')
        //         } else {
        //             $('#digit--help').html('&nbsp;')
        //         }
                
        //         //
        //         if (saving) disabled = true;
        //         $('#save-category').prop({disabled: disabled});
                
        //     }
            
        //     checkForm.start();
            
        // }, 500, true); //
        
    });

</script>