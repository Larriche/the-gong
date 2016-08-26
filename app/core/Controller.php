<?php
class Controller
{
	/*
    |--------------------------------------------------------------------------
    | Controller
    |--------------------------------------------------------------------------
    |
    | This class interpretes the links passed to the app and extracts the 
    | controllers and their methods from them and then loads the required
    | controller files
    | 
    |
    */

    protected $models;

    
    /**
     * Load the files containing the required Model classes
     *
     * @return void
     */
	public function loadModels()
	{
        foreach($this->models as $model){
            require_once 'app/models/'.$model.'.php';	
        }
        
	}

    /**
     * Loads the required View file.A view file contains mostly html
     * for rendering a page
     * 
     * @param  string view a link to the folder and name of the view file
     * @return void
     */
	public function makeView($view,$data = [])
	{
		extract($data);

		require_once('app/views/header.phpt');
		require_once 'app/views/'.$view.'.phpt';
		require_once('app/views/footer.phpt');
	}


}
?>