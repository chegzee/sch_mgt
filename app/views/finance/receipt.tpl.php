<?php
$data = $data ?? [];
echo $data['menu'];
// var_dump($data['term']);exit;
?>

<!-- Main body -->
<div class="main-body">
    
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a>
            </li>
            <li class="breadcrumb-item active"><a href="#">Receipt</a></li>
        </ol>
    </nav>
    
    <div class="card card-style-1">
        <div class="card-body">
            <div style="">
                <button href="javascript:void(0)" onclick="modalReceipt({table: '#table-receipt', row: ''}); $('#modal-title').html('New Receipt')" class=""><i class="fa fa-plus"></i> New</button>
                
                <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-cog"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">
                
                    <a class="dropdown-item" href="#"  onclick="deleteReceipt({receipt_code: $('input.receipt-list:checked').map(function(v){return $(this).val();}).get() })">
                        <i class="fas fa-trash text-orange-peel"></i> Delete
                    </a>
                </div>
                <div class="ml-1" style="display:inline;z-index:99;">
                    <button class="mb-3 dropdown-toggle" type="button" id="dropdownAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i> Selected</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#"  onclick="loadReceipt('')">
                            <i class="text-orange-peel"></i> All
                        </a>
                        <?php 
                            foreach($data['terms'] as $k => $v){
                                echo "
                                    <a class='dropdown-item' href='#' onclick='loadReceipt(\"".$v->code."\")'>
                                        <i class='text-orange-peel'></i> ".$v->term." ".$v->year."
                                    </a>
                                ";
                            }
                        ?>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table id="table-receipt" class="table table-striped table-bordered table-sm nowrap w-100"
                       style="cursor: pointer">
                    <thead>
                    <tr>
                        <th style="width:fit-content"><input type="checkbox" onclick="$('input.receipt-list:not(:disabled)').prop({checked: $(this).prop('checked')})">
                        <th>Date</th>
                        <th>Receipt Code</th>
                        <th>Invoice Code</th>
                        <th>Amount Paid</th>
                        <th>Invoice Amount</th>
                        <th>Students</th>
                        <th>Parents</th>
                        <th>Class</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /Main body -->

<!-- invoice Modal -->
<div id="modal-invoices" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Invoice List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="ml-1" style="display:inline;z-index:99;">
                    <button class="mb-3 dropdown-toggle" type="button" id="dropdownAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i> Selected</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#"  onclick="loadInvoice({term_code: ''})">
                            <i class="text-orange-peel"></i> All
                        </a>
                        <?php 
                            foreach($data['terms'] as $k => $v){
                                echo "
                                    <a class='dropdown-item' href='#' onclick='loadInvoice({term_code: \"".$v->code."\"})'>
                                        <i class='text-orange-peel'></i> ".$v->term." ".$v->year."
                                    </a>
                                ";
                            }
                        ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-invoices" class="table table-striped table-bordered table-sm nowrap w-100"
                           style="cursor: pointer">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <!-- <th>Invoice code</th> -->
                            <th>Client name</th>
                            <th>Parent name</th>
                            <!-- <th>term</th> -->
                            <th>Amount</th>
                            <!-- <th>Level Fees</th>
                            <th>Other Fees</th> -->
                            <th>Amount Paid</th>
                            <th>Balance due</th>
                            <th>Branch</th>
                            <th>Currency Code</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<dialog id="receipt_dialog" style="position:absolute;top:0;left:0;height:100%;width:100%;margin-left:auto;background-color:rgba(75, 65, 34, 0.3);text-align:center;z-index:9999;">  
            
    <div class="row" style="display:flex;justify-content:center;">
        <div class="col-lg-8 col-xl-8 col-12" style="background-color:white;">
            <div class="modal-header">
                <div class="mt-0">Receipt New/Edit 
                    <span id="credit_balance_view" class="px-2" style="float: right; margin-left: 2em; font-size: 14pt; font-weight: normal; border: 1px solid #777777; border-radius: 5px">
                        Credit Balance: 
                        <span id="credit_balance-span" style="float: inherit; margin-left: 0.5em">0.00</span>
                    </span>
                </div>
                
                <!-- <h4 id="creditor_label" class="modal-title mt-0" style="padding:4px;color:#042954"></h4> -->
                <button type="button" class="close" onclick="document.getElementById('receipt_dialog').close();">
                    <span>×</span>
                </button>
            </div>
            <div class="row" id="receipt-dialog-body">

                <div class="col-lg-6 px-3" style="margin-top:12px">

                    <div class="form-group row">
                        <label for="receipt_code" class="col-md-4 col-form-label text-sm-right">Receipt Code | Date <br><span class="small text-warning">Required</span></label>
                        <div class="col-md-8 pr-3">
                            <div class="input-group">
                                <div style="width:50%;">
                                    <input class="form-control form-control-sm" type="text" id="receipt_code" maxlength="250">
                                </div>
                                <div style="width:50%;">
                                    <input class="form-control form-control-sm" type="text" id="trans_date" maxlength="10">
                                </div>
                            </div>
                            <code class="small text-danger" id="receipt_code--help">&nbsp;</code>
                            <code class="small text-danger" id="trans_date--help">&nbsp;</code>
                        </div>
                        <input type="hidden" id="receipt_code_old" readonly>
                        <input type="hidden" id="ref_code" readonly>
                        <input type="hidden" id="term_code" readonly>
                        <input type="hidden" id="client_fullname" readonly>
                        <input type="hidden" id="lock" readonly>
                    </div>

                    <div class="form-group row">
                        <label for="invoice_code" class="col-md-4 col-form-label text-sm-right">Invoice <br><span class="small text-warning">Required</span></label>
                        <div class="col-md-8 pr-3">
                            <input class="form-control form-control-sm" type="text" id="invoice_code" style="background-color: #ffffff; cursor: pointer" placeholder="Select Invoice here" maxlength="100" readonly>
                            <code class="small text-danger" id="invoice_code--help">&nbsp;</code>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="std_code" class="col-md-4 col-form-label text-sm-right">Student <br><span class="small text-warning">Required</span></label>
                        <div class="col-md-8 pr-3">
                            <select class="form-control form-control-sm" id="std_code" disabled maxlength="100" >
                            </select>
                            <!-- <input class="form-control form-control-sm" type="hidden" id="client_name" maxlength="100" readonly> -->
                            <code class="small text-danger" id="std_code--help">&nbsp;</code>
                        </div>
                    </div>

                    <div class="row">
                        <label for="client_code" class="col-md-4 col-form-label text-sm-right">Depositors <br><span class="small text-warning">Required</span></label>
                        <div class="col-md-8 pr-3">
                            <div class="input-group"  style="position:relative;" id="client-code-box">
                                <select class="form-control form-control-sm" id="client_code" style="width: 100%"></select>
                            </div>
                            <code class="small text-danger" id="client_code--help">&nbsp;</code>
                        </div>

                    </div>

                </div>

                <div class="col-lg-6 px-3" style="margin-top:12px">

                    <div class="form-group row">
                        <label for="invoice_amount" class="col-md-4 col-form-label text-sm-right">Inv. Amount <br><span class="small text-warning">Required (YYYY-MM-DD)</span></label>
                        <div class="col-md-8 pr-3">
                            <input class="form-control form-control-sm" type="text" id="invoice_amount" maxlength="10">
                            <code class="small text-danger" id="invoice_amount--help">&nbsp;</code>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="balance_due" class="col-md-4 col-form-label text-sm-right">Balance due <br><span class="small text-warning">Required</span></label>
                        <div class="col-md-8 pr-3">
                            <div class="input-group">
                                <div style="width: calc(100% - 12em)">
                                    <input class="form-control form-control-sm money" type="text" id="balance_due" maxlength="20">
                                </div>
                            </div>
                            <code class="small text-danger" id="balance_due--help">&nbsp;</code>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-md-4 col-form-label text-sm-right">Amount <br><span class="small text-warning">Required</span></label>
                        <div class="col-md-8 pr-3">
                            <div class="input-group">
                                <div style="width: calc(100% - 12em)">
                                    <input class="form-control form-control-sm money" type="text" id="amount" maxlength="20">
                                </div>
                            </div>
                            <code class="small text-danger" id="amount--help">&nbsp;</code>
                        </div>
                    </div>
                    
                </div>

                <input type="hidden" id="branch_code" readonly>

            </div>
            
            <div class="form-group mb-2">
                <button id="save-receipt" type="button" style="float:left;" onclick="saveReceipt({})"><i class="fa fa-save"></i> Save</button>
                <button class="receipt_btn" type="button" style="float:left;" onclick="printReceipt({receipt_code: [$('#receipt_code_old').val()]})"><i class="fa fa-print"></i> Print</button>
                <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button"  style="float:left;" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-cog"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99;float:left;">
                
                    <a class="dropdown-item" href="#"  onclick="deleteReceiptPayment({ref_code: $('input.payment-list:checked').map(function(v){return $(this).val();}).get() })">
                        <i class="fas fa-trash text-orange-peel"></i> Remove
                    </a>
                    <!-- <a class="dropdown-item" href="#" onclick="printReceipt({receipt_code: $('input.receipt-list:checked').map(function(v){return $(this).val();}).get() })">
                        <i class="fas fa-print text-dark-pastel-green"></i> Receipt
                    </a> -->
                </div>
                <div style="float: right; border: 1px solid; padding: 6px; border-radius: 5px; font-weight: bold;">Total paid: <span id="total_amount_paid"></span></div>
            </div>

            <div class="table-responsive" style="height:300px">
                <div class="dataTables_wrapper">
                    
                    <table id="table-ref" class="table table-striped table-bordered table-sm nowrap w-100" style="cursor: pointer;">
                        <thead style="">
                        <tr>
                            <th style="width:fit-content"><input type="checkbox" onclick="$('input.payment-list:not(:disabled)').prop({checked: $(this).prop('checked')})">
                            </th>
                            <th>Date</th>
                            <th>Payer</th>
                            <th>Invoice No.</th>
                            <th>Receipt No.</th>
                            <th>Amount Paid</th>
                            <th>Ref</th>
                        </tr>
                        </thead>
                    </table>
                
                </div>
            </div>

        </div>
        
    </div>
    
</dialog>  

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    // console.log(userAccess);
    
    let lock;
    
    // currencies
    let currencies = <?php echo json_encode($data['currencyObj']) ?>;
    ////////////
    let term = <?php echo json_encode($data['term']) ?>;
    // console.log(term);

    // trans_modes
    const transModes_ = <?php echo json_encode($data['transModeObj']) ?>;
    let transModes = [];
    $.each(transModes_, function(i, v) { transModes.push({id: v.trans_mode, text: v.trans_mode}) });

    // bus_type
    const busTypes = <?php echo json_encode($data['busTypeObj']) ?>;
    var tableReceipt = $("#table-receipt").DataTable();


    let deleteReceiptPayment = (json) => {
        let receipt_code = $('input.payment-list:checked')[0].name;
        // console.log(receipt_code);return
        // return;
        let tableRef = $("#table-ref").DataTable();
        // let data = tableStudents.row(json.row).data(); // data["colName"]
        // console.log(json);return;
        
        if (!confirm('You are about to delete ' + json.ref_code.length + ' receipt(s)') || json.ref_code.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/receipt/__delete/?user_log=<?php echo $data['params']['user_log'] ?>', json, function (data) {
            //console.log(data);
            if (data.status) {
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                tableReceipt.ajax.reload(null, false);
                setTimeout(() => {
                    localStorage.setItem("receipt_code", receipt_code);
                    localStorage.setItem('modalOpen', "1");
                    modalAuto();
                    
                }, 1000);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
            //
            
        }, 'JSON');
    }

    //
    let deleteReceipt = (json) => {
        // console.log(json);
        // return;
        let tableReceipt = $("#table-receipt").DataTable();
        // let data = tableStudents.row(json.row).data(); // data["colName"]
        // console.log(json);return;
        
        if (!confirm('You are about to delete ' + json.receipt_code.length + ' receipt(s)') || json.receipt_code.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/receipt/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', json, function (data) {
            //console.log(data);
            if (data.status) {
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                tableReceipt.ajax.reload(null, false);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
            //
            
        }, 'JSON');
    }
    
    //
    let modalReceipt = (json) => {
           
       let tableReceipt = $(json.table).DataTable();
       let data = json.row === '' ? {} : json.row;
       let receipt = JSON.parse(data.receipt_obj ?? '[]');
       let last_paid = receipt[receipt.length - 1] ?? {}
        // console.log(json.row)
       let lock = false;
        
       $('#receipt_code_old').val(data['receipt_code'] ?? '');
       $('#receipt_code').val(data['receipt_code'] ?? 'AUTO').prop({readonly: data['receipt_code'] !== undefined});
        //
        // $("#amount").val()
       $('#invoice_code').val(data['invoice_code'] ?? '');
       $('#amount').val(number_format(data['amount'] ?? '', 2));
       $('#total_amount_paid').html(number_format(data.total_receipt_amount, 2));
        //    $('#trans_date').val(last_paid.trans_date ?? moment().format('YYYY-MM-DD'));
       $('#branch_code').val(data['branch_code'] ?? '');
       $('#std_code').append(new Option((data['student_first_name'] ?? '') + ' ' + (data['student_last_name'] ?? ''), data['std_code'] ?? '', true, true)).trigger('change');
       $('#client_code').append(new Option((last_paid.client_fullname), last_paid.client_code, true, true)).trigger('change');
       $("#client_fullname").val(last_paid.client_fullname);
        //
       let balance = data['balance_due'] ?? '';
        //    if( balance === ''){
        //     balance =  data['invoice_amount'] - data['amount_paid'];
        //    $('#total_amount_paid').html(number_format(data.amount_paid, 2));

        //    }

       if (balance <= 0) {
            lock = true;
       }

       $('#balance_due').val(number_format(balance, 2));
       $('#invoice_amount').val(number_format(data['invoice_amount'], 2));
       //
         //    console.log(last_paid)
       document.getElementById('receipt_dialog').show();
        $.post("<?php echo URL_ROOT ?>/finance/accountSetting/getCashbookBalance", {client_code: last_paid.client_code ?? ''}, (data)=>{
            // console.log(data)
            $("#credit_balance-span").html(number_format(data.balance ?? 0, 2));
            loadRef({data: receipt});
        },'JSON')
    }
    
    //
    let modalInvoice = () => {
        //
        document.getElementById('receipt_dialog').close();
        $('#modal-invoices').modal('show');
        
        loadInvoice({term_code: term.code});
        
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let tableInvoices = $("#table-invoices").DataTable();

    let loadInvoice = (json) => {
        // console.log(json);
    
        // dataTables
        let url = "<?php echo URL_ROOT ?>/finance/accountSetting/getInvoices";
        // $.post(url, {agent_code: json.agent_code}, function(data) { console.log(data) }); return;
    
        tableInvoices.destroy();
    
        tableInvoices = $('#table-invoices').DataTable({
            "processing": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {term_code: json.term_code},
            },
            "columns": [
                {"data": "trans_date", "className": "dt-body-nowrap"},
                // {"data": "invoice_code", "className": "dt-body-nowrap"},
                {"data": "std_name", "render": $.fn.dataTable.render.ellipsis(20)},
                {"data": "parent_last_name", "render": function(data, type, row, meta){
                    return row["parent_title"] + " "+ row["parent_last_name"] + " " + row["parent_first_name"]
                }},
                // {"data": "term_name", "className": "dt-body-nowrap", "render": function(data, type, row, meta){
                //     return row['term_name'] + " " + row['year'];
                // }},
                {"data": "invoice_amount", "render": function(data, type, row, meta) { return number_format(data, 2) }},
                // {"data": "level_fees", "render": function(data, type, row, meta) { 
                //     return number_format(data, 2) 
                // }},
                // {"data": "other_fees", "className": "dt-body-nowrap",  "render": $.fn.dataTable.render.ellipsis(20)},
                {"data": "total_receipt_amount", "render": function(data, type, row, meta) { 
                    return number_format(data, 2) 
                }},
                {"data": "balance_due", "render": function(data, type, row, meta) { 
                    return number_format(data, 2) 
                }},
                {"data": "branch_name", "render": $.fn.dataTable.render.ellipsis(10)},
                {"data": "currency_code", "className": "dt-body-nowrap"}
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false}
            ],
            "aaSorting": [[0, "desc"]],
            "initComplete": function (settings, json_) {
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                // console.log(json_);
                // $('#modal-invoices').modal('show');

                // let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableBank.search(this.previousElementSibling.value).draw() });
                //     $("#table-bank_filter.dataTables_filter input")
                //     .unbind()
                //     .bind("input, keyup", function(e) {
                //         if( (e.charCode || e.keyCode || e.which) === 13) tableBank.search(this.value).draw();
                //         e.preventDefault();
                //     }).prop({placeholder: 'Press [Enter] Key'})
                //     .after(searchButton).prop({autocomplete: 'off'});
            }
        });
        tableInvoices.search('', false, true);
        tableInvoices.row(this).remove().draw(false);
    }

    //
    $('#table-invoices tbody').on('click', 'td', function () {
        //
        let data = tableInvoices.row($(this)).data(); // data["colName"]
        // console.log(data);return;
        //  return false;
        let rowId = $(this).parent('tr').index();
        
        localStorage.setItem('selected-row', rowId);
        
        // console.log(client_code)
        $('#modal-invoices').modal('hide');
        
        modalReceipt({table: 'table-receipt', row: data});
        // document.getElementById('receipt_dialog').show('show');
        // modalReceipt({row:''});
    });

    // /////////////////////////////////////////////////////////////////////////////////////////
    let tableRef = $("#table-ref").DataTable();

    let loadRef = (json) => {
        // console.log(json);
        tableRef.destroy();
    
        tableRef = $('#table-ref').DataTable({
            "processing": true,
            //"serverSide": true,
            "data": json.data,
            // "fixedHeader": true,
            // "fixedColumns": {leftColumns: 1},
            "columns": [
                {
                    "data": "ref_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<input type="checkbox" class="payment-list" name="'+row['receipt_code']+'" value="' + row['ref_code'] + '">';
                    }
                },
                {"data": "trans_date", "className": "dt-body-nowrap"},
                {"data": "client_fullname", "className": "dt-body-nowrap"},
                {"data": "invoice_code", "className": "dt-body-nowrap"},
                {"data": "receipt_code", "className": "dt-body-nowrap"},
                {"data": "amount", "render": function(data, type, row, meta) { return number_format(data, 2) }},
                {"data": "ref_code", "className": "dt-body-nowrap"}
            ],
            "columnDefs": [
                // {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[0, "desc"]],
            "initComplete": function (settings, json_) {
                // console.log(json_);
            }
        });
    }

    tableRef.search('', false, true);
    //
    tableRef.row(this).remove().draw(false);
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        // console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        let receipt_code = localStorage.getItem('receipt_code');
        let receipt = null;
        // console.log(receipt_code)
        if (receipt_code !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
            
            if (receipt_code !== '') {
    
                let tableReceipt = $('#table-receipt').DataTable();
                tableReceipt.rows().every(function () {
                    let data = this.data();
                    // console.log(data);
                    if(data.receipt_code === receipt_code){
                        receipt = data;
                        
                    }
                });
                ((receipt ?? '') === '') ? modalReceipt({table: '#table-receipt', row: ''}) : modalReceipt({table: '#table-receipt', row: receipt});
    
            }
            else modalReceipt({table: '#table-receipt', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveReceipt = (json) => {
         //    let ddd =  document.getElementById('client_code').value;
            //     console.log(ddd)
        let tableReceipt = $('#table-receipt').DataTable();
        
        if (!confirm('Save Receipt: ')) {
           return false;
        }
        
        //console.log($('#save').prop('disabled'));
        if ($('#save-receipt').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#receipt_dialog').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            // console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('receipt', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
                // console.log(obj['id'], obj['value'])
            }
            
        });
        // console.log(form_data);return;
        // return
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/receipt/_save/?user_log=<?php echo $data['params']['user_log'] ?>&risk=<?php echo $data['params']['risk'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-receipt').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-receipt').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                // console.log(data);return
                //
                saving = false;
                //
                $('#save-receipt').html('Save Changes');
                $('#save-receipt').prop({disabled: false});
                
                if (data.status === true) {
                    // console.log(data)
                    //
                    new Noty({type: 'success', text: '<h5>successful!</h5>', timeout: 10000}).show();
                    tableReceipt.ajax.reload(null, false);
                    // document.getElementById('receipt_dialog').close();
                    setTimeout(() => {
                        localStorage.setItem("receipt_code", data.data);
                        localStorage.setItem('modalOpen', "1");
                        modalAuto();
                        
                    }, 1000);
                    return false;
                }
                //
                new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
                //
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-receipt').html('Save Changes');
                $('#save-receipt').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    
        // /////////////////////////////////////////////////////////////////////////////////////////
        tableReceipt = $("#table-receipt").DataTable();
        
        let loadReceipt = (term) => {
            // console.log(term)
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/receipt/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }, 'JSON'); return;
            
            tableReceipt.destroy();
            
            tableReceipt = $('#table-receipt').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {term: term},
                },
                "columns": [
                    {
                        "data": "receipt_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<input type="checkbox" class="receipt-list" value="' + row['receipt_code'] + '">';
                        }
                    },
                    {"data": "trans_date"},
                    {"data": "receipt_code"},
                    {"data": "invoice_code"},
                    {"data": "total_receipt_amount", "render": function(data, type, row, meta){
                        return number_format(data);
                    }},
                    {"data": "invoice_amount", "render": function(data, type, row, meta){
                        return number_format(data);
                    }},
                    {"data": "first_name", "render": function(data, type, row, meta){
                        return row['student_first_name'] + " " + row['student_last_name']
                    }},
                    {"data": "", "render": function(data, type, row, meta){
                        return (row['parent_title'] ?? '') + " " + (row['parent_first_name'] ?? '') + " " + (row['parent_last_name'] ?? '')
                    }},
                    {"data": "", "render": function(data, type, row, meta){
                        return row['cat_name'] + " " + row['class_name']
                    }}
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "desc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    // console.log(json);
                }
            });
        }
        
        loadReceipt(term.code);
    
    //
    $(() => {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        
        $('#client_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getCashbooks",
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
                    return {results: response};
                },
                cache: true
                }
                }).on("select2:select", ()=>{
                let client_code = $("#client_code").find(":selected").val();
                let client_fullname = $("#client_code").find(":selected").html();
                $("#client_fullname").val(client_fullname);
               // modalReceipt({table: '#table-receipt', row: '', client_code: client_code, client_fullname: client_fullname});
                $.post("<?php echo URL_ROOT ?>/finance/accountSetting/getCashbookBalance", {client_code: client_code}, (data)=>{
                    // console.log(data)
                    //  $('#client_code-box').find('span#client_spinner').remove();
                    $("#credit_balance-span").html(number_format(data.balance, 2));
                },'JSON')
                //    console.log(cashbook_code);
        });
        
        // $('#ref_code').on('click', () => modalInvoice());
        
        //
        flatpickr('#trans_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });
        
        $('#invoice_code').on('click', () => modalInvoice());
        
        //
        tableReceipt.search('', false, true);
        //
        tableReceipt.row(this).remove().draw(false);
        
        //
        $('#table-receipt tbody').on('click', 'td', function () {
            //
            //let data = tableReceipt.row($(this)).data(); // data["colName"]
            let data = tableReceipt.row($(this));
            let data_ = tableReceipt.row($(this)).data();
            let client_code = data_.client_code;
            // console.log(data_);return;
            let rowId = $(this).parent('tr').index();
    
            localStorage.setItem('selected-row', rowId);
    
            if (!data) return;
            //
            if (this.cellIndex != 0) {
                //
                modalReceipt({table: '#table-receipt', row: data_});
                //
            }
        });
        
    })
</script>