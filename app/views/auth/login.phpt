<section class="container">
  <section class="row">
    <section class="col-md-6 col-md-offset-3" id="login-form-container">
      <div class="row">
        <div class="col-md-6 col-md-offset-4">
            <h3 class="form-header">Admin Login</h3>
        </div>
      </div>

      <?php if(count($messages)){ 
          include('app/views/includes/messages.phpt');
      } ?>

      <form method="POST" action="/thegong/auth/process_login">
        <div class="form-group">
          <input type="text" name="username" placeholder="Username" class="form-control">
        </div>

        <div class="form-group">
          <input type="password" name="password" placeholder="Password" class="form-control">
        </div>

        <div class="form-group">
          <input type="submit" name="login" value="Log In"  style="float: right" class="normal-button">
        </div>
      </form>
    </section>
  </section>
</section>