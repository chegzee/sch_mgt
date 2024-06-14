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
            <li class="breadcrumb-item active" aria-current="page">Currencies</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button href="javascript:void(0)" onclick="modalActBank({table: '#table-actBank', row: ''}); $('#modal-title').html('New Currency')" class=" mb-3"><i class="fa fa-plus"></i> Add</button>
            <div style="overflow: hidden">
                <table id="table-actBank" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>Account Code</th>
                        <th>Account No.</th>
                        <th>Account Name</th>
                        <th>Bank Name</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- currencyModal -->
<div id="modal-actBank" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Currency New/Edit</h5>
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
    
                        <button href="javascript:void(0)" onclick="modalCurrency({table: '#table-currency', row: ''}); $('#modal-title').html('New Currency')" class="mb-3"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
            
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
                                            <select class="form-control form-control-sm" id="account_code" style="width:90%;" maxlength="7"></select>
                                        </div>
                                        <code class="small text-danger" id="account_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="account_code_old" readonly>
                                    <input type="hidden" id="account_name" readonly>
                                </div>
    
                                <div class="form-group row">
                                    <label for="account_number" class="col-md-4 col-form-label text-sm-right">Account Number <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="account_number" maxlength="20">
                                        <code class="small text-danger" id="account_number--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <!-- <div class="form-group row">
                                    <label for="account_name" class="col-md-4 col-form-label text-sm-right">Account name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="account_name" maxlength="20">
                                        <code class="small text-danger" id="account_name--help">&nbsp;</code>
                                    </div>
                                </div> -->
    
                                <div class="form-group row">
                                    <label for="bank_name" class="col-md-4 col-form-label text-sm-right">Bank name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="bank_name" style="width:100%;" maxlength="20"></select>
                                        <code class="small text-danger" id="bank_name--help">&nbsp;</code>
                                    </div>
                                </div>
                            
                            </div>
                        
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-actBank" class="" type="button" style="margin-left: auto" onclick="saveActBank({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
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
    let deleteActBank = (json) => {
        // console.log(json);
        let tableAccount = $(json.table).DataTable();
        let data = tableAccount.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Account: ' + data['account_code'] + ' : ' + data['account_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/actBank/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
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

    let modalActBank = (json) => {
        let tableActBank = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableActBank.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['currency_code'] === undefined) {
            //
        }
        // console.log(data)

        $('#account_code_old').val(data['account_code'] ?? '');
        $('#account_code').append(new Option(data['account_name'] ?? '', data['account_code'] ?? '')).trigger('change');
        //
        $('#account_number').val(data['account_number']);
        $('#status').val(data['status']).prop({checked: ((data['status'] ?? '') === "1") ? true : false});
        $('#account_name').val(data['account_name']);
        $('#bank_name').append(new Option(data['bank_name'] ?? '', data['bank_name'] ?? '', true, true)).trigger('change');
        
        //
        $('#modal-actBank').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
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
    let saveActBank = (json) => {
        //console.log(json);
        let tableActBank = $(json.table).DataTable();
        
        if ($('#save-actBank').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-actBank').find('input, select, textarea'), function (i, obj) {
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
        // console.log(form_data);
        // }
        //console.log($('#modal-client').find('input, select, textarea').length); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/actBank/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-actBank').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-actBank').prop({disabled: true});
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
                $('#save-actBank').html('Save Changes');
                $('#save-actBank').prop({disabled: false});
                
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
                tableActBank.ajax.reload(null, false);
                //
                // $('#account_code').val(data.data.account_code);
                // $('#account_code_old').val(data.data.account_code);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-actBank').html('Save Changes');
                $('#save-actBank').prop({disabled: false});
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
        $('#bank_name').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/accountSetting/getAllBanksName",
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

        $('#account_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/accountSetting/getAccounts",
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
        }).on("select2:select", ()=>{
           let account_name = $('#account_code').find(':selected').html();
           $('#account_name').val(account_name);
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableActBank = $("#table-actBank").DataTable();
    
        let loadActBank = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/actBank/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableActBank.destroy();
        
            tableActBank = $('#table-actBank').DataTable({
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
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalActBank({table: \'#table-actBank\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit </a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteActBank({table: \'#table-actBank\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Delete </a>') +
                                '</div>';
                        }
                    },
                    {"data": "account_code"},
                    {"data": "account_number"},
                    {"data": "account_name"},
                    {"data": "bank_name"}
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    console.log(json);
                    // modalAuto();
                }
            });
        }
    
        loadActBank({});
    
        //
        tableActBank.search('', false, true);
        //
        tableActBank.row(this).remove().draw(false);
    
        //
        $('#table-actBank tbody').on('click', 'td', function () {
            //
            //let data = tableCurrency.row($(this)).data(); // data["colName"]
            let data = tableActBank.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalActBank({table: '#table-actBank', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-actBank').on('hidden.bs.modal', function () {
            tableActBank.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // currency
            if ($('#modal-currency').hasClass('show')) {
            
                // currency_main
                // if ($('#currency_main').val().trim() === '') {
                //     disabled = true;
                //     $('#currency_main--help').html('CURRENCY MAIN REQUIRED')
                // } else {
                //     $('#currency_main--help').html('&nbsp;')
                // }
            
                // // currency_sub
                // if ($('#currency_sub').val().trim() === '') {
                //     disabled = true;
                //     $('#currency_sub--help').html('CURRENCY SUB REQUIRED')
                // } else {
                //     $('#currency_sub--help').html('&nbsp;')
                // }
            
                // // currency_rate
                // if (getFloat($('#currency_rate').val() ?? '') <= 0) {
                //     disabled = true;
                //     $('#currency_rate--help').html('CURRENCY RATE REQUIRED')
                // } else {
                //     $('#currency_rate--help').html('&nbsp;')
                // }
    
                
                //
                if (saving) disabled = true;
                $('#save-currency').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



