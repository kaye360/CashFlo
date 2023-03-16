<?php

use Router\Router;
use PagesController\PagesController;
use UsersController\UsersController;

require_once './config.php';
require_once './lib/Router.php';
require_once './controllers/Pages.php';
require_once './controllers/Users.php';


$route = new Router();

/**
 * 
 * App Page Routes
 * 
 */

$route->get('/', function() 
{
    (new PagesController())->home() ;
});

$route->get('/about', function() 
{
    (new PagesController())->about() ;
});

$route->get('/error', function() 
{
    (new PagesController())->error() ;
});

/**
 * 
 * User Routes
 * 
 */

$route->get('/signin', function() 
{
    (new UsersController())->sign_in() ;
});

$route->get('/signup', function() 
{
    (new UsersController())->sign_up() ;
});
$route->post('/signup', function() 
{
    (new UsersController())->sign_up() ;
});

$route->response();