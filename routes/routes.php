<?php
/**
 * 
 * Define App Routes here
 * 
 * 
 * To register a route, simply use the Route Facade class
 * 
 * The structure is as follows:
 * Route::{get, post, put, delete, any}('/uri/path/with/:params', callback fn(), http status code );
 * http status code is 200 by default
 * 
 * @example To Register route
 * Route::get('/user/:id', fn() => (new UsersController)->getUser() )
 * 
 * @example To Define a param
 * Just use :paramName and Route::params()->paramName will be 
 * available thorughout the app. There can be multiple params with
 * different names in each route
 * 
 * @example To require Authentication for a route
 * The auth() method is available from the Controller Class
 * Route::get('/example', fn() => (new ExampleController())->auth()->page() )
 * 
 */

use lib\Router\Route\Route;
use controllers\BudgetsController\BudgetsController;
use controllers\PagesController\PagesController;
use controllers\TransactionsController\TransactionsController;
use controllers\TrendsController\TrendsController;
use controllers\UsersController\UsersController;

/**
 * 
 * Static Page Routes
 * 
 */

Route::get('/',            fn() => (new PagesController())->home() );

Route::get('/about',       fn() => (new PagesController())->about() );

Route::get('/error',       fn() => (new PagesController())->error(), 400 );

Route::get('/error/:code', fn() => (new PagesController())->error(), 400 );

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
Route::get('/budgets',            fn() => (new BudgetsController())->auth()->index() );

Route::post('/budgets',           fn() => (new BudgetsController())->auth()->create() );

Route::get('/budget/:id/edit',    fn() => (new BudgetsController())->auth()->edit() );

Route::post('/budget/:id/edit',   fn() => (new BudgetsController())->auth()->update() );

Route::post('/budget/:id/delete', fn() => (new BudgetsController())->auth()->destroy() );

/**
 * 
 * Transaction Routes (Authorized)
 * 
 */
Route::get('/transactions',            fn() => (new TransactionsController())->auth()->index() );

Route::post('/transactions',           fn() => (new TransactionsController())->auth()->create() );

Route::get('/transaction/:id/edit',    fn() => (new TransactionsController())->auth()->edit() );

Route::post('/transaction/:id/edit',   fn() => (new TransactionsController())->auth()->update() );

Route::post('/transaction/:id/delete', fn() => (new TransactionsController())->auth()->destroy() );

/**
 * 
 * Trends Routes (Authorized)
 * 
 */
Route::get('/trends',                fn () => (new TrendsController())->auth()->index() );

Route::get('/trends/budgets',        fn () => (new TrendsController())->auth()->budgets_index() );

Route::get('/trends/budgets/:id',    fn () => (new TrendsController())->auth()->budgets_single() );

Route::get('/trends/monthly',        fn () => (new TrendsController())->auth()->monthly_index() );

Route::get('/trends/monthly/:month', fn () => (new TrendsController())->auth()->monthly_single() );


