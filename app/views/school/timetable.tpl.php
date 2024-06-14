<?php
$data = $data ?? [];
$timetable = $data['timetable'] ?? [];
$term = $data['term'] ?? [];
$subjects = $data['subjectsobj'];
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
            <button onclick="downloadPDF()"><i class="fa fa-print"></i></button>
            <div class="table-responsive">
                <div id="" class="dataTables_wrapper">
                    <table class="table table-striped table-bordered table-sm nowrap w-100 datatableList dataTable" id="table-class_routine">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <!-- <th>Class</th> -->
                                <?php 
                                    foreach(SCHOOL_PERIOD as $k => $v){
                                        echo '
                                            <th>'.$v.'</th>
                                        ';
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($timetable as $k => $v){
                                    unset($v->Class);
                                    $tr = '<tr>';
                                    foreach($v as $k_ => $v_){
                                        $subject_name = (($subjects->{$v_} ?? '') === '') ? $v_ : $subjects->{$v_}->subject_name; 
                                        $tr .= '<td>'.$subject_name.'</td>';
                                    }
                                    $tr .= '</tr>';
                                    echo $tr;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- class routine -->


<?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

<script>
    let timetable = <?php echo json_encode($data["timetable"]) ?>;
    let term = <?php echo json_encode($data["term"]) ?>;
    // console.log(timetable)

    let downloadPDF = ()=>{
        let tableHeaderText = [...document.querySelectorAll("#table-class_routine thead tr th")].map((thElement)=>{
           return {text: thElement.innerHTML, bold:true, alignment:'center'}
            
        })

        let tableRowCell = [...document.querySelectorAll("#table-class_routine tbody tr td")].map((thElement)=>{
            return {text: thElement.innerText, alignment: 'center'}
        })
        // console.log(tableRowCell);return

        let tableDataRow = tableRowCell.reduce((rows, cellData, index)=>{
            if(index % 15 === 0){
                rows.push([]);
            }
                rows[rows.length - 1].push(cellData)
                return rows
            // console.log(rows);
        }, [])
        
        var docDefinition = {
            header: {text: `${term.term} ${term.year} timetable, from ${term.start_date} to ${term.end_date}`, alignment: 'left', margin:[38,20,0,0]},
            footer: function(currentPage, pageCount){ return ({text: `Page ${currentPage} of ${pageCount}`, alignment: 'center'}); },
            content: [
                {
                    table: {
                        widths: [100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100],
                        headerRow: 1,
                        body:[
                            tableHeaderText,
                            ...tableDataRow
                        ]
                    },
                    
                    layout:{
                        fillColor: function(rowIndex){
                            // if(rowIndex === 0){
                            //     return "#000"
                            // }
                            // return (rowIndex % 2 === 0) ? "": null;
                        }
                    }
                }
            ], 
            pageSize: 'A1',
            pageOrientation: 'landscape',
            styles: {
                tableExample:{
                    margin: [0]
                },
                tableHeader: {
                    color: 'black'

                },
                tableData: {
                    margin: 2
                }
            }
        };

         pdfMake.createPdf(docDefinition).open();
    }

    $(function () {
        //
    
    });

</script>