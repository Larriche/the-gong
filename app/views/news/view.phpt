<section class="container-fluid">
  <section class="row">
    <section class="col-md-8 col-md-offset-2">
      <article>
         <section id="news-head">
            <h1><?php echo $news->getTitle(); ?></h1>
            <p>by <?php echo $news->getAuthor();?></p>
         </section>
         <hr />

         <section id="news-body">
            <?php if($news->getImageUrl()) { ?>
            <img src="<?php echo $news->getImageUrl();?>" width="300" height="300"
              alt="featured image"/>
            <?php } ?>

            <?php $news->setbody('<p>'.str_replace("\n","</p><p>",$news->getBody()).'</p>'); echo $news->getBody();?>
         </section>

          <?php
          $tagObjs = $news->getTags();
          foreach($tagObjs as $tagObj){ ?>
            <span class="tag"><a href="/thegong/tags/view/<?php echo $tagObj->getId();?>"><?php echo $tagObj->getName();?></a></span>
          <?php } ?>

          <section id="comments-container">
            <?php $comments = $news->getComments(); foreach($comments as $comment) {?>
              <section class="comment">
                <section class="comment-header">
                <hr />
                <?php $date = new PrettyDate($comment->getDatePosted());?>
                  <span><?php echo $comment->getPoster();?> on <?php echo $date->getReadable();?> wrote </span>
                </section>
                <hr />
                <p><?php echo $comment->getBody();?></p>
              </section>
            <?php } ?>
         </section>

         <section id="comment-form">
            <form method="POST" action="/thegong/comments/process_create" role="form">
              <div class="form-group">
                <input type="text" name="name" placeholder="Your Name" class="form-control"/>
              </div>

              <div class="form-group">
                <textarea name="comment" placeholder="Your Comment" rows="10" class="form-control"></textarea>
              </div>

              <input type="hidden" name="news_id" value="<?php echo $news->getId();?>"/>

              <p>
                <input type="submit" name="make_comment" class="normal-button" style="float: right" value="Comment">
              </p>
            </form>
         </section>

         <?php if(count($recent)) { ?>
         <h2 class="items-view-header">Recent News Articles</h2>
         <hr />
         <?php } ?>

         <section class="row">
           <?php 
            $count = 0; 
            while($count < 4 && $count < count($recent)) { $post = $recent[$count]; ?>
              <section class='col-md-3 news-item'>
                <a href="/thegong/news/view/<?php echo $post->getId();?>"><img src="<?php echo $post->getImageUrl();?>" class="img-responsive" width="300" height="300"/></a>
                <p><?php echo $post->getTitle();?></p>
                </a>
              </section>
            <?php $count++; } ?>
         </section>

         <section class="row">
           <?php while($count < 8 && $count < count($recent)) { $post = $recent[$count]; ?>
            <section class='col-md-3 news-item'>
              <a href="/thegong/news/view/<?php echo $post->getId();?>"><img src="<?php echo $post->getImageUrl();?>" class="img-responsive" width="300" height="300"/></a>
              <p><?php echo $post->getTitle();?></p>
              </a>
            </section>
          <?php $count++; } ?>
         </section>

         <?php if(count($older)) { ?>
           <h2 class="items-view-header">Older News Articles</h2>
           <hr />
           <section class="row">
              <?php foreach ($older as $post) { ?>
                  <section class='col-md-3 news-item'>
                <a href="/thegong/news/view/<?php echo $post->getId();?>"><img src="<?php echo $post->getImageUrl();?>" class="img-responsive" width="300" height="300"/></a>
                <p><?php echo $post->getTitle();?></p>
                </a>
              </section>
              <?php } ?>
           </section>
         <?php } ?>

      </article>
    </section>
  </section>
</section>