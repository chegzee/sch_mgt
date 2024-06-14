<?php
$data = $data ?? [];
echo $data['menu'];

//
$module_access = $data['user']['access'];
//
$params_ = $data['params'] ?? [];
// var_dump($params_);exit;
unset($params_['url']);
unset($params_['list_option']);
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?<?php echo $params_ ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Requisition Budget</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">

            <div class="d-inline-flex">
                <button class="mb-3" href="javascript:void(0)" onclick="modalRequisitionBudget({table: '#table-requisitionbudget', row: ''}); $('#modal-title').html('New RequisitionBudget')"><i class="fa fa-plus"></i> Add</button>
                <div class="ml-1">
                    <button class="mb-3 dropdown-toggle" id="dropdownAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i> Selected</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownAction">
                        <a class="dropdown-item text-danger" href="#" onclick="processRequisition({func: 'reverse', requisition_budget: $('input.requisition-list:checked').map(function() { return $(this).val() }).get()})">Reverse</a>
                    </div></div>
            </div>
            
            <div style="min-height: 493px; overflow: auto; padding-right: 15px">
                <table id="table-requisitionbudget" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><div class="custom-control custom-control-nolabel custom-checkbox"><input type="checkbox" class="custom-control-input" id="requisition-list" onclick="$('.requisition-list:not(:disabled)').prop({checked: $(this).prop('checked')})"><label class="custom-control-label" for="requisition-list"></label></div></th>
                        <th>Budget Code</th>
                        <th>Department</th>
                        <th>Year</th>
                        <th>CAPEX</th>
                        <th>OPEX</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- requisitionGroupModal -->
<div id="modal-requisitionbudget" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">RequisitionBudget </h5>
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
    
                        <button onclick="modalRequisitionBudget({table: '#table-requisitionbudget', row: ''}); $('#modal-title').html('New RequisitionBudget')"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="requisition_budget" class="col-md-4 col-form-label text-sm-right">Budget Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm ucase" type="text" id="requisition_budget" maxlength="250">
                                        <code class="small text-danger" id="requisition_budget--help">&nbsp;</code>
                                        <input type="hidden" id="requisition_budget_old" readonly disabled>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="user_group" class="col-md-4 col-form-label text-sm-right">Department <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="user_group" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach ($data['userGroups'] ?? [] as $k => $v) {
                                                echo '<option value="' . $v->group_code . '">' . $v->group_name . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="user_group--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="year" class="col-md-4 col-form-label text-sm-right">Year <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="year" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach (range(date('Y') + 1, 2000, -1) as $v) {
                                                echo '<option value="' . $v . '">' . $v . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="year--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="capex" class="col-md-4 col-form-label text-sm-right">CAPEX <br><span class="small text-info">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm money" type="text" id="capex" maxlength="20">
                                        <code class="small text-danger" id="capex--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="opex" class="col-md-4 col-form-label text-sm-right">OPEX <br><span class="small text-info">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm money" type="text" id="opex" maxlength="20">
                                        <code class="small text-danger" id="opex--help">&nbsp;</code>
                                    </div>
                                </div>

                            </div>
                        
                        </div>
                    
                    </div>
    
                    <div class="form-group mb-2 d-flex">
                        <button id="save-requisitionbudget" type="button" style="margin-left: auto" onclick="saveRequisitionBudget({})"><i class="fa fa-save"></i> Save Changes</button>
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
    
    let typeObj;

    //
    let processRequisition = (json) => {
        // console.log(json); return;
        if (!confirm('Do you want to perform this operation?') || json.requisition_budget.length === 0) return;

        $('#dropdownAction').html('<i class="fa fa-spinner fa-spin"></i> Selected').prop({disabled: true});

        let formData = new FormData;
        formData.append('func', json.func);
        formData.append('requisition_budget', JSON.stringify(json.requisition_budget));
        fetch ('<?php echo URL_ROOT ?>/finance/requisitionBudget/_process/?<?php echo urldecode(http_build_query($params_)); ?>', {method: 'POST', body: formData})
            .then(response => response.json())
            .then(result => {
                console.log(result);

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
                if ($('#modal-requisitionbudget').hasClass('show')) {
                    if (result.data.error === '') modalRequisitionBudget({requisition_budget: json.requisition_budget[0]});
                } else {
                    $("#table-requisitionbudget").DataTable().ajax.reload(null, false);
                }
            })
    }
    
    let modalRequisitionBudget = (json) => {
        // console.log(json.requisition_budget);return
        //
        modalLoadingDiv = $('#modal-requisitionbudget');
        modalLoading({status: 'show'});
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        $('.authorise-link, .verify-link, .return-link, .cancel-link, .restore-link').css({display: 'none'});
        //
        localStorage.clear();
        $('#duration').empty();
        //
        $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getRequisitionBudget/?<?php echo urldecode(http_build_query($params_)) ?>', { requisition_budget: json.requisition_budget }, function(data) {
            // console.log(data);
            if (!data) data = [];
            //
            if (data['requisition_budget'] === undefined) {
                //
            }
            // console.log(data);
    
            //
            $('#requisition_budget_old').val(data['requisition_budget'] ?? '');
            $('#requisition_budget').val(data['requisition_budget'] ?? 'AUTO').prop({disabled: (data['requisition_budget'] ?? '') !== ''});
            //
            data['user_group'] = data['user_group'] ?? '';
            $('#user_group').val(data['user_group']).trigger('change').prop({disabled: (data['requisition_budget'] ?? '') !== ''});
            //
            data['year'] = data['year'] ?? '';
            $('#year').val(data['year']).trigger('change').prop({disabled: (data['requisition_budget'] ?? '') !== ''});
            //
            $('#capex').val(number_format(data['capex'] ?? ''));
            $('#opex').val(number_format(data['opex'] ?? ''));
            
            //
            modalLoading({status: 'hide'});
            
            //
            initInputFormat();
    
        }, 'JSON');
        
        //
        $('#modal-requisitionbudget').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let requisition_budget = '<?php echo $data['params']['requisition_budget'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        
        if (hash !== '' && modalOpen) {
            localStorage.clear();
            //
            modalRequisitionBudget({requisition_budget: requisition_budget});
        }
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveRequisitionBudget = (json) => {
        //console.log(json);
        let tableRequisitionBudget = $(json.table).DataTable();
        
        if ($('#save-requisitionbudget').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-requisitionbudget').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('requisition', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        // console.log(form_data); return;
        
        // process the form
        form_data.set("capex", form_data.get("capex").replaceAll(",", ''))
        form_data.set("opex", form_data.get("opex").replaceAll(",", ''))
        // console.log(form_data);return;
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/requisitionBudget/_save/?<?php echo urldecode(http_build_query($params_)); ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-requisitionbudget').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-requisitionbudget').prop({disabled: true});
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
                $('#save-requisitionbudget').html('<i class="fa fa-save"></i> Save Changes');
                $('#save-requisitionbudget').prop({disabled: false});
    
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                //
                localStorage.clear();
                modalRequisitionBudget({requisition_budget: data.data.requisition_budget});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-requisitionbudget').html('<i class="fa fa-save"></i> Save Changes');
                $('#save-requisitionbudget').prop({disabled: false});
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
        $('#user_group').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        //
        $('#year').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableRequisitionBudget = $("#table-requisitionbudget").DataTable();
        
        let loadRequisitionBudget = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/requisitionBudget/_list/?<?php echo urldecode(http_build_query($params_)); ?>";
            
            tableRequisitionBudget.destroy();
            
            tableRequisitionBudget = $('#table-requisitionbudget').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "requisition_budget", "width": "30px", "render": function (data, type, row, meta) {
                            return '<input type="checkbox" class="requisition-list" id="requisition-list-' + meta['row'] + '" value="' + row['auto_id'] + '">';
                        }
                    },
                    {"data": "requisition_budget"},
                    {"data": "group_name"},
                    {"data": "year"},
                    {"data": "capex", "render": function (data, type, row, meta) {
                            return number_format(data ?? '')
                        }},
                    {"data": "opex", "render": function (data, type, row, meta) {
                            return number_format(data ?? '')
                        }},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "asc"]],
                "lengthMenu": [10, 20],
                "initComplete": function (settings, json) {
                    //console.log(json);
                    let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableRequisitionBudget.search(this.previousElementSibling.value).draw() });
                    $("#table-requisitionbudget_filter.dataTables_filter input")
                        .unbind()
                        .bind("input, keyup", function(e) {
                            if( (e.charCode || e.keyCode || e.which) === 13) tableRequisitionBudget.search(this.value).draw();
                            e.preventDefault();
                        }).prop({placeholder: 'Press [Enter] Key'})
                        .after(searchButton).prop({autocomplete: 'off'});
                    //
                    modalAuto();
                }
            });
        }
        
        loadRequisitionBudget({});
        
        //
        tableRequisitionBudget.search('', false, true);
        //
        // tableRequisitionBudget.row(this).remove().draw(false);
        
        //
        $('#table-requisitionbudget tbody').on('click', 'td', function () {
            //
            let data = tableRequisitionBudget.row($(this)).data();
            // console.log(data)
            let rowId = $(this).parent('tr').index();
            // console.log("row clicked : " + rowId)
            
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex !== 0) {
                //
                modalRequisitionBudget({requisition_budget: data['requisition_budget']});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        $('#modal-requisitionbudget').on('hidden.bs.modal', function () {
            tableRequisitionBudget.ajax.reload(null, false);
        });
        
        // ////////////////////////////////////////////////////////////////////////////////////////
        
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
            
            // requisition
            if ($('#modal-requisitionbudget').hasClass('show')) {
    
                // access
                // if (module_access['finance']['requisition']['setup'] < 2) disabled = true;
    
                //
                let modalNav = {
                    '#page_1': false,
                };
                
                // requisition_budget
                if ($('#requisition_budget').val().trim() === '' && $('#requisition_budget_old').val().trim() !== '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#requisition_budget--help').html('BUDGET CODE REQUIRED')
                } else {
                    $('#requisition_budget--help').html('&nbsp;')
                }

                // user_group
                if (($('#user_group').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#user_group--help').html('DEPARTMENT REQUIRED')
                } else {
                    $('#user_group--help').html('&nbsp;')
                }

                // year
                if (($('#year').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#year--help').html('YEAR REQUIRED')
                } else {
                    $('#year--help').html('&nbsp;')
                }
    
                //
                $.each(modalNav, function(k, v) {
                    let item = $('#modalNav').find('a[href="' + k + '"]');
                    if (v) item.addClass('bg-danger-faded');
                    else item.removeClass('bg-danger-faded');
                });
                
                //
                if (saving) disabled = true;
                $('#save-requisitionbudget').prop({disabled: disabled});
                
            }
            
            checkForm.start();
            
        }, 500, true); 
    });

</script>



