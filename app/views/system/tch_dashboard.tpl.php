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

$totalStudents = $data['total_students'];
$maleStudents = $data['male_students'];
$femaleStudents = $data['female_students'];
$totalTeachers = $data['total_teachers'];
$totalParents = $data['total_parents'];
// var_dump($totalParents);exit;
echo $data['menu'];
?>

<!-- Main body -->
<div class="main-body">

    <div class="row">
        <!-- Dashboard summery Start Here -->
        <div class="col-12 col-4-xxxl">
            <div class="row">
                <div class="col-6-xxxl col-lg-3 col-sm-6 col-12">
                    <div class="dashboard-summery-two">
                        <div class="item-icon bg-light-magenta">
                            <i class="fa fa-user-graduate" style="font-size: 36px;" ></i>
                        </div>
                        <div class="item-content">
                            <div class="item-number"><span class="counter"><?php echo $totalStudents->total_students ?></span></div>
                            <div class="item-title">Total Students</div>
                        </div>
                    </div>
                </div>
                <div class="col-6-xxxl col-lg-3 col-sm-6 col-12">
                    <div class="dashboard-summery-two">
                        <div class="item-icon bg-light-blue">
                            <i class="fa fa-book-open-reader" style="font-size: 36px;" ></i>
                        </div>
                        <div class="item-content">
                            <div class="item-number"><span class="counter"><?php echo $totalTeachers->total_teachers ?></span></div>
                            <div class="item-title">Total Teachers</div>
                        </div>
                    </div>
                </div>
                <div class="col-6-xxxl col-lg-3 col-sm-6 col-12">
                    <div class="dashboard-summery-two">
                        <div class="item-icon bg-light-yellow">
                            <i class="fa fa-user" style="font-size: 36px;" ></i>
                        </div>
                        <div class="item-content">
                            <div class="item-number"><span class="counter"><?php echo $totalParents->total_parent ?></span></div>
                            <div class="item-title">Parents</div>
                        </div>
                    </div>
                </div>
                <div class="col-6-xxxl col-lg-3 col-sm-6 col-12">
                    <div class="dashboard-summery-two">
                        <div class="item-icon bg-light-red">
                            <i class="fa fa-user-graduate" style="font-size: 36px;" ></i>
                        </div>
                        <div class="item-content">
                            <div class="item-number"><span></span><span class="counter" data-num="2102050">0</span></div>
                            <div class="item-title">Total Graduate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard summery End Here -->
        <!-- Students Chart End Here -->
        <div class="col-lg-6 col-4-xxxl col-xl-6">
            <div class="card dashboard-card-three">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Students</h3>
                        </div>
                    </div>
                    <div class="doughnut-chart-wrap"><div style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="student-doughnut-chart" width="445" height="270" style="display: block; width: 445px; height: 270px;" class="chartjs-render-monitor"></canvas>
                    </div>
                    <div class="student-report">
                        <div class="student-count pseudo-bg-blue">
                            <h4 class="item-title">Female Students</h4>
                            <div class="item-number"><?php echo $femaleStudents->total ?></div>
                        </div>
                        <div class="student-count pseudo-bg-yellow">
                            <h4 class="item-title">Male Students</h4>
                            <div class="item-number"><?php echo $maleStudents->total ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Students Chart End Here -->
        <!-- Notice Board Start Here -->
        
        <div class="col-12 col-6-xl col-4-xxxl" style="height:100%;max-height:500px;overflow:auto">
            <div class="card dashboard-card-six pd-b-20">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Notification</h3>
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
        <!-- Notice Board End Here -->
    </div>
    

    <!-- Footer Area Start Here -->
    <footer class="footer-wrap-layout1">
        <div class="copyright">© Copyrights <a href="https://safamdigital.com">safam digital hub</a> 2023. All rights reserved. Designed by <a href="#">Scared info</a></div>
    </footer>
    <!-- Footer Area End Here -->
</div>
<!-- /Main body -->

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    var totalFemale = <?php echo json_encode($femaleStudents->total) ?>;
    var totalMale = <?php echo json_encode($maleStudents->total) ?>;
    // console.log(totalFemale,totalMale )
    $(() => {
        
        let checkForm = new timer();
            
        // Global Chart configuration
        Chart.defaults.global.elements.rectangle.borderWidth = 1 // bar border width
        Chart.defaults.global.elements.line.borderWidth = 1 // line border width
        Chart.defaults.global.maintainAspectRatio = false // disable the maintain aspect ratio in options then it uses the available height
        let current = {
            'year': moment().format('YYYY').toString(),
            'month': moment().format('M').toString(),
            'day': moment().format('DDD').toString(),
        }
        let previous = {
            'year': (moment().format('YYYY') - 1).toString(),
        }
            
        // Monthly Sales
        let monthlySales = new Chart('earning-line-chart', {
            type: 'line', // line
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Last week',
                        backgroundColor: Chart.helpers.color(red).alpha(0.1).rgbString(),
                        borderColor: gray,
                        fill: 'start',
                        data: [65, 59, 80, 81, 56, 55, 40]
                    },
                    {
                        label: 'This week',
                        backgroundColor: Chart.helpers.color(blue).alpha(0.1).rgbString(),
                        borderColor: blue,
                        fill: 'start',
                        data: [28, 48, 40, 19, 46, 27, 30]
                    }
                ]
            },
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                    // Interactions configuration: https://www.chartjs.org/docs/latest/general/interactions/
                }
            }
        });
        
        let exp_monthlySales = new Chart('expense-bar-chart', {
            type: 'bar', // line
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Last year',
                        backgroundColor: Chart.helpers.color(red).alpha(0.1).rgbString(),
                        borderColor: blue,
                        fill: 'start',
                        data: [65, 59, 80, 81, 56, 55, 40, 67, 98, 34, 67, 55]
                    },
                    {
                        label: 'This year',
                        backgroundColor: Chart.helpers.color(blue).alpha(0.1).rgbString(),
                        borderColor: blue,
                        fill: 'start',
                        data: [28, 48, 40, 19, 46, 27, 30, 65, 78, 45, 43, 89]
                    }
                ]
            },
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                    // Interactions configuration: https://www.chartjs.org/docs/latest/general/interactions/
                }
            }
        });
        
        // pieChart
        let pieChart = new Chart('student-doughnut-chart', {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                // labels: Object.keys(yearlySaleCurrent),
                datasets: [{
                    label: '# of student',
                    data: [totalMale, totalFemale],
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
                $.each(result, function (i, item) {
                    events.push({
                        id: result[i].id,
                        title: result[i].title,
                        start: result[i].start,
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
                    select: function(start, end) {
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


