<!-- Main Scripts -->
<script src="<?php echo ASSETS_ROOT ?>/js/script.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/js/app.min.js"></script>


<!-- Plugins -->
<script src="<?php echo ASSETS_ROOT ?>/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/chart.js/Chart.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/jqvmap/jquery.vmap.min.js"></script>
<!--<script src="<?php echo ASSETS_ROOT ?>/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>-->
<script src="<?php echo ASSETS_ROOT ?>/plugins/jqvmap/maps/jquery.vmap.nigeria.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/noty/noty.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/datatables/jquery.dataTables.bootstrap4.responsive.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/datatables/jquery.dataTables.ellipsis.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/datatables/jquery.dataTables.sum.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/select2-3.5.2/select2.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/flatpickr/flatpickr.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/moment/moment.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/xlsx/xlsx.full.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/session-timeout/session-timeout.min.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/plugins/ckeditor5-inline/build/ckeditor.js"></script>

<script src="<?php echo ASSETS_ROOT ?>/js/custom.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/js/tabletoexcel.js"></script>
<script src="<?php echo ASSETS_ROOT ?>/js/timepicker-ui.umd.js"></script>
<!-- cdn fullcalendar -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.9/pdfmake.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.9/pdfmake.js" integrity="sha512-cXLS4U14Rm+shV7jwsUiq97QCKtYB9tgsxDncZ5j2Emaye4wi/lYWrEK1472KFFykPdHug7L6NBNzGT8s7U1lA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.9/vfs_fonts.min.js" integrity="sha512-EFlschXPq/G5zunGPRSYqazR1CMKj0cQc8v6eMrQwybxgIbhsfoO5NAMQX3xFDQIbFlViv53o7Hy+yCWw6iZxA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    // REGULAR EXPRESSION
    // EMAIL
    const regexp_email = new RegExp(/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/i);
    // PHONE, MOBILE
    const regexp_phone = new RegExp(/^([0-9]{1,3})([7-9][0-9]{9}|[1-9]{1,2}[0-9]{7})$/i); //"/^[0-9]{1,3}[0-9]{7,10}
    // PASSWORD
    const regexp_password = new RegExp(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,20}$/); //(?=.*?[#?!@$%^&*-.])
    // USERNAME
    const regexp_username = new RegExp(/^((?![_.])(?!.*[_.]{2})(?![_.])[_A-Za-z0-9\.\-]*([_A-Za-z0-9\.\-])){4,40}$/i);
    // ILLEGAL CHARACTERS
    const regexp_illegal = new RegExp(/[\'\"\&\<\>\\r\\n\\t\\v]/);
    // PRIVATE IP
    const regexp_private_ip = new RegExp(/^(^127\.0\.0\.1$)|(^::1$)|(^fc00:)|(^[fF][cCdD])|(^10\.)|(^172\.(1[6-9]|2[0-9]|3[0-1])\.)|(^192\.168\.)$/);
    // INT
    const regexp_int = new RegExp(/^\d+$/);
    // URL
    const regexp_url = new RegExp(/[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/);
    
    // REGULAR EXPRESSION
    // EMAIL
    //regexp_email = /^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/i;
    // PHONE, MOBILE
    //regexp_phone = /^([0-9]{1,3})([7-9][0-9]{9}|[1-9]{1,2}[0-9]{7})$/i; //"/^[0-9]{1,3}[0-9]{7,10}
    // PASSWORD
    //regexp_password = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,20}$/; //(?=.*?[#?!@$%^&*-.])
    // USERNAME
    //regexp_username = /^((?![_.])(?!.*[_.]{2})(?![_.])[_A-Za-z0-9\.\-]*([_A-Za-z0-9\.\-])){4,40}$/i;
    // ILLEGAL CHARACTERS
    //regexp_illegal = /[\'\"\&\<\>\\r\\n\\t\\v]/;
    // PRIVATE IP
    //regexp_private_ip = /^(^127\.0\.0\.1$)|(^::1$)|(^fc00:)|(^[fF][cCdD])|(^10\.)|(^172\.(1[6-9]|2[0-9]|3[0-1])\.)|(^192\.168\.)$/;
    
    
    //
    function initInputFormat() {
        
        //
        $(".numeric").keyup(function (event) {
            return $(this).val($(this).val().replace(/[^\d]/, ''));
        });
        //
        $(".decimal").keyup(function (event) {
            return $(this).val($(this).val().replace(/[^\d\.]/, ''));
        });
        //
        $(".money").keyup(function (event) {
            return $(this).val($(this).val().replace(/[^\d\.\,]/, ''));
        });
        //
        $(".alphaNumeric").keyup(function (event) {
            return $(this).val($(this).val().replace(/[^a-zA-Z0-9\.\-\_]/, ''));
        });
        //
        $(".email").keyup(function (event) {
            return $(this).val($(this).val().replace(/[^a-zA-Z0-9\.\-\_\@]/, ''));
        });
        
        //
        $(".money").change(function () {
            $(this).val(number_format($(this).val(), 2));
        });
        
    }
    
    //
    initInputFormat();

    let showModal = (json)=>{
        // console.log(json)
        $(json.modal).modal('show');
    }

    
    let modalLoadingDiv;
    function modalLoading(json) {
        if (json.status === 'show') {
            (modalLoadingDiv).find('div.modal-content').css({display: 'none'});
            (modalLoadingDiv).append('<div class="modal-loading text-center py-5 text-white"><i class="fa fa-spinner fa-spin"></i> ' + (json.text ?? 'Loading...') + '</div>');
        } else {
            setTimeout(() => {
                (modalLoadingDiv).find('div.modal-loading').remove();
                (modalLoadingDiv).find('div.modal-content').css({display: 'block'});
            }, 500);
        }
    }

    
    function formLoading(json) {
        if (json.status === 'show') {
            (modalLoadingDiv).append('<div class="modal-loading text-center py-5 text-blue"><i class="fa fa-spinner fa-spin"></i> ' + (json.text ?? 'Loading...') + '</div>');
        } else {
            setTimeout(() => {
                (modalLoadingDiv).find('div.modal-loading').remove();
            }, 5000);
        }
    }

    // convert excel to json
    let scheduleImport = (json) => {
        // console.log(json); return;
        let exceljson;
        //
        //reset({});
        //
        let regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
        // /*Checks whether the file is a valid excel file*/
        if (regex.test($(json.excelfile).val().toLowerCase())) {
            let xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
            if ($(json.excelfile).val().toLowerCase().indexOf(".xlsx") > 0) {
                xlsxflag = true;
            $( json.table +' tbody').find('tr').remove();
                //console.log($(json.excelfile).val());
            }
            /*Checks whether the browser supports HTML5*/
            if (typeof (FileReader) != "undefined") {
                //console.log(xlsxflag);
                let reader = new FileReader();
                reader.onload = function (e) {
                    let data = e.target.result;
                    /*Converts the excel data in to object*/
                    
                    let workbook;
                    if (xlsxflag) {
                        workbook = XLSX.read(data, {
                            type: 'binary',
                            dateFormat: 'yyyy-mm-dd'
                        });
                    } else {
                        workbook = XLS.read(data, {
                            type: 'binary',
                            dateFormat: 'yyyy-mm-dd'
                        });
                    }
                    
                    // /*Convert the cell value to Json*/
                    
                    if (xlsxflag) {
                        exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]]);
                        let data = JSON.parse(JSON.stringify(exceljson));
                        // console.log(data);return
                        $(".import_btn").html('<i class="fa fa-file-import"></i>Import').prop({disable: false});
                        // console.log(data);return
                        $.each(data, function (k, v) {
                            if(json.action === 'examSchedule'){
                                createExamScheduleRow({
                                    row: k,
                                    data: v,
                                    table: json.table
                                });
                            
                            }else if(json.action === 'classRoutine'){
                                createClassRoutineRow({
                                    row: k,
                                    data: v,
                                    table: json.table
                                });
                            }else if(json.action === 'stdSchedule'){
                                createstdscheduleRow({
                                    row: k,
                                    data: v,
                                    table: json.table
                                });
                            }else if(json.action === 'parentSchedule'){
                                createstdscheduleRow({
                                    row: k,
                                    data: v,
                                    table: json.table
                                });
                            }else if(json.action === 'cashbookSchedule'){
                                createstdscheduleRow({
                                    row: k,
                                    data: v,
                                    table: json.table
                                });
                            }
                        });
                        
                    } else {
                        exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[workbook.SheetNames[0]]);
                    }
                    // console.log(JSON.parse(JSON.stringify(exceljson)));
                    
                }
                if (xlsxflag) {
                    reader.readAsBinaryString(event.target.files[0]);
                    $(".import_btn").html('<i class="fa fa-spinner fa-spin"></i>Import').prop({disable: true});
                } else {
                    reader.readAsArrayBuffer(event.target.files[0]);
                    $(".import_btn").html('<i class="fa fa-spinner fa-spin"></i>Import').prop({disable: true});
                }
            }
            //
            else {
                alert("Sorry! Your browser does not support HTML5!");
            }
        }
        // //
        else {
            alert("Please upload a valid Excel file!");
        }
        // createTable({});
        //
        //scheduleTableCalc();
        
    }

    // schedule export version 2
    let scheduleExportV2 = (json) => {
        
        let heading = [];
        let rows = [];
        let headingrowIndex = [];
        let elem_heading = [...document.querySelector(json.theadRows).children];
        // console.log(elem_heading);
        elem_heading.forEach((td) => {
            let field = td.childNodes[0].data ?? '';
            // let field = $(td).data();
            //
            // if (td.style.backgroundColor !== '') return;
            //console.log(td.style.backgroundColor);
            heading.push(field);
            headingrowIndex.push(td.cellIndex)
        });
        
        headingrowIndex.shift();
        heading.shift();
        
        let body = [...document.querySelector(json.tbodyRows).children];
        // console.log(body.length);return;
        let row = null;
        body.forEach((tr) => {
            // console.log(tr);
            // if (body.length == tr.rowIndex) {
            //     return;
            // }
            
            let td = [...tr.children];
            td.shift();
            //  console.log(td);return 
            //
            row = {};
            headingrowIndex.forEach((v_, k) => {
               
                let data;
                td.forEach((v) => {
                    if (v.cellIndex === v_) {
                        if(v.children.length === 0){
                                row[heading[k]] = '';

                        }else{
                            data = v.children[0];
                            let tag_name = v.children[0].tagName;
                            // console.log(data);
                            if(tag_name === 'INPUT'){
                                row[heading[k]] = data.value ?? '';

                            }else if(tag_name === 'SELECT'){ 
                                let option = data.selectedOptions['0'] ?? {};
                                row[heading[k]] = option.innerText ?? '';

                            }
                        }
                        // console.log(data);
                    }
                    
                })
            });
            rows.push(row);
            
        });
        //  console.log(rows);return;
        var wscols = [];
        let filename = json.filename + '.xlsx';
        let worksheet = XLSX.utils.json_to_sheet(rows);
        for (var i = 0; i < heading.length; i++) {
            wscols.push({wch: heading[i].length + 4});
        }
        worksheet["!cols"] = wscols;
        //const ws = XLSX.utils.aoa_to_sheet(xlsData)
        let workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'myworksheet');
        XLSX.writeFile(workbook, filename);
    }
    
    //
    function timer() {
        let timer = {
            running: false,
            iv: 5000,
            timeout: false,
            cb: function () {
            },
            start: function (cb, iv, sd) {
                let elm = this;
                clearInterval(this.timeout);
                this.running = true;
                if (cb)
                    this.cb = cb;
                if (iv)
                    this.iv = iv;
                if (sd) 
                    elm.execute(elm);
                this.timeout = setTimeout(function () {
                    elm.execute(elm)
                }, this.iv);
            },
            execute: function (e) {
                if (!e.running)
                    return false;
                e.cb();
                e.start();
            },
            stop: function () {
                this.running = false;
            },
            set_interval: function (iv) {
                clearInterval(this.timeout);
                this.start(false, iv);
            }
        };
        return timer;
    }

    
    
    /**
     * @param {type} data
     * @returns {int}
     */
    function getInt(num) {
        num = num.toString();
        try {
            return parseInt('0' + num.replace(/[^0-9\-\+]/g, ''));
        } catch (e) {
            return 0;
        }
    }
    
    /**
     * @param {type} data
     * @returns {float}
     */
    function getFloat(num) {
        num = num.toString();
        try {
            return parseFloat('0' + num.replace(/[^0-9\.\-\+]/g, ''));
        } catch (e) {
            return 0;
        }
    }
    
    /**
     * @returns {undefined}   */
    function number_format(number, decimals = 2, dec_point = '.', thousands_sep = ',') {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        let n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                let k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    
    // extended getInt, getFloat
    //(function($) {
    //})(jQuery);
    
    //
    function dataTablesDistinct(json) {
        // distinct
        let table = $(json.table).DataTable();
        let total = table.rows().count();
        let duplicate = [];
        if (total > 1) {
            for (let i = 1; i < total; i++) {
                let prev = table.row(i - 1).data();
                let curr = table.row(i).data();
                //console.log(prev[json.col], curr[json.col]);
                if (prev[json.col] === curr[json.col]) {
                    duplicate.push(i);
                }
            }
        }
        //console.log(duplicate);
        if (duplicate.length > 0) {
            (duplicate.reverse()).forEach(function (v) {
                table.row(v).remove().draw();
            });
        }
    }
    
    //
    function deleteAny(json) {
        //console.log(json);
        //
        if (confirm('Reverse Item?'))
            //
            $.post('<?php echo URL_ROOT ?>/' + json.url + '/?user_log=<?php //echo $data['params']['user_log'] ?>', json, function (data) {
                //console.log(data);
                //
                if (data.status == "1") {
                    //
                    $('#' + json.table).DataTable().ajax.reload();
                    //
                    new Noty({type: 'success', text: '<h5>Success!</h5>' + data.message, timeout: 10000}).show();
                }
                //
                else {
                    new Noty({type: 'warning', text: '<h5>Warning!</h5>' + data.err, timeout: 10000}).show();
                }
            }, 'JSON');
    }
    
    // upload to azure
    //let AZURE_CONTAINER = <?php echo json_encode(AZURE_CONTAINER) ?>;
    
    function googleDriveUpload(json) {
        let _newName = String(json.newName).toLowerCase();
        // console.log(json.newName);return;
        let value = '';
        for(let item in json.items){
            value = json.items[item];
        }

        if(value === '') {
            return;
        }
        const formData = new FormData();
        const fileField = document.querySelector(json.item);
        
        const ext = fileField.files[0].name.substr(-4);
        
        formData.append('action', 'createFile');
        formData.append('directory', json.directory);
        //
        if ((json.newName ?? '') === '') json.newName = (fileField.files[0].name).slice(0, (fileField.files[0].name).indexOf('.'));
        if (json.unique) json.newName += moment().format('-YYYYMMDD-HHmmss');
        formData.append('newName', (json.newName).replace(/\W/g, '-').replace(/-{2,}/, ''));
        
        formData.append('file', fileField.files[0]);
        // console.log(formData);return;
        if(json.formData === '1'){
            formLoading({status: 'show', text: 'Uploading FIle...'});

        }else{
            
            modalLoading({status: 'show', text: 'Uploading FIle...'});
        }
        
        fetch('<?php echo URL_ROOT ?>/system/googleDriveUpload', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                // console.log('Success:', result);
                $("#picture").val('<?php echo ASSETS_ROOT ?>/temp/'+  formData.get('newName') + ext);
                 setTimeout(modalLoading({status: ''}), 2000)
            })
            .catch(error => {
                // console.error('Error:', error);
                alert('Error: ' + error);
                //
                modalLoading({status: ''});
            });
    }
    
    // delete from azure
    function azureDelete(json) {
        
        const formData = new FormData();
        
        formData.append('action', 'deleteFile');
        formData.append('containerName', AZURE_CONTAINER);
        formData.append('directory', json.directory);
        formData.append('file', json.file);
        
        fetch('<?php echo URL_ROOT ?>/system/azurestorage', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                // console.log('Success:', result);
                new Noty({type: 'success', text: '<h5>Success!</h5>' + result.data, timeout: 10000}).show();
                //
                if ((json.callback ?? '') !== '') {
                    eval(json.callback)({});
                }
            })
            .catch(error => {
                // console.error('Error:', error);
                alert('Error: ' + error);
            });
    }
    
    // display uploaded picture
    function imageChange(json) {
        
        let file = json.event.target.files[0];
        if(json.product_image){
            let filename = file.name ?? '';
            let size = parseInt(file.size ?? '0');
            let type = file.type ?? '';
            let lastmodified = file.lastModified ?? '';
            $("#name").attr("type", 'text').val(filename.substring(0, filename.indexOf('.')))
            $("#type").attr("type", 'text').val(type)
            $("#size").attr("type", 'text').val(Math.ceil(size/1000) + " KB")
            // console.log(file)

        }
        let reader = new FileReader();
        reader.onload = function (e) { 
            $("#picture").val(e.target.result);
            $('#' + json.preview + '--preview').attr('src', e.target.result);
            // let img = new Image();
            // img.src = e.target.result;
            // img.onload = ()=>{
            //     let canvas = document.createElement('canvas');
            //     canvas.width = 400;
            //     canvas.height = 300;
            //     let ctx = canvas.getContext('2d');
            //     ctx.drawImage(img, 0,500);
            //     let imgString = canvas.toDataURL();
            //     $("#picture").val(imgString);
            //     $('#' + json.preview + '--preview').attr('src', imgString);

            // }
        };
        
        reader.readAsDataURL(file);
    }
    
    // fileAutoUpload
    function fileAutoUpload(json) {
        //console.log($(json.elem).prop('id'))
        let form_data = new FormData();
        //
        $.each($(json.elem), function (i, obj) {
            //
            $.each(obj.files, function (j, file) {
                //
                form_data.append('file', file);
            });
            
        });
        
        //
        form_data.append('doc_path', json.doc_path);
        //
        form_data.append('func', 'document--upload');
        
        // process the form
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?php echo URL_ROOT ?>/system/document/?user_log=<?php //echo $data['params']['user_log'] ?>', // the url where we want to POST
            data: form_data, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            contentType: false,
            //encode: true,
            cache: false,
            processData: false
        })
            // using the done promise callback
            .done(function (data, textStatus, jqXHR) {
                // log data to the console so we can see
                //console.log(data);
                //
                if (data.status == '1') {
                    //
                    new Noty({type: 'success', text: '<h5>Success!</h5>' + data.message, timeout: 10000}).show();
                    //
                    $('#documentListTable').DataTable().ajax.reload();
                }
                //
                else {
                    //
                    new Noty({type: 'error', text: '<h5>Error!</h5>' + data.err, timeout: 10000}).show();
                }
            })
            // process error information
            .fail(function (jqXHR, textStatus, errorThrown) {
                
                // log data to the console so we can see
                //console.log(errorThrown);
                new Noty({type: 'error', text: '<h5>Error!</h5>' + errorThrown, timeout: 10000}).show();
                
            });
        
    }

    
    /////////////upload to azure/////////////////////////////////
    let AZURE_CONTAINER = <?php echo json_encode(AZURE_CONTAINER) ?>;
    // console.log(AZURE_CONTAINER);
    
    function azureUpload(json) {
        const formData = new FormData();
        const fileField = document.querySelector(json.item);
        // console.log(fileField);return;
        formData.append('action', 'createFile');
        formData.append('containerName', AZURE_CONTAINER);
        formData.append('directory', json.directory);
        // console.log(formData); return;
        //
        if ((json.newName ?? '') === '') json.newName = (fileField.files[0].name).slice(0, (fileField.files[0].name).indexOf('.'));
        if (json.unique) json.newName += moment().format('-YYYYMMDD-HHmmss');
        formData.append('newName', (json.newName).replace(/\W/g, '-').replace(/-{2,}/, ''));
        
        formData.append('file', fileField.files[0]);
        
        //
        modalLoading({status: 'show', text: 'Uploading FIle...'});
        
        fetch('<?php echo URL_ROOT ?>/system/azurestorage', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                // console.log('Success:', result);
                if (result.status) {
                    let data = result.data ?? [];
                    // input
                    if ($('#' + json.doc_path).length > 0) {
                        $('#' + json.doc_path).val(data.url);
                    }
                    // display
                    if (data.url.slice(-4) === '.pdf') {
                        $(json.display).attr('src', '<?php echo ASSETS_ROOT ?>/images/icons/pdf.jpg').css({height: '90px', width: 'auto'});
                    }
                    // preview
                    if ($(json.preview).length > 0) {
                        $(json.preview).prop({href: data.url}).click();
                    }
                    // link
                    if ($(json.link).length > 0) {
                        $(json.link).html(data.url);
                    }
                    //
                    if ((json.callback ?? '') !== '') {
                        eval(json.callback)({});
                    }
                    // val: '#image', href: '#image-link', src: '#image-img'
                    if ($(json.val).length > 0) {
                        $(json.val).val(data.url);
                    }
                    //
                    if ($(json.href).length > 0) {
                        $(json.href).prop({href: data.url});
                    }
                    //
                    if ($(json.src).length > 0) {
                        $(json.src).attr('src', data.url);
                    }
                } else alert(result.message);
                
                //
                modalLoading({status: ''});
                
            })
            .catch(error => {
                // console.error('Error:', error);
                alert('Error: ' + error);
                //
                modalLoading({status: ''});
            });
    }

    
    // delete from azure
    function azureDelete(json) {
        
        const formData = new FormData();
        
        formData.append('action', 'deleteFile');
        formData.append('containerName', AZURE_CONTAINER);
        formData.append('directory', json.directory);
        formData.append('file', json.file);
        
        fetch('<?php echo URL_ROOT ?>/system/azurestorage', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                // console.log('Success:', result);
                new Noty({type: 'success', text: '<h5>Success!</h5>' + result.data, timeout: 10000}).show();
                //
                if ((json.callback ?? '') !== '') {
                    eval(json.callback)({});
                }
            })
            .catch(error => {
                // console.error('Error:', error);
                alert('Error: ' + error);
            });
    }

    
    function strEllipsis(str, len) {
        if (!str) return null;
        return str.length > len ? str.substr(0, len) + '...' : str;
    }
    
    //
    function sendMail(json) {
        // console.log(json);
        $('.rpt_button').prop({disabled: true});
        $('.email_button').html('<i class="fa fa-spinner spin"></i> Email').prop({disabled: true});
        
        const formData = new FormData();
        
        formData.append('action', json.action);
        formData.append('items', JSON.stringify(json.items));
        
        fetch('<?php echo URL_ROOT ?>/system/sendMail', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log('Success:', result);
                new Noty({type: result.status ? 'success' : 'warning', text: '<h5>Alert!</h5>' + result.message, timeout: 10000}).show();
                //
                $('.rpt_button').prop({disabled: false});
                $('.email_button').html('<i class="fa fa-envelope"></i> Email').prop({disabled: false});
            })
            .catch(error => {
                // console.error('Error:', error);
                $('.rpt_button').prop({disabled: false});
                $('.email_button').html('<i class="fa fa-envelope"></i> Email').prop({disabled: false});
                
                alert('Error: ' + error);
            });
    }
    
    // passwordgenerator
    function passwordGenerate(json) {
        // console.log(json);
        let shuffleArray = (array) => {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                const temp = array[i];
                array[i] = array[j];
                array[j] = temp;
            }
            return array;
        }
        //
        let number = '0123456789';
        let alpha = 'abcdefghijklmnopqrstuvwxyz';
        let special = '@#$%^*/?';
        
        $(json.elem).val(
            shuffleArray(
                (
                    shuffleArray(number.split('')).join('').substr(0, 3) +
                    shuffleArray(alpha.split('')).join('').substr(0, 4) +
                    shuffleArray(alpha.toUpperCase().split('')).join('').substr(0, 2) +
                    shuffleArray(special.split('')).join('').substr(0, 1)
                ).split('')
            ).join('')
        );
        
        return $(json.elem).val();
    }
    
    //
    function fancyFileSize(size) {
        let i = size === 0 ? 0 : Math.floor(Math.log(size) / Math.log(1024));
        return Number((size / Math.pow(1024, i)).toFixed(2)) + ' ' + ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i];
    }
    
    //
    const sortObject = obj => Object.keys(obj).sort().reduce((res, key) => (res[key] = obj[key], res), {});
    
    // function strEllipsis(str, len) {
    //     if (!str) return null;
    //     return str.length > len ? str.substr(0, len) + '...' : str;
    // }
    
    
    let addFormat = (e) => {
        let c = e.target.style.backgroundColor;
        c === '' ? e.target.style.backgroundColor = '#ddeeff' : e.target.style.backgroundColor = '';
        
    }
    // /////////////////////////////////////////////////////////////////////////////////////////
    let uuidv4 = () => {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    
    //
    function uuid() {
        let chars = '0123456789abcdef'.split('');
    
        let uuid = [], rnd = Math.random, r;
        uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
        uuid[14] = '4'; // version 4
    
        for (let i = 0; i < 36; i++) {
            if (!uuid[i]) {
                r = 0 | rnd() * 16;
                uuid[i] = chars[(i === 19) ? (r & 0x3) | 0x8 : r & 0xf];
            }
        }
    
        return uuid.join('');
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function realtime_fb_likes() {
        $.getJSON('https://graph.facebook.com/me?fields=id,name,likes&access_token=EAAIUZAjCZCFz0BO2cy6VUOC1jOwjAca70QlopjZA0ZBbZAuT8GiiIqnZBCmVe1EzZBEv9F4faUBkGtAnETm0Gr9gcPlJTSAuMp9bzKVYnMDe6nVJMZCkZA2RJCm0ScT7GgtxHFYPxtAeVJ5OXeMsUUdrop2vemgpFlWI5pgciXMqsV5KZAXE48usIN0ZCNU9eXjSqyippyZCQQ4AZBxsyB6pdnAZDZD', function(data) {
            // console.log(data.likes.data.length);
        var fb_likes = addCommas(data.likes.data.length);
        $('#fb-likes-count').text(fb_likes);
        });
    }
    function search_by_date() {
        var input, filter,filter_, table, tr, td, i, txtValue, div, post_date;
        input = document.getElementById("notice_date");
        filter_ = input.value.trim();
        filter = moment(input.value.trim()).format('DD MMMM, YYYY');
        // console.log(filter)
        table = document.getElementById("notice_table");
        tr = table.getElementsByTagName("tr");
        // console.log(tr);return;
        if(filter_ === ''){
                // console.log(tr);
            for (i = 0; i < tr.length; i++) {
                td = tr[i].style.display = '';
            }
            return;
        }

        // console.log(tr);
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            // console.log(td)
            if (td) {
                div = td.getElementsByTagName("div")[0];
                if(div){
                    post_date = div.getElementsByClassName("post-date")[0];
                    txtValue = post_date.textContent || post_date.innerText;
                    // console.log(txtValue.indexOf(filter))
                    if (txtValue.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                    // console.log(post_date)
                }
            }       
        }
    }
    //
    function search_by_title() {
        var input, filter, table, tr, td, i, txtValue, div, notice_title;
        input = document.getElementById("notice_title");
        filter = input.value.toUpperCase().trim();
        // console.log(filter)
        table = document.getElementById("notice_table");
        tr = table.getElementsByTagName("tr");
        // console.log(tr);return;
        if(filter === ''){
                // console.log(tr);
            for (i = 0; i < tr.length; i++) {
                td = tr[i].style.display = '';
            }
            return;
        }

        // console.log(tr);
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            // console.log(td)
            if (td) {
                div = td.getElementsByTagName("div")[0];
                if(div){
                    notice_title = div.getElementsByClassName("notice_title")[0];
                    notice_title = div.getElementsByTagName("a")[0];
                    txtValue = notice_title.textContent.toUpperCase().trim() || notice_title.innerText.toUpperCase().trim();
                    // console.log(txtValue)
                    if (txtValue.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                    // console.log(post_date)
                }
            }       
        }
    }

    let printStudentReport = (json)=>{
        // console.log(json);return;
        if(!confirm("Click ok to continue"))return;

        $('.std_report_btn').html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
        if((json.single ?? '') === '' ){
            let term = [];
            let tr = $('input.student-list:checked').closest('tr');
            $.each(tr, (k, v)=>{
                let s = $(v).find('td:eq(11)').html();
                term.push(s)
            })
            json.term = term

        }
        // console.log(json);return;
        // return;
        let url = '<?php echo URL_ROOT ?>/report/studentReport/getResult/?user_log=<?php echo $data["params"]["user_log"] ?>';
        
       // $('.rpt_button').html('<i class="fa fa-spinner fa-spin"></i> Print').prop({disabled: true});
    
        const fileName = (json.filename ?? 'Report').replace(/[^\da-zA-Z]/g, '_') + ' ' + moment().format('YYYY-MM-DD HH-mm-ss') + '.' + (json.export === 'xls' ? 'xlsx' : 'pdf').toLowerCase();
        
        //
        fetch(url, {
            body: JSON.stringify(json),
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            },
        })
            .then(response =>{ 
               return response.blob()
            })
            .then(response => {
                //
                $('.std_report_btn').html('<i class="fa fa-print"></i> Print').prop({disabled: false});
                // console.log(json);
            
                const blob = new Blob([response], {type: 'text/plain'});
                //const blob = new Blob([response], {type: (json.export === 'PDF' ? 'application/pdf' : (json.export === 'XLS' ? 'application/octet-stream' : 'text/plain'))});
                const downloadUrl = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = downloadUrl;
                a.target = '_blank';
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
            });

    }

    let printInvoice = (json)=>{
        // console.log(json);
        // return;
        let url = '<?php echo URL_ROOT ?>/report/invoiceReport/getInvoice/?user_log=<?php echo $data["params"]["user_log"] ?>';
        if(!confirm("Click ok to continue"))return;
        $('.invoice_print').html('<i class="fa fa-spinner fa-spin"></i> Print').prop({disabled: true});
    
        const fileName = (json.filename ?? 'Report').replace(/[^\da-zA-Z]/g, '_') + ' ' + moment().format('YYYY-MM-DD HH-mm-ss') + '.' + (json.export === 'xls' ? 'xlsx' : 'pdf').toLowerCase();
        
        //
        fetch(url, {
            body: JSON.stringify(json),
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            },
        })
            .then(response =>{ 
               return response.blob()
            })
            .then(response => {
                //
                $('.invoice_print').html('<i class="fa fa-print"></i> Print invoice').prop({disabled: false});
                // console.log(json);
            
                const blob = new Blob([response], {type: 'text/plain'});
                //const blob = new Blob([response], {type: (json.export === 'PDF' ? 'application/pdf' : (json.export === 'XLS' ? 'application/octet-stream' : 'text/plain'))});
                const downloadUrl = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = downloadUrl;
                a.target = '_blank';
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
            });
    }
    
    let printReceipt = (json)=>{
        // console.log(json);return
        if(!confirm("Click ok to continue"))return;
        let url = '<?php echo URL_ROOT ?>/report/ReceiptReport/getReceipt/?user_log=<?php echo $data["params"]["user_log"] ?>';
        
        $('.receipt_btn').html('<i class="fa fa-spinner fa-spin"></i> Print').prop({disabled: true});
    
        const fileName = (json.filename ?? 'Report').replace(/[^\da-zA-Z]/g, '_') + ' ' + moment().format('YYYY-MM-DD HH-mm-ss') + '.' + (json.export === 'xls' ? 'xlsx' : 'pdf').toLowerCase();
        
        //
        fetch(url, {
            body: JSON.stringify(json),
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            },
        })
            .then(response =>{ 
               return response.blob()
            })
            .then(response => {
                //
                $('.receipt_btn').html('<i class="fa fa-print"></i> Print').prop({disabled: false});
                // console.log(json);
            
                const blob = new Blob([response], {type: 'text/plain'});
                //const blob = new Blob([response], {type: (json.export === 'PDF' ? 'application/pdf' : (json.export === 'XLS' ? 'application/octet-stream' : 'text/plain'))});
                const downloadUrl = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = downloadUrl;
                a.target = '_blank';
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
            });
    }
    

</script>