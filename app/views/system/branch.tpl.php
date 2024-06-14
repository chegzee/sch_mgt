<?php
$data = $data ?? [];
echo $data['menu'];
?>

<style>
    /* .select2{
        width:100%;
    } */
</style>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Branch</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalBranch({table: '#table-branch', row: ''}); $('#modal-title').html('New Branch')"><i class="fa fa-plus"></i> Add</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-branch" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                        <tr>
                            <th><i class="material-icons">build</i></th>
                            <th>Branch</th>
                            <th>Code</th>
                            <th>Alpha</th>
                            <th>Digit</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- branchModal -->
<div id="modal-branch" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Branch New/Edit</h5>
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
    
                        <button onclick="modalBranch({table: '#table-branch', row: ''}); $('#modal-title').html('New Branch')"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-xl-6 col-lg-6 px-3">

                                <div class="form-group row ">
                                    <label for="branch_code" class="col-md-4 col-form-label text-sm-right">Logo <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture" style="width:100%">
                                                </div>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="picture--help">&nbsp;</code>
                                    </div>
                                    <input type="file" id="picture-file" accept="image/*" onchange="imageChange({'event': event, preview:'picture', 'items': [$('#first_name').val(), $('#last_name').val()]})" style="display:none">
                                    <input type="hidden" id="picture" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="branch_code" class="col-md-4 col-form-label text-sm-right">Branch Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="branch_code" maxlength="20">
                                        </div>
                                        <code class="small text-danger" id="branch_code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="branch_code_old" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="branch_name" class="col-md-4 col-form-label text-sm-right">Branch Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="branch_name" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="branch_name--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="alpha" class="col-md-4 col-form-label text-sm-right">Alpha Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="alpha" maxlength="10">
                                        </div>
                                        <code class="small text-danger" id="alpha--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="digit" class="col-md-4 col-form-label text-sm-right">Digit Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="digit" maxlength="10">
                                        </div>
                                        <code class="small text-danger" id="digit--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="address" class="col-md-4 col-form-label text-sm-right">Address <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="address" maxlength="255">
                                        </div>
                                        <code class="small text-danger" id="address--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label text-sm-right">Business Phone <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="tel" id="phone" maxlength="20">
                                        </div>
                                        <code class="small text-danger" id="phone--help">&nbsp;</code>
                                    </div>
                                </div>
                            
                            </div>
    
                            <div class="col-xl-6 col-lg-6 px-3">
        
                                <div class="form-group row">
                                    <label for="contact" class="col-md-4 col-form-label text-sm-right">Contact Person <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="contact" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="contact--help">&nbsp;</code>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-sm-right">Email <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="email" id="email" maxlength="100">
                                        </div>
                                        <code class="small text-danger" id="email--help">&nbsp;</code>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="phone2" class="col-md-4 col-form-label text-sm-right">Contact Phone <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="tel" id="phone2" maxlength="20">
                                        </div>
                                        <code class="small text-danger" id="phone2--help">&nbsp;</code>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="state" class="col-md-4 col-form-label text-sm-right">State <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group w-100">
                                            <select class="form-control form-control-sm" id="state"  style="width:100%"></select>
                                        </div>
                                        <code class="small text-danger" id="state--help">&nbsp;</code>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="country" class="col-md-4 col-form-label text-sm-right">Country <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-sm" id="country" style="width:100%"></select>
                                        </div>
                                        <code class="small text-danger" id="country--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="account_code" class="col-md-4 col-form-label text-sm-right">Bank <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group w-100">
                                            <select class="form-control form-control-sm" id="account_code"  style="width:100%">
                                                <option value="" selected disabled ></option>
                                            </select>
                                        </div>
                                        <code class="small text-danger" id="account_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                            </div>
                        
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-branch" class="" type="button" style="margin-left: auto" onclick="saveBranch({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
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
    let deleteBranch = (json) => {
        //console.log(json);
        let tableBranch = $(json.table).DataTable();
        let data = tableBranch.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Branch: ' + data['branch_code'] + ' : ' + data['branch_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/branch/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableBranch.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalBranch = (json) => {
        let tableBranch = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableBranch.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['branch_code'] === undefined) {
            //
        }
        // console.log(data);
        
        $('#branch_code_old').val(data['branch_code'] ?? '');
        $('#branch_code').val(data['branch_code'] ?? 'AUTO');
        $('#branch_name').val(data['branch_name'] ?? '');
        $('#account_code').append(new Option('('+data['account_number']??''+')' + data['account_name']??''+''+data['bank_name'], data['account_code']??'', true, true)).trigger("change");
        $('#status').prop({checked: data['status'] === '1' || !data['branch_code']});
        //
        $('#alpha').val(data['alpha']);
        $('#digit').val(data['digit']);
        $('#email').val(data['email']);
        $('#phone').val(data['phone']);
        $('#phone2').val(data['phone']);
        $('#address').val(data['address']);
        $('#contact').val(data['contact']);
        $('#state').append(new Option(data['state'] ?? '', data['state'] ?? '', true, true)).trigger('changes');
        $('#country').append(new Option(data['country'] ?? '', data['country'] ?? '', true, true)).trigger('changes');
        // $('#country').val(data['country']);
        
        let pics = data['picture'] ?? '';
        pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
        //
        $('#picture--preview').attr('src', pics);
        //
        $('#picture').val(data['picture'] ?? '');
        
        //
        $('#modal-branch').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let branch_code = '<?php echo $data['params']['branch_code'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (branch_code !== '') {
            
                let tableBranch = $('#table-branch').DataTable();
            
                tableBranch.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === branch_code) {
                            //console.log(v, i);
                            modalBranch({table: '#table-branch', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalBranch({table: '#table-branch', row: ''});
        }
    }


    //
    $('#account_code').select2({
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

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveBranch = (json) => {
        //console.log(json);
        let tableBranch = $(json.table).DataTable();
        
        if ($('#save-branch').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-branch').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('branch', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        // console.log(form_data); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/branch/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-branch').html('<i class="fa fa-spinner fa-spin" style="white"></i> Save Changes');
                $('#save-branch').prop({disabled: true});
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
                $('#save-branch').html('Save Changes');
                $('#save-branch').prop({disabled: false});
                
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
                tableBranch.ajax.reload(null, false);
                //
                $('#branch_code').val(data.data.branch_code);
                $('#branch_code_old').val(data.data.branch_code);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-branch').html('Save Changes');
                $('#save-branch').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
        //
        $('#state').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getStates",
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
        //
        $('#country').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getCountries",
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
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        // $('.select2').css("width", '');
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableBranch = $("#table-branch").DataTable();
    
        let loadBranch = (json) => {
            //console.log("scared");return;
            // dataTables
            let url = "<?php echo URL_ROOT ?>/system/branch/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableBranch.destroy();
        
            tableBranch = $('#table-branch').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "branch_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalBranch({table: \'#table-branch\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteBranch({table: \'#table-branch\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                            ;
                        }
                    },
                    {"data": "branch_name"},
                    {"data": "branch_code"},
                    {"data": "alpha"},
                    {"data": "digit"},
                    {"data": "contact"},
                    {"data": "email"},
                    {"data": "phone"},
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
    
        loadBranch({});
    
        //
        tableBranch.search('', false, true);
        //
        tableBranch.row(this).remove().draw(false);
    
        //
        $('#table-branch tbody').on('click', 'td', function () {
            //
            //let data = tableBranch.row($(this)).data(); // data["colName"]
            let data = tableBranch.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalBranch({table: '#table-branch', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-branch').on('hidden.bs.modal', function () {
            tableBranch.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // branch
            if ($('#modal-branch').hasClass('show')) {
            
                // branch_code
                if ($('#branch_code').val().trim() === '' && $('#branch_code_old').val().trim() !== '') {
                    disabled = true;
                    $('#branch_code--help').html('BRANCH CODE REQUIRED')
                } else {
                    $('#branch_code--help').html('&nbsp;')
                }
            
                // branch_name
                if ($('#branch_name').val().trim() === '') {
                    disabled = true;
                    $('#branch_name--help').html('BRANCH NAME REQUIRED')
                } else {
                    $('#branch_name--help').html('&nbsp;')
                }
                // branch_name
                if ($('#account_code').find(":selected").val().trim() === '') {
                    disabled = true;
                    $('#account_code--help').html('BANK NAME REQUIRED')
                } else {
                    $('#account_code--help').html('&nbsp;')
                }
                
                // alpha
                if ($('#alpha').val().trim() === '') {
                    disabled = true;
                    $('#alpha--help').html('ALPHA CODE REQUIRED')
                } else {
                    $('#alpha--help').html('&nbsp;')
                }
            
                // digit
                if ($('#digit').val().trim() === '') {
                    disabled = true;
                    $('#digit--help').html('DIGIT CODE REQUIRED')
                } else {
                    $('#digit--help').html('&nbsp;')
                }
            
                // contact
                if ($('#contact').val().trim() === '') {
                    disabled = true;
                    $('#contact--help').html('CONTACT REQUIRED')
                } else {
                    $('#contact--help').html('&nbsp;')
                }
            
                // address
                if ($('#address').val().trim() === '') {
                    disabled = true;
                    $('#address--help').html('ADDRESS REQUIRED')
                } else {
                    $('#address--help').html('&nbsp;')
                }
                // state
                if ($('#state').val().trim() === '') {
                    disabled = true;
                    $('#state--help').html('STATE REQUIRED')
                } else {
                    $('#state--help').html('&nbsp;')
                }
                // state
                if ($('#country').val().trim() === '') {
                    disabled = true;
                    $('#country--help').html('COUNTRY REQUIRED')
                } else {
                    $('#country--help').html('&nbsp;')
                }
            
                // email
                if (!regexp_email.test($('#email').val())) {
                    disabled = true;
                    $('#email--help').html('EMAIL INVALID')
                } else {
                    $('#email--help').html('&nbsp;')
                }
            
                // phone
                if (!regexp_phone.test($('#phone').val())) {
                    disabled = true;
                    $('#phone--help').html('BUSINESS PHONE INVALID')
                } else {
                    $('#phone--help').html('&nbsp;')
                }
            
                // phone2
                if ($('#phone2').val() !== '' && !regexp_phone.test($('#phone2').val())) {
                    disabled = true;
                    $('#phone2--help').html('CONTACT PHONE INVALID')
                } else {
                    $('#phone2--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-branch').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



