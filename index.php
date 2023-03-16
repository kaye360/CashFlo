<?php

use Router\Router;

require_once './config.php';
require_once './lib/Router.php';


$route = new Router();

$route->get('/', function() 
{
    echo 'home';
});

$route->get('/account', function() 
{
    echo 'account';
});

$route->response();