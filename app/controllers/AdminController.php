<?php


namespace App\Controllers;


class AdminController extends Controller
{
    public function run(){
        echo "Admin Panel<hr/>";

        echo var_export($this->data["request_type"],true);
        print("<br/>");
        echo var_export($this->data["get_data"],true);
        print("<br/>");
        echo var_export($this->data["post_data"],true);

        if($this->data["request_type"] == 'GET'){
//        switch ($this->get){
//            return $this->index();
//        }
        }
        elseif($this->data["request_type"] == 'POST'){
//        switch ($this->get){
//            return $this->index();
//        }
        }
    }

    private function index(){

    }
}