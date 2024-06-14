<?php
function get_time_ago( $time )
{
    $time_difference = time() - $time;
    // echo($time_difference);

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
// var_dump($data['sch_notice']);exit;
echo $data['menu'];
?>


<style>
    /* .p_user{
        position: absolute;
  top: 56px;
  right: 34px;
  font-size: 15px;
  color: #000;
    } */
</style>
<div class="main-body">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subject</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    <!-- content -->
    <div class="card card-style-1">
        <div class="card-body">
            <button class="btn btn-small btn-light mb-3" onclick="showModal({table: 'table_exam_grade', row: '', modal: '#modal-exam_grade'})" disabled><i class="fa fa-file-import"></i>Upload exam schedule</button>
            <!-- <button class="btn btn-small btn-outline-primary mb-3" onclick="$('#exam_schedule_file').click()"><i class="fa fa-file-import"></i>Import</button>
            <button class="btn btn-small btn-outline-primary mb-3"><i class="fa fa-file-import"></i>Export</button>
            <input type="hidden" id="doc_path" maxlength="250" readonly>
            <input type="file" id="exam_schedule_file" accept="application/vmd.openxmlformats.officedocumnet.spreadsheet.sheet, application/vmd.ms.excel" onchange="scheduleImport({excelfile: '#exam_schedule_file'}, event)" style="display:none"> -->
            <div class="row">
                <div class="col-4-xxxl col-12">
                    <div class="card height-auto">
                        <div class="card-body">
                            <div class="heading-layout1">
                                <div class="item-title">
                                    <h3>Create A Notice</h3>
                                </div>
                            </div>
                            <form id="notice-form' class="new-added-form">
                                <div class="row">
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="title">Title</label>
                                        <input type="text" id="title" placeholder="" class="form-control">
                                        <input type="text" id="notice_code" placeholder="" class="form-control">
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="details">Details</label>
                                        <input type="text" id="details" placeholder="" class="form-control">
                                    </div>
                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="posted_by">Posted By </label>
                                        <select id="posted_by" class="form-control"></select>
                                        <input type="text" id="posted_by_name" placeholder="" class="form-control air-datepicker">
                                        <!-- <span class="p_user"><i class="fas fa-user"></i></span> -->
                                    </div>
                                    <!-- <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                        <label for="date_posted">Date</label>
                                        <input type="text" id="date_posted" placeholder="" class="form-control air-datepicker">
                                        <span class="p_user"><i class="far fa-calendar-alt"></i></span>
                                    </div> -->
                                    <div class="col-12 form-group mg-t-8">
                                        <button type="button" id="save-notice" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" onclick="save()">Save</button>
                                        <button type="reset" class="btn-fill-lg bg-blue-dark btn-hover-yellow"><i class="fa fa-refresh"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8-xxxl col-12">
                    <div class="card height-auto">
                        <div class="card-body">
                            <div class="heading-layout1">
                                <div class="item-title">
                                    <h3>Notice Board</h3>
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
                                <div class="table-responsive" style="height:500px;">
                                    <table style="width:100%">
                                        <?php 
                                            foreach($data['sch_notice'] as $k => $v){
                                                echo '
                                                    <tr>
                                                        <td>
                                                            <div class="notice-list card" style="max-width:80%;">
                                                                <div style="display:flex;justify-content:space-between;">
                                                                    <span style="visibility:hidden;">'.$v->code.'</span>
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="font-size:24px">&hellip;</a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                                                                            <a class="dropdown-item" href="#" data-code='.$v->code.' onclick="edit_notice(event)"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                                                                            <a class="dropdown-item" href="#" data-code='.$v->code.' onclick="delete_notice(event)"><i class="fas fa-redo-alt text-orange-peel"></i>delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="post-date" style="color:white;width:fit-content;background-color: '.$v->color.'">'.date("D d  M, Y", strtotime($v->date_posted)).'</div>
                                                                <h6 class="notice-title">
                                                                    <h5 style="padding-left:2px">'.$v->title.'</5>
                                                                </h6>
                                                                <p style="padding-left:2px"> '.$v->details.' </p>
                                                                <div class="entry-meta" style="padding-left:2px">Posted by: '.$v->posted_by.' </div>
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
            </div>
        </div>
    </div>
    <!-- content -->

</div>

<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>
<script>
    let table_exam_schedule = null;

    ///todo
    let edit_notice = (e)=>{
        // console.log(e)
    }

    let delete_notice = (e)=>{ 
       // $("#div.notice-list").append('<div class="modal-loading text-center py-5 text-white"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
       if(!confirm("You about to delete a notice"));return;
        let notification_code = e.target.dataset.code;
        let url = "<?php echo URL_ROOT ?>/school/notice/_delete/?user_log=<?php echo $data['params']['user_log'] ?>";
        // return;
        $.post(url, {code: notification_code}, (data)=>{
            if(!data.status){
                new Noty({type: 'warning', text: "<h5>WARNING</h5>"+ data.message, timeout: 10000}).show();
                return;
            }

                new Noty({type: 'success', text: "<h5>SUCCESS</h5>"+ data.message, timeout: 10000}).show();
                setTimeout(()=>{
                    // console.log("scared");
                     location.reload()
                }, 2000)
            // console.log(data);
        }, "JSON")
    }

    /////////////////////////////
    let saving = false;
    let save = ()=>{
        $("#save-notice").html('<i class="fa fa-spinner fa-spin" style="white"></i> Save Changes');
        $("#save-notice").prop({disabled: true})
        let url = "<?php echo URL_ROOT ?>/school/notice/_save/?user_log=<?php echo $data['params']['user_log'] ?>";
        let title = $("#title").val();
        let posted_by = $("#posted_by").val();
        let posted_by_name = $("#posted_by_name").val();
        let date_posted = $("#date_posted").val();
        let details = $("#details").val();
        let notice_code = $("#notice_code").val();
        let data = {
            title: title,
            posted_by: posted_by,
            posted_by_name: posted_by_name,
            date_posted: date_posted,
            details: details, 
            code: notice_code
        }
        
        saving = true;
        $.post(url, data, (data)=>{

            if(data.status !== true){
                new Noty({type: "warning", text: "<h5>WARNINNG</H5>"+ data.message, timeout: 10000}).show();
                $("#save-notice").html('<i class="fa fa-save"></i> Save');
                $("#save-notice").prop({disabled: false})
                return;
            }
            saving = false;
            new Noty({type: "success", text: "<h5>SUCCESS</H5>"+ data.message, timeout: 10000}).show();
            $("#save-notice").html('<i class="fa fa-save"></i> Save');
            $("#save-notice").prop({disabled: false})
            setTimeout(()=>{
                location.reload()
                    // console.log("scared");
                }, 2000)

        }, 'JSON')
        // console.log(data)
    }

    $(function(){
        //
        flatpickr('#notice_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });
        
        flatpickr('#date_posted', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: '1800-01-01',
            maxDate: new Date().fp_incr(0), // -92
        });

        $("#posted_by").select2({
            placeholder: "Please select an option",
            allowClear: true,
            ajax: {
                url: "<?php echo URL_ROOT ?>/system/systemSetting/getUsers/?user_log=<?php echo $data['params']['user_log'] ?>",
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
            let fullname = v.target.selectedOptions["0"].innerText
            $("#posted_by_name").val(fullname)
            // console.log()
        })
        

    })
</script>