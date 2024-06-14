<?php
$data = $data ?? [];
// var_dump($data['students']);exit;
$term = $data['term'] ?? [];
$termObj = $data['termObj'] ?? [];
echo $data['menu'];
// var_dump($term);exit;
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page"> Invoices </li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button href="javascript:void(0)" onclick="modalInvoices({table: '#table-invoices', row: ''}); $('#modal-title').html('New Invoices')"><i class="fa fa-plus"></i> Add</button>
            <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-cog"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99">
                <a class="dropdown-item" href="#" onclick="printInvoice({invoice_code: $('input.invoice-list:checked').map(function(v){return $(this).val();}).get() })">
                    <i class="fas fa-print text-dark-pastel-green"></i> Invoice
                </a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="loadStudent()" >
                    <i class="fas fa-school"></i> Students
                </a>
                <a class="dropdown-item" href="#"  onclick="deleteInvoice({invoice_code: $('input.invoice-list:checked').map(function(v){return $(this).val();}).get() })">
                <i class="fas fa-trash text-orange-peel"></i> Delete
                </a>
            </div><div class="ml-1" style="display:inline;z-index:99;">
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
                <div class="dataTables_wrapper">
                    <table id="table-invoices" class="table table-striped table-bordered table-sm nowrap w-100">
                        <thead>
                        <tr>
                            <th><input type="checkbox" class="" onclick="$('input.invoice-list:not(:disabled)').prop({checked: $(this).prop('checked')})"></th>
                            <th>Trans date</th>
                            <th>Name</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Branch</th>
                            <th>Term</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- invoice Modal -->
<div id="modal-invoices" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Invoice New/Edit</h4>
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
                        <a href="javascript:void(0)" onclick="modalInvoices({table: '#table-invoices', row: ''}); $('#modal-title').html('New Invoice')"><i class="fa fa-plus"></i> Reset</a>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="invoice_code" class="col-md-4 col-form-label text-sm-right">Invoice Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="invoice_code" maxlength="50">
                                        </div>
                                        <code class="small text-danger" id="invoice_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="invoice_code_old" readonly>
                                    <input type="hidden" id="receipt_code" readonly>
                                    <input type="hidden" id="term_code" value="<?php echo $term['code'] ?>" readonly>
                                </div>

                                <div class="form-group row">
                                    <label for="trans_date" class="col-md-4 col-form-label text-sm-right"> Trans Date <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3 ml-0">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="trans_date" maxlength="50">
                                        </div>
                                        <code class="small text-danger" id="trans_date--help">&nbsp;</code>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="client_code" class="col-md-4 col-form-label text-sm-right">Student <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="client_code" style="width: 100%">
                                            </select>
                                        </div>
                                        <code class="small text-danger" id="client_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branch_code" class="col-md-4 col-form-label text-sm-right">Branch <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" id="branch_code" style="width: 100%">
                                        </div>
                                        <code class="small text-danger" id="branch_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <!-- <div class="form-group row">
                                    <label for="trans_info" class="col-md-4 col-form-label text-sm-right"> Trans Info <br><span class="small text-primary">Optional</span></label>
                                    <div class="col-md-8 pr-3 ml-0">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="trans_info" maxlength="50">
                                        </div>
                                        <code class="small text-danger" id="trans_info--help">&nbsp;</code>
                                    </div>
                                </div> -->
                                
                                <!-- <div class="form-group row">
                                    <label for="description" class="col-md-4 col-form-label text-sm-right"> description <br><span class="small text-primary">Optional</span></label>
                                    <div class="col-md-8 pr-3 ml-0">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="description" maxlength="50">
                                        </div>
                                        <code class="small text-danger" id="description--help">&nbsp;</code>
                                    </div>
                                </div> -->
                                
                            </div>

                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="bank_account" class="col-md-4 col-form-label text-sm-right">Bank <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="bank_account" style="width: 100%"></select>
                                        </div>
                                        <code class="small text-danger" id="bank_account--help">&nbsp;</code>
                                    </div>
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

                                <!-- <div class="form-group row">
                                    <label for="vat_amount" class="col-md-4 col-form-label text-sm-right">VAT <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div style="width: 50%">
                                                <input class="form-control form-control-sm money" type="text" id="vat_amount" maxlength="20" readonly>
                                            </div>
                                            <div style="width: 4em; text-align: center">
                                                <span style="font-size: 0.9em; font-weight: bold">Rate %</span>
                                            </div>
                                            <div style="width: calc(50% - 4em)">
                                                <select class="form-control form-control-sm decimal" id="vat_rate" maxlength="20">
                                                    <option disabled hidden selected>Select a rate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="vat_amount--help">&nbsp;</code>
                                    </div>
                                </div> -->
                                
                                <div class="form-group row">
                                    <label for="level_fees" class="col-md-4 col-form-label text-sm-right">Level fees <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm money" id="level_fees">
                                        </div>
                                        <code class="small text-danger" id="level_fees--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="other_fees" class="col-md-4 col-form-label text-sm-right">Other fees <br><span class="small text-primary">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" id="other_fees" value="">
                                        </div>
                                        <code class="small text-danger" id="other_fees--help">&nbsp;</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-invoice" type="button" style="margin-left: auto" onclick="saveInvoice({})"><i class="fa fa-save"></i> Save</button>
                        </div>
                    
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- student Modal -->
<div id="modal-student" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Students </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <button id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-cog"></i> Select
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99">
                        <a class="dropdown-item" href="#" onclick="">
                            <i class="fas fa-cogs text-dark-pastel-green"></i> Edit
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="createInvoice({std_code: $('input.student-list:checked').map(function(v){return $(this).val();}).get() })" >
                            <i class="fas fa-file-invoice-dollar"></i> Create invoice
                        </a>
                    </div>
                </nav>
                <div class="tab-content">
                    
                    <div class="tab-pane show active" id="page_1">
                        <!-- <a href="javascript:void(0)" onclick="modalInvoices({table: '#table-students', row: ''}); $('#modal-title').html('New Student')"><i class="fa fa-plus"></i> Reset</a> -->
                        
                        <!-- <div class="form-group mb-2 d-flex">
                            <button id="save-invoice" type="button" style="margin-left: auto" onclick="saveInvoice({})"><i class="fa fa-save"></i> Save</button>
                        </div> -->

                        <div class="table-responsive">
                            <div class="dataTables_wrapper">
                                <table id="table-std" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="" onclick="$('input.student-list:not(:disabled)').prop({checked: $(this).prop('checked')})"></th>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>term</th>
                                            <th>Class</th>
                                            <th>Fees</th>
                                            <th>Picture</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Phone</th>
                                            <th>Admission ID</th>
                                            <th>Birthday</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    const TRANS_MODE = <?php echo json_encode(TRANS_MODE) ?>;
    const TRANS_TYPE = <?php echo json_encode(TRANS_TYPE) ?>;
    const TRANS_TYPE_REC = <?php echo json_encode(TRANS_TYPE_REC) ?>;
    const branchObj = <?php echo json_encode($data['branchObj']) ?>;
    let term = <?php echo json_encode($data['term']) ?>;
    let termObj = <?php echo json_encode($data['termObj']) ?>;
    let tableStudent = null;
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    //
    let deleteInvoice = (json) => {
        // console.log(json);return;
        let tableInvoice = $("#table-invoices").DataTable();
        // let data = tableStudents.row(json.row).data(); // data["colName"]
        // console.log(json);return;
        
        if (!confirm('You are about to delete ' + json.invoice_code.length + ' invoices(s)') || json.invoice_code.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/invoices/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', json, function (data) {
            //console.log(data);
            if (data.status) {
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                tableInvoice.ajax.reload(null, false);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
            //
            
        }, 'JSON');
    }

    let loadStudent = (json) => {
        modalLoadingDiv = $("#modal-student");
        modalLoading({status: "show"})
        tableStudents = $("#table-std").DataTable();
        // dataTables
        let url = "<?php echo URL_ROOT ?>/school/students/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        // $.post(url, {}, function(data) { console.log(data) }); return;
    
        tableStudents.destroy();
    
        tableStudents = $('#table-std').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {},
            },
            "columns": [
                {
                    "data": "std_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<input type="checkbox" class="student-list" value="' + row['std_code'] + '">';
                    }
                },
                {"data": "create_date"},
                {"data": "std_code"},
                {"data": "term", "render": function(data, type, row, meta){
                    return row['term_name'] + " " + row['year'];
                }},
                {"data": "cat_name", "render": function(data, type, row, meta){
                    return row.cat_name + " " + row.class_name;
                }},
                {"data": "fees", "render": function(data, type, row, meta){
                    return number_format(data);
                }}, 
                {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                    return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                    }},
                {"data": "first_name", "render": function(data, type, row, meta){
                    return row.first_name + " " + row.last_name;
                }},
                {"data": "gender"},
                {"data": "phone"},
                {"data": "admission_id"},
                {"data": "birthday"},
                {"data": "email"},
                
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "desc"]],
            "initComplete": function (settings, json) {
                modalLoading({status: ""})
                // console.log(json)
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                //  console.log(json);
                
                // let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableBank.search(this.previousElementSibling.value).draw() });
                //     $("#table-bank_filter.dataTables_filter input")
                //     .unbind()
                //     .bind("input, keyup", function(e) {
                //         if( (e.charCode || e.keyCode || e.which) === 13) tableBank.search(this.value).draw();
                //         e.preventDefault();
                //     }).prop({placeholder: 'Press [Enter] Key'})
                //     .after(searchButton).prop({autocomplete: 'off'});
                
                $('#modal-student').modal('show');
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    }

    let createInvoice = (json)=>{
        modalLoadingDiv = '';
         console.log(json);
        //  return;
         let tableInvoice = $("#table-invoices").DataTable();
        // let data = tableStudents.row(json.row).data(); // data["colName"]
        // console.log(json);return;
        
        if (!confirm('You are about to create ' + json.std_code.length + ' invoices(s)') || json.std_code.length === 0) {
            return false;
        }
        modalLoadingDiv = $("#modal-student");
        modalLoading({status: "show"})
        
       // $('.gen_invoice_btn').html('<i class="fa fa-spinner fa-spin"></i> Print').prop({disabled: true});
        $.post('<?php echo URL_ROOT ?>/finance/invoices/saveMultipleInvoice/?user_log=<?php echo $data['params']['user_log'] ?>', json, function (data) {
            // console.log(data);
            // modalLoadingDiv = '';
            if (data.status) {
                // modalLoadingDiv = $("#modal-student");
                modalLoading({status: ""});
                new Noty({type: 'success', text: '<h5>SUCCESSFUL!</h5>', timeout: 10000}).show();
                tableInvoice.ajax.reload(null, false);
                return false;
            }
            //
            new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
            // modalLoadingDiv = $("#modal-student");
            modalLoading({status: ""});
            tableInvoice.ajax.reload(null, false);
            //
            
        }, 'JSON');
        
    }

    let modalInvoices = (json) => {
        let data =  json.row === '' ? {} : json.row;
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        let other_fees = (data['other_fees']);
        let level_fees = data['level_fees'] ?? 0;
        let branch_name = data['branch_name'] ?? '';
        let total_amount = data['invoice_amount'] ?? 0;
        let std_name = data['std_name'] ?? "";
        let std_code = data['std_code'] ?? "";
        let invoice_code = (data['invoice_code'] === '') ? 'AUTO' : (data['invoice_code'] === undefined ? 'AUTO' : data['invoice_code'])
        let receipt_code = (data['receipt_code'] === '') ? 'AUTO' : (data['receipt_code'] === undefined ? 'AUTO' : data['receipt_code'])
        // console.log(data)
        if (!data) data = [];
        let lock = (data['invoice_code'] ?? '') !== '';
        $('#invoice_code_old').val(data['invoice_code'] ?? '');
        $('#invoice_code').val(invoice_code).prop({disabled: lock});
        $('#receipt_code').val(receipt_code);
        //
        $('#trans_date').val((data['trans_date'] ?? moment().format('YYYY-MM-DD')).slice(0, 10));
        $('#client_code').append(new Option(std_name, std_code, true, true)).trigger('change');
        $('#branch_code').val(branch_name).prop({disabled: lock});
        $('#level_fees').val(number_format(level_fees, 2)).prop({disabled: lock});
        $('#other_fees').val(other_fees).prop({disabled: lock});
        // //
        $('#currency_code').val(data['currency_code'] ?? '<?php echo BASE_CURRENCY ?>').trigger('change');
        $('#currency_rate').val(data['currency_rate'] ?? '<?php echo BASE_RATE ?>').prop({disabled: lock});
        $('#amount').val(number_format(total_amount, 2)).prop({disabled: lock});
        // $('#trans_info').val(data['trans_info'] ?? '');
        // $('#description').val(data['description'] ?? '');
        $('#bank_account').append(new Option(data['bank'] ?? '', data['bank_account'] ?? '', true, true)).trigger('change');
        $('#modal-invoices').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    $("#vat_rate").select2({
        placeholder: "Select rate",
        allowClear: true,
        data: [
            {id: '0.075', text: "7.5"},
            {id: '0.045', text: "12.5"}
        ]
    }).on("select2:select", ()=>{
       let v = getFloat($("#vat_rate").find(":selected").val());
       let amt = getFloat($("#amount").val());
       $("#vat_amount").val(number_format(v * amt, 2));
    //    console.log(v)
    })


    // /////////////////////////////////////////////////////////////////////////////////////////
    let tableInvoices = $("#table-invoices").DataTable();
    // let tableInvoices = null;

    let loadInvoice = (json) => {
        // console.log(json);
        // dataTables
        let url = "<?php echo URL_ROOT ?>/finance/invoices/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        tableInvoices.destroy();
        //console.log(url)
        tableInvoices = $('#table-invoices').DataTable({
            "processing": true,
            // "serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {term_code: json.term_code},
            },
            "columns": [
                {
                    "data": "invoice_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<input type="checkbox" class="invoice-list" value="' + row['invoice_code'] + '">';
                    }
                },
                {"data": "trans_date", "render": function (data, type, row, meta) {
                        return moment(data).format('YYYY-MM-DD');
                    }},
                {"data": "std_name"},
                {"data": "currency_code"},
                {"data": "invoice_amount", "render": function(data, type, row, meta){
                    return number_format(data, 2);
                }},
                {"data": "branch_name"},
                {"data": "", "render": function(data, type, row, meta){
                    return row['term_name'] + " " + row['year']
                }},
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "desc"]],
            "initComplete": function (settings, json) {
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                // console.log(json);
                // modalAuto();
            }
        });
    }

    loadInvoice({term_code: term.code});

    //
    tableInvoices.search('', false, true);
    //
    tableInvoices.row(this).remove().draw(false);

    //
    $('#table-invoices tbody').on('click', 'td', function () {
        //
        let data = tableInvoices.row($(this)).data();
        //console.log(data)
        let rowId = $(this).parent('tr').index();
        //console.log("row clicked : " + rowId)

        // localStorage.setItem('selected-row', rowId);
        // console.log(data);return
    
        if (!data) return;
        //
        //console.log(this.cellIndex);
        if (this.cellIndex != 0) {
            //
            modalInvoices({table: '#table-invoices', row: data});
            //
            $('#modalNav a[href="#page_1"]').tab('show');
        }
    });

    // /////////////////////////////////////////////////////////////////////////////////////////

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
    let saveInvoice = (json) => {
        // console.log(json);return;
        let tableInvoice = $("#table-invoices").DataTable();
        
        if ($('#save-invoice').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-invoices').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
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
        // console.log(form_data);return;
        // }
        //console.log($('#modal-client').find('input, select, textarea').length); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/invoices/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-invoice').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-invoice').prop({disabled: true});
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
                $('#save-invoice').html('Save Changes');
                $('#save-invoice').prop({disabled: false});
                
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
                // tableInvoice.ajax.reload(null, false);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-invoice').html('Save Changes');
                $('#save-invoice').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        // $('#vat_rate').on('keyup change', () => calc({
        //     elem: 'vat_rate'
        // }));
        // $('#trans_mode').select2({
        //     placeholder: "Select an option",
        //     allowClear: true,
        //     data: Object.keys(TRANS_MODE).map(function(v) { return {id: v, text: TRANS_MODE[v]} }),
        // });


        //
        $('#currency_code').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        //
        $('#currency_code').select2({
            placeholder: "Select an option",
            allowClear: true,
        });

        //
        // $('#client_code').select2({
        //     placeholder: "Select an option",
        //     allowClear: true,
        // });
        
        //
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
                    console.log(response);
                    return { results: response };
                },
                cache: true
            }
        }).on("select2:select", ()=>{
            modalLoadingDiv = $('#modal-invoices');
            modalLoading({status: "show"})
           let url = "<?php echo URL_ROOT ?>/finance/AccountSetting/getStudentForInvoice";
           $.post(url, {_option: "std_code", std_code: $("#client_code").val()}, (data)=>{
                modalLoading({status: ''})
                // console.log(data);return;
                // let row = JSON.parse(data);
                let act = JSON.parse(data.activities)
                let arr = [];
                let total_price = parseFloat(data.fees ?? 0);
                // console.log(data);return;
                $.each(act, (k, v)=>{
                    let obj = {};
                    total_price = total_price + parseFloat(v.product_price)
                    obj[v.product_name] = number_format(v.product_price);
                    arr.push(obj)
                })
                    // console.log(row.fees);
                if(arr.length <= 0){
                    arr = ''
                }else{
                    arr = JSON.stringify(arr)
                }

                let obj2 = {
                    level_fees: data.fees,
                    branch_name: data.branch_name,
                    other_fees: arr,
                    invoice_amount: total_price,
                    std_name: data.std_name ?? '',
                    std_code: data.std_code ?? '',
                    invoice_code: data.invoice_code ?? '',
                    receipt_code: data.receipt_code ?? '',
                }
                    // console.log(obj2);
                modalInvoices({row: obj2})
            }, 'JSON')
        })
        //
        $('#bank_account').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getBanks",
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
        // //
        flatpickr('#trans_date', {
            dateFormat: 'Y-m-d',
            // allowInput: true,
            minDate: '1800-01-01',
            maxDate: moment().format('YYYY-MM-DD'),
        });
    
        $('#modal-invoices').on('hidden.bs.modal', function () {
            tableInvoices.ajax.reload(null, false);
        });

        $('#modal-student').on('hidden.bs.modal', function () {
            tableInvoices.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
            
            // receivable
            if ($('#modal-invoices').hasClass('show')) {

                // access
                // if (module_access['finance']['cashbook']['receivable'] < 2) disabled = true;


                // trans_code
                if ($('#invoice_code').val().trim() === '' && $('#invoice_code_old').val().trim() !== '') {
                    disabled = true;
                    $('#invoice_code--help').html('TRANSACTION CODE REQUIRED')
                } else {
                    $('#invoice_code--help').html('&nbsp;')
                }
                // trans_code
                if ($('#branch_code').val().trim() === '' ) {
                    disabled = true;
                    $('#branch_code--help').html('BRANCH NAME REQUIRED')
                } else {
                    $('#branch_code--help').html('&nbsp;')
                }

                // trans_date
                if ($('#trans_date').val() === '' || !moment($('#trans_date').val()).isValid()) {
                    disabled = true;
                    $('#trans_date--help').html('TRANSACTION DATE INVALID')
                } else {
                    $('#trans_date--help').html('&nbsp;')
                }

                // client_code
                if (($('#client_code').val() ?? '') === '') {
                    disabled = true;
                    $('#client_code--help').html('CLIENT NAME REQUIRED')
                } else {
                    $('#client_code--help').html('&nbsp;')
                }

                if (($('#level_fees').val() ?? '') === '') {
                    disabled = true;
                    $('#level_fees--help').html('CLIENT NAME REQUIRED')
                } else {
                    $('#level_fees--help').html('&nbsp;')
                }

                // currency_code
                if (($('#currency_code').val() ?? '') === '') { 
                    disabled = true;
                    $('#currency_code--help').html('CURRENCY REQUIRED')
                } else if (getFloat($('#currency_rate').val() ?? '') <= 0) {
                    disabled = true;
                    $('#currency_code--help').html('CURRENCY RATE REQUIRED')
                } else if (getFloat($('#amount').val() ?? '') <= 0) {
                    disabled = true;
                    $('#currency_code--help').html('RECEIPT AMOUNT REQUIRED')
                } else {
                    $('#currency_code--help').html('&nbsp;')
                }


                //
                if (saving) disabled = true;
                $('#save-invoice').prop({disabled: disabled});
                }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



