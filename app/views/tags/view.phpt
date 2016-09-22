<section class="container-fluid">
  <section class="row">
    <section class="col-md-8 col-md-offset-2">
        <h3>News Articles tagged with #<?php echo $tag->getName();?></h3>
        <hr />

        <?php foreach($news as $new){?>
        	<section>
               <p>
        	   <a class="news-link" href="/thegong/news/view/<?php echo $new->getId();?>"><?php echo $new->getTitle();?></a>
        	   </p>
        	</section>
        <?php } ?>
    </section>
  </section>
</section>