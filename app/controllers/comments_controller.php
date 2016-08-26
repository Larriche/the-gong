<?php
class CommentsController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Comments Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles actions pertaining to comments on news articles
    |
    */

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
	public function __construct()
	{
		$this->models = ['Comment'];
		$this->loadModels();
	}


    /**
     * Store a new comment in the database
     *
     * @return void
     */
	public function process_create()
	{
		if(isset($_POST['make_comment'])){
			$errors = [];
			$notifications = [];

			if(empty($_POST['name'])){
				$errors[] = 'name_empty';
			}
			else{
				$name = sanitizeInput($_POST['name']);
			}

			if(empty($_POST['comment'])){
				$errors[] = 'comment_empty';
			}
			else{
				$body = sanitizeInput($_POST['comment']);
			}

			$newsId = $_POST['news_id'];

			if(empty($errors)){
				$comment = new Comment;

				$comment->setNewsId($newsId);
				$comment->setPoster($name);
				$comment->setBody($body);
				$comment->setDatePosted(date('Y-m-d h:m:s'));

				$comment->save();

				redirect('/thegong/news/view/'.$newsId);
			}
		}
	}
}
?>