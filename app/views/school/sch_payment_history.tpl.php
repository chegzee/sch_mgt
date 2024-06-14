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
            <button onclick="modalTeacher({table: '#table-std', row: ''}); $('#modal-title').html('New Teacher')"><i class="fa fa-plus"></i> New</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-tchPaymentHistoryModal" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>Roll</th>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- studentrModal -->
<div id="modal-teacher" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Teacher New/Edit</h5>
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
                        
                        <div class="row">
                            
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="first_name">First name </label>
                                <input type="text" class="form-control form-control-lg" id="first_name" style="width: 100%"/>
                                <code class="small text-danger" id="first_name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="last_name">Last name </label>
                                    <input type="text" class="form-control form-control-lg" id="last_name" style="width: 100%"/>
                                <code class="small text-danger" id="last_name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="gender">Gender </label>
                                <select class="form-control form-control-lg" id="gender" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="gender--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="birthday">Date of Birth<span class="small text-primary"> (Option)</span> </label>
                                    <input class="form-control form-control-lg" id="birthday" style="width: 100%"/>
                                <code class="small text-danger" id="birthday--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="blood_group">Blood Group <span class="small text-primary"> (Option)</span></label>
                                    <select class="form-control form-control-lg" id="blood_group" style="width: 100%">
                                    </select>
                                <code class="small text-danger" id="blood_group--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="religion">Religion <span class="small text-primary"> (Optional)</span></label>
                                 <select class="form-control form-control-lg" id="religion" style="width: 100%">
                                </select>
                                <code class="small text-danger" id="religion--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="email">Email<span class="small text-primary"> (Optional)</span> </label>
                                <input type="email" class="form-control form-control-lg" id="email" style="width: 100%"/>
                                <code class="small text-danger" id="email--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="phone">Phone <span class="small text-primary"> (Optional)</span></label>
                                <input type="text" class="form-control form-control-lg" id="phone" style="width: 100%"/>
                                <code class="small text-danger" id="phone--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="address">Address </label>
                                <input class="form-control form-control-lg" id="address" type="text" style="width: 100%"/>
                                <code class="small text-danger" id="address--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                <label for="state">State </label>
                                <select class="form-control form-control-lg" id="state" type="text" style="width: 100%"></select>
                                <code class="small text-danger" id="state--help">&nbsp;</code>
                            </div>
                            <div class="col-lg-6 col-12 form-group">
                                <label>Short BIO<span class="small text-primary"> (Option)</span></label>
                                <textarea class="textarea form-control" name="message" id="short_bio" cols="10" rows="9"></textarea>
                            </div>
                            <div class=" col-lg-6 px-3 mt-4" style="position: relative;">
                                <div class="form-group row">
                                    <!-- <label style="position: absolute;top:0px;left:8px;"> <br><span class="small text-warning">Click to upload picture</span></label> -->
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture" style="height: auto; width: 100%; color: #9999ff">
                                                </div>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="picture--help">&nbsp;</code>
                                    </div>
                                    <input type="file" id="picture-file" accept="image/*" onchange="imageChange({'event': event, 'preview':'picture', 'items': [$('#first_name').val(), $('#last_name').val()]}); modalLoadingDiv = $('#modal-teacher'); googleDriveUpload({doc_path: 'picture', directory: 'users/', item: '#picture-file', newName: $('#first_name').val().toLowerCase()+ '-' + $('#last_name').val().toLowerCase().slice(0, ($('#first_name').val() + '@').indexOf('@')) + '-picture','items': [$('#first_name').val(), $('#last_name').val()], unique: false })" style="display:none">
                                    <input type="hidden" id="picture" readonly>
                                </div>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalTeacher({table: '#table-std', row: ''}); $('#modal-title').html('New Student')"><i class="fa fa-plus"></i> Reset</button>
                                <button id="save-teacher" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveTeacher({})"><i class="mdi mdi-file-download"></i> Save </button>
                            </div>
                            <input id="identity_no" style="display:none"/>
                            <input id="identity_no_old" style="display:none"/>
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
    let delete_ = (json) => {
        //console.log(json);
        let tableTeacher = $(json.table).DataTable();
        let data = tableTeacher.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Teacher: ' + data['identity_no'] + ' : ' + data['first_name'] + " " + data['last_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/teachers/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableTeacher.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let tchPaymentHistoryModal = (json) => { 
        let tableTeacher = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableTeacher.row(json.row).data(); // data["colName"]
        // console.log(data)
        $('#modalNav').find('a.non-active').addClass('d-none');
        
        $('#identity_no').val(data['identity_no']);
        $('#identity_no_old').val(data['identity_no']);
        $('#first_name').val(data['first_name'] ?? '');
        $('#last_name').val(data['last_name']);
        $('#gender').append(new Option(data['gender'] ?? "" , data['gender'] ?? "", true, true)).trigger('change');
        $('#birthday').val(data['birthday']);
        $('#blood_group').append(new Option(data['blood_group'] ?? '', data['blood_group'] ?? '', true, true)).trigger('change');
        $('#religion').append(new Option(data['religion'] ?? "", data['religion'] ?? "" , true, true)).trigger('change');
        $('#email').val(data['email']);
        $('#phone').val(data['phone']);
        $('#address').val(data['address']);
        $('#state').val(data['state']);
        $('#short_bio').val(data['short_bio']);
        let pics = data['picture'] ?? '';
        pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
        //
        $('#picture--preview').attr('src', pics);
        //
        $('#picture').val(data['picture'] ?? '');
        //
        $('#modal-teacher').modal('show');
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
    let save_ = (json) => {
        // console.log(json);return;
        let tableTeacher = $(json.table).DataTable();
        
        if ($('#save-teacher').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        //
        $.each($('#modal-teacher #page_1').find('input, select, textarea'), function (i, obj) {
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
        // Display the values
        // for (var value of form_data.values()) {
        //     console.log(value);
        // }
        //    console.log(form_data); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/teachers/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-teacher').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-teacher').prop({disabled: true});
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
                $('#save-teacher').html('Save Changes');
                $('#save-teacher').prop({disabled: false});
                
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
                tableTeacher.ajax.reload(null, false);
                //
                $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-teacher').html('Save Changes');
                $('#save-teacher').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        

        // $('#gender').select2({
        //     placeholder: "please select option",
        //     data: [{id: "MALE", text: "MALE"}, {id: "FEMALE", text: "FEMALE"}]}
        // )
        $('#religion').select2({
                placeholder: "Please select an option",
                allowClear: true,
                ajax: {
                    url: "<?php echo URL_ROOT ?>/system/systemSetting/getReligions/?user_log=<?php echo $data['params']['user_log'] ?>",
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
            }
        )
        $('#blood_group').select2({
            placeholder: "please select option",
            data: [
                {id: "A+", text: "A+"},
                 {id: "A-", text: "A-"}, 
                 {id:"B+", text: "B+"},
                  {id: "B-", text: "B-"},
                  {id: "O+", text: "O+"},
                  {id: "O-", text: "O-"},
                  {id: "AB-", text: "AB-"},
                  {id: "AB+", text: "AB+"}
                
                ]}
        )
        //
        flatpickr('#birthday', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });
        
        $('#gender').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getGenders/?user_log=<?php echo $data['params']['user_log'] ?>",
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
        $('#state').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getStates/?user_log=<?php echo $data['params']['user_log'] ?>",
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
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tchPaymentHistory = $("#table-teacher").DataTable();
    
        let loadTchPaymentHistory = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/school/tchPaymentHistory/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
            tchPaymentHistory.destroy();
        
            tchPaymentHistory = $('#table-teacher').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "identity_no", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="tchPaymentHistoryModal({table: \'#table-tchPaymentHistoryModal\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="delete_({table: \'#table-tchPaymentHistory\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                        }
                    },
                    {"data": "roll"},
                    {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                        return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                     }},
                    {"data": "name"},
                    {"data": "gender"},
                    {"data": "class"},
                    {"data": "subject"},
                    {"data": "amount"},
                    {"data": "status"},
                    {"data": "phone"},
                    {"data": "email"}
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                   // $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  console.log(json);
                    //  modalAuto();
                }
            });
        }
    
        loadTchPaymentHistory({});
    
        //
        tableTeacher.search('', false, true);
        //
        tableTeacher.row(this).remove().draw(false);
    
        //
        $('#table-teacher tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableTeacher.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalTeacher({table: '#table-teacher', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-teacher').on('hidden.bs.modal', function () {
            tableTeacher.ajax.reload(null, false);
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
            if ($('#modal-teacher').hasClass('show')) {
            
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
    
                // address
                if ($('#address').val().trim() === '') {
                    disabled = true;
                    $('#address--help').html('ADDRESS REQUIRED')
                } else {
                    $('#address--help').html('&nbsp;')
                }
                // gender
                if ($('#gender').val().trim() === '') {
                    disabled = true;
                    $('#gender--help').html('GENDER REQUIRED')
                } else {
                    $('#gender--help').html('&nbsp;')
                }
            
                
                // access-*
                // $.each(user_access, function (i, v) {
                //     let items = $('#')
                // });
                
                //
                if (saving) disabled = true;
                $('#save-teacher').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true);
    });

</script>