<?php


//
//$fileToUpload = "HelloWorld.txt";

class GoogleDriveUpload extends Controller {
    //
    protected $post;
    protected $newName;
    
    public function index() {
    
        //
        $this->post = (object) filter_input_array(INPUT_POST);
        $this->newName = $this->post->newNmae;
    
        // action
        $method = $this->post->action;
        if (!method_exists($this, $method)) {
            echo json_encode(['status' => false, 'message' => 'ACTION REQUIRED']); exit;
        }
        
        //
        echo json_encode($this->{$method}());
    
    }

    function createFile(){
            //file uploaded
        if(isset($_FILES['file']) && !empty($_FILES['file']['tmp_name'])){
                
            $uploadFile =  $_FILES['file'];
            //file info.
            $size = $uploadFile['size'];
            $filename = $uploadFile['name'];
            $error = $uploadFile['error'];
            //
            $dir = $this->post->directory;
                //    $fileContent = file_get_contents($uploadFile['tmp_name']);
            $ext = substr($filename, -4);
            // $ext = ".jpg";
            //
            $finfo = new finfo(FILEINFO_MIME_TYPE);

            if (!isset($error) || is_array($error)) {
                    throw new RuntimeException('INVALID PARAMETERS');
                    
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($error) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('NO FILE SENT');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('EXCEEDED FILESIZE LIMIT');
                default:
                    throw new RuntimeException('UNKNOWN ERRORS');
            }

            // You should also check filesize here.5r   1aa
            if ($size > (4 * 1024 * 1024)) {
                throw new RuntimeException('EXCEEDED FILESIZE LIMIT');
            }
            //
            if (false === array_search($finfo->file($uploadFile['tmp_name']), $this->mime_types, true)) {
                throw new RuntimeException('INVALID FILE FORMAT');
            }
            
            $res = move_uploaded_file($uploadFile['tmp_name'], 'assets/temp/'. $this->post->newName . $ext);
            // $gdriveapi = new GoogleDriveUploadService();
            // $token = $gdriveapi->getAccessToken(GOOGLE_WEB_CLIENT_ID, AUTHORIZED_REDIRECT_URI, GOOGLE_WEB_CLIENT_SECRET);
            
            // if(isset($_SESSION['code'])) echo json_encode(["status"=>false, "message"=> $token]); 
            // sleep(10);
            return ['status' => false, 'message' =>  $res]; 
            exit;
                
        }
    }
    
}