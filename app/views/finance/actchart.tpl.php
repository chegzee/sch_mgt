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
            <li class="breadcrumb-item active" aria-current="page">Account</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button href="javascript:void(0)" onclick="modalAccount({table: '#table-account', row: ''}); $('#modal-title').html('New Account')" class="mb-3"><i class="fa fa-plus"></i> Add</button>
            <div style="overflow: hidden">
                <table id="table-account" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>Account Name</th>
                        <th>Account Code</th>
                        <th>Group</th>
                        <th>Base</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- accountModal -->
<div id="modal-account" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Account New/Edit</h5>
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
    
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary mb-3 group_link" onclick="localStorage.setItem('modalOpen', '1'); parent.location.assign($(this).data('href'))"></a>
                        <button href="javascript:void(0)" onclick="modalAccount({table: '#table-account', row: ''}); $('#modal-title').html('New Account')" class="mb-3"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="group_code" class="col-md-4 col-form-label text-sm-right">Group Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="group_code" style="width: 100%" onchange="getLastAccount()"></select>
                                        </div>
                                        <code class="small text-danger" id="group_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="account_code" class="col-md-4 col-form-label text-sm-right">Account Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="account_code" maxlength="7">
                                        </div>
                                        <code class="small text-danger" id="account_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="account_code_old" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="account_name" class="col-md-4 col-form-label text-sm-right">Account Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="account_name" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="account_name--help">&nbsp;</code>
                                    </div>
                                </div>
                            
                            </div>
                        
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-account" type="button" style="margin-left: auto" onclick="saveAccount({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
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
    let deleteAccount = (json) => {
        //console.log(json);
        let tableAccount = $(json.table).DataTable();
        let data = tableAccount.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Account: ' + data['account_code'] + ' : ' + data['account_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/account/actchart/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableAccount.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalAccount = (json) => {
        let tableAccount = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableAccount.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['account_code'] === undefined) {
            //
        }
        // console.log(data);
    
        $('.group_link').each(function() {
            let obj = $(this);
            obj.css({display: data['account_code'] === undefined ? 'none' : 'inline-block'});
            obj.data({href: '<?php echo URL_ROOT ?>/finance/actgroup/?user_log=<?php echo $data['params']['user_log'] ?>&group_code=' + data['group_code'] + '&rand=' + Math.random() + '#page_1'}).html('<i class="fa fa-undo"></i> ' + strEllipsis(data['group_name'], 15));
        });
        
        $('#account_code_old').val(data['account_code'] ?? '');
        $('#account_code').val(data['account_code'] ?? 'AUTO');
        $('#account_name').val(data['account_name']);
        $('#status').prop({checked: data['status'] === '1' || !data['account_code']});
        //
        data['group_code'] = data['group_code'] ?? '';
        $('#group_code').append(new Option(data['group_name'], data['group_code'], true, true)).trigger('change');
        
        //
        $('#modal-account').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    let getLastAccount = () => {
        
        let group_code = $('#group_code').val();
        // console.log(group_code);return;
        if (group_code === '' || group_code === null) return false;
    
        $.post('<?php echo URL_ROOT ?>/finance/AccountSetting/getAccounts/?user_log=<?php echo $data['params']['user_log'] ?>', {_option: 'group_code', group_code: group_code}, function (data) {
            // console.log(data);
            let account_codes = data.map((obj) => { return getInt(obj.account_code) });
            // console.log(Math.max(...account_codes));

            if ($('#account_code_old').val() === '') {
                $('#account_code').val(Math.max(...account_codes) + 1);
            }
            
        }, 'JSON');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let account_code = '<?php echo $data['params']['account_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (account_code !== '') {
            
                let tableAccount = $('#table-account').DataTable();
            
                tableAccount.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === account_code) {
                            //console.log(v, i);
                            modalAccount({table: '#table-account', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalAccount({table: '#table-account', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveAccount = (json) => {
        //console.log(json);
        let tableAccount = $(json.table).DataTable();
        
        if ($('#save-account').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-account').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('account', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
            url: '<?php echo URL_ROOT ?>/finance/actchart/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-account').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-account').prop({disabled: true});
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
                $('#save-account').html('Save Changes');
                $('#save-account').prop({disabled: false});
                
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
                tableAccount.ajax.reload(null, false);
                //
                $('#account_code').val(data.data.account_code);
                $('#account_code_old').val(data.data.account_code);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-account').html('Save Changes');
                $('#save-account').prop({disabled: false});
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
        $('#group_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getActgroups/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        let tableAccount = $("#table-account").DataTable();
    
        let loadAccount = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/actchart/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableAccount.destroy();
        
            tableAccount = $('#table-account').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "account_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a dropdown-toggle" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></button>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalAccount({table: \'#table-account\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteAccount({table: \'#table-account\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Reverse Item</a>') +
                                '</div>';
                        }
                    },
                    {"data": "account_name"},
                    {"data": "account_code"},
                    {"data": "group_name", "render": function (data, type, row, meta) {
                        return '(' + row['group_code'] + ') ' + data;
                        }},
                    {"data": "base_name", "render": function (data, type, row, meta) {
                        return '(' + row['base_code'] + ') ' + data;
                        }},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    // console.log(json);
                    modalAuto();
                }
            });
        }
    
        loadAccount({});
    
        //
        tableAccount.search('', false, true);
        //
        tableAccount.row(this).remove().draw(false);
    
        //
        $('#table-account tbody').on('click', 'td', function () {
            //
            //let data = tableAccount.row($(this)).data(); // data["colName"]
            let data = tableAccount.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalAccount({table: '#table-account', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-account').on('hidden.bs.modal', function () {
            tableAccount.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // account_code
            if ($('#modal-account').hasClass('show')) {
    
                let group_code = getInt($('#group_code').val());
                let account_code = getInt($('#account_code').val());
    
                // account_code
                if ($('#account_code').val().trim() === '' && $('#account_code_old').val().trim() !== '') {
                    disabled = true;
                    $('#account_code--help').html('ACCOUNT CODE REQUIRED')
                } else if (account_code < group_code * 1000 + 1 || account_code > group_code * 1000 + 999) {
                    disabled = true;
                    $('#account_code--help').html('ACCOUNT CODE INVALID')
                } else {
                    $('#account_code--help').html('&nbsp;')
                }
            
                // account_name
                if ($('#account_name').val().trim() === '') {
                    disabled = true;
                    $('#account_name--help').html('ACCOUNT NAME REQUIRED')
                } else {
                    $('#account_name--help').html('&nbsp;')
                }
    
                // group_code
                if ($('#group_code').val() === null || $('#group_code').val() === '') {
                    disabled = true;
                    $('#group_code--help').html('ACCOUNT GROUP CODE REQUIRED')
                } else if ($('#group_code').val() !== $('#account_code').val().substr(0, 4)) {
                    disabled = true;
                    $('#group_code--help').html('ACCOUNT|GROUP CODE INVALID')
                } else {
                    $('#group_code--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-account').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



