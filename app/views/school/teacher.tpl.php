<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">teacher</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card height-auto">
            <div class="card-body">
                <div class="heading-layout1">
                    <div class="item-title">
                        <h3>Add New Teacher</h3>
                    </div>
                </div>
                <form class="new-added-form" id="teacher_form">
                    <div class="row">
                        
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="title">Title </label>
                            <select type="text" class="form-control form-control-lg" id="title" style="width: 100%"></select>
                            <code class="small text-danger" id="title--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="identity_no">ID No </label>
                            <input type="text" class="form-control form-control-lg" id="identity_no" style="width: 100%"/>
                            <code class="small text-danger" id="identity_no--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="last_name">Last name </label>
                            <input type="text" class="form-control form-control-lg" id="last_name" style="width: 100%"/>
                            <code class="small text-danger" id="last_name--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="gender">Gender </label>
                            <select class="form-control form-control-lg" id="gender" style="width: 100%"></select>
                            <code class="small text-danger" id="gender--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="first_name">First name </label>
                            <input type="text" class="form-control form-control-lg" id="first_name" style="width: 100%"/>
                            <code class="small text-danger" id="first_name--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="birthday">Date of Birth </label>
                            <input class="form-control form-control-lg" id="birthday" style="width: 100%"/>
                            <code class="small text-danger" id="birthday--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="blood_group">Blood Group <span class="small text-primary"> (Option)</span></label>
                            <select class="form-control form-control-lg" id="blood_group" style="width: 100%"></select>
                            <code class="small text-danger" id="blood_group--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="religion">Religion <span class="small text-primary"> (Option)</span></label>
                            <select class="form-control form-control-lg" id="religion" style="width: 100%"></select>
                            <code class="small text-danger" id="religion--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="email">Email<span class="small text-primary"> (Option)</span> </label>
                            <input type="email" class="form-control form-control-lg" id="email" style="width: 100%"/>
                            <code class="small text-danger" id="email--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="phone">Phone <span class="small text-primary"> (Option)</span></label>
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
                            <select class="form-control form-control-lg" id="state" type="text" style="width: 100%">
                            </select>
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
                                                <img id="picture--preview" src="<?php echo ASSETS_ROOT ?>/images/gallery/man.png" alt="[Click] to Upload Picture" style="height: auto; width: 100%; color: #9999ff">
                                            </div>
                                        </div>
                                    </div>
                                    <code class="small text-danger" id="picture--help">&nbsp;</code>
                                </div>
                                <input type="file" id="picture-file" accept="image/*" onchange="imageChange({'event': event, preview:'picture', 'items': [$('#first_name').val(), $('#last_name').val()]})" style="display:none">
                                <input type="hidden" id="picture" readonly>
                            </div>
                        </div> 
                        <div class="col-12 form-group mg-t-8">
                            <button type="button" class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalTeacher({})"><i class="fa fa-refresh"></i> Reset</button>
                            <button id="save-teacher" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveTeacher({})">Save </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>

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
    let saveTeacher = (json) => {
        //  console.log(json);return;
        // let tableUser = $(json.table).DataTable();
                
        
        if ($('#save-teacher').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        //
        $.each($('#teacher_form').find('input, select, textarea'), function (i, obj) {
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
        //  console.log(form_data);return;
        
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
                $('#save-teacher').html('<i class="fa fa-spinner fa-spin"></i> Saving Changes');
                $('#save-teacher').prop({disabled: true});
                //
                saving = true;
            }
        })
        // using the done promise callback
        .done(function (data, textStatus, jqXHR) {
            //
            saving = false;
            //
            $('#save-teacher').html('<i class="fa fa-save"></i> Save');
            $('#save-teacher').prop({disabled: false});
            
            if (!data.status) {
                //
                new Noty({type: 'warning', text: '<h5>WARNING!</h5>' + data.message, timeout: 10000}).show();
                return false;
                //
            }
            //
            new Noty({type: 'success', text: '<h5>SUCCESSFUL</h5>', timeout: 10000}).show();
            //
            
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
    let modalTeacher = (json) => { 
        //
        $('#first_name').val('');
        $('#last_name').val('');
        $('#gender').append(new Option('', '', true, true)).trigger('change');
        $('#religion').append(new Option('', '', true, true)).trigger('change');
        $('#state').append(new Option('', '', true, true)).trigger('change');
        $('#blood_group').append(new Option('', '', true, true)).trigger('change');
        $('#birthday').val('');
        $('#roll').val('');
        $('#email').val('');
        $('#identity_no').val('AUTO');
        $('#phone').val('');
        $('#parent_name').val('');
        $('#address').val('');
        //
        // $('#picture--preview').attr('src', data['picture'] ?? '' === '' ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture']);
        // //
        // $('#picture').val(data['picture'] ?? '');
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        //
        $('#identity_no').val('AUTO');

        // $('#section').append(new Option('', '', true, true)).trigger('change')
        $('#gender').append(new Option('', '', true, true)).trigger('change')
        $('#religion').append(new Option('', '', true, true)).trigger('change')
        $('#blood_group').append(new Option('', '', true, true)).trigger('change')
        $('#class_name').append(new Option('', '', true, true)).trigger('change')
        
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
        
        // $('#religion').select2({
        //     placeholder: "please Select",
        //     data: [{id: "CHRISTAIN", text: "CHRISTAIN"}, 
        //     {id: "ISLAM", text: "ISLAM"}, {id: "HINDU", text: "HINDU"}, 
        //     {id: "BUDDISH", text: "BUDDISH"},{id:"OTHERS", text: "OTHERS"}]}
        // )
        ////
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
        });

        
        $('#state').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getstates/?user_log=<?php echo $data['params']['user_log'] ?>",
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

        $('#blood_group').select2({
            placeholder: "please select",
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
        
        $('#title').select2({
                placeholder: "Please select an option",
                allowClear: true,
                ajax: {
                    url: "<?php echo URL_ROOT ?>/system/systemSetting/getTitles",
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

        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // user
                // if ($('#class_name').val().trim() === '') {
                //     disabled = true;
                //     $('#class_name--help').html('CLASS NAME REQUIRED');
                // } else {
                //     $('#class_name--help').html('&nbsp;')
                // }
            
                // first_name
                if ($('#identity_no').val().trim() === '') {
                    disabled = true;
                    $('#identity_no--help').html('VALUE REQUIRED')
                } else {
                    $('#identity_no--help').html('&nbsp;')
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
    
                // address
                if ($('#address').val().trim() === '') {
                    disabled = true;
                    $('#address--help').html('ADDRESS REQUIRED')
                } else {
                    $('#address--help').html('&nbsp;')
                }
                // birthday
                if ($('#birthday').val().trim() === '') {
                    disabled = true;
                    $('#birthday--help').html('BIRTHDAY REQUIRED')
                } else {
                    $('#birthday--help').html('&nbsp;')
                }
                // gender
                if ($('#gender').val().trim() === '') {
                    disabled = true;
                    $('#gender--help').html('GENDER REQUIRED')
                } else {
                    $('#gender--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-teacher').prop({disabled: disabled});
            // }
        
            checkForm.start();
        
        }, 500, false);
    });

</script>