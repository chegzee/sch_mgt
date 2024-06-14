<?php

class SystemSetting extends Controller
{
    
    //
    public function getUsers($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $users = $this->model('SystemData')->getUsers((array)$post);
        // var_dump($users); exit;
        
        echo json_encode($users);
        exit;
    }
    
    //
    public function getUsergroups($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $usergroups = $this->model('SystemData')->getUsergroups((array)$post);
        // var_dump($usergroups); exit;
        
        if ($post->_option === 'select') {
            echo json_encode($usergroups);
            exit;
        }
        
        echo json_encode(array('data' => $usergroups));
        exit;
    }
    
    //
    public function getAuditlogs($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $auditlogs = $this->model('SystemData')->getAuditlogs((array)$post);
        // var_dump($auditlogs); exit;
        
        echo json_encode(array('data' => $auditlogs));
        exit;
    }
    
    //
    public function getBranches($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $branches = $this->model('SystemData')->getBranches((array)$post);
        // var_dump($branches); exit;
        
        if ($post->_option === 'select') {
            echo json_encode($branches);
            exit;
        }
        
        echo json_encode(array('data' => $branches));
        exit;
    }
    
    //
    public function getBranch($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $branch = $this->model('SystemData')->getBranch((array)$post);
        // var_dump($branch); exit;
        
        return $branch;
    }
    
    //
    public function getCategories($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $categories = $this->model('SystemData')->getCategories((array)$post);
        // var_dump($risks); exit;
    
        if ($post->_option === 'select') {
            echo json_encode($categories);
            exit;
        }
        
        echo json_encode(array('data' => $categories));
        exit;
    }
    //
    public function getClasses($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $classes = $this->model('SystemData')->getClasses((array)$post);
        // var_dump($risks); exit;
    
        if ($post->_option === 'select' || $post->_option === 'class_name') {
            echo json_encode($classes);
            exit;
        }
        if ($post->_option2 === 'select') {
            echo json_encode($classes);
            exit;
        }
        if (!empty($post->cat_code)) {
            echo json_encode($classes);
            exit;
        }
        
        echo json_encode(array('data' => $classes));
        exit;
    }
    public function getStudentSubjects($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $res = $this->model('SystemData')->getStudentSubjects((array)$post);
        // var_dump($risks); exit;
        echo json_encode(array('data' => $res));
        exit;
    }
    //
    public function getStudent($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $std = $this->model('SystemData')->getStudents(array("_option"=> "std_code", "std_code"=> $post->std_code));
        // var_dump($risks); exit;
        
        echo json_encode(array('data' => $std));
        exit;
    }
    //
    public function getSubjects($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $classes = $this->model('SystemData')->getSubjects((array)$post);
        // var_dump($risks); exit;
    
        if ($post->_option === 'select') {
            echo json_encode($classes);
            exit;
        }
        
        echo json_encode(array('data' => $classes));
        exit;
    }
    //
    public function getProducts($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $products = $this->model('SystemData')->getProducts((array)$post);
        // var_dump($risks); exit;
    
        if ($post->_option === 'select') {
            echo json_encode($products);
            exit;
        }
        
        echo json_encode(array('data' => $products));
        exit;
    }
    //
    public function getTeachers($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $teachers = $this->model('SystemData')->getTeachers((array)$post);
        // var_dump($risks); exit;
    
        if ($post->_option === 'select') {
            echo json_encode($teachers);
            exit;
        }
        
        echo json_encode(array('data' => $teachers));
        exit;
    }
    //
    public function getParents($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $parents = $this->model('SystemData')->getParents((array)$post);
        var_dump($parents); exit;
    
        if ($post->_option === 'select') {
            echo json_encode($parents);
            exit;
        }
        
        echo json_encode(array('data' => $parents));
        exit;
    }

    public function getSubjectType($arg = array()){
        //
        $post = (object) filter_input_array(INPUT_POST);
        //   var_dump($post);exit;
        //
        $subjectType = $this->model('SystemData')->getSubjectType((array)$post);
    
        if ($post->_option === 'select') {
            echo json_encode($subjectType);
            exit;
        }
        
        echo json_encode(array('data' => $subjectType));
        exit;
    }
    public function getClassSubject($arg = array()){
        //
        $post = (object) filter_input_array(INPUT_POST);
        // //
        $classSubject = $this->model('SystemData')->getClassSubject((array)$post);
        // var_dump($classSubject);exit;
        echo json_encode($classSubject);
        exit;
    
    }

    public function getTerms($arg = array()){
        $post = (object) filter_input_array(INPUT_POST);
        //
        $term = $this->model('SystemData')->getTerms((array)$post);
    
        if ($post->_option === 'select') {
            echo json_encode($term);
            exit;
        }
        if ($post->_option === 'select2') {
            echo json_encode($term);
            exit;
        }
        if ($post->_option === 'all_select') {
            echo json_encode($term);
            exit;
        }
        
        if ($post->_option === 'all_select2') {
            echo json_encode($term);
            exit;
        }
        echo json_encode(array('data' => $term));
        exit;

    }
    public function getClassRoutine($arg = array()){
        $post = (object) filter_input_array(INPUT_POST);
        // var_dump($post);
        //
       $classRoutine = $this->model('SystemData')->getClassRoutine((array)$post);
    
        // if ($post->_option === 'select') {
        //     echo json_encode($term);
        //     exit;
        // }
        echo json_encode(array('data' => $classRoutine));
        exit;

    }
    //
    public function getUnits($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $units = $this->model('SystemData')->getUnits((array)$post);
        // var_dump($units); exit;
    
        echo json_encode($units);
        exit;
    }
    
    //
    public function getTitles($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $titles = $this->model('SystemData')->getTitles((array)$post);
        // var_dump($titles); exit;
    
        echo json_encode($titles);
        exit;
    }
    
    //
    public function getOccupations($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $occupations = $this->model('SystemData')->getOccupations((array)$post);
        // var_dump($occupations); exit;
    
        echo json_encode($occupations);
        exit;
    }
    
    //
    public function getGenders($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $genders = $this->model('SystemData')->getGenders((array)$post);
        // var_dump($genders); exit;
    
        echo json_encode($genders);
        exit;
    }
    
    //
    public function getMaritalStatuses($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $marital_statuses = $this->model('SystemData')->getMaritalStatuses((array)$post);
        // var_dump($marital_statuses); exit;
    
        echo json_encode($marital_statuses);
        exit;
    }
    
    //
    public function getReligions($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $religions = $this->model('SystemData')->getReligions((array)$post);
        // var_dump($religions); exit;
    
        echo json_encode($religions);
        exit;
    }
    
    //
    public function getSectors($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $sectors = $this->model('SystemData')->getSectors((array)$post);
        // var_dump($sectors); exit;
    
        echo json_encode($sectors);
        exit;
    }
    
    //
    public function getBusinesses($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $businesses = $this->model('SystemData')->getBusinesses((array)$post);
        // var_dump($businesses); exit;
    
        echo json_encode($businesses);
        exit;
    }
    
    //
    public function getStates($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $states = $this->model('SystemData')->getStates((array)$post);
        // var_dump($states); exit;
    
        echo json_encode($states);
        exit;
    }
    
    //
    public function getCountries($arg = array()) {
        //
        $post = (object) filter_input_array(INPUT_POST);
        //
        $countries = $this->model('SystemData')->getCountries((array)$post);
        // var_dump($countries); exit;
    
        echo json_encode($countries);
        exit;
    }
}