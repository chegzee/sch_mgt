<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalUser({table: '#table-user', row: ''}); $('#modal-title').html('New User')"><i class="fa fa-plus"></i> Add</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-user" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Permission</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- userModal -->
<div id="modal-user" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">User New/Edit</h5>
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
    
                        <button onclick="modalUser({table: '#table-user', row: ''}); $('#modal-title').html('New User')"><i class="fa fa-plus"></i> Reset</button>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3 mt-4">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-sm-right"> <br><span class="small text-warning"></span></label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; border: 1px solid; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture" style="height: auto; width: 100%; color: #9999ff">
                                                </div>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="picture--help">&nbsp;</code>
                                    </div>
                                    <input type="file" id="picture-file" accept="image/*" onchange="imageChange({'event': event, preview:'picture', 'items': [$('#first_name').val(), $('#last_name').val()]})" style="display:none">
                                    <input type="hidden" id="picture" readonly>
                                </div>
                            </div>
    
                            <div class="col-lg-6 px-3 mt-4">
                                <div class="form-group row">
                                    <label for="group_code" class="col-md-4 col-form-label text-sm-right">Department <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-lg" id="group_code" style="width: 100%"></select>
                                        </div>
                                        <code class="small text-danger" id="group_code--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row" id="std_user_div" style="display:none;">
                                    <label for="group_code" class="col-md-4 col-form-label text-sm-right">Students <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control form-control-lg" id="students_users" style="width: 100%;">
                                                <option disabled selected>Choose a student</optio>
                                            </select>
                                        </div>
                                        <code class="small text-danger" id="students_users--help">&nbsp;</code>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="username" class="col-md-4 col-form-label text-sm-right">Username/Email <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="email" id="username" maxlength="100">
                                        </div>
                                        <code class="small text-danger" id="username--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="username_old" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-sm-right">Password <a href="javascript:void(0)" data-container="body" data-toggle="popover" data-placement="top" data-title="Password complexity" data-content="<?php echo PASSWORD_COMPLEXITY ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <br><span class="small text-warning">Optional</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input type="password" id="password" class="form-control form-control-sm" maxlength="50">
                                            <div class="input-group-append">
                                                <button id="password-generate" type="button" class="btn btn-faded-dark btn-sm" onclick="passwordGenerate({elem: '#password'})"><i class="material-icons">vpn_key</i></button>
                                                <button type="button" class="btn btn-faded-dark btn-sm" style="border-left: 1px solid #cccccc" onclick="$('#password').prop('type') == 'password' ? $('#password').prop({'type':'text'}) : $('#password').prop({'type':'password'})"><i class="material-icons">remove_red_eye</i></button>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="password--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="password1" readonly>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="first_name" class="col-md-4 col-form-label text-sm-right">First Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="first_name" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="first_name--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="last_name" class="col-md-4 col-form-label text-sm-right">Last Name <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="last_name" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="last_name--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="address" class="col-md-4 col-form-label text-sm-right">Address <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="address" maxlength="200">
                                        </div>
                                        <code class="small text-danger" id="address--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label text-sm-right">Phone <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="phone" maxlength="15">
                                        </div>
                                        <code class="small text-danger" id="phone--help">&nbsp;</code>
                                    </div>
                                </div>

                            </div>
    
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="access" class="col-md-4 col-form-label text-sm-right">Permission <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <table class="table table-bordered">
                                                <?php
                                                if (!empty(USER_ACCESS)) foreach (USER_ACCESS as $k => $v) {
                                                    echo '
                                                    <tr>
                                                        <td style="width: 30px; background-color: #eeeeff"><input id="' . $k . '-_main" data-id="' . $k . '" data-id2="_main" type="checkbox" class="access"></td>
                                                        <td><label style="font-size: 90%; cursor: pointer; color: #9999ff" onclick=" $(\'.access_div\').css({display: \'none\'}); $(this).next(\'div.access_div\').css({display: \'block\'}) ">' . ucfirst($k) . ' <i class="fa fa-sort"></i> </label>
                                                            <div class="access_div" style="display: none">';
                                                                if (!empty($v)) foreach ($v as $k_ => $v_) { 
                                                                    if ($k_ === '_main') continue;
                                                                    
                                                                    echo '<div style="float: left; padding: 1px 8px; margin-right: 5px"><input id="' . $k . '-' . $k_ . '" data-id="' . $k . '" data-id2="' . $k_ . '" type="checkbox" class="access" style="margin-right: 5px;"><label for="' . $k . '-' . $k_ . '" style="font-size: 90%; cursor: pointer; color: #9999ff">' . ucfirst($k_) . '</label></div>';
                                                                }
                                                            echo '</div>
                                                        </td>
                                                    </tr>';
                                                }
                                                ?>
                                            </table>
                                        </div>
                                        <code class="small text-danger" id="access--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="access" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-user" class="" type="button" style="margin-left: auto" onclick="saveUser({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
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
    let students_users = <?php echo json_encode($data['student_user']) ?>;
    // console.log(students_users);
    
    let user_access = <?php echo json_encode(USER_ACCESS) ?>;
    
    //
    let deleteUser = (json) => {
        //console.log(json);
        let tableUser = $(json.table).DataTable();
        let data = tableUser.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete User: ' + data['username'] + ' : ' + data['first_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/user/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableUser.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalUser = (json) => {
        let tableUser = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableUser.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['username'] === undefined) {
            //
        }
        // console.log(data);
        
        $('#username_old').val(data['username'] ?? '');
        $('#username').val(data['username'] ?? '');
        $('#status').prop({checked: data['status'] === '1' || !data['username']});
        $('#first_name').val(data['first_name']);
        $('#last_name').val(data['last_name']);
        $('#password').val(data['password'] ?? passwordGenerate({elem: '#password'}));
        $('#password1').val(data['password']);
        //
        $('#address').val(data['address']);
        $('#phone').val(data['phone']);
        //
        data['group_code'] = data['group_code'] ?? '';
        $('#group_code').append(new Option(data['group_name'], data['group_code'], true, true)).trigger('change');
        
        //
        $('#picture--preview').attr('src', data['picture'] ?? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png');
        //
        $('#picture').val(data['picture'] ?? '');
        
        //
        $('input[type=checkbox].access').prop({checked: false});
        let access = JSON.parse(data['access'] ?? '<?php echo json_encode(USER_ACCESS) ?>');
        // console.log(access)
        if (typeof access == "object") {
            $.each(access, function (i, v) {
                if (typeof v == "object") {
                    $.each(v, function (i_, v_) {
                        $('#' + i + '-' + i_).prop({checked: access[i][i_] === '1'});
                    });
                }
            });
        }
        
        //
        $('#modal-user').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let username = '<?php echo $data['params']['username'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (username !== '') {
            
                let tableUser = $('#table-user').DataTable();
            
                tableUser.columns(1).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === username) {
                            //console.log(v, i);
                            modalUser({table: '#table-user', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalUser({table: '#table-user', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveUser = (json) => {
        // console.log(json);return;
        let tableUser = $(json.table).DataTable();
        
        if ($('#save-user').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        //
        $.each($('#modal-user #page_1').find('input, select, textarea'), function (i, obj) {
            //
            // console.log(obj);return;
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                // form_data.append(obj['id'].replace('user', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/user/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-user').html('<i class="fa fa-spinner fa-spin" style="color:white"></i> Save Changes');
                $('#save-user').prop({disabled: true});
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
                $('#save-user').html('Save Changes');
                $('#save-user').prop({disabled: false});
                
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
                tableUser.ajax.reload(null, false);
                //
                $('#username').val(data.data.username);
                $('#username_old').val(data.data.username);
                $('#password').val(data.data.password).prop({type: 'password'});
                $('#password1').val(data.data.password);
                //
                $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-user').html('Save Changes');
                $('#save-user').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
        //
        $('#group_code').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getUsergroups/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        }).on("select2:select", (e)=>{
            let group_name = e.target.selectedOptions[0].innerHTML
            if(group_name === "STUDENTS"){
                $("#std_user_div").css("display", "")
                $("#students_users").select2({
                    data: students_users
                }).on("select2:select", (v)=>{
                    let std_name = String(v.target.selectedOptions[0].innerHTML).split("-");
                    let std_email = v.target.selectedOptions[0].value;
                    $("#username").val(std_email)
                    $("#first_name").val(std_name[0])
                    $("#last_name").val(std_name[1])
                    console.log(std_name, std_email)

                })
            }else{
                $("#std_user_div").css("display", "none")
                $("#username").val('')
                $("#first_name").val('')
                $("#last_name").val('')

            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableUser = $("#table-user").DataTable();
    
        let loadUser = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/system/user/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableUser.destroy();
        
            tableUser = $('#table-user').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "username", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalUser({table: \'#table-user\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteUser({table: \'#table-user\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                            ;
                        }
                    },
                    {"data": "username"},
                    {"data": "first_name"},
                    {"data": "last_name"},
                    // {"data": "phone"},
                    {"data": "group_name"},
                    {"data": "access", "render": function (data, type, row, meta) {
                        let json_ = {};
                        try { json_ = JSON.parse(data); } catch (e) {}
                        //  console.log(json_);
                        let html_ = '';
                        $.each(user_access, function (i, v) {
                            if (json_ === null || json_ === undefined) return true;
                            if (json_[i] === null || json_[i] === undefined) return false;
                            html_ += '<div style="float: left; padding: 2px 10px; margin-right: 5px; border: 1px solid #cccccc; border-radius: 5px; background-color: ' + (json_[i]['_main'] === '1' ? '#b4eec0' : '#ffd062') + '; font-size: 85%">' + i + '</div>';
                        });
                        return html_;
                    }},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    //  modalAuto();
                }
            });
        }
    
        loadUser({});
    
        //
        tableUser.search('', false, true);
        //
        tableUser.row(this).remove().draw(false);
    
        //
        $('#table-user tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableUser.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalUser({table: '#table-user', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-user').on('hidden.bs.modal', function () {
            tableUser.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // user
            if ($('#modal-user').hasClass('show')) {
            
                // username
                if (!regexp_email.test($('#username').val())) {
                    disabled = true;
                    $('#username--help').html('USERNAME/EMAIL REQUIRED')
                } else {
                    $('#username--help').html('&nbsp;')
                }
    
                // password
                if (!regexp_password.test($('#password').val()) && $('#password').val() !== $('#password1').val()) {
                    disabled = true;
                    $('#password--help').html('PASSWORD TOO WEAK')
                } else {
                    $('#password--help').html('&nbsp;')
                }
            
                // first_name
                if ($('#first_name').val().trim() === '') {
                    disabled = true;
                    $('#first_name--help').html('FIRST NAME REQUIRED')
                } else {
                    $('#first_name--help').html('&nbsp;')
                }
            
                // last_name
                if ($('#last_name').val().trim() === '') {
                    disabled = true;
                    $('#last_name--help').html('LAST NAME REQUIRED')
                } else {
                    $('#last_name--help').html('&nbsp;')
                }
    
                // phone
                if (!regexp_phone.test($('#phone').val())) {
                    disabled = true;
                    $('#phone--help').html('TELEPHONE INVALID')
                } else {
                    $('#phone--help').html('&nbsp;')
                }
    
                // address
                if ($('#address').val().trim() === '') {
                    disabled = true;
                    $('#address--help').html('ADDRESS REQUIRED')
                } else {
                    $('#address--help').html('&nbsp;')
                }
            
                // group_code
                if ($('#group_code').val() === null || $('#group_code').val() === '') {
                    disabled = true;
                    $('#group_code--help').html('GROUP REQUIRED')
                } else {
                    $('#group_code--help').html('&nbsp;')
                }
                
                // access-*
                // $.each(user_access, function (i, v) {
                //     let items = $('#')
                // });
                
                $('input[type=checkbox].access').each(function() {
                    let item = $(this);
                    let id = item.data('id');
                    let id2 = item.data('id2');
                    if (user_access[id] === undefined)
                        user_access[id] = {}
                    user_access[id][id2] = item.prop('checked') ? '1' : '';
                });

                $('#access').val(JSON.stringify(user_access));
                
                //
                if (saving) disabled = true;
                $('#save-user').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



