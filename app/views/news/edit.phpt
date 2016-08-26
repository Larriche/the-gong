<section id = "page-content-container">
  <section id = "page-content">
    <section id="news-create-form">
      <h3 id="create-form-header">Create News Article</h3>
      <form method="POST" action="/thegong/news/process_edit" enctype="multipart/form-data">
        <p>
          <label for="title-name">Title:</label>
          <input required type="text" name="title" value="<?php echo $news->getTitle();?>" />
        </p>

        <p>
          <label for="body">Body:</label>
          <textarea required name="body"><?php echo $news->getBody();?></textarea>
        </p>

        <p>
          <label for="author">Author</label>
          <input required type="text" name="author" value="<?php echo $news->getAuthor();?>"/>
        </p>

        <p>
          <?php
          $tagObjs = $news->getTags();
          $tags = [];

          foreach($tagObjs as $tagObj){
            $tags[] = $tagObj->getName();
          }
          ?>
          <label for="tags">Tags:</label>
          <input type="text" name="tags" placeholder="Separate with commas" 
             value="<?php echo implode(',',$tags);?>"/>
        </p>

        <p>
           <label for="main-image">Upload New Image:</label>
           <input type="file" name="image"/>
        </p>
        
        <input type="hidden" name="news_id" value="<?php echo $news->getId();?>"/>
        
        <p id="create-buttons">
          <input type="submit" name="save_news" value="Save" class="alternate-button">
          <input type="submit" name="publish_news" value="Publish" class="normal-button">
        </p>
      </form>
    </section>
  </section>
</section>