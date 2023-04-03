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

/**
 * 
 * Static Page Routes
 * 
 */

Route::get('/',             fn() => (new PagesController)->home() );

Route::get('/about',        fn() => (new PagesController())->about() );

Route::get('/error',        fn() => (new PagesController())->error(),           400 );

Route::get('/404',          fn() => (new PagesController())->error_404(),       404 );

Route::get('/unauthorized', fn() => (new PagesController())->unauthorized() );

/**
 * 
 * User Routes (Public)
 * 
 */

Route::get('/signin',   fn() => (new UsersController)->sign_in() );

Route::post('/signin',  fn() => (new UsersController())->authenticate() );

Route::get('/signup',   fn() => (new UsersController())->new() );

Route::post('/signup',  fn() => (new UsersController())->create() );

Route::any('/signout',  fn() => (new UsersController())->sign_out() );

/**
 * 
 * User Routes (Authorized)
 * 
 */
Route::get('/dashboard', fn() => (new UsersController())->auth()->dashboard() );

Route::get('/settings',  fn() => (new UsersController())->auth()->settings() );

Route::post('/settings', fn() => (new UsersController())->auth()->update_settings() );

/**
 * 
 * Budgets Routes (Authorized)
 * 
 */
Route::get('/budgets',               fn() => (new BudgetsController())->auth()->new() );

Route::post('/budgets',              fn() => (new BudgetsController())->auth()->create() );

Route::get('/budget/:param/edit',    fn() => (new BudgetsController())->auth()->edit() );

Route::post('/budget/:param/edit',   fn() => (new BudgetsController())->auth()->update() );

Route::post('/budget/:param/delete', fn() => (new BudgetsController())->auth()->destroy() );
