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
            <li class="breadcrumb-item active" aria-current="page">Bank</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <a href="javascript:void(0)" onclick="modalBank({table: '#table-bank', row: ''}); $('#modal-title').html('New Bank')" class="btn btn-sm btn-primary mb-3"><i class="fa fa-plus"></i> Add</a>
            <div style="overflow: hidden">
                <table id="table-bank" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>Bank Account</th>
                        <th>Ledger Code</th>
                        <th>Ledger Name</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- bankModal -->
<div id="modal-bank" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Bank New/Edit</h5>
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
    
                        <a href="javascript:void(0)" onclick="modalBank({table: '#table-bank', row: ''}); $('#modal-title').html('New Bank')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> Reset</a>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
                                
                                <div class="form-group row">
                                    <label for="bank_account" class="col-md-4 col-form-label text-sm-right">Bank Account <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="bank_account" maxlength="100">
                                        </div>
                                        <code class="small text-danger" id="bank_account--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="bank_account_old" readonly>
                                </div>
    
                                <div class="form-group row">
                                    <label for="account_code" class="col-md-4 col-form-label text-sm-right">Ledger Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="account_code" style="width: 100%"></select>
                                        </div>
                                        <code class="small text-danger" id="account_code--help">&nbsp;</code>
                                    </div>
                                </div>
                            
                            </div>
                        
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-bank" class="btn btn-success" type="button" style="margin-left: auto" onclick="saveBank({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
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
    let deleteBank = (json) => {
        //console.log(json);
        let tableBank = $(json.table).DataTable();
        let data = tableBank.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Bank: ' + data['bank_account'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/account/bank/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableBank.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalBank = (json) => {
        let tableBank = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableBank.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['bank_account'] === undefined) {
            //
        }
        // console.log(data);
        
        $('#bank_account_old').val(data['bank_account'] ?? '');
        $('#bank_account').val(data['bank_account'] ?? '');
        $('#status').prop({checked: data['status'] === '1' || !data['bank_account']});
        //
        data['account_code'] = data['account_code'] ?? '';
        $('#account_code').append(new Option(data['account_name'], data['account_code'], true, true)).trigger('change');
        
        //
        $('#modal-bank').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let bank_account = '<?php echo $data['params']['bank_account'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (bank_account !== '') {
            
                let tableBank = $('#table-bank').DataTable();
            
                tableBank.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === bank_account) {
                            //console.log(v, i);
                            modalBank({table: '#table-bank', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalBank({table: '#table-bank', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveBank = (json) => {
        //console.log(json);
        let tableBank = $(json.table).DataTable();
        
        if ($('#save-bank').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-bank').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('bank', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
            url: '<?php echo URL_ROOT ?>/account/bank/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-bank').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-bank').prop({disabled: true});
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
                $('#save-bank').html('Save Changes');
                $('#save-bank').prop({disabled: false});
                
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
                tableBank.ajax.reload(null, false);
                //
                $('#bank_account').val(data.data.bank_account);
                $('#bank_account_old').val(data.data.bank_account);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-bank').html('Save Changes');
                $('#save-bank').prop({disabled: false});
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
        $('#account_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/account/accountSetting/getAccounts/?user_log=<?php echo $data['params']['user_log'] ?>",
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
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableBank = $("#table-bank").DataTable();
    
        let loadBank = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/account/bank/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableBank.destroy();
        
            tableBank = $('#table-bank').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "bank_account", "width": 5, "render": function (data, type, row, meta) {
                            return '<button class="btn btn-xs ' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') + ' dropdown-toggle" type="button" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i></button>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalBank({table: \'#table-bank\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteBank({table: \'#table-bank\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Reverse Item</a>') +
                                '</div>';
                        }
                    },
                    {"data": "bank_account"},
                    {"data": "account_code"},
                    {"data": "account_name"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    //console.log(json);
                    modalAuto();
                }
            });
        }
    
        loadBank({});
    
        //
        tableBank.search('', false, true);
        //
        tableBank.row(this).remove().draw(false);
    
        //
        $('#table-bank tbody').on('click', 'td', function () {
            //
            //let data = tableBank.row($(this)).data(); // data["colName"]
            let data = tableBank.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalBank({table: '#table-bank', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-bank').on('hidden.bs.modal', function () {
            tableBank.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // bank
            if ($('#modal-bank').hasClass('show')) {
            
                // bank_account
                if ($('#bank_account').val().trim() === '') {
                    disabled = true;
                    $('#bank_account--help').html('BANK ACCOUNT REQUIRED')
                } else {
                    $('#bank_account--help').html('&nbsp;')
                }
            
                // account_code
                if ($('#account_code').val() === null || $('#account_code').val() === '') {
                    disabled = true;
                    $('#account_code--help').html('ACCOUNT CODE REQUIRED')
                } else {
                    $('#account_code--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-bank').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



