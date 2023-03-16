<?php

use PagesController\PagesController;
use Router\Router;
use UsersController\Users;

require_once './config.php';
require_once './lib/Router.php';
require_once './controllers/Pages.php';


$route = new Router();

$route->get('/', fn() => (new PagesController())->home() );

$route->get('/about', fn() => (new PagesController())->about() );

$route->get('/error', fn() => (new PagesController())->error() );

$route->response();