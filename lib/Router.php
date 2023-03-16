<?php
namespace Router;
/**
 * 
 * Custom API router
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Methods are in the order in which they are invoked, with 
 * helper methods at the end
 * 
 * How it works:
 * 1. Get/Format current REQUEST_URI
 * 		--_contstruct
 * 2. Define routes with callback function for each route
 * 3. If current REQUEST_METHOD = route request_method,
 *    format defined route and push route/method to an
 * 	  assoc array: ['route' => method]
 * 4. Call the method in the array associated with the 
 * 	  current route via response().
 * 
 */

class Router
{

	private string $url = '';
	public ?string $param = null;
	public array $routes = [];

	/**
	 * Get/set current URL without url filters and param 
     * 
     * Routes are formatted to avoid slashes in key names
     * For example, ['book/1' => function() { ... } ] 
     * becomes ['bookSLASH1' => function() { ... }]
	 */
	public function __construct()
	{
		$url = trim($_SERVER['REQUEST_URI'], " \n\r\t\v\x00/");
		$url = filter_var($url, FILTER_SANITIZE_URL);

		if( empty($url) ) {
			$url = '/';
		}

		$url_no_query_string = explode('?', $url)[0];

		$url_formatted_as_key = str_replace('/', 'SLASH', $url_no_query_string);
		$this->url = $url_formatted_as_key;
		
		$url_array_by_slashes = explode('/', $url_no_query_string);
		if( isset($url_array_by_slashes[1]) ) {
			$this->param = $url_array_by_slashes[1];
		} 
	}



	/**
	 * Register a Route
	 */
	private function register_route($req_method, $route, $method)
	{
		if($_SERVER['REQUEST_METHOD'] !== $req_method) return;

		$route = trim($route, '/');
		if(empty($route)) $route = '/';
		$route = str_replace('/', 'SLASH', $route);
		$route = str_replace(':', '_', $route);
		
		$this->routes[$route] = $method;
	}



	/**
	 * Register a Get request
	 */
	public function get($route, $method)
	{
		$this->register_route('GET', $route, $method);
	}
	


	/**
	 * Register a Post Request
	 */
	public function post($route, $method)
	{
		$this->register_route('POST', $route, $method);
	}
	


	/**
	 * Register a Delete Request
	 */
	public function delete($route, $method)
	{
		$this->register_route('DELETE', $route, $method);	
	}



	/**
	 * Register a Put Request
	 */
	public function put($route, $method)
	{
		$this->register_route('PUT', $route, $method);
	}



	/**
	 * Generate response based on current route
	 */
	public function response()
	{
		// Swap :param with requested param if GET route
		if($_SERVER['REQUEST_METHOD'] === 'GET') {
			$this->routes = $this->apply_params($this->routes);
		}

		// Check if current route exists in routes
		if(!array_key_exists( $this->url, $this->routes )) {
			echo '404';
			return;
		}

		return $this->routes[$this->url]();
	}


	
	/**
	 * Apply Params to $route_methods in $this->response
	 */
	private function apply_params($route_methods)
	{
		if( is_null($this->param) ) return $route_methods;

		// Every :param gets replaced with _param to be array key friendly
		// replace _param with with $this->param in all routes
		foreach($route_methods as $key => $value) {

			$new_key = str_replace('_param', $this->param, $key);

			if( array_key_exists($new_key, $route_methods) ) continue;

			unset($route_methods[$key]);
			$route_methods[$new_key] = $value;
		}

		return $route_methods;
	}


	
	/**
	 * Render Error Message
	 */
	public function error($message) 
	{
		return ['error' => $message];
	}
	
}