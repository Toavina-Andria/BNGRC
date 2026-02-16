<?php
namespace app\controllers;
use app\models\DonModel;
use flight\Engine;
use Flight;

Class DonController{
    protected Engine $app;

    public function __construct($app){
        $this->app = $app;
    }


}