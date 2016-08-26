<section class="container">
  <section class="row">
    <section class="col-md-6 col-md-offset-3" id="login-form-container">
      <div class="row">
        <div class="col-md-6 col-md-offset-4">
            <h3 class="form-header">Password Update</h3>
        </div>
      </div>

      <?php if(count($messages)){ 
          include('app/views/includes/messages.phpt');
      } ?>

      <form method="POST" action="/thegong/auth/process_password_update">
        <div class="form-group">
          <input type="password" name="current_password" placeholder="Current Password" class="form-control">
        </div>

        <div class="form-group">
          <input type="password" name="new_password" placeholder="New Password" class="form-control">
        </div>

        <div class="form-group">
          <input type="submit" name="update_password" value="Update"  style="float: right" class="normal-button">
        </div>
      </form>
    </section>
  </section>
</section>