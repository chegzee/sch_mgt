<?php $data = $data ?? [] ?>
<!-- Redirect to parent window -->
<!-- <script>
    if (window.location !== window.parent.location) {
        parent.location.assign('<?php echo URL_ROOT . '/system/login' ?>');
        window.frameElement.remove();
    }
</script> -->

<div class="container d-sm-none">
    <div class="row">
        <div class="col-md-auto justify-content-center">
            <img src="<?php echo ASSETS_ROOT ?>/images/app-icon.svg" style="width: 30%; display: block; margin: auto">
            <h4 class="app-logo text-center"><?php echo $data['app'] ?></h4>
        </div>
    </div>
</div>

<div id="login-div" class="container pt-5 all-div">
    <div class="row justify-content-center">
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card card-style-1">
                <div class="card-body p-4">
                    
                    <!-- LOG IN FORM -->
                    <img src="<?php echo ASSETS_ROOT ?>/images/app_logo.jpg" style="width: 100%; height: auto; max-height: 200px">
                    <h4 class="card-title text-center mb-0 app-logo"><?php echo $data['app'] ?></h4>
                    <div class="text-center text-muted font-italic">Log into your account</div>
                    <hr>
                    <form id="form1">
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons" style="color: rgba(35, 57,140);">person_outline</i>
                                <input type="email" class="form-control" placeholder="Username/Email" id="username">
                                <label for="username">Username/Email</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons" style="color: rgba(35, 57,140);">lock_open</i>
                                <input type="password" class="form-control" placeholder="Password" id="password">
                                <label for="password">Password</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block" style="background-color: rgba(35, 57,140);color:#fff;font-size:1.5em;" id="login">LOG IN</button>
                    </form>
                    <hr>
                    <div class="small">
                        Can't log into an account?
                        <a href="javascript:void(0)" class="text-primary text-decoration-underline" onclick="div_display({elem: '#password-step1-div'})">Forgot password</a>
                        <div style="display:flex;justify-content:space-between">
                            <a href="javascript:void(0)" style="font-size:18px" onclick="div_display({elem: '#student-div'})"><i class="fas fa-users"></i>Student</a>
                            <a href="javascript:void(0)"  style="font-size:18px"  onclick="div_display({elem: '#teacher-div'})"><i class="fas fa-users"></i>Teacher</a>
                        </div>
                    </div>
                    <!-- /LOG IN FORM -->
                
                </div>
            </div>
        </div>
    </div>
</div>

<div id="password-step1-div" class="container pt-5 all-div" style="display: none">
    <div class="row justify-content-center">
        <div class="col-md-auto d-flex justify-content-center">
            <!-- <div class="card card-style-1 d-none d-sm-block" style="background-color: transparent">
                <div class="card-body p-4" style="text-align: center">
                    <img src="<?php echo APP_LOGO ?>" style="width: 50%; display: block; margin: auto">
                    <h4 class="app-logo"><?php echo $data['app'] ?></h4>
                </div>
            </div> -->
            <div class="card card-style-1">
                <div class="card-body p-4">
                    
                    <!-- LOG IN FORM -->
                    <img src="<?php echo ASSETS_ROOT ?>/images/app_logo.jpg" style="width: 100%; height: auto; max-height: 200px">
                    <h4 class="card-title text-center mb-0 app-logo"><?php echo $data['app'] ?></h4>
                    <div class="text-center text-muted font-italic">Log into your account</div>
                    <hr>
                    <form id="form1">
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons">person_outline</i>
                                <input type="text" class="form-control" placeholder="Username/Email" id="username-1">
                                <label for="username-1">Username/Email</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-block"  style="background-color:  rgba(35, 57,140);;color:#fff;font-size:1.5em;" id="login-1">SEND CODE</button>
                    </form>
                    <hr>
                    <div class="small">
                        Remember credentials?
                        <a href="javascript:void(0)" class="text-primary text-decoration-underline" onclick="div_display({elem: '#login-div'})">Back to Login</a>
                    </div>
                    <!-- /LOG IN FORM -->
                
                </div>
            </div>
        </div>
    </div>
</div>

<div id="password-step2-div" class="container pt-5 all-div" style="display: none">
    <div class="row justify-content-center">
        <div class="col-md-auto d-flex justify-content-center">
            <!-- <div class="card card-style-1 d-none d-sm-block" style="background-color: transparent">
                <div class="card-body p-4" style="text-align: center">
                    <img src="<?php echo APP_LOGO ?>" style="width: 50%; display: block; margin: auto">
                    <h4 class="app-logo"><?php echo $data['app'] ?></h4>
                </div>
            </div> -->
            <div class="card card-style-1">
                <div class="card-body p-4">
                    
                    <!-- LOG IN FORM -->
                    <img src="<?php echo ASSETS_ROOT ?>/images/app_logo.jpg" style="width: 100%; height: auto; max-height: 100px">
                    <h4 class="card-title text-center mb-0 app-logo"><?php echo $data['app'] ?></h4>
                    <div class="text-center text-muted font-italic">Log into your account</div>
                    <hr>
                    <form id="form1">
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons">person_outline</i>
                                <input type="text" class="form-control" placeholder="Username/Email" id="username-2" readonly>
                                <label for="username-2">Username/Email</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons">person_outline</i>
                                <input type="text" class="form-control" placeholder="OTP" id="otp-2">
                                <label for="otp-2">OTP</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons">lock_open</i>
                                <input type="password" class="form-control" placeholder="New Password" id="password-2">
                                <label for="password">New Password</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons">lock_open</i>
                                <input type="password" class="form-control" placeholder="Confirm Password" id="confirm-2">
                                <label for="password">Confirm Password</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-block"  style="background-color:rgba(35, 57,140);;color:#fff;font-size:1.5em;" id="login-2">SAVE PASSWORD</button>
                    </form>
                    <hr>
                    <div class="small">
                        Remember credentials?
                        <a href="javascript:void(0)" class="text-primary text-decoration-underline" onclick="div_display({elem: '#login-div'})">Back to Login</a>
                        &nbsp; | &nbsp;
                        Did not receive code?
                        <a href="javascript:void(0)" class="text-primary text-decoration-underline" onclick="div_display({elem: '#password-step1-div'})">Resend code</a>
                    </div>
                    <!-- /LOG IN FORM -->
                
                </div>
            </div>
        </div>
    </div>
</div>

<div id="student-div" class="container pt-5 all-div" style="display: none">
    <div class="row justify-content-center">
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card card-style-1">
                <div class="card-body p-4">
                    
                    <!-- LOG IN FORM -->
                    <img src="<?php echo ASSETS_ROOT ?>/images/app_logo.jpg" style="width: 100%; height: auto; max-height: 200px">
                    <h4 class="card-title text-center mb-0 app-logo"><?php echo $data['app'] ?></h4>
                    <div class="text-center text-muted font-italic">Log into your account</div>
                    <hr>
                    <form id="form1">
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons" style="color: rgba(35, 57,140);">person_outline</i>
                                <input type="text" class="form-control" placeholder="Student ID" id="student_id">
                                <label for="student_id">Student ID</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-block" style="background-color: rgba(35, 57,140);;color:#fff;font-size:1.5em;" id="student_login">LOG IN</button>
                    </form>
                        <a href="javascript:void(0)" class="text-primary text-decoration-underline" onclick="div_display({elem: '#login-div'})">Back to Login</a>
                
                </div>
            </div>
        </div>
    </div>
</div>

<div id="teacher-div" class="container pt-5 all-div" style="display: none">
    <div class="row justify-content-center">
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card card-style-1">
                <div class="card-body p-4">
                    
                    <!-- LOG IN FORM -->
                    <img src="<?php echo ASSETS_ROOT ?>/images/app_logo.jpg" style="width: 100%; height: auto; max-height: 200px">
                    <h4 class="card-title text-center mb-0 app-logo"><?php echo $data['app'] ?></h4>
                    <div class="text-center text-muted font-italic">Log into your account</div>
                    <hr>
                    <form id="form1">
                        <div class="form-group">
                            <div class="floating-label input-icon">
                                <i class="material-icons" style="color:  rgba(35, 57,140);">person_outline</i>
                                <input type="text" class="form-control" placeholder="Teacher ID" id="teacher_id">
                                <label for="teacher_id">Teacher ID</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-block" style="background-color:  rgba(35, 57,140);;color:#fff;font-size:1.5em;" id="teacher_login">LOG IN</button>
                    </form>
                        <a href="javascript:void(0)" class="text-primary text-decoration-underline" onclick="div_display({elem: '#login-div'})">Back to Login</a>
                
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    let authenticator = <?php echo json_encode(AUTHENTICATOR) ?>;
    
    let div_display = (json) => {
        //  console.log(json);return;
        $('.all-div').css({display: 'none'});
        $(json.elem).css({display: 'block'});
        
        if (json.elem === '#login-div') {
            $('#username').val('');
            $('#password').val('');
        }
        else if (json.elem === '#password-step1-div') {
            $('#username-1').val('');
        }
        else if (json.elem === '#password-step2-div') {
            $('#otp-2').val('');
            $('#password-2').val('');
            $('#confirm-2').val('');
        }
    }
    
    $(function ($) {
        //
        $('#username').val('');
        $('#password').val('');
        //
        $('#form1').submit(function(e) {
            //
            e.preventDefault();
            if (!regexp_email.test($('#username').val())) {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Email required', timeout: 10000 }).show();
                return false;
            }
            //
            if (!regexp_password.test($('#password').val())) {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Password invalid', timeout: 10000 }).show();
                return false;
            }
    
            //console.log($('#save').prop('disabled'));
            $('#login').html('<i class="fa fa-spinner fa-spin"></i> LOG IN');
            $('#login').prop({disabled: true});
            // console.log("scared");return
            $.post('<?php echo URL_ROOT ?>/system/login/verify', {username: $('#username').val(), password: $('#password').val()}, function(data) {
                // console.log(data);;
                //
                if (data.status) {
                    parent.location.assign(data.data.url);
                    //
                    // if (authenticator) {
                    //     console.log("scared");return;
                    //     data.data.elem = '#token-div';
                    //     div_display(data.data);
                    //     //
                    //     keypad({item: '#keypad', token: '#token'});
                    // }
                    // else parent.location.assign(data.data.url);
                }
                //
                else {
                    //
                    $('#login').html('Login');
                    $('#login').prop({disabled: false});
                    //
                    new Noty({ type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000 }).show();
                    //
                    setTimeout(() => { parent.location.reload(); }, 2000);
                }
                
            }, 'JSON');
            //
        });
        //
        $('#login-1').click(function(e) {
            //
            if (!regexp_email.test($('#username-1').val())) {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Email required', timeout: 10000 }).show();
                return false;
            }
            //
            $('#login-1').html('<i class="fa fa-spinner fa-spin"></i> SEND CODE');
            $('#login-1').prop({disabled: true});
            //
            $.post('<?php echo URL_ROOT ?>/system/login/passwordStep1', {username: $('#username-1').val()}, function(data) {
                
                // console.log(data);
                //
                if (data.status) {
                    //
                    div_display({elem: '#password-step2-div'});
                    $('#username-2').val($('#username-1').val());
                }
                //
                else {
                    //
                    $('#login-1').html('SEND CODE');
                    $('#login-1').prop({disabled: false});
                    //
                    new Noty({ type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000 }).show();
                }
                
            }, 'JSON');
            //
            e.preventDefault();
        });
        //
        $('#login-2').click(function(e) {
            //
            if (!regexp_email.test($('#username-2').val())) {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Email required', timeout: 10000 }).show();
                return false;
            }
            //
            if ($('#otp-2').val().trim() === '') {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Email required', timeout: 10000 }).show();
                return false;
            }
            //
            if (!regexp_password.test($('#password-2').val())) {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Password invalid', timeout: 10000 }).show();
                return false;
            }
            //
            if ($('#confirm-2').val() !== $('#password-2').val()) {
                new Noty({ type: 'warning', text: '<h5>Warning!</h5>Confirm invalid', timeout: 10000 }).show();
                return false;
            }
            //
            $('#login-2').html('<i class="fa fa-spinner fa-spin"></i> SAVE PASSWORD');
            $('#login-2').prop({disabled: true});
            //
            $.post('<?php echo URL_ROOT ?>/system/login/passwordStep2', {username: $('#username-1').val(), password_reset: $('#otp-2').val(), new_password: $('#password-2').val(), confirm_password: $('#confirm-2').val()}, function(data) {
                //
                if (data.status) {
                    //
                    div_display({elem: '#login-div'});
                }
                //
                else {
                    //
                    $('#login-2').html('SAVE PASSWORD');
                    $('#login-2').prop({disabled: false});
                    //
                    new Noty({ type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000 }).show();
                }
                
            }, 'JSON');
            //
            e.preventDefault();
        });

        
        $('#student_login').click(function() {
            // console.log( $('#student_id').val());
            //
            // e.preventDefault();
            $('#student_login').html('<i class="fa fa-spinner fa-spin"></i> SEND CODE');
            $('#student_login').prop({disabled: true});
            $.post('<?php echo URL_ROOT ?>/system/login/verifyStudent', {student_id: $('#student_id').val()}, function(data) {
                console.log(data);
                //
            //    console.log(data)
                if (data.status) {
                    parent.location.assign(data.data.url);
                    //
                    // if (authenticator) {
                    //     console.log("scared");return;
                    //     data.data.elem = '#token-div';
                    //     div_display(data.data);
                    //     //
                    //     keypad({item: '#keypad', token: '#token'});
                    // }
                    // else parent.location.assign(data.data.url);
                }
                //
                else {
                    //
                    $('#student_login').html('Login');
                    $('#student_login').prop({disabled: false});
                    //
                    new Noty({ type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000 }).show();
                    //
                    // setTimeout(() => { parent.location.reload(); }, 2000);
                }
                
            }, 'JSON');
            //
        });

        $('#teacher_login').click(function() {
            // console.log( $('#student_id').val());
            //
            // e.preventDefault();
            $('#teacher_login').html('<i class="fa fa-spinner fa-spin"></i> LOGIN');
            $('#teacher_login').prop({disabled: true});
            $.post('<?php echo URL_ROOT ?>/system/login/verifyTeacher', {teacher_id: $('#teacher_id').val()}, function(data) {
                // console.log(data);
                //
            //    console.log(data)
                if (data.status) {
                    parent.location.assign(data.data.url);
                }
                //
                else {
                    //
                    $('#teacher_login').html('LOGIN');
                    $('#teacher_login').prop({disabled: false});
                    //
                    new Noty({ type: 'warning', text: '<h5>WARNING!</h5>' + data.message, timeout: 10000 }).show();
                    //
                    // setTimeout(() => { parent.location.reload(); }, 2000);
                }
                
            }, 'JSON');
            //
        });
    });
</script>

</body>
</html>