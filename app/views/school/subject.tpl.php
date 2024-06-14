<?php
$data = $data ?? [];
echo $data['menu'];
?>

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
                <select id="class_room" class="form-control col-lg-3"  onchange='loadSubject({class_code: $(this).val() })'  style="float:left">
                    <option value="" selected>Select a class</option>
                </select>
                <button style="width:100px;" id="save-subject" onclick="save(event)"><i class="fa fa-save"></i> Save</button>
                <input type="checkbox" id="allow_copy" style="margin-left:2px;width:50px;height:50px;" >
            </div>
            <div class="table-responsive">
                <div id="" class="dataTables_wrapper">
                    <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" id="table-subject">
                        <thead>
                            <tr>
                                <th onclick="addSubject()" width="20px" style="cursor:pointer"><i class="fa fa-plus"></i>add</th>
                                <th>sub_code</th>
                                <th>subject_name</th>
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
    
    let addSubject = ()=>{
        row_id = "add_sub-" + row_number
        // row_id = "add_sub-" + row_number
        let html = '<tr>\
            <td style="border:none;width:20%;">\
                <div class="dropdown">\
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="fas fa-cog"></i></a>\
                    <div class="dropdown-menu" aria-labelledBy="dropdownMenuButton">\
                        <div class="dropdown-item" style="cursor:pointer" onclick="removeRow({elem: event})"><i class="fa fa-trash text-orange-peel"></i> Remove</div>\
                        <div class="dropdown-item" style="cursor:pointer"><i class="fas fa-cogs text-dark-pastel-green"></i> edit</div>\
                        <div class="dropdown-item" style="cursor:pointer"><i class="fas fa-times text-orange-red"></i> close</div>\
                    </div>\
                </div>\
            </td>\
            <td style="border:none;width:30%;"><input type="text" value="AUTO" style="font-size:18px;"></td>\
            <td style="border:none;width:50%;"><select id="'+row_id+'" style="width:100%;"></select></td>\
        </tr>';
        $("#table-subject tbody").append(html);
        $("#"+row_id).select2({
            placeholder: "select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getSubjectType",
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
        row_number++;
        

    }

    let save = ()=>{
        if(!confirm('Click ok to perform this operation')) return;
        localStorage.setItem("class_code", '');
        localStorage.setItem("class_name", '');
        let table_subject = $("#table-subject");
        let table_head = $(table_subject).find("thead");
        let table_body = $(table_subject).find("tbody");
        let head_row = $(table_head).find('tr');
        let body_children = [...$(table_body).children()];
        let head_row_children = [...$(head_row).children()];
        let option = $("#class_room")["0"].selectedOptions[0];
        let class_code = option.value;
        let class_name = option.innerHTML;
        head_row_children.shift()
        let dataSource = [];
        $.each(body_children, (k, v)=>{
            let obj = {class_code: $("#class_room").val()};

            let row_td = [...$(v).children()];
            row_td.shift();
            $.each(head_row_children, (k, v)=>{
                let key = $(v).html();
                let tagname = $(row_td[k]).children()[0].tagName;
                if(tagname === 'INPUT'){
                    let value = $(row_td[k]).find('input[type=text]').val().trim().replace(/[.]/g, '').toUpperCase();
                    obj[key]=value;
                }
                if(tagname === 'SELECT'){
                    let value = $(row_td[k]).find(':selected').val().trim().replace(/[.]/g, '').toUpperCase();
                    obj[key]=value;
                }
                // console.log();return;
                
            })
            dataSource.push(obj);
        })
        let data = {}; 
        data["subject"] = JSON.stringify(dataSource);
        $('#save-subject').html('<i class="fa fa-spinner fa-spin"></i> Save');
        $('#save-subject').prop({disabled: true});
        console.log(data);
        $.post("<?php echo URL_ROOT ?>/school/subject/_save/?user_log=<?php echo $data['params']['user_log'] ?>", data, (data)=>{
            if(data.status === true){
                new Noty({type:"success", text:"<h5>SUCCESS</h5>SUCCESSFUL", timeout: 10000}).show();
                $('#save-subject').html('<i class="fa fa-save"></i> Save');
                $('#save-subject').prop({disabled: false});
                // localStorage.setItem("class_code", class_code);
                // localStorage.setItem("class_name", class_name);
                
                setTimeout(()=>{
                    $("#allow_copy")["0"].checked = false;
                    loadSubject({class_code: class_code})
                    // parent.location.reload();
                }, 1000)
                return;
            }
            new Noty({type:"warning", text:"<h5>WARNING</h5>"+ data.status, timeout: 10000}).show();
            $('#save-subject').html('<i class="fa fa-save"></i> Save');
            $('#save-subject').prop({disabled: true});

        }, 'JSON')
        // console.log(body_children);
    }

    let loadSubject = (json)=>{
        // console.log(json);return
        let allowCopy = $("#allow_copy")["0"].checked;
        //  console.log(allowCopy);
        if(allowCopy){
            let tr = $("#table-subject").find("tr");
            $.each(tr, (k, v)=>{
                let td = $(v).find("td:eq(1) input");
                $(td).val('AUTO');
                // console.log(td);
            });
            return
        }
       
        // return;
        if((json.class_code ?? '') === '')return;
        json._option = json.class_code
        $("#table-subject tbody tr").remove();
        $.post("<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject", json, (data)=>{
            $.each(data, (k, v)=>{
            let row_id = "sub" + row_number;
            let col_id = "sub" + row_number+k;
            let elem = '<td><select style="width:100%;"><option value="'+v.text+'" selected>'+v.text+'</option></select></td>';
            let html = '<tr id="'+row_id+'">\
                <td style="border:none;width:20%;">\
                    <div class="dropdown">\
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="fas fa-cog"></i></a>\
                        <div class="dropdown-menu" aria-labelledBy="dropdownMenuButton">\
                            <div class="dropdown-item" style="cursor:pointer" onclick="delete_({elem: event})"><i class="fa fa-trash text-orange-peel"></i> Delete</div>\
                            <div class="dropdown-item" style="cursor:pointer"><i class="fas fa-cogs text-dark-pastel-green"></i> edit</div>\
                            <div class="dropdown-item"style="cursor:pointer"><i class="fas fa-times text-orange-red"></i> close</div>\
                        </div>\
                    </div>\
                </td>\
                <td style="border:none;width:30%;"><input type="text" value="'+v.id+'" style="font-size:18px;border:none"></td>\
                <td style="border:none;width:50%;"><select id="'+col_id+'" style="width:100%;"><option value="'+v.text+'" selected>'+v.text+'</option></select></td>\
            </tr>';
            $("#table-subject tbody").append(html);
            $("#"+col_id).select2({
                placeholder: "select an option",
                allowClear: true,
                ajax: {
                    url: "<?php echo URL_ROOT ?>/system/systemSetting/getSubjectType",
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
            row_number++
            })
        }, 'JSON')
    }

    
    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");

        $("#"+table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    let delete_ = (e)=>{
        if(!confirm('Click ok to perform this operation')) return;
        let sub_code = $(e.elem.target).closest("tr").find("td:eq(1) input").val();
        let class_code = $("#class_room")["0"].selectedOptions[0].value;
        $.post("<?php echo URL_ROOT ?>/school/subject/_delete/?user_log=<?php echo $data['params']['user_log'] ?>", {subj_code: sub_code}, (data)=>{
            // console.log(data);
            if(data.status !== true){
                new Noty({type:"warning", text: "<h5>WARNING</h5>"+ data.message, timeout: 10000}).show();
                loadSubject({class_code: class_code});
                return;
            }
            new Noty({type:"success", text: "<h5>SUCCESS</h5>"+ data.message, timeout: 10000}).show();
        }, 'JSON')
        // console.log(sub_code)
    }

    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        $("#class_room").select2({
            placeholder: "Select a class",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
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

    
    });

</script>