<?php
class App
{
	/*
    |--------------------------------------------------------------------------
    | Routes Handler
    |--------------------------------------------------------------------------
    |
    | This class interpretes the links passed to the app and extracts the 
    | controllers and their methods from them and then loads the required
    | controller files
    | 
    |
    */

    // default controller is home
	protected $controller = "home";    

    // default method is index
	protected $method ="index";
    
    // parameters to be passed in page url
	protected $params = [];

    
    /**
     * Create a new App instance.
     *
     * @return void
     */
	public function __construct()
	{
		$url = $this->parseUrl();

		// check if the controller file exists
		// if it exists,then remove from the url array
		// if it doesn't exist we have to redirect to a 404 page
		// but currently we redirecting to home/index
		if(file_exists('app/controllers/'.$url[0].'_controller.php'))
		{
			$this->controller = $url[0].'_controller';
			unset($url[0]);
		}
		else{
			//404 controller
		}

		require_once 'app/controllers/'.$this->controller.'.php';

        // remove the underscores so that can look like the corresponding
        // Controller class name
        $this->controller = str_replace('_','',$this->controller);

		// create an instance of the given controller
		$this->controller = new $this->controller;

        
        // check if the controller method exists
		// if it exists,then remove from the url array
		// if it doesn't exist we have to redirect to a 404 page
		// but that's not implemented yet
		// but currently we redirecting to home/index
		if(isset($url[1]))
		{
			if(method_exists($this->controller, $url[1]))
			{
				$this->method = $url[1];
				unset($url[1]);
			}
			else{
				// 404 
			}
		}

        // after extracting the controller and controller methods,
        // the rest of the array are parameters to be passed to the
        // controller method
		$this->params = $url ? array_values($url) : [];
		call_user_func_array([$this->controller,$this->method],$this->params);
	}


    /**
     * This method gets the url which with the help of a .htaccess file
     * is passed to index.php as ?url=controller/method/parameters.
     * It then creates an array by spltting the url into parts using the / as
     * a separator
     *
     * @return array an aray of url parts
     */
	public function parseUrl()
	{
		if(isset($_GET['url'])){
			if($_GET['url'] != ""){
			    $url = filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL);
			    return explode('/',$url);
			}
			else return ['news' , 'home'];
		}
		else {
			return ['news','home'];
		}
	}
}