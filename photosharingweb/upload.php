<?php
include("includes/init.php");

$messages = array();
$header_title = "upload";

// Source: my own Lab8 work as well as the solution branch
// Source: some inspiration from Kyle Harms solution branch on Lab8

const MAX_FILE_SIZE = 1000000;

// users must be logged in to upload files
if ( isset($_POST["submit_upload"]) && is_user_logged_in() ) {

  // get the info about the uploaded files.
  $upload_info = $_FILES["upload_file"];
  $upload_desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

  if ( $upload_info['error'] == UPLOAD_ERR_OK ) {
    $upload_name = basename($upload_info["name"]);
    $upload_ext = strtolower( pathinfo($upload_name, PATHINFO_EXTENSION) );

    // Put it into the database
    $sql = "INSERT INTO documents (user_id, file_name, file_ext, description) VALUES (:user_id, :filename, :extension, :description)";
    $params = array(
      ':user_id' => $current_user['id'],
      ':filename' => $upload_name,
      ':extension' => $upload_ext,
      ':description' => $upload_desc,
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      // Put it into the uploads folder as well
      $file_id = $db->lastInsertId("id");
      $id_filename = 'uploads/documents/' . htmlspecialchars($file_id) . '.' . htmlspecialchars($upload_ext);

      if ( move_uploaded_file($upload_info["tmp_name"], htmlspecialchars($id_filename) ) ) {
      } else {
        array_push($messages, "Failed to upload file. ");
      }
    } else {
      array_push($messages, "Wrong file or entries. ");
    }
  } else {
    array_push($messages, "Failed to upload file. ");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
  <?php include("includes/header.php");?>

  <div id="content-wrap">

    <?php
    if ( is_user_logged_in() ) {
      foreach ($messages as $message) {
        echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
      }
      ?>
      <!-- Form for uploading files -->
      <form id="uploadFile" action="upload.php" method="post" enctype="multipart/form-data">
        <ul>
          <li>
            <!-- MAX_FILE_SIZE must precede the file input field -->
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
            <label for="upload_file">Upload File:</label>
            <input id="upload_file" type="file" name="upload_file">
          </li>
          <li>
            <label for="upload_desc">Description:</label>
            <textarea id="upload_desc" name="description"></textarea>
          </li>
          <li>
            <button name="submit_upload" type="submit">Upload File</button>
          </li>
        </ul>
      </form>
      <?php
    } else{
      ?>
      <p><strong>You need to sign in before you can upload any photos.</strong></p>

<!-- Login form displays if not logged in -->
      <form id="loginForm" action="
      <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?>" method="post">
        <ul>
          <li>
              <label for="username" >Username:</label>
              <input id="username" type="text" name="username" />
          </li>

          <li>
              <label for="password" >Password:</label>
              <input id="password" type="password" name="password" />
          </li>
          <li class ="padleft">
          <button name="login" type="submit">Sign In</button>
          </li>
        </ul>
      </form>
    <?php
    }?>

</body>

</html>
