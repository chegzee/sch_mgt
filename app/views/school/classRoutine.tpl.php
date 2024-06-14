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
                <select id="class_room" class="form-control col-lg-3"  onchange='loadTimeTable({term_code:$("#terms").val(), class_code: $(this).val() })'  style="float:left">
                    <option value="" selected>Select a class</option>
                </select>
                <select id="terms" class="form-control col-lg-3" onchange='loadTimeTable({term_code:$(this).val(), class_code: $("#class_room").val() })' style="float:left">
                    <option value="" selected>Select term </option>
                </select>
                <select id="sch_days" class="form-control col-lg-3" style="float:left">
                    <option value="" selected>Select a day</option>
                </select>
                <button style="width:100px;" onclick="save(event)"><i class="fa fa-save"></i> Save</button>
                <div style="padding-left:4px;">
                    <input type="checkbox" id="copy_timetable" style="width:20px;height:50px;" />
                </div>
            </div>
            <div class="table-responsive">
                <div id="" class="dataTables_wrapper">
                    <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" id="table-class_routine">
                        <thead>
                            <tr>
                                <th onclick="addPeriod()" style="cursor:pointer"><i class="fa fa-plus"></i>add</th>
                                <th>Day</th>
                                <th>Class</th>
                                <?php 
                                    foreach(SCHOOL_PERIOD as $k => $v){
                                        echo '
                                            <th>'.$v.'</th>
                                        ';
                                    }
                                ?>
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
    
    let levels = <?php echo json_encode($data['levelsobj']) ?>;
    let classes = <?php echo json_encode($data['classesobj']) ?>;
    let subjects = <?php echo json_encode($data['subjectsobj']) ?>;
    let teachers = <?php echo json_encode($data['teachersobj']) ?>;
    var sch_period = <?php echo json_encode(SCHOOL_PERIOD) ?>;
    var OTHER_PERIOD_NAME = <?php echo json_encode(OTHER_PERIOD_NAME) ?>;
    var timetable_name = <?php echo json_encode(TIMETABLE_NAME) ?>;
    var class_subject = '';
    var row_number = 1;
    var row_id = '';
    // console.log(subjects)
    
    let addPeriod = ()=>{
        row_number++;
        let cell_number = 0
        let row_id = "period" + row_number;
        let day = $("#sch_days").val() ?? '';
        let class_room = $("#class_room")["0"].selectedOptions[0].text ?? '';
        if(day === "" || class_room === ""){
            return;
        }

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
            <td>'+day+'</td>\
            <td>'+class_room+'</td>\
        </tr>';
        $("#table-class_routine tbody").append(html);
        $.each(sch_period, (k, v)=>{
            let vvv = row_id + "-" + cell_number;
            $("#"+row_id).append('<td style="width:100px;"><select id="'+vvv+'" class="form-control" style="width:200px"><option value="" selected value="">Select an options</option></select></td>');
            $("#"+vvv).select2({
                data: class_subject
            })
            cell_number++;
        });

    }

    let save = ()=>{
        let table_classRoutine = $("#table-class_routine");
        let table_head = $(table_classRoutine).find("thead");
        let table_body = $(table_classRoutine).find("tbody");
        let head_row = $(table_head).find('tr');
        let body_children = [...$(table_body).children()];
        let head_row_children = [...$(head_row).children()];
        // console.log(head_row_children);return;
        head_row_children.shift()
        let dataSource = [];
        $.each(body_children, (k, v)=>{
            let obj = {};
            let row_td = [...$(v).children()];
            row_td.shift();
            $.each(row_td, (k, v)=>{
                let key = head_row_children[k].innerText
                // console.log(k);return;
                let value = ''
                let select = $(v).find('select')["0"] ?? '';
                let options = select.selectedOptions ?? '';
                let option = options[0] ?? '';
                if(option !== ""){
                    value = option.value
                }else{
                    value =  $(v)["0"].innerText;;
                }

                obj[key]=value;
            })
            dataSource.push(obj);
        })
            // console.log(dataSource);return;
            let data = {}; 
            data["timetable"] = JSON.stringify(dataSource);
            data['class_code'] = $("#class_room").val();
            data['term_code']= $("#terms").val();
            $.post("<?php echo URL_ROOT ?>/school/classRoutine/_save/?user_log=<?php echo $data['params']['user_log'] ?>", data, (data)=>{
                // console.log(data.status)
                if(data.status === true){
                    new Noty({type:"success", text:"<h5>SUCCESS</h5>SUCCESSFUL", timeout: 10000}).show();
                    return;
                }
                new Noty({type:"warning", text:"<h5>WARNING</h5>"+ data.status, timeout: 10000}).show();

            }, 'JSON')
        // console.log(body_children);
    }

    let loadTimeTable = (json)=>{
        let ch = $("#copy_timetable")["0"].checked;
        if(ch)return;
        if((json.term_code ?? '') === "" || (json.class_code ?? '') === '')return;
        $("#table-class_routine tbody tr").remove();
        $.post("<?php echo URL_ROOT ?>/system/systemSetting/getClassRoutine", json, (data)=>{
            let time_table = JSON.parse(data.data.timetable ?? "[]");
            let fields = Object.keys(time_table[0] ?? []);
            $.each(time_table, (k, v)=>{
                let row_id = "period" + row_number;
                // console.log(v)
                let html = '<tr id="'+row_id+'"><td style="border:none;">\
                <div class="dropdown">\
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="fas fa-cog"></i></a>\
                    <div class="dropdown-menu" aria-labelledBy="dropdownMenuButton">\
                        <div class="dropdown-item" onclick="removeRow({elem: event})"><i class="fa fa-trash text-orange-peel"></i> Remove</div>\
                        <div class="dropdown-item"><i class="fas fa-cogs text-dark-pastel-green"></i> edit</div>\
                        <div class="dropdown-item"><i class="fas fa-times text-orange-red"></i> close</div>\
                    </div>\
                </div>\
                </td></tr>';
                $("#table-class_routine tbody").append(html);
                $.each(fields, (k, f)=>{
                    let vvv = row_id + "-" + k
                    if(f === "Day" || f === "Class"){
                        $("#"+row_id).append('<td id="'+vvv+'">'+v[f]+'</td>')

                    }else{
                        let subject = subjects[v[f]] ?? {};
                        let subject_name = (subject.subject_name ?? '') === '' ? v[f] : subject.subject_name;
                        $("#"+row_id).append('<td><select id="'+vvv+'" style="width:200px;"><option value="'+v[f]+'">'+ subject_name +'</option></select></td>');
                        $("#"+vvv).select2({
                            placeholder: "Select an options",
                            data: class_subject
                        })
                        
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

    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        $("#class_room").select2({
            placeholder: "Select an options",
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
            let c = v.target.selectedOptions[0].value;
            $.post("<?php echo URL_ROOT ?>/system/systemSetting/getClassSubject", {_option: c}, (data)=>{
                class_subject = data;
                // console.log(class_subject)
                $.each(OTHER_PERIOD_NAME, (k, v)=>{
                    class_subject.push(v);
                });
            }, 'JSON')

        })

        $("#sch_days").select2({
            data:[
                {id:"MONDAY", text:"MONDAY"},
                {id:"TUESDAY", text:"TUESDAY"},
                {id:"WEDNESDAY", text:"WEDNESDAY"},
                {id:"THURDAY", text:"THURSDAY"},
                {id:"FRIDAY", text:"FRIDAY"}
            ]
        })

        
        $("#terms").select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getTerm",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        _option: 'all_select2'
                    };
                },
                processResults: function (response) {
                    // console.log(response);
                    return { results: response };
                },
                cache: true
            }
        }).on("select2:select", ()=>{
            
        })
    
    });

</script>