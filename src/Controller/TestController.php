<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController{
    public function index(){
        $request = Request::createFromGlobals();
        $age = $request->query->get('age', 0);

        return new Response("Vous avez $age ans");
    }


    /**
     * @Routes("/test/{age<\d+>?0}", name="test", methods={"GET", "POST"}, host="localhost", schemes={"http", "https"})
     */ 
    public function test(Request $request){
        var_dump($request);
        $test=0
        $age = $request->query->get('age', 0);

        return new Response("Vous avez $age ans");
    }
}