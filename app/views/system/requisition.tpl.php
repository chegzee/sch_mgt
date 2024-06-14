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
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?<?php echo urldecode(http_build_query($params_)); ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Requisition</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button class="mb-3" onclick="modalRequisition({table: '#table-requisition', row: ''}); $('#modal-title').html('New Requisition')"><i class="fa fa-plus"></i> Add</button>
            <button class="mb-3 dropdown-toggle" type="button" id="dropdownList" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> <?php $list_option = $data['params']['list_option'] ?? ''; echo (int)$list_option === 0 ? 'All' : ($list_option === '1' ? 'Awaiting Authorisation' : ($list_option === '2' ? 'Awaiting Approval' : 'Processed')); ?></button>
            <div class="dropdown-menu" aria-labelledby="dropdownList">
                <a class="dropdown-item" href="?<?php echo urldecode(http_build_query($params_)); ?>">All</a>
                <?php if ($module_access->requisition->authorise === '1') { ?><a class="dropdown-item" href="?<?php echo urldecode(http_build_query($params_)); ?>&list_option=1">Awaiting Authorisation</a><?php } ?>
                <?php if ($module_access->requisition->approve === '1') { ?><a class="dropdown-item" href="?<?php echo urldecode(http_build_query($params_)); ?>&list_option=2">Awaiting Approval</a><?php } ?>
            </div>
    
            <?php if ((int)$list_option === 0) { ?>
            <button type="button" class="btn btn-sm btn-warning mb-3" onclick="if(confirm('Check selected items?')){ processRequisition($('.requisition-check:checked').map(function() { return $(this).val() }).get()) }"><i class="fa fa-check"></i> Check</button>
            <?php } else if ((int)$list_option === 1) { ?>
            <button type="button" class="btn btn-sm btn-danger mb-3" onclick="if(confirm('Authorise selected items?')){ processRequisition($('.requisition-check:checked').map(function() { return $(this).val() }).get()) }"><i class="fa fa-check"></i> Authorise</button>
            <?php } else if ((int)$list_option === 2) { ?>
            <button type="button" class="btn btn-sm btn-success mb-3" onclick="if(confirm('Verify selected items?')){ processRequisition($('.requisition-check:checked').map(function() { return $(this).val() }).get()) }"><i class="fa fa-check"></i> Approve</button>
            <?php } ?>
    
            <?php if ((int)$list_option === 0) { ?> 
            <button type="button" class="btn btn-sm btn-danger float-right mb-3 ml-1" onclick="if(confirm('Reverse selected items?')){ deleteRequisition($('.requisition-check:checked').map(function() { return $(this).val() }).get()) }"><i class="fa fa-trash"></i> Reverse</button>
            <?php } if ((int)$list_option >= 1 && (int)$list_option <= 2) { ?>
            <button type="button" class="btn btn-sm btn-warning float-right mb-3 ml-1" onclick="if(confirm('Return selected items?')){ returnRequisition($('.requisition-check:checked').map(function() { return $(this).val() }).get()) }"><i class="fa fa-times"></i> Return</button>
            <?php } ?>
            
            <div style="min-height: 493px; overflow: auto; padding-right: 15px">
                <table id="table-requisition" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="requisition-check" onclick="$('.requisition-check:not(:disabled)').prop({checked: $(this).prop('checked')})"></th>
                        <th>Requisition #</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Group</th>
                        <th>Department</th>
                        <th>User</th>
                        <th>Beneficiary</th>
                        <th>Status</th>
                        <th>Priority</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- requisitionGroupModal -->
<div id="modal-requisition" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Requisition <span id="status_notify" class="badge small"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Basic Info</a>
                    <a class="nav-item nav-link has-icon non-active d-none" href="#page_2" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Files</a>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane show active" id="page_1">
                        <button onclick="modalRequisition({table: '#table-requisition', row: ''}); $('#modal-title').html('New Requisition')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> Reset</button>

                        <button type="button" class="btn btn-sm btn-warning mb-3 d-none check"><i class="fa fa-check"></i> Check</button>
                        <button type="button" class="btn btn-sm btn-danger mb-3 d-none authorise"><i class="fa fa-check"></i> Authorise</button>
                        <button type="button" class="btn btn-sm btn-success mb-3 d-none verify"><i class="fa fa-check"></i> Verify</button>
                        <button type="button" class="btn btn-sm btn-secondary mb-3 d-none approve"><i class="fa fa-check"></i> Approve</button>
                        <button type="button" class="btn btn-sm btn-primary mb-3 d-none process"><i class="fa fa-check"></i> Process</button>

                        
                        <button type="button" class="btn btn-sm btn-danger float-right mb-3 ml-1 d-none delete"><i class="fa fa-trash"></i> Reverse</button>
                        <button type="button" class="btn btn-sm btn-warning float-right mb-3 ml-1 d-none return"><i class="fa fa-times"></i> Return</button>

                        
                        <div class="row">
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="requisition_code" class="col-md-4 col-form-label text-sm-right">Requisition # <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="requisition_code" maxlength="250">
                                        <code class="small text-danger" id="requisition_code--help">&nbsp;</code>
                                        <input type="hidden" id="requisition_code_old" readonly disabled>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="trans_date" class="col-md-4 col-form-label text-sm-right">Date <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="trans_date" maxlength="20">
                                        <code class="small text-danger" id="trans_date--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="requisition_group" class="col-md-4 col-form-label text-sm-right">Budget Group <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="requisition_group" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach ($data['requisitionGroups'] ?? [] as $k => $v) {
                                                echo '<option value="' . $v->requisition_group . '">' . $v->requisition_group . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="requisition_group--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="amount" class="col-md-4 col-form-label text-sm-right">Amount <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm money" type="text" id="amount" maxlength="20">
                                            <div class="input-group-append">
                                                <select class="form-control form-control-sm" id="currency_code" style="width: 80px"></select>
                                            </div>
                                            <div class="input-group-append">
                                                <input class="form-control form-control-sm decimal" style="width: 70px" type="text" id="currency_rate" maxlength="100" disabled>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="amount--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="priority" class="col-md-4 col-form-label text-sm-right">Priority <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="priority" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach (['Low', 'Medium', 'High'] as $v) {
                                                echo '<option value="' . $v . '">' . $v . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="priority--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="description" class="col-md-4 col-form-label text-sm-right">Description <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="description" maxlength="200">
                                        <code class="small text-danger" id="description--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="beneficiary_name" class="col-md-4 col-form-label text-sm-right">Beneficiary's Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="beneficiary_name" maxlength="200">
                                        <code class="small text-danger" id="beneficiary_name--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="account_bank" class="col-md-4 col-form-label text-sm-right">Beneficiary's Bank <br><span class="small text-info">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="account_bank" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach ($data['allBanks'] as $v) {
                                                echo '<option value="' . $v->bank_name . '">' . $v->bank_name . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="account_bank--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="account_number" class="col-md-4 col-form-label text-sm-right">Beneficiary's Account <br><span class="small text-info">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="account_number" maxlength="20">
                                        <code class="small text-danger" id="account_number--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <style>
                                    #notification {
                                        border-collapse: collapse;
                                    }
                                    #notification th, #notification td {
                                        font-size: 10pt;
                                        padding: 3px 5px;
                                    }
                                </style>
                                <div class="form-group row">
                                    <div class="col-12" style="height: 175px; overflow: auto">
                                        <table id="notification" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>DateTime</th><th>Event</th><th>Username</th><th>Remarks</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="page_2">

                        <button type="button" class="btn btn-sm btn-warning mb-3 d-none check"><i class="fa fa-check"></i> Check</button>
                        <button type="button" class="btn btn-sm btn-danger mb-3 d-none authorise"><i class="fa fa-check"></i> Authorise</button>
                        <button type="button" class="btn btn-sm btn-success mb-3 d-none verify"><i class="fa fa-check"></i> Verify</button>
                        <button type="button" class="btn btn-sm btn-secondary mb-3 d-none approve"><i class="fa fa-check"></i> Approve</button>
                        <button type="button" class="btn btn-sm btn-primary mb-3 d-none process"><i class="fa fa-check"></i> Process</button>
    
                        <button type="button" class="btn btn-sm btn-danger float-right mb-3 ml-1 d-none delete"><i class="fa fa-trash"></i> Reverse</button>
                        <button type="button" class="btn btn-sm btn-warning float-right mb-3 ml-1 d-none return"><i class="fa fa-times"></i> Return</button>

                        
                        <div style="height: 420px; overflow: auto; margin-bottom: 6px">
                            <div style="display: inline-flex">

                                <button class="btn btn-sm btn-outline-secondary mr-2" type="button"><i class="fa fa-recycle" onclick="azureList({directory: 'document/requisition/' + $('#doc_path').val() + '/', doc_list: $('#doc_list'), display_fileName: 'requisition'})"></i></button>
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownDocument" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-clipboard-list"></i> Select Document</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownDocument">
                                    <?php
                                    foreach (UPLOAD_DOCUMENTS['requisition'] ?? [] as $k => $v) {
                                        echo '<a class="dropdown-item" href="javascript:void(0)" onclick="localStorage.setItem(\'doc_path_id\', \'' . $k . '\'); $(\'#doc_path_id\').click()">' . ucfirst($v) . '</a>';
                                    }
                                    ?>
                                    <div class="dropdown-divider"></div>
                                    <?php
                                    foreach ($data['medical'] ?? [] as $k => $v) {
                                        if (strlen($v->medical_name ?? '') <= 10) continue;
                                        echo '<a class="dropdown-item" href="javascript:void(0)" onclick="localStorage.setItem(\'doc_path_id\', \'' . $k . '\'); $(\'#doc_path_id\').click()">[' . $k . '] ' . ucfirst(substr($v->medical_name ?? '', 0, 20)) . '...</a>';
                                    }
                                    ?>
                                </div>

                            </div>

                            <div class="mt-2" id="doc_list">Loading document...</div>
                        </div>

                    </div>

                    
                    <div class="form-group mb-2 d-flex">
                        <button id="save-requisition" class="btn btn-success" type="button" style="margin-left: auto" onclick="saveRequisition({})"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
                <input type="hidden" id="doc_path" disabled>

                <input type="file" id="doc_path_id" accept="<?php echo ACCEPT_FILE_TYPE ?>" onchange="azureUpload({doc_path: localStorage.getItem('doc_path_id'), directory: 'document/requisition/' + $('#doc_path').val().replace(/[^a-zA-Z0-9\-]/g, '-') + '/', item: '#doc_path_id', newName: localStorage.getItem('doc_path_id'), callback: 'azureList', params: {directory: 'document/requisition/' + $('#doc_path').val().replace(/[^a-zA-Z0-9\-]/g, '-') + '/', doc_list: $('#doc_list'), display_fileName: 'requisition'}, actionButton: '.upload-button, .refresh-button'})" style="display:none">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script type="text/javascript" src="<?php echo ASSETS_ROOT ?>/js/nigeria.js"></script>

<script>
    
    const module_access = <?php echo json_encode($module_access); ?>;

    let list_option = <?php echo json_encode($data['params']['list_option'] ?? ''); ?>;
    //
    const requisitionStatuses = <?php echo json_encode($data['requisitionStatuses']); ?>;
    //
    const requisitionActivities = <?php echo json_encode($data['requisitionActivities']); ?>;
    

    // currencyObj
    const currencyObj = <?php echo json_encode($data['currencyObj']) ?>;
    
    let modalRequisition = (json) => {
        // console.log(json);return
        //
        modalLoadingDiv = $("#modal-requisition");
        modalLoading({status: 'show'});
        // console.log(list_option)
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        $('.check, .authorise, .verify, .approve, .process, .return, .delete').addClass('d-none');
        //
        localStorage.clear();
        $('#duration').empty();
        //
        $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getRequisition/?<?php echo urldecode(http_build_query($params_)) ?>', { requisition_code: json.requisition_code, list_option: getInt(list_option ?? ''), username: '<?php echo $data['user']['username'] ?>', user_group: '<?php echo $data['user']['group_code'] ?>' }, function(data) {
            // console.log(data);
            if (!data) data = [];
            //
            if (data['requisition_code'] === undefined) {
                //
            }
            // console.log(data);
    
            if (data['requisition_code'] !== undefined) {
                $('#modalNav a[href="#page_2"]').removeClass('d-none');
            }
            
            // button
            let status = getInt(data['status'] ?? '');
            // console.log(status);
            switch (status) {
                case 0:
                    if ((data['requisition_code'] ?? '') !== '') $('.check, .delete').removeClass('d-none');
                    break;
                case 1:
                    if (getInt(list_option ?? '') === 1) $('.authorise, .return').removeClass('d-none');
                    break;
                // case 2:
                //     if (getInt(list_option ?? '') === 2) $('.verify, .return').removeClass('d-none');
                //     break;
                case 2:
                    if (getInt(list_option ?? '') === 2) $('.approve, .return').removeClass('d-none');
                    break;
                // case 4:
                //     if (getInt(list_option ?? '') === 4) $('.process, .return').removeClass('d-none');
                //     break;
            }
            //
            $('.delete').on('click', () => {
                if (confirm('Reverse selected items?')) { deleteRequisition([data['requisition_code']]) }
            });
            //
            $('.return').on('click', () => {
                if (confirm('Reject selected items?')) { returnRequisition([data['requisition_code']]) }
            });
            // console.log(data)
            //
            $('.check, .authorise, .verify, .approve, .process').on('click', () => {
                let process;
                switch (getInt(data['status'] ?? '')) {
                    case 0:
                        process = 'Check';
                        break;
                    case 1:
                        process = 'Authorise';
                        break;
                    case 2:
                        process = 'Verify';
                        break;
                    case 3:
                        process = 'Approve';
                        break;
                    case 4:
                        process = 'Process';
                        break;
                }
                if (confirm(process + ' selected items?')) { processRequisition([data['requisition_code']]) }
            });
            //
            
            $('#doc_list--load, #dropdownDoc').css({display: (status > 0 ? 'none' : 'inline-block')});
            // console.log(data['priority'])
            //
            $('#requisition_code_old').val(data['requisition_code'] ?? '');
            $('#requisition_code').val(data['requisition_code'] ?? 'AUTO').prop({disabled: status > 0});
            $('#trans_date').val((data['trans_date'] ?? '').substring(0, 10)).prop({disabled: status > 0}).css({'background-color': status > 0 ? '#e9ecef' : ''});
            //
            data['requisition_group'] = data['requisition_group'] ?? '';
            $('#requisition_group').val(data['requisition_group']).trigger('change').prop({disabled: status > 0});
            //
            data['priority'] = data['priority'] ?? '';
            // console.log(data['priority']);return
            $('#priority').append(new Option(data['priority'] ?? '', data['priority'] ?? '', true, true)).trigger('change').prop({disabled: status > 0});
            //
            data['currency_code'] = data['currency_code'] ?? '<?php echo BASE_CURRENCY ?>';
            $('#currency_code').val(data['currency_code']).trigger('change').prop({disabled: status > 0});
            $('#currency_rate').val(data['currency_rate'] ?? '<?php echo BASE_RATE ?>').prop({disabled: status > 0});
            //
            $('#amount').val(number_format(data['amount'] ?? '')).prop({disabled: status > 0});
            $('#description').val(data['description'] ?? '').prop({disabled: status > 0});
            $('#beneficiary_name').val(data['beneficiary_name'] ?? '').prop({disabled: status > 0});
            $('#account_bank').val(data['account_bank'] ?? '').trigger('change').prop({disabled: status > 0});
            $('#account_number').val(data['account_number'] ?? '').prop({disabled: status > 0});
            //
            let notification = '[' + (data['notification'] ?? '').replace(/^,+|,+$/g, '') + ']';
            // console.log(notification)
            try { notification = JSON.parse(notification) } catch (e) { notification = [] }
            $('#notification tbody tr').remove();
            notification.forEach((v) => {
                if (v['dateTime'] === undefined || v['event'] === undefined) return true;
                $('#notification tbody').append('<tr>\
                    <td>' + (v['dateTime'] ?? '').substring(0, 16) + '</td>\
                    <td>' + v['event'] + '</td>\
                    <td>' + (v['username'] ?? '').substring(0, (v['username'] ?? '').indexOf('@')) + '</td>\
                    <td>' + v['reason'] + '</td>\
                </tr>');
            });

            //
            let doc_path_update = false;
            if (!(new RegExp(/[0-9a-zA-Z\-]{36}/g).test(data['doc_path'] ?? ''))) {
                data['doc_path'] = uuid();
                doc_path_update = true;
            }
            $('#doc_path').val(data['doc_path']);

            
          // azureList({directory: 'document/requisition/' + data['doc_path'] + '/', doc_list: $('#doc_list'), display_fileName: 'requisition'});

            
            // if (doc_path_update) {
            //     // Update doc_path
            //     let formData = new FormData;
            //     formData.append('table', 'act_requisition')
            //     formData.append('doc_path', data['doc_path'])
            //     formData.append('field', 'requisition_code')
            //     formData.append('value', data['requisition_code'])

            //     fetch ('<?php echo URL_ROOT ?>/system/systemSetting/postDocument/?<?php echo urldecode(http_build_query($params_)) ?>', {method: 'POST', body: formData});
            // }
            
            //
            modalLoading({status: 'hide'});
            
            //
            initInputFormat();
    
        }, 'JSON');
        
        //
        $('#modal-requisition').modal('show');
        //
        // $('#modalNav a[href="#page_1"]').tab('show');
    }
    //
    let deleteRequisition = (json) => {
        // console.log(json); return;
        let tableRequisition = $('#table-requisition').DataTable();
        
        if (json.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/requisition/_delete/?<?php echo urldecode(http_build_query($params_)); ?>', {requisition_code: json}, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            if ($('#modal-requisition').hasClass('show')) $('#modal-requisition').modal('hide');
            else tableRequisition.ajax.reload(null, false);
            //
            
            
        }, 'JSON');
    }
    
    //
    let returnRequisition = (json) => {
        // console.log(json); return;
        let tableRequisition = $('#table-requisition').DataTable();
        
        if (json.length === 0) {
            return false;
        }
        
        let returnReason = prompt('Kindly specify reason for rejection: ');
        if ((returnReason ?? '') === '') {
            alert('Reason for Rejection required!')
            return;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/requisition/_return/?<?php echo urldecode(http_build_query($params_)); ?>', {requisition_code: json, return_reason: returnReason}, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            if ($('#modal-requisition').hasClass('show')) $('#modal-requisition').modal('hide');
            else tableRequisition.ajax.reload(null, false);
    
        }, 'JSON');
    }
    
    //
    let processRequisition = (json) => {
        // console.log(json);return;
        
        let tableRequisition = $('#table-requisition').DataTable();
        
        if (json.length === 0) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/requisition/_process/?<?php echo urldecode(http_build_query($params_)); ?>', {requisition_code: json, list_option: getInt(list_option ?? ''), username: '<?php echo $data['user']['username'] ?>', user_group: '<?php echo $data['user']['group_code'] ?>'}, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>' + data.data.message, timeout: 10000}).show();
            //
            if (data.error.status) {
                new Noty({type: 'warning', text: '<h5>Warning</h5>' + data.error.message, timeout: 10000}).show();
            }
            //
            if ($('#modal-requisition').hasClass('show')) $('#modal-requisition').modal('hide');
            else tableRequisition.ajax.reload(null, false);
    
        }, 'JSON');
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let requisition_code = '<?php echo $data['params']['requisition_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        
        if (hash !== '' && modalOpen) {
            localStorage.clear();
            //
            modalRequisition({requisition_code: requisition_code});
        }
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveRequisition = (json) => {
        //console.log(json);
        let tableRequisition = $(json.table).DataTable();
        
        if ($('#save-requisition').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-requisition').find('input, select, textarea'), function (i, obj) {
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
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/requisition/_save/?<?php echo urldecode(http_build_query($params_)); ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-requisition').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-requisition').prop({disabled: true});
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
                $('#save-requisition').html('<i class="fa fa-save"></i> Save Changes');
                $('#save-requisition').prop({disabled: false});
    
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                //
                localStorage.clear();
                // modalRequisition({requisition_code: data.data.requisition_code});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-requisition').html('<i class="fa fa-save"></i> Save Changes');
                $('#save-requisition').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            if ($(this).hasClass('ucase')) $(this).val($(this).val().trim().toUpperCase());
        });
        
        
        $('#requisition_group').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        //
        $('#priority').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        //
        $('#account_bank').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
        //
        $('#currency_code').select2({
            placeholder: "Select an option",
            // allowClear: true,
            data: Object.values(currencyObj),
        });
        $('#currency_code').on('select2:select', () => $('#currency_rate').val(currencyObj[$('#currency_code').val()]['currency_rate']) );
        
        flatpickr('#trans_date', {
            dateFormat: 'Y-m-d',
            disableMobile: true,
            // allowInput: true,
            defaultDate: moment().format('YYYY-MM-DD'),
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableRequisition = $("#table-requisition").DataTable();
        
        let loadRequisition = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/system/requisition/_list/?<?php echo urldecode(http_build_query($params_)); ?>";
            
            tableRequisition.destroy();
            
            tableRequisition = $('#table-requisition').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {
                        list_option: list_option,
                        user_group: '<?php echo $data['user']['group_code'] ?>',
                    },
                },
                "columns": [
                    {
                        "data": "requisition_code", "width": "30px", "render": function (data, type, row, meta) {
                            return '<input type="checkbox" class="requisition-check" id="requisition-check-' + data + '" value="' + data + '" ' + (getInt(row['status'] ?? '') > 0 && getInt(list_option ?? '') === 0 ? 'disabled' : '') + '></div>';
                        }
                    },
                    {"data": "requisition_code"},
                    {"data": "trans_date", "render": function (data, type, row, meta) {
                            return (data ?? '').substring(0, 10)
                        }},
                    {"data": "amount", "className": "text-right", "render": function (data, type, row, meta) {
                            return currencyObj[row['currency_code']]['html_code'] + number_format(data ?? '')
                        }},
                    {"data": "requisition_group"},
                    {"data": "group_name"},
                    {"data": "fullname"},
                    {"data": "beneficiary_name"},
                    {"data": "status", "render": function (data, type, row, meta) {
                        let status = getInt(data ?? '');
                            return '<div class="d-inline rounded-sm py-1 px-2 text-white bg-' + (status === 0 ? 'info' : status === 1 ? 'warning' : status === 2 ? 'danger' : status === 3 ? 'success' : status === 4 ? 'secondary' : 'primary') + '">' + requisitionStatuses[status] + '</div>'
                        }},
                    {"data": "priority"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "desc"]],
                "lengthMenu": [10, 20],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    $('.dataTables_filter input[type="search"]').removeClass("form-control form-control-sm");
                    let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableRequisition.search(this.previousElementSibling.value).draw() });
                    $("#table-requisition_filter.dataTables_filter input")
                        .unbind()
                        .bind("input, keyup", function(e) {
                            if( (e.charCode || e.keyCode || e.which) === 13) tableRequisition.search(this.value).draw();
                            e.preventDefault();
                        }).prop({placeholder: 'Press [Enter] Key'})
                        .after(searchButton).prop({autocomplete: 'off'});
                    //
                    modalAuto();
                }
            });
        }
        
        loadRequisition({});
        
        //
        tableRequisition.search('', false, true);
        //
        // tableRequisition.row(this).remove().draw(false);
        
        //
        $('#table-requisition tbody').on('click', 'td', function () {
            //
            let data = tableRequisition.row($(this)).data();
            // console.log(data)
            let rowId = $(this).parent('tr').index();
            // console.log("row clicked : " + rowId)
            
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex !== 0) {
                //
                modalRequisition({requisition_code: data['requisition_code']});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
        
        // /////////////////////////////////////////////////////////////////////////////////////////
        
        $('#modal-requisition').on('hidden.bs.modal', function () {
            tableRequisition.ajax.reload(null, false);
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
            if ($('#modal-requisition').hasClass('show')) {
    
                // access
    
                //
                let modalNav = {
                    '#page_1': false,
                };
                
                // requisition_code
                if ($('#requisition_code').val().trim() === '' && $('#requisition_code_old').val().trim() !== '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#requisition_code--help').html('REQUISITION # REQUIRED')
                } else {
                    $('#requisition_code--help').html('&nbsp;')
                }

                // requisition_group
                if (($('#requisition_group').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#requisition_group--help').html('REQUISITION GROUP REQUIRED')
                } else {
                    $('#requisition_group--help').html('&nbsp;')
                }

                // priority
                if (($('#priority').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#priority--help').html('PRIORITY REQUIRED')
                } else {
                    $('#priority--help').html('&nbsp;')
                }
    
                // trans_date
                if (!moment($('#trans_date').val()).isValid()) {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#trans_date--help').html('TRANSACTION DATE REQUIRED')
                } else {
                    $('#trans_date--help').html('&nbsp;')
                }
    
                // amount
                if (getFloat($('#amount').val() ?? '0') <= 0) {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#amount--help').html('AMOUNT REQUIRED')
                } else if (($('#currency_code').val() ?? '') === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#amount--help').html('CURRENCY REQUIRED')
                } else if (getFloat($('#currency_rate').val() ?? '0') <= 0) {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#amount--help').html('EXCHANGE RATE REQUIRED')
                } else {
                    $('#amount--help').html('&nbsp;')
                }
    
                // description
                if ($('#description').val().trim() === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#description--help').html('DESCRIPTION REQUIRED')
                } else {
                    $('#description--help').html('&nbsp;')
                }

                // description
                if ($('#beneficiary_name').val().trim() === '') {
                    disabled = true;
                    if (!(modalNav['#page_1'] ?? false)) modalNav['#page_1'] = true;
                    $('#beneficiary_name--help').html('BENEFICIARY REQUIRED')
                } else {
                    $('#beneficiary_name--help').html('&nbsp;')
                }
                
                // list_option
                if ($('#requisition_code').prop('disabled')) {
                    disabled = true;
                }
    
                //
                $.each(modalNav, function(k, v) {
                    let item = $('#modalNav').find('a[href="' + k + '"]');
                    if (v) item.addClass('bg-danger-faded');
                    else item.removeClass('bg-danger-faded');
                });
                
                //
                if (saving) disabled = true;
                $('#save-requisition').prop({disabled: disabled});
                
            }
            
            checkForm.start();
            
        }, 500, true); 
    });

</script>



