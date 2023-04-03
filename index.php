<?php
/**
 * 
 * -----------------------------------
 * | Spendly                         |
 * | A personal expense tracking app |
 * -----------------------------------
 * 
 * 
 * Welcome to Spendly: A personal expense tracking app!
 * @author Josh Kaye - https://joshkaye.dev
 * @link to github repo: https://github.com/kaye360/spendly_v2
 * 
 * 
 * This is the gateway to the entire application
 * 
 * Bootstrap.php fires up the app
 * 
 * App Routes are defined in /routes/routes.php
 * 
 */
declare(strict_types=1);
use lib\Router\Route\Route;

/**
 * Bootstrap the App Essentials
 */
require_once './bootstrap.php';

/**
 * Render the requested route
 */
Route::render();
