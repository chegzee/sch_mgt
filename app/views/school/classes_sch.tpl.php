<?php
$data = $data ?? [];
echo $data['menu'];
?>
<!-- tesing azure -->
<div class="main-body">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Class timetable</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card">
        <div class="card-body">
            <div id="" class="row" style="display:flex;">
                <select id="level" class="form-control col-lg-3"  onchange='loadClass({cat_code: $(this).val() })'  style="float:left">
                    <option value="" selected>Select a level</option>
                </select>
                <button style="width:100px;" id="save-classes" onclick="save(event)"><i class="fa fa-save"></i> Save</button>
            </div>
            <div class="table-responsive">
                <div id="" class="dataTables_wrapper">
                    <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" id="table-class">
                        <thead>
                            <tr>
                                <th onclick="addClass()" style="cursor:pointer;" width="20px"><i class="fa fa-plus"></i>add</th>
                                <th>class_code</th>
                                <th>class_name</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- userModal -->


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    var class_subject = '';
    var row_number = 1;
    var row_id = '';
    
    let addClass = ()=>{
        if($("#level").val() === '')return;
        row_id = "sub-" + row_number
        let html = '<tr id="'+row_id+'">\
            <td style="border:none;">\
                <div class="dropdown">\
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="fas fa-cog"></i></a>\
                    <div class="dropdown-menu" aria-labelledBy="dropdownMenuButton">\
                        <div class="dropdown-item" onclick="removeRow({elem: event})"><i class="fa fa-trash text-orange-peel"></i> Remove</div>\
                        <div class="dropdown-item"><i class="fas fa-cogs text-dark-pastel-green"></i> edit</div>\
                        <div class="dropdown-item"><i class="fas fa-times text-orange-red"></i> close</div>\
                    </div>\
                </div>\
            </td>\
            <td><input type="text" value="AUTO" style="font-size:18px;"></td>\
            <td><input type="text" style="font-size:18px;min-width:300px;" placeholder="Type class name"></td>\
        </tr>';
        $("#table-class tbody").append(html);
        row_number++;
        

    }

    let save = ()=>{
        localStorage.setItem("cat_code", "");
        localStorage.setItem("cat_name", "");
        let table_classes = $("#table-class");
        let table_head = $(table_classes).find("thead");
        let table_body = $(table_classes).find("tbody");
        let head_row = $(table_head).find('tr');
        let body_children = [...$(table_body).children()];
        let head_row_children = [...$(head_row).children()];
        let option = $("#level")["0"].selectedOptions[0];
        let cat_code = option.value;
        let cat_name = option.innerHTML;
        head_row_children.shift()
        let dataSource = [];
        $.each(body_children, (k, v)=>{
            let obj = {cat_code: $("#level").val()};
            let row_td = [...$(v).children()];
            row_td.shift();
            $.each(head_row_children, (k, v)=>{
                let key = $(v).html();
                let value = $(row_td[k]).find('input[type=text]').val().trim().replace(/[.]/g, '').toUpperCase();
                obj[key]=value;
            })
            dataSource.push(obj);
        })
        // console.log(dataSource);
        // return;
        let data = {}; 
        data["classes"] = JSON.stringify(dataSource);
        $('#save-classes').html('<i class="fa fa-spinner fa-spin"></i> Save');
        $('#save-classes').prop({disabled: true});
        $.post("<?php echo URL_ROOT ?>/school/classesSch/_save/?user_log=<?php echo $data['params']['user_log'] ?>", data, (data)=>{
            if(data.status === true){
                new Noty({type:"success", text:"<h5>SUCCESS</h5>SUCCESSFUL", timeout: 10000}).show();
                $('#save-classes').html('<i class="fa fa-save"></i> Save');
                $('#save-classes').prop({disabled: false});
                localStorage.setItem("cat_code", cat_code);
                localStorage.setItem("cat_name", cat_name);
                setTimeout(()=>{
                    parent.location.reload();
                }, 1000)
                return;
            }
            new Noty({type:"warning", text:"<h5>WARNING</h5>"+ data.status, timeout: 10000}).show();
        }, 'JSON')
    }

    let loadClass = (json)=>{
        // console.log(json);return
        if((json.cat_code ?? '') === '')return;
        json._option = json.cat_code
        $("#table-class tbody tr").remove();
        $.post("<?php echo URL_ROOT ?>/system/systemSetting/getClasses", json, (data)=>{
            // console.log(data)
            $.each(data, (k, v)=>{
                let row_id = "sub" + row_number;
                    
                let html = '<tr id="'+row_id+'">\
                    <td style="border:none;">\
                        <div class="dropdown">\
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="fas fa-cog"></i></a>\
                            <div class="dropdown-menu" aria-labelledBy="dropdownMenuButton">\
                                <div class="dropdown-item" onclick="deleteClass({elem: event})" style="cursor:pointer"><i class="fa fa-trash text-orange-peel"></i> Delete</div>\
                                <div class="dropdown-item"><i class="fas fa-cogs text-dark-pastel-green"></i> edit</div>\
                                <div class="dropdown-item"><i class="fas fa-times text-orange-red"></i> close</div>\
                            </div>\
                        </div>\
                    </td>\
                    <td><input type="text" value="'+v.class_code+'" style="font-size:18px;border:none"></td>\
                    <td><input type="text" value="'+v.class_name+'" style="font-size:18px;min-width:300px;border:none" placeholder="Subject name"></td>\
                </tr>';
                $("#table-class tbody").append(html);
                    row_number++
            })
        }, 'JSON')
    }

    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");

        $("#"+table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    let deleteClass = (json)=>{
        if(!confirm("You are about to delete a class")) return;
        let class_code = $(json.elem.target).parents('tr').find("td:eq(1) input").val();
        let option = $("#level")["0"].selectedOptions[0];
        let cat_code = option.value;
        let cat_name = option.innerHTML;
        $.post("<?php echo URL_ROOT ?>/school/classesSch/_delete/?user_log=<?php echo $data['params']['user_log'] ?>", {class_code: class_code}, (data)=>{
            // console.log(data);
            if(data.status === true){
                new Noty({type:"success", text:"<h5>SUCCESSFUL</h5>", timeout: 10000}).show();
                localStorage.setItem("cat_code", cat_code);
                localStorage.setItem("cat_name", cat_name);
                setTimeout(()=>{
                    parent.location.reload();
                }, 1000)
                return;
            }
            
            new Noty({type:"warning", text:"<h5>WARNING</h5>"+data.message, timeout: 10000}).show();
            
        }, 'JSON')
        // console.log(class_code);
    }

    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
        
        ////
        $('#level').select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getCategories/?user_log=<?php echo $data['params']['user_log'] ?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log(params)
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

        let code = localStorage.getItem("cat_code") ?? '';
        let n = localStorage.getItem("cat_name") ?? '';
        //
        if(code !== ''){
            $('#level').append(new Option(n, code, true, true)).trigger('change');
        }
        //
        // let checkForm = new timer();
        // checkForm.start(function () {
        //     //
        //     checkForm.stop();
        
        //     checkForm.start();
        
        // }, 500, true);
    
    });

</script>