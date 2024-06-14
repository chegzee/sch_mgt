<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
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
            <button onclick="modalSocialBehaviour({table: '#table-social_beh', row: ''}); $('#modal-title').html('New Term')"><i class="fa fa-plus"></i> Add</button>
            <button onclick="modalSocialRate({table: '#table-social-rate', row: ''});"><i class="fa fa-plus"></i> Social Rate</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-social_beh" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>Social Behaviour</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- term modal -->
<div id="modal-social_beh" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit</h5>
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
    
                        <!-- <button onclick="modalTerm({table: '#table-term', row: ''}); $('#modal-title').html('New Term period')"><i class="fa fa-plus"></i> Reset</button> -->
                        
                        <div class="row">
    
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="code" class="col-md-4 col-form-label text-sm-right">Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status" checked="checked">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="code" maxlength="100" readonly>
                                        </div>
                                        <code class="small text-danger" id="code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="code_old" readonly>
                                </div>
                                <div class="form-group row">
                                    <label for="social_behaviour" class="col-md-4 col-form-label text-sm-right">Behaviour <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="behaviour" maxlength="100"/>
                                        </div>
                                        <code class="small text-danger" id="social_behaviour--help">&nbsp;</code>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-social_behaviour" class="" type="button" style="margin-left: auto" onclick="saveSocialBehaviour({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
                        </div>
                    
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- social Rate -->
<div id="modal-social-rate" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Social Rate New</h5>
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
                        <div class="row" id="social_rate_form">
                            <div class="form-group row col-lg-4">
                                <label for="key_name" class="col-md-4 col-form-label text-sm-right">Key-name <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg" id="key_name" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="key_name--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="form-group row col-lg-4">
                                <label for="key_value" class="col-md-4 col-form-label text-sm-right">Key-value <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg" id="key_value" style="width: 100%"/>
                                    </div>
                                    <code class="small text-danger" id="key_value--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="col-12 form-group col-lg-3">
                                <button id="save-social_rate" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveSocialRate({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card card-style-1">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <div class="dataTables_wrapper">
                                    <table id="table-social-rate" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                        <thead>
                                            <tr>
                                                <th><i class="material-icons">build</i></th>
                                                <th>Name</th>
                                                <th>Value</th>
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
<!-- social Rate -->

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    let tableSocialRate = null;
    
    //
    let deleteSocialBehaviour = (json) => {
        // console.log(json);return
        let tableSocialBehaviour = $(json.table).DataTable();
        let data = tableSocialBehaviour.row(json.row).data(); // data["colName"]
        // console.log(data);return;
        if (!confirm('Delete Item')) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/socialBehaviour/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableSocialRate.ajax.reload(null, false);
            
        }, 'JSON');
    }
    let deleteSocialRate = (json) => {
        // console.log(json);return
        tableSocialRate = $(json.table).DataTable();
        let data = tableSocialRate.row(json.row).data(); // data["colName"]
        //  console.log(data);return;
        if (!confirm('Delete record: ' + data['code'] )) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/socialBehaviour/__delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableSocialRate.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalSocialBehaviour = (json) => {
        let tableSocialBehaviour = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableSocialBehaviour.row(json.row).data(); // data["colName"]
        // console.log(data)
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        $('#code').val((data['code'] ?? '') === '' ? 'AUTO' : data['code']);
        $('#code_old').val((data['code'] ?? '') === '' ? '' : data['code']);
        $('#behaviour').val((data['behaviour'] ?? '') === '' ? '' : data['behaviour']);
        
        //
        $('#modal-social_beh').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    let modalSocialRate = (json)=>{
        // console.log(json);
        tableSocialRate = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableSocialRate.row(json.row).data(); // data["colName"]
        // console.log(data['status'] ?? '');
        $('#key_name').val(data['key_name'] ?? '');
        $('#key_value').val(data['key_value'] ?? '');
        if(json.action === 'edit') return;

        // tableSocialRate = $('#table-exam-rate').DataTable();
        tableSocialRate.destroy();
        let url = "<?php echo URL_ROOT ?>/system/socialBehaviour/__list/?user_log=<?php echo $data['params']['user_log'] ?> ";
        
        tableSocialRate = $('#table-social-rate').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {},
            },
            "columns": [
                {
                    "data": "key_name", "width": 5, "render": function (data, type, row, meta) {
                        return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + ((row['key_value'] ?? '') === '' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalSocialRate({table: \'#table-social-rate\', row: \'' + meta['row'] + '\', action: \'edit\' })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteSocialRate({table: \'#table-social-rate\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                    }
                },
                {"data": "key_name"},
                {"data": "key_value"},
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

        
        $('#modal-social-rate').modal('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveSocialBehaviour = (json) => {
        //console.log(json);
        let tableSocialBehaviour = $(json.table).DataTable();
        
        if ($('#save-social_behaviour').prop('disabled')) return false;
        // console.log(f);return;
        
        //
        let form_data = new FormData();

        saving = true;
        //
        $.each($('#modal-social_beh #page_1').find('input, select, textarea'), function (i, obj) {
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
        // Display the values
        // for (var value of form_data.values()) {
        //     console.log(value);
        // }
        //   console.log(form_data); return;
        
        
        // process the form

        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/socialBehaviour/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-term').html('<i class="fa fa-spinner fa-spin" style="color:white"></i> Save Changes');
                $('#save-term').prop({disabled: true});
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
                $('#save-term').html('Save Changes');
                $('#save-term').prop({disabled: false});
                
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
                tableSocialBehaviour.ajax.reload(null, false);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-term').html('Save Changes');
                $('#save-term').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    //
    let saveSocialRate = (json)=>{
        // console.log(json);return;
    
        let form_data = new FormData();
        // //
        $.each($('#social_rate_form').find('input, select, textarea'), function (i, obj) {
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
            url: '<?php echo URL_ROOT ?>/system/socialBehaviour/___save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-social_rate').html('<i class="fa fa-spinner fa-spin" style=""></i> Save Changes');
                $('#save-social_rate').prop({disabled: true});
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
                $('#save-social_rate').html('Save Changes');
                $('#save-social_rate').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $('#save-social_rate').html('<i class="fa fa-save"></i> Save');
                tableSocialRate.ajax.reload(null, false);

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
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableSocialBehaviour = $("#table-social_beh").DataTable();
    
        let loadSocialBehaviour = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/system/socialBehaviour/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableSocialBehaviour.destroy();
        
            tableSocialBehaviour = $('#table-social_beh').DataTable({
                "processing": true,
                // "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalSocialBehaviour({table: \'#table-social_beh\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteSocialBehaviour({table: \'#table-social_beh\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                            ;
                        }
                    },
                    {"data": "behaviour"},
                ],
                "columnDefs": [
                    {"targets": [1], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "desc"]],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    //  modalAuto();
                }
            });
        }
    
        loadSocialBehaviour({});
    
        //
        tableSocialBehaviour.search('', false, true);
        //
        tableSocialBehaviour.row(this).remove().draw(false);
    
        //
        $('#table-social_beh tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableSocialBehaviour.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalSocialBehaviour({table: '#table-social_beh', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-social_beh').on('hidden.bs.modal', function () {
            tableSocialBehaviour.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // user
            if ($('#modal-social-rate').hasClass('show')) {

                // start_date
                if ($('#key_name').val().trim() === '') {
                    // disabled = true;
                    $('#key_name--help').html('VALUE REQUIRED')
                } else {
                    $('#key_name--help').html('&nbsp;')
                }
                // start_date
                if ($('#key_value').val().trim() === '') {
                    // disabled = true;
                    $('#key_value--help').html('VALUE REQUIRED')
                } else {
                    $('#key_value--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-social_rate').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



