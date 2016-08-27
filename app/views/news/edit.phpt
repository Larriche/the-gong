<section class="container" id="article-form-container">
  <section class="row">
    <section class="col-md-8 col-md-offset-2">

        <?php if(count($messages)){ 
          include('app/views/includes/messages.phpt');
        } ?>

        <form method="POST" action="/thegong/news/process_edit" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title-name">Title:</label>
          <input type="text" name="title" class="form-control" value="<?php echo $news->getTitle();?>">
        </div>

        <div class="form-group">
          <label for="body">Body:</label>
          <textarea  name="body" class="form-control" rows="20">
            <?php echo $news->getBody();?>
          </textarea>
        </div>

        <div class="form-group">
          <label for="author">Author</label>
          <input  type="text" name="author" class="form-control" value="<?php echo $news->getAuthor();?>">
        </div>

        <?php
          $tagObjs = $news->getTags();
          $tags = [];

          foreach($tagObjs as $tagObj){
            $tags[] = $tagObj->getName();
          }
        ?>
        <div class="form-group">
          <label for="tags">Tags:</label>
           <input type="text" name="tags" placeholder="Separate with commas" 
             value="<?php echo implode(',',$tags);?>"/>
        </div>

        <div class="form-group">
           <label for="main-image">Image:</label>
           <input type="file" name="image" class="form-control">
        </div>

        <input type="hidden" name="news_id" value="<?php echo $news->getId();?>">

        <div class="form-group" style="float: right">
          <input type="submit" name="save_news" value="Save" class="alternate-button">
          <input type="submit" name="publish_news" value="Publish" class="normal-button">
        </div>
      </form>
    </section>
  </section>
</section> 