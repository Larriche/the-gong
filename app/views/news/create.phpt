<section class="container" id="article-form-container">
  <section class="row">
    <section class="col-md-8 col-md-offset-2">

        <?php if(count($messages)){ 
          include('app/views/includes/messages.phpt');
        } ?>

        <form method="POST" action="/thegong/news/process_create" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title-name">Title:</label>
          <input required type="text" name="title" class="form-control">
        </div>

        <div class="form-group">
          <label for="body">Body:</label>
          <textarea  name="body" class="form-control" rows="20"></textarea>
        </div>

        <div class="form-group">
          <label for="author">Author</label>
          <input  required type="text" name="author" class="form-control">
        </div>

        <div class="form-group">
          <label for="tags">Tags:</label>
          <input  type="text" name="tags" placeholder="Separate with commas" class="form-control">
        </div>

        <div class="form-group">
           <label for="main-image">Image:</label>
           <input type="file" name="image" class="form-control">
        </div>

        <div class="form-group" style="float: right">
          <input type="submit" name="save_news" value="Save" class="alternate-button">
          <input type="submit" name="publish_news" value="Publish" class="normal-button">
        </div>
      </form>
    </section>
  </section>
</section> 