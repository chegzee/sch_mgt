<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Account Lock</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <a href="javascript:void(0)" onclick="modalActlock({table: '#table-actlock', row: ''}); $('#modal-title').html('New Account Lock')" class="btn btn-sm btn-primary mb-3"><i class="fa fa-plus"></i> Add</a>
            <div style="overflow: hidden">
                <table id="table-actlock" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th style="width:50px">Year Start</th>
                        <th style="width:50px">Year End</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- actlockModal -->
<div id="modal-actlock" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Account Lock New/Edit</h5>
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
    
                        <a href="javascript:void(0)" onclick="modalActlock({table: '#table-actlock', row: ''}); $('#modal-title').html('New Account Lock')" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-plus"></i> Reset</a>
                        
                        <div class="row">
                            
                            <div class="col-lg-6 px-3">
    
                                <div class="form-group row">
                                    <label for="year_start" class="col-md-4 col-form-label text-sm-right">Start Date <br><span class="small text-warning">Required (YYYY-MM-DD)</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="year_start" maxlength="10">
                                        <code class="small text-danger" id="year_start--help">&nbsp;</code>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="year_end" class="col-md-4 col-form-label text-sm-right">End Date <br><span class="small text-warning">Required (YYYY-MM-DD)</span></label>
                                    <div class="col-md-8 pr-3">
                                        <input class="form-control form-control-sm" type="text" id="year_end" maxlength="10">
                                        <code class="small text-danger" id="year_end--help">&nbsp;</code>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="year_status" class="col-md-4 col-form-label text-sm-right">Status <br><span class="small text-warning">Required</span></label>
                                    <div class="col-md-8 pr-3">
                                        <div class="input-group">
                                            <table class="table table-bordered">
                                                <?php
                                                if (!empty($data['year_status'])) foreach ($data['year_status'] as $v) {
                                                    echo '
                                                        <tr>
                                                            <td style="width: 30px; background-color: #eeeeff"><input id="status-' . $v . '" type="checkbox" class="status"></td>
                                                            <td><span style="font-size: 85%">' . ucfirst($v) . '</span></td>
                                                        </tr>';
                                                }
                                                ?>
                                            </table>
                                            <input class="form-control form-control-sm" type="hidden" id="year_status">
                                        </div>
                                        <code class="small text-danger" id="year_status--help">&nbsp;</code>
                                    </div>
                                </div>
                            
                            </div>
                        
                        </div>
                        
                        <input type="hidden" id="auto_id" readonly>
                        
                        <div class="form-group mb-2 d-flex">
                            <button id="save-actlock" class="btn btn-success" type="button" style="margin-left: auto" onclick="saveActlock({})"><i class="mdi mdi-file-download"></i> Save Changes</button>
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
    
    let year_status = <?php echo json_encode($data['year_status']) ?>;
    
    //
    let deleteActlock = (json) => {
        //console.log(json);
        let tableActlock = $(json.table).DataTable();
        let data = tableActlock.row(json.row).data(); // data["colName"]
        
        if (!confirm('Delete Account Lock: ' + data['year_start'] + ' : ' + data['year_end'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/finance/actlock/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableActlock.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalActlock = (json) => {
        let tableActlock = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableActlock.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['auto_id'] === undefined) {
            //
        }
        // console.log(data);
        //return;
        $('#auto_id').val(data['auto_id'] ?? '');
        $('#year_start').val(data['year_start'] ?? moment().startOf('month').format('YYYY-MM-DD'));
        $('#year_end').val(data['year_end'] ?? moment().endOf('month').format('YYYY-MM-DD'));
        $('#year_status').val(data['year_status']);
        //
        $('input[type=checkbox].status').prop({checked: false});
        data['year_status'] = data['year_status'] ?? '{}';
        let year_status = JSON.parse(data['year_status']);
        console.log(year_status);
        $.each(year_status, function(i, v) {
            let item = $('#status-' + i);
            console.log(item)
            if (item.length > 0) {
                item.prop({checked: v === '1'});
            }
        });
        
        //
        $('#modal-actlock').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let modalAuto = () => {
        //console.log(window.location.hash, localStorage.getItem('modalOpen'));
        let hash = window.location.hash;
        let auto_id = '<?php echo $data['params']['auto_id'] ?>';
        let modalOpen = localStorage.getItem('modalOpen') !== '';
    
        if (hash !== '' && modalOpen) {
            localStorage.setItem('modalOpen', '');
        
            if (auto_id !== '') {
            
                let tableActlock = $('#table-actlock').DataTable();
            
                tableActlock.columns(2).every(function () {
                    let data = this.data();
                    data.each(function (v, i) {
                        if (v === auto_id) {
                            //console.log(v, i);
                            modalActlock({table: '#table-actlock', row: i});
                            $('#modalNav a[href="#page_1"]').tab('show');
                        
                            return false;
                        }
                    });
                });
            
            } else modalActlock({table: '#table-actlock', row: ''});
        }
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    let saving = false;
    //
    let saveActlock = (json) => {
        //console.log(json);
        let tableActlock = $(json.table).DataTable();
        
        if ($('#save-actlock').prop('disabled')) return false;
        
        //
        let form_data = new FormData();
        
        //
        $.each($('#modal-actlock').find('input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                form_data.append(obj['id'].replace('actlock', ''), ($('#' + obj['id']).prop('checked') ? "1" : "0"));
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
        //console.log(form_data)
        
       // console.log($('#modal-actlock').find('input, select, textarea').length); return;
        
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/finance/actlock/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-actlock').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-actlock').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                //console.log(data);
                //
                saving = false;
                //
                $('#save-actlock').html('Save Changes');
                $('#save-actlock').prop({disabled: false});
                
                if (!data.status) {
                    console.log(data.message);
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                    //
                    //setTimeout(function () {}, 1500);
                }
                //
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                //
                tableActlock.ajax.reload(null, false);
                //
                $('#auto_id').val(data.data.auto_id);
                
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-actlock').html('Save Changes');
                $('#save-actlock').prop({disabled: false});
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
        flatpickr('#year_start', {
            dateFormat: 'Y-m-d',
            allowInput: true,
        });
    
        //
        flatpickr('#year_end', {
            dateFormat: 'Y-m-d',
            allowInput: true,
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableActlock = $("#table-actlock").DataTable();
    
        let loadActlock = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/finance/actlock/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableActlock.destroy();
        
            tableActlock = $('#table-actlock').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "auto_id", "width": 5, "render": function (data, type, row, meta) {
                            return '<a dropdown-toggle" class="dropdown-toggle" type="button" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalActlock({table: \'#table-actlock\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                (userAccess['finance']['admin'] !== '1' ? '' : '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteActlock({table: \'#table-actlock\', row: \'' + meta['row'] + '\'})"><i class="fa fa-trash-alt w-25px"></i> Reverse Item</a>') +
                                '</div>';
                        }
                    },
                    {"data": "year_start"},
                    {"data": "year_end"},
                    {"data": "year_status", "render": function (data, type, row, meta) {
                        // console.log(data)
                        let json_ = JSON.parse(data);
                        let html_ = '';
                        $.each(json_, function(i, v) {
                            html_ += '<div style="float: left; padding: 2px 10px; margin-right: 5px; border: 1px solid #cccccc; border-radius: 5px; background-color: ' + (v === '1' ? '#b4eec0' : '#ffd062') + '; font-size: 85%">' + i + '</div>';
                        });
                        return html_;
                    }},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "desc"],[2, "desc"]],
                "initComplete": function (settings, json) {
                    //console.log(json);
                    modalAuto();
                }
            });
        }
    
        loadActlock({});
    
        //
        tableActlock.search('', false, true);
        //
        tableActlock.row(this).remove().draw(false);
    
        //
        $('#table-actlock tbody').on('click', 'td', function () {
            //
            //let data = tableActlock.row($(this)).data(); // data["colName"]
            let data = tableActlock.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalActlock({table: '#table-actlock', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-actlock').on('hidden.bs.modal', function () {
            tableActlock.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            // auto_id
            if ($('#modal-actlock').hasClass('show')) {
                
                let year_start = moment($('#year_start').val());
                let year_end = moment($('#year_end').val());
    
                // year_start
                if (!moment($('#year_start').val()).isValid()) {
                    disabled = true;
                    $('#year_start--help').html('START DATE INVALID')
                } else if (year_start >= year_end) {
                    disabled = true;
                    $('#year_start--help').html('START|END DATE INVALID')
                } else {
                    $('#year_start--help').html('&nbsp;')
                }
    
                // year_end
                if (!moment($('#year_end').val()).isValid()) {
                    disabled = true;
                    $('#year_end--help').html('END DATE INVALID')
                } else if (year_start >= year_end) {
                    disabled = true;
                    $('#year_end--help').html('START|END DATE INVALID')
                } else {
                    $('#year_end--help').html('&nbsp;')
                }
    
                
                let year_status_ = {};
                $.each(year_status, function(i, v) {
                    year_status_[v] = $('#status-' + v).prop('checked') ? '1' : '';
                });
                $('#year_status').val(JSON.stringify(year_status_));
    
                // year_status
                if ($('#year_status').val().trim() === '') {
                    disabled = true;
                    $('#year_status--help').html('STATUS REQUIRED')
                } else {
                    $('#year_status--help').html('&nbsp;')
                }
                
                //
                if (saving) disabled = true;
                $('#save-actlock').prop({disabled: disabled});
            
            }
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



