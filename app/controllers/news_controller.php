<?php
class NewsController extends Controller
{
	public function __construct()
	{
		$this->models = ['News'];
		$this->loadModels();
	}

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

	public function create()
	{
		$this->makeView('news/create');
	}

	public function process_create()
	{
		if(isset($_POST['create_news']) || isset($_POST['save_news'])){
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

			if(empty($_FILES['image']['tmp_name'])){
				$errors[] = 'image_empty';
			}

			if(empty($errors)){
				// this is interesting
				$news = new News();

				$news->setTitle($title);
				$news->setBody($body);
				$news->setAuthor($author);

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
				if(!empty($_POST['tags'])){
					$tags = explode(',',$_POST['tags']);
					
					foreach($tags as $tag){
						$news->addTag($tag);
					}
				}
                
				// we upload the image
				$file = $_FILES['image']['tmp_name'];
                $mime = $_FILES['image']['type'];

                uploadPhoto($file,$mime,$newsId);

                $news->setImageUrl('http://localhost/thegong/resources/uploads/images/'.$newsId.'.jpg');
                $news->save();

                redirect('/thegong/news/create');
			}
		}
	}

	public function process_edit()
	{
	    if(isset($_POST['publish_news']) || isset($_POST['save_news'])){
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

			$id = $_POST['news_id'];

			if(empty($errors)){
				$news = News::find($id);

				$news->setTitle($title);
				$news->setBody($body);
				$news->setAuthor($author);

				if(isset($_POST['save_news'])){
					$news->setStatus('UNPUBLISHED');
				}
				else{
					$news->setStatus('PUBLISHED');
					$news->setDatePublished(date('Y-m-d'));
				}

				$news->save();

                // we save tags
				if(!empty($_POST['tags'])){
					$tags = explode(',',$_POST['tags']);
					
					foreach($tags as $tag){
						$news->addTag($tag);
					}
				}
                
				// we upload the new image if there is one
				if(empty($_FILES['image']['tmp_name'])){
			        $file = $_FILES['image']['tmp_name'];
                    $mime = $_FILES['image']['type'];
                    uploadPhoto($file,$mime,$id);
			    }
				
                $news->save();
			}
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
		$data = func_get_args();

		if(isset($data[0]) && is_numeric($data[0])){
			$id = $data[0];
		}

		$news = News::find($id);

		$this->makeView('news/edit',compact('news'));
	}
}
?>