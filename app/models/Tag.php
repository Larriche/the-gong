<?php
class Tag
{
	protected $id;

	protected $name;

	public $loadedFromTable;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getNews()
	{
		$dbman = new DBSelectManager();
        
        $tagId = $this->id;
        $news = [];

		$rows = $dbman->select('*')->from('news_articles')->join('tags_to_posts')->on("news_articles.id = tags_to_posts.post_id AND tags_to_posts.tag_id = '$tagId'")->getRows();

		if(count($rows)){
			foreach($rows as $row){
				$news[] = News::createFromRow($row);
			}
		}
        
		return $news;
	}

	public function save()
	{
		if($this->loadedFromTable){
			$dbman = new DBUpdateManager();

			$dbman->in('tags')->update('name')->with($this->name)->where('id')->match($this->id)
			  ->execute();
		}
		else{
			$dbman = new DBInsertManager();

			$dbman->into('tags')->insert($this->name)->fields('name')->execute();

			$this->setId($dbman->getLastInsertId());
		}
	}

	public static function createFromRow($row)
	{
		$tag = new Tag();

		$tag->setId($row['id']);
		$tag->setName($row['name']);
		$tag->loadedFromTable = true;

		return $tag;
	}

	public static function find($id)
	{
		$dbman = new DBSelectManager();

		$rows = $dbman->select('*')->from('tags')->where('id')->match($id)->getRows();

		if(count($rows) > 0){
			return Tag::createFromRow($rows[0]);
		}
		else{
			return null;
		}
	}

	public static function findByName($name)
	{
		$dbman = new DBSelectManager();

		$rows = $dbman->select('*')->from('tags')->where('name')->match($name)->getRows();

        if(count($rows) > 0){
			return Tag::createFromRow($rows[0]);
		}
		else{
			return null;
		}

	}
}
?>