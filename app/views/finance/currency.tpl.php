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
            
            <button href="javascript:void(0)" onclick="modalCurrency({table: '#table-currency', row: ''}); $('#modal-title').html('New Currency')" class="mb-3"><i class="fa fa-plus"></i> Add</button>
            <div style="overflow: hidden">
                <table id="table-currency" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>Currency Code</th>
                        <th>Currency Main</th>
                        <th>Currency Sub</th>
                        <th>Currency Rate</th>
                        <th>Currency Symbol</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- currencyModal -->
<div id="modal-currency" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
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
                                    <label for="currency_code" class="col-md-4 col-form-label text-sm-right">Currency Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="currency_code" maxlength="20">
                                        <code class="small text-danger" id="currency_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="currency_code_old" readonly>
                                </div>
    
                                <div class="form-group row">
                                    <label for="currency_main" class="col-md-4 col-form-label text-sm-right">Currency Main <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="currency_main" maxlength="20">
                                        <code class="small text-danger" id="currency_main--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="currency_sub" class="col-md-4 col-form-label text-sm-right">Currency Sub <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="currency_sub" maxlength="20">
                                        <code class="small text-danger" id="currency_sub--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="currency_rate" class="col-md-4 col-form-label text-sm-right">Currency Rate <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm decimal" type="text" id="currency_rate" maxlength="20">
                                        <code class="small text-danger" id="currency_rate--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="html_code" class="col-md-4 col-form-label text-sm-right">Currency Symbol <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="html_code" maxlength="20">
                                        <code class="small text-danger" id="html_code--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="remarks" class="col-md-4 col-form-label text-sm-right">Remarks <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="remarks" maxlength="20">
                                        <code class="small text-danger" id="remarks--help">&nbsp;</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2 d-flex">
                            <button id="save-currency" type="button" style="margin-left: auto" onclick="saveCurrency({})"><i class="fa fa-save"></i> Save Changes</button>
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
    let deleteCurrency = (json) => {
        //console.log(json);
        let tableCurrency = $(json.table).DataTable();
        let data = tableCurrency.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Currency: ' + data['currency_code'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/currency/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>WARNING!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>SUCCESS</h5>', timeout: 10000}).show();
            //
            tableCurrency.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalCurrency = (json) => {
        let tableCurrency = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableCurrency.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['currency_code'] === undefined) {
            //
        }
        // console.log(data);
        
        $('#currency_code_old').val(data['currency_code'] ?? '');
        $('#currency_code').val(data['currency_code'] ?? '');
        //
        $('#currency_main').val(data['currency_main']);
        $('#currency_sub').val(data['currency_sub']);
        $('#currency_rate').val(data['currency_rate']);
        $('#html_code').val(data['html_code']);
        $('#remarks').val(data['remarks']);
        
        //
        $('#modal-currency').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let currency_code = '<?php echo $data['params']['currency_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (currency_code !== '') {
            
                let tableCurrency = $('#table-currency').DataTable();
            
                tableCurrency.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === currency_code) {
                            //console.log(v, i);
                            modalCurrency({table: '#table-cureency', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalCurrency({table: '#table-currency', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveCurrency = (json) => {
        //console.log(json);
        let tableCurrency = $(json.table).DataTable();
        
        if ($('#save-currency').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-currency').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('currency', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
            url: '<?php echo URL_ROOT ?>/finance/currency/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-currency').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-currency').prop({disabled: true});
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
                $('#save-currency').html('Save Changes');
                $('#save-currency').prop({disabled: false});
                
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
                tableCurrency.ajax.reload(null, false);
                //
                $('#currency_code').val(data.data.currency_code);
                $('#currency_code_old').val(data.data.currency_code);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-currency').html('Save Changes');
                $('#save-currency').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        // $('input[type=text]').on('blur change', function () {
        //     $(this).val($(this).val().trim().toUpperCase());
        // });
    
        //
        $('#account_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/accountSetting/getAccounts/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        let tableCurrency = $("#table-currency").DataTable();
    
        let loadCurrency = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/currency/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableCurrency.destroy();
        
            tableCurrency = $('#table-currency').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "currency_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a class="dropdown-toggle" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="' + ((row['status'] ?? '') !== '1' ? 'btn-outline-danger' : 'btn-outline-success') + ' fa fa-cog"></i></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalCurrency({table: \'#table-currency\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteCurrency({table: \'#table-currency\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Reverse Item</a>') +
                                '</div>';
                        }
                    },
                    {"data": "currency_code"},
                    {"data": "currency_main"},
                    {"data": "currency_sub"},
                    {"data": "currency_rate"},
                    {"data": "html_code"},
                    {"data": "remarks"},
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
    
        loadCurrency({});
    
        //
        tableCurrency.search('', false, true);
        //
        tableCurrency.row(this).remove().draw(false);
    
        //
        $('#table-currency tbody').on('click', 'td', function () {
            //
            //let data = tableCurrency.row($(this)).data(); // data["colName"]
            let data = tableCurrency.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalCurrency({table: '#table-currency', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-currency').on('hidden.bs.modal', function () {
            tableCurrency.ajax.reload(null, false);
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
            
                // currency_code
                if ($('#currency_code').val().trim() === '') {
                    disabled = true;
                    $('#currency_code--help').html('CURRENCY CODE REQUIRED')
                } else {
                    $('#currency_code--help').html('&nbsp;')
                }
            
                // currency_main
                if ($('#currency_main').val().trim() === '') {
                    disabled = true;
                    $('#currency_main--help').html('CURRENCY MAIN REQUIRED')
                } else {
                    $('#currency_main--help').html('&nbsp;')
                }
            
                // currency_sub
                if ($('#currency_sub').val().trim() === '') {
                    disabled = true;
                    $('#currency_sub--help').html('CURRENCY SUB REQUIRED')
                } else {
                    $('#currency_sub--help').html('&nbsp;')
                }
            
                // currency_rate
                if (getFloat($('#currency_rate').val() ?? '') <= 0) {
                    disabled = true;
                    $('#currency_rate--help').html('CURRENCY RATE REQUIRED')
                } else {
                    $('#currency_rate--help').html('&nbsp;')
                }
    
                // html_code
                if ($('#html_code').val().trim() === '') {
                    disabled = true;
                    $('#html_code--help').html('CURRENCY SYMBOL REQUIRED')
                } else {
                    $('#html_code--help').html('&nbsp;')
                }
    
                // remarks
                if ($('#remarks').val().trim() === '') {
                    disabled = true;
                    $('#remarks--help').html('REMARKS REQUIRED')
                } else {
                    $('#remarks--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-currency').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



