<?php
class Comment
{
	/*
    |--------------------------------------------------------------------------
    | Comment Model
    |--------------------------------------------------------------------------
    |
    | This model represents a comment on a news article 
    |
    */

    // id of comment instance
	protected $id;
    
    // name of comment poster
	protected $poster;

    // id of associated news article
	protected $newsId;

    // the content of the comment
	protected $body;

    // date comment was made
	protected $datePosted;

    // whether this comment instance was loaded from already existing
    // info in database
	public $loadedFromTable = false;

    /**
     * Set the id of this comment instance
     * 
     * @param $id the id of the comment as loaded from the database or 
     * @return void
     */
	public function setId($id)
	{
		$this->id = $id;
	}

    /**
     * Get the id of this comment instance
     *  
     * @return int $id id of the comment instance
     */
	public function getId()
	{
		return $this->id;
	}

    /**
     * Set the name of the poster of this comment
     * 
     * @param $string name of poster 
     * @return void
     */
	public function setPoster($posterName)
	{
		$this->poster = $posterName;
	}

    /**
     * Get the name of the poster of this comment instance
     *  
     * @return string the name of the poster of this comment
     */
	public function getPoster()
	{
		return $this->poster;
	}
    
    /**
     * Set the id of the news associated with this comment
     * 
     * @param $id of the news article as loaded from the database  
     * @return void
     */
	public function setNewsId($id)
	{
		$this->newsId = $id;
	}

    /**
     * Get the id of the news article associated with this comment
     *  
     * @return int $id id of news article
     */
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