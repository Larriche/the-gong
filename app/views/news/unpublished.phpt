<section class="container">
  <section class="row">
    <section class="col-md-7 col-md-offset-3">
      <h3 class="items-view-header">Unpublished Articles</h3>
      <hr />
      <?php if(count($unpublished) > 0) { ?>
        <table class="table">
         <colgroup>
           <col style="width: 300px"/>
           <col style="width: 100px"/>
           <col style="width: 100px"/>
           <col style="width: 100px"/>
         </colgroup>
        <?php foreach($unpublished as $post) {?>
          <tr>
            <td><?php echo $post->getTitle();?></td>
            <td><a href="/thegong/news/edit/<?php echo $post->getId();?>" class="normal-button">Edit</a></td>
            <td><a class="alternate-button">View</a></td>
            <td><a class="danger-button">Delete</a></td>
          </tr>
        <?php } ?>
        </table>
      <?php } else{ ?>
        <p>No unpublished news articles</p>
      <?php } ?>
    </section>
  </section>
</section>