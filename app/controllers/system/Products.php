<?php

class Products extends Controller
{
    
    protected $data;
    protected $user;
    protected $db;
    protected $userAccess;
    protected $sql;
    protected $term;
    protected $termObj;
    protected $result;
    
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
        $images = $this->model('SystemData')->getImages(array());
        //max value in social behaviour table
        
        //menu content
        $menu = $this->getHeader();
        $this->data['menu'] = $menu;
        $this->data['term'] = (array)$this->term;
        $this->data['termObj'] = (array)$this->termObj;
        $this->data['product_images'] = $images;
        
        $this->post = (object)filter_input_array(INPUT_POST);
        
    }
    
    public function index()
    {
        // head
        $this->view('system/header', array());
        // body
        $this->view('system/products', $this->data);
        //
        $this->view('system/footer', $this->data);
    }
    
    public function _list()
    {
        //
        $products = $this->model('SystemData')->getProducts(array());
        echo json_encode(array('data' => $products));
        exit;
    }
    
    public function _delete()
    {
        $post = $this->post;
        //  var_dump($post );exit;
        if($this->user->group_name =='ICT DEPARTMENT' ||  $this->user->group_name == 'ADMIN'){
            $sql = "DELETE FROM products WHERE product_code = '{$post->product_code}' LIMIT 1";
            //var_dump($sql); exit;
            
            $this->db->query($sql);
            $result = $this->db->execute();
            
            if ($result !== true) {
                echo json_encode(array('status' => false, 'message' => $result));
                exit;
            }
            
            //
            echo json_encode(array('status' => true, 'data' => array('message' => 'Product deleted')));
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
        // var_dump($post);exit;

    
        if (empty($this->post->product_name)) {
            echo json_encode(array('status' => false, 'message' => 'PRODUCT NAME REQUIRED'));
            exit;
        }
        if (empty($this->post->price)) {
            echo json_encode(array('status' => false, 'message' => 'PRICE REQUIRED'));
            exit;
        }
    
        if (empty(trim($post->level))) {
            echo json_encode(array('status' => false, 'message' => 'LEVEL NAME REQUIRED'));
            exit;
        }
        
        // INSERT
        $fields = array('product_code','product_name', 'price', 'level', 'picture', 'description', 'submit_by');
        $product = $this->model('SystemData')->getProduct(array("product_code"=>$post->product_code));
        // var_dump($image_product);exit;
        $post->submit_by = $this->user->username;
       //INSERT STUDENT & HISTORY
        if (empty($product)) {

            $post->product_code = $this->model('SystemData')->newAuto(array('auto_item' => $post->product_code, 'digit' => '000', 'prefix' => 'PRD/', 'suffix' => '/' .date('Y'), 'serial' => 'PRODUCTS', 'field' => 'product_code', 'table' => 'products'));
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'INSERT', 'fields' => $fields, 'table' => 'products', 'option' => ""));
            $this->db->query($sql);
            $this->result = $this->db->execute();
           
            echo json_encode(array('status' => true, 'message' => $result, "product_code"=>$post->product_code));
            exit;
        }
        // UPDATE STUDENT & HISTORY
        else {
            $sql = $this->model('SystemData')->sqlQuery(array('array' => array($post), 'action' => 'UPDATE', 'fields' => $fields, 'table' => 'products', 'option' => "WHERE product_code='".$post->product_code."' LIMIT 1 "));
            // var_dump($sql);exit;
            $this->db->query($sql);
            $this->result = $this->db->execute();
            echo json_encode(array('status' => true, 'message' => $result, "product_code"=>$post->product_code_old));
            exit;
        }
        echo json_encode(array('status' => $this->result, 'message' => $result));
        exit;
    }

}