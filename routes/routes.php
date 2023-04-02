<?php
/**
 * 
 * Define App Routes here
 * 
 */

use lib\Router\Route\Route;
use controllers\BudgetsController\BudgetsController;
use controllers\PagesController\PagesController;
use controllers\UsersController\UsersController;

Route::get('/', function() 
{
    (new PagesController)->home();
});

Route::get('/about', function() 
{
    (new PagesController())->about();
});

Route::get('/error', function() 
{
    (new PagesController())->error();
});

Route::get('/unauthorized', function() 
{
    (new PagesController())->unauthorized();
});

/**
 * 
* User Routes (Public)
* 
*/

Route::get('/signin', function() 
{
    (new UsersController)->sign_in();
});

Route::post('/signin', function() 
{
    (new UsersController())->authenticate();
});

Route::get('/signup', function() 
{
    (new UsersController())->new();
});

Route::post('/signup', function() 
{
    (new UsersController())->create();
});

Route::any('/signout', function()
{
    (new UsersController())->sign_out();
});

/**
 * 
* User Routes (Auth)
* 
*/
Route::get('/dashboard', function()
{
    (new UsersController())->auth()->dashboard();
});

Route::get('/settings', function()
{
    (new UsersController())->auth()->settings();
});

Route::post('/settings', function()
{
    (new UsersController())->auth()->update_settings();
});

/**
 * Budgets Routes
*/
Route::get('/budgets', function()
{
    (new BudgetsController)->auth()->new();
});

Route::post('/budgets', function()
{
    (new BudgetsController())->auth()->create();
});

Route::get('/budget/:param/edit', function()
{
    (new BudgetsController())->auth()->edit();
});

Route::post('/budget/:param/edit', function()
{
    (new BudgetsController())->auth()->update();
});

Route::post('/budget/:param/delete', function()
{
    (new BudgetsController())->auth()->destroy();
});
