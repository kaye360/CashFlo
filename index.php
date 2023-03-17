<?php

require_once './bootstrap.php';

use lib\Router\Router;
use controllers\PagesController\PagesController;
use controllers\UsersController\UsersController;

$route = new Router();

/**
 * 
 * App Page Routes
 * 
 */

$route->get('/', function() 
{
    (new PagesController())->home();
});

$route->get('/about', function() 
{
    (new PagesController())->about();
});

$route->get('/error', function() 
{
    (new PagesController())->error();
});

$route->get('/unauthorized', function() 
{
    (new PagesController())->unauthorized();
});

$route->get('/dashboard', function()
{
    (new PagesController())->auth()->dashboard();
});

/**
 * 
 * User Routes
 * 
 */

$route->any('/signin', function() 
{
    (new UsersController())->sign_in();
});

$route->any('/signup', function() 
{
    (new UsersController())->sign_up();
});

$route->any('/signout', function()
{
    (new UsersController())->sign_out();
});

$route->response();