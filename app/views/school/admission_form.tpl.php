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
        <div class="card height-auto">
            <div class="card-body">
                <div class="heading-layout1">
                    <div class="item-title">
                        <h3>Add New Students</h3>
                    </div>
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">...</a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                        </div>
                    </div>
                </div>
                <form class="new-added-form" id="std_form">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="std_code">Code *</label>
                            <input type="text" class="form-control form-control-lg" id="std_code" style="width: 100%"/>
                            <code class="small text-danger" id="std_code--help">&nbsp;</code>
                            <input id="std_code_old" style="display:none"/>
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
                            <label for="roll">Roll <span class="small text-primary"> (Optional)</span></label>
                            <input type="text" class="form-control form-control-lg" id="roll" style="width: 100%"/>
                            <code class="small text-danger" id="roll--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="blood_group">Blood Group <span class="small text-primary"> (Optional)</span></label>
                            <select class="form-control form-control-lg" id="blood_group" style="width: 100%"></select>
                            <code class="small text-danger" id="blood_group--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="religion">Religion <span class="small text-primary"> (Optional)</span></label>
                            <select class="form-control form-control-lg" id="religion" style="width: 100%"></select>
                            <code class="small text-danger" id="religion--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="email">Email<span class="small text-primary"> (Optional)</span> </label>
                            <input type="email" class="form-control form-control-lg" id="email" style="width: 100%"/>
                            <code class="small text-danger" id="email--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="class">Class </label>
                            <select class="form-control form-control-lg" id="class_code" style="width: 100%">
                            </select>
                            <code class="small text-danger" id="class--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group" id="dept_container" style="display: none;">
                            <label for="cat_code">Department </label>
                            <select class="form-control form-control-lg" id="department" style="width: 100%">
                                <!-- <option selected disabled>Choose an option</option> -->
                                <option>SCIENCE</option>
                                <option>COMMERCIAL</option>
                                <option>ART</option>
                            </select>
                            <code class="small text-danger" id="department--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="section">Section </label>
                            <select class="form-control form-control-lg" id="section" style="width: 100%"></select>
                            <code class="small text-danger" id="section--help">&nbsp;</code>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                            <label for="admission_id">Admission id(Optional) </label>
                            <input type="text" class="form-control form-control-lg" id="admission_id" style="width: 100%"/>
                            <code class="small text-danger" id="admission_id--help">&nbsp;</code>
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
                            <label for="parent_name">Parent name </label>
                            <input type="text" class="form-control form-control-lg" id="parent_name" style="width: 100%"/>
                            <code class="small text-danger" id="parent_name--help">&nbsp;</code>
                        </div>
                        <div class="col-12 form-group mg-t-8">
                            <button type="button" class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalStudent({})"><i class="fa fa-refresh"></i> Reset</button>
                            <button id="save-student" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveStudent({})">Save </button>
                        </div>
                        <input id="std_code" style="display:none"/>
                        <!-- <div class="col-lg-6 col-12 form-group">
                            <label>Short BIO</label>
                            <textarea class="textarea form-control" name="message" id="form-message" cols="10" rows="9"></textarea>
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    let classObj = <?php echo json_encode($data['classesobj']) ?>;

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveStudent = (json) => {
        //  console.log("json");return;
        // let tableUser = $(json.table).DataTable();
                
        
        if ($('#save-student').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        //
        $.each($('#std_form').find('input, select, textarea'), function (i, obj) {
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
        // console.log(form_data);return;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/students/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-student').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-student').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                //
                saving = false;
                //
                $('#save-student').html('Save Changes');
                $('#save-student').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                //
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                saving = false;
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-student').html('Save Changes');
                $('#save-student').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }
    let modalStudent = (json) => { 
        //
        $('#std_code').val('AUTO');
        $('#std_code_old').val('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#gender').append(new Option('', '', true, true)).trigger('change');
        $('#cat_code').append(new Option('', '', true, true)).trigger('change');
        $('#class_name_code').append(new Option('', '', true, true)).trigger('change');
        $('#section').append(new Option('', '', true, true)).trigger('change');
        $('#religion').append(new Option('', '', true, true)).trigger('change');
        $('#birthday').val('');
        $('#roll').val('');
        $('#email').val('');
        $('#admission_id').val('');
        $('#phone').val('');
        $('#parent_name').val('');
        $('#address').val('');
        $('#year').append(new Option('', '', true, true));
        $('#term').append(new Option('', '', true, true));
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
        $('#std_code').val('AUTO');

        $('#section').append(new Option('', '', true, true)).trigger('change')
        $('#gender').append(new Option('', '', true, true)).trigger('change')
        $('#religion').append(new Option('', '', true, true)).trigger('change')
        $('#blood_group').append(new Option('', '', true, true)).trigger('change')
        $('#class_code').append(new Option('', '', true, true)).trigger('change')
        ////
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
        $('#section').select2({
            placeholder: "please select",
            data: [{id: "BLUE", text: "BLUE"}, 
            {id: "BIRD", text: "BIRD"},
             {id:"ROSE", text: "ROSE"},
              {id: "PINK", text: "PINK"}]}
        )
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
        
        //
        $('#class_code').select2({
            placeholder: "Please select",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    // console.log(params);return;
                    return {
                        searchTerm: params.term,
                        _option: 'select'
                    };
                },
                processResults: function (response) {
                      console.log(response);
                    return { results: response };
                },
                cache: true
            }
        }).on("select2:select", (v)=>{
            $("#department").html('');
            $("#department").append(new Option("Choose an option", "", true, true)).trigger("change");
            $.each(["ART", "COMMERCIAL", "SCIENCE"], (k, v)=>{
                $("#department").append(new Option(v, v));

            })

            let class_code = v.target.selectedOptions[0].value;
            let obj = classObj[class_code];
            let digit = parseInt(obj.digit) >= 12 ? "block" : "none";
            $("#dept_container").css("display", digit)
        });

        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;

            
            // CLASS/SSS1/06
            // if($('#cat_code').val() === 'CLASS/SSS1/06'){
            //     $('#dept_container').css("display", "");
            // }else if($('#cat_code').val() === 'CLASS/SS2/04'){
            //     $('#dept_container').css("display", "");
            // }else if($('#cat_code').val() === 'CLASS/SSS3/05'){
            //     $('#dept_container').css("display", "");
            // }else {
            //     $('#dept_container').css("display", "none");
            //     $('#department').val('');
            // }
        
            // user
                if ($('#class_code').val().trim() === '') {
                    // disabled = true;
                    $('#class--help').html('CLASS NAME REQUIRED');
                } else {
                    $('#class--help').html('&nbsp;')
                }
                //
                // if ($('#class_name_code').val().trim() === '') {
                //     // disabled = true;
                //     $('#class_name--help').html('CLASS NAME REQUIRED');
                // } else {
                //     $('#class_name--help').html('&nbsp;')
                // }
            
                // first_name
                if ($('#first_name').val().trim() === '') {
                    // disabled = true;
                    $('#first_name--help').html('FIRST NAME REQUIRED')
                } else {
                    $('#first_name--help').html('&nbsp;')
                }
            
                // last_name
                if ($('#last_name').val().trim() === '') {
                    // disabled = true;
                    $('#last_name--help').html('LAST NAME REQUIRED')
                } else {
                    $('#last_name--help').html('&nbsp;')
                }
                // username
                if ($('#email').val().trim() !== '' && !regexp_email.test($('#email').val())) {
                    // disabled = true;
                    $('#email--help').html('VALID EMAIL REQUIRED')
                } else {
                    $('#email--help').html('&nbsp;')
                }
                // username
                if ($('#phone').val().trim() !== '' && !regexp_phone.test($('#phone').val())) {
                    // disabled = true;
                    $('#phone--help').html('VALID PHONE NUMBER REQUIRED')
                } else {
                    $('#phone--help').html('&nbsp;')
                }
    
                // address
                if ($('#address').val().trim() === '') {
                    // disabled = true;
                    $('#address--help').html('ADDRESS REQUIRED')
                } else {
                    $('#address--help').html('&nbsp;')
                }
                // admission_id
                if ($('#admission_id').val().trim() === '') {
                    // disabled = true;
                    $('#admission_id--help').html('ADMISSION ID REQUIRED')
                } else {
                    $('#admission_id--help').html('&nbsp;')
                }
                // parent
                if ($('#parent_name').val().trim() === '') {
                    // disabled = true;
                    $('#parent_name--help').html('PARENT NAME REQUIRED')
                } else {
                    $('#parent_name--help').html('&nbsp;')
                }
                // birthday
                if ($('#birthday').val().trim() === '') {
                    // disabled = true;
                    $('#birthday--help').html('BIRTHDAY REQUIRED')
                } else {
                    $('#birthday--help').html('&nbsp;')
                }
                // gender
                if ($('#gender').val().trim() === '') {
                    // disabled = true;
                    $('#gender--help').html('GENDER REQUIRED')
                } else {
                    $('#gender--help').html('&nbsp;')
                }
                // gender
                // if ($('#year').val().trim() === '') {
                //     // disabled = true;
                //     $('#year--help').html('YEAR REQUIRED')
                // } else {
                //     $('#year--help').html('&nbsp;')
                // }
                // // gender
                // if ($('#term').val().trim() === '') {
                //     // disabled = true;
                //     $('#term--help').html('TERM REQUIRED')
                // } else {
                //     $('#term--help').html('&nbsp;')
                // }
                
                //
                if (saving) disabled = true;
                $('#save-student').prop({disabled: disabled});
            // }
        
            checkForm.start();
        
        }, 500, false);
    });

</script>