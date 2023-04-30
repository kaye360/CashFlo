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
 * 	  save routes to array
 * 4. Call the method in the array associated with the 
 * 	  current route via render().
 * 
 */
declare(strict_types=1);
namespace lib\Router\Router;

use Exception;
use lib\Redirect\Redirect\Redirect;
use stdClass;



class Router {

	/**
	 * 
	 * /@var error_404_route
	 * 
	 * This sets the route to call if a request is made that is not registered.
	 * If not set, this will throw an error
	 * 
	 */
	private string $error_404_route = '/error/404';

	/**
	 * 
	 * /@var url stores the current REQUEST_URI
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
	public ?object $params = null;

	/**
	 * 
	 * 
	 */
	public $resolved_path_key = null;

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
		$this->url = (string) $this->resolve_url();
	}

	/**
	 * 
	 * @method Resolve URL and params
	 * 
	 */
	private function resolve_url() : string
	{
		$url_trimmed     	  = trim($_SERVER['REQUEST_URI'], " \n\r\t\v\x00/");
		$url_filtered		  = filter_var($url_trimmed, FILTER_SANITIZE_URL);
		$url_no_query_string  = explode('?', $url_filtered)[0];
		$url_no_query_string  = empty($url_no_query_string) ? '/' : $url_no_query_string;
		
		return $url_no_query_string;
	}

	/**
	 * 
	 * @method Register a Route
	 * 
	 * Only registers routes that match the current request method
	 * or if the route is registered as 'ANY'. Otherwise it is ignored
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

		$path = $path === '/' ? $path : trim($path, '/');

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
	public function any(string $route, callable $method, int $response_code) : void
	{
		$this->register_route('ANY', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Register a Get Route
	 * 
	 */
	public function get(string $route, callable $method, int $response_code) : void
	{
		$this->register_route('GET', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Register a Post Route
	 * 
	 */
	public function post(string $route, callable $method, int $response_code) : void
	{
		$this->register_route('POST', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Register a Delete Route
	 * 
	 */
	public function delete(string $route, callable $method, int $response_code) : void
	{
		$this->register_route('DELETE', $route, $method, $response_code);	
	}

	/**
	 * 
	 * @method Register a Put Route
	 * 
	 */
	public function put(string $route, callable $method, int $response_code) : void
	{
		$this->register_route('PUT', $route, $method, $response_code);
	}

	/**
	 * 
	 * @method Resolve route and params
	 * 
	 */
	public function resolve() : void
	{
		$request_uri 		  = explode('/', $this->url);

		// Loop thru defined routes
		foreach($this->routes as $key => $route)
		{
			$params 	= [];
			$route_path = explode('/', $route->path);

			// Check if route path and uri path have the same amount of uri 'sections'
			if( count($route_path) !== count($request_uri) ) continue;

			// Compare each slice of $request_uri[] with $route_path[]
			foreach($route_path as $path_key => $path_slice)
			{
				// Check for param. First letter should be :
				// If param, add to array
				$is_param = substr($path_slice, 0, 1) === ':';

				if( $is_param ) 
				{
					$param_key   		= substr($route_path[$path_key], 1);
					$param_value 		= $request_uri[$path_key];
					$params[$param_key] = $param_value;
					$path_slice 		= $param_value;
				}

				// Check for matching slice
				if( $path_slice !== $request_uri[$path_key]) break;

				// If we are at the end of the path loop, all slices have matched
				// We have a match!
				if( $path_key === array_key_last($route_path) ) 
				{
					$this->resolved_path_key = $key;
					$this->params 	 		 = (object) $params;
				}
			}
		}
	}

	/**
	 * 
	 * @method Get the params for the current requested route
	 * 
	 */
	public function get_params() : object | null
	{
		return $this->params;
	}

	/**
	 * 
	 * @method Get the path for the current requested route
	 * 
	 */
	public function get_path() : string | null
	{
		return $this->url;
	}

	/**
	 * 
	 * @method Call the route method if it is set. Else 404
	 * 
	 */
	public function call_route_method() : void
	{
		if( is_null($this->resolved_path_key) ) 
		{
			Redirect::to($this->error_404_route);
		}

		($this->routes[$this->resolved_path_key]->method)();
	}
	
}