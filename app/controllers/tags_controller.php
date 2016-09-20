<?php
class TagsController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Tags Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles actions pertaining to post tags including their
    | deletion and display of associated news articles
    |
    */

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
	public function __construct()
	{
		// load the models that this controller will make use of
		$this->models = ['News','Tag'];
		$this->loadModels();
	}

     /**
     * Display a list of news items associated with a given tag
     *
     * @return void
     */
	public function view()
	{
		$data = func_get_args();

		if(isset($data[0])){
			$id = $data[0];
		}
		else{
			$this->makeView('errors/system_error');
			return;
		}

		$tag = Tag::find($id);
		$news = $tag->getNews();

		$this->makeView('tags/view',compact('tag','news'));
	}
}
?>