<?php
//
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING); // Update to PHP 8
ini_set("memory_limit","256M");

// environment params
try {
    $define = json_decode(file_get_contents(getenv('DB_SCH_MGT', true), true), true);
} catch (Exception $e) {
    echo $e->getMessage(); exit;
}
//var_dump($define);
//echo '<pre>' . print_r($define, true) . '</pre>'; exit;

//
define('DB_HOST', $define['DB_HOST']);
define('DB_USER', $define['DB_USER']);
define('DB_PASS', $define['DB_PASS']);
define('DB_NAME', $define['DB_NAME']);
define('DB_PORT', $define['DB_PORT']);
define('APP_ID', $define['APP_ID']);
define('URL_ROOT', $define['URL_ROOT']);
define('URL_PROXY', $define['URL_PROXY']);
define('JASPER_URL', $define['JASPER_URL']);
define('DEPLOYMENT', $define['DEPLOYMENT']);
define('SCHMGT_CATEGORY_DISABLED', strtolower($define['SCHMGT_CATEGORY_DISABLED'] ?? '') == 'true' ||  strtolower($define['SCHMGT_CATEGORY_DISABLED'] ?? '') == 'yes' || $define['SCHMGT_CATEGORY_DISABLED'] == '1');
define('AUTHENTICATOR', strtolower($define['AUTHENTICATOR'] ?? '') == 'true' ||  strtolower($define['AUTHENTICATOR'] ?? '') == 'yes' || $define['AUTHENTICATOR'] == '1');
define('PERIOD_OVERLAP', strtolower($define['PERIOD_OVERLAP'] ?? '') == 'true' ||  strtolower($define['PERIOD_OVERLAP'] ?? '') == 'yes' || $define['PERIOD_OVERLAP'] == '1');
define('DISABLE_MAKER_CHECKER', $define['DISABLE_MAKER_CHECKER'] == 'true' || $define['DISABLE_MAKER_CHECKER'] == 'yes' || $define['DISABLE_MAKER_CHECKER'] == '1');
define('ADDRESS', $define['ADDRESS']);
define('ADDRESS2', $define['ADDRESS2']);
define('ADDRESS3', $define['ADDRESS3']);
define('PHONE', $define['PHONE']);
define('WWW', $define['WWW']);
define('EMAIL', $define['EMAIL']);
define('REPLY_TO', $define['REPLY_TO']);
define('RC', $define['RC']);
define('SITE_NAME', $define['SITE_NAME']);
define('APP_NAME', $define['APP_NAME']);
define('SEND_GRID', $define['SEND_GRID']);
define('AZURE_STORAGE', $define['AZURE_STORAGE']);
define('AZURE_CONTAINER', $define['AZURE_CONTAINER']);
define('AZURE_BLOB', $define['AZURE_BLOB']);
define('DEFAULT_BANK', $define['DEFAULT_BANK']);
define('DEFAULT_BRANCH', $define['DEFAULT_BRANCH']);

const USER_ACCESS = array(
    'requisition' => [ '_main' => '', 'authorise' => '', 'verify' => '', 'approve' => '', 'process' => ''],
    'school'=> array('_main'=> '','allStudents'=> "",'admissionForm'=> "",'studentDetails'=> '','subject' => "", "class"=>"", "teacher"=>"",
        "parent" => "", "student"=> "", "teachers"=>"", "classRoutine" => "", "exam" => "", "notice"=> "", "promotion"=>"", "history"=>"", "questionAnswer"=> "",
        "setExams" => "", "allQuestionAnswer"=>"", "parents"=>"", "subject_type"=>""
    ),
    'finance' => array('_main' => '', 'admin'=>'', 'invoice' => '','receipt' => '', 'payable' => '', 'report'=>'',
        'tool' => '', 'setup' => '', 'requisition_setup'=>'', 'admin'=>'', 'budget_expense'=>'',
     'budget_revenue'=>'', 'cashbook_approve'=>''
    ),
    'system' => array('_main' => '', 'technical' => '', 'user' => '', 'branch' => '', 'category'=> '', "mapping" => "", "term"=>"", "social"=> "")
);

const SCHOOL_PERIOD = array(
    "period1"=> "7:30 - 8:10",
    "period2"=> "8:10 - 8:20",
    "period3"=> "8:20 - 8:55",
    "period4"=> "8:55 - 9:30",
    "period5"=> "9:30 - 10:05",
    "period6"=> "10:05 - 10:40",
    "period7"=> "10:40 - 11:10",
    "period8"=> "11:10 - 11:45",
    "period9"=> "11:45 - 12:20",
    "period10"=> "12:20 - 12:55",
    "period11"=> "12:55 - 1:30",
    "period12"=> "1:30 - 2:05",
    "period13"=> "2:05 - 2:40",
    "period14"=> "2:40 - 3:15",
);


const OTHER_PERIOD_NAME = array(
    array("id"=>"ASSEMBLY", "text"=> "ASSEMBLY"),
    array("id"=>"REGISTRATION", "text"=> "REGISTRATION"),
    array("id"=>"LUNCH BREAK", "text"=> "LUNCH BREAK"),
    array("id"=>"SHORT BREAK AND TUCK SHOP", "text"=> "SHORT BREAK AND TUCK SHOP")
);

const TIMETABLE_NAME = array(
    array("id"=>"GENERAL", "text"=> "GENERAL TIMETABLE"),
    array("id"=>"SCHOOL", "text"=> "SCHOOL TIMETABLE"),
);
///
const TRANS_MODE = [
    'TRF'   => 'Transfer',
    'CAD'   => 'Card',
    'CHB'   => 'Cheque',
    'CSH'   => 'Cash',
    'DDI'   => 'Direct Debit',
    'POS'   => 'Point of Sale',
    'CRD'   => 'Credit',
];

const TRANS_TYPE = [
    'DN'    => 'Debit',
    'CN'    => 'Credit',
    'RCP'   => 'Receipt',
    'RCP2'  => 'Receipt adjustment',
    'PYM'   => 'Commission',
    'PYM2'  => 'Commission deduction',
    'VAT'   => 'Vat',
    'NIA'   => 'NIA tax',
    'CHG'   => 'Charges',
    'CSH'   => 'Cash',
    'JNL'   => 'Journal',
    'CHB'   => 'Cashbook',
    'OPB'   => 'Opening balance',
    'ASS'   => 'Asset',
    'CLT'   => 'Client',
    'AGT'   => 'Agent',
    'BRK'   => 'Broker',
    'CMP'   => 'Company',
    'REC'   => 'Receivable',
    'CLM'   => 'Claim',
    'COM'   => 'Commission',
    'FAC'   => 'Facultative',
    'RTN'   => 'Return premium',
    'PAY'   => 'Payable',
];

const TRANS_TYPE_REC = ['CLT', 'AGT', 'BRK', 'CMP', 'REC'];
const TRANS_TYPE_PAY = ['CN', 'PYM', 'PYM2', 'VAT', 'NIA', 'CLM', 'COM', 'FAC', 'RTN', 'PAY'];


const UPLOAD_DOCUMENTS = [
    //
    'requisition' => [
        'pre-approval' => 'Pre approval memo',
        'quotation-01' => 'Quotation #01',
        'quotation-02' => 'Quotation #02',
        'quotation-03' => 'Quotation #03',
    ]
    
];


// Application root
define('APP_ROOT', dirname(dirname(__FILE__)));

// Assets root
const ASSETS_ROOT = URL_ROOT . '/public/assets';
const APP_LOGO = ASSETS_ROOT . '/images/app-icon.svg';

const BASE_CURRENCY = 'NGN';
const BASE_RATE = 1.00;
const DATATABLE_LIMIT = 10000;
//var_dump($define);
//echo '<pre>' . print_r($define, true) . '</pre>'; exit;

//
const ACCEPT_FILE_TYPE = 'image/*,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv';

//
const LOREM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

// google
const GOOGLE_RECAPTCHA_PK = '6Lf-140UAAAAABr7MCagDj3KVmkszY2lr3eGpnNn';
const GOOGLE_RECAPTCHA_SK = '6Lf-140UAAAAAED1EH9obGqF4pLmi1Y39QNi2OgS';

///
const PASSWORD_COMPLEXITY = 'Between 8 and 128 characters long. Use at least the following types of characters: (a) uppercase letters, (b) lowercase letters, (c) numbers, and (d) special characters';

//google drive api credentials
// const CLIENT_ID = "854153959009-4lve0o0ro64h6p5kfrabjg3jh63e0irt.apps.googleusercontent.com";
// const CLIENT_SECRET = "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";
// const REDIRECT_URL = "https://developers.google.com/oauthplayground";
// const REFRESH_TOKEN = "1//04UBIYFKimrJvCgYIARAAGAQSNwF-L9Ir9Q_pvwyO8b2xawjkc8xslpMku1YoMVbb44IF1warF0mhJlxsGA_ZureReCRD2a3CCZI";
//
// const GOOGLE_WEB_CLIENT_ID = '854153959009-4lve0o0ro64h6p5kfrabjg3jh63e0irt.apps.googleusercontent.com';

// const GOOGLE_WEB_CLIENT_SECRET = "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";

// const GOOGLE_ACCESS_SCOPE = 'https://www.googleapis.com/auth/drive';

// const AUTHORIZED_REDIRECT_URI = 'http://localhost/sch_mgt/system/login';

// const GOOGLE_OAUTH2_TOKEN_URI = 'https://oauth2.googleapis.com/token';

// const GOOGLE_DRIVE_FILE_UPLOAD_URI = 'https://www.googleapis.com/upload/drive/v3/files';

// const GOOGLE_DRIVE_FILE_META_URI = 'https://www.googleapis.com/drive/v3/files/';

// if(!session_id()) session_start();

    //Authorization URL
// $gOauthURL = "https://accounts.google.com/o/oauth2/auth?scope=". (urlencode(GOOGLE_ACCESS_SCOPE)). "&redirect_uri=". (urlencode(AUTHORIZED_REDIRECT_URI)). "&client_id=" . (urlencode(GOOGLE_WEB_CLIENT_ID)) . "&access_type=online&response_type=code" ;

// if(isset($_GET['code'])) $_SESSION['code'] = $_GET['code'];
// if(!isset($_SESSION['code'])) header("location: {$gOauthURL}");

//  echo json_encode(['status' => false, "message" => $_SESSION['code']]);exit;

