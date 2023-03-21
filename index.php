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

$route->get('/signin', function() 
{
    (new UsersController())->sign_in_form();
});

$route->post('/signin', function() 
{
    (new UsersController())->sign_in();
});

$route->get('/signup', function() 
{
    (new UsersController())->sign_up_form();
});

$route->post('/signup', function() 
{
    (new UsersController())->sign_up();
});

$route->any('/signout', function()
{
    (new UsersController())->sign_out();
});

$route->get('/settings', function()
{
    (new UsersController())->settings();
});

$route->post('/settings', function()
{
    (new UsersController())->update_settings();
});

$route->response();