<section class="container">
  <section class="row">
    <section class="col-md-7 col-md-offset-3">
      <h3 class="items-view-header">Published Articles</h3>
      <hr />
      <?php if(count($published) > 0) { ?>
        <table class="table">
         <thead>
         </thead>
         <tbody>
        <?php foreach($published as $post) {?>
          <tr>
            <td><?php echo $post->getTitle();?></td>
            <td><a class="normal-button">View</a></td>
            <td><a class="alternate-button">Make Unpublished</a></td>
          </tr>
        <?php } ?>
         </tbody>
        </table>
      <?php } else{ ?>
        <p>No published news articles</p>
      <?php } ?>
    </section>
  </section>
</section>