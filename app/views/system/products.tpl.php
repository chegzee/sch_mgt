<?php
$data = $data ?? [];
$term = $data['term'] ?? [];
$termObj = $data['termObj'] ?? [];
$product_images = $data['product_images'] ?? [];
// $classrooms = $data['classrooms'] ?? [];
// $classroomsObj = $data['classroomsObj'] ?? [];
// $valggk = $classrooms[0];
// var_dump($product_images);exit;
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
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <button onclick="modalProducts({table: '#table-products', row: ''}); $('#modal-title').html('New images')"><i class="fa fa-plus"></i> New</button>
            <div class="table-responsive">
                <div class="dataTables_wrapper">
                    <table id="table-product" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build </i></th>
                                <th>Product name</th>
                                <th>image</th>
                                <th>Price</th>
                                <th>level</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div> 
        </div>
    </div>

</div>


<dialog id="product-image_dialog" style="position:absolute;top:0;left:0;height:100%;width:100%;margin-left:auto;background-color:#ccc;text-align:center;z-index:9991;">  
            
    <div class="row">
        <?php 
            foreach($product_images as $k => $v){
                echo '<div class="float:left;margin-top:"12px">
                    <img src="'.$v->picture.'" width="100px" height="100px" ondblclick="setImages(event)" />
                </div>';

            }
        ?>
    </div>
        <div class="col-lg-6"><button  onclick="document.getElementById('product-image_dialog').close();$('#modal-product').modal('show')">Close</button></div>
    
</dialog>  

<!-- productModal -->
<div id="modal-product" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <!-- <div style="position:absolute;top:4px;left:200px;">+120days</div> -->
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Products New/Edit</h5>
                <div id="period_name" style="padding-left:12px;padding-right:12px;font-size:18px;font-wight:bold;background-color:#cfcfcf;color:black;margin-left:8px;"></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav id="modalNav" class="nav nav-tabs nav-gap-x-1">
                    <a class="nav-item nav-link has-icon active" href="#page_1" data-toggle="tab"><i class="fa fa-edit mr-2 fs-10"></i>Products</a>
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
                                                <div style="overflow: hidden; flex: 1; float: left; padding: 5px; cursor: pointer" onclick="document.getElementById('product-image_dialog').show();$('#modal-product').modal('hide')">
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
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-12">
                                <div class="col-xl-6">
                                    <label for="product_code">Product | Service name </label></br>
                                    <input type="text" id="product_code" class="d-none"/>
                                    <input type="text" id="product_name" class="form-control form-control-lg" style="width: 100%;"/>
                                    <code class="small text-danger" id="product_code--help">&nbsp;</code>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-12">
                                    <label for="price">Price </label>
                                    <input type="text" id="price" class="form-control form-control-lg money" style="width: 100%;">
                                    <code class="small text-danger" id="price--help">&nbsp;</code>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-12">
                                    <label for="level">Level</label>
                                    <select type="text" id="level" class="form-control form-control-lg" style="width: 100%;"></select>
                                    <code class="small text-danger" id="level--help">&nbsp;</code>
                                </div>
                                <div class="col-12">
                                    <label for="desc">Description(Max:100) </label>
                                    <input type="text" id="description" class="form-control form-control-lg" style="width: 100%;" maxlength="100">
                                    <code class="small text-danger" id="description--help">&nbsp;</code>
                                </div>
                                <div class="col-12 form-group mg-t-8">
                                    <button class="btn-fill-lg bg-blue-dark btn-hover-yellow" onclick="modalProducts({table: '#table-images', row: ''}); $('#modal-title').html('New Image')"><i class="fa fa-refresh"></i> Reset</button>
                                    <button id="save-product" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button" style="margin-left: auto" onclick="saveProduct({})"><i class="fa fa-save"></i> Save </button>
                                </div>
                            </div>
                            <input id="product_code_old" style="display:none"/>
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
    let product = null;
    saving = false;
    // console.log(term)
    
    let setImages = (e)=>{
        let elem = e.target.src;
        $("#picture--preview").attr('src', elem);
        document.getElementById('product-image_dialog').close();
        $('#modal-product').modal('show');
        // console.log(elem)
    }
    //
    let deleteProduct = (json) => {
        // console.log(json.row)
        product = $(json.table).DataTable();
        let data = product.row(json.row).data(); // data["colName"]
        // console.log(data);return;
        
        if (!confirm('Delete Product: ' + data['product_code'] + ' : ' + data['product_name']  )) {
            return false;
        }
        let obj = {product_code: data.product_code};
        $.post('<?php echo URL_ROOT ?>/system/products/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', obj, function (data) {
            //console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 10000}).show();
            //
            product.ajax.reload(null, false);
            
        }, 'JSON');
    }

    let modalProducts = (json) => { 
        
        product = $(json.table).DataTable();
        //let data = json.row === '' ? {} : ( json.modalAuto ? tableStudents.row(json.row).data() : json.row); // data["colName"]
        let data = json.row === '' ? {} : json.row.data(); // data["colName"]
        // console.log(data);
        $('#product_code').val(data['product_code'] ?? 'AUTO').prop("disabled", (((data['image_code'] ?? '') !== '') && ((data['image_code'] ?? '') !== 'AUTO'))  ? true : false );
        $('#product_code_old').val(data['product_code'] ?? '');
        $('#price').val(number_format(data['price'] ?? ''));
        $('#product_name').val(data['product_name'] ?? '');
        $('#level').append(new Option((data['cat_name'] ?? ''), (data['level'] ?? ''), true, true)).trigger("change");
        let pics = data['picture'] ?? '';
        pics = (pics === '') ? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png' : data['picture'];
        $('#description').val(data['description'] ?? '');
        //
        $('#picture--preview').attr('src', pics);
        //
        $('#picture').val(data['picture'] ?? '');
        //
        $('#modal-product').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    // /////////////////////////////////////////////////////////////////////////////////////////
    product = $("#table-product").DataTable();

    var loadProducts = (json) => {
        // dataTables
        let url = "<?php echo URL_ROOT ?>/system/products/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        // $.post(url, {}, function(data) { console.log(data) }); return;
    
        product.destroy();
    
        product = $('#table-product').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {},
            },
            "columns": [
                {
                    "data": "product_code", "width": 5, "render": function (data, type, row, meta) {
                        return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="btn-outline-success fa fa-cog"></i></a>'+
                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalProduct({table: \'#table-product\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteProduct({table: \'#table-product\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                    }
                },
                {"data": "product_name"},
                {"data": "picture", "width": 5, "render": function(data, type, row, meta){
                    return '<div style="justify-content:center;"><img src="'+ data +'" style="width:30px;height:30px;border-radius:8px;" /></div>'
                    }},
                {"data": "price", "render": function(data, type, row, meta){
                    return number_format(data);
                }},
                {"data": "cat_name"},
                {"data": "description", "render": function(data, type, row, meta){
                    return strEllipsis(data, 20);
                }}
            ],
            "columnDefs": [
                {"targets": [0], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "product_name"]],
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
    let saveProduct = (json) => {
        // console.log(json);return
        let form_data = new FormData()
        let picture = $("#picture--preview").attr('src');
        let product_code = $("#product_code").val(); 
        let product_code_old = $("#product_code_old").val(); 
        let product_name = $("#product_name").val();
        let price = $("#price").val();
        let level = $("#level").val();
        let description = $("#description").val();
        form_data.set('picture', picture);
        form_data.set('product_code', product_code);
        form_data.set('product_code_old', product_code_old);
        form_data.set('product_name', product_name);
        form_data.set('price', price);
        form_data.set('level', level);
        form_data.set('description', description);

        // console.log(form_data);return;
        
        if ($('#save-product').prop('disabled')) return false;
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/products/_save/?user_log=<?php echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false,
            
            beforeSend: function () {
                //
                $('#save-product').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
                $('#save-product').prop({disabled: true});
                //
                saving = true;
            }
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                //
                saving = false;
                //
                $('#save-product').html('Save Changes');
                $('#save-product').prop({disabled: false});
                
                if (data.status) {
                    //
                    new Noty({type: 'success', text: '<h5>SUCCESS!</h5>', timeout: 10000}).show();
                    $('#save-product').html('Save Changes');
                    $('#save-product').prop({disabled: false});
                    product = $("#table-product").DataTable();
                    
                    $("#modal-product").modal('hide')
                    product.ajax.reload(null, false);
                    return false;
                }
                new Noty({type: 'warning', text: '<h5>WARNING</h5>'+ data.message, timeout: 10000}).show();
                $('#save-product').html('Save Changes');
                $('#save-product').prop({disabled: false});
                
                // product = $("#table-product").DataTable();
                
                // $("#modal-product").modal('hide');
                // product.ajax.reload(null, false);
                // setTimeout(() => {
                //     $("#modal-images").modal("hide");
                    
                // }, 2000);
                
            }) 
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                saving = false;
                
                // log data to the console so we can see
                //console.log(errorThrown);
                $('#save-product').html('Save Changes');
                $('#save-product').prop({disabled: false});
                //
                new Noty({type: 'error', text: '<h5>Error</h5>' + errorThrown, timeout: 10000}).show();
                
            });
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
    
        loadProducts({});
        //
        product.search('', false, true);
        //
        product.row(this).remove().draw(false);
        $('#level').select2({
            placeholder: "Select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getCategories",
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
        })
        //
        $('#table-product tbody').on('click', 'td', function () {
            //
            //let data = tableUser.row($(this)).data(); // data["colName"]
            let data = product.row(this);
            let rowId = $(this).parent('tr').index();
            // console.log(data);
        
            if (!data) return;
            if (this.cellIndex != 0) {
                modalProducts({table: '#table-product', row: data});
            }
        });
        ////////////////////
        // $('#modal-images').on('hidden.bs.modal', function () {
        //     tableImages.ajax.reload(null, false);
        // });
    });

</script>