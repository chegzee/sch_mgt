<?php


class EventCalendar{
    protected $db;
    protected $post;
    protected $res;

    public function __construct(){
        
        $this->db = new Database();
        $this->post = (object)filter_input_array(INPUT_POST);
    }

    public function _save(){
        $name = $this->post->event_name;
        $start_date = date('Y-m-d', strtotime($this->post->event_start_date));
        $end_date = date('Y-m-d', strtotime($this->post->event_end_date));
        $sql = "insert into event_calendar(`name`, `start_date`, `end_date`) values('".$name."', '".$start_date."', '".$end_date."') ";
        $this->db->query($sql);
        $rows =  $this->db->rowCount();
        if($rows > 0){
            echo json_encode(['status'=>true, 'message'=>$rows]);

        }else{
            echo json_encode(['status'=>true, 'message'=>$rows]);

        }
            // echo json_encode($this->post);
    }

    public function getEvents(){
        $dataEvent = array();
        $sql = "SELECT id, name, start_date, end_date FROM event_calendar";
        $this->db->query($sql);
        if($this->db->rowCount() > 0){
            $this->res = $this->db->resultSet();
            foreach($this->res as $k => $v){
                $dataEvent[$k]['id'] = $v->id; 
                $dataEvent[$k]['title'] = $v->name; 
                $dataEvent[$k]['start'] = date('Y-m-d', strtotime($v->start_date)) ; 
                $dataEvent[$k]['end'] = date('Y-m-d', strtotime($v->end_date)) ; 
                $dataEvent[$k]['color'] = '#'. substr(uniqid(), -6);
                // $dataEvent[$k]['color'] = "red";
                // $dataEvent[$k]['url'] = '#';
                // $dataEvent[$k]['url'] = 'https://safamdigital.com';

            }
            echo json_encode(["status" => true, "msg" =>"successful", "data" => $dataEvent]);
        }else{
            echo json_encode(["status" => false, "msg" => "unsuccessful"]);

        }
    }

}
