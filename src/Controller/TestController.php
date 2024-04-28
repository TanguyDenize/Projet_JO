<?php

namespace App\Controller;

use PhpParser\Builder\Function_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController{
    public function index(){
        $request = Request::createFromGlobals();
        $age = $request->query->get('age', 0);

        return new Response("Vous avez $age ans");
    }


    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET", "POST"}, host="localhost", schemes={"http", "https"})
     */ 
    public function test(Request $request, $age){
        
        return new Response("Vous avez $age ans");

    }
}