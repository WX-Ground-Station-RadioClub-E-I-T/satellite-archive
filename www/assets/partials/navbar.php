<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a href="index.php">
      <img src="assets/images/logo.png" height="70" alt="" href="index.php">
    </a>

    <?php
    $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
    ?>

    <!-- Responsive button for tongle menu -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav mr-auto ">

        <li class="nav-item ml-3">
          <div class="menu_top_main text-left">
            <a class="nav-link <?php if($uri_parts[0] == '/index.php' || $uri_parts[0] == '/'){ echo active; } ?> pb-0 pt-3" href="index.php"><?php echo HOME; ?>
              <span class="sr-only">(current)</span>
            </a>
          </div>
          <div class="menu_top_second">
            <?php echo HOME_SUB; ?>
          </div>
        </li>

        <li class="nav-item ml-3">
          <div class="menu_top_main text-left">
            <a class="nav-link <?php if($uri_parts[0] == '/about.php'){ echo active; } ?> pb-0 pt-3" href="about.php"><?php echo ABOUT; ?></a>
          </div>
          <div class="menu_top_second">
            <?php echo ABOUT_SUB; ?>
          </div>
        </li>

        <li class="nav-item ml-3">
          <div class="menu_top_main text-left">
            <a class="nav-link pb-0 pt-3" href="http://cesar.esa.int/index.php?Section=Contact&Origin=Archive_Viewer"><?php echo CONTACT; ?></a>
          </div>
          <div class="menu_top_second">
            <?php echo CONTACT_SUB; ?>
          </div>
        </li>
    </ul>

      <form class="form-inline my-2 my-lg-0 pt-3 float-right" action="simplesearch.php" method="GET">
        <i class="fas fa-search" style="color:#00a9e0"></i>
        <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="<?php echo SEARCH; ?>" name="query" id="inputQuery" <?php if($_GET[query] != ""){echo "value =\"" . $_GET[query] . "\"";} ?>>
        <small class="form-text text-muted">
          <?php echo SEARCH_SUB; ?>
        </small>
      </form>

    </div>
  </div>
</nav>
