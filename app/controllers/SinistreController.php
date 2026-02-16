<?php
namespace app\controllers;
use app\models\SinistreModel;
use flight\Engine;
use Flight;

Class LivraisonController{
    protected Engine $app;

    public function __construct($app){
        $this->app = $app;
    }


}