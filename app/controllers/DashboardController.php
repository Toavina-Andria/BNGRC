<?php
namespace app\controllers;

use flight\Engine;
class DashboardController{
    protected Engine $app;

    public function __construct($app){
        $this->app = $app;
    }


    public function index(){
        $this->app->render('dashboard/dashboard',['basepath'=> $this->app->get('basepath')]);
    }
}