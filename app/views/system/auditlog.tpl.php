<?php
$data = $data ?? [];
echo $data['menu'];
?>

<div class="main-body">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT ?>/system/dashboard/?user_log=<?php echo $data['params']['user_log'] ?>">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="javascript:void(0)">Tables</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Audit Logs</li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    
    <div class="card card-style-1">
        <div class="card-body">
            
            <a href="javascript:void(0)" onclick="" class="btn btn-sm btn-primary mb-3"><i class="fa fa-print"></i> Print</a>
            <div style="overflow: hidden">
                <table id="table-auditlog" class="table table-striped table-bordered table-sm nowrap w-100 datatableList">
                    <thead>
                    <tr>
                        <th><i class="material-icons">build</i></th>
                        <th>DateTime</th>
                        <th>Event</th>
                        <th>Full Name</th>
                        <th>IP Addr.</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- auditlogModal -->
<div id="modal-auditlog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Audit Log Detail</h5>
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
    
                        <a href="javascript:void(0)" onclick="" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-print"></i> Print</a>
    
                        <style>
                            div.journal {
                                height: 24px;
                                overflow: hidden;
                            }
                            .table-sm td, .table-sm th {
                                padding: .2rem;
                            }
                        </style>
    
                        <div class="table-responsive" style="border-top: 1px solid">
                            <table id="table-auditlog-detail" class="table table-striped table-bordered table-sm nowrap w-100" style="cursor: pointer">
                                <tbody>
                                <tr style="border-bottom: 3px double">
                                    <th>Item</th>
                                    <th>Description</th>
                                </tr>
                                </tbody>
                            </table>
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
    // console.log(userAccess);
    
    //
    let modalAuditlog = (json) => {
        let tableAuditlog = $(json.table).DataTable();
        let data = json.row === '' ? {} : tableAuditlog.row(json.row).data(); // data["colName"]
        //
        $('#modalNav').find('a.non-active').addClass('d-none');
        // console.log(data);
        
        let detail = $('#table-auditlog-detail');
        detail.find('tr:gt(0)').remove();
        
        detail.find('tr:last').after('<tr><td><strong>DateTime:</strong></td><td>' + data['log_time'] + '</td></tr>');
        detail.find('tr:last').after('<tr><td><strong>Event:</strong></td><td>' + data['event_log'] + '</td></tr>');
        detail.find('tr:last').after('<tr><td><strong>Fullname:</strong></td><td>' + data['first_name'] + ' ' + data['last_name'] + '</td></tr>');
        detail.find('tr:last').after('<tr style="border-bottom: 3px double"><td><strong>IP Address:</strong></td><td>' + data['user_ip'] + ' <span id="ip_address"></span></td></tr>');
    
        // $.getJSON('http://ip-api.com/json/' + data['user_ip'] + '?fields=country,regionName,city', function (data_ip) {
        //     if (data_ip.country !== undefined)
        //         $('#ip_address').html(' (' + data_ip.country + ', ' + data_ip.regionName + ', ' + data_ip.city + ')');
        // });
    
        let json_ = {}
        try {
            json_ = JSON.parse(data['remarks'])
        } catch (e) {
        }
        $.each(json_, function (i, v) {
            if (typeof v !== "string") return true;
            if (i === 'auto_id' || i === 'func' || i === 'password' || v.trim() === '' || v.trim() === '0') return true;
            detail.find('tr:last').after('<tr><td><strong>' + i + ':</strong></td><td>' + v + '</td></tr>');
        });
    
        //
        $('#modal-auditlog').modal('show');
        //
        $('#modalNav a[href="#page_1"]').tab('show');
    }
    
    $(function () {
        //
        $('input[type=text]').on('blur change', function () {
            $(this).val($(this).val().trim().toUpperCase());
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
        let tableAuditlog = $("#table-auditlog").DataTable();
    
        let loadAuditlog = (json) => {
        
            // dataTables
            let url = "<?php echo URL_ROOT ?>/system/auditlog/_list/?user_log=<?php echo $data['params']['user_log'] ?>";
            // $.post(url, {}, function(data) { console.log(data) }); return;
        
            tableAuditlog.destroy();
        
            tableAuditlog = $('#table-auditlog').DataTable({
                "processing": true,
                //"serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": {},
                },
                "columns": [
                    {
                        "data": "log_time", "width": 5, "render": function (data, type, row, meta) {
                            return '<button class="btn btn-xs ' + (row['status'] !== '1' ? 'btn-outline-danger' : 'btn-outline-success') + ' dropdown-toggle" type="button" id="dropdownMenuButton' + meta['row'] + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i></button>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton' + meta['row'] + '">' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="modalAuditlog({table: \'#table-auditlog\', row: \'' + meta['row'] + '\'})"><i class="fa fa-edit w-25px"></i> View/Edit Item</a>' +
                                '<a class="dropdown-item text-primary" href="javascript:void(0)" onclick="printAuditlog({table: \'#table-auditlog\', row: \'' + meta['row'] + '\'})"><i class="fa fa-print w-25px"></i> Print Item</a>' +
                                '</div>';
                        }
                    },
                    {"data": "log_time"},
                    {"data": "event_log"},
                    {"data": "last_name", "render": function (data, type, row, meta) {
                        return row['first_name'] + ' ' + row['last_name'];
                        }},
                    {"data": "user_ip"},
                    {"data": "remarks", "render": function (data, type, row, meta) {
                        // let data_ = (data.substr(0, 1) === '{' && data.substr(-1) === '}') ? data : '{}';
                        let json_ = {};
                        try {
                            json_ = JSON.parse(data);
                        } catch (e) {
                        }
                        // console.log(row['access']);
                        let html_ = '';
                        let count = 0;
                        $.each(json_, function (i, v) {
                            if (typeof v !== "string") return true;
                            if (i === 'auto_id' || i === 'func' || i === 'password' || v.trim() === '' || v.trim() === '0') return true;
                            html_ += '<div style="float: left; padding: 2px 5px; margin-right: 5px; border: 1px solid #cccccc; font-size: 85%"><strong>' + strEllipsis(i, 15) + ': </strong>' + strEllipsis(v, 30) + '</div>';
                            if (count >= 3) return false;
                            count++;
                        });
                        return html_;
                    }},
                ],
                "columnDefs": [
                    {"targets": [0], "sortable": false, "searchable": false},
                ],
                "aaSorting": [[1, "desc"]],
                "initComplete": function (settings, json) {
                    //console.log(json);
                }
            });
        }
    
        loadAuditlog({});
    
        //
        tableAuditlog.search('', false, true);
        //
        tableAuditlog.row(this).remove().draw(false);
    
        //
        $('#table-auditlog tbody').on('click', 'td', function () {
            //
            //let data = tableAuditlog.row($(this)).data(); // data["colName"]
            let data = tableAuditlog.row($(this));
            //console.log(data)
            let rowId = $(this).parent('tr').index();
            //console.log("row clicked : " + rowId)
    
            localStorage.setItem('selected-row', rowId);
        
            if (!data) return;
            //
            //console.log(this.cellIndex);
            if (this.cellIndex != 0) {
                //
                modalAuditlog({table: '#table-auditlog', row: data});
                //
                $('#modalNav a[href="#page_1"]').tab('show');
            }
        });
    
        // /////////////////////////////////////////////////////////////////////////////////////////
    
        $('#modal-auditlog').on('hidden.bs.modal', function () {
            tableAuditlog.ajax.reload(null, false);
        });
    
        // ////////////////////////////////////////////////////////////////////////////////////////
    
        //
        let checkForm = new timer();
        checkForm.start(function () {
            //
            checkForm.stop();
            //
            let disabled = false;
        
            checkForm.start();
        
        }, 500, true); //
    });

</script>



