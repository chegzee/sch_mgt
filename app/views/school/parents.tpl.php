<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Parents</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalParent({table: '#table-parent', row: ''}); $('#modal-title').html('New Parent')"><i class="fa fa-plus"></i> New</button>
            <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'parent_schedule_upload', row: '', modal: '#modal-parent_schedule'})"><i class="fa fa-file-import"></i> Excel</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-parent" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>ID No</th>
                                <th>Picture</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
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

<!-- parentModal -->
<div id="modal-parent" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Parent New/Edit</h5>
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
                                <label for="title">Title </label>
                                <select type="text" class="form-control form-control-lg" id="title" style="width: 100%"></select>
                                <code class="small text-danger" id="title--help">&nbsp;</code>
                            </div>
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
                            <div class="col-xl-3 col-lg-6 col-12 px-3 mt-4 form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture"  style="min-width:150px;max-width:150px;">
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
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalParent({table: '#table-parent', row: ''}); $('#modal-title').html('New Parent')"><i class="fa fa-plus"></i> Reset</button>
                                <button id="save-parent" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveParent({table: '#table-parent'})"><i class="mdi mdi-file-download"></i> Save </button>
                            </div>
                            <input id="parent_code" style="display:none"/>
                            <input id="parent_code_old" style="display:none"/>
                        </div>
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- parent schedule modal -->
<div id="modal-parent_schedule" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Parent schedule New/Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <button class="btn btn-small btn-outline-primary mb-3" onclick="$('#parent_schedule_file').click()"><i class="fa fa-file-import"></i>Import</button>
                <button class="btn btn-small btn-outline-primary mb-3" onclick="scheduleExportV2({theadRows: '#table-parent_schedule_upload thead tr', tbodyRows: '#table-parent_schedule_upload tbody', filename: 'parent_schedule'})" ><i class="fa fa-file-import"></i>Export</button>
                <button class="btn btn-small btn-outline-primary mb-3" id="save-import_parent_schedule" onclick="saveStdScheduleUpload({table: '#table-parent_schedule_upload'})"><i class="fa fa-save"></i>Save</button>
                <!-- <input type="hidden" id="doc_path" maxlength="250" readonly> -->
                <input type="file" id="parent_schedule_file" accept="application/vmd.openxmlformats.officedocumnet.spreadsheet.sheet, application/vmd.ms.excel" onchange="scheduleImport({excelfile: '#parent_schedule_file', table: '#table-parent_schedule_upload', action: 'parentSchedule'}, event)" style="display:none">
                <div class="table-responsive">
                    
                    <div id="" class="dataTables_wrapper">
                        <!-- <div class="dataTables_wrapper dt-bootstrap4 no-footer"> -->
                            <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" role="grid" id="table-parent_schedule_upload">
                                <thead>
                                    <tr>
                                        <th><i class="material-icons">build</i></th>
                                        <th>ID</th>
                                        <th>First name</th>
                                        <th>Last name</th>
                                        <th>Title</th>
                                        <th>Gender</th>
                                        <th>Religion</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <!-- <th>Branch</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <!-- <td> </td> -->

                                    </tr>
                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- std schedule -->


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    // User Access
    let userAccess = <?php echo json_encode($data['user']['access']) ?>;
    let upload_fields = [];
    // console.log(userAccess);
    
    //
    let deleteParent = (json) => {
        //console.log(json);
        let tableParent = $(json.table).DataTable();
        let data = tableParent.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Parent: ' + data['parent_code'] + ' : ' + data['first_name'] + " " + data['last_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/school/parents/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableParent.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalParent = (json) => { 
        let tableParent = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableParent.row(json.row).data(); // data["colName"]
        // console.log(data)
        $('#modalNav').find('a.non-active').addClass('d-none');
        
        $('#parent_code').val(data['parent_code']);
        $('#parent_code_old').val(data['parent_code']);
        $('#first_name').val(data['first_name'] ?? '');
        $('#title').append(new Option(data['title'] ?? '', data['title'] ?? '', true, true)).trigger('change');
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
        // $('#doc_path').val(data['doc_path'] ?? '');
        let pics = data['picture'] ?? '';
        pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
        //
        $('#picture--preview').attr('src', pics);
        //
        $('#picture').val(data['picture'] ?? '');
        //
        $('#modal-parent').modal('show');
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
            
                let tableParent = $('#table-parent').DataTable();
            
                tableParent.columns(1).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === username) {
                            //console.log(v, i);
                            modalUser({table: '#table-parent', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalUser({table: '#table-parent', row: ''});
        }
    }
    //
    let std_schedule_upload_fields = ()=>{
        let td_collection = [...$("#table-parent_schedule_upload thead").find('tr')["0"].children];
        td_collection.shift();
        $.each(td_collection, (k, v)=>{
            let v_ = $(v).html();
            upload_fields.push(v_)

        })
    }
    
    let createstdscheduleRow = (json) => {
        // console.log(json.data);return
        let tbody = $(json.table).find('tbody');
        let keys = Object.keys(json.data);
        // console.log(keys)
        let id, first_name, last_name, gender, birthday, religion, email, address;
        id = (json.data['ID'] ?? '') !== '' ? json.data['ID'].replace(/[^0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        first_name = (json.data['First name'] ?? '') !== '' ? json.data['First name'].replace(/[^0-9a-zA-Z_\s\.]/g, '').trim().toUpperCase() : '';
        last_name = (json.data['Last name'] ?? '') !== '' ? json.data['Last name'].toString().replace(/[^0-9a-zA-Z_\s\.]/g, '').trim().toUpperCase() : ''; '';
        gender = (json.data['Gender'] ?? '') !== '' ? json.data['Gender'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        //birthday = (json.data['Birthday'] ?? '') !== '' ? json.data['Birthday'].toString().replace(/[^:0-9a-zA-Z_\s-\/]/g, '').trim().toUpperCase() : '';
        religion = (json.data['Religion'] ?? '') !== '' ? json.data['Religion'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        email = (json.data['Email'] ?? '') !== '' ? json.data['Email'].toString().replace(/[^:0-9a-zA-Z_\s@\.]/g, '').trim().toUpperCase() : '';
        address = (json.data['Address'] ?? '') !== '' ? json.data['Address'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        branch = (json.data['Branch'] ?? '') !== '' ? json.data['Branch'].toString().replace(/[^:0-9a-zA-Z_\s]/g, '').trim().toUpperCase() : '';
        // birthday = birthday
        // birthday = moment(birthday, 'DD/MM/YYYY').format('YYYY-MM-DD');
        //
        let html = '<tr>\
        <td>\
            <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">\
                <i class="fa fa-cog"></i>\
            </a>\
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">\
                <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                <a class="dropdown-item" href="#"  onclick="removeRow({table: \''+json.table+'\', elem: event})">\
                    <i class="fas fa-trash text-orange-peel"></i>Remove\
                </a>\
            </div>\
        </td>';
        $.each(upload_fields, function (k, v) {
            if(v === 'ID'){
                 html += '<td><input id="std_code" class="" value="'+ id +'" /></td>';

            }else if(v === 'First name'){
                 html += '<td><input style="width:200px" id="first_name" value="'+ first_name +'" readonly/></td>';

            } else if(v === 'Last name'){
                 html += '<td><input style="width:200px" id="last_name" value="'+ last_name +'" readonly></td>';

            } else if(v === 'Title'){
                 html += '<td><select style="width:200px" id="title-'+json.row+''+k+'"><option  value="'+json.data['Title']+'" selected>'+json.data['Title']+'</option></select></td>';

            }
            else if(v === 'Gender'){
                 html += '<td><select style="width:100%" id="gender-'+json.row+''+k+'"><option value="'+json.data['Gender']+'" selected >'+json.data['Gender']+'</option></select></td>';

            }
            else if(v === 'Religion'){
                 html += '<td><select id="religion-'+json.row+''+k+'" style="width:100%"><option value="'+ religion +'" selected >'+religion+'</option></select></td>';

            }else if(v === 'Email'){
                 html += '<td><input id="email" value="'+ email +'" /></td>';

            }else if(v === 'Phone'){
                 html += '<td><input id="phone" value="'+ email +'" /></td>';

            }
            else if(v === 'Address'){
                 html += '<td><input id="address" value="'+ address +'" /></td>';

            }
            // else if(v === 'Branch'){
            //      html += '<td><select id="branch_code-'+json.row+''+k+'" style="width:100%" ></select></td>';

            // }
        })
        html += '</tr>';
        tbody.append(html);

        //////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(6) > select#religion-'+json.row+'5').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getReligions",
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
         $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(4) > select#title-'+json.row+'3').select2({
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
        });
        // ////////////////////
        $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(5) > select#gender-'+json.row+'4').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getGenders",
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
        
        // $(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(10) > select#branch_code-'+json.row+'9').select2({
        //     placeholder: "Select an option",
        //     allowClear: true,
        //     ajax: {
        //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getBranches",
        //         type: "post",
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             return {
        //                 searchTerm: params.term,
        //                 _option: 'select',
        //             };
        //         },
        //         processResults: function (response) {
        //             //console.log(response);
        //             return {
        //                 results: response
        //             };
        //         },
        //         cache: true
        //     }
        // });
        // ////
        // flatpickr($(json.table + ' tbody').find('tr:eq('+json.row+') > td:eq(6) > input#birthday-'+json.row+'5'), {
        //     dateFormat: 'Y-m-d',
        //     allowInput: true,
        //     minDate: '1800-01-01',
        //     // maxDate: new Date().fp_incr(0), // -92
        // });

    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveParent = (json) => {
        // console.log(json);return;
        let tableParent = $(json.table).DataTable();
        
        if ($('#save-parent').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        //
        $.each($('#modal-parent #page_1').find('input, select, textarea'), function (i, obj) {
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
        //    console.log(form_data); return;
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/parents/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-parent').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-parent').prop({disabled: true});
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
                $('#save-parent').html('Save Changes');
                $('#save-parent').prop({disabled: false});
                
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
                $("#modal-parent").modal('hide');
                tableParent.ajax.reload(null, false);
                //
                $('div.access_div').css({display: 'none'});
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-parent').html('Save Changes');
                $('#save-parent').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    
    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");

        $("#"+table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    
    let saveStdScheduleUpload = (json) =>{
        // $('#save-import_std_schedule').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');

        if(!confirm("Do you want to perform this operation")) return;
        // console.log("scared");return;
        let tr = $(json.table + ' tbody').find('tr');
        tr.each((k, v)=>{
            $(v).find('td:eq(0)').find('a').css('color', '#167bea');
        })
        let rows = [];
        $(json.table + ' tbody > tr').each((k, tr)=>{
            //
            // console.log();
            let row = {};
            let td = [...tr.children];
            td.forEach((v, k)=>{
                
                let tag_name = v.children[0].tagName;
                let id = String(v.children[0].id);
                let key = id.substring(0, (id.indexOf('-') === -1 ) ? id.length :  id.indexOf('-') );
                if(tag_name === 'A'){

                }else if(tag_name === 'INPUT'){
                    row[key] = String(v.children[0].value).trim();
                }else if(tag_name === 'SELECT'){
                    let select_ = v.children[0] ?? '<select></select>';
                    let option_ = $(select_)["0"].selectedOptions[0] ?? '<option></option>';
                    let vvv = $(option_)["0"].value ?? '';
                    
                    row[key] = String(vvv).trim();
                    // console.log(vvv)
                    // row[key] = String(v.children[0].selectedOptions[0].value).trim();

                }
            })
            rows.push(row);
            
        });
        // console.log(rows);return;
        let data = {data: JSON.stringify(rows)};
        $('#save-import_parent_schedule').html('<i class="fa fa-spinner fa-spin"></i>Save').prop({disabled: true});
        $.post('<?php echo URL_ROOT ?>/school/parents/__save/?user_log=<?php echo $data['params']['user_log'] ?>', data, (data)=>{
            if(!data.status){
                $(json.table + ' tbody > tr:eq('+data.key+') td:eq(0) a').css("color", "red");
                $('#save-impor_parent_schedule').html('<i class="fa fa-save"></i>Save');
                new Noty({type: 'warning', text: '<h5>WARNING!</h5>' + data.message, timeout: 10000}).show();
                $($(json.table + ' tbody').find('tr:eq('+data.rowNo+') td:eq(0)')).css("background-color", "red");
                return false;
            }
            $('#save-import_parent_schedule').html('<i class="fa fa-save"></i>Save').prop({disabled: false});
            //
            new Noty({type: 'success', text: '<h5>SUCCESSFUL</h5>', timeout: 10000}).show();
            $("#modal-parent_schedule").modal('hide');
                // console.log(data.key);
        }, 'json')
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
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
        let tableParent = $("#table-parent").DataTable();
    
        let loadParent = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/school/parents/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
            tableParent.destroy();
        
            tableParent = $('#table-parent').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "parent_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalParent({table: \'#table-parent\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteParent({table: \'#table-parent\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                        }
                    },
                    {"data": "parent_code"},
                    {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                        return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                     }},
                    {"data": "first_name"},
                    {"data": "last_name"},
                    {"data": "gender"},
                    {"data": "phone"},
                    {"data": "email"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  console.log(json);
                    //  modalAuto();
                }
            });
        }
    
        loadParent({});
        
        std_schedule_upload_fields();
    
        //
        tableParent.search('', false, true);
        //
        tableParent.row(this).remove().draw(false);
    
        //
        $('#table-parent tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableParent.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalParent({table: '#table-parent', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-parent').on('hidden.bs.modal', function () {
            tableParent.ajax.reload(null, false);
        });
        $('#modal-parent_schedule').on('hidden.bs.modal', function () {
            tableParent.ajax.reload(null, false);
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
            if ($('#modal-parent').hasClass('show')) {
            
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
                $('#save-parent').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true);
    });

</script>