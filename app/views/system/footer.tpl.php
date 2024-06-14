<script>
    let select2_data = {}
    
    //
    $.post('<?php echo URL_ROOT ?>/system/systemSetting/getTerms', { _option: 'select' }, function(data) {
        
        data.push({id:"all", text: "All"});
        // console.log(data);
        select2_data['term_code'] = data;
        //
            $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getActgroups', { _option: 'select' }, function(data) {
                select2_data['rpt_group_code'] = data;
                        //
            $.post('<?php echo URL_ROOT ?>/finance/accountSetting/getAccounts', { _option: 'select' }, function(data) {
                // console.log(data);
                select2_data['rpt_account_code'] = data;
            }, 'JSON')
        }, 'JSON')
    }, 'JSON');

    //
    select2_data['rpt_act_type'] = [
        {id: 'trial_balance', text: 'Trial Balance'},
        {id: 'balance_sheet', text: 'Balance Sheet'},
        {id: 'income_statement', text: 'Income Statement'},
        {id: 'financial_position', text: 'Financial Position'},
    ]
    
    //
    select2_data['rpt_act_period'] = [
        {id: '3', text: 'Quarter One'},
        {id: '6', text: 'Quarter Two'},
        {id: '9', text: 'Quarter Three'},
        {id: '12', text: 'Quarter Four'},
    ]
    
    //
    function reportForm(json) {
        //
        // console.log(json);
        // console.log(Math.ceil(moment().format('M') / 3.1 * 3));return;
        //
        if ($('#reportFormDiv').length > 0) {
            $('#reportFormDiv').remove();
        }
    
        //
        let firstDayCurMonth = new Date(new Date().getFullYear(), new Date().getMonth() - 0, 1);
        //
        let lastDayCurMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);
    
        //
        let reportFormDiv =
            '<form id="reportFormDiv" style="padding: 20px; border: 1px solid #ccc; background-color: #fefefe; border-radius: 10px;"></form>';
        //
        let rpt_start_date = '\
            <div class="form-group row">\
                <label for="rpt_start_date" class="col-md-4 col-form-label text-sm-right">Start Date <br><span class="small text-warning">Required (YYYY-MM-DD)</span></label>\
                <div class="col-md-8 pr-3">\
                    <input class="form-control form-control-sm" type="text" id="rpt_start_date" value="' + firstDayCurMonth.getFullYear() + '-' + (firstDayCurMonth.getMonth() + 1) + '-01" maxlength="10">\
                </div>\
            </div>';
        //
        let rpt_end_date = '\
            <div class="form-group row">\
                <label for="rpt_end_date" class="col-md-4 col-form-label text-sm-right">End Date <br><span class="small text-warning">Required (YYYY-MM-DD)</span></label>\
                <div class="col-md-8 pr-3">\
                    <input class="form-control form-control-sm" type="text" id="rpt_end_date" value="' + lastDayCurMonth.getFullYear() + '-' + (lastDayCurMonth.getMonth() + 1) + '-' + lastDayCurMonth.getDate() + '" maxlength="10">\
                </div>\
            </div>';
        //
        let rpt_ref_date = '\
            <div class="form-group row">\
                <label for="rpt_ref_date" class="col-md-4 col-form-label text-sm-right">Reference Date <br><span class="small text-warning">Required (YYYY-MM-DD)</span></label>\
                <div class="col-md-8 pr-3">\
                    <input class="form-control form-control-sm" type="text" id="rpt_ref_date" value="' + lastDayCurMonth.getFullYear() + '-' + (lastDayCurMonth.getMonth() + 1) + '-' + lastDayCurMonth.getDate() + '" maxlength="10">\
                </div>\
            </div>';
        //
        let term_code = '\
            <div class="form-group row">\
                <label for="term_code" class="col-md-4 col-form-label text-sm-right">Term <br><span class="small text-warning">Required</span></label>\
                <div class="col-md-8 pr-3">\
                    <select id="term_code" class="form-control form-control-sm" style="width: 100%">\
                        <option value=""></option>\
                    </select>\
                </div>\
            </div>';
        //
        
        let rpt_group_code = '\
            <div class="form-group row">\
                <label for="rpt_group_code" class="col-md-4 col-form-label text-sm-right">Account Group <br><span class="small text-info">Optional</span></label>\
                <div class="col-md-8 pr-3">\
                    <select id="rpt_group_code" class="form-control form-control-sm" style="width: 100%">\
                        <option value=""></option>\
                    </select>\
                </div>\
            </div>';
        //
        let rpt_account_code = '\
            <div class="form-group row">\
                <label for="rpt_account_code" class="col-md-4 col-form-label text-sm-right">Account Code <br><span class="small text-info">Optional</span></label>\
                <div class="col-md-8 pr-3">\
                    <select id="rpt_account_code" class="form-control form-control-sm" style="width: 100%">\
                        <option value=""></option>\
                    </select>\
                </div>\
            </div>';
        //
        let rpt_act_type = '\
            <div class="form-group row">\
                <label for="rpt_act_type" class="col-md-4 col-form-label text-sm-right">Account Type <br><span class="small text-warning">Required</span></label>\
                <div class="col-md-8 pr-3">\
                    <select id="rpt_act_type" class="form-control form-control-sm" style="width: 100%">\
                    </select>\
                </div>\
            </div>';
            ///////
        let rpt_act_period = '\
            <div class="form-group row">\
                <label for="rpt_act_period" class="col-md-4 col-form-label text-sm-right">Account Period <br><span class="small text-warning">Required</span></label>\
                <div class="col-md-8 pr-3">\
                    <select id="rpt_act_period" class="form-control form-control-sm" style="width: 100%">\
                    </select>\
                </div>\
            </div>';
            ///
        let rpt_button = '\
            <div class="form-group row">\
                <div class="offset-md-4 col-md-8 ">\
                    <button type="button" class="btn btn-primary btn-sm rpt_button" onclick="reportSubmit({rpt_report:\'' + json.report + '\'})"><i class="fa fa-print"></i> Print</button>\
                </div>\
            </div>';
        //
        $('#'+json.div).after(reportFormDiv);
        //
        if (json.elem.length > 0) {
            //
            json.elem.forEach(function(i) {
                //
                $('#reportFormDiv').append(eval(i));
                //
                if (i === 'rpt_start_date' || i === 'rpt_end_date' || i === 'rpt_ref_date' ) {
                    flatpickr('#' + i, {
                        dateFormat: 'Y-m-d',
                        minDate: '1920-01-01',
                    });
                }

                //
                if (i === 'term_code' || i === 'rpt_act_type' || i === 'rpt_act_period') {
                    $('#' + i).select2({
                        placeholder: "Select an option",
                        allowClear: true,
                        data: select2_data[i] ?? [],
                    });
                    // set selected
                    if (i === 'rpt_act_period') {
                        $('#rpt_act_period').val((Math.ceil(moment().format('M') / 3.1) * 3).toString()).trigger('change');
                    }
                }
                if(i === 'rpt_group_code'){
                    $('#' + i).select2({
                        placeholder: "Select an option",
                        allowClear: true,
                        data: select2_data[i] ?? [],
                    }).on("select2:select", (e)=>{
                        let group_code = $(e.currentTarget).val();
                        let selectElem = $('#reportFormDiv').find("#rpt_account_code");
                        console.log($('#reportFormDiv').find("#rpt_account_code"))
                        $(selectElem).select2({
                            placeholder: "Select an option",
                            allowClear: true,
                            ajax: {
                                url: "<?php echo URL_ROOT ?>/finance/AccountSetting/getAccounts",
                                type: "post",
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        searchTerm: params.term,
                                        _option: 'select',
                                        group_code: group_code
                                    };
                                },
                                processResults: function (response) {
                                    //console.log(response);
                                    return { results: response };
                                },
                                cache: true
                            }
                        });
                        console.log(group_code)
                    });
                }
            });
            //
            $('#reportFormDiv').append(rpt_button);
        }
    }

    //
    function reportSubmit(json) {
        //
        $('.rpt_button').html('<i class="fa fa-spinner fa-spin"></i> Print').prop({disabled: true});
        
        // console.log($('#reportFormDiv'));return;
        //
        $.each($('#reportFormDiv input, select, textarea'), function (i, obj) {
            //
            if (obj['id'] == '') return true;
            //console.log(obj['id']);
            //
            if ($('#' + obj['id']).prop('type') == 'checkbox') {
                //
                //form_data.append(obj['id'], ($('#' + obj['id']).prop('checked') ? "1" : "0"));
                json[obj['id']] = ($('#' + obj['id']).prop('checked') ? "1" : "0");
            }
            //
            else {
                //form_data.append(obj['name'], obj['value']);
                json[obj['id']] = obj['value'];
            }
        
        });

        json.report = json.rpt_report;
        // json.rpt_report === 'finance_naicom_statement' ? json.report = 'naicom_statement' : json.report = json.rpt_report;
        json.message = '';
        
        // check input
        if ((json.rpt_start_date === '' || json.rpt_end_date === '') && (json.report === 'production_statement' || json.report === 'ledger_entry' || json.report === 'journal_entry' || json.report === 'receivable_statement' || json.report === 'payable_statement')) json.message = 'Start and End dates required';
        //
        if (json.rpt_ref_date === '' && (json.report === 'production_earned' || json.report === 'financial_statement')) json.message = 'Reference date required';
        if (json.term_code === '') json.message = 'TERM REQUIRED';
        
        if (json.message !== '') json.report = '';
        // console.log(json)
        printController(json);
    
    }

    
    let printController = (json) => {
        //  console.log(json);return;
        if (json.report === '') {
            //
            $('.rpt_button').html('<i class="fa fa-print"></i> Print').prop({disabled: false});
            new Noty({type: 'warning', text: '<h5>WARNING!</h5> ' + json.message, timeout: 10000}).show();
            return false;
        }
        let url = '<?php echo URL_ROOT ?>/report/Report/?user_log=<?php echo $data['params']['user_log'] ?>';

        //
        const fileName = (json.filename ?? 'Report').replace(/[^\da-zA-Z]/g, '_') + ' ' + moment().format('YYYY-MM-DD HH-mm-ss') + '.' + (json.export === 'xls' ? 'xlsx' : 'pdf').toLowerCase();

        fetch(url, {
            body: JSON.stringify({report: json.report, items: json}),
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
                $('.rpt_button').html('<i class="fa fa-print"></i> Print').prop({disabled: false});
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
</body>
</html>