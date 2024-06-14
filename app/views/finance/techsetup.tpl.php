<?php
$data = $data ?? [];
echo $data['menu'];
// var_dump($data['techsetupObj']);exit;
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Account Mapping</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Page One</a>
            </nav>
            <div class="tab-content">
                
                <div class="tab-pane show active" id="page_1">
                    
                    <a href="javascript:void(0)" onclick="parent.location.assign('<?php echo URL_ROOT ?>/finance/techsetup/?user_log=<?php echo $data['params']['user_log'] ?>')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> Reset</a>
                    
                    <div class="row">
                        
                        <style>
                            .select2-results__option, .select2-selection__rendered {
                                font-size: 8pt;
                            }
                            
                            #select2-branch_code-container, #select2-branch_copy-container, #select2-branch_code-results li, #select2-branch_copy-results li {
                                font-size: 12pt;
                            }
                        </style>
                    
                        <div class="col-lg-6 px-3">
                            
                            <div class="form-group row">
                                <label for="branch_code" class="col-md-4 col-form-label text-sm-right">Branch Location <br><span class="small text-warning">Required</span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <select class="form-control form-control-sm" id="branch_code" style="width: 100%" onchange="loadTechsetup({branch_code: '#branch_code'})"></select>
                                    </div>
                                    <code class="small text-danger" id="branch_code--help">&nbsp;</code>
                                </div>
                            </div>
                            <div class="text-center mb-1 mt-4"><strong>Account mapping</strong></div>';
                            <?php  foreach ($data['techsetupObj'] as $k_){
                                echo '
                                <div class="form-group row">
                                    <label id="' . $k_ . '--label" class="col-md-4 col-form-label text-sm-right">' . $k_ . ' </label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div style="width: calc(50%); padding-right: 10px">
                                                <select class="account_code" id="' . $k_ . '--dr" style="width: 100%"></select>
                                            </div>
                                            <div style="width: calc(50%); padding-left: 10px">
                                                <select class="account_code" id="' . $k_ . '--cr" style="width: 100%"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ';
                            } ?>;
                            <div class="form-group mb-2 d-flex">
                                <button id="save-techsetup" type="button" style="margin-left: auto" onclick="saveTechsetup({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                            
                        </div>

                        
                        <div class="col-lg-6 px-3">
                            
                            <div class="form-group row">
                                <label for="branch_copy" class="col-md-4 col-form-label text-sm-right">
                                    <button class="btn btn-sm btn-outline-primary" onclick="loadTechsetup({branch_code: '#branch_copy'})"><i class="fa fa-arrow-left"></i> Copy From Branch</button>
                                    <br><span class="small text-warning"> </span></label>
                                <div class="col-md-8 pr-3">
                                    <div class="input-group">
                                        <select class="form-control form-control-sm" id="branch_copy" style="width: 100%"></select>
                                    </div>
                                    <code class="small text-danger" id="branch_copy--help">&nbsp;</code>
                                </div>
                            </div>
                        
                        </div>
                        
                    </div>
                
                </div>
            </div>
        
        </div>
    </div>

</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    // console.log(userAccess);
    
    let techsetupObj = <?php echo json_encode($data['techsetupObj']) ?>;
    
    let accountObj = <?php echo json_encode($data['accountObj']) ?>;
    // console.log(techsetupObj)
    
    let loadTechsetup = (json) => {
        let branch_code = $(json.branch_code).val();

        if (branch_code === '' || branch_code === null) {
            new Noty({type: 'warning', text: '<h5>WARNING!</h5> Copy branch not selected', timeout: 10000}).show();
            
            return false;
        }

        // console.log(branch_code);return;
        $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getTechsetup', {_option: 'branch_code', branch_code: branch_code}, function (data) {
            // console.log(data);
            
            reset(data);
            
        }, 'JSON');
    }
    
    let reset = (json) => {
        // console.log(json);return
        $.each($('[id$="--dr"]'), function(i, v) {
            $(this).val('').trigger('change');
        });
        $.each($('[id$="--cr"]'), function(i, v) {
            $(this).val('').trigger('change');
        });
    
        $.each(json, function (i, v) {
            let account_code = '#'  + v.item;
            $(account_code + '--dr').append(new Option(accountObj[v.debit_account]['account_name'], v.debit_account, true, true)).trigger('change');
            $(account_code + '--cr').append(new Option(accountObj[v.credit_account]['account_name'], v.credit_account, true, true)).trigger('change');
        });
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveTechsetup = (json) => {
        //console.log(json);
        let tableTechsetup = $(json.table).DataTable();
        
        if ($('#save-techsetup').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#page_1').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('techsetup', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        // console.log(form_data);
        //  return;
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/techsetup/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-techsetup').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-techsetup').prop({disabled: true});
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
                $('#save-techsetup').html('Save Changes');
                $('#save-techsetup').prop({disabled: false});
                
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
                setTimeout(() => {
                   parent.location.assign('<?php echo URL_ROOT ?>/finance/techsetup/?user_log=<?php echo $data['params']['user_log'] ?>');
                }, 1000);
    
                // loadTechsetup({});
                // $("html, body").animate({ scrollTop: 0 });
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-techsetup').html('Save Changes');
                $('#save-techsetup').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
        $('#branch_code').select2({
            placeholder: "Select an option",
            // allowClear: true,
        });
        

        
        // //
        $('#branch_code, #branch_copy').select2({
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

        loadTechsetup({});
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
        
        // ////////////////////////////////////////////////////////////////////////////////////////
        
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
                // gender
                if (($('#branch_code')["0"].selectedOptions["0"] ?? '') === '') {
                    disabled = true;
                    $('#branch_code--help').html('BRANCH REQUIRED')
                } else {
                    $('#branch_code--help').html('&nbsp;')
                }
            
            //
            if (saving) disabled = true;
            $('#save-techsetup').prop({disabled: disabled});
            
            checkForm.start();
            
        }, 500, true); 
    });

</script>



