<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subject type</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalSubject({table: '#table-subjects', row: ''}); $('#modal-title').html('New Subject')"><i class="fa fa-plus"></i> New</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-subjects" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
<!-- studentrModal -->
<div id="modal-subject" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Subject New/Edit</h5>
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
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="subject_type" class="col-md-4 col-form-label text-sm-right">Subject <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="subject_type" maxlength="50">
                                        </div>
                                        <code class="small text-danger" id="subject_type--help">&nbsp;</code>
                                    </div>
                                </div>
                            <div class="col-12 form-group mg-t-8">
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalSubject({table: '#table-subjects', row: ''}); $('#modal-title').html('New Subject')"><i class="fa fa-plus"></i> Reset</button>
                                <button id="save-subject" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveSubject({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

</div>


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    let saved_data = '';

    // /////////////////////////////////////////////////////////////////////////////////////////
    // let modalAuto = () => {
    //     //console.log(window.location.hash, localStorage.getItem('modalOpen'));
    //     let hash = window.location.hash;
    //     let username = '<?php echo $data['params']['username'] ?>';
    //     let modalOpen = localStorage.getItem('modalOpen') !== '';
    
    //     if (hash !== '' && modalOpen) {
    //         localStorage.setItem('modalOpen', '');
        
    //         if (username !== '') {
            
    //             let tableUser = $('#table-user').DataTable();
            
    //             tableUser.columns(1).every(function () {
    //                 let data = this.data();
    //                 data.each(function (v, i) {
    //                     if (v === username) {
    //                         //console.log(v, i);
    //                         modalUser({table: '#table-user', row: i});
    //                         $('#modalNav a[href="#page_1"]').tab('show');
                        
    //                         return false;
    //                     }
    //                 });
    //             });
            
    //         } else modalUser({table: '#table-user', row: ''});
    //     }
    // }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveSubject = (json) => {
        //  console.log(json);return;
        let tableSubjects = $("#table-subjects").DataTable();
                
        // if(!confirm("Click ok to save"));return
        // if ($('#save-subject').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        //
        $.each($('#modal-subject').find('input, select, textarea'), function (i, obj) {
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
        //  console.log(form_data.get("subject_type"));return;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/school/subjectType/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-subject').html('<i class="fa fa-spinner fa-spin"></i> Saving Changes');
                $('#save-subject').prop({disabled: true});
                //
                saving = true;
            }
        })
        // using the done promise callback
        .done(function (data, textStatus, jqXHR) {
            //
            saving = false;
            //
            $('#save-subject').html('<i class="fa fa-save"></i> Save');
            $('#save-subject').prop({disabled: false});
            
            if (!data.status) {
                //
                new Noty({type: 'warning', text: '<h5>WARNING!</h5>' + data.message, timeout: 10000}).show();
                return false;
                //
            }
            //
            $("#modal-subject").modal("hide");
            saved_data = form_data.get("subject_type");
            new Noty({type: 'success', text: '<h5>SUCCESSFUL</h5>', timeout: 10000}).show();
            //
            
        })
        // process error information
        .fail(function (jqXHR, textStatus, errorThrown) {
            saving = false;
            // log data to the console so we can see
            //console.log(errorThrown);
            $('#save-subject').html('Save Changes');
            $('#save-subject').prop({disabled: false});
            //
            new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
            
        });
    }
    
    let modalSubject = (json) => { 
        //
        let data = json.row === '' ? {} : json.row;
        // console.log(data)
        $('#subject_type').val(data['subject_type'] ?? '');
        $('#status').prop({checked: ((data['status'] ?? '') === "1") ? true: false});
        $("#modal-subject").modal("show");
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableSubjects = $("#table-subjects").DataTable();
    
        let loadSubjects = (json) => {
            
            // dataTables
            let url = "<?php echo URL_ROOT ?>/school/subjectType/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
            tableSubjects.destroy();
        
            tableSubjects = $('#table-subjects').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "subject_type", "width": 5, "render": function (data, type, row, meta) {
                            return '\
                            <a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">\
                                <i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i>\
                            </a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99">\
                                <a class="dropdown-item" href="javascript:void(0)">\
                                    <i class="fas fa-times text-orange-red"></i>Close\
                                </a>\
                                <a class="dropdown-item" href="#" onclick="modalSubject({table: \'#table-subjects\', row: \'' + meta['row'] + '\'})">\
                                    <i class="fas fa-cogs text-dark-pastel-green"></i>Edit\
                                </a>\
                                <a class="dropdown-item" href="#"  onclick="deleteSubject({table: \'#table-subjects\', row: \'' + meta['row'] + '\'})">\
                                    <i class="fas fa-trash text-orange-peel"></i>Delete\
                                </a>\
                            </div>';
                        }
                    },
                    {"data": "subject_type"},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "asc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                // let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableSubjects.search(this.previousElementSibling.value).draw() });
                //     $("#table-subjects_filter.dataTables_filter input")
                //     .unbind()
                //     .bind("input, keyup", function(e) {
                //         // console.log(e.keyCode)
                //         if( (e.keyCode || e.keyCode || e.which) === 13) tableSubjects.search(this.value).draw();
                //         e.preventDefault();
                //     }).prop({placeholder: 'Press [Enter] Key'})
                //     .after(searchButton).prop({autocomplete: 'off'});
                //     //  modalAuto();
                }
            });
        }
    
        loadSubjects({});
    
        //
        tableSubjects.search('', false, true);
        //
        tableSubjects.row(this).remove().draw(false);
    
        //
        $('#table-subjects tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableSubjects.row($(this)).data();
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalSubject({table: '#table-subjects', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////

        //
        $('#modal-subject').on('hidden.bs.modal', function () {
            tableSubjects.ajax.reload(null, false);
            tableSubjects.search(saved_data).draw();
            // console.log(saved_data);
            // $('.dataTables_filter input[type="search"]').val(saved_data);
            
        });

        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
            
                // first_name
                if ($('#subject_type').val().trim() === '') {
                    disabled = true;
                    $('#subject_type--help').html('VALUE REQUIRED')
                } else {
                    $('#subject_type--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-subject_type').prop({disabled: disabled});
            // }
        
            checkForm.start();
        
        }, 500, false);
    });

</script>