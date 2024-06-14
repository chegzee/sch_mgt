<?php

class ProductsImages extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $sql;
    protected $term;
    protected $termObj;
    
    protected $post;
    
    public function __construct()
    {
        $this->db = new Database;
        
        //var_dump($_REQUEST); exit;
        $this->data['params'] = $this->getParams();
    
        // var_dump($this->data['params']['user_log']);exit;
        // verify user
        $this->user = $this->model('SystemData')->verifyUser(array('user_log' => $this->data['params']['user_log']));
        if (!$this->user) header('Location: ' . URL_ROOT . '/system/login');
        $this->data['user'] = (array)$this->user;
    
        $this->userAccess = json_decode(json_encode($this->data['user']['access']), true);
        $this->term = $this->model('SystemData')->getTerms(array("_option" => "current"));
        $this->termObj= $this->model('SystemData')->getTerms(array("_option" => "object"));
        //max value in social behaviour table
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/products_images', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $images = $this->model('SystemData')->getImages(array());
        echo json_encode(array('data' => $images));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM product_images WHERE image_code = '{$post->image_code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'Image deleted')));
            exit;
            
        }else{
            echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
            exit;

        }
    
        // if ($this->userAccess['system']['admin'] !== '1') {
        //     echo json_encode(array('status' => false, 'message' => 'ACCESS-DENIED'));
        //     exit;
        // }
        
    }
    
    public function _save()
    {
        $post = $this->post;
        // var_dump($post);exit;
    
        if (!empty($post)) {
            foreach ($post as $k => $v) {
                // int
                if (in_array($k, $this->column_int)) {
                    $post->{$k} = (int)preg_replace('/[^0-9.]/', '', $v);
                }
                // float
                else if (in_array($k, $this->column_float)) {
                    $post->{$k} = (float)preg_replace('/[^0-9.]/', '', $v);
                }
                // string
                else
                    $post->{$k} = trim($v);
            }
        }

    
        if (empty($this->post->name)) {
            echo json_encode(array('status' => false, 'message' => 'FILE NAME REQUIRED'));
            exit;
        }
        if (empty($this->post->type)) {
            echo json_encode(array('status' => false, 'message' => 'VALID PHONE REQUIRED'));
            exit;
        }
    
        if (empty(trim($post->image_code)) && !empty($post->image_code_old)) {
            echo json_encode(array('status' => false, 'message' => 'IMAGE CODE REQUIRED'));
            exit;
        }
        if (empty(trim($post->size))) {
            echo json_encode(array('status' => false, 'message' => 'SIZE NAME REQUIRED'));
            exit;
        }
        
        // INSERT
        $fields = array('image_code','name', 'type', 'size', 'picture', 'submit_by', 'submit_on');
        $image_product = $this->model('SystemData')->getImage(array("image_code"=>$post->image_code));
        // var_dump($image_product);exit;
        $post->submit_by = $post->modified_by = $this->user->username;
       //INSERT STUDENT & HISTORY
        if (empty($image_product)) {

            $post->image_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->image_code, 'digit' => '000000', 'prefix' => 'IMG/', 'suffix' => '/' .date('Y'), 'serial' => 'IMAGES', 'field' => 'image_code', 'table' => 'product_images'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'product_images', 'option' => ""));
            $this->db->query($sql);
            $result = $this->db->execute();
           
            echo json_encode(array('status' => true, 'message' => $result, "image_code"=>$post->image_code));
            exit;
        }
        // UPDATE STUDENT & HISTORY
        else {
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'product_images', 'option' => "WHERE image_code='".$post->image_code."' LIMIT 1 "));
            // var_dump($sql);exit;
            $this->db->query($sql);
            $result = $this->db->execute();
            echo json_encode(array('status' => true, 'message' => $result, "image_code"=>$post->image_code_old));
            exit;
        }
    }

}