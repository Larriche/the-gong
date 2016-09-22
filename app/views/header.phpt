<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>The Gong</title>

    <!-- when application is online , it's better to link to hosted version -->
    <link rel="stylesheet" href="/thegong/public/css/bootstrap.min.css">

    <!-- my own style for the site -->
    <link rel="stylesheet" href="/thegong/public/css/style.css">
  </head>

  <body>
  <header class="site-header">
        <nav class="navbar-inverse" id="main-nav">
        <div class="container-fluid">
            <!-- Logo -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNavBar">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a href="/thegong/" class="navbar-brand"><span class="site-logo">The Gong</span></a>
            </div>

            <!-- Menu Items for admins -->
            <?php if(adminLoggedIn()) { ?>
            <div class="collapse navbar-collapse" id="mainNavBar">
                <ul class="nav navbar-nav navbar-right">
                        <li><a href="/thegong/news/create">Create New</a></li>
                        
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Articles<b class="caret"></b></a>

                        <ul class="dropdown-menu">
                          <li><a href="/thegong/news/unpublished">Unpublished Articles</a></li>
                          <li><a href="/thegong/news/published">Published Articles</a></li>
                        </ul></li>
                        
                         <li><a href="/thegong/auth/update_password">Update Password</a></li>
                        <li><a href="/thegong/auth/logout">Log Out</a></li>
                </ul>
            </div>
            <?php } ?>
         </div>
       </nav>
  </header>