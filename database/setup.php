<?php
$dbhost  = 'localhost';    
$dbname  = 'news';   
$dbuser  = 'root';  
$dbpass  = '';   

$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// a quick function cooked up to reduce the amount of typing
// required to create the tables
function queryMysql($query)
{
    global $connection;
    $result = $connection->query($query);
    if (!$result) 
    	echo $connection->error.'<br />';
    else 
    	echo "Query ran Ok"."<br />";

    return $result;
}

queryMysql(
	"CREATE TABLE users
	(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(100),
	password VARCHAR(255)
	);"
);

queryMysql(
	"CREATE TABLE news_articles
	(
	   id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	   title VARCHAR(255),
	   body TEXT,
	   author VARCHAR(255),
	   status enum('PUBLISHED','UNPUBLISHED'),
	   date_published DATETIME,
	   image_url VARCHAR(100)
	);"
);

queryMysql(
	"CREATE TABLE comments
	(
	 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	 news_id INT UNSIGNED NOT NULL,
	 poster VARCHAR(255),
	 body TEXT,
	 date_posted DATETIME,
	 FOREIGN KEY(news_id) REFERENCES news_articles(id)
	     ON DELETE CASCADE
	     ON UPDATE CASCADE
	);"
);

queryMysql(
	"CREATE TABLE tags
	(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
	);"
);

queryMysql(
	"CREATE TABLE tags_to_posts
	(
	 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	 tag_id INT UNSIGNED NOT NULL,
	 post_id INT UNSIGNED NOT NULL,
	 FOREIGN KEY(post_id) REFERENCES news_articles(id)
	     ON DELETE CASCADE
	     ON UPDATE CASCADE,
	 FOREIGN KEY(tag_id) REFERENCES tags(id)
	     ON DELETE CASCADE
	     ON UPDATE CASCADE
	);"
);

$username = "admin";
$password = password_hash("admin",PASSWORD_DEFAULT);
$result = $connection->query("INSERT INTO users(username,password)
 	                      VALUES('$username','$password')");

if(!$result)
	echo $connection->error;
else
	echo "Done";