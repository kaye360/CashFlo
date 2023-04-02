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



class Router {

	/**
	 * 
	 * @var url stores the current REQUEST_URI
	 * 
	 * Formatted to be an array key. any '/' is converted to 'SLASH' 
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
	 * @method Get/set/format request url and params
     * 
	 */
	public function __construct()
	{
		$url = trim($_SERVER['REQUEST_URI'], " \n\r\t\v\x00/");
		$url = filter_var($url, FILTER_SANITIZE_URL);

		if( empty($url) ) $url = '/';

		$url_no_query_string = explode('?', $url)[0];
		$url_formatted_as_key = str_replace('/', 'SLASH', $url_no_query_string);
		$this->url = $url_formatted_as_key;
		
		$url_array_by_slashes = explode('/', $url_no_query_string);
		if( isset($url_array_by_slashes[1]) ) 
		{
			$this->param = $url_array_by_slashes[1];
		} 
	}

	/**
	 * 
	 * @method Register a Route
	 * 
	 * Routes are formatted to be an array key to match $this->url
	 * any '/' will become a 'SLASH'
	 * any ':' will become a '_' // For :param
	 * 
	 */
	private function register_route(string $req_method, string $route, callable $method)
	{
		if(
			$req_method !== 'ANY' &&
			$req_method !== $_SERVER['REQUEST_METHOD']
		) return;

		$route = trim($route, '/');
		if(empty($route)) $route = '/';
		$route = str_replace('/', 'SLASH', $route);
		$route = str_replace(':', '_', $route);
		
		$this->routes[$route] = $method;
	}

	/**
	 * 
	 * @method Register a route that will work on any http method
	 * 
	 */
	public function any(string $route, callable $method)
	{
		$this->register_route('ANY', $route, $method);
	}

	/**
	 * 
	 * @method Register a Get Route
	 * 
	 */
	public function get(string $route, callable $method)
	{
		$this->register_route('GET', $route, $method);
	}

	/**
	 * 
	 * @method Register a Post Route
	 * 
	 */
	public function post(string $route, callable $method)
	{
		$this->register_route('POST', $route, $method);
	}

	/**
	 * 
	 * @method Register a Delete Route
	 * 
	 */
	public function delete(string $route, callable $method)
	{
		$this->register_route('DELETE', $route, $method);	
	}

	/**
	 * 
	 * @method Register a Put Route
	 * 
	 */
	public function put(string $route, callable $method)
	{
		$this->register_route('PUT', $route, $method);
	}

	/**
	 * 
	 * @method Generate response based on current route
	 * 
	 */
	public function render()
	{
		// Swap :param with requested param
		$this->routes = $this->apply_params($this->routes);

		// Check if current route exists in routes
		if(!array_key_exists( $this->url, $this->routes )) 
		{
			header('Location: /error');
			die();
		}

		return $this->routes[$this->url]();
	}
	
	/**
	 * 
	 * @method Apply extracted Param to defined routes
	 * 
	 */
	private function apply_params(array $route_methods)
	{
		if( is_null($this->param) ) return $route_methods;

		// Every :param gets replaced with _param to be array key friendly
		// replace _param with with $this->param in all routes
		foreach($route_methods as $key => $value) 
		{
			$new_key = str_replace('_param', $this->param, $key);

			if( array_key_exists($new_key, $route_methods) ) continue;

			unset($route_methods[$key]);
			$route_methods[$new_key] = $value;
		}

		return $route_methods;
	}
	
	/**
	 * 
	 * @method Return an Error Message
	 * 
	 */
	public function error(string $message) 
	{
		return ['error' => $message];
	}
	
}