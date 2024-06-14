<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Account Group</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button href="javascript:void(0)" onclick="modalActgroup({table: '#table-actgroup', row: ''}); $('#modal-title').html('New Account Group')"><i class="fa fa-plus"></i> Add</button>
            <div style="overflow: hidden">
                <table id="table-actgroup" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>Group Name</th>
                        <th>Group Code</th>
                        <th>Base</th>
                        <th>Min</th>
                        <th>Max</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- actgroupModal -->
<div id="modal-actgroup" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Account Group New/Edit</h5>
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
    
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary mb-3 base_link" onclick="localStorage.setItem('modalOpen', '1'); parent.location.assign($(this).data('href'))"></a>
                        <button href="javascript:void(0)" onclick="modalActgroup({table: '#table-actgroup', row: ''}); $('#modal-title').html('New Account Group')" class="mb-3"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="base_code" class="col-md-4 col-form-label text-sm-right">Base Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="base_code" style="width: 100%" onchange="getLastGroup()"></select>
                                        </div>
                                        <code class="small text-danger" id="base_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="group_code" class="col-md-4 col-form-label text-sm-right">Group Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="group_code" maxlength="4">
                                        </div>
                                        <code class="small text-danger" id="group_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="group_code_old" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="group_name" class="col-md-4 col-form-label text-sm-right">Group Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="group_name" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="group_name--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="range_min" class="col-md-4 col-form-label text-sm-right">Range MIN <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="range_min" maxlength="7">
                                        </div>
                                        <code class="small text-danger" id="range_min--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="range_max" class="col-md-4 col-form-label text-sm-right">Range MAX <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="range_max" maxlength="7">
                                        </div>
                                        <code class="small text-danger" id="range_max--help">&nbsp;</code>
                                    </div>
                                </div>
                            
                            </div>
                        
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-actgroup" type="button" style="margin-left: auto" onclick="saveActgroup({})"><i class="fa fa-save"></i> Save Changes</button>
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
    // console.log(userAccess);
    
    //
    let deleteActgroup = (json) => {
        //console.log(json);
        let tableActgroup = $(json.table).DataTable();
        let data = tableActgroup.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Account Group: ' + data['group_code'] + ' : ' + data['group_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/actgroup/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableActgroup.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalActgroup = (json) => {
        let tableActgroup = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableActgroup.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['group_code'] === undefined) {
            //
        }
        // console.log(data);
    
        $('.base_link').each(function() {
            let obj = $(this);
            obj.css({display: data['group_code'] === undefined ? 'none' : 'inline-block'});
            obj.data({href: '<?php echo URL_ROOT ?>/finance/actbase/?user_log=<?php echo $data['params']['user_log'] ?>&base_code=' + data['base_code'] + '&rand=' + Math.random() + '#page_1'}).html('<i class="fa fa-undo"></i> ' + strEllipsis(data['base_name'], 15));
        });
        
        $('#group_code_old').val(data['group_code'] ?? '');
        $('#group_code').val(data['group_code'] ?? 'AUTO');
        $('#group_name').val(data['group_name']);
        $('#status').prop({checked: data['status'] === '1' || !data['group_code']});
        //
        data['base_code'] = data['base_code'] ?? '';
        $('#base_code').append(new Option(data['base_name'], data['base_code'], true, true)).trigger('change');
        //
        $('#range_min').val(data['range_min']);
        $('#range_max').val(data['range_max']);
        
        //
        $('#modal-actgroup').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    let getLastGroup = () => {
        
        let base_code = $('#base_code').val();
        if (base_code === '' || base_code === null) return false;
    
        $.post('<?php echo URL_ROOT ?>/finance/AccountSetting/getActgroups/?user_log=<?php echo $data['params']['user_log'] ?>', {_option: 'base_code', base_code: base_code}, function (data) {
            // console.log(data);
            let group_codes = data.map((obj) => { return getInt(obj.group_code) });
            // console.log(group_codes);

            if ($('#group_code_old').val() === '') {
                $('#group_code').val(Math.max(...group_codes) + 10);
                $('#range_min').val($('#group_code').val() + '001');
                $('#range_max').val($('#group_code').val() + '999');
            }
            
        }, 'JSON');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let group_code = '<?php echo $data['params']['group_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (group_code !== '') {
            
                let tableActgroup = $('#table-actgroup').DataTable();
            
                tableActgroup.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === group_code) {
                            //console.log(v, i);
                            modalActgroup({table: '#table-actgroup', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalActgroup({table: '#table-actgroup', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveActgroup = (json) => {
        //console.log(json);
        let tableActgroup = $(json.table).DataTable();
        
        if ($('#save-actgroup').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-actgroup').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('actgroup', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        // console.log(value);
        // }
        //console.log($('#modal-client').find('input, select, textarea').length); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/actgroup/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-actgroup').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-actgroup').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                console.log(data);
                //
                saving = false;
                //
                $('#save-actgroup').html('Save Changes');
                $('#save-actgroup').prop({disabled: false});
                
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
                tableActgroup.ajax.reload(null, false);
                //
                $('#group_code').val(data.data.group_code);
                $('#group_code_old').val(data.data.group_code);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-actgroup').html('Save Changes');
                $('#save-actgroup').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
        //
        $('#base_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getActbases/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                    return { results: response };
                },
                cache: true
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableActgroup = $("#table-actgroup").DataTable();
    
        let loadActgroup = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/actgroup/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableActgroup.destroy();
        
            tableActgroup = $('#table-actgroup').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "group_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a class="dropdown-toggle" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalActgroup({table: \'#table-actgroup\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteActgroup({table: \'#table-actgroup\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Delete Item</a>') +
                                '</div>';
                        }
                    },
                    {"data": "group_name"},
                    {"data": "group_code"},
                    {"data": "base_name", "render": function (data, type, row, meta) {
                        return '(' + row['base_code'] + ') ' + data;
                        }},
                    {"data": "range_min"},
                    {"data": "range_max"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "asc"]],
                "initComplete": function (settings, json) {
                    
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //console.log(json);
                    modalAuto();
                }
            });
        }
    
        loadActgroup({});
    
        //
        tableActgroup.search('', false, true);
        //
        tableActgroup.row(this).remove().draw(false);
    
        //
        $('#table-actgroup tbody').on('click', 'td', function () {
            //
            //let data = tableActgroup.row($(this)).data(); // data["colName"]
            let data = tableActgroup.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalActgroup({table: '#table-actgroup', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-actgroup').on('hidden.bs.modal', function () {
            tableActgroup.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // group_code
            if ($('#modal-actgroup').hasClass('show')) {
    
                let base_code = getInt($('#base_code').val());
                let group_code = getInt($('#group_code').val());
                let range_min = getInt($('#range_min').val());
                let range_max = getInt($('#range_max').val());
    
                // group_code
                if ($('#group_code').val().trim() === '' && $('#group_code_old').val().trim() !== '') {
                    disabled = true;
                    $('#group_code--help').html('ACCOUNT GROUP CODE REQUIRED')
                } else if (group_code < base_code * 100 || group_code > base_code * 100 + 99) {
                    disabled = true;
                    $('#group_code--help').html('ACCOUNT GROUP CODE INVALID')
                } else {
                    $('#group_code--help').html('&nbsp;')
                }
            
                // group_name
                if ($('#group_name').val().trim() === '') {
                    disabled = true;
                    $('#group_name--help').html('ACCOUNT GROUP NAME REQUIRED')
                } else {
                    $('#group_name--help').html('&nbsp;')
                }
    
                // base_code
                if ($('#base_code').val() === null || $('#base_code').val() === '') {
                    disabled = true;
                    $('#base_code--help').html('ACCOUNT BASE CODE REQUIRED')
                } else if ($('#base_code').val() !== $('#group_code').val().substr(0, 2)) {
                    disabled = true;
                    $('#base_code--help').html('ACCOUNT BASE|GROUP CODE INVALID')
                } else {
                    $('#base_code--help').html('&nbsp;')
                }
                
                // range_min
                if (range_min !== group_code * 1000 + 1) {
                    disabled = true;
                    $('#range_min--help').html('RANGE MIN INVALID')
                } else if ($('#range_min').val().substr(0, 4) !== $('#group_code').val()) {
                    disabled = true;
                    $('#range_min--help').html('ACCOUNT GROUP|MIN CODE INVALID')
                } else {
                    $('#range_min--help').html('&nbsp;')
                }
                
                // range_max
                if (range_max !== group_code * 1000 + 999) {
                    disabled = true;
                    $('#range_max--help').html('RANGE MAX INVALID')
                } else if ($('#range_max').val().substr(0, 4) !== $('#group_code').val()) {
                    disabled = true;
                    $('#range_max--help').html('ACCOUNT GROUP|MAX CODE INVALID')
                } else {
                    $('#range_max--help').html('&nbsp;')
                }
                
                // range_min < range_max
                if (range_min >= range_max) {
                    disabled = true;
                    $('#range_max--help').html('RANGE MIN < MAX INVALID')
                } else {
                    $('#range_max--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-actgroup').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



