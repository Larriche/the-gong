<?php
require('app/models/Tag.php');
require('app/models/Comment.php');

class News
{
	protected $id;

	protected $title;

	protected $body;

	protected $author;

	protected $imageUrl;

	protected $datePublished;

	protected $status;

	protected $tags = [];

	public $loadedFromTable = false;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setBody($body)
	{
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setImageUrl($image)
	{
		$this->imageUrl = $image;
	}

	public function getImageUrl()
	{
		if(!empty($this->imageUrl)){
			return $this->imageUrl;
		}

		return null;
	}

	public function setDatePublished($date)
	{
		$this->datePublished = $date;
	}

	public function getDatePublished()
	{
		return $this->datePublished;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function addTag($tagName)
	{
		$tag = Tag::findByName($tagName);

		if(!$tag){
			$tag = new Tag();

			$tag->setName($tagName);
			$tag->save();
		}

		$id = $tag->getId();

		$dbman = new DBInsertManager();

		$dbman->into('tags_to_posts')->insert($this->id,$id)->fields('post_id','tag_id')
		  ->execute();

		$this->tags[] = $tag;
	}

	public function setTags()
	{
		$dbman = new DBSelectManager();

		$rows = $dbman->select('tag_id')->from('tags_to_posts')->where('post_id')->match($this->id)
		  ->getRows();



		if(count($rows) > 0){
			foreach($rows as $row){
				$this->tags[] = Tag::find($row['tag_id']);
			}
		}

	}

	public function getTags()
	{
		return $this->tags;
	}

	public function save()
	{
		if($this->loadedFromTable){
			$dbman = new DBUpdateManager();

			$dbman->in('news_articles')->update('title','body','author','date_published','status','image_url')
			  ->with($this->title,$this->body,$this->author,
				$this->datePublished,$this->status,$this->imageUrl)->where('id')->match($this->id)->execute();
		}
		else{
			$dbman = new DBInsertManager;

			$dbman->into('news_articles')->insert($this->title,$this->body,$this->author,$this->imageUrl,
				$this->datePublished,$this->status)
			  ->fields('title','body','author','image_url','date_published','status')->execute();

			$this->id = $dbman->getLastInsertId();
		}
	}

	public function getComments()
	{
		$comments = [];

		$dbman = new DBSelectManager();

		$rows = $dbman->select('*')->from('comments')->where('news_id')->match($this->id)
		  ->getRows();

		if(count($rows) > 0){
			foreach($rows as $row){
				$comments[] = Comment::createFromRow($row);
			}
		}

		return $comments;
	}

	public static function createFromRow($row)
	{
		$news = new News();

		$news->setId($row['id']);
		$news->setTitle($row['title']);
		$news->setBody($row['body']);
		$news->setAuthor($row['author']);
		$news->setStatus($row['status']);
		$news->setDatePublished($row['date_published']);
		$news->setImageUrl($row['image_url']);
		$news->loadedFromTable = true;
        $news->setTags();

          
		return $news;
	}

	public static function find($id)
	{
		$dbman = new DBSelectManager();

		$rows = $dbman->select('*')->from('news_articles')->where('id')->match($id)
		  ->getRows();

		if(count($rows) > 0){
			return News::createFromRow($rows[0]);
		}
		else{
			return null;
		}
	}

	public static function getPublished()
	{
		$dbman = new DBSelectManager();
		$published = [];

		$rows = $dbman->select('*')->from('news_articles')->where('status')->match('PUBLISHED')
		   ->orderBy('date_published','DESC')->getRows();

		if(count($rows) > 0){
			foreach($rows as $row){
				$published[] = News::createFromRow($row);
			}
		}

		return $published;
	}

	public static function getUnpublished()
	{
		$dbman = new DBSelectManager();
		$unpublished = [];

		$rows = $dbman->select('*')->from('news_articles')->where('status')->match('UNPUBLISHED')
		   ->getRows();

		if(count($rows) > 0){
			foreach($rows as $row){
				$unpublished[] = News::createFromRow($row);
			}
		}

		return  $unpublished;
	}


}
?>