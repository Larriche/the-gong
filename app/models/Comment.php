<?php
class Comment
{
	protected $id;

	protected $poster;

	protected $newsId;

	protected $body;

	protected $datePosted;

	public $loadedFromTable = false;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setPoster($posterName)
	{
		$this->poster = $posterName;
	}

	public function getPoster()
	{
		return $this->poster;
	}

	public function setNewsId($id)
	{
		$this->newsId = $id;
	}

	public function getNewsId()
	{
		return $this->newsId;
	}

	public function setBody($body)
	{
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function setDatePosted($date)
	{
		$this->datePosted = $date;
	}

	public function getDatePosted()
	{
		return $this->datePosted;
	}

	public function save()
    {
    	if($this->loadedFromTable){
    		$dbman = new DBUpdateManager();

    		$dbman->in('comments')->update('poster','news_id','body','date_posted')
    		  ->with($this->poster,$this->newsId,$this->body,$this->datePosted)
    		  ->where('id')->match($this->id)->execute();
    	}
    	else{
    		$dbman = new DBInsertManager();

    		$dbman->into('comments')->insert($this->poster,$this->newsId,$this->body,$this->datePosted)
    		  ->fields('poster','news_id','body','date_posted')->execute();   

    		$this->id = $dbman->getLastInsertId(); 		
    	}
	}

	public static function createFromRow($row)
	{
		$comment = new Comment;

		$comment->loadedFromTable = true;

		$comment->setId($row['id']);
		$comment->setPoster($row['poster']);
		$comment->setNewsId($row['news_id']);
		$comment->setBody($row['body']);
		$comment->setDatePosted($row['date_posted']);

		return $comment;
	}
}
?>