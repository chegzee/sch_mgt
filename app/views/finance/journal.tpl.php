<?php
$data = $data ?? [];
echo $data['menu'];
?>

<!-- Main body -->
<div class="main-body">
    
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="#">Journal</a></li>
        </ol>
    </nav>
    
    <div class="card card-style-1">
        <div class="card-body">
            <a href="javascript:void(0)" onclick="modalJournal({table: '#table-journal', row: ''}); $('#modal-title').html('New Journal')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> New</a>
            <div class="table-responsive">
                <table id="table-journal" class="table table-striped table-bordered table-sm nowrap w-100"
                       style="cursor: pointer">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>Date</th>
                        <th>Journal Code</th>
                        <th>Act Code</th>
                        <th>Act Name</th>
                        <th>Curr</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Beneficiary</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /Main body -->

<!-- journalModal -->
<div id="modal-journal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Journal New/Edit <span class="loading d-inline-block"><i class="fa fa-spinner fa-spin"></i></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
    
                <a href="javascript:void(0)" onclick="if (confirm('The form will be permanently cleared!!!')) modalJournal({table: '#table-journal', row: ''}); $('#modal-title').html('New Journal')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary mb-3 other_link" onclick="uploadJournal({})"><i class="fa fa-print"></i> Upload</a>
                <button type="button" class="btn btn-sm btn-outline-primary mb-3 other_link rpt_button" onclick="loadIframe({func: 'journal-print', modal: '#iframeModal', title: $('#trans_code').val(), trans_code: $('#trans_code').val(), report: 'journal_print', detail: 'QUOTATION'})"><i class="fa fa-print"></i> Print</button>
                
                <style>
                    div.journal {
                        height: 24px;
                        overflow: hidden;
                    }
                    .table-sm td, .table-sm th {
                        padding: .2rem;
                    }
                </style>
    
                <div class="table-responsive" style="border-top: 1px solid">
                    <div class="dataTables_wrapper">
                        <table id="table-journal-header" class="table table-striped table-bordered table-sm nowrap w-100 datatableList" style="cursor: pointer">
                            <thead>
                                <tr>
                                    <th style="width: 150px">Date</th>
                                    <th style="width: 200px">Journal Code</th>
                                    <th style="width: 150px">Currency</th>
                                    <th style="width: 300px">Reference</th>
                                    <th>Beneficiary</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
    
                <style>
                    .select2-container {
                        box-sizing: border-box;
                        display: inline-block;
                        margin: 0;
                        position: relative;
                        vertical-align: middle;
                    }
    
                    .select2-container .select2-selection--single {
                        box-sizing: border-box;
                        cursor: pointer;
                        height: 16px;
                        user-select: none;
                        -webkit-user-select: none;
                        display: contents;
                    }
                    .select2-container--default .select2-selection--single .select2-selection__arrow {
                        height: 26px;
                    }
                    .select2-container--default .select2-selection--single .select2-selection__rendered {
                        height: 24px;
                    }
                    .select2-results__option, .select2-selection__rendered {
                        font-size: 10pt;
                    }
                </style>
    
                <div class="table-responsive" style="border-top: 1px solid">
                    <div class="dataTables_wrapper">
                        <table id="table-journal-body" class="table table-striped table-bordered table-sm nowrap w-100" style="cursor: pointer">
                            <thead>
                            <tr>
                                <th style="width: 50px">&nbsp;</th>
                                <th style="width: 450px">Account</th>
                                <th style="width: 150px">Debit <span id="dr-balance" style="float: right; font-size: 85%; font-weight: normal"></span></th>
                                <th style="width: 150px">Credit <span id="cr-balance" style="float: right; font-size: 85%; font-weight: normal"></span></th>
                                <th>Description <span id="balance" style="float: right; font-size: 85%; font-weight: normal"></span></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                
                <input type="hidden" id="trans_code_old" readonly>
                <input type="hidden" id="trans_code" readonly>
                <input type="hidden" id="branch_code" readonly>
                <input type="hidden" id="journal" readonly>

                <div class="form-group mb-2 d-flex">
                    <button id="save-journal" class="btn btn-success" type="button" style="margin-left: auto" onclick="saveJournal({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
                </div>
                
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="menu">
    <a class="dropdown-item" href="#">Action</a>
    <a class="dropdown-item" href="#">Another action</a>
    <a class="dropdown-item" href="#">Something else here</a>
</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    // console.log(userAccess);
    
    // currencies
    let currencies = <?php echo json_encode($data['currencyObj']) ?>;
    
    // accounts
    let accounts = <?php echo json_encode($data['accountObj']) ?>;
    
    //
    let deleteJournal = (json) => {
        //console.log(json);
        let tableJournal = $(json.table).DataTable();
        let data = tableJournal.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete journal: ' + data['trans_code'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/journal/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableJournal.ajax.reload(null, false);
            
        }, 'JSON');
    }
    
    //
    let uploadJournal = (json) => {
        console.log(json);
    }
    
    //
    let modalJournal = (json) => {
        console.log(json);
        $('span.loading').removeClass('d-none').addClass('d-inline-block');
        
        let tableJournal = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableJournal.row(json.row).data(); // data["colName"]// policy
        //
        let header = $('#table-journal-header tbody');
        let body = $('#table-journal-body tbody');
        
        if(data['trans_code'] === undefined || data['trans_code'] === '') {
            data['trans_code'] = '~~~~';
        }
        
        // load journal details
        $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getJournals/?user_log=<?php echo $data['params']['user_log'] ?>', {_option: 'detail', trans_type: 'JNL', trans_code: data.trans_code}, function (data_) {
            // console.log(data_); // return;
            let journals = [...data_];
            $('span.loading').removeClass('d-inline-block').addClass('d-none');
            //
            header.find('tr').remove();
            body.find('tr').remove();
            //
            if (journals[0] === undefined) {
                journals[0] = {};
                journals[0]['trans_code'] = 'AUTO';
                // journals[0]['trans_code_old'] = '';
                journals[0]['branch_code'] = '';
                journals[0]['trans_date'] = moment().format('YYYY-MM-DD');
                journals[0]['currency_code'] = '<?php echo BASE_CURRENCY ?>';
                journals[0]['currency_rate'] = 1;
                journals[0]['ref_code'] = '';
                journals[0]['trans_detail'] = '';
                journals[0]['beneficiary'] = '';
                journals[0]['account_code'] = '';
                journals[0]['account_name'] = '';
            }
            // console.log(journals);
            
            $('#trans_code_old').val(journals[0]['trans_code'] === '' || journals[0]['trans_code'] === 'AUTO' ? '' : journals[0]['trans_code']);
            $('#trans_code').val(journals[0]['trans_code']);
            $('#branch_code').val(journals[0]['branch_code']);
            
            // table-journal-head
            let v = journals[0];
            let html =
                '<tr class="journal">\
                    <td><input type="text" class="w-100 border-0 calendar" value="' + v['trans_date'] + '" maxlength="10"></td>\
                    <td><input type="text" class="w-100 border-0" value="' + v['trans_code'] + '" maxlength="50"></td>\
                    <td><select class="w-50 border-0 bg-transparent" onchange="$(this).parent().find(\'input\').val($(this).find(\':selected\').data(\'currency_rate\'))">';
            $.each(currencies, function(i_, v_) {
                html += '<option value="' + v_.currency_code + '" data-currency_rate="' + v_.currency_rate + '" ' + (v['currency_code'] === v_.currency_code ? 'selected' : '') + '>' + v_.currency_code + '</option>';
            });
                html += '</select><input type="text" class="w-50 border-0 numeric" value="' + v['currency_rate'] + '" maxlength="10" readonly tabindex="-1"></td>\
                    <td><input type="text" class="w-100 border-0" value="' + v['ref_code'] + '" maxlength="100"></td>\
                    <td><input type="text" class="w-100 border-0" value="' + v['beneficiary'] + '" maxlength="200"></td>\
                </tr>';
            header.append(html);
    
            //
            flatpickr('.calendar:not(.flatpickr-input)', {
                dateFormat: 'Y-m-d',
                allowInput: true,
                minDate: '1800-01-01',
            });
            
            // table-journal-body
            $.each(journals, function(i, v) {
                body.append(journalRow({f: 'add', i: i, v: v}));
                journalInit();
            });
            
            //
            $('#modal-journal').modal('show');
    
        }, 'JSON');
    }
    
    let journalRow = (json) => {
        // console.log(json);
    
        let header = $('#table-journal-header tbody');
        let body = $('#table-journal-body tbody');
    
        // f: add
        if (json.f === 'add') {
            return '\
            <tr class="journal">\
                <td>\
                    <a class="dropdown-toggle" type="button" id="dropdownMenuButton' + json.i + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="btn-outline-success fa fa-cog"></i></a>\
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + json.i + '">\
                        <a class="dropdown-item text-primary" href="javascript:void(0)" onclick="journalRow({f: \'insert\', r: $(this).closest(\'tr\').index()})"><i class="fa fa-plus w-25px"></i> Insert Row</a>\
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="journalRow({f: \'delete\', r: $(this).closest(\'tr\').index()})"><i class="fa fa-minus w-25px"></i> Remove Row</a>\
                        <a class="dropdown-item text-primary" href="javascript:void(0)" onclick="journalRow({f: \'up\', r: $(this).closest(\'tr\').index()})"><i class="fa fa-arrow-up w-25px"></i> Move Up</a>\
                        <a class="dropdown-item text-primary" href="javascript:void(0)" onclick="journalRow({f: \'down\', r: $(this).closest(\'tr\').index()})"><i class="fa fa-arrow-down w-25px"></i> Move Down</a>\
                    </div>\
                </td>\
                <td><select class="border-0 bg-transparent account_code" style="width: 100%"><option value="' + json.v['account_code'] + '">(' + json.v['account_code'] + ') ' + json.v['account_name'] + '</option></select></td>\
                <td><input type="text" class="w-100 border-0 text-right money trans_detail" value="' + number_format(json.v['debit'], 2) + '"></td>\
                <td><input type="text" class="w-100 border-0 text-right money trans_detail" value="' + number_format(json.v['credit'], 2) + '"></td>\
                <td><input type="text" class="w-100 border-0 trans_detail" value="' + (json.v['trans_detail'] ?? '') + '"></td>\
            </tr>';
        }
    
        // f: blank
        else if (json.f === 'blank') {
            let last = body.find('tr:last').index();
            if (last == json.r) {
                body.append(journalRow({f: 'add', i: last + 1, v: {account_code: '', account_name: '', trans_detail: ''}}));
                journalInit();
            }
        }
    
        // f: insert
        else if (json.f === 'insert') {
            let i = json.r + 1;
            body.find('tr:eq(' + json.r + ')').after(journalRow({f: 'add', i: i, v: {account_code: '', account_name: '', trans_detail: ''}}));
            journalInit();
        }

        // f: delete
        else if (json.f === 'delete') {
            let last = body.find('tr:last').index();
            if (last <= 1) {
                journalRow({f: 'blank', r: 1});
            }
            body.find('tr:eq(' + json.r + ')').remove();
        }

        // f: up
        else if (json.f === 'up') {
            if (json.r <= 1) return;
            let row = body.find('tr:eq(' + json.r + ')');
            body.find('tr:eq(' + (json.r - 1) + ')').before(row);
        }

        // f: down
        else if (json.f === 'down') {
            let last = body.find('tr:last').index();
            if (json.r == last) return;
            let row = body.find('tr:eq(' + json.r + ')');
            body.find('tr:eq(' + (json.r + 1) + ')').after(row);
        }
    
    }
    
    let journalInit = () => {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
    
        //
        $('.account_code').select2({
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
                    return {results: response};
                },
                cache: true
            }
        });
    
        //
        $('.trans_detail').on('focus', function(e) {
            let item = $(this);
            journalRow({f: 'blank', r: item.closest('tr').index()});
        });
    
        //
        initInputFormat();
        
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        // console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let trans_code = '<?php echo $data['params']['trans_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
            
            if (trans_code !== '') {
                
                let tableJournal = $('#table-journal').DataTable();
    
                tableJournal.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === trans_code) {
                            // console.log(v, i);
                            modalJournal({table: '#table-journal', row: i});
                
                            return false;
                        }
                    });
                });
    
            }
            else modalJournal({table: '#table-journal', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveJournal = (json) => {
        //console.log(json);
        let tableJournal = $(json.table).DataTable();
        
        //console.log($('#save').prop('disabled'));
        if ($('#save-journal').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        form_data.append('trans_code', $('#trans_code').val());
        form_data.append('trans_code_old', $('#trans_code_old').val());
    
        // journal
        let journal = $('#journal').val();
        form_data.append('journal', journal);
        // console.log(form_data);return;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/journal/_save/?user_log=<?php echo $data['params']['user_log'] ?>&risk=<?php echo $data['params']['risk'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-journal').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-journal').prop({disabled: true});
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
                $('#save-journal').html('Save Changes');
                $('#save-journal').prop({disabled: false});
                
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
                $('#trans_code').val(data.data.trans_code);
                $('#trans_code_old').val(data.data.trans_code);
                //
                setTimeout(() => {
                    localStorage.setItem('modalOpen', '1');
                    parent.location.assign('<?php echo URL_ROOT ?>/finance/journal/?user_log=<?php echo $data['params']['user_log'] ?>&trans_code=' + data.data.trans_code + '#' + Math.random());
                }, 1000);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-journal').html('Save Changes');
                $('#save-journal').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    //
    $(() => {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
        //
        $('#currency_code').select2({
            placeholder: "Select an option",
            // allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/accountSetting/getCurrencies/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        $('#currency_code').on('change', () => $('#currency_rate').val(currencies[$('#currency_code').val()]['currency_rate']) );
        
        //
        flatpickr('#trans_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            // maxDate: new Date().fp_incr(0), // -92
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableJournal = $("#table-journal").DataTable();
        
        let loadJournal = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/journal/_list/?user_log=<?php echo $data['params']['user_log'] ?>&risk=<?php echo $data['params']['risk'] ?>";
            //$.post(url, {}, function(data) { console.log(data) }); return;
            
            tableJournal.destroy();
            
            tableJournal = $('#table-journal').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "trans_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a class="dropdown-toggle" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') + ' fa fa-cog"></i></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalJournal({table: \'#table-journal\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteJournal({table: \'#table-journal\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Reverse Item</a>') +
                                '</div>';
                        }
                    },
                    {"data": "trans_date"},
                    {"data": "trans_code"},
                    {"data": "account_code"},
                    {"data": "account_name"},
                    {"data": "currency_code"},
                    {"data": "debit_total", "render": function(data, type, row, meta) {
                        return number_format(data, 2);
                        }},
                    {"data": "ref_code"},
                    {"data": "trans_detail", "className": "dt-body-nowrap", "render": $.fn.dataTable.render.ellipsis(20)},
                    {"data": "beneficiary", "className": "dt-body-nowrap", "render": $.fn.dataTable.render.ellipsis(20)},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "desc"]],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    modalAuto();
                }
            });
        }
        
        loadJournal({});
        
        //
        tableJournal.search('', false, true);
        //
        tableJournal.row(this).remove().draw(false);
        
        //
        $('#table-journal tbody').on('click', 'td', function () {
            //
            //let data = tableJournal.row($(this)).data(); // data["colName"]
            let data = tableJournal.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
    
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalJournal({table: '#table-journal', row: data});
            }
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        $('#modal-journal').on('hidden.bs.modal', function () {
            tableJournal.ajax.reload(null, false);
        });
        
        // ////////////////////////////////////////////////////////////////////////////////////////
        
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
    
            // journal
            if ($('#modal-journal').hasClass('show')) {
                
                let journal = [];
                let journal_header = {};;
                let journal_row = {};;
                
                // table-journal-header
                let header = $('#table-journal-header'); //.find('tr:gt(0)');
                
                if (header.find('tr:gt(0)').length > 0)  {
                    $.each(header.find('tr:gt(0)'), function() {
                        let row = $(this);
                        
                        // trans_date
                        let trans_date = row.find('td:eq(0) input').val(); // console.log(trans_date);
                        if (!moment(trans_date).isValid() || trans_date.length < 10) {
                            header.find('tr:eq(0) th:eq(0)').addClass('bg-danger-faded');
                            disabled = true;
                        }
                        else header.find('tr:eq(0) th:eq(0)').removeClass('bg-danger-faded');
                        
                        // trans_code
                        let trans_code = row.find('td:eq(1) input').val(); // console.log(trans_code);
                        if (trans_code.trim() === '') {
                            header.find('tr:eq(0) th:eq(1)').addClass('bg-danger-faded');
                            disabled = true;
                        }
                        else header.find('tr:eq(0) th:eq(1)').removeClass('bg-danger-faded');
                        $('#trans_code').val(trans_code);
                        
                        // currency_code
                        let currency_code = row.find('td:eq(2) select').val(); // console.log(currency_code);
                        let currency_rate = getFloat(row.find('td:eq(2) input').val()); // console.log(currency_rate);
                        if (currency_code === '' || currency_rate <= 0) {
                            header.find('tr:eq(0) th:eq(2)').addClass('bg-danger-faded');
                            disabled = true;
                        }
                        else header.find('tr:eq(0) th:eq(2)').removeClass('bg-danger-faded');
                        
                        // ref_code
                        let ref_code = row.find('td:eq(3) input').val(); // console.log(ref_code);
                        if (ref_code.trim() === '') {
                            header.find('tr:eq(0) th:eq(3)').addClass('bg-danger-faded');
                            header.find('tr:eq(0) th:eq(3)').addClass('text-dark');
                            disabled = true;
                        }
                        else{
                            header.find('tr:eq(0) th:eq(3)').removeClass('bg-danger-faded');
                            header.find('tr:eq(0) th:eq(3)').removeClass('text-dark');
                            header.find('tr:eq(0) th:eq(3)').addClass("text-white");
                        } 
    
                        // beneficiary
                        let beneficiary = row.find('td:eq(4) input').val().trim(); // console.log(beneficiary);
                        
                        // journal
                        journal_header.trans_date = trans_date;
                        journal_header.trans_code = trans_code;
                        journal_header.currency_code = currency_code;
                        journal_header.currency_rate = currency_rate;
                        journal_header.ref_code = ref_code;
                        journal_header.beneficiary = beneficiary;
                        
                    });
                }
                
                // table-journal-body
                let body = $('#table-journal-body'); //.find('tr:gt(0)');
                
                let debit_total = 0;
                let credit_total = 0;
                let balance = 0;
                
                if (body.find('tr:gt(0)').length > 0) {
                    $.each(body.find('tr:gt(0)').not('tr:last'), function() {
                        let row = $(this);
                        
                        let account_code = row.find('td:eq(1) select').val(); // console.log(account_code);
                        let debit = getFloat(row.find('td:eq(2) input').val()); // console.log(account_code);
                        let credit = getFloat(row.find('td:eq(3) input').val()); // console.log(account_code);
                        
                        // account_code
                        if (account_code === '' || account_code === null) {
                            row.find('td:eq(0)').addClass('bg-danger-faded');
                            disabled = true;
                        }
                        // debit && credit
                        else if (debit <= 0 && credit <= 0 || debit > 0 && credit > 0) {
                            row.find('td:eq(0)').addClass('bg-danger-faded');
                            disabled = true;
                        }
                        //
                        else row.find('td:eq(0)').removeClass('bg-danger-faded');
    
                        // trans_detail
                        let trans_detail = row.find('td:eq(4) input').val().trim(); // console.log(trans_detail);
    
                        //
                        debit_total += debit;
                        credit_total += credit;
                        balance = debit_total - credit_total;
    
                        // journal
                        journal_row.trans_date = journal_header.trans_date;
                        journal_row.trans_code = journal_header.trans_code;
                        journal_row.currency_code = journal_header.currency_code;
                        journal_row.currency_rate = journal_header.currency_rate;
                        journal_row.ref_code = journal_header.ref_code;
                        journal_row.beneficiary = journal_header.beneficiary;
                        //
                        journal_row.account_code = account_code;
                        journal_row.account_name = accounts[account_code] ? accounts[account_code]['account_name'] : '';
                        journal_row.debit = debit;
                        journal_row.credit = credit;
                        journal_row.trans_detail = trans_detail;
    
                        //
                        if (!disabled) journal.push(journal_row);
                        journal_row = {};
                        
                    });
                    //
                    let row = body.find('tr:last');
                    let account_code = row.find('td:eq(1) select').val();
                    let debit = getFloat(row.find('td:eq(2) input').val());
                    let credit = getFloat(row.find('td:eq(3) input').val());
                    let trans_detail = row.find('td:eq(4) input').val().trim();

                    if (
                        (account_code !== '' || trans_detail !== '') && (debit <= 0 && credit <= 0 || debit > 0 && credit > 0)
                    ) {
                        row.find('td:eq(0)').addClass('bg-danger-faded');
                        disabled = true;
                    }
                    else row.find('td:eq(0)').removeClass('bg-danger-faded');
                    
                    //
                    debit_total += debit;
                    credit_total += credit;
                    balance = debit_total - credit_total;
    
                    // journal
                    journal_row.trans_date = journal_header.trans_date;
                    journal_row.trans_code = journal_header.trans_code;
                    journal_row.currency_code = journal_header.currency_code;
                    journal_row.currency_rate = journal_header.currency_rate;
                    journal_row.ref_code = journal_header.ref_code;
                    journal_row.beneficiary = journal_header.beneficiary;
                    //
                    journal_row.account_code = account_code;
                    journal_row.account_name = accounts[account_code] ? accounts[account_code]['account_name'] : '';
                    journal_row.debit = debit;
                    journal_row.credit = credit;
                    journal_row.trans_detail = trans_detail;
    
                    //
                    if (!disabled && (journal_row.debit > 0 || journal_row.credit > 0)) journal.push(journal_row);
                    journal_row = {};
                }
                
                //
                $('#dr-balance').html(number_format(debit_total, 2));
                $('#cr-balance').html(number_format(credit_total, 2));
                $('#balance').html(number_format(balance, 2));
                //
                $('#journal').val(JSON.stringify(journal));
                
                // debit
                if (debit_total <= 0) {
                    $('#dr-balance').removeClass('text-success').addClass('text-danger');
                    disabled = true;
                }
                else {
                    $('#dr-balance').removeClass('text-danger').addClass('text-success');
                }
                
                // credit
                if (credit_total <= 0) {
                    $('#cr-balance').removeClass('text-success').addClass('text-danger');
                    disabled = true;
                }
                else {
                    $('#cr-balance').removeClass('text-danger').addClass('text-success');
                }
                
                // balance
                if (balance != 0) {
                    $('#balance').removeClass('text-success').addClass('text-danger');
                    disabled = true;
                }
                else {
                    $('#balance').removeClass('text-danger').addClass('text-success');
                }
                
                //
                if (saving) disabled = true;
                $('#save-journal').prop({disabled: disabled});
                
            }
            
            checkForm.start();
            
        }, 500, true); //
        
    })
</script>