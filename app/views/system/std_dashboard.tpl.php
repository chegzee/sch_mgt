<?php
function get_time_ago( $time )
{
    $time_difference = time() - $time;
    // echo($time);

    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );
    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;

        if( $d >= 1 )
        {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}
$data = $data ?? [];
$student = $data['student'] ?? [];
// var_dump($student['cat_name']);exit;
echo $data['menu'];
?>

<style>
    .myclass{
        font-weight: bolder;
        font-size:42px;
        color:blue;
    }
    /* tbody td{
        font-size:24px;
        font-weight:bolder;
        width:fit-content;
    } */
</style>

<!-- Main body -->
<div class="main-body">
    
    <div class="row gutters-20">
        <!-- <div class="row"> -->
            <!-- Student Info Area Start Here -->
            <div class="col-4-xxxl col-12">
                <div class="card dashboard-card-ten">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>About Me</h3>
                            </div>
                        </div>
                        <div class="student-info">
                            <div class="media media-none--xs">
                                <div class="item-img">
                                    <img src="<?php echo $student['picture'] ?>" class="media-img-auto" alt="student">
                                </div>
                                <div class="media-body">
                                    <h3 class="item-title"><?php echo ucwords(strtolower($student['first_name'])) ?></h3>
                                    <p>Aliquam erat volutpat. Curabiene natis massa
                                        sedde lacustiquen sodale word moun taiery.</p>
                                </div>
                            </div>
                            <div class="table-responsive info-table">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td>Name:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['last_name']. " ". $student['first_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Gender:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['gender'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Parent Name:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['parent_name'] ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Mother Name:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['parent_name'] ?></td>
                                        </tr> -->
                                        <tr>
                                            <td>Date Of Birth:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['birthday'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Religion:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['religion'] ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Father Occupation:</td>
                                            <td class="font-medium text-dark-medium">Graphic Designer</td>
                                        </tr> -->
                                        <tr>
                                            <td>E-Mail:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['email'] ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Admission Date:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['submit_on'] ?></td>
                                        </tr> -->
                                        <tr>
                                            <td>Level:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['cat_name'] ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Class name:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['class_name'] ?></td>
                                        </tr> -->
                                        <tr>
                                            <td>Section:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['section'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Roll:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['roll'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Adress:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['address'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['phone'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Session:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $student['term_name'] ." " . $student['year'] ."" ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Student Info Area End Here -->
            <div class="col-8-xxxl col-12">
                <div class="row">
                    <!-- Summery Area Start Here -->
                    <div class="col-lg-4">
                        <div class="dashboard-summery-one">
                            <div class="row">
                                <div class="col-6">
                                    <div class="item-icon bg-light-magenta">
                                        <i class="fas fa-bell" style="font-size: 36px;" ></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">Notification</div>
                                        <div class="item-number"><span class="counter" data-num="12">12</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-summery-one">
                            <div class="row">
                                <div class="col-6">
                                    <div class="item-icon bg-light-blue">
                                        <i class="fa fa-calendar" style="font-size: 36px;" ></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">Events</div>
                                        <div class="item-number"><span class="counter" data-num="06">6</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-summery-one">
                            <div class="row">
                                <div class="col-6">
                                    <div class="item-icon bg-light-yellow">
                                        <i class="fa fa-percent" style="font-size: 36px;" ></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item-content">
                                        <div class="item-title">Attendance</div>
                                        <div class="item-number"><span class="counter" data-num="94">94</span><span>%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Summery Area End Here -->
                    <!-- Exam Result Area Start Here -->
                    <div class="col-lg-12" style="min-height: 700px;">
                        <div class="card dashboard-card-eleven">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>All Exam Results</h3>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">...</a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                                            <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                            <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-box-wrap">
                                    <div class="table-responsive result-table-box">
                                        <div class="dataTables_wrapper">
                                            <table  class="table table-striped table-bordered table-sm nowrap w-100 datatableList" id="table-exam-result" role="grid">
                                                <thead>
                                                    <tr role="row">
                                                        <th><i class="material-icons">build </i></th>
                                                        <th>code</th>
                                                        <th>Posted date</th>
                                                        <th>Subject</th>
                                                        <th>Exam Name</th>
                                                        <th>Scores</th>
                                                        <th>Total Questions</th>
                                                        <th>Submit date</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Exam Result Area End Here -->
                </div>
            </div>
        <!-- </div> -->
    </div>
    <div class="row gutters-20">
        <div class="col-6-xxxl col-xl-6 col-12" style="height:100%;max-height:500px;overflow:hidden">
            <div class="card dashboard-card-three">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Attendance</h3>
                        </div>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">...</a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                            </div>
                        </div>
                    </div>
                    <div class="doughnut-chart-wrap"><div style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="student-doughnut-chart" width="445" height="270" style="display: block; width: 445px; height: 270px;" class="chartjs-render-monitor"></canvas>
                    </div>
                    <div class="student-report">
                        <div class="student-count pseudo-bg-blue">
                            <h4 class="item-title">Absent</h4>
                            <div class="item-number">28.2%</div>
                        </div>
                        <div class="student-count pseudo-bg-yellow">
                            <h4 class="item-title">Present</h4>
                            <div class="item-number">65.8%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6-xxxl col-12" style="height:100%;max-height:500px;overflow:auto">
            <div class="card dashboard-card-six">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Notification</h3>
                        </div>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">...</a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                            </div>
                        </div>
                    </div>
                    <form class="mg-b-20">
                        <div class="row gutters-8">
                            <div class="col-lg-5 col-12 form-group">
                                <input type="text" placeholder="Search by Date ..." class="form-control" id="notice_date" onchange ="search_by_date()">
                            </div>
                            <div class="col-lg-5 col-12 form-group">
                                <input type="text" placeholder="Search by Title ..." class="form-control" id="notice_title" onkeyup="search_by_title()">
                            </div>
                            <!-- <div class="col-lg-2 col-12 form-group">
                                <button type="button" class="fw-btn-fill btn-gradient-yellow">SEARCH</button>
                            </div> -->
                        </div>
                    </form>
                    <div class="notice-board-wrap" id="notice_table">
                        <table style="width:100%">
                            <?php 
                                foreach($data['sch_noticePerTerm'] as $k => $v){
                                    echo '
                                        <tr>
                                            <td>
                                                <div class="notice-list card" style="max-width:80%">
                                                    <div class="post-date bg-skyblue" style="color:white;width:fit-content;background-color: '.$v->color.'">'.date("D d  M, Y", strtotime($v->date_posted)).'</div>
                                                    <h6 class="notice-title">
                                                        <h5>'.$v->title.'</5>
                                                    </h6>
                                                    <p> '.$v->details.' </p>
                                                    <div class="entry-meta">Posted by: '.$v->posted_by.' / <span>'. get_time_ago(strtotime($v->date_posted)) .'</span></div>
                                                </div>
                                            </td>
                                        </tr>
                                    ';
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div class="row">
        <div class="col-12 col-xl-12 col-12-xxxl">
            <div class="card dashboard-card-four">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Event Calender</h3>
                        </div>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-expanded="false">...</a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fas fa-times text-orange-red"></i>Close</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                            </div>
                        </div>
                    </div>
                    <div class="calender-wrap">
                        <div id="fc-calender" class="fc-calender"></div>
                    </div>
                </div>
            </div>
        </div>

    </div> -->
    <!-- Footer Area Start Here -->
    <footer class="footer-wrap-layout1">
        <div class="copyright">© Copyrights <a href="#"></a> 2023. All rights reserved. Designed by <a href="#">Scared info</a></div>
    </footer>
    <!-- Footer Area End Here -->
</div>
<!-- /Main body -->

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>

    var tableExamResult = null;
    var std_code = <?php echo json_encode($student['std_code']) ?>
    

    $(() => {
        // console.log(std_code);
        tableExamResult = $("#table-exam-result").DataTable();

        let loadStdOnlineExam = ()=>{
            let url = "<?php echo URL_ROOT ?>/school/questionAnswer/onlineResult/?user_log=<?php echo $data['params']['user_log'] ?>";
            tableExamResult.destroy();

            tableExamResult = $("#table-exam-result").DataTable({
                "prosessing": true,

                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {std_code: std_code},
                },
                "columns": [
                    {
                        "data": "question_code", "width": 5, "render": function (data, type, row, meta) {
                            return '<a id="dropdownMenuButton' + meta['row'] + '" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') +' fa fa-cog"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' + meta['row'] + '" style="z-index:99"><a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-times text-orange-red"></i>Close</a><a class="dropdown-item" href="#" onclick="modalStudent({table: \'#table-std\', row: \'' + meta['row'] + '\'})"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a><a class="dropdown-item" href="#"  onclick="deleteStudent({table: \'#table-std\', row: \'' + meta['row'] + '\'})"><i class="fas fa-trash text-orange-peel"></i>Delete</a></div>';
                        }
                    },
                     {"data": "question_code"},
                     {"data": "posted_date"},
                     {"data": "subject_name"},
                     {"data": "exam_name"},
                     {"data": "scores", "render": function(data, type, row, meta){
                        return row.scores ?? '' + " over " + row.total_question ?? ''
                        // console.log(row)
                     }},
                     {"data": "total_question"},
                     {"data": "submit_date"}

                ],

                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                    {"className": "myclass", "targets": [5]}
                ],
                "aaSorting": [[7, "desc"]],
                "initComplete": function (settings, json) {
                    $('.dataTables_filter input[type="search"]').css({"height": "30px", "width": "200px", "background-color": "white", "font-size":"16px", "font-weight": "bold"})
                    //  console.log(json);
                }
            })
        }

        loadStdOnlineExam();
                
        // Global Chart configuration
        Chart.defaults.global.elements.rectangle.borderWidth = 1 // bar border width
        Chart.defaults.global.elements.line.borderWidth = 1 // line border width
        Chart.defaults.global.maintainAspectRatio = false // disable the maintain aspect ratio in options then it uses the available height
        
        // pieChart
        let pieChart = new Chart('student-doughnut-chart', {
            type: 'doughnut',
            data: {
                labels: ['present', 'absent'],
                // labels: Object.keys(yearlySaleCurrent),
                datasets: [{
                    label: '# of student',
                    data: [40, 60],
                    // data: Object.values(yearlySaleCurrent),
                    backgroundColor: [
                        'rgba(255, 166, 0, 0.9)',
                        'rgba(255, 111, 0, 0.9)'
                    ],
                    borderWidth: 0.5
                }]
            }
        });
    })


        function display_events() {
            var events = [];
            $.ajax({
                url: '<?php echo URL_ROOT ?>/system/EventCalendar/getEvents?user_log=<?php echo $data['params']['user_log'] ?>',  
                dataType: 'json',
                success: function (response) {
                    var result=response.data;
                    // console.log(result)
                    $.each(result, function (i, item) {
                        events.push({
                            id: result[i].id,
                            title: result[i].title,
                            scared: result[i].start,
                            end: result[i].end,
                            color: result[i].color,
                            url: result[i].url
                        }); 	
                    })
                    var calendar = $('#fc-calender').fullCalendar({
                        defaultView: 'month',
                        timeZone: 'local',
                        editable: true,
                        selectable: true,
                        selectHelper: true,
                        select: function(scared, end) {
                            // alert(start);
                            // alert(end);
                            $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
                            $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
                            $('#event_entry_modal').modal('show');
                        },
                        events: events,
                        eventRender: function(event, element, view) { 
                            // element.bind('click', function() {
                            //         alert(event.id);
                            // });
                        }
                    }); 
                    //end fullCalendar block	
                },//end success block
                error: function (xhr, status) {
                        alert(status);
                }
            });//end ajax block	
        }
    </script>


