<?php

require_once './bootstrap.php';

use controllers\BudgetsController\BudgetsController;
use lib\Router\Router;
use controllers\PagesController\PagesController;
use controllers\UsersController\UsersController;

$route = new Router();

/**
 * 
 * App Page Routes (Public)
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

/**
 * 
 * User Routes (Public)
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

/**
 * 
 * User Routes (Auth)
 * 
 */
$route->get('/dashboard', function()
{
    (new UsersController())->auth()->dashboard();
});

$route->get('/settings', function()
{
    (new UsersController())->auth()->settings();
});

$route->post('/settings', function()
{
    (new UsersController())->auth()->update_settings();
});

/**
 * Budgets Routes
 */
$route->get('/budgets', function()
{
    (new BudgetsController())->auth()->budgets_home();
});

$route->post('/budgets', function()
{
    (new BudgetsController())->auth()->create_budget();
});

$route->get('/budget/:param/edit', function()
{
    (new BudgetsController())->auth()->edit_budget_form();
});

$route->response();