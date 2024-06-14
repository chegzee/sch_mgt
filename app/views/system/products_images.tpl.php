<?php
$data = $data ?? [];
$term = $data['term'] ?? [];
$termObj = $data['termObj'] ?? [];
// $classrooms = $data['classrooms'] ?? [];
// $classroomsObj = $data['classroomsObj'] ?? [];
// $valggk = $classrooms[0];
// var_dump($term['start_date']);exit;
echo $data['menu'];
?>

<div class="main-body">
    <style>
        .container_flip{
            position:relative;
            border:1px solid black;
            height:100%;
            width:51px;
            text-align:center;
        }
        .rotate{
            position:absolute;
            bottom:15px;
            writing-mode: vertical-lr;
        }
        .flipvertical{
            font-size:18px;
            font-weight: bold;
            transform: rotate(180deg);
        }
        select:required:invalid {
        color: #666;
        }
        option[value=""][disabled] {
            display: none;
        }
        option {
            color: #000;
        }
    </style>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Images</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalImages({table: '#table-images', row: ''}); $('#modal-title').html('New images')"><i class="fa fa-plus"></i> New</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-images" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build </i></th>
                                <th>name</th>
                                <th>image</th>
                                <th>type</th>
                                <th>size</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- studentrModal -->
<div id="modal-images" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <!-- <div style="position:absolute;top:4px;left:200px;">+120days</div> -->
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Images New/Edit</h5>
                <div id="period_name" style="padding-left:12px;padding-right:12px;font-size:18px;font-wight:bold;background-color:#cfcfcf;color:black;margin-left:8px;"></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Images</a>
                </nav>
                <div class="tab-content">
                    
                    <div class="tab-pane show active" id="page_1">
                        <div class="d-none">
                            <input type="text" id="image_code" style="width: 100%"/>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-12 px-3 mt-4 form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="w-100">
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="$('#picture-file').click()">
                                                    <img id="picture--preview" src="" alt="[Click] to Upload Picture" style="min-width:150px;max-width:150px;">
                                                </div>
                                            </div>
                                        </div>
                                        <code class="small text-danger" id="picture--help">&nbsp;</code>
                                    </div>
                                    <input type="file" id="picture-file" accept="image/svg" onchange="imageChange({'event': event, preview:'picture', 'items': [$('#name').val()], product_image: true})" style="display:none">
                                    <input type="hidden" id="picture" readonly>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-12 form-group">
                                <label for="name">name </label>
                                <input type="text" id="name" style="width: 100%;border:none"/>
                                <code class="small text-danger" id="name--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-12 form-group">
                                <label for="type">Type </label>
                                <input type="text" id="type" style="width: 100%;border:none">
                                <code class="small text-danger" id="type--help">&nbsp;</code>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-12 form-group">
                                <label for="size">Size</label>
                                <input type="text" id="size" style="width: 100%;border:none"/>
                                <code class="small text-danger" id="size--help">&nbsp;</code>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalImages({table: '#table-images', row: ''}); $('#modal-title').html('New Image')"><i class="fa fa-refresh"></i> Reset</button>
                                <button id="save-images" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveImages({})"><i class="fa fa-save"></i> Save </button>
                            </div>
                            <input id="image_code_old" style="display:none"/>
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
    let term = <?php echo json_encode($data['term']) ?>;
    let termObj = <?php echo json_encode($data['termObj']) ?>;
    let tableImages = null;
    saving = false;
    // console.log(term)
    
    
    //
    let deleteImages = (json) => {
        // console.log(json.row)
        tableImages = $(json.table).DataTable();
        let data = tableImages.row(json.row).data(); // data["colName"]
        // console.log(data);return;
        
        if (!confirm('Delete Student: ' + data['std_code'] + ' : ' + data['first_name'] + " " + data['last_name'])) {
            return false;
        }
        
        $.post('<?php echo URL_ROOT ?>/system/productsImages/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            tableImages.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalImages = (json) => { 
        
        tableImages = $(json.table).DataTable();
        //let data = json.row === '' ? {} : ( json.modalAuto ? tableStudents.row(json.row).data() : json.row); // data["colName"]
        let data = json.row === '' ? {} : json.row.data(); // data["colName"]
        // console.log(data);
        $('#image_code').val(data['image_code'] ?? 'AUTO').prop("disabled", (((data['image_code'] ?? '') !== '') && ((data['image_code'] ?? '') !== 'AUTO'))  ? true : false );
        $('#image_code_old').val(data['image_code'] ?? '');
        $('#name').val(data['name'] ?? '');
        $('#type').val(data['type'] ?? '');
        $('#size').val(data['size'] ?? '');
        let pics = data['picture'] ?? '';
        pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
        //
        $('#picture--preview').attr('src', pics);
        //
        $('#picture').val(data['picture'] ?? '');
        //
        $('#modal-images').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    tableImages = $("#table-images").DataTable();

    var loadImages = (json) => {
    
        // dataTables
        let url = "<?php echo URL_ROOT ?>/system/productsImages/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        // $.post(url, {}, function(data) { console.log(data) }); return;
    
        tableImages.destroy();
    
        tableImages = $('#table-images').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {},
            },
            "columns": [
                {
                    "data": "image_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="btn-outline-success fa fa-cog"></i></a>'+
                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalImages({table: \'#table-images\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteImages({table: \'#table-images\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                    }
                },
                {"data": "name"},
                {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                    return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                    }},
                {"data": "type"},
                {"data": "size"},
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "desc"]],
            "initComplete": function (settings, json) {
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                //  console.log(json);
                
                // let searchButton = $('<button type="button" class="btn btn-sm btn-primary text-white" style="margin-left: -5px"><i class="fa fa-play"></i></button>').click(function() { tableBank.search(this.previousElementSibling.value).draw() });
                //     $("#table-bank_filter.dataTables_filter input")
                //     .unbind()
                //     .bind("input, keyup", function(e) {
                //         if( (e.charCode || e.keyCode || e.which) === 13) tableBank.search(this.value).draw();
                //         e.preventDefault();
                //     }).prop({placeholder: 'Press [Enter] Key'})
                //     .after(searchButton).prop({autocomplete: 'off'});
            }
        });
    }

    
    // /////////////////////////////////////////////////////////////////////////////////////////
    //
    let saveImages = (json) => {
        // console.log(json);return
        let form_data = new FormData()
        $.each($('#modal-images #page_1').find('input, select, textarea'), function (i, obj) {
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
        
        if ($('#save-images').prop('disabled')) return false;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/productsImages/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-images').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-images').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                //
                saving = false;
                //
                $('#save-images').html('Save Changes');
                $('#save-images').prop({disabled: false});
                
                if (!data.status) {
                    //
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                    return false;
                }
                new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
                $('#save-images').html('Save Changes');
                $('#save-images').prop({disabled: false});
                
                tableImages = $("#table-images").DataTable();
                
                $("#modal-images").modal('hide')
                tableImages.ajax.reload(null, false);
                // setTimeout(() => {
                //     $("#modal-images").modal("hide");
                    
                // }, 2000);
                
            }) 
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-images').html('Save Changes');
                $('#save-images').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
    
        loadImages({});
    
        //
        tableImages.search('', false, true);
        //
        tableImages.row(this).remove().draw(false);
    
        //
        $('#table-images tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = tableImages.row(this);
            let rowId = $(this).parent('tr').index();
            // console.log(data);
        
            if (!data) return;
            if (this.cellIndex != 0) {
                modalImages({table: '#table-images', row: data});
            }
        });

          
        ////////////////////
        // $('#modal-images').on('hidden.bs.modal', function () {
        //     tableImages.ajax.reload(null, false);
        // });
    });

</script>