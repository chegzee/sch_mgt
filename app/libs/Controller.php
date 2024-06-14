<?php

// Load the model and view
class Controller
{
    
    private $db;
    
    // REGULAR EXPRESSION
    // EMAIL
    public $regexp_email = '/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/i';
    // PHONE, MOBILE
    public $regexp_phone = '/^([0-9]{1,3})([7-9][0-9]{9}|[1-9]{1,2}[0-9]{7})$/i'; //"/^[0-9]{1,3}[0-9]{7,10}
    // PASSWORD
    public $regexp_password = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,20}$/'; //(?=.*?[#?!@$%^&*-.])
    // USERNAME
    public $regexp_username = '/^((?![_.])(?!.*[_.]{2})(?![_.])[_A-Za-z0-9\.\-]*([_A-Za-z0-9\.\-])){4,40}$/i';
    // ILLEGAL CHARACTERS
    public $regexp_illegal = '/[\'\"\&\<\>\\r\\n\\t\\v]/';
    // PRIVATE IP
    public $regexp_private_ip = '/^(^127\.0\.0\.1$)|(^::1$)|(^fc00:)|(^[fF][cCdD])|(^10\.)|(^172\.(1[6-9]|2[0-9]|3[0-1])\.)|(^192\.168\.)$/';
    // mime types
    public $mime_types = array(
        'csv' => 'text/csv',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'png' => 'image/png',
        'pdf' => 'application/pdf',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'rtf' => 'application/rtf',
        'txt' => 'text/plain',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    );
    
    public $column_int = array('val', 'password_attempt', 'user_period', 'range_min', 'range_max');
    public $column_float = array('currency_rate', 'vat_rate', 'loss_amount', 'paid_amount', 'vat_amount', 'other_charge', 'amount', 'price', 'fees', 'level_fees');
    
    ///////////////////////////////////////////////////////////////
    
    public function __construct()
    {
        //
        $this->db = new Database();
        
    }
    
    // load the model
    public function model($model)
    {
        // Require model file
        require_once '../app/models/' . $model . '.php';
        // instantiate model
        return new $model;
    } 
    
    // Load the view
    public function view($view, $data = array())
    {
        if (file_exists('../app/views/' . $view . '.tpl.php')) {
            require_once '../app/views/' . $view . '.tpl.php';
        } else {
            die ('View does not exists');
        }
    }
    
    /////////////////////////////////////////////////////////////////////////////
    
    // get URL
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            // filter variables as string/number
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // breaking into array
            $url = explode('/', $url);
            
            return $url;
        }
    }
    
    // get $_SERVER['QUERY_STRING']
    public function getParams()
    {
        return filter_input_array(INPUT_GET);
    }
     
    // Load menu
    public function getHeader()
    {
        //
        $url = $this->getUrl();
        //
        $params = $this->getParams();
        unset($params['url']);
        // unset($params['client_code']);
        unset($params['list_option']);
        
        // verify user
        $user = $this->model('SystemData')->verifyUser(array('user_log' => $params['user_log']));
        
        // $help = json_decode(file_get_contents(str_ireplace('/' . AZURE_CONTAINER, '', AZURE_BLOB) . '/help/toc.json', true), true);
        
        //
        // $risks = $this->model('TechnicalData')->getRisks(array());
        
        //
        $menu = '
        
        <div class="sidebar">
            
            <div class="sidebar-header">
                <a href="' . URL_ROOT . '/system/dashboard/?user_log=' . $params['user_log'] . '" class="logo">
                    <img src="' . ASSETS_ROOT . '/images/favicon.png" alt="Logo">
                    ' . APP_NAME . '
                </a>
                <a href="#" class="nav-link nav-icon rounded-circle ml-auto" data-toggle="sidebar">
                    <i class="material-icons">close</i>
                </a>
            </div>
            
            <div class="sidebar-body">
                <ul class="nav nav-sub" id="menu">
                    <li class="nav-label">ADMIN</li>
                    <li class="nav-item ' . ($url[0] == 'system' && $url[1] == 'dashboard' ? 'active' : '') . '">
                        <a class="nav-link has-icon" href="' . URL_ROOT . '/system/dashboard/?user_log=' . $params['user_log'] . '" data-breadcrumb="false"><i class="material-icons">pie_chart</i>System Monitoring</a>
                        <li>&nbsp;</li>
                    </li>
                    
                    '
                    .($user->access->requisition->_main !== '1' ? '' : '<li class="nav-item '. ($url[0] == 'school' && $url[1] == 'requisition' ? 'active' : '') .'"><a class="nav-link" href="' . URL_ROOT . '/system/requisition/?' . urldecode(http_build_query($params)) . '&list_option="><i class="material-icons">subjects</i>Requisition</a></li>')
                    .('<hr class="dashed">')
                    .($user->access->school->promotion !== "1" ? '' : '<li class="nav-item '. ($url[0] == 'school' && $url[1] == 'promotion' ? 'active' : '') .'"><a class="nav-link" href="'. URL_ROOT .'/school/promotion/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">subjects</i>Promotion</a></li>')
                    .($user->access->school->history !== "1" ? '' : '<li class="nav-item '. ($url[0] == 'school' && $url[1] == 'studentHistory' ? 'active' : '') .'"><a class="nav-link" href="'. URL_ROOT .'/school/studentHistory/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">subjects</i>History</a></li>')
                    .($user->access->school->parents !== "1" ? '' : '<li class="nav-item '. ($url[0] == 'school' && $url[1] == 'parents' ? 'active' : '') .'"><a class="nav-link" href="'. URL_ROOT .'/school/parents/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">subjects</i>Parents</a></li>')
                    .($user->access->school->notice !== "1" ? '' : '<li class="nav-item '. ($url[0] == 'school' && $url[1] == 'notice' ? 'active' : '') .'"><a class="nav-link" href="'. URL_ROOT .'/school/notice/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">subjects</i>Notice</a></li>')
                    .($user->access->school->allQuestionAnswer !== "1" ? '' : '<li class="nav-item '. ($url[0] == 'school' && $url[1] == 'allQuestionAnswer' ? 'active' : '') .'"><a class="nav-link" href="'. URL_ROOT .'/school/allQuestionAnswer/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">subjects</i>Questions</a></li>')
                    .(
                        $user->access->school->_main != '1' ? '' : '
                        <li class="nav-item ' . ($url[0] == 'school' && $url[1] == 'students' || $url[1] == 'admissionForm' || $url[1] == 'studentsDetails' ? 'active' : '') . '">
                            <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">class</i>Students</a>
                            <ul>
                                '.($user->access->school->allStudents != "1" ? '' : '<li><a class="' . ($url[0] == 'school' && $url[1] == 'students' ? 'active' : '') . '" href="'. URL_ROOT .'/school/students/?'. urldecode(http_build_query($params)).' &list_option=0">All students</a></li>').'
                                '.($user->access->school->studentDetails != "1" ? '' : '<li class="' . ($url[0] == 'school' && $url[1] == 'studentDetails' ? 'active' : '') . '"><a class="nav-link" href="#">Student details</a></li>').'
                                '.($user->access->school->admissionForm != "1" ? '' : '<li><a class=" ' . ($url[0] == 'school' && $url[1] == 'admissionForm' ? 'active' : '') . ' " href="'. URL_ROOT .'/school/admissionForm/?'. urldecode(http_build_query($params)).' ">Admission form</a></li>').'
                            </ul>
                        </li>'
                    ) 
                    .(
                        $user->access->school->teacher != '1' ? '' : '
                        <li class="nav-item ' . ($url[0] == 'school' && $url[1] == 'teacher' || $url[1] == 'teachers' || $url[1] == 'teacherDetails' ? 'active' : '') . '">
                        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">class</i>Teachers</a>
                        <ul>
                            '.($user->access->school->teachers != "1" ? '' : '<li><a class="' . ($url[0] == 'school' && $url[1] == 'teachers' ? 'active' : '') . '" href="'. URL_ROOT .'/school/teachers/?'. urldecode(http_build_query($params)).' &list_option=0">All teachers</a></li>').'
                            '.($user->access->school->teacherDetails != "1" ? '' : '<li class="' . ($url[0] == 'school' && $url[1] == 'teacherDetails' ? 'active' : '') . '"><a class="nav-link" href="#">Teacher details</a></li>').'
                            '.($user->access->school->teacher != "1" ? '' : '<li><a class=" ' . ($url[0] == 'school' && $url[1] == 'teacher' ? 'active' : '') . ' " href="'. URL_ROOT .'/school/teacher/?'. urldecode(http_build_query($params)).' ">New teacher</a></li>').'
                        </ul>
                    </li>'
                    ).
                    (
                        
                        $user->access->school->exam != '1' ? '' : '
                        <li class="nav-item ' . ($url[0] == 'school' && $url[1] == 'examSchedule' || $url[1] == 'examGrade' ? 'active' : '') . '">
                            <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">class</i>Exam </a>
                            <ul>
                                <li><a class="' . ($url[0] == 'school' && $url[1] == 'examSchedule' ? 'active' : '') . '" href="'. URL_ROOT .'/school/examSchedule/?'. urldecode(http_build_query($params)).' &list_option=0">Exam Schedule<span class="badge badge-info">In prog.</span></a></li>
                                <li><a class="' . ($url[0] == 'school' && $url[1] == 'examGrade' ? 'active' : '') . '" href="'. URL_ROOT .'/school/examGrade/?'. urldecode(http_build_query($params)).' &list_option=0">Exam Grade</a>
                                <li><a class="' . ($url[0] == 'school' && $url[1] == 'setExams' ? 'active' : '') . '" href="'. URL_ROOT .'/school/setExams/?'. urldecode(http_build_query($params)).' &list_option=0">Set Exams</a>
                            </ul>
                        </li>'
                    ).
                    (
                    $user->access->system->_main !== '1' ? '' : '<li class="nav-label">FINANCE</li>'.
                    ($user->access->finance->invoice !== '1' ? '' : '
                    <li class="nav-item ' . ($url[0] == 'finance' && $url[1] == 'invoices' ? 'active' : '') . '">
                        <a class="nav-link has-icon" href="' . URL_ROOT . '/finance/invoices/?user_log='.$params['user_log'].'"><i class="material-icons">casts</i> Invoices </a>
                    </li>').
                    (
                        $user->access->finance->receipt !== '1' ? '' : '
                        <li class="nav-item ' . ($url[0] == 'finance' && $url[1] == 'receipt' ? 'active' : '') . '">
                            <a class="nav-link has-icon" href="' . URL_ROOT . '/finance/receipt/?user_log='.$params['user_log'].'"><i class="material-icons">casts</i> Receipt </a>
                        </li>'
                    ).
                    (
                    $user->access->finance->setup !== '1' ? '' : '
                        <li class="nav-item ' . ($url[0] == 'finance' && $url[1] == 'actgroup' || $url[1] == 'actchart' ? 'active' : '') . '">
                            <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">settings</i> Setup</a>
                            <ul>
                                '.($user->access->finance->setup != '1' ? '' : '<li><a class="' . ($url[0] == 'finance' && $url[1] == 'actBank' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/actBank/?' . urldecode(http_build_query($params)) . '">Banks</a></li>').'
                                '.($user->access->finance->setup != '1' ? '' : '<li><a class="' . ($url[0] == 'finance' && $url[1] == 'actgroup' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/actgroup/?' . urldecode(http_build_query($params)) . '">Account group</a></li>').'
                                '.($user->access->finance->setup != '1' ? '' : '<li><a class="' . ($url[0] == 'finance' && $url[1] == 'actchart' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/actchart/?' . urldecode(http_build_query($params)) . '">Account chart</a></li>').'
                                '.($user->access->finance->setup != '1' ? '' : '<li><a class="' . ($url[0] == 'finance' && $url[1] == 'techsetup' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/techsetup/?' . urldecode(http_build_query($params)) . '">Account mapping</a></li>').'
                                '.($user->access->finance->setup != '1' ? '' : '<li><a class="' . ($url[0] == 'finance' && $url[1] == 'currency' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/currency/?' . urldecode(http_build_query($params)) . '">Currency Setup</a></li>').'
                            </ul>
                        </li>
                    ')
                    ).(
                    $user->access->finance->tool !== '1' ? '' :'
                    <li class="nav-item ' . ($url[0] == 'finance' && $url[1] == 'journal' || $url[1] == 'reconcile' ? 'active' : '') . '">
                        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">build</i> Tools</a>
                        <ul>
                            '.('<li><a class="' . ($url[0] == 'finance' && $url[1] == 'journal' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/journal/?' . urldecode(http_build_query($params)) . '">Journal</a></li>').'
                            '.('<li><a class="' . ($url[0] == 'finance' && $url[1] == 'cashbook' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/cashbook/?' . urldecode(http_build_query($params)) . '">Cashbook</a></li>').'
                            '.('<li><a class="' . ($url[0] == 'finance' && $url[1] == 'actlock' ? 'active' : '') . '" href="' . URL_ROOT . '/finance/actlock/?' . urldecode(http_build_query($params)) . '">(Un)lock GL</a></li>').'
                        </ul>
                    </li>
                    ').
                    (
                        $user->access->finance->requisition_setup !== '1' ? '' : '
                        <li class="nav-item ' . ($url[0] == 'finance' && $url[1] == 'requisitionGroup' ? 'active' : '') . '">
                            <a class="nav-link has-icon" href="' . URL_ROOT . '/finance/requisitionGroup/?user_log='.$params['user_log'].'"><i class="material-icons">settings</i>Requisition Setup</a>
                        </li>'
                    ).(
                        $user->access->finance->admin !== '1' ? '' : '
                    <li class="nav-item ' . ($url[0] == 'finance' && ($url[1] == 'requisitionBudget') ? 'active' : '') . '">
                        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">cast</i>Budget</a>
                        <ul>
                            ' . ($user->access->finance->budget_expense !== '1' ? '' : '
                                <li>
                                    <a class="' . ($url[0] == 'finance' && ($url[1] == 'requisitionBudget') ? 'active' : '') . '" href="' . URL_ROOT . '/finance/requisitionBudget/?user_log='.$params['user_log'].'">Expense</a>
                                </li>
                            ') .($user->access->finance->budget_revenue !== '1' ? '' : '
                            <li>
                                <a class="' . ($url[0] == 'finance' && $url[1] == 'requisitionBudgetRevenue' ? 'active' : '') . '" href="javascript:void(0)">Revenue <span class="badge badge-info ml-auto badge-pill">In progress</span>"</a>
                            </li>')
                            . '
                        </ul>
                    </li>
                   ').
                   (
                    $user->access->finance->report !== '1' ? '' : '
                    <li class="nav-item ' . ($url[0] == 'finance' && ($url[1] == 'report') ? 'active' : '') . '">
                        <a class="nav-link has-icon" href="javascript:void(0)" onclick="$(\'#modalReportAccount\').modal(\'show\')"><i class="material-icons">insert_chart</i>Report</a>
                    </li>'
                   ).
                    (
                        $user->access->system->_main !== '1' ? '' : ' <li class="nav-label">SYSTEM</li>' . 
                        (
                                $user->access->system->technical !== '1' ? '' : '
                            <li class="nav-item ' . ($url[0] == 'system' && $url[1] == 'user' || $url[1] == 'branch' || $url[1] == 'category' ? 'active' : '') . '">
                                <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">settings</i>Settings</a>
                                <ul>
                                    '.($user->access->system->category != '1' ? '' : '<li><a class="' . ($url[0] == 'system' && $url[1] == 'category' ? 'active' : '') . '" href="' . URL_ROOT . '/system/category/?' . urldecode(http_build_query($params)) . '&list_option=0">category</a></li>').'
                                    '.($user->access->system->term != '1' ? '' : '<li><a class="' . ($url[0] == 'system' && $url[1] == 'term' ? 'active' : '') . '" href="' . URL_ROOT . '/system/term/?' . urldecode(http_build_query($params)) . '&list_option=0">Term</a></li>').'
                                    '.($user->access->system->social != '1' ? '' : '<li><a class="' . ($url[0] == 'system' && $url[1] == 'socialBehaviour' ? 'active' : '') . '" href="' . URL_ROOT . '/system/socialBehaviour/?' . urldecode(http_build_query($params)) . '&list_option=0">Social</a></li>').'
                                    '.($user->access->school->class !== "1" ? '' : '<li><a class="'. ($url[0] == 'school' && $url[1] == 'classesSch' ? 'active' : '') .'" href="'. URL_ROOT .'/school/classesSch/?'. urldecode(http_build_query($params)) .'&list_option=0">Classes</a></li>').'
                                    '.($user->access->school->subject !== "1" ? '' : '<li><a class=" '. ($url[0] == 'school' && $url[1] == 'subject' ? 'active' : '') .'" href="'. URL_ROOT .'/school/subject/?'. urldecode(http_build_query($params)) .'&list_option=0">Class Subjects</a></li>').'
                                    '.($user->access->school->subject_type !== "1" ? '' : '<li><a class=" '. ($url[0] == 'school' && $url[1] == 'subjectType' ? 'active' : '') .'" href="'. URL_ROOT .'/school/subjectType/?'. urldecode(http_build_query($params)) .'&list_option=0">Subjects</a></li>').'
                                    '.($user->access->system->mapping != '1' ? '' : '<li><a class="' . ($url[0] == 'system' && $url[1] == 'sysMapping' ? 'active' : '') . '" href="' . URL_ROOT . '/system/sysMapping/?' . urldecode(http_build_query($params)) . '&list_option=0">mapping</a></li>').'
                                    '.($user->access->school->classRoutine !== "1" ? '' : '<li><a class="'. ($url[0] == 'school' && $url[1] == 'classRoutine' ? 'active' : '') .'" href="'. URL_ROOT .'/school/classRoutine/?'. urldecode(http_build_query($params)) .'&list_option=0">Timetable</a></li>').'
                                    '.($user->access->system->branch != '1' ? '' : '<li><a class="' . ($url[0] == 'system' && $url[1] == 'branch' ? 'active' : '') . '" href="' . URL_ROOT . '/system/branch/?' . urldecode(http_build_query($params)) . '&list_option=0">Campus</a></li>').'
                                    '. ($user->access->system->user != '1' ? '' : ' <li><a class="' . ($url[0] == 'system' && $url[1] == 'user' ? 'active' : '') . '" href="' . URL_ROOT . '/system/user/?' . urldecode(http_build_query($params)) . '&list_option=0">Users</a></li>').'
                                    '. ($user->access->system->user != '1' ? '' : ' <li><a class="' . ($url[0] == 'system' && $url[1] == 'products' ? 'active' : '') . '" href="' . URL_ROOT . '/system/products/?' . urldecode(http_build_query($params)) . '&list_option=0">Products</a></li>').'
                                    '. ($user->access->system->user != '1' ? '' : ' <li><a class="' . ($url[0] == 'system' && $url[1] == 'productsImages' ? 'active' : '') . '" href="' . URL_ROOT . '/system/productsImages/?' . urldecode(http_build_query($params)) . '&list_option=0">Images</a></li>').'
                                </ul>
                            </li>
                            <li>&nbsp;</li>
                        ')
                    ) .'
                </ul>
            </div>
        </div>
        ';
        $menu .= '
        <div class="sidebar-backdrop" id="sidebarBackdrop" data-toggle="sidebar"></div>
        <div class="main">
            <div class="main-header">
                <a class="nav-link nav-icon rounded-circle" href="#" data-toggle="sidebar"><i class="material-icons">menu</i></a>
                <form class="form-inline ml-3 d-none d-md-flex">
                <span class="input-icon">
                  <i class="material-icons">search</i>
                  <input type="search" placeholder="Search..." class="form-control shadow-none bg-light border-0" readonly disabled>
                </span>
                </form>
                <ul class="nav nav-circle nav-gap-x-1 ml-auto">
                    <li class="nav-item d-md-none"><a class="nav-link nav-icon" data-toggle="modal" href="#searchModal"><i class="material-icons">search</i></a></li>
                    <li class="nav-item d-none d-sm-block"><a class="nav-link nav-icon" href="" id="refreshPage"><i class="material-icons">refresh</i></a></li>
                    <li class="nav-item dropdown nav-notif d-none">
                        <a class="nav-link nav-icon dropdown-toggle no-caret" href="#" data-toggle="dropdown" data-display="static">
                            <i class="material-icons">color_lens</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-3">
                            <form>
                                <h6>Navigation theme</h6>
                                <div class="custom-color custom-color-lg">
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-blue.min.css" id="sidebar-theme-blue" class="custom-control-input" checked>
                                        <label class="rounded-circle" for="sidebar-theme-blue" style="background-color:#2b579a"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-cyan.min.css" id="sidebar-theme-cyan" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-cyan" style="background-color:#006064"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-gray.min.css" id="sidebar-theme-gray" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-gray" style="background-color:#37474f"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-green.min.css" id="sidebar-theme-green" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-green" style="background-color:#217346"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-pink.min.css" id="sidebar-theme-pink" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-pink" style="background-color:#ad1457"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-purple.min.css" id="sidebar-theme-purple" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-purple" style="background-color:#7151c8"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-red.min.css" id="sidebar-theme-red" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-red" style="background-color:#b7472a"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-dark.min.css" id="sidebar-theme-dark" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-dark" style="background-color:#272822"></label>
                                    </div>
                                    <div class="color-item color-item-light">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-white.min.css" id="sidebar-theme-white" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-white" style="background-color:#fff"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-royal.min.css" id="sidebar-theme-royal" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-royal" style="background-color:#243b55"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-ash.min.css" id="sidebar-theme-ash" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-ash" style="background-color:#606c88"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-crimson.min.css" id="sidebar-theme-crimson" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-crimson" style="background-color:#573662"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-namn.min.css" id="sidebar-theme-namn" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-namn" style="background-color:#9b3333"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-frost.min.css" id="sidebar-theme-frost" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-frost" style="background-color:#00275a"></label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item dropdown nav-notif">
                        <a class="nav-link nav-icon has-badge dropdown-toggle no-caret" href="#" data-toggle="dropdown" data-display="static">
                            <i class="material-icons">notifications</i>
                            <span class="badge badge-pill badge-primary">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
                            <form class="d-none">
                                <div class="card border-0">
                                    <div class="card-header bg-primary text-white">
                                        <i class="material-icons mr-2">notifications</i> 0 Notifications
                                    </div>
                                    <div class="card-body p-1 font-size-sm">
                                        <div class="list-group list-group-sm list-group-flush d-none">
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-primary-faded text-primary btn-icon rounded-circle"><i class="material-icons">person_add</i></span>
                                                    <div class="media-body ml-2">
                                                        5 New members joined today
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 5 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-info-faded text-info btn-icon rounded-circle"><i class="material-icons">comment</i></span>
                                                    <div class="media-body ml-2">
                                                        2 New comments
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 10 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-success-faded text-success btn-icon rounded-circle"><i class="material-icons">shopping_cart</i></span>
                                                    <div class="media-body ml-2">
                                                        10+ New Orders
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 15 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-warning-faded text-warning btn-icon rounded-circle"><i class="material-icons">person</i></span>
                                                    <div class="media-body ml-2">
                                                        Complete your account details
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 20 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="javascript:void(0)">See all notifications &rsaquo;</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                
                </ul>
                <ul class="nav nav-pills">
                    <li class="nav-link-divider mx-2"></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link has-img dropdown-toggle px-2" href="#" data-toggle="dropdown">
                            <img src="' . $user->picture . '" alt="' . strtoupper(substr($user->first_name, 0, 1)) . '" class="rounded-circle mr-2">
                        
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
                            <div class="card border-0">
                                <div class="card-header flex-column" style="width: 180px">
                                    <img src="' . $user->picture . '" alt="' . $user->first_name . '" class="rounded-circle mb-2" width="75" height="75">
                                    <h6>' . $user->first_name . '</h6>
                                    <small class="text-muted">' . $user->group_name . '</small>
                                </div>
                                <div class="list-group list-group-flush list-group-sm list-group-borderless py-2">
                                    <!--<a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">person</i>My Profile</a>-->
                                    <!--<a href="javascript:void(0)" onclick="sessionLock()" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">lock</i>Lock</a>-->
                                    <a href="' . URL_ROOT . '/system/login/?user_log=' . $params['user_log'] . '" class="list-group-item list-group-item-action has-icon text-danger"style="font-size:1.85rem"><i class="material-icons mr-2">exit_to_app</i>Logout</a>
                                    <!--<a href="https://mimity-admin79.netlify.app/dist/html/" class="list-group-item list-group-item-action has-icon text-info" target="_blank"><i class="material-icons mr-2">help_outline</i>Template</a>-->
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        ';

        // Modal report
        $menu .= '
        
        <div class="modal fade" id="modalReportAccount" tabindex="-1" role="dialog" aria-labelledby="modalReportAccount" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Finance and Account Report</h5>
                        <button class="btn btn-icon btn-sm btn-text-secondary rounded-circle" type="button" data-dismiss="modal">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ol style="list-style: upper-alpha; line-height: 24pt">
                            <li><strong>Finance Report</strong>
                                <ol style="line-height: 24pt; padding-left: 20px">
                                    <li><a id="ledger_entry" href="javascript:void(0)" onclick="reportForm({div:\'ledger_entry\', report:\'ledger_entry\', elem:[\'rpt_start_date\',\'rpt_end_date\',\'rpt_group_code\',\'rpt_account_code\']})">Ledger Entries</a></li>
                                    <li><a id="journal_entry" href="javascript:void(0)" onclick="reportForm({div:\'journal_entry\', report:\'journal_entry\', elem:[\'rpt_start_date\',\'rpt_end_date\',\'rpt_group_code\',\'rpt_account_code\']})">Journal Entries</a></li>
                                    <li><a id="financial_statement" href="javascript:void(0)" onclick="reportForm({div:\'financial_statement\', report:\'financial_statement\', elem:[\'rpt_ref_date\',\'rpt_act_type\',\'rpt_act_period\']})">Financial Statements Quarterly</a></li>
                                    <li><a id="financial_statement2" href="javascript:void(0)" onclick="reportForm({div:\'financial_statement2\', report:\'financial_statement\', elem:[\'rpt_start_date\',\'rpt_act_type\',\'rpt_end_date\']})">Financial Statements</a></li>
                                </ol>
                            </li>
                            <li><strong>Account Report</strong>
                                <ol style="line-height: 24pt; padding-left: 20px">
                                    <li><a id="receivable_statement" href="javascript:void(0)" onclick="reportForm({div:\'receivable_statement\', report:\'receivable_statement\', elem:[\'rpt_start_date\', \'rpt_end_date\']})">Receivable</a></li>
                                    <li><a id="payable_statement" href="javascript:void(0)" onclick="reportForm({div:\'payable_statement\', report:\'payable_statement\', elem:[\'rpt_start_date\', \'rpt_end_date\']})">Payable</a></li>
                                    <li><a id="invoice_list" href="javascript:void(0)" onclick="reportForm({div:\'invoice_list\', report:\'invoice_list\', elem:[\'term_code\']})">Invoice List</a></li>
                                </ol>
                            </li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        ';
        
        return $menu;
    }

    // Load menu
    public function getStdSidebar()
    {
        //
        $url = $this->getUrl();
        //
        $params = $this->getParams();
        unset($params['url']);
        
        // verify user
        $student = $this->model('SystemData')->verifyStudent(array('user_log' => $params['user_log']));
        
        // $help = json_decode(file_get_contents(str_ireplace('/' . AZURE_CONTAINER, '', AZURE_BLOB) . '/help/toc.json', true), true);
        
        //
        // $risks = $this->model('TechnicalData')->getRisks(array());
        
        //
        $menu = '
        
        <div class="sidebar">
            
            <div class="sidebar-header">
                <a href="' . URL_ROOT . '/system/dashboard/?user_log=' . $params['user_log'] . '" class="logo">
                    <img src="' . ASSETS_ROOT . '/images/favicon.png" alt="Logo">
                    ' . APP_NAME . '
                </a>
                <a href="#" class="nav-link nav-icon rounded-circle ml-auto" data-toggle="sidebar">
                    <i class="material-icons">close</i>
                </a>
            </div>
            
            <div class="sidebar-body">
                <ul class="nav nav-sub" id="menu">
                    <li class="nav-label">STUDENT</li>
                    <li class="nav-item' . ($url[0] == 'system' && $url[1] == 'stdDashboard' ? 'active' : '') . '"><a class="nav-link" href="'. URL_ROOT .'/system/stdDashboard/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">class</i>Dashboard</a></li>
                    <li class="nav-item' . ($url[0] == 'school' && $url[1] == 'studentStd' ? 'active' : '') . '"><a class="nav-link" href="'. URL_ROOT .'/school/studentStd/?'. urldecode(http_build_query($params)) .'&list_option='.$student->std_code.'"><i class="material-icons">class</i>Student</a></li>
                    <li class="nav-item' . ($url[0] == 'school' && $url[1] == 'studentHistory' ? 'active' : '') . '"><a class="nav-link" href="'. URL_ROOT .'/school/studentHistory/?'. urldecode(http_build_query($params)) .'&list_option='.$student->std_code.'"><i class="material-icons">class</i>History</a></li>
                    <li class="nav-item' . ($url[0] == 'school' && $url[1] == 'schQuestionLink' ? 'active' : '') . '"><a class="nav-link" href="'. URL_ROOT .'/school/schQuestionLink/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">class</i>Questions<span class="badge badge-info" >In progress</span></a></li>
                    <li class="nav-item' . ($url[0] == 'school' && $url[1] == 'timetable' ? 'active' : '') . '"><a class="nav-link" href="'. URL_ROOT .'/school/timetable/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">class</i>Timetable </a></li>
                </ul>
            </div>
        </div>
        ';
        $menu .= '
        <div class="sidebar-backdrop" id="sidebarBackdrop" data-toggle="sidebar"></div>
        
        <div class="main">
            
            <div class="main-header">
                <a class="nav-link nav-icon rounded-circle" href="#" data-toggle="sidebar"><i class="material-icons">menu</i></a>
                <form class="form-inline ml-3 d-none d-md-flex">
                <span class="input-icon">
                  <i class="material-icons">search</i>
                  <input type="search" placeholder="Search..." class="form-control shadow-none bg-light border-0" readonly disabled>
                </span>
                </form>
                <ul class="nav nav-circle nav-gap-x-1 ml-auto">
                    <li class="nav-item d-md-none"><a class="nav-link nav-icon" data-toggle="modal" href="#searchModal"><i class="material-icons">search</i></a></li>
                    <li class="nav-item d-none d-sm-block"><a class="nav-link nav-icon" href="" id="refreshPage"><i class="material-icons">refresh</i></a></li>
                
                </ul>
                <ul class="nav nav-pills">
                    <li class="nav-link-divider mx-2"></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link has-img dropdown-toggle px-2" href="#" data-toggle="dropdown">
                            <img src="' . $student->picture . '" alt="' . strtoupper(substr($student->first_name, 0, 1)) . '" class="rounded-circle mr-2">
                        
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
                            <div class="card border-0">
                                <div class="card-header flex-column" style="width: 180px">
                                    <img src="' . $student->picture . '" alt="' . $student->first_name . '" class="rounded-circle mb-2" width="75" height="75">
                                    <h6>' . $student->first_name . '</h6>
                                    <small class="text-muted">' . $student->group_name . '</small>
                                </div>
                                <div class="list-group list-group-flush list-group-sm list-group-borderless py-2">
                                    <!--<a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">person</i>My Profile</a>-->
                                    <!--<a href="javascript:void(0)" onclick="sessionLock()" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">lock</i>Lock</a>-->
                                    <a href="' . URL_ROOT . '/system/login/?user_log=' . $params['user_log'] . '" class="list-group-item list-group-item-action has-icon text-danger"style="font-size:1.85rem"><i class="material-icons mr-2">exit_to_app</i>Logout</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        ';
        
        return $menu;
    }
    
    // Load menu
    public function getTchSidebar()
    {
        //
        $url = $this->getUrl();
        //
        $params = $this->getParams();
        unset($params['url']);
        
        // verify user
        $user = $this->model('SystemData')->verifyUser(array('user_log' => $params['user_log']));
        
        // $help = json_decode(file_get_contents(str_ireplace('/' . AZURE_CONTAINER, '', AZURE_BLOB) . '/help/toc.json', true), true);
        
        //
        // $risks = $this->model('TechnicalData')->getRisks(array());
        
        //
        $menu = '
        
        <div class="sidebar">
            
            <div class="sidebar-header">
                <a href="' . URL_ROOT . '/system/dashboard/?user_log=' . $params['user_log'] . '" class="logo">
                    <img src="' . ASSETS_ROOT . '/images/favicon.png" alt="Logo">
                    ' . APP_NAME . '
                </a>
                <a href="#" class="nav-link nav-icon rounded-circle ml-auto" data-toggle="sidebar">
                    <i class="material-icons">close</i>
                </a>
            </div>
            
            <div class="sidebar-body">
                <ul class="nav nav-sub" id="menu">
                    <li class="nav-label">TEACHER</li>
                    <li class="nav-item ' . ($url[0] == 'system' && $url[1] == 'tchDashboard' ? 'active' : '') . '">
                        <a class="nav-link has-icon" href="' . URL_ROOT . '/system/tchDashboard/?user_log=' . $params['user_log'] . '" data-breadcrumb="false"><i class="material-icons">pie_chart</i>Dashboard</a>
                        <li>&nbsp;</li>
                    </li>
                    <li class="nav-item' . ($url[0] == 'school' && $url[1] == 'teacherStd' ? 'active' : '') . '"><a class="nav-link" href="'. URL_ROOT .'/school/teacherStd/?'. urldecode(http_build_query($params)) .'&list_option=0"><i class="material-icons">class</i>Student</a></li>
                    <li class="nav-item' . ($url[0] == 'school' && $url[1] == 'teachersDetail' ? 'active' : '') . '"><a class="nav-link" href="#"><i class="material-icons">class</i>details <span class="badge badge-info">In grogress</span> </a></li>
                </ul>
            </div>
        </div>
        ';
        $menu .= '
        <div class="sidebar-backdrop" id="sidebarBackdrop" data-toggle="sidebar"></div>
        
        <div class="main">
            
            <div class="main-header">
                <a class="nav-link nav-icon rounded-circle" href="#" data-toggle="sidebar"><i class="material-icons">menu</i></a>
                <form class="form-inline ml-3 d-none d-md-flex">
                <span class="input-icon">
                  <i class="material-icons">search</i>
                  <input type="search" placeholder="Search..." class="form-control shadow-none bg-light border-0" readonly disabled>
                </span>
                </form>
                <ul class="nav nav-circle nav-gap-x-1 ml-auto">
                    <li class="nav-item d-md-none"><a class="nav-link nav-icon" data-toggle="modal" href="#searchModal"><i class="material-icons">search</i></a></li>
                    <li class="nav-item d-none d-sm-block"><a class="nav-link nav-icon" href="" id="refreshPage"><i class="material-icons">refresh</i></a></li>
                    <li class="nav-item dropdown nav-notif d-none">
                        <a class="nav-link nav-icon dropdown-toggle no-caret" href="#" data-toggle="dropdown" data-display="static">
                            <i class="material-icons">color_lens</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-3">
                            <form>
                                <h6>Navigation theme</h6>
                                <div class="custom-color custom-color-lg">
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-blue.min.css" id="sidebar-theme-blue" class="custom-control-input" checked>
                                        <label class="rounded-circle" for="sidebar-theme-blue" style="background-color:#2b579a"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-cyan.min.css" id="sidebar-theme-cyan" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-cyan" style="background-color:#006064"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-gray.min.css" id="sidebar-theme-gray" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-gray" style="background-color:#37474f"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-green.min.css" id="sidebar-theme-green" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-green" style="background-color:#217346"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-pink.min.css" id="sidebar-theme-pink" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-pink" style="background-color:#ad1457"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-purple.min.css" id="sidebar-theme-purple" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-purple" style="background-color:#7151c8"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-red.min.css" id="sidebar-theme-red" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-red" style="background-color:#b7472a"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-dark.min.css" id="sidebar-theme-dark" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-dark" style="background-color:#272822"></label>
                                    </div>
                                    <div class="color-item color-item-light">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-white.min.css" id="sidebar-theme-white" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-white" style="background-color:#fff"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-royal.min.css" id="sidebar-theme-royal" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-royal" style="background-color:#243b55"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-ash.min.css" id="sidebar-theme-ash" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-ash" style="background-color:#606c88"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-crimson.min.css" id="sidebar-theme-crimson" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-crimson" style="background-color:#573662"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-namn.min.css" id="sidebar-theme-namn" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-namn" style="background-color:#9b3333"></label>
                                    </div>
                                    <div class="color-item">
                                        <input type="radio" name="sidebar-theme" value="' . ASSETS_ROOT . '/css/sidebar-frost.min.css" id="sidebar-theme-frost" class="custom-control-input">
                                        <label class="rounded-circle" for="sidebar-theme-frost" style="background-color:#00275a"></label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item dropdown nav-notif">
                        <a class="nav-link nav-icon has-badge dropdown-toggle no-caret" href="#" data-toggle="dropdown" data-display="static">
                            <i class="material-icons">notifications</i>
                            <span class="badge badge-pill badge-primary">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
                            <form class="d-none">
                                <div class="card border-0">
                                    <div class="card-header bg-primary text-white">
                                        <i class="material-icons mr-2">notifications</i> 0 Notifications
                                    </div>
                                    <div class="card-body p-1 font-size-sm">
                                        <div class="list-group list-group-sm list-group-flush d-none">
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-primary-faded text-primary btn-icon rounded-circle"><i class="material-icons">person_add</i></span>
                                                    <div class="media-body ml-2">
                                                        5 New members joined today
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 5 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-info-faded text-info btn-icon rounded-circle"><i class="material-icons">comment</i></span>
                                                    <div class="media-body ml-2">
                                                        2 New comments
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 10 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-success-faded text-success btn-icon rounded-circle"><i class="material-icons">shopping_cart</i></span>
                                                    <div class="media-body ml-2">
                                                        10+ New Orders
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 15 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <span class="bg-warning-faded text-warning btn-icon rounded-circle"><i class="material-icons">person</i></span>
                                                    <div class="media-body ml-2">
                                                        Complete your account details
                                                        <div class="small text-muted mt-1 d-flex align-items-center">
                                                            <i class="material-icons mr-1 font-size-sm">access_time</i> 20 minutes ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="javascript:void(0)">See all notifications &rsaquo;</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                
                </ul>
                <ul class="nav nav-pills">
                    <li class="nav-link-divider mx-2"></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link has-img dropdown-toggle px-2" href="#" data-toggle="dropdown">
                            <img src="' . $user->picture . '" alt="' . strtoupper(substr($user->first_name, 0, 1)) . '" class="rounded-circle mr-2">
                        
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
                            <div class="card border-0">
                                <div class="card-header flex-column" style="width: 180px">
                                    <img src="' . $user->picture . '" alt="' . $user->first_name . '" class="rounded-circle mb-2" width="75" height="75">
                                    <h6>' . $user->first_name . '</h6>
                                    <small class="text-muted">' . $user->group_name . '</small>
                                </div>
                                <div class="list-group list-group-flush list-group-sm list-group-borderless py-2">
                                    <!--<a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">person</i>My Profile</a>-->
                                    <!--<a href="javascript:void(0)" onclick="sessionLock()" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">lock</i>Lock</a>-->
                                    <a href="' . URL_ROOT . '/system/login/?user_log=' . $params['user_log'] . '" class="list-group-item list-group-item-action has-icon text-danger"style="font-size:1.85rem"><i class="material-icons mr-2">exit_to_app</i>Logout</a>
                                    <!--<a href="https://mimity-admin79.netlify.app/dist/html/" class="list-group-item list-group-item-action has-icon text-info" target="_blank"><i class="material-icons mr-2">help_outline</i>Template</a>-->
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        ';
        
        return $menu;
    }
    
}