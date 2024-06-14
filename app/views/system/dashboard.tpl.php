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
$students = $data['students'];
$totalStudents = $data['total_students'];
$term = $data['term'];
$payable = $data['payable'];
$receivable = $data['receivable'];
$sales = $data['sales'];
$maleStudents = $data['male_students'];
$femaleStudents = $data['female_students'];
$totalTeachers = $data['total_teachers'];
$totalParents = $data['total_parents'];
// var_dump($payable);exit;
echo $data['menu'];
?>

<!-- Main body -->
<div class="main-body">
    
    <div class="row gutters-20">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="item-icon bg-light-green ">
                            <i class="fa fa-users" style="font-size: 36px;" ></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="item-content">
                            <div class="item-title">Students</div>
                            <div class="item-number"><span class="counter"><?php echo $totalStudents->total_students ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="item-icon bg-light-blue">
                            <i class="fa fa-user text-blue" style="font-size: 36px;"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="item-content">
                            <div class="item-title">Teachers</div>
                            <div class="item-number"><span class="counter"><?php echo $totalTeachers->total_teachers ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="item-icon bg-light-yellow">
                            <i class="fa fa-user" style="font-size: 36px;"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="item-content">
                            <div class="item-title">Receivable</div>
                            <div class="item-number"><span>&#8358;</span><span class="counter"  id="receivable_id"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="item-icon bg-light-red">
                            <i class="fa fa-box text-red" style="font-size: 36px;"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="item-content">
                            <div class="item-title">Earning</div>
                            <div class="item-number"><span>&#8358;</span><span id="earning_id" class="counter"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard summery End Here -->
    <!-- Dashboard Content Start Here -->
    <div class="row gutters-20">
        <div class="col-12 col-xl-6 col-6-xxxl">
            <div class="card dashboard-card-one pd-b-20">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Earning</h3>
                        </div>
                    </div>
                    <div class="earning-report">
                        <div class="item-content">
                            <!-- <div class="single-item pseudo-bg-blue">
                                <h4>Total Collections</h4>
                                <span>75,000</span>
                            </div> -->
                            <div class="">
                                <span><span>&#8358;</span><?php echo number_format(abs($sales->balance), 2) ?></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a class="date-dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><?php echo date('d M, Y', strtotime($term->start_date)) . ' - '.  date('d M, Y', strtotime($term->end_date)) ?></a>
                        </div>
                    </div>
                    <div class="earning-chart-wrap">
                        <canvas id="earning-line-chart" width="660" height="320"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-6-xxxl col-12">
            <div class="card dashboard-card-two pd-b-20">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Expenses</h3>
                        </div>
                    </div>
                    <div class="expense-report">
                        <div class="monthly-expense pseudo-bg-Aquamarine">
                            <div class="expense-date">Jan 2019</div>
                            <div class="expense-amount"><span>$</span> 15,000</div>
                        </div>
                        <div class="monthly-expense pseudo-bg-blue">
                            <div class="expense-date">Feb 2019</div>
                            <div class="expense-amount"><span>$</span> 10,000</div>
                        </div>
                        <div class="monthly-expense pseudo-bg-yellow">
                            <div class="expense-date">Mar 2019</div>
                            <div class="expense-amount"><span>$</span> 8,000</div>
                        </div>
                    </div>
                    <div class="expense-chart-wrap">
                        <canvas id="expense-bar-chart" width="100" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gutters-20">
        <div class="col-xl-6 col-6-xxxl col-12">
            <div class="card dashboard-card-three pd-b-20">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Students</h3>
                        </div>
                    </div>
                    <div class="doughnut-chart-wrap">
                        <canvas id="student-doughnut-chart" width="100" height="300"></canvas>
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
        <div class="col-xl-6 col-6xxxl col-12">
            <div class="card dashboard-card-two pd-b-20">
            <div class="card-body">
                <div class="heading-layout1">
                    <div class="item-title">
                        <h3>Notification</h3>
                    </div>
                </div>
                <!-- <form class="mg-b-0">
                    <div class="row">
                        <div class="col-lg-5 col-12">
                            <input type="text" placeholder="Search by Date ..." class="form-control" id="notice_date" onchange ="search_by_date()">
                        </div>
                        <div class="col-lg-5 col-12">
                            <input type="text" placeholder="Search by Title ..." class="form-control" id="notice_title" onkeyup="search_by_title()">
                        </div>
                        <div class="col-lg-2 col-12">
                            <button type="button" class="fw-btn-fill btn-gradient-yellow">SEARCH</button>
                        </div>
                    </div>
                </form> -->
                <div class="col-12" id="notice_table">
                    <div class="table-responsive" style="height:447px;">
                        <table style="width:100%;">
                            <tbody>
                                <?php 
                                    foreach($data['sch_noticePerTerm'] as $k => $v){
                                        echo '
                                            <tr>
                                                <td>
                                                    <div class="notice-list card">
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
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
         </div>
        </div>
    </div>
        
</div>
<!-- Dashboard Content End Here -->
<!-- Footer Area Start Here -->
<footer class="footer-wrap-layout1">
    <div class="copyright">© Copyrights <a href="https://safamdigital.com">safam digital hub</a> 2023. All rights reserved. Designed by <a
            href="#">Scared info</a></div>
</footer>
<!-- Footer Area End Here -->

    <!-- Start popup dialog box for event calendar-->
<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Add New Event</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="img-container">
					<div class="row">
						<div class="col-sm-12">  
							<div class="form-group">
							  <label for="event_name">Event name</label>
							  <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter your event name">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">  
							<div class="form-group">
							  <label for="event_start_date">Event start</label>
							  <input type="date" name="event_start_date" id="event_start_date" class="form-control onlydatepicker" placeholder="Event start date">
							 </div>
						</div>
						<div class="col-sm-6">  
							<div class="form-group">
							  <label for="event_end_date">Event end</label>
							  <input type="date" name="event_end_date" id="event_end_date" class="form-control" placeholder="Event end date">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button>
			</div>
		</div>
	</div>
</div>
    <!-- End popup dialog box -->
</div>
<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>

    var students_ = <?php echo json_encode($students) ?>;
    var totalFemale = <?php echo json_encode($femaleStudents->total) ?>;
    var totalMale = <?php echo json_encode($maleStudents->total) ?>;
    var term = <?php echo json_encode($term->start_date) ?>;
    var payable = <?php echo json_encode($payable) ?>;
    var dateposted =  <?php echo json_encode($data['sch_noticePerTerm']) ?>;
    // console.log( get_time_ago(strtotime(dateposted[0])) );
    var students = ()=>{
        let receivable = 0;
        let received = 0;
        $.each(students_, (k, v)=>{ 
            received += parseFloat(v.total_receipt_amount ?? 0)
            receivable += parseFloat(v.balance_due ?? 0)
            // console.log(v.total_invoice_amount)
        })
        $("#earning_id").html(number_format(received, 2));
        $("#receivable_id").html(number_format(receivable, 2));
        // console.log(receivable, received)
        // console.log(students_)
        
    }
    

    $(() => {
        // console.log(payable);
        // let checkForm = new timer();
        // checkForm.start(function () {
            //
            // checkForm.stop();
            // //
            // disabled = false;
            
            //
           // $.post('<?php echo URL_ROOT ?>/system/cron/dashboard?user_log=<?php echo $data['params']['user_log'] ?>', {branch_code: '<?php echo $data['user']['branch_code'] ?>'}, function (data) {
                
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
                
                // let ctx = document.getElementById("earning-line-chart").getContext("2d");
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
                                data: [28, 78, 40, 19, 46, 27, 30]
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
                ////
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
                
                // checkForm.start();
                
            // }, 'JSON');
            
        // }, 1000 * 60 * 5, true); 
        // display_events();
        
        // realtime_fb_likes();
        // setInterval("realtime_fb_likes()", 30000);

        students();
            
        
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
                        // url: result[i].url
                    }); 	
                })
                var calendar = $('#fc-calender').fullCalendar({
                    defaultView: 'month',
                    timeZone: 'local',
                    editable: true,
                    selectable: true,
                    selectHelper: true,
                    events: events,
                    select: function(start, end) {
                        // $('#event_name').val(name);
                        // $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
                        // $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
                        // $('#event_entry_modal').modal('show');
                    },
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

    function save_event()
    {
        var event_name=$("#event_name").val();
        var event_start_date=$("#event_start_date").val();
        var event_end_date=$("#event_end_date").val();
        if(event_name ==="" || event_start_date === "" || event_end_date === "")
        {
            // console.log("scared")
            alert("Please enter all required details.");
            return false;
        }
        $.ajax({
            url: '<?php echo URL_ROOT ?>/system/eventCalendar/_save?user_log=<?php echo $data['params']['user_log'] ?>',
            type:"POST",
            dataType: 'json',
            data: {event_name:event_name,event_start_date:event_start_date,event_end_date:event_end_date},
            success:function(response){
                $('#event_entry_modal').modal('hide');  
                if(response.status == true)
                {
                    // alert(response.msg);
                    location.reload();
                }
                else
                {
                    alert(response.msg);
                }
            },
            error: function (xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
                alert(xhr);
            }
        });    
        // return false;
    }
</script>


