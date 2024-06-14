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
            <li class="breadcrumb-item active" aria-current="page">Requisition Group</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">

            <div class="d-inline-flex">
                <button class="mb-3" href="javascript:void(0)" onclick="modalRequisitionGroup({table: '#table-requisitiongroup', row: ''}); $('#modal-title').html('New Requisition Group')"><i class="fa fa-plus"></i> Add</button>
                <div class="ml-1">
                    <button class="mb-3 dropdown-toggle" type="button" id="dropdownAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i> Selected</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownAction">
                        <a class="dropdown-item text-danger" href="#" onclick="processRequisition({func: 'reverse', requisition_group: $('input.requisition-list:checked').map(function() { return $(this).val() }).get()})">Reverse</a>
                    </div>
                </div>
            </div>
            
            <div style="min-height: 493px; overflow: auto; padding-right: 15px">
                <table id="table-requisitiongroup" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><div class="custom-control custom-control-nolabel custom-checkbox"><input type="checkbox" class="custom-control-input" id="requisition-list" onclick="$('.requisition-list:not(:disabled)').prop({checked: $(this).prop('checked')})"><label class="custom-control-label" for="requisition-list"></label></div></th>
                        <th>Requisition Group</th>
                        <th>Budget Type</th>
                        <th>Debit Ledger</th>
                        <th>Credit Ledger</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- requisitiongroupModal -->
<div id="modal-requisitiongroup" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Requisition Group <?php if ($module_access->finance->requisition->setup < 2) echo '<span class="badge badge-success small">Readonly</span>'; ?> <span id="status_notify" class="badge small"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Basic Info</a>
                </nav>
                <div class="tab-content">
                    
                    <div class="tab-pane show active" id="page_1">
    
                        <button onclick="modalRequisitionGroup({table: '#table-requisitiongroup', row: ''}); $('#modal-title').html('New Requisition Group')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="requisition_group" class="col-md-4 col-form-label text-sm-right">Requisition Group <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm ucase" type="text" id="requisition_group" maxlength="250">
                                        <code class="small text-danger" id="requisition_group--help">&nbsp;</code>
                                        <input type="hidden" id="requisition_group_old" readonly disabled>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="budget_type" class="col-md-4 col-form-label text-sm-right">Budget Type <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="budget_type" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach ($data['budgetTypes'] ?? [] as $k => $v) {
                                                echo '<option value="' . $v . '">' . $v . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="budget_type--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="debit_account" class="col-md-4 col-form-label text-sm-right">Expense | DR <br><span class="small text-warning">Giver Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm account_code" id="debit_account" style="width: 100%"></select>
                                        <code class="small text-danger" id="debit_account--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="credit_account" class="col-md-4 col-form-label text-sm-right">Liability | CR <br><span class="small text-warning">Giver Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm account_code" id="credit_account" style="width: 100%"></select>
                                        <code class="small text-danger" id="credit_account--help">&nbsp;</code>
                                    </div>
                                </div>

                            </div>
                        
                        </div>
                    
                    </div>
    
                    <div class="form-group mb-2 d-flex">
                        <button id="save-requisitiongroup" class="btn btn-success" type="button" style="margin-left: auto" onclick="saveRequisitionGroup({})"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                
                </div>
                
                <input type="hidden" id="doc_path" disabled>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script type="text/javascript" src="<?php echo ASSETS_ROOT ?>/js/nigeria.js"></script>

<script>
    
    const module_access = <?php echo json_encode($module_access); ?>;

    let list_option = <?php echo json_encode($data['params']['list_option'] ?? ''); ?>;
    
    // accountObj
    const accountObj = <?php echo json_encode($data['accountObj']) ?>;
    
    let typeObj;

    //
    let processRequisition = (json) => {
        // console.log(json); return;
        if (!confirm('Do you want to perform this operation?') || json.requisition_group.length === 0) return;

        $('#dropdownAction').html('<i class="fa fa-spinner fa-spin"></i> Selected').prop({disabled: true});

        let formData = new FormData;
        formData.append('func', json.func);
        formData.append('requisition_group', JSON.stringify(json.requisition_group));
        fetch ('<?php echo URL_ROOT ?>/finance/requisitionGroup/_process/?<?php echo urldecode(http_build_query($params_)); ?>', {method: 'POST', body: formData})
            .then(response => response.json())
            .then(result => {
                // console.log(result);

                $('#dropdownAction').html('Selected').prop({disabled: false});

                //
                if (!result.status) {
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + result.message, timeout: 10000}).show();
                    return false;
                }
                //
                if (result.data.success !== '') new Noty({type: 'success', text: '<h5>Success</h5>' + result.data.success, timeout: 10000}).show();
                if (result.data.error !== '') new Noty({type: 'warning', text: '<h5>Warning</h5>' + result.data.error, timeout: 10000}).show();
                //
                if ($('#modal-requisitiongroup').hasClass('show')) {
                    if (result.data.error === '') modalRequisitionGroup({requisition_group: json.requisition_group[0]});
                } else {
                    $("#table-requisitiongroup").DataTable().ajax.reload(null, false);
                }
            })
    }
    
    let modalRequisitionGroup = (json) => {
        // console.log(json);
        //
        modalLoadingDiv = $('#modal-requisitiongroup');
        modalLoading({status: 'show'});
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        $('.authorise-link, .verify-link, .return-link, .cancel-link, .restore-link').css({display: 'none'});
        //
        localStorage.clear();
        $('#duration').empty();
        //
        $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getRequisitionGroup/?<?php echo urldecode(http_build_query($params_)) ?>', { requisition_group: json.requisition_group }, function(data) {
            // console.log(data);
            if (!data) data = [];
            //
            if (data['requisition_group'] === undefined) {
                //
            }
            // console.log(data);
    
            //
            $('#requisition_group_old').val(data['requisition_group'] ?? '');
            $('#requisition_group').val(data['requisition_group'] ?? '').prop({disabled: (data['requisition_group'] ?? '') !== ''});
            //
            data['budget_type'] = data['budget_type'] ?? '';
            $('#budget_type').val(data['budget_type']).trigger('change');
    
            data['debit_account'] = data['debit_account'] ?? '';
            data['credit_account'] = data['credit_account'] ?? '';
            data['debit_account_name'] = '';
            data['credit_account_name'] = '';
            try {
                data['debit_account_name'] = accountObj[data['debit_account']]['account_name'];
                data['credit_account_name'] = accountObj[data['credit_account']]['account_name'];
            } catch(e) {}
    
            $('#debit_account').append(new Option('(' + data['debit_account'] + ') ' + data['debit_account_name'], data['debit_account'], true, true)).trigger('change');
            $('#credit_account').append(new Option('(' + data['credit_account'] + ') ' + data['credit_account_name'], data['credit_account'], true, true)).trigger('change');
            
            //
            modalLoading({status: 'hide'});
            
            //
            initInputFormat();
    
        }, 'JSON');
        
        //
        $('#modal-requisitiongroup').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let requisition_group = '<?php echo $data['params']['requisition_group'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        
        if (hash !== '' && modalOpen) {
            localStorage.clear();
            //
            modalRequisitionGroup({requisition_group: requisition_group});
        }
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveRequisitionGroup = (json) => {
        //console.log(json);
        let tableRequisitionGroup = $(json.table).DataTable();
        
        if ($('#save-requisitiongroup').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-requisitiongroup').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('requisition_group', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        
        // Display the values
        // for (var value of form_data.values()) {
        //     console.log(value);
        // }
        // console.log($('#modal-requisitiongroup').find('input, select, textarea').length); return;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/requisitionGroup/_save/?<?php echo urldecode(http_build_query($params_)); ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-requisitiongroup').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-requisitiongroup').prop({disabled: true});
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
                $('#save-requisitiongroup').html('<i class="fa fa-save"></i> Save Changes');
                $('#save-requisitiongroup').prop({disabled: false});
    
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                //
                localStorage.clear();
                modalRequisitionGroup({requisition_group: data.data.requisition_group});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-requisitiongroup').html('<i class="fa fa-save"></i> Save Changes');
                $('#save-requisitiongroup').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            if ($(this).hasClass('ucase')) $(this).val($(this).val().trim().toUpperCase());
        });
        
        //
        $('#budget_type').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        //
        $('.account_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/finance/accountSetting/getLedgerAccounts/?<?php echo urldecode(http_build_query($params_)); ?>",
                type: "post",
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        _option: 'select-group'
                    };
                },
                processResults: function (response) {
                    console.log(response);
                    return {results: response};
                },
                cache: true
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableRequisitionGroup = $("#table-requisitiongroup").DataTable();
        
        let loadRequisitionGroup = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/requisitionGroup/_list/?<?php echo urldecode(http_build_query($params_)); ?>";
            
            tableRequisitionGroup.destroy();
            
            tableRequisitionGroup = $('#table-requisitiongroup').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "requisition_group", "width": "30px", "render": function (data, type, row, meta) {
                            return '<input type="checkbox" class="requisition-list" id="requisition-list-' + meta['row'] + '" value="' + row['auto_id'] + '">';
                        }
                    },
                    {"data": "requisition_group"},
                    {"data": "budget_type"},
                    {"data": "debit_account"},
                    {"data": "credit_account"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "asc"]],
                "lengthMenu": [10, 20],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    $('.dataTables_filter input[type="search"]').removeClass('form-control form-control-sm')
                    let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableRequisitionGroup.search(this.previousElementSibling.value).draw() });
                    $("#table-requisitiongroup_filter.dataTables_filter input")
                        .unbind()
                        .bind("input, keyup", function(e) {
                            if( (e.charCode || e.keyCode || e.which) === 13) tableRequisitionGroup.search(this.value).draw();
                            e.preventDefault();
                        }).prop({placeholder: 'Press [Enter] Key'})
                        .after(searchButton).prop({autocomplete: 'off'});
                    //
                    modalAuto();
                }
            });
        }
        
        loadRequisitionGroup({});
        
        //
        tableRequisitionGroup.search('', false, true);
        //
        // tableRequisitionGroup.row(this).remove().draw(false);
        
        //
        $('#table-requisitiongroup tbody').on('click', 'td', function () {
            //
            let data = tableRequisitionGroup.row($(this)).data();
            // console.log(data)
            let rowId = $(this).parent('tr').index();
            // console.log("row clicked : " + rowId)
            
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex !== 0) {
                //
                modalRequisitionGroup({requisition_group: data['requisition_group']});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        $('#modal-requisitiongroup').on('hidden.bs.modal', function () {
            tableRequisitionGroup.ajax.reload(null, false);
        });
        
        // ////////////////////////////////////////////////////////////////////////////////////////
        
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
            
            // requisition_group
            if ($('#modal-requisitiongroup').hasClass('show')) {
    
                // access
                if (module_access['finance']['requisition_setup'] !== '1') disabled = true;
    
                //
                let modalNav = {
                    '#page_1': false,
                };
                
                // requisition_group
                if ($('#requisition_group').val().trim() === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#requisition_group--help').html('REQUISITION GROUP REQUIRED')
                } else {
                    $('#requisition_group--help').html('&nbsp;')
                }
    
                // budget_type
                if (($('#budget_type').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#budget_type--help').html('BUDGET TYPE REQUIRED')
                } else {
                    $('#budget_type--help').html('&nbsp;')
                }
    
                // debit_account
                if (($('#debit_account').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#debit_account--help').html('DEBIT LEDGER ACCOUNT REQUIRED')
                } else {
                    $('#debit_account--help').html('&nbsp;')
                }
    
                // credit_account
                if (($('#credit_account').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#credit_account--help').html('CREDIT LEDGER ACCOUNT REQUIRED')
                } else {
                    $('#credit_account--help').html('&nbsp;')
                }
    
                //
                $.each(modalNav, function(k, v) {
                    let item = $('#modalNav').find('a[href="' + k + '"]');
                    if (v) item.addClass('bg-danger-faded');
                    else item.removeClass('bg-danger-faded');
                });
                
                //
                if (saving) disabled = true;
                $('#save-requisitiongroup').prop({disabled: disabled});
                
            }
            
            checkForm.start();
            
        }, 500, true);
    });

</script>



