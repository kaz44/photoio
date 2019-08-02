<header>
  <h1 id="title" class="white"><?php

  // Putting the title for the header for a particular page
  if ((strpos(htmlspecialchars($_SERVER['PHP_SELF']), 'vacation.php')!==false) || (strpos(htmlspecialchars($_SERVER['PHP_SELF']), 'upload.php')!==false)){
    echo $header_title;
  }
  else{
  echo "photo";}
  ?></h1>

  <nav id="menu">
    <ul>
      <!-- Displaying each page's navigation in the header nav bar -->
      <?php
      foreach( $pages as $page ) {
        if($page[0] != "index.php"){
          $file = $page[0];
          $name = $page[1];

          echo '<li';
          if ($current_file == $file) {
            echo ' class=\'current_page\'';
          }
          echo '><a href="' . $file . '">' . $name . '</a></li>';
        }
    }

      // log out link
      if ( is_user_logged_in() ) {
        $loggingout = htmlspecialchars( $_SERVER['PHP_SELF'] ) . '?' . http_build_query( array( 'logout' => '' ) );
        echo '<li id="nav-last"><a href="' . $loggingout . '">Sign Out ' . htmlspecialchars($current_user['username']) . '</a></li>';
      }
      else{
        echo '<li id="nav-last"><a href="index.php">Sign In </a></li>';
      }
      ?>
    </ul>
  </nav>
</header>
            <!-- Source: https://www.example.com/image.png -->
            <a href="http://www.4usky.com/download/164919023.html">src</a>
