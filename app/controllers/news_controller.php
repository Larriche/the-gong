<?php
class NewsController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | News Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles all requests pertaining to news articles.
    |
    */

    /**
     * Create a new news controller instance.
     *
     * @return void
     */
	public function __construct()
	{
		// Models used by this controller
		$this->models = ['News'];
		$this->loadModels();
	}

    /**
     * Shows the landing page with the most recent news article
     *
     * @return void
     */
	public function home()
	{
		$published = News::getPublished();
		
		$news = $published[0];

        // load 8 more news articles for display in recent news section
		$recent = array_slice($published, 1, 8);

		// the rest
		$older = array_slice($published, 8);

		$this->makeView('news/view',compact('recent','news','older'));
	}

    /**
     * Shows the news article with a given id
     *
     * @return void
     */
	public function view()
	{
		$data = func_get_args();

		if(isset($data[0]) && is_numeric($data[0])){
			$id = $data[0];
		}

		$news = News::find($id);

		$published = News::getPublished();

		// load 8 news articles for display in recent news section
		$recent = array_slice($published, 0, 8);

		// the rest
		$older = array_slice($published, 8);

		$this->makeView('news/view',compact('recent','news','older'));

	}

    /**
     * Shows the news article creation form
     *
     * @return void
     */
	public function create()
	{   
		$data = $this->getFormMessages();

		$messages = $data['messages'];
	    $alertType = $data['alertType'];

		$this->makeView('news/create' , compact('messages' , 'alertType'));
	}


    /**
     * Store a news article in the database
     *
     * @return void
     */
	public function process_create()
	{
		if(isset($_POST['publish_news']) || isset($_POST['save_news'])){
			$data = $this->handleForm();

			$errors = $data['errors'];
			$input = $data['input'];

			if(empty($errors)){
				// this is interesting
				$news = new News();

				$news->setTitle($input['title']);
				$news->setBody($input['body']);
				$news->setAuthor($input['author']);

				if(isset($_POST['save_news'])){
					$news->setStatus('UNPUBLISHED');
				}
				else{
					$news->setStatus('PUBLISHED');
					$news->setDatePublished(date('Y-m-d h:m:s'));
				}

				$news->save();

				$newsId = $news->getId();
				$news = News::find($newsId);

                // we save tags
				if(!empty($input['tags'])){
					$tags = $input['tags'];
					
					foreach($tags as $tag){
						$news->addTag($tag);
					}
				}

				// we upload image if there is one and it is ok
				if($input['imagePresent']){
					$file = $_FILES['image']['tmp_name'];
                    $mime = $_FILES['image']['type'];

					uploadPhoto($file,$mime,$newsId);

	                $news->setImageUrl('http://localhost/thegong/resources/uploads/images/'.$newsId.'.jpg');	
				}
                			
                $news->save();
                
                $notifications[] = 'article_saved';

                if($news->getStatus() == 'PUBLISHED'){
                	$notifications[] = 'article_published';
                }

                logNotifications($notifications);
			}
			else{
				logNotifications($errors);
			}

			redirect('/thegong/news/create');
		}
	}

	public function process_edit()
	{
	    if(isset($_POST['publish_news']) || isset($_POST['save_news'])){
			
			$id = $_POST['news_id'];
			$data = $this->handleForm();

			$errors = $data['errors'];
			$input = $data['input'];

			if(empty($errors)){
				$news = News::find($id);

				$news->setTitle($input['title']);
				$news->setBody($input['body']);
				$news->setAuthor($input['author']);

				if(isset($_POST['publish_news'])){
					$news->setStatus('PUBLISHED');
					$news->setDatePublished(date('Y-m-d h:m:s'));
				}


                // we save tags
				if(!empty($input['tags'])){
					$tags = $input['tags'];

					foreach($tags as $tag){
						$news->addTag($tag);
					}
				}
                
                // we upload the new image if there is one and it is ok
				if($input['imagePresent']){
					$file = $_FILES['image']['tmp_name'];
                    $mime = $_FILES['image']['type'];

					uploadPhoto($file,$mime,$newsId);	
				}
                			
                $news->save();

                $notifications[] = 'article_saved';

                if($news->getStatus() == 'PUBLISHED'){
                	$notifications[] = 'article_published';
                }

                logNotifications($notifications);
			}
			else{
				logNotifications($errors);
			}

			redirect('/thegong/news/edit/'.$id);
		}	    	
	}

	public function published()
	{
		$published = News::getPublished();
		$this->makeView('news/published',compact('published'));
	}

	public function unpublished()
	{
		$unpublished = News::getUnpublished();
		$this->makeView('news/unpublished',compact('unpublished'));
	}

	public function edit()
	{
		$params = func_get_args();

		if(isset($params[0]) && is_numeric($params[0])){
			$id = $params[0];
		}
		// else we go to a custom error page , to be implemented later

		$data = $this->getFormMessages();

		$messages = $data['messages'];
		$alertType = $data['alertType'];

		$news = News::find($id);

		$this->makeView('news/edit',compact('news' , 'messages' , 'alertType'));
	}

	public function getFormMessages()
	{
	    $successMessages = [];
		$errorMessages = [];

		if(notificationExists('title_empty')){
			$errorMessages[] = '<p>Please enter a title for the article</p>';
			removeNotification('title_empty');
		}

		if(notificationExists('body_empty')){
			$errorMessages[] = '<p>Please enter a body text for the article</p>';
			removeNotification('body_empty');
		}

		if(notificationExists('author_empty')){
			$errorMessages[] = "<p>Please enter author's name</p>";
			removeNotification('author_empty');
		}

		if(notificationExists('image_invalid')){
			$errorMessages[] = "<p>The uploaded image is invalid</p>";
			removeNotification('image_invalid');
		}

		if(notificationExists('article_saved')){
			$successMessages[] = '<p>Article saved successfully</p>';
		    removeNotification('article_saved');
		}

		if(notificationExists('article_published')){
			$successMessages[] = '<p>Article has been published successfully</p>';
			removeNotification('article_published');
		}

		if(count($successMessages)){
			$messages = $successMessages;
			$alertType = 'success';
		}
		else if(count($errorMessages)){
			$messages = $errorMessages;
			$alertType = 'danger';
		}
		else{
			$messages = [];
			$alertType = '';
		}

		return ['messages'=>$messages , 'alertType'=>$alertType];		
	}

	public function handleForm()
	{
		$errors = [];

		if(empty($_POST['title'])){
			$errors[] = 'title_empty';
		}
		else{
			$title = $_POST['title'];
		}


		if(empty($_POST['body'])){
			$errors[] = 'body_empty';
		}
		else{
			$body = $_POST['body'];
		}


		if(empty($_POST['author'])){
			$errors[] = 'author_empty';
		}
		else{
			$author = $_POST['author'];
		}

        $imagePresent = false;

		if(!empty($_FILES['image']['tmp_name'])){
            $mime = $_FILES['image']['type'];

            if(explode('/' , $mime)[0] != 'image'){
                $errors[] = 'image_invalid';
            }
            else{
            	$imagePresent = true;
            }       			
		}

		if(!empty($_POST['tags'])){
			$tags = explode(',' , $_POST['tags']);
		}
		else{
			$tags = [];
		}

		$data = [];
		$input = ['tags' => $tags,'imagePresent' => $imagePresent];

		if(count($errors)){
			$data['errors'] = [];
		}
		else{
			$data['errors'] = [];
			$input += ['title' => $title,'body'=>$body , 'author'=>$author ];
		}

		$data['input'] = $input;

		return $data;
	}
}
?>