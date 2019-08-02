<?php
// DO NOT REMOVE!
include("includes/init.php");
// DO NOT REMOVE!
$title = "Home";
$is_there_message = false;
$going_page ="vacation.php";
$the_message = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $title; ?></title>

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
</head>


<body id = "bg">
<!-- Source: (original: Kathy Zhang)
Picture of me and my friend in California -->
  <div class="center morepad">
    <h1 class="white">LOG IN </h1>
    <ul>
  <?php
  foreach ($session_messages as $message) {
    echo "<p class =\"white\" ><strong>" . htmlspecialchars($message) . "</strong></li>\n";

    $is_there_message = true;
    $the_message = $message;
  }
  ?>
</ul>
</div>

<!-- Just a simple login form -->
<form id="loginForm" action="<?php
// Determine the page action form
if ($is_there_message = false){
  $going_page = "vacation.php";}
echo $going_page;
?>" method="post">
  <div class="padmoreleft">
    <ul>
      <li>
        <label for="username" class="white">Username:</label>
        <input id="username" type="text" name="username" />
      </li>

      <li>
        <label for="password" class="white">Password:</label>
        <input id="password" type="password" name="password" />
      </li>

      <li>
      <button name="login" type="submit">Sign In or Continue as Guest</button>
      </li>
    </ul>
  </div>
</form>

</body>
</html>
