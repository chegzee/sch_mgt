<?php

class Login extends Controller
{
    private $db;
    
    private $post;
    private $data;

    public function __construct()
    {
        $this->db = new Database;
    
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }

    public function index()
    {
        
        $this->data['user_log'] = addslashes($_GET['user_log'] ?? '');
        // echo json_encode($this->post);exit;
        // logout session
        // $_SESSION['code'] = null;
        $this->db->query("UPDATE sys_user SET user_log = '' WHERE user_log = '" . $this->data['user_log'] . "' LIMIT 1");
        $this->db->execute();
        // head
        $this->view('system/header', array('body' => 'class="login-page"'));
        // body
        $this->view('system/login', array('app' => APP_NAME));
    }

    public function verify()
    {
        // post
        $post = (object) filter_input_array(INPUT_POST);

        $this->db->query("SELECT t1.*, t2.group_name FROM sys_user t1 LEFT JOIN (SELECT t21.* FROM sys_user_group t21)t2 ON t1.group_code = t2.group_code WHERE username = '" . addslashes($post->username ?? '') . "' LIMIT 1");
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        //
        $user = $this->db->single();
        //
        //echo json_encode(array($post->password, $user->password, password_verify($post->password, $user->password))); exit;
        if (!password_verify($post->password, $user->password)) {
            echo json_encode(array('status' => false, 'message' => 'INVALID PASSWORD'));
            exit;
        }
        //
        if ((int)$user->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        //
        //$user->user_log = $this->model('SystemData')->GUIDv4();
        $user->user_log = hash("sha256", $user->username . $user->password . session_id()); // . date('Ymd')
        $user->user_log = substr($user->user_log, 0, 16) . '-'
            . substr($user->user_log, 16, 8) . '-'
            . substr($user->user_log, 24, 8) . '-'
            . substr($user->user_log, 32, 8) . '-'
            . substr($user->user_log, 40, 24);
        $user->user_period = mktime(1);
        $user->last_log = date("Y-m-d H:i:s", $user->user_period);

        $sql = "UPDATE sys_user SET user_log = '" . (AUTHENTICATOR ? '' : $user->user_log) . "', user_period = '" . $user->user_period . "', last_log = '" . $user->last_log . "', password_reset = NULL WHERE username = '" . $user->username . "' LIMIT 1";
        //echo json_encode(array('status' => false, 'message' => http_response_code(200), 'data' => array('err' => $sql))); exit;
        $this->db->query($sql);
        // echo json_encode($this->db->single()); exit;
        $this->db->execute();
        
        // log
        $sql = "INSERT INTO sys_user_log (username, user_ip, event_log, remarks) VALUES ('" . $user->username . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'LOGIN', NULL)";
        $this->db->query($sql);
        //
        $this->db->execute();
        if(strtoupper($user->group_name) == 'STUDENTS'){
            echo json_encode(array('status' => true, 'data' => array('url' => URL_ROOT . '/system/stdDashboard/?user_log=' . $user->user_log, 'username' => $user->username, 'user_log' => $user->user_log)));
            exit;
        }

        if(strtoupper($user->group_name) == 'TEACHERS'){
            echo json_encode(array('status' => true, 'data' => array('url' => URL_ROOT . '/system/TchDashboard/?user_log=' . $user->user_log, 'username' => $user->username, 'user_log' => $user->user_log)));
            exit;
        }

        echo json_encode(array('status' => true, 'data' => array('url' => URL_ROOT . '/system/dashboard/?user_log=' . $user->user_log, 'username' => $user->username, 'user_log' => $user->user_log)));
    }

    public function verifyStudent(){
        $post = (object) filter_input_array(INPUT_POST);
        $this->db->query("SELECT t1.std_code, t1.status FROM sch_students t1 WHERE t1.std_code = '" . addslashes($post->student_id ?? '') . "' LIMIT 1");
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        // //
        $student = $this->db->single();
        //
        if ((int)$student->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        
        //$user->user_log = $this->model('SystemData')->GUIDv4();
        $student->user_log = hash("sha256", $student->std_code  . session_id()); // . date('Ymd')
        $student->user_log = substr($student->user_log, 0, 16) . '-'
            . substr($student->user_log, 16, 8) . '-'
            . substr($student->user_log, 24, 8) . '-'
            . substr($student->user_log, 32, 8) . '-'
            . substr($student->user_log, 40, 24);
        $student->user_period = mktime(1);
        $student->last_log = date("Y-m-d H:i:s", $student->user_period);

        
        
        $sql = "UPDATE sch_students SET user_log = '" . (AUTHENTICATOR ? '' : $student->user_log) . "' WHERE std_code = '" . $student->std_code . "' LIMIT 1";
        //echo json_encode(array('status' => false, 'message' => http_response_code(200), 'data' => array('err' => $sql))); exit;
        $this->db->query($sql);
        // echo json_encode($this->db->single()); exit;
       $res =  $this->db->execute();
        // echo json_encode(array('status' => false, 'message' => $res));exit;
        
        // log
        $sql = "INSERT INTO sys_user_log (username, user_ip, event_log, remarks) VALUES ('" . $student->std_code . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'LOGIN', NULL)";
        $this->db->query($sql);
        //
        $this->db->execute();
            echo json_encode(array('status' => true, 'data' => array('url' => URL_ROOT . '/system/stdDashboard/?user_log=' . $student->user_log, 'std_code' => $student->std_code, 'user_log' => $student->user_log)));
            exit;
        
        // echo json_encode(array("message" => $student));
        // var_dump($student);exit;

    }

    public function verifyTeacher(){
        $post = (object) filter_input_array(INPUT_POST);
        $this->db->query("SELECT t1.identity_no, t1.status FROM sch_teacher t1 WHERE t1.identity_no = '" . addslashes($post->teacher_id ?? '') . "' LIMIT 1");
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        // //
        $teacher = $this->db->single();
        //
        if ((int)$teacher->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        
        //$user->user_log = $this->model('SystemData')->GUIDv4();
        $teacher->user_log = hash("sha256", $teacher->identity_no  . session_id()); // . date('Ymd')
        $teacher->user_log = substr($teacher->user_log, 0, 16) . '-'
            . substr($teacher->user_log, 16, 8) . '-'
            . substr($teacher->user_log, 24, 8) . '-'
            . substr($teacher->user_log, 32, 8) . '-'
            . substr($teacher->user_log, 40, 24);
        $teacher->user_period = mktime(1);
        $teacher->last_log = date("Y-m-d H:i:s", $teacher->user_period);

        
        
       $sql = "UPDATE sch_teacher SET user_log = '" . (AUTHENTICATOR ? '' : $teacher->user_log) . "' WHERE identity_no = '" . $teacher->identity_no . "' LIMIT 1";
       $this->db->query($sql);
       $res =  $this->db->execute();
        // echo json_encode(array('status' => false, 'message' => $res));exit;
        
        // log
        $sql = "INSERT INTO sys_user_log (username, user_ip, event_log, remarks) VALUES ('" . $teacher->identity_no . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'LOGIN', NULL)";
        $this->db->query($sql);
        //
        $this->db->execute();
        echo json_encode(array('status' => true, 'data' => array('url' => URL_ROOT . '/system/tchDashboard/?user_log=' . $teacher->user_log, 'identity_no' => $teacher->identity_no, 'user_log' => $teacher->user_log)));
        exit;
    
        // echo json_encode(array("message" => $student));
        // var_dump($student);exit;

    }

    public function changePassword()
    {
        // post
        $post = (object) filter_input_array(INPUT_POST);
        //echo json_encode($post); exit;

        //
        if ($post->new_password !== $post->confirm_password || !preg_match($this->regexp_password, $post->new_password)) {
            echo json_encode(array('status' => false, 'message' => 'PASSWORD CONFIRM DOES NOT MATCH'));
            exit;
        }

        $this->db->query("SELECT * FROM sys_user WHERE username = '" . addslashes($post->username ?? '') . "' LIMIT 1");
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        //
        $user = $this->db->single();
        //
        if (password_verify($post->new_password, $user->password)) {
            echo json_encode(array('status' => false, 'message' => 'PASSWORD ALREADY USED'));
            exit;
        }
        //
        if ((int)$user->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        //
        $user->password = password_hash($post->new_password, PASSWORD_DEFAULT);
        //$user->user_log = $this->model('SystemData')->GUIDv4();
        $user->user_log = hash("sha256", $user->username . $user->password . session_id()); // . date('Ymd')
        $user->user_log = substr($user->user_log, 0, 16) . '-'
            . substr($user->user_log, 16, 8) . '-'
            . substr($user->user_log, 24, 8) . '-'
            . substr($user->user_log, 32, 8) . '-'
            . substr($user->user_log, 40, 24);
        $user->user_period = mktime(1);
        $user->last_log = date("Y-m-d H:i:s", $user->user_period);

        $sql = "UPDATE sys_user SET user_log = '" . $user->user_log . "', user_period = '" . $user->user_period . "', password = '" . $user->password . "', last_log = '" . $user->last_log . "' WHERE username = '" . $user->username . "' LIMIT 1";
        //echo json_encode(array('status' => false, 'message' => http_response_code(200), 'data' => array('err' => $sql))); exit;
        $this->db->query($sql);
        $this->db->execute();

        echo json_encode(array('status' => true, 'data' => array()));
    }

    public function sessionVerify()
    {
        // post
        $post = (object) filter_input_array(INPUT_POST);
        //echo json_encode($post); exit;
    
        $this->data['user_log'] = filter_var($_GET['user_log'] ?? '');

        $this->db->query("SELECT * FROM sys_user WHERE username = '" . addslashes($post->username ?? '') . "' LIMIT 1");
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        //
        $user = $this->db->single();
        //
        if (!password_verify($post->password, $user->password)) {
            echo json_encode(array('status' => false, 'message' => 'INVALID PASSWORD'));
            exit;
        }
        //
        if ((int)$user->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        //
        //$user->user_log = $this->model('SystemData')->GUIDv4();
        $user->user_log = hash("sha256", $user->username . $user->password . session_id()); // . date('Ymd')
        $user->user_log = substr($user->user_log, 0, 16) . '-'
            . substr($user->user_log, 16, 8) . '-'
            . substr($user->user_log, 24, 8) . '-'
            . substr($user->user_log, 32, 8) . '-'
            . substr($user->user_log, 40, 24);
        $user->user_period = mktime(1);
        $user->last_log = date("Y-m-d H:i:s", $user->user_period);

        // remove duplicate user_log
        //$this->db->db_query("update cod_user set user_log = '' where user_log = '" . $user->user_log . "'");
        // set new user_log

        $sql = "UPDATE sys_user SET user_log = '" . (AUTHENTICATOR ? '' : $user->user_log) . "', user_period = '" . $user->user_period . "', last_log = '" . $user->last_log . "' WHERE username = '" . $user->username . "' LIMIT 1";
        //echo json_encode(array('status' => false, 'message' => http_response_code(200), 'data' => array('err' => $sql))); exit;
        $this->db->query($sql);
        //echo json($this->db->single()); exit;
        $this->db->execute();

        echo json_encode(array('status' => true, 'username' => $user->username, 'user_log' => $user->user_log));
    } 

    public function sessionLock()
    {

        $this->data['user_log'] = filter_var($_GET['user_log'] ?? '');

        // logout session
        $this->db->query("UPDATE sys_user SET user_log = '' WHERE user_log = '" . $this->data['user_log'] . "' LIMIT 1");
        //
        $this->db->execute();

        echo json_encode(array('status' => true, 'data' => 'SESSION DESTROYED'));
    }

    public function passwordStep1()
    {
        // post
        $post = (object) filter_input_array(INPUT_POST);
        $this->db->query("SELECT * FROM sys_user WHERE username = '" . addslashes($post->username ?? '') . "' LIMIT 1");
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        $user = $this->db->single();
        if ((int)$user->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        if ($user->username != $post->username) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME DOES MATCH'));
            exit;
        }
        $user->password_reset = str_shuffle(rand(10,20) . rand(20,30) . rand(30,40));
        $user->user_log = hash("sha256", $user->username . $user->password . session_id()); // . date('Ymd')
        $user->user_log = substr($user->user_log, 0, 16) . '-'
            . substr($user->user_log, 16, 8) . '-'
            . substr($user->user_log, 24, 8) . '-'
            . substr($user->user_log, 32, 8) . '-'
            . substr($user->user_log, 40, 24);
        $user->user_period = mktime(1);
        $user->last_log = date("Y-m-d H:i:s", $user->user_period);

        $sql = "UPDATE sys_user SET user_log = '" . $user->user_log . "', user_period = '" . $user->user_period . "', last_log = '" . $user->last_log . "', password_reset = '" . $user->password_reset . "' WHERE username = '" . $user->username . "' LIMIT 1";
        $this->db->query($sql);
        $this->db->execute();

        echo json_encode(array('status' => true, 'data' => array('message' => 'CODE WAS SENT TO: '. $user->username, 'user_log' => $user->user_log)));
        // http://localhost/ims-broker/system/login/passwordStep2/?user_log=84e36dffba0ba7fc-9a80cd84-8a7c8895-41afca19-2aa439995a2be38be13b9482
        // send username
        require_once APP_ROOT . '/controllers/system/Mail.php';
        $mail = (new Mail())->sendMail(rawurlencode(
            json_encode(
            array('contact' => $user->fullname, 'email' => $user->username, 
            'email_cc' => '', 'subject' => 'PASSWORD RESET CODE', 
            'body' => htmlentities(
            '<p style="font-size: 18pt">PASSWORD RESET CODE</p>
            <p style="font-weight: bold">Dear Sir/Ma</p>
            <p>You recently requested to reset your password for your school management Platform account. Click the button below to reset your password</p>
            <p style="font-size: 16pt; font-weight: bold">
            <div style="text-align: center">
                <a href="' . URL_ROOT . '/system/login/passwordStep2/?user_log=' . $user->user_log . '" style="padding: 10px 20px; background-color: #ff7a59; color: #ffffff; text-decoration: none">' . $user->password_reset . '</a>
            </div>
            </p>
            <p>If you did not request a password reset, please ignore this email or reply to let us know.</p>
            '
            ) )))
        );

    }

    public function passwordStep2()
    {
        // post
        $post = (object) filter_input_array(INPUT_POST);
        //echo json_encode($post); exit;

        //
        if ($post->new_password !== $post->confirm_password || !preg_match($this->regexp_password, $post->new_password)) {
            echo json_encode(array('status' => false, 'message' => 'PASSWORD CONFIRM DOES NOT MATCH'));
            exit;
        }

        $this->db->query("SELECT * FROM sys_user WHERE username = '" . addslashes($post->username ?? '') . "' LIMIT 1");
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME NOT FOUND'));
            exit;
        }
        //
        $user = $this->db->single();
        //
        if ((int)$user->status != '1') {
            echo json_encode(array('status' => false, 'message' => 'USER DISABLED'));
            exit;
        }
        //
        if ($user->username != $post->username) {
            echo json_encode(array('status' => false, 'message' => 'USERNAME DOES MATCH'));
            exit;
        }
        //
        if ($user->password_reset != $post->password_reset) {
            echo json_encode(array('status' => false, 'message' => 'RESET CODE INVALID'));
            exit;
        }
        //
        if (password_verify($post->new_password, $user->password)) {
            echo json_encode(array('status' => false, 'message' => 'PASSWORD ALREADY USED'));
            exit;
        }

        $user->password = password_hash($post->new_password, PASSWORD_DEFAULT);

        // remove duplicate user_log
        //$this->db->db_query("update cod_user set user_log = '' where user_log = '" . $user->user_log . "'");
        // set new user_log

        $sql = "UPDATE sys_user SET user_log = NULL, password_reset = NULL, password = '" . $user->password . "' WHERE username = '" . $user->username . "' LIMIT 1";
        //echo json_encode(array('status' => false, 'message' => http_response_code(200), 'data' => array('err' => $sql))); exit;
        $this->db->query($sql);
        $this->db->execute();

        echo json_encode(array('status' => true, 'data' => array('message' => 'PASSWORD RESET SUCCESSFUL')));

    }
    
    public function tokenVerify() {
        // post
        $post = (object) filter_input_array(INPUT_POST);
        //echo json_encode($post); exit;
        
        $sql = "UPDATE sys_user SET user_log = '" . $post->user_log . "' WHERE username = '" . $post->username . "' LIMIT 1";
        //echo json_encode(['status' => false, 'message' => http_response_code(200), 'data' => ['err' => $sql]]); exit;
        $this->db->query($sql);
        //echo json($this->db->single()); exit;
        $this->db->execute();
        
        $sql = "SELECT * FROM sys_user WHERE username = '" . $post->username . "' AND user_log = '" . $post->user_log . "' LIMIT 1";
        $this->db->query($sql);
        //
        if ($this->db->rowCount() < 1) {
            echo json_encode(['status' => false, 'message' => 'SESSION INVALID, LOGIN AGAIN']);
            exit;
        }
        //
        $user = $this->db->single();
        //
        if ((int)$user->status != '1') {
            echo json_encode(['status' => false, 'message' => 'USER DISABLED']);
            exit;
        }
        //
        if ((int)$user->device_otp !== (int)$post->token) {
            echo json_encode(['status' => false, 'message' => 'TOKEN INVALID']);
            exit;
        }
        //
        if ((new DateTime())->getTimestamp() - strtotime($user->device_time) > 90) { // inline with authenticator token refresh
            echo json_encode(['status' => false, 'message' => 'TOKEN HAS EXPIRED']);
            exit;
        }
        
        echo json_encode(array('status' => true, 'data' => array('url' => URL_ROOT . '/system/dashboard/?user_log=' . $post->user_log)));
    }
}
