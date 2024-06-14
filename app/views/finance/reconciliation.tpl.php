<?php
$data = $data ?? [];
echo $data['menu'];

//
$module_access = $data['user']['access'];

//
$params_ = $data['params'] ?? [];
unset($params_['url']);
?>


    <style>
        .head-row .file-input, .btn-input, .select-input, .segun{
            float: left;
        }
        @media screen and (max-width:576px){
            .bank_stmt_container ,.acctLedger_stmt_cont{
                width: 100%;
            }
        }
        @media screen and (max-width:768px){
            .acctLedger_stmt_cont, .bank_stmt_container{
                width: 50%;
            }
        }
        @media screen and (max-width:992px){
            .acctLedger_stmt_cont, .bank_stmt_container{
                width: 50%;
            }
        }
    </style>
        <!-- main content -->
        <div class="main-body">

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card"> 

                        <div class="">
                            <div class="acctType_label" id="acctType_label" style="float:left;margin: 1px;font-weight:bold;"></div>
                            <div class="acctType_label2" id="acctType_label2" style="float:right;margin: 1px;font-weight:bold;"></div>
                        </div>
                        <div class="card-body px-1 p-md-3" style="overflow: auto;background-color:#f6f9fb;margin-left: -10px">
                            <div class="row justify-content-center">
                                <button type="button" id="new_btn_stmt" onclick="show()" class="btn btn-primary mr-3" style="line-height:1"> New</button>
                                <button type="button" id="prev_btn_stmt" onclick="show_modal()" class="btn btn-primary mr-3" style="line-height:1;">old</button>
                                <button type="button" id="btn_auto" onclick="auto_reconcile()" class="btn btn-primary mr-3" style="line-height:1;">Auto</button>
                            </div>

                            <div class="row head-row" style="margin-left: -10px">
                                <div class="file-input form-group" id="file-input" style="display:none;margin-bottom: 4px;">
                                    <input type="file" class="form-control" id="input2" accept=".xls, .xlsx"/>
                                </div>
                                <div class="btn-input" id="gen_btn_div" style="display:none;">
                                    <button type="button" class="btn btn-primary" id="gen_btn">Generate</button>          
                                </div>
                                <div id="acct_name_sel_div" class="select-input form-group" style="margin-left:4px;display: none">
                                    <select class="form-control acct_name_sel" id="acct_name_sel">
                                    </select>
                                    <input type="text" id="account_name_hidden" style="visibility:hidden"/>
                                </div>
                                <div class="btn-input" id="upload_btn_div" style="display:none;">
                                    <button type="button" class="btn btn-primary" id="upload_btn" onclick="upload_btn()">Upload</button>          
                                </div>

                            </div>

                            <div class="row">  
                                <div class="container bank_stmt_container" id="bank_stmt_container" style="overflow:auto">
                                    <table id="bank_stmt" class="table">
                                    </table>
                                </div>

                                <div class="container acctLedger_stmt_cont" id="acctLedger_stmt_cont" style="overflow:auto">
                                    <table id="acctLedger_stmt" class="table">
                                    </table>
                                </div>
                            </div>

                            <button type="button" id="report_id" style="line-height:1; display: none;"><a class="text-dark-m2" href="<?php echo URL_ROOT ?>/account/reconcile/reconcileReport/?user_log=<?php echo $data["params"]["user_log"] ?>">Generate report</a></button>

                            <!--modal 1-->
                            <div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-8 mb10">
                                                    <input type="text" class="form-control dateTimeLog" id="date_time_modal" placeholder="Date">
                                                </div>
                                            </div>
                                            <hr class="rounded">
                                            <div class="row">
                                                <div class="col-sm-8 mb10">
                                                    <select class="form-control acct_name_sel" id="acct_name_sel_modal"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90" data-dismiss="modal">
                                                <span class="d-none d-sm-inline mr-1">Close <span aria-hidden="true">&times;</span></span>
                                            </button>

                                            <button id="save" type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90" onclick="prev_recon({})">
                                                <span class="d-none d-sm-inline mr-1">View</span>
                                                <i class="fa fa-save text-110 w-2 h-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->   
        </div>


        <?php require_once dirname(dirname(__FILE__)) . '/system/script.jsc.php' ?>

        <script>

            let bankStmt = [];
            let post_obj = {};

            let show_modal = () => {
                $('#editModal').modal('show');


            };

            window.addEventListener('resize', function () {
                let view_port = window.innerWidth;
                if (view_port < 800) {
                    $('#bank_stmt_container').css({'width': '100%'});
                    $('#acctLedger_stmt_cont').css({'width': '100%'});

                } else {
                    $('#bank_stmt_container').css({'width': '50%'});
                    $('#acctLedger_stmt_cont').css({'width': '50%'});

                }
            });
                                
            //auto reconcile
            let auto = () => {
                if (bankStmt.length == 0) return;
                
                let bankId = bankStmt.shift();
                let bankRef = String($('#' + bankId).closest('tr').find('td:eq(1)').html()).replace(/[^0-9a-zA-Z.]/g, '').trim();
                let bankAmount = parseFloat('0' + $('#' + bankId).closest('tr').find('td:eq(2)').html().replaceAll(/[^0-9\.]/g, '').trim())
                        + parseFloat('0' + $('#' + bankId).closest('tr').find('td:eq(3)').html().replaceAll(/[^0-9.]/g, '').trim());
                
                // loop ledger
                $('#acctLedger_stmt').find('tr:gt(0)').each(function () {
                    let tr = $(this);
                    let ledgerId = tr.find('td:eq(4) input').prop('id');
                    let ledgerAmount = parseFloat('0' + tr.find('td:eq(2)').html().replaceAll(/[^0-9\.]/g, '').trim());
                            + parseFloat('0' + tr.find('td:eq(3)').html().replaceAll(/[^0-9\.]/g, '').trim());
                            
                    if ($('#' + ledgerId).prop('checked')) return true;

                    let ledgerRef = String($('#' + ledgerId).closest('tr').find('td:eq(1)').html()).replace(/[^0-9a-zA-Z.]/g, '').trim();
                    // console.log(ledgerRef, bankRef, bankAmount, ledgerAmount);
                    
                    if (bankRef === ledgerRef && bankAmount === ledgerAmount) {
                        // check
                        $('#' + bankId).prop({'checked': true}).css({'background-color': '#78b578'});
                        $('#' + ledgerId).prop({'checked': true}).css({'background-color': '#78b578'});
                        // post to database
                        let bankIdSplit = bankId.split('-');
                        //console.log(bankIdSplit[1]);
                        let ledgerIdSplit = ledgerId.split('-');
                        //console.log(ledgerIdSplit[1]);
                        reconcile_bankStmt({reconcile_id_: bankIdSplit[1], _val: $('#' + bankId).prop('checked'), reconcile: 'manual'});
                        reconcile_ledger({reconcile_id_: ledgerIdSplit[1], _val: $('#' + bankId).prop('checked'), reconcile: 'manual'});

                        return false;
                    }
                });
                auto();
            };

            let auto_reconcile = () => {
                bankStmt = [];
                ledger = [];
                $('#bank_stmt').find('tr:gt(0)').each(function () {
                    let id = $(this).find('td:eq(5) input').prop('id');
                    if ($('#' + id).prop('checked'))
                        return true;
                    bankStmt.push(id);
                });
                auto();
            };
            //load previous bank stmt
            let prev_recon = (json) => {
                $(".head-row").css({display: "none"});
                let view_port = window.innerWidth;
                if (view_port < 800) {
                    $('#bank_stmt_container').css({'width': '100%'});
                    $('#acctLedger_stmt_cont').css({'width': '100%'});

                } else {
                    $('#bank_stmt_container').css({'width': '50%'});
                    $('#acctLedger_stmt_cont').css({'width': '50%'});

                }
                let head_row = '\
                <tr style="color:black;font-weight:bold">\
                    <td style="text-align:left;">DATE</td>\
                    <td style="text-align:left;">REFERENCE NUMBER</td>\
                    <td style="text-align:right;">WITHDRAWAL</td>\
                    <td style="text-align:right;">LODGEMENT</td>\
                    <td style="text-align:right;">BALANCE</td>\
                    <td></td>\
                </tr>';
                let head_row2 = '\
                <tr style="color:black;font-weight:bold">\
                    <td class="" style="text-align:left;">DATE</td>\
                    <td class="" style="text-align:left;">REFERENCE NUMBER</td>\
                    <td class="" style="text-align:right;">DEBIT</td>\
                    <td class="" style="text-align:right;">CREDIT</td>\
                    <td class=""></td>\
                </tr>';
                let date = $('#date_time_modal').val() ?? '';
                let acct_name = $('#acct_name_sel_modal').val() ?? '';
                let acct_name_hidden = $('#account_name_hidden').val() ?? '';
                
                if ((json['load'] ?? '') === 'current') {
                    date = json['date'];
                    acct_name = json['account_code'];
                    
                }
                //////set local storage//////////////////////////
                localStorage.setItem("reconcile_date", date);
                localStorage.setItem("account_code", acct_name);
                ////load bank statement////////////////////
                $.post("<?php echo URL_ROOT ?>/account/reconcile/getBankStatement/?user_log=<?php echo $data["params"]["user_log"] ?>", {action: 'loadbankStmt', date: date, "account_code": acct_name}, function (data) {
                  
                    $('#editModal').modal('hide');
                    // if (data.data.length == 0) {
                    //     new Noty({type: "warning", text: "<h5>WARNING</h5>".data.message, timeout: 10000}).show();
                    //     return;
                    // }
                    $($('#bank_stmt').find('tr')).remove();
                    $('#acctType_label').html('');
                    $('#bank_stmt').append(head_row);
                    $.each(data.data, function (i, v) {
                        // console.log(v['date']);
                        let id = v['id'] ?? '';
                        let date = v['date'] ?? '';
                        let ref_num = v['reference_no'] ?? '';
                        let acct_name = v['account_code'] ?? '';
                        let reconcile = v['reconcile'] ?? '';
                        let debit = v['debit'] ?? '';
                        let credit = v['credit'] ?? '';
                        let balance = v['balance'] ?? '';
                        $('#acctType_label').html(acct_name_hidden);
                        let row = '\
                        <tr style="text-align:left;">\
                            <td>' + date + '</td>\
                            <td>' + ref_num + '</td>\
                            <td style="text-align:right;">' + number_format(debit, 2) + '</td>\
                            <td style="text-align:right;">' + number_format(credit, 2) + '</td>\
                            <td style="text-align:right;">' + number_format(balance, 2) + '</td>\
                            <td><input style="background-color:' + (reconcile == 1 ? "#78b578" : "") + ' " type="checkbox" id="bankStmt-' + id + '" onclick="reconcile_bankStmt({reconcile_id_:\'' + id + '\',_val: $(this).prop(\'checked\'), reconcile:\'manual\'})" ' + (reconcile == 1 ? "checked" : "") + '>\
                            </td>\
                        </tr>';
                        $('#bank_stmt').append(row);
                    });
                    //load general ledger////////////////////////
                    $.post("<?php echo URL_ROOT ?>/account/reconcile/getAccountLedgerList/?user_log=<?php echo $data["params"]["user_log"] ?>", {date: date, account_code: acct_name}, function (data) {
                        // console.log(data);return
                        if (data.data.length == 0) {
                            new Noty({type:"warning", text:"<h5>WARNING</h5> ACCOUNT TYPE NOT FOUND IN THE GL", timeout:10000}).show();
                        }
                        $($('#acctLedger_stmt').find('tr')).remove();
                        $('#acctType_label2').html('');
                        $('#acctLedger_stmt').append(head_row2);
                        $.each(data.data, function (i, v) {
                            let reconcile = v['reconcile'] ?? '';
                            let acct_name = v['account_code'] ?? '';
                            let ref_code = v['ref_code'] ?? '';
                            let debit = v['debit'] ?? '';
                            let credit = v['credit'] ?? '';
                            let id = v['auto_id'] ?? '';
                            $('#acctType_label2').html(acct_name_hidden);
                            let row = '\
                            <tr style="text-align:left;">\
                                <td>' + v['trans_date'] + '</td><td>' + ref_code + '</td>\
                                <td style="text-align:right;">' + number_format(debit, 2) + '</td>\
                                <td style="text-align:right;">' + number_format(credit, 2) + '</td>\
                                <td>\
                                    <input style="background-color:' + (reconcile == 1 ? "#78b578" : "") + ' " type="checkbox" id="cashBook-' + id + '" onclick="reconcile_ledger({reconcile_id_:\'' + id + '\', _val: $(this).prop(\'checked\'), reconcile:\'manual\'})" ' + (reconcile == 1 ? "checked" : "") + '>\
                                </td>\
                            </tr>';
                            $('#acctLedger_stmt').append(row);
                        });
                    }, 'json');
                    $('#report_id').css('display', 'inline')
                }, 'json');
            };
            /////////////////////////////////////////
            let reconcile_ledger = (json) => {

                let reconcile_id = json['reconcile_id_'];
                let reconcile_val = json['_val'];
                let reconcileVal = 0;
                if (json['reconcile'] === 'manual') {

                    reconcileVal = reconcile_val ? 1 : 0;
                }

                $.post("<?php echo URL_ROOT ?>/account/reconcile/reconcileLedger/?user_log=<?php echo $data["params"]["user_log"] ?>", {_id: reconcile_id, _val: reconcileVal}, function (data) {
                    // console.log(data)
                     $('#cashBook-' + reconcile_id).css({'background-color': reconcile_val ? '#78b578' : '#ffffff'});
                }, 'JSON');
            };

            //both manual and auto reconcile
            let reconcile_bankStmt = (json) => {
                // console.log(json)
                let reconcile_id = json['reconcile_id_'];
                let reconcile_val = json['_val'];
                let reconcileVal = 0;
                //manual reconcile
                if (json['reconcile'] === 'manual') {
                    reconcileVal = reconcile_val ? 1 : 0;

                }//manual reconcile

                $.post("<?php echo URL_ROOT ?>/account/reconcile/reconcileBankStatement/?user_log=<?php echo $data["params"]["user_log"] ?>", {_id: reconcile_id, _val: reconcileVal}, function (data) {
                    // console.log(data);
                    if(data.status === true){
                        $('#bankStmt-' + reconcile_id). css({'background-color': reconcile_val ? '#78b578' : '#ffffff'});

                    }
                }, 'JSON');
            };

            let show = () => {
                $('#input2').val('');
                $('#acct_name_sel').val(new Option("", "", true, true)).trigger("change");
                $('#file-input').toggle("slow");
                $('#gen_btn_div').toggle("slow");
                $('#acct_name_sel_div').toggle("slow");
            };

            /////////////////////////////////
            let upload_btn = () => {
                bankStmt = [];
                if (!confirm('Remember,this action will override any duplicate,press ok to proceed')) {
                    return;
                }
                let date = moment($('#bank_stmt').find('tr:eq(1) td:eq(1)').html().trim(), 'DD-MMM-YYYY').format("YYYY-MM-DD");
                let acct_name = $('#acct_name_sel').val() ?? '';
                let acct_name_hidden = $('#account_name_hidden').val() ?? '';
                $('#bank_stmt').find('tr:gt(0)').each(function () {
                    let tr = $(this);
                    let date = moment(tr.find('td:eq(1)').html(), 'DD-MMM-YYYY').format("YYYY-MM-DD");
                    let reference_no = String(tr.find('td:eq(2)').html()).replace(/[^0-9a-zA-Z.]/g, '').trim();
                    let debit = String(tr.find('td:eq(3)').html()).replace(/[^0-9.]/g, '').trim();
                    let credit = String(tr.find('td:eq(4)').html()).replace(/[^0-9.]/g, '').trim();
                    let balance = String(tr.find('td:eq(5)').html()).replace(/[^0-9.]/g, '').trim();
                    let reconcile = tr.find('td:eq(6) input').prop('checked');
                    // console.log(reconcile);
                    if (typeof reconcile === 'undefined') {
                        reconcile = '0';
                    }
                    bankStmt.push({date: date, reference_no: reference_no, debit: debit, credit: credit, balance: balance, reconcile: reconcile});
                    //localStorage.setItem("account_name", acct_name);
                });
                // console.log(bankStmt); return;
                post_obj.action = 'create';
                post_obj.account_code = acct_name;
                post_obj.account_name = acct_name_hidden;
                post_obj.bank_statement = JSON.stringify(bankStmt);
                // console.log(post_obj);return
                $.post('<?php echo URL_ROOT ?>/account/reconcile/saveBankStatement/?user_log=<?php echo $data["params"]["user_log"] ?>', post_obj, function (data) {
                    // console.log(data);
                    if(!data.status){
                        new Noty({type:'warning', text: '<h5>WARNING</h5>'+data.message, timeout: 10000}).show();
                        return;
                    }
                    
                    // new Noty({type:'success', text: '<h5>SUCCESS</h5>'+data.message, timeout: 2000}).show();
                    // if (data.status === true) {
                        setTimeout(function () {
                            prev_recon({load: 'current', account_code: acct_name, date: date});
                        }, 1000);
                    // }
                }, 'json');
            };

            let selectedFile;
            let bankstmt;
            let num = 0;
            let keys;
            document.getElementById("input2").addEventListener("change", (event) => {
                //console.log(event.target.files);
                selectedFile = event.target.files[0];
            });

            /////////////populate the bank statement table//////////////////
            document.getElementById("gen_btn").addEventListener("click", (event) => {
                if ($('#acct_name_sel').val() === '' || $('#input2').val() === '') {
                    $('#upload_btn_div').css({display: 'none'});
                } else {
                    $('#upload_btn_div').css({display: 'inline'});
                }

                /////////////clean localStorage//////////////
                localStorage.setItem("reconcile_date", "");
                localStorage.setItem("account_code", "");

                $('#bank_stmt_container').css('width', '100%');
                $('#file-input').toggle("slow");
                $('#acct_name_sel_div').toggle("slow");
                $('#gen_btn_div').toggle("slow");
                $('#bank_stmt').find('tr').remove();
                $('#acctLedger_stmt').find('tr').remove();
                $('#acctType_label').html('');
                $('#acctType_label2').html($('#acct_name_sel').val());
                let i = 0;
                bankstmt = '';
                keys = '';
                //console.log(event.target.files);
                if (selectedFile) {
                    let fileReader = new FileReader();
                    fileReader.readAsBinaryString(selectedFile);
                    fileReader.onload = (event) => {
                        //console.log(event.target.result);
                        let data = event.target.result;
                        let workbook = XLSX.read(data, {type: "binary"});
                        //console.log(workbook);
                        workbook.SheetNames.forEach(sheet => {
                            ++i;
                            if (i == 1) {
                                bankstmt = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                                return;
                            }
                        });
                        $('#upload_btn_div').css({display: 'inline'});
                        //console.log(bankstmt);return;

                        let head_row = '\
                        <tr style="color:black;font-weight:bold">\
                            <td style="text-align:left;">S/N</td>\
                            <td style="text-align:left;">DATE</td>\
                            <td style="text-align:left;">REFERENCE NUMBER</td>\
                            <td style="text-align:left;">WITHDRAWAL</td>\
                            <td style="text-align:left;">LODGEMENT</td>\
                            <td style="text-align:left;">BALANCE</td>\
                        </tr>';
                        $('#bank_stmt').append(head_row);
                        let body_row;
                        $.each(bankstmt, function (i, v_body) {
                            let SN = v_body['S/NO'] ?? '';
                            let date = v_body['DATE'] ?? '';
                            let ref_num = v_body['REFERENCE NUMBER'] ?? '';
                            let debit = v_body['WITHDRAWAL'] ?? '';
                            let credit = v_body['LODGEMENT'] ?? '';
                            let balance = v_body['BALANCE'] ?? '';
                            // if (SN === undefined) {
                            //     SN = "";
                            // }
                            // if (date === undefined) {
                            //     date = "";
                            // }
                            // if (ref_num === undefined) {
                            //     ref_num = "";
                            // }
                            // if (debit === undefined) {
                            //     debit = "";
                            // }
                            // if (credit === undefined) {
                            //     credit = "";
                            // }
                            // if (balance === undefined) {
                            //     balance = "";
                            // }
                            body_row = '\
                            <tr>\
                                <td>' + SN + '</td><td>' + date + '</td>\
                                <td>' + ref_num + '</td><td>' + number_format(debit, 2) + '</td>\
                                <td>' + number_format(credit, 2) + '</td>\
                                <td>' + number_format(balance, 2) + '</td>\
                            </tr>';
                            $('#bank_stmt').append(body_row);
                        });
                    };
                }
            });
            ////////////
            $(function ($) {

                $(".acct_name_sel").select2({
                    placeholder: "Select account name",
                    allowClear: true,
                    ajax: {
                        url: "<?php echo URL_ROOT ?>/account/accountSetting/getCompanyBanks/?user_log=<?php echo $data['params']['user_log'] ?>",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term,
                                _option: 'select2'
                            };
                        },
                        processResults: function (response) {
                            //console.log(response);
                            return {results: response};
                        },
                        cache: true
                    }
                }).on("select2:select", ()=>{
                    $("#account_name_hidden").val($("#acct_name_sel")["0"].selectedOptions[0].innerHTML)
                    // console.log("scared")
                })

                flatpickr('#date_time_modal', {
                    dateFormat: 'Y-m-d'
                });
                $('#input2').val("");
            });
        </script>
