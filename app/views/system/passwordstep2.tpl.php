<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="p-2">
                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <a href="<?php echo URL_ROOT ?>/system/login" class="logo"><img src="<?php echo ASSETS_ROOT ?>/images/logo.png"
                                                                                                    height="50" alt="logo"></a>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="font-size-16 text-50 pt-4 text-right"><?php echo $data['app'] ?></h5>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>

                            <form id="form1" name="form1" class="form-horizontal" action="">
                                <input type="hidden" id="csrf" name="csrf" value="<?php echo $data['csrf'] ?>" readonly>
                                <input type="hidden" id="disable-session-check">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="username">Username</label>
                                            <input type="email" class="form-control" id="username" name="username" value="<?php echo $data['user']['username'] ?>" placeholder="Enter username"
                                                   readonly>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="password_reset">Reset Code</label>
                                            <input type="text" class="form-control autocomplete" id="password_reset" name="password_reset" placeholder="Enter Code"
                                                   readonly autocomplete="current-password">
                                            <code class="small text-danger" id="password_reset--help">&nbsp;</code>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="new_password">New Password</label>
                                            <input type="password" class="form-control autocomplete" id="new_password" name="new_password" placeholder="Enter new password"
                                                   readonly>
                                            <code class="small text-danger" id="new_password--help">&nbsp;</code>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="confirm_password">Confirm Password</label>
                                            <input type="password" class="form-control autocomplete" id="confirm_password" name="confirm_password" placeholder="Confirm password"
                                                   readonly>
                                            <code class="small text-danger" id="confirm_password--help">&nbsp;</code>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="text-md-left mt-3 mt-md-0" style="padding-left: 5px">
                                                    <a href="<?php echo URL_ROOT ?>/system/login/passwordStep1" class="text-muted"><i class="mdi mdi-textbox-password"></i> Resend Code?</a>
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 10px">
                                                <button id="login" name="login" class="btn btn-success btn-block waves-effect waves-light" disabled
                                                        type="submit">Reset Password
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-white"><i class="mdi mdi-account-circle mr-1"></i> Copy &copy; SAFAM Digital Hub LLC</a>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    $(function () {

        // form1 submit
        $('#form1').submit(function (e) {

            //console.log($('#save').prop('disabled'));
            if ($('#login').prop('disabled')) return false;
            //
            let form_data = new FormData();

            //
            $.each($('#form1 input, select, textarea'), function (i, obj) {
                //
                if (obj['id'] == '') return true;
                //console.log(obj['id']);
                //
                if ($('#' + obj['id']).prop('type') == 'checkbox') {
                    //
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
                    form_data.append(obj['name'], obj['value']);
                }

            });

            //
            form_data.append('login', true);

            // process the form
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?php echo URL_ROOT ?>/system/login/passwordStep2_', // the url where we want to POST
                data: form_data, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                contentType: false,
                //encode: true,
                cache: false,
                processData: false,

                beforeSend: function () {
                    //
                    $('#login').html('<i class="fa fa-spinner fa-spin"></i> Reset Password');
                    $('#login').prop({disabled: true});
                    //
                    checkForm.stop();
                }
            })
                // using the done promise callback
                .done(function (data, textStatus, jqXHR) {
                    // log data to the console so we can see
                    console.log(data);
                    //
                    if (data.status == '1') {
                        //
                        new Noty({ type: 'success', text: '<h5>Success!</h5>' + data.data.message, timeout: 10000 }).show();
                        //
                        setTimeout(function () {
                            parent.location.assign('<?php echo URL_ROOT ?>/system/login');
                        }, 3000);
                    }
                    //
                    else {
                        //
                        $('#login').html('Reset Password');
                        $('#login').prop({disabled: false});
                        //
                        checkForm.start();
                        //
                        new Noty({ type: 'error', text: '<h5>Error!</h5>' + data.message, timeout: 10000 }).show();
                    }
                })
                // process error information
                .fail(function (jqXHR, textStatus, errorThrown) {

                    // log data to the console so we can see
                    //console.log(errorThrown);
                    $('#login').html('Reset Password');
                    $('#login').prop({disabled: false});
                    //
                    checkForm.start();
                    //
                    new Noty({ type: 'error', text: '<h5>Error!</h5>' + errorThrown, timeout: 10000 }).show();

                });

            // stop the form from submitting the normal way and refreshing the page
            e.preventDefault();
        });

        //
        var checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;

            // username
            if (!regexp_email.test($('#username').val())) {
                disabled = true;
                $('#username--help').html('INVALID USERNAME/EMAIL')
            } else {
                $('#username--help').html('&nbsp;')
            }

            // password_reset
            if ($.trim($('#password_reset').val()) == '') {
                disabled = true;
                $('#password_reset--help').html('RESET CODE REQUIRED')
            } else {
                $('#password_reset--help').html('&nbsp;')
            }

            // new_password
            if (!regexp_password.test($('#new_password').val())) {
                disabled = true;
                $('#new_password--help').html('Password invalid / short: a-zA-Z0-9.@#$%')
            } else {
                $('#new_password--help').html('&nbsp;')
            }

            // confirm_password
            if ($('#confirm_password').val() == '' || $('#new_password').val() !== $('#confirm_password').val()) {
                disabled = true;
                $('#confirm_password--help').html('Confirm password does not match')
            } else {
                $('#confirm_password--help').html('&nbsp;')
            }

            //
            $('#login').prop({disabled: disabled});

            checkForm.start();

        }, 500, true); //

    });
</script>

</body>
</html>
