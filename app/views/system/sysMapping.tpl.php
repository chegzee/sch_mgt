<?php
$data = $data ?? [];
echo $data['menu'];
$categories_object = $data["categories_object"];
$term = $data["term"];
$classes = $data["classes"];

// var_dump($term['code']);exit;
?>

<div class="main-body">
    <style>
        th{
            min-width: 200px
        }
        .custom-select {
            position: relative;
            font-family: Arial;
        }


    </style>
    
    <!-- Breadcrumb -->
    <!-- <nav aria-label="breadcrumb" class="main-breadcrumb"> -->
        <!-- <div style="background-color:white;" >
            <select id="level_list" class="form-control" style="width:40%;border:1px solid blue;font-size:24px">
                <option value="" selected>Select a class</option>
                <?php 
                    foreach($classes as $k => $v){
                        echo '<option onclick="loadClasses({elem:event, target:1})" data-class_code="'.$v->class_code.'" >'.($v->cat_name).'-'.$v->class_name.' </option>';
                    }
                ?>
            </select>
        </div> -->
        <div style="background-color:white;" >
            <select id="classes" class="form-control" style="width:40%;border:1px solid blue;font-size:24px">
                <option value="" selected>Select a class</option>
            </select>
        </div>
    <!-- /Breadcrumb -->
    <div class="card card-style-1">
        <div class="card-body">
            <div class="row" id="classes">
                <?php 
                    foreach($data['categories'] as $k => $v){
                        if($v->status === "1"){
                            echo '
                                <div class="sss col-lg-12" id="card-'.strtolower($v->alpha).'" style="display:none">
                                    <div class="table-responsive">
                                        <div class="dataTables_wrapper" style="height: 500px;overflow-y: auto;border-bottom:2px solid black;margin-top:8px;">
                                            <div style="display:flex;justify-content: space-between;">
                                                <div class="row"  style="width:60%;padding-left:8px;">
                                                    <input type="text" class="form-control" id="" placeholder="Search by class name"  style="width:40%;font-size:18px;" /><button onclick="searchClass({elem: event})" style="height:36.5px">Search</button>
                                                </div>
                                                <h2 id="table-'.strtolower($v->alpha).'-title" data-cat_code="'.$v->cat_code.'">'.strtoUpper($v->cat_name).'</h2>
                                            </div>
                                            <table id="table-'.strtolower($v->alpha).'" class="table table-striped table-bordered table-sm">
                                                <thead>
                                                    <tr> 
                                                        <th style="width:1em">
                                                            <button id="save-'.$v->alpha.'" class="'.$v->cat_code.'" onclick="save({elem: event})" data-action="'.$v->action.'" data-category_name="'.$v->cat_name.'" ><i class="fa fa-save"></i></button>
                                                        </th>
                                                        <th>Subject</th>
                                                        <th>Teacher</th>
                                                        '.(($v->alpha === 'JSS1' || $v->alpha === 'JSS2' || $v->alpha === 'JSS3') ? '<th><input type="checkbox" id="jnr_status_subj" onclick="checker({elem: event})"/><span> Status</span></th>' : '').'
                                                        '.(($v->alpha === 'PLAY' || $v->alpha === 'NUR' || $v->alpha === 'PRY1' || $v->alpha === 'PRY2' || $v->alpha === 'PRY3' || $v->alpha === 'PRY4' || $v->alpha === 'PRY5' || $v->alpha === 'PRY6') ? '<th><input type="checkbox" id="jnr_status_subj" onclick="checker({elem: event})"/><span> Status</span></th>' : '').'
                                                        '.(($v->alpha === 'SSS1' || $v->alpha === 'SSS2' || $v->alpha === 'SSS3') ? '<th><input type="checkbox" id="science" onclick="checker({elem: event})"/><span> Status</span></th>' : '').'
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            ';

                        }
                    }
                ?>
            </div>
        </div>
    </div>

</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    
    let counter = 0;
    let saving = false;
    let action = null;
    let categories = <?php echo json_encode($data['categories']) ?>;
    let categories_object = <?php echo json_encode($data['categories_object']) ?>;
    let term = <?php echo json_encode($data['term']) ?>;
    let class_code = '';
    // console.log(term.code)
    //
    let modalDisplay = (json) => {
        let row_index = $(json.elem.target).parents('tr');
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        //
        if (data['username'] === undefined) {
            //
        }
        
        $('#username_old').val(data['username'] ?? '');
        $('#username').val(data['username'] ?? '');
        $('#status').prop({checked: data['status'] === '1' || !data['username']});
        $('#first_name').val(data['first_name']);
        $('#last_name').val(data['last_name']);
        $('#password').val(data['password'] ?? passwordGenerate({elem: '#password'}));
        $('#password1').val(data['password']);
        //
        $('#address').val(data['address']);
        $('#phone').val(data['phone']);
        //
        data['group_code'] = data['group_code'] ?? '';
        $('#group_code').append(new Option(data['group_name'], data['group_code'], true, true)).trigger('change');
        
        //
        $('#picture--preview').attr('src', data['picture'] ?? '<?php echo ASSETS_ROOT ?>/images/gallery/man.png');
        //
        $('#picture').val(data['picture'] ?? '');
        
        //
        $('input[type=checkbox].access').prop({checked: false});
        let access = JSON.parse(data['access'] ?? '<?php echo json_encode(USER_ACCESS) ?>');
        
        //
        $('#modal-user').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }

    let checker = (json)=>{
        
        let table_id, input, element, target_id;
        element = json.elem.target;
        target_id = $(element).prop('id');
        table_id = $(element).closest('table').prop('id');
        console.log(table_id, target_id)
        if($(element).prop("checked")){
            $("#" + table_id).find('.'+target_id).prop({checked: true});
        

        }else{
           input =  $("#" + table_id).find('.'+target_id);
           $(input).prop({"checked": false})
        }
    }
    
    //
    let save = (json) => {
        if(!confirm('Click ok to perform this operation')) return;
        let save_btn, title, id, class_code, cat_name;
        save_btn = $(json.elem.target).closest('button');
        id = save_btn['0'].id;
        title = save_btn['0'].className;
        class_code = $("#classes")["0"].selectedOptions[0].value;
        cat_name = $("#classes")["0"].selectedOptions[0].innerText;
        // console.log(cat_name);
        let category_name = $(save_btn)["0"].dataset.category_name
        let rows = [];
        let obj = {};
        let table = $(json.elem.target).closest('table')['0'];
        
        let elem = [...document.querySelector('#'+table.id + ' tbody').children];
        // console.log(table.id);return;
        elem.forEach((tr, i)=>{
            obj = {};
            let td_collection = [...tr.children];
            // let v1 = td_collection[1].children[0].selectedOptions
            let v2 = td_collection[1].children[0].selectedOptions
            let v3 = td_collection[2].children[0].selectedOptions
            if(v2['length'] === 0 || v3['length'] === 0) return;
            obj['cat_code'] = title;
            obj['class_code'] = class_code;
            td_collection.forEach((td, i)=>{
                if(i === 0) return;
                let element = td.children[0];
                // console.log(element);return;
                if(element.tagName === 'INPUT'){
                    let v = String(element.id).substring(0, String(element.id).indexOf('-'));
                    if(v === 'science'){
                        let op = element.checked ? 'COMPULSORY' : 'ELECTIVE';
                        obj[v] = op;
                    }
                    if(v === 'jnr_status_subj'){
                        let op = element.checked ? 'COMPULSORY' : 'ELECTIVE';
                        obj[v] = op;
                    }
                    // console.log(v)

                }
                ////
                if(element.tagName === 'SELECT'){
                    let op = element.selectedOptions['0'];
                    let v = String(element.id).substring(0, String(element.id).indexOf('-'));
                    obj[v] = op.value;
                    
                }
            });
                rows.push(obj);
        })

        let data = {data: JSON.stringify(rows), class_code:class_code, term: term.code};
        // console.log(data);return;
        if(rows.length === 0)return false;
        if ($('#'+ id).prop('disabled')) return false;
        $('#'+id).html('<i class="fa fa-spinner fa-spin" style="white"></i> Save Changes');
        $('#'+ id).prop({disabled: true});
        //  console.log(data);
        $.post('<?php echo URL_ROOT ?>/system/sysMapping/_save/?user_log=<?php echo $data['params']['user_log'] ?>', data, function (data) {
            //
            // console.log(data)
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.message, timeout: 10000}).show();
                return;
            }
            //
            // console.log(data.status)
           saving = true;
            new Noty({type: 'success', text: '<h5>Success</h5>', timeout: 1000}).show();
            loadClasses({class_code: class_code, cat_name:cat_name, target:"save"});
            $('#'+id).html('<i class="fa fa-save"></i> Save');
            $('#'+ id).prop({disabled: false});
            // parent.location.reload();
        
        },'JSON');
        
        
    }

    let removeRow = (json)=>{
        let row_index = $(json.elem.target).parents('tr').index();
        let table = $($(json.elem.target).parents('table')).prop("id");

        $("#"+table + ' tbody tr:eq(\''+ row_index +'\')').remove();

    }

    let deleteRow = (json) =>{
        if(!confirm('Click ok to perform this operation')) return;
        let v = json.elem.target.dataset.category_autoid;
        let v2 = json.elem.target.dataset.category_name;
        let subj_code = json.elem.target.dataset.subject_name_code;
        class_code = $("#classes")["0"].selectedOptions[0].value;
        // console.log(subj_code);return;
        $.post('<?php echo URL_ROOT ?>/system/sysMapping/_delete/?user_log=<?php echo $data['params']['user_log'] ?>', {auto_id: v, subj_code: subj_code} , (data)=>{
            // console.log(data);
            if (!data.status) {
                new Noty({type: 'warning', text: '<h5>WARNING!</h5>' + data.message, timeout: 10000}).show();
                return false;
            }
            //
            new Noty({type: 'success', text: '<h5>SUCCESS</h5>', timeout: 10000}).show();
            loadClasses({cat_name: v2, class_code: class_code, target: "delete"});
        }, "JSON")

    }

    // let add_row = (json) =>{
    //     // console.log(class_code)
    //     //let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
    //     let table_id;
    //     let table = $(json.elem.target).closest('table')['0'];
    //     let title = table.previousElementSibling.children[1].innerText;
    //     // let title = table.previousElementSibling.innerText;
    //     //  console.log(title);
    //     let tbody = $('#'+table.id).find('tbody');
    //     table_id = table.id;
    //     // console.log(tbody);
    //     counter++;
        
    //     let row2 = '<tr class="play-'+ counter +' ">\
    //         <td>\
    //             <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
    //             <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
    //                 <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \''+ json.table +'\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
    //                 <a class="dropdown-item" href="javascript:void(0)" onclick="removeRow({elem: event})"><i class="fas fa-times text-orange-red"></i>Remove</a>\
    //             </div>\
    //         </td>\
    //         <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
    //         <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
    //         "'+((table.id === "table-jss1" || table.id === "table-jss2" || table.id === "table-jss3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg \" style=\"width: 100%;\"/></td>" : "")+'"\
    //         "'+((table.id === "table-sss1" || table.id === "table-sss2" || table.id === "table-sss3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"science-"+counter+"\" class=\"science form-control form-control-lg \" style=\"width: 100%;\"/></td>" : "")+'"\
    //     </tr>';
    //     tbody.prepend(row2);
    //     //
    //     $('#subject_name_code-'+ counter).select2({
    //         placeholder: "Select an option",
    //         allowClear: true,
    //         ajax: {
    //             url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
    //             type: "post",
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 return {
    //                     searchTerm: params.term,
    //                     _option: class_code_
    //                 };
    //             },
    //             processResults: function (response) {
    //                 // console.log(response);
    //                 return { results: response };
    //             },
    //             cache: true
    //         }
    //     });
    //     //
    //     $('#teacher_code-'+ counter).select2({
    //         placeholder: "Select an option",
    //         allowClear: true,
    //         ajax: {
    //             url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
    //             type: "post",
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 return {
    //                     searchTerm: params.term,
    //                     _option: 'select'
    //                 };
    //             },
    //             processResults: function (response) {
    //                 //console.log(response);
    //                 return { results: response };
    //             },
    //             cache: true
    //         }
    //     });
        
    // }

    //search by class name
    let searchClass = (json)=>{
        let elem = $(json.elem.target)['0'];
        let table = $($(elem)['0'].offsetParent).find('table')["0"];
        let tr = $(table).find('tbody tr')
        let filter_value = elem.previousElementSibling.value;
        
        $.each(tr, (k, v)=>{
            let td = $(v).find('td:eq(1)')["0"];
            let select = td.children[0];
            let option = $(select).find('option')["0"]
            let text = option.firstChild.data;
            if(text.indexOf(filter_value) > -1){
                $(v).css("display", "")
            }
            if(text.indexOf(filter_value) === -1){
                $(v).css("display", "none")
            }
        })
    }

    //todo search by teacher
    let searchTeacher = (json)=>{
        //todo
    }

    function loadPlay(param){
        // $($($($(table)["0"]).find('tbody')).find('tr')).remove();
        //let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-play tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            // console.log(data)
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            // console.log(data)
            $.each(data.data, (i, v)=>{ 
                counter++
                let row2 = '<tr class="play-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-play\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-play\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+ counter+ "\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                // $("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: "PLAY" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)

            })
            // loadNursery();
        }, "JSON")

    }

    function loadNursery(param){
       // let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-nur tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            //
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="nursery-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-play\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-play\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                // $("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: "NURSERY" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
            // loadPrimary1();
        }, "JSON")

    }

    function loadPrimary1(param){
        let tbody = $('#table-pry1 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="pry1-'+counter+' ">\
                    <td id="">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-pry1\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-pry1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                // $("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                //
                $('#subject_name-code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "PRIMARY ONE" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
        }, "JSON")

    }
    // //
    function loadPrimary2(param){
        let tbody = $('#table-pry2 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="pry2-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-pry2\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-pry2\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                // $("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');

                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "PRIMARY TWO" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
        }, "JSON")
    }
    //
    function loadPrimary3(param){
        //let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        // console.log(param);return;
        let tbody = $('#table-pry3 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{
                ++counter
                // console.log(data);
                let row2 = '<tr class="pry3-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-pry3\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-pry3\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                //$("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "PRIMARY THREE" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
        }, "JSON")
    }
    //
    function loadPrimary4(param){
        //let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-pry4 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="pry4-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-pry4\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-pry4\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "PRIMARY FOUR" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
        }, "JSON")
    }
    //
    function loadPrimary5(param){
       // let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-pry5 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="pry5-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-pry5\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-pry5\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "PRIMARY FIVE" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
        }, "JSON")
    }
    //
    function loadPrimary6 (param){
       // let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-pry6 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="pry6-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-pry6\', elem: event })"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-pry6\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "PLAY" || v.alpha === "NUR" || v.alpha === "PRY1" || v.alpha === "PRY2" || v.alpha === "PRY3" || v.alpha === "PRY4" || v.alpha === "PRY5" || v.alpha === "PRY6") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                //$("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                // $("#class_name_code-"+ counter).select2({
                //     placeholder: "Select an option",
                //     allowClear: true,
                //     ajax: {
                //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                //         type: "post",
                //         dataType: 'json',
                //         delay: 250,
                //         data: function (params) {
                //             console.log(params)
                //             return {
                //                 searchTerm: params.term,
                //                 _option: 'select'
                //             };
                //         },
                //         processResults: function (response) {
                //             //console.log(response);
                //             return { results: response };
                //         },
                //         cache: true
                //     }
                // });
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "PRIMARY SIX" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
        }, "JSON")
    }
    //
    function loadJss1 (param){
       // let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-jss1 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            //    console.log(data)
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                let row2 = '<tr class="jss1-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-jss1\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" href="#"  onclick="deleteRow({table: \'#table-jss1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "JSS1" || v.alpha === "JSS2" || v.alpha === "JSS3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            // console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
            })
            
            // jss2();
        }, "JSON")
    }
    //
    function loadJss2 (param){
      //  let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-jss2 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="jss2-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-jss1\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item"  data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" href="#"  onclick="deleteRow({table: \'#table-jss1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "JSS1" || v.alpha === "JSS2" || v.alpha === "JSS3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                //$("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                // $("#class_name_code-"+ counter).select2({
                //     placeholder: "Select an option",
                //     allowClear: true,
                //     ajax: {
                //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                //         type: "post",
                //         dataType: 'json',
                //         delay: 250,
                //         data: function (params) {
                //             console.log(params)
                //             return {
                //                 searchTerm: params.term,
                //                 _option: "select"
                //             };
                //         },
                //         processResults: function (response) {
                //             //console.log(response);
                //             return { results: response };
                //         },
                //         cache: true
                //     }
                // });
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "JUNIOR SECONDARY TWO" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
            // jss2();
        }, "JSON")
    }
    //
    function loadJss3 (param){
       // let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-jss3 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="jss3-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-jss1\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#"  data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'"  onclick="deleteRow({table: \'#table-jss1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "JSS1" || v.alpha === "JSS2" || v.alpha === "JSS3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"jnr_status_subj-"+counter+"\" class=\"jnr_status_subj form-control form-control-lg\" style=\"width: 100%;\" "+ (v.jnr_status_subj === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
               // $("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                // $("#class_name_code-"+ counter).select2({
                //     placeholder: "Select an option",
                //     allowClear: true,
                //     ajax: {
                //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                //         type: "post",
                //         dataType: 'json',
                //         delay: 250,
                //         data: function (params) {
                //             // console.log(params)
                //             return {
                //                 searchTerm: params.term,
                //                 _option: "select"
                //             };
                //         },
                //         processResults: function (response) {
                //             //console.log(response);
                //             return { results: response };
                //         },
                //         cache: true
                //     }
                // });
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
            // jss2();
        }, "JSON")
    }
    
    // //
    function loadSSS1 (param){
        //let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-sss1 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="sss1-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-jss1\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#"  data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-jss1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "SSS1" || v.alpha === "SSS2" || v.alpha === "SSS3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"science-"+counter+"\" class=\"science form-control form-control-lg\" style=\"width: 100%;\" "+ (v.science === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                //$("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                // $("#class_name_code-"+ counter).select2({
                //     placeholder: "Select an option",
                //     allowClear: true,
                //     ajax: {
                //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                //         type: "post",
                //         dataType: 'json',
                //         delay: 250,
                //         data: function (params) {
                //             // console.log(params)
                //             return {
                //                 searchTerm: params.term,
                //                 _option: 'select'
                //             };
                //         },
                //         processResults: function (response) {
                //             //console.log(response);
                //             return { results: response };
                //         },
                //         cache: true
                //     }
                // });
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "SENIOR SECONDARY ONE" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
            // jss2();
        }, "JSON")
    }
    // //
    function loadSSS2(param){
       // let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-sss2 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="sss2-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-jss1\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#"  data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-jss1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "SSS1" || v.alpha === "SSS2" || v.alpha === "SSS3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"science-"+counter+"\" class=\"science form-control form-control-lg\" style=\"width: 100%;\" "+ (v.science === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                //$("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                // $("#class_name_code-"+ counter).select2({
                //     placeholder: "Select an option",
                //     allowClear: true,
                //     ajax: {
                //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                //         type: "post",
                //         dataType: 'json',
                //         delay: 250,
                //         data: function (params) {
                //             console.log(params)
                //             return {
                //                 searchTerm: params.term,
                //                 _option: "select"
                //             };
                //         },
                //         processResults: function (response) {
                //             //console.log(response);
                //             return { results: response };
                //         },
                //         cache: true
                //     }
                // });
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
            // jss2();
        }, "JSON")
    }
    // //
    function loadSSS3(param){
        //let class_code_ = $("#level_list")["0"].selectedOptions[0].dataset.class_code;
        let tbody = $('#table-sss3 tbody');
        $($(tbody).find("tr")).remove();
        let url = "<?php echo URL_ROOT ?>/system/sysMapping/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        $.post(url, {class_code: param}, (data)=>{
            ////
            if(saving){
                $($($(tbody).find('tr'))).remove();
                saving = false;
            }
            $.each(data.data, (i, v)=>{  
                ++counter
                // console.log(data);
                let row2 = '<tr class="sss3-'+counter+' ">\
                    <td id="segun">\
                        <a id="dropdownMenuButton" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="z-index:99">\
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a>\
                            <a class="dropdown-item" href="javascript:void(0)" onclick="modalDisplay({table: \'#table-jss1\', elem: event})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>\
                            <a class="dropdown-item" href="#" data-category_name="'+v.cat_name+'" data-category_autoid="'+v.auto_id+'" data-subject_name_code="'+v.subject_name_code+'" onclick="deleteRow({table: \'#table-jss1\', elem: event})"><i class="fas fa-trash text-orange-peel"></i>Delete</a>\
                        </div>\
                    </td>\
                    <td style="height: fit-content;"><select id="subject_name_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    <td style="height: fit-content;"><select id="teacher_code-'+counter+'" class="form-control form-control-lg" style="width: 100%;"></select></td>\
                    "'+((v.alpha === "SSS1" || v.alpha === "SSS2" || v.alpha === "SSS3") ? "<td style=\"height: fit-content;\"><input type=\"checkbox\" type=\"checkbox\" id=\"science-"+counter+"\" class=\"science form-control form-control-lg\" style=\"width: 100%;\" "+ (v.science === 'COMPULSORY' ? 'checked' : '' )+" /></td>" : "")+'"\
                </tr>';
                tbody.append(row2);
                //$("#class_name_code-"+ counter).append(new Option(v.class_name ?? '', v.class_name_code ?? '', true, true)).trigger('change');
                $("#subject_name_code-"+ counter).append(new Option(v.subject_name ?? '', v.subject_name_code ?? '', true, true)).trigger('change');
                $("#teacher_code-"+ counter).append(new Option(v.teacher ?? '', v.teacher_code ?? '', true, true)).trigger('change');
                
                //
                // $("#class_name_code-"+ counter).select2({
                //     placeholder: "Select an option",
                //     allowClear: true,
                //     ajax: {
                //         url: "<?php echo URL_ROOT ?>/system/systemSetting/getClasses/?user_log=<?php echo $data['params']['user_log'] ?>",
                //         type: "post",
                //         dataType: 'json',
                //         delay: 250,
                //         data: function (params) {
                //             console.log(params)
                //             return {
                //                 searchTerm: params.term,
                //                 _option: 'select'
                //             };
                //         },
                //         processResults: function (response) {
                //             //console.log(response);
                //             return { results: response };
                //         },
                //         cache: true
                //     }
                // });
                //
                $('#subject_name_code-'+ counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm:  "SENIOR SECONDARY THREE" || params.term,
                                _option: class_code_
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                //
                $('#teacher_code-'+counter).select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/system/systemSetting/getTeachers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
                            //console.log(response);
                            return { results: response };
                        },
                        cache: true
                    }
                });
                // console.log(v)
            })
            
            // jss2();
        }, "JSON")
    }

    function loadClasses(e){
        let class_code, vv;
        // console.log(e.elem.target.selectedOptions[0].innerText);return;
        if(e.target === 1){
            // class_code = e.elem.target.dataset.class_code
            class_code = e.elem.target.selectedOptions[0].value;
            vv = String(e.elem.target.selectedOptions[0].innerText).split('-');
            // vv = String(e.elem.target.innerText).split('-');
            // console.log(class_name, class_code);
        }else if(e.target === "save"){
            class_code = e.class_code
            vv = String(e.cat_name).split('-');
        }else if(e.target === "delete"){
            class_code = e.class_code
            vv = String(e.cat_name).split('-');
        }
        // console.log(vv);return;
        let cat_name = vv[0];
        $(".sss").css("display", "none");
        if((categories_object[cat_name] ?? '') === ''){
            new Noty({type: "warning", text: "<h5>WARNING</h5>level disabled", timeout: 10000}).show();
            return
        }
        let action = categories_object[cat_name].action;
        let alpa_card = String(categories_object[cat_name].alpha).toLowerCase();
        $("#card-"+alpa_card).css("display", "block");
        window[action](class_code);
        // console.log(categories_object[v])
    }
    //
    $(function () {

        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
      let option = $('#level_list').children()["1"];

      $(option).click()
      $(option).attr("selected", true)

      
        ////
        $('#classes').select2({
            placeholder: "Please select an option",
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
        }).on("select2:select", (v)=>{
            loadClasses({elem:v, target:1})
            // console.log(v);
        });

    });

    

</script>



