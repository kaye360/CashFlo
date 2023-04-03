<?php
/**
 * 
 * Custom App Router
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Allows developer defined routing
 * 
 * Methods are in the order in which they are invoked, with 
 * helper methods at the end
 * 
 * How it works:
 * 1. Get/Format current REQUEST_URI
 * 2. Define (get, post, put, delete, or any) routes with
 * 	  callback function for each route. Typically call a
 * 	  controller class/method in each callback.
 * 3. If current REQUEST_METHOD = route request_method,
 *    format defined route and push route/method to an
 * 	  assoc array: ['route' => method]
 * 4. Call the method in the array associated with the 
 * 	  current route via response().
 * 
 */
declare(strict_types=1);
namespace lib\Router\Router;

use Exception;
use stdClass;

class Router {

	/**
	 * 
	 * @var error_404_route
	 * 
	 * This sets the route to call if a request is made that is not registered.
	 * If not set, this will throw an error
	 * 
	 */
	private string $error_404_route = '/404';

	/**
	 * 
	 * @var url stores the current REQUEST_URI
	 * 
	 * Formatted to be an array key. any '/' is removed 
	 * @example users/23 becomes usersSLASH23
	 * 
	 */
	private string $url = '';

	/**
	 * 
	 * /@var param stores dynamic url params
	 * This is automatically set as the 2nd part of the request uri 
	 * @example /page/param/page
	 * 
	 */
	public ?string $param = null;

	/**
	 * 
	 * /@var routes stores developer defined routes and route methods
	 * 
	 */
	public array $routes = [];

	/**
	 * 
	 * @method Get/set/format request url and param
     * 
	 */
	public function __construct()
	{
		$url 		 = $this->resolve_url();
		$this->url   = $url->url;
		$this->param = $url->param;
	}

	/**
	 * 
	 * @method Resolve URL and params
	 * 
	 */
	private function resolve_url()
	{
		$url_trimmed     	  = trim($_SERVER['REQUEST_URI'], " \n\r\t\v\x00/");
		$url_filtered		  = filter_var($url_trimmed, FILTER_SANITIZE_URL);
		$url_no_query_string  = explode('?', $url_filtered)[0];
		$url_no_slashes		  = str_replace('/', '', $url_no_query_string);
		$url_array_by_slashes = explode('/', $url_no_query_string);
		$url_param 			  = $url_array_by_slashes[1] ?? null;

		return (object) [
			'url'   => $url_no_slashes,
			'param' => $url_param
		];
	}

	/**
	 * 
	 * @method Register a Route
	 * 
	 * Routes are formatted to be an array key to match $this->url
	 * any '/' will be removed
	 * any ':' will become a '_' (For :param)
	 * 
	 */
	private function register_route(
		string $req_method, 
		string $path, 
		callable $method, 
		int $response_code = 200
	) : void {
		if(
			$req_method !== 'ANY' &&
			$req_method !== $_SERVER['REQUEST_METHOD']
		) return;

		$path = trim($path, '/');
		$path = str_replace('/', '', $path);
		$path = str_replace(':', '_', $path);

		$route 				  = new stdClass();
		$route->path 		  = $path;
		$route->method 		  = $method;
		$route->response_code = $response_code;
		
		array_push( $this->routes, $route);
	}

	/**
	 * 
	 * @method Register a route that will work on any http method
	 * 
	 */
	public function any(string $route, callable $method, int $response_code)
	{
		$this->register_route('ANY', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Register a Get Route
	 * 
	 */
	public function get(string $route, callable $method, int $response_code)
	{
		$this->register_route('GET', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Register a Post Route
	 * 
	 */
	public function post(string $route, callable $method, int $response_code)
	{
		$this->register_route('POST', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Register a Delete Route
	 * 
	 */
	public function delete(string $route, callable $method, int $response_code)
	{
		$this->register_route('DELETE', $route, $method, $response_code);	
	}

	/**
	 * 
	 * @method Register a Put Route
	 * 
	 */
	public function put(string $route, callable $method, int $response_code)
	{
		$this->register_route('PUT', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Generate response based on current route
	 * 
	 * This will only be called after all routes are registered
	 * 
	 */
	public function render()
	{
		
		if( is_null($this->error_404_route) ) 
		{
			throw new Exception('No error 404 route is set in Router class');
		}
		
		$this->resolve_params();
		
		$current_route = array_filter( $this->routes, fn($route) => $route->path === $this->url );
		$current_route = array_values($current_route)[0] ?? null;

		if( empty($current_route) ) {
			header("Location: $this->error_404_route");
			die();
		}

		http_response_code( $current_route->response_code );
		
		return ($current_route->method)();
	}
	
	/**
	 * 
	 * @method Apply extracted Param to defined routes
	 * 
	 * Replace every instance of _param in $this->routes with $this->param if it is set
	 * 
	 */
	private function resolve_params() : void
	{
		if( is_null($this->param) ) return;

		foreach($this->routes as $key => $route) 
		{
			$this->routes[$key]->path = str_replace('_param', $this->param, $route->path);
		}

	}	
}