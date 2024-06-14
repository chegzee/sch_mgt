<?php
$data = $data ?? [];
$term = $data['term'] ?? [];
$termObj = $data['termObj'] ?? [];
// var_dump($max_key_val);exit;
echo $data['menu'];
?>

<div class="main-body">
    <style>
        
        /* * {
            box-sizing: border-box;
        }
        th{
            border-bottom:2px solid elelel;
            border-top:none;
            font-size:16px;
            font-weight:500;
            color:#fff;
            padding:14px 15px;
            background-color:#042954;

        }
        p, h1, h2, h3,h4,h5,h6 {
            margin:0
        } */
    </style>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body" style="padding-left:0px;">
            <div class="table-responsive" style="height:700px;">
                <div id="" class="dataTables_wrapper">
                    <table id="table-question"  class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" role="grid">
                        <thead>
                            <tr>
                                <th><i class="material-icons">build</i></th>
                                <th>posted date</th>
                                <th>exam name</th>
                                <th>time</th>
                                <th>subject</th>
                                <th>term</th>
                                <th>year</th>
                                <th>submit on</th>
                                <th>submit by</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
            
        </div>
    </div>

</div>

<!-- question modal -->
<div id="modal-question" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <!-- <div style="position:absolute;top:4px;left:200px;">+120days</div> -->
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Questions</h5>
                <div id="period_name" style="padding-left:12px;padding-right:12px;font-size:18px;font-wight:bold;background-color:#cfcfcf;color:black;margin-left:8px;"></div>
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
                            
                            <div class="col-12 form-group">
                                <div class="table-responsive">
                                    <div id="" class="dataTables_wrapper">
                                        <table id="table-student-answer"  class="table table-striped table-bordered table-sm nowrap datatableList dataTable" role="grid">
                                            <thead>
                                                <tr>
                                                    <th>Student code</th>
                                                    <th>Student name</th>
                                                    <th>subject</th>
                                                    <th>Scores</th>
                                                    <th>Numbers</th>
                                                    <th>submit on</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
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
    let term = <?php echo json_encode($data['term']) ?>;
    let termObj = <?php echo json_encode($data['termObj']) ?>;
    var tableQuestions = null;
  
    // console.log(parseInt(question_meta.exam_timer));


    $(function () {

        tableQuestions = $("#table-question").DataTable();


        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });

        let modalQuestion = (json)=>{

            $("#table-student-answer tbody").html('');

            let tableQuestion = $(json.table).DataTable();
            let data = json.row === '' ? {} : tableQuestion.row(json.row).data(); // data["colName"]
            let students = JSON.parse(data.students);
            $.each(students, (k, v)=>{
                let html = "\
                <tr>\
                    <td>"+(v.std_code ?? '')+"</td>\
                    <td style='color:black;font-weight:bold;'>"+(v.student_name ?? '')+"</td>\
                    <td>"+(v.subject_name ?? '')+"</td>\
                    <td style='text-align:center;color:black;font-weight:bold;'>"+(v.scores ?? '')+"</td>\
                    <td style='text-align:center;color:black;font-weight:bold;'>"+(v.total_question ?? '')+"</td>\
                    <td>"+(v.submit_date ?? '')+"</td>\
                </tr>"
                $("#table-student-answer tbody").append(html);
                // console.log(v)
            })

            // $('#modalNav').find('a.non-active').addClass('d-none');

            $("#modal-question").modal("show")
        
            // console.log(students)
        }
        
        let loadQuestion = (json) => {
        
        // dataTables
        let url = "<?php echo URL_ROOT ?>/school/allQuestionAnswer/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
        // $.post(url, {}, function(data) { console.log(data) }); return;
    
        tableQuestions.destroy();
    
        tableQuestions = $('#table-question').DataTable({
            "processing": true,
            //"serverSide": true,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {},
            },
            "columns": [
                {
                    "data": "code", "width": 5, "render": function (data, type, row, meta) {
                        return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#")"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#" ><i class="fas fa-trash text-orange-peel"></i>Delete</a>  </div>'
                        ;
                    }

                },
                {"data": "posted_date"},
                {"data": "exam_name"},
                {"data": "exam_timer"},
                {"data": "subject_name"},
                {"data": "term"},
                {"data": "year"},
                {"data": "submit_on"},
                {"data": "submit_by"},
            ],
            "columnDefs": [
                {"targets": [1], "sortable": false, "searchable": false},
            ],
            "aaSorting": [[1, "asc"]],
            "initComplete": function (settings, json) {
                $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                 console.log(json);
                //  modalAuto();
            }
        });
    }

    loadQuestion({});

    //
    tableQuestions.search('', false, true);
    //
    tableQuestions.row(this).remove().draw(false);

    //
    $('#table-question tbody').on('click', 'td', function () {
        //
        //let data = tableUser.row($(this)).data(); // data["colName"]
        let data = tableQuestions.row($(this));
        //console.log(data)
        let rowId = $(this).parent('tr').index();
        //console.log("row clicked : " + rowId)

        localStorage.setItem('selected-row', rowId);
    
        if (!data) return;
        //
        //console.log(this.cellIndex);
        if (this.cellIndex != 0) {
            //
            modalQuestion({table: '#table-question', row: data});
            //
            // $('#modalNav a[href="#page_1"]').tab('show');
        }
    });
    
    });

</script>