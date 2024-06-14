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
            <button onclick="modalTerm({table: '#table-term', row: ''}); $('#modal-title').html('New Term')"><i class="fa fa-plus"></i> Add</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-term" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>Term</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- term modal -->
<div id="modal-term" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Term period</h5>
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
    
                        <!-- <button onclick="modalTerm({table: '#table-term', row: ''}); $('#modal-title').html('New Term period')"><i class="fa fa-plus"></i> Reset</button> -->
                        
                        <div class="row">
    
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="code" class="col-md-4 col-form-label text-sm-right">Code <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-control-nolabel custom-checkbox">
                                                        <input type="checkbox" class="" id="status" checked="checked">
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-control form-control-sm" type="text" id="code" maxlength="100" readonly>
                                        </div>
                                        <code class="small text-danger" id="code--help">&nbsp;</code>
                                    </div>
                                    <input type="hidden" id="code_old" readonly>
                                    <input type="hidden" id="term_code_prefix" readonly>
                                </div>
                                <!-- <div class="form-group row">
                                    <label for="year" class="col-md-4 col-form-label text-sm-right">Year <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="year" maxlength="100"/>
                                        </div>
                                        <code class="small text-danger" id="year--help">&nbsp;</code>
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label for="year" class="col-md-4 col-form-label text-sm-right">Year <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <select class="form-control form-control-sm" id="year" style="width: 100%">
                                            <option value="" selected></option>
                                            <?php
                                            foreach (range(date('Y') + 1, 2000, -1) as $v) {
                                                echo '<option value="' . $v . '">' . $v . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <code class="small text-danger" id="year--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Term" class="col-md-4 col-form-label text-sm-right">Term <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <select class="form-control" id="term" style="width:100%">
                                                <option value="" selected>Select term</option>
                                            </select>
                                        </div>
                                        <code class="small text-danger" id="term--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_date" class="col-md-4 col-form-label text-sm-right">Start Date <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="start_date" maxlength="100">
                                        </div>
                                        <code class="small text-danger" id="start_date--help">&nbsp;</code>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_date" class="col-md-4 col-form-label text-sm-right">End Date <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="end_date" maxlength="100">
                                        </div>
                                        <code class="small text-danger" id="end_date--help">&nbsp;</code>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-term" class="" type="button" style="margin-left: auto" onclick="saveTerm({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
                        </div>
                    
                    </div>
                </div>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    let tableTerm = null;
    
    //
    let deleteUser = (json) => {
        // console.log(json);return
        let tableTerm = $(json.table).DataTable();
        let data = tableTerm.row(json.row).data(); // data["colName"]
        // console.log(data);return;
        if (!confirm('Delete Term')) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/term/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableTerm.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalTerm = (json) => {
        tableTerm = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableTerm.row(json.row).data(); // data["colName"]
        // console.log(data)
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        $('#status').prop("checked", (data['status']?? '') !== "1" ? false: true);
        $('#code').val((data['code'] ?? '') === '' ? 'AUTO' : data['code']);
        $('#code_old').val(data['code'] ?? '');
        $('#term').append(new Option(data['term'] ?? '', data['term'] ?? '', true, true)).trigger('change')
        $('#start_date').val(data['start_date'] ?? '')
        $('#end_date').val(data['end_date'] ?? '')
        $('#year').val(data['year'] ?? '').trigger('change');
        // $('#year').val(data['year']).trigger('change')
        //
        $('#modal-term').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = (json) => {
        console.log(json);
        let modalOpen = localStorage.getItem('modalOpen') !== '';
        if ((json.code ?? '') !== '') {
            // console.log(modalOpen,selectedStd)
                tableTerm = $('#table-term').DataTable();
            
                tableTerm.rows().every(function () {
                    let data = this.data();
                    // console.log(data);
                    if(data.code === json.code){
                        modalTerm({table: '#table-term', row: this, page: json.page, modalAuto: true});
                        // console.log(this);
                    }
                });
            
        } else modalTerm({table: '#table-term', row: ''});
        
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveTerm = (json) => {
        //console.log(json);
        let tableTerm = $(json.table).DataTable();
        if ($('#save-term').prop('disabled')) return false;
        let tc = String($('#term').val()).split(" ");
        let f = tc[0][0] + tc[1][0];
        $('#term_code_prefix').val(f);
        // console.log(f);return;
        
        //
        let form_data = new FormData();
        //
        $.each($('#modal-term #page_1').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
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
        // console.log(form_data);return;
        let term_code = form_data.get("code");

        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/term/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-term').html('<i class="fa fa-spinner fa-spin" style="color:white"></i> Save Changes');
                $('#save-term').prop({disabled: true});
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
                $('#save-term').html('Save Changes');
                $('#save-term').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                //
                // tableTerm.ajax.reload(null, false);
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $("#modal-term").modal("hide");
                //
                // setTimeout(()=>{
                //     modalAuto({code: term_code});
                // }, 2000)
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-term').html('Save Changes');
                $('#save-term').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    
    $("#term").select2({
        placeholder: "Select an option",
        allowClear: true,
        data: [
            {id: "FIRST TERM", text: "FIRST TERM"},
            {id: "SECOND TERM", text: "SECOND TERM"},
            {id: "THIRD TERM", text: "THIRD TERM"}
        ]
    })
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        //
        flatpickr('#start_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            // maxDate: new Date().fp_incr(0), // -92
        });
        //
        flatpickr('#end_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            // maxDate: new Date().fp_incr(0), // -92
        });
        //
        // flatpickr('#year', {
        //     dateFormat: 'Y',
        //     allowInput: true,
        //     minDate: '1800-01-01',
        //     maxDate: new Date().fp_incr(365), // -92
        // });

        
        $('#year').select2({
            placeholder: "Select an option",
            allowClear: true,
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableTerm = $("#table-term").DataTable();
    
        let loadTerm = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/system/term/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        
            tableTerm.destroy();
        
            tableTerm = $('#table-term').DataTable({
                "processing": true,
                // "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalUser({table: \'#table-term\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteUser({table: \'#table-term\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                            ;
                        }
                    },
                    {"data": "term"},
                    {"data": "start_date"},
                    {"data": "end_date"},
                    {"data": "year"},
                ],
                "columnDefs": [
                    {"targets": [4], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[2, "desc"]],
                "initComplete": function (settings, json) {
                    // console.log(json);
                    //  modalAuto();
                }
            });
        }
        loadTerm({});
        //
        tableTerm.search('', false, true);
        //
        tableTerm.row(this).remove().draw(false);
    
        //
        $('#table-term tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableTerm.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalTerm({table: '#table-term', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-term').on('hidden.bs.modal', function () {
            tableTerm.ajax.reload(null, false);
        });
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // user
            if ($('#modal-term').hasClass('show')) {

                // start_date
                if ($('#start_date').val().trim() === '') {
                    disabled = true;
                    $('#start_date--help').html('START DATE REQUIRED')
                } else {
                    $('#start_date--help').html('&nbsp;')
                }
                // End_date
                if ($('#end_date').val().trim() === '') {
                    disabled = true;
                    $('#end_date--help').html('END DATE REQUIRED')
                } else {
                    $('#end_date--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-term').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



