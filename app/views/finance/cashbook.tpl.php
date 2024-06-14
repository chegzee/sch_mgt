<?php
$data = $data ?? [];
echo $data['menu'];

//
$module_access = $data['user']['access'];
//
$params_ = $data['params'] ?? [];
unset($params_['url']);
unset($params_['list_option']);
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?<?php echo urldecode(http_build_query($params_)); ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Cash Receipts</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card">
        <div class="card-body">

            <div class="d-flex flex-row">
                <div>
                    <button href="javascript:void(0)" onclick="modalCashbook({table: '#table-cashbook', row: ''}); $('#modal-title').html('New Cashbook Receipt')" class="mb-3"><i class="fa fa-plus"></i> Add</button>
                    <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'cashbook_schedule_upload', row: '', modal: '#modal-cashbook_schedule'})"><i class="fa fa-file-import"></i> Excel</button>
                </div>
                <div class="ml-1">
                    <button class="mb-3 dropdown-toggle" type="button" id="dropdownList" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> 
                        <?php $list_option = $data['params']['list_option'] ?? ''; echo $list_option === '' ? 'All' : ($list_option === 'x' ? 'Cancelled' : ($list_option === '0' ? 'Awaiting approval' : 'Approved')) ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownList">
                        <a class="dropdown-item" href="?<?php echo urldecode(http_build_query($params_)); ?>&list_option=">All</a>
                        <a class="dropdown-item" href="?<?php echo urldecode(http_build_query($params_)); ?>&list_option=0">Awaiting approval</a>
                        <a class="dropdown-item" href="?<?php echo urldecode(http_build_query($params_)); ?>&list_option=1">Approved</a>
                    </div>
                </div>
                
                <div class="ml-1">
                    <button class="mb-3 dropdown-toggle" type="button" id="dropdownProcess" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-check-double"></i> Selected</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownProcess">
                        <!-- <a class="dropdown-item" href="javascript:void(0)" onclick="printDocument({class: 'finance', method: 'receipt', export: 'PDF', trans_code: $('input.cashbook-list:checked').map(function(v) { return $(this).val(); }).get() })">Print Receipt</a> -->
                        <div class="dropdown-divider"></div>
                        <?php if($module_access->finance->cashbook_approve === '1') {?><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="processCashbook({func: 'delete', trans_code: [$('#trans_code').val()]})">Reverse<span class="badge badge-info">In progress</span></a> <?php } ?>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                
                <?php if ((int)$list_option === 0) { ?>
                    <?php if($module_access->finance->cashbook_approve === '1') {?><button type="button" class="btn btn-sm btn-success mb-3" style="padding:0px" onclick="if(confirm('Check selected items?')){ processCashbook($('.cashbook-list:checked').map(function() { return $(this).val() }).get()) }"><i class="fa fa-check"></i> Approve</button> <?php } ?>
                <?php } ?>
            </div>
            
            <div style="min-height: 493px; overflow: auto">
                <div class="table-responsive">
                    <div class="dataTables_wrapper">
                        <table id="table-cashbook" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" onclick="$('input.cashbook-list:not(:disabled)').prop({checked: $(this).prop('checked')})"></th>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Client code</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>CUR</th>
                                    <th>Amount</th>
                                    <!-- <th>Bank</th> -->
                                    <th>Mode</th>
                                    <th>Trans type</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- cashbookModal -->
<div id="modal-cashbook" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Premium Deposit</h5>
                <span style="margin-left:8px;"><span id="ref-span" style="font-weight:bold"></span></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Deposit Info</a>
                </nav>
                <div class="tab-content">
                    
                    <div class="tab-pane show active" id="page_1">

                        <div class="d-flex flex-row">
                            <button href="javascript:void(0)" onclick="modalCashbook({trans_code: ''}); $('#modal-title').html('New Cashbook Receipt')" class="mb-3"><i class="fa fa-plus"></i> Reset</button>
                            <div class="ml-1">
                                <button class="mb-3 dropdown-toggle" type="button" id="dropdownProcess2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-check-double"></i> Selected</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownProcess2">
                                    <!-- <a class="dropdown-item" href="javascript:void(0)" onclick="printDocument({class: 'finance', method: 'receipt', export: 'PDF', trans_code: [$('#trans_code').val()]})">Print Receipt</a> -->
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteCashbook({table: 'table-cashbook', trans_code: $('#trans_code').val()})"><i class="fa fa-trash"></i> Remove</a>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="input-group-prepend ml-3" style="width: 7em">
                            <input class="form-control form-control-sm" type="text" id="trans_date" maxlength="10"><br>
                            <code class="small text-danger" id="trans_date--help">&nbsp;</code>
                        </div> -->

                        <div class="row">
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="trans_code" class="col-md-4 col-form-label text-sm-right">Trans. Code | Date <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="trans_code" maxlength="20">
                                            <div class="input-group-prepend ml-3" style="width: 6.5em">
                                                <input class="form-control form-control-sm pr-4" type="text" id="trans_date" maxlength="200">
                                            </div>
                                            <code class="small text-danger" id="trans_code--help">&nbsp;</code>
                                        </div>
                                    </div>
                                    <input type="hidden" id="trans_code_old" readonly disabled>
                                    <input type="hidden" id="trans_type" value="CLT" readonly disabled>
                                </div>
                                <div class="form-group row">
                                    <label for="trans_mode" class="col-md-4 col-form-label text-sm-right">Trans. Mode | Branch <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="width: 7em">
                                                <select class="form-control form-control-sm" id="trans_mode" style="width: 100%"></select>
                                            </div>
                                            <div class="input-group-prepend ml-3" style="width: calc(100% - 8em)">
                                                <select class="form-control form-control-sm" id="branch_code" style="width: 100%">
                                                    <?php
                                                    foreach ($data['branchObj'] ?? [] as $k => $v) {
                                                        echo '<option value="' . $v->branch_code . '">' . $v->branch_name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="trans_mode--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ref_code" class="col-md-4 col-form-label text-sm-right">Trans. Ref. | Desc. <br><span class="small text-info">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="ref_code" maxlength="200">
                                            <div class="input-group-prepend ml-3" style="width: calc(100% - 8em)">
                                                <input class="form-control form-control-sm" type="text" id="trans_detail" maxlength="200">
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="ref_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label for="debit_account" class="col-md-4 col-form-label text-sm-right">Bank:DR | Deposit:CR <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="width: calc(50% - 0.5em)">
                                                <select class="form-control form-control-sm account_code" id="debit_account" style="width: 100%"></select>
                                            </div>
                                            <div class="input-group-prepend  ml-3" style="width: calc(50% - 0.5em)">
                                                <select class="form-control form-control-sm account_code" id="credit_account" style="width: 100%"></select>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="debit_account--help">&nbsp;</code>
                                    </div>
                                </div> -->
                            </div>
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="payer" class="col-md-4 col-form-label text-sm-right">Payer  <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" type="text" id="payer" style="width: 100%">
                                            <option value="" disabled selected>Select an option</option>
                                            <!-- <option>Parents</option>
                                            <option>Students</option>
                                            <option>Others</option> -->
                                        </select>
                                        <code class="small text-danger" id="payer--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="client_code" class="col-md-4 col-form-label text-sm-right">Client  <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" type="text" id="client_code" style="width: 100%"></select>
                                        <code class="small text-danger" id="client_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="client_name" >
                                </div>
                                <div class="form-group row">
                                    <label for="currency_code" class="col-md-4 col-form-label text-sm-right">Currency | Amount<br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="width: 4em">
                                                <select id="currency_code" class="form-control form-control-sm" style="width: 100%" onchange="$('#currency_rate').val($(this).find(':selected').data('currency_rate'))">
                                                    <?php
                                                    foreach ($data['currencies'] as $k => $v) {
                                                        echo '<option value="' . $v->currency_code . '" data-currency_rate="' . $v->currency_rate . '">' . $v->currency_code . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <input class="form-control form-control-sm text-right decimal" type="text" id="currency_rate" maxlength="20">
                                            <div class="input-group-append ml-3" style="width: calc(100% - 8em)">
                                                <input class="form-control form-control-sm text-right money" type="text" id="amount" maxlength="20">
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="currency_code--help">&nbsp;</code>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
    
                    <div class="form-group mb-2 d-flex">
                        <button id="save-cashbook" type="button" style="margin-left: auto" onclick="saveCashbook({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- cashbook schedule modal -->
<div id="modal-cashbook_schedule" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Cashbook schedule New/Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <button class="btn btn-small btn-outline-primary mb-3 import_btn" onclick="$('#cashbook_schedule_file').click()"><i class="fa fa-file-import"></i>Import</button>
                <button class="btn btn-small btn-outline-primary mb-3" onclick="scheduleExportV2({theadRows: '#table-cashbook_schedule_upload thead tr', tbodyRows: '#table-cashbook_schedule_upload tbody', filename: 'cashbook_schedule'})" ><i class="fa fa-file-import"></i>Export</button>
                <button class="btn btn-small btn-outline-primary mb-3" id="save-import_cashbook_schedule" onclick="saveStdScheduleUpload({table: '#table-cashbook_schedule_upload'})"><i class="fa fa-save"></i>Save</button>
                <!-- <input type="hidden" id="doc_path" maxlength="250" readonly> -->
                <input type="file" id="cashbook_schedule_file" accept="application/vmd.openxmlformats.officedocumnet.spreadsheet.sheet, application/vmd.ms.excel" onchange="scheduleImport({excelfile: '#cashbook_schedule_file', table: '#table-cashbook_schedule_upload', action: 'cashbookSchedule'}, event)" style="display:none">
                <div class="table-responsive">
                    
                    <div id="" class="dataTables_wrapper">
                        <!-- <div class="dataTables_wrapper dt-bootstrap4 no-footer"> -->
                            <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" role="grid" id="table-cashbook_schedule_upload">
                                <thead>
                                    <tr>
                                        <th><i class="material-icons">build</i></th>
                                        <!-- <th>Client_code</th> -->
                                        <th>Client name</th>
                                        <th>Trans code</th>
                                        <th>Trans type</th>
                                        <th>Trans date</th>
                                        <th>Trans mode</th>
                                        <th>Amount</th>
                                        <th>Currency code</th>
                                        <th>Currency rate</th>
                                        <th>Branch</th>
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
<!-- std schedule -->

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    const TRANS_MODE = <?php echo json_encode(TRANS_MODE) ?>;
    const TRANS_TYPE = <?php echo json_encode(TRANS_TYPE) ?>;
    const TRANS_TYPE_REC = <?php echo json_encode(TRANS_TYPE_REC) ?>;
    const accountObj = <?php echo json_encode($data['accountObj']) ?>;
    const branchObj = <?php echo json_encode($data['branchObj']) ?>;
    let parentObj = <?php echo json_encode($data['parentObj']) ?>;
    let branches = <?php echo json_encode($data['branches']) ?>;
    let currenciesObj = <?php echo json_encode($data['currenciesObj']) ?>;
    let upload_fields = [];
    // console.log(parentObj)
    
    const module_access = <?php echo json_encode($module_access); ?>;

   // currencies
    const currencies = <?php echo json_encode($data['currencyObj']) ?>;
    // accounts
    const accounts = <?php echo json_encode($data['accountObj']) ?>;

    let list_option = <?php echo json_encode($data['params']['list_option'] ?? ''); ?>;
    var cashbookTable = null;
    cashbookTable = $('#table-cashbook').DataTable();

    // ////////////////////////////////////////////////////////////////////////////////////////////
    let modalCashbook = (json) => {
        cashbookTable = $(json.table).DataTable();
        // let data = json.row === '' ? {} : cashbookTable.row(json.row).data();
        let data = json.row === '' ? {} : json.row;
        // console.log(json.row);return;
        //
        $('#modalNav').find('a.non-active').addClass('d-none');

        if (!data) data = [];
        let lock = (data['trans_code'] ?? '') !== '';
        let lock_status = (data['status'] >= '1');
        // let lock_trans = (((data['receipts'] ?? [])[0] ?? [])['receipt_code'] ?? '') !== '';
        $('#trans_code_old').val(data['trans_code'] ?? '');
        $('#trans_code').val(data['trans_code'] ?? 'AUTO').prop({disabled: lock});
        //
        $('#trans_date').val((data['trans_date'] ?? moment().format('YYYY-MM-DD')).slice(0, 10)).prop({disabled: lock_status});
        $('#client_code').append(new Option(data['client_name'] ?? '', data['client_code'] ?? '', true, true)).trigger('change').prop({disabled: lock_status});
        $('#client_name').val(data['client_name'] ?? '');
        $('#trans_type').val(data['trans_type'] ?? 'CLT').trigger('change').prop({disabled: lock_status});
        //
        $('#trans_mode').append(new Option(data['trans_mode'] ?? 'Transfer', data['trans_mode'] ?? 'Transfer')).trigger('change').prop({disabled: lock_status});
        $('#branch_code').val(data['branch_code'] ?? '<?php echo DEFAULT_BRANCH ?>').trigger('change').prop({disabled: lock_status});
        //
        $('#ref_code').val(data['ref_code'] ?? '').prop({disabled: lock_status});
        // $("#ref-span").html(data["ref_code"] ?? '');
        $('#trans_detail').val(data['trans_detail'] ?? '').prop({disabled: lock_status});
        //
        $('#currency_code').val(data['currency_code'] ?? '<?php echo BASE_CURRENCY ?>').trigger('change').prop({disabled: lock_status});
        $('#currency_rate').val(data['currency_rate'] ?? '<?php echo BASE_RATE ?>').prop({disabled: lock_status});
        $('#amount').val(number_format(data['amount'], 2)).prop({disabled: lock_status});
        //
        // $('#debit_account').append(new Option(data['debit_account'] ?? '' , data['debit_account'] ?? '', true, true)).trigger('change').prop({disabled: lock_status});
        // $('#credit_account').append(new Option(data['credit_account'] ?? '' , data['credit_account'] ?? '', true, true)).trigger('change').prop({disabled: lock_status});
        //
        $('#modal-cashbook').modal('show');
        // console.log(json);return;
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        // console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let client_code = '<?php echo $data['params']['client_code'] ?>';
        // console.log(client_code)
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
            if (client_code !== '') {
                let cashbookTable = $('#table-cashbook').DataTable();
                cashbookTable.columns(3).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === client_code) {
                            modalCashbook({table: '#table-cashbook', row: i});
                            return false;
                        }
                    });
                });
            }
            else modalReceipt({table: '#table-cashbook', row: ''});
        }
    }
    
    let createstdscheduleRow = (json) => {
        // console.log(json.data);return
        let tbody = $(json.table).find('tbody');
        let keys = Object.keys(json.data);
        // console.log(keys)
        let trans_code, client_name, amount, trans_type, trans_mode, currency_code, currency_rate, trans_date, branch_name;
        trans_code = (json.data['Trans code'] ?? '') !== '' ? json.data['Trans_code'].trim().toUpperCase() : '';
        client_name = (json.data['Client name'] ?? '') !== '' ? json.data['Client name'].toString().trim().toUpperCase() : '';
        // client_name = (json.data['Client name'] ?? '') !== '' ? json.data['Client name'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        trans_type = (json.data['Trans type'] ?? '') !== '' ? json.data['Trans type'].trim().toUpperCase() : '';
        trans_mode = (json.data['Trans mode'] ?? '') !== '' ? json.data['Trans mode'].trim().toUpperCase() : '';
        currency_code = (json.data['Currency code'] ?? '') !== '' ? json.data['Currency code'].toString().trim().toUpperCase() : '';
        currency_rate = (json.data['Currency rate'] ?? '') !== '' ? json.data['Currency rate'].toString().trim() : '';
        trans_date = (json.data['Trans date'] ?? '') !== '' ? json.data['Trans date'].toString().trim().toUpperCase() : '';
        amount = (json.data['Amount'] ?? '') !== '' ? json.data['Amount'].toString().trim().replace(/[^0-9.]/g, '') : '';
        branch_name = (json.data['Branch'] ?? '') !== '' ? json.data['Branch'].toString().trim().toUpperCase() : '';
        
        let parent = parentObj[client_name] ?? {};
        let branch = branches[branch_name] ?? {};
        // birthday = moment(birthday, 'DD/MM/YYYY').format('YYYY-MM-DD');
        //
        let html = '<tr>\
        <td>\
            <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">\
                <i class="fa fa-cog"></i>\
            </a>\
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">\
                <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                <a class="dropdown-item" href="#"  onclick="removeRow({table: \''+json.table+'\', elem: event})">\
                    <i class="fas fa-trash text-orange-peel"></i>Remove\
                </a>\
            </div>\
        </td>';
        $.each(upload_fields, function (k, v) {
            if(v === 'Trans code'){
                 html += '<td><input id="trans_code" class="" value="'+ trans_code +'" /></td>';

            }if(v === 'Trans date'){
                 html += '<td><input id="trans_date-'+json.row+''+k+'" value="'+ trans_date +'" /></td>';

            }
            else if(v === 'Client name'){
                 html += '<td><select style="width:200px" id="client_code-'+json.row+''+k+'" readonly><option value="'+parent.parent_code+'" selected >'+client_name+'</option></select></td>';

            } else if(v === 'Trans type'){
                 html += '<td><input style="width:200px" id="trans_type-'+json.row+''+k+'" value="'+ trans_type +'" readonly></td>';

            } else if(v === 'Trans mode'){
                 html += '<td><select style="width:200px" id="trans_mode-'+json.row+''+k+'"><option  value="'+trans_mode+'" selected>'+trans_mode+'</option></select></td>';

            }
            else if(v === 'Currency code'){
                 html += '<td><select style="width:100%" id="currency_code-'+json.row+''+k+'"><option value="'+currency_code+'" selected >'+currency_code+'</option></select></td>';

            }
            else if(v === 'Currency rate'){
                 html += '<td><input id="currency_rate-'+json.row+''+k+'" value="'+ currency_rate +'" /></td>';

            }else if(v === 'Amount'){
                 html += '<td><input id="amount" value="'+ amount +'" /></td>';

            }
            else if(v === 'Branch'){
                 html += '<td><select id="branch_code-'+json.row+''+k+'" style="width:100%" ><option value="'+(branch.branch_code ?? '')+'" selected >'+branch_name+'</option></select></td>';

            }
        })
        html += '</tr>';
        tbody.append(html);
        
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(9) > select#branch_code-'+json.row+'8').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getBranches",
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
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        
        ////////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(1) > select#client_code-'+json.row+'0').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getParents",
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
        ////////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(7) > select#currency_code-'+json.row+'6').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getCurrencies",
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
        }).on("select2:select", (e)=>{
            let currency_code = $(e.target).find(':selected').val();
            let currency_rate = currenciesObj[currency_code].currency_rate;
            console.log(currency_rate);
            $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(8) > input#currency_rate-'+json.row+'7').val(currency_rate);
        });
        ////////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(5) > select#trans_mode-'+json.row+'4').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getTransModes",
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
        // ////
        flatpickr($(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(4) > input#trans_date-'+json.row+'3'), {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            // maxDate: new Date().fp_incr(0), // -92
        });

    }

    let saveStdScheduleUpload = (json) =>{
        $('#save-import_cashbook_schedule').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');

        if(!confirm("Do you want to perform this operation")) return;
        // console.log("scared");return;
        let tr = $(json.table + ' tbody').find('tr');
        tr.each((k, v)=>{
            $(v).find('td:eq(0)').find('a').css('color', '#167bea');
        })
        let rows = [];
        $(json.table + ' tbody > tr').each((k, tr)=>{
            //
            // console.log();
            let row = {};
            let td = [...tr.children];
            td.forEach((v, k)=>{
                
                let tag_name = v.children[0].tagName;
                let id = String(v.children[0].id);
                let key = id.substring(0, (id.indexOf('-') === -1 ) ? id.length :  id.indexOf('-') );
                if(tag_name === 'A'){

                }else if(tag_name === 'INPUT'){
                    row[key] = String(v.children[0].value).trim();
                }else if(tag_name === 'SELECT'){
                    let select_ = v.children[0] ?? '<select></select>';
                    let option_ = $(select_)["0"].selectedOptions[0] ?? '<option></option>';
                    let vvv = $(option_)["0"].value ?? '';
                    
                    row[key] = String(vvv).trim();

                    // console.log(vvv)
                    // row[key] = String(v.children[0].selectedOptions[0].value).trim();

                }
            })
            rows.push(row);
            
        });
        // console.log(rows);
        let data = {data: JSON.stringify(rows)};
        $.post('<?php echo URL_ROOT ?>/finance/cashbook/__save/?user_log=<?php echo $data['params']['user_log'] ?>', data, (data)=>{
                if(!data.status){
                    $(json.table + ' tbody > tr:eq('+data.key+') td:eq(0) a').css("color", "red");
                    $('#save-import_cashbook_schedule').html('<i class="fa fa-save"></i>Save');
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    $($(json.table + ' tbody').find('tr:eq('+data.rowNo+') td:eq(0)')).css("background-color", "red");
                    return false;
                }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            $('#save-import_cashbook_schedule').html('<i class="fa fa-save"></i>Save');
                // console.log(data.key);
        }, 'json')
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    // //
    let saveCashbook = (json) => {
        // console.log(json);
         $(json.table).DataTable();
        
        if ($('#save-cashbook').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-cashbook').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            // console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('receivable', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
            }
            //
            else if ($('#' + obj['id']).is('select') && $('#' + obj['id']).prop('multiple')) {
                form_data.append(obj['id'], $('#' + obj['id']).val());
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
            url: '<?php echo URL_ROOT ?>/finance/cashbook/_save/?<?php echo urldecode(http_build_query($params_)); ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-cashbook').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-cashbook').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                saving = false;
                // console.log(data.data.client_code)
                //
                $('#save-cashbook').html('Save Changes');
                $('#save-cashbook').prop({disabled: false});
                
                if (data.status === true) {
                    //
                    new Noty({type: 'success', text: '<h5>Saved successfully!</h5>', timeout: 10000}).show();
                    
                    setTimeout(() => {
                        //localStorage.setItem('modalOpen', '1');
                        $("#modal-cashbook").modal('hide');
                       // parent.location.assign('<?php echo URL_ROOT ?>/finance/cashbook/?user_log=<?php echo $data['params']['user_log'] ?>&client_code=' + data.data.client_code + '#' + Math.random());
                    }, 1000);
                    return false;
                    //
                }
                //
                new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
                ////
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                $('#save-cashbook').html('Save Changes');
                $('#save-cashbook').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    // //
    let deleteCashbook = (json) => {
        // cashbookTable = $("table-cashbook").DataTable();
        // let data_ = cashbookTable.row(row_id).data(); // data["colName"]
        let data = {trans_code: json.trans_code};
        // console.log(data);return;
        
        if (!confirm('Delete Cashbook: ' + json['trans_code'] ?? '' + ' : ' + json['client_name'] ?? '')) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/cashbook/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            // console.log(data.status);
            if (data.status === true) {
                new Noty({type: 'success', text: '<h5>successfully deleted!</h5>', timeout: 10000}).show();
                
                setTimeout(() => {
                    $("#modal-cashbook").modal('hide');
                    // cashbookTable.ajax.reload(null, false);
                }, 1000);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.status, timeout: 10000}).show();
            //
            // cashbookTable.ajax.reload(null, false);
            // $("#modal-cashbook").modal('hide');
            
        }, 'JSON');
    }

    
    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");

        $("#"+table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }
    
    //
    let processCashbook = (json) => {
        // console.log(json); return;
        let tableRequisition = $('#table-requisition').DataTable();
        
        if (json.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/cashbook/_process/?<?php echo urldecode(http_build_query($params_)); ?>', {trans_code: json, list_option: getInt(list_option ?? ''), username: '<?php echo $data['user']['username'] ?>', user_group: '<?php echo $data['user']['group_code'] ?>'}, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>' + data.data.message, timeout: 10000}).show();
            //
            // if (data.error.status) {
            //     new Noty({type: 'warning', text: '<h5>Warning</h5>' + data.error.message, timeout: 10000}).show();
            // }
            //
            if ($('#modal-requisition').hasClass('show')) $('#modal-requisition').modal('hide');
            else cashbookTable.ajax.reload(null, false);
    
        }, 'JSON');
    }

    // //
    let loadCashbook = (json) => {
        // console.log(json);return
        // dataTables
        let url = "<?php echo URL_ROOT ?>/finance/cashbook/_list/?<?php echo urldecode(http_build_query($params_)); ?>";
        cashbookTable.destroy();

        cashbookTable = $('#table-cashbook').DataTable({
            "processing": true,
            // "serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {
                    list_option: list_option
                },
            },
            "columns": [
                {
                    "data": "trans_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<input type="checkbox" class="cashbook-list" value="' + row['trans_code'] + '" ' + (getInt(row['status'] ?? '') > 0 && getInt(list_option ?? '') === 0 ? 'disabled' : '') + '>';
                    }
                },
                {"data": "trans_date", "render": function (data, type, row, meta) {
                        return moment(data).format('YYYY-MM-DD');
                    }},
                {"data": "client_name"},
                {"data": "client_code"},
                {"data": "trans_code"},
                {"data": "status", "render": function (data, type, row, meta) {
                        return  '<div class="d-inline rounded-sm py-1 px-2 text-white bg-' + ((row['status'] ?? '') === '1' ? 'success' : 'danger') + '">' + ((row['status'] ?? '') === '0' ? 'Pending' : 'Approved') + '</div>';
                    }},
                {"data": "currency_code"},
                {"data": "amount", "className": "text-right", "render": function (data, type, row, meta) {
                        return number_format(data, 2);
                    }},
                // {"data": "debit_account", "render": function (data, type, row, meta) {
                //         return accountObj[data]['account_name'];
                //     }},
                {"data": "trans_mode"},
                {"data": "trans_type", 'render': function(data, type, row, meta){
                    return TRANS_TYPE[data]
                }},
                {"data": "trans_detail", "render": function (data, type, row, meta) {
                        return strEllipsis(data, 30);
                    }},
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "desc"]],
            "initComplete": function (settings, json) {
                $('.dataTables_length .select').removeClass('custom-select')
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                // console.log(json);
                modalAuto();
            }
        });
    }
    //
    //
    let cashbook_schedule_upload_fields = ()=>{
        let td_collection = [...$("#table-cashbook_schedule_upload thead").find('tr')["0"].children];
        td_collection.shift();
        $.each(td_collection, (k, v)=>{
            let v_ = $(v).html();
            upload_fields.push(v_)

        })
    }
    cashbookTable.search('', false, true);
    //
    cashbookTable.row(this).remove().draw(false);

    // /////////////////////////////////////////////////////////////////////////////////////////
    
    $(function () {
        //
        // console.log("module_access")
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-cashbook').on('hidden.bs.modal', function () {
            cashbookTable.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
        // console.log(TRANS_MODE);

        //
        // $('#trans_mode').select2({
        //     placeholder: "Select an option",
        //     allowClear: true,
        //     data: Object.keys(TRANS_MODE).map(function(v) { return {id: v, text: TRANS_MODE[v]} }),
        // });
        
        cashbook_schedule_upload_fields();

        //
        $('#currency_code').select2({
            placeholder: "Select an option",
            allowClear: true,
        });

        ///////for payer in cashbook//////////////////
        $('#payer').select2({
            placeholder: "Select an option",
            allowClear: true,
            data: [
                {id:"PARENT", text: "PARENTS"},
                {id:"STUDENT", text: "STUDENTS"}
            ]
        }).on("select2:select", (e)=>{
            let option = $("#payer").val();
            if(option === "STUDENT"){
                // $("#client_code").html('');
                $('#client_code').select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getClients",
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
                            // console.log(response);
                            return {results: response};
                        },
                        cache: true
                    }
                }).on("select2:select", ()=>{
                    $("#client_name").val($("#client_code").find(":selected").html())
                })

            }
            else if(option === 'PARENT'){
                $('#client_code').select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getParents",
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
                            // console.log(response);
                            return {results: response};
                        },
                        cache: true
                    }
                }).on("select2:select", ()=>{
                    $("#client_name").val($("#client_code").find(":selected").html())
                })
            }
        });

        //
        $('#branch_code').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        // console.log($('#client_code').val())
        //
        $('#trans_mode').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getTransModes",
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
                    // console.log(response);
                    return {results: response};
                },
                cache: true
            }
        })

        // //
        $('.account_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/accountSetting/getLedgerAccounts/?<?php echo urldecode(http_build_query($params_)) ?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        _option: 'select-group',
                    };
                },
                processResults: function (response) {
                    // console.log(response);
                    return {results: response};
                },
                cache: true
            }
        });

        // //
        flatpickr('#trans_date', {
            dateFormat: 'Y-m-d',
            // allowInput: true,
            minDate: '1800-01-01',
            maxDate: moment().format('YYYY-MM-DD'),
        });
        
        //
        $('#table-cashbook tbody').on('click', 'td', function () {
            //
            let data = cashbookTable.row($(this)).data();
            
            let rowId = $(this).parent('tr').index();

            if (!data) return;
            //
            // console.log(this.cellIndex);
            if (this.cellIndex !== 0) {
                //
                modalCashbook({table: '#table-cashbook', row: data});
                
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
        // /////////////////////////////////////////////////////////////////////////////////////////

        //
        cashbookTable.search('', false, true);
        
        cashbookTable.row(this).remove().draw(false);


        loadCashbook({});
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // receivable
            if ($('#modal-cashbook').hasClass('show')) {

                // access
                // if (module_access['finance']['cashbook']['receivable'] < 2) disabled = true;
                
                //
                let modalNav = {
                    '#page_1': false,
                };

                // trans_code
                if ($('#trans_code').val().trim() === '' && $('#trans_code_old').val().trim() !== '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#trans_code--help').html('TRANSACTION CODE REQUIRED')
                } else {
                    $('#trans_code--help').html('&nbsp;')
                }
                
                // trans_date
                if ($('#trans_date').val() === '' || !moment($('#trans_date').val()).isValid()) {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#trans_date--help').html('TRANSACTION DATE INVALID')
                } else {
                    $('#trans_date--help').html('&nbsp;')
                }

                // if ($('#payer').val() === '' || !moment($('#payer').val()).isValid()) {
                //     disabled = true;
                //     if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                //     $('#payer--help').html('TRANSACTION DATE INVALID')
                // } else {
                //     $('#payer--help').html('&nbsp;')
                // }

                // client_code
                if (($('#client_code').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#client_code--help').html('CLIENT NAME REQUIRED')
                } else {
                    $('#client_code--help').html('&nbsp;')
                }
                
                // debit_account
                // if (($('#debit_account').val() ?? '') === '') {
                //     disabled = true;
                //     if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                //     $('#debit_account--help').html('BANK REQUIRED')
                // } else if (($('#credit_account').val() ?? '') === '') {
                //     disabled = true;
                //     if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                //     $('#debit_account--help').html('INCOME REQUIRED')
                // } else {
                //     $('#debit_account--help').html('&nbsp;')
                // }
                
                // currency_code
                if (($('#currency_code').val() ?? '') === '') { 
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#currency_code--help').html('CURRENCY REQUIRED')
                } else if (getFloat($('#currency_rate').val() ?? '') <= 0) {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#currency_code--help').html('CURRENCY RATE REQUIRED')
                } else if (getFloat($('#amount').val() ?? '') <= 0) {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#currency_code--help').html('RECEIPT AMOUNT REQUIRED')
                } else {
                    $('#currency_code--help').html('&nbsp;')
                }
    
                
                //
                if (saving) disabled = true;
                $('#save-cashbook').prop({disabled: disabled});
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



