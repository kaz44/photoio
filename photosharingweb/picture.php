<?php
include("includes/init.php");
include("includes/header.php");
$title = "picture";
$header_title = "photo";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $title; ?></title>

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
</head>

<!-- Source of all seed photos: (original: Kathy Zhang)
all photos taken during my vacation over spring break a week ago. -->

<?php

// If you click on a photo, we want to see the photo
if (isset($_GET['id'])) {
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $sql = "SELECT * FROM documents WHERE id = :id;";
  $params = array(
    ':id' => $id
  );
  $result = exec_sql_query($db, $sql, $params);
  if ($result) {
    $documents = $result->fetchAll();
    if ( $documents ) {
      $document = $documents[0];
    }
  }

    // If you want to delete a photo, it will be deleted from database and uploads folder
  if (isset($_POST['delete'])) {
    $sql = "DELETE FROM documents WHERE id = $id";
    $result = exec_sql_query($db, $sql,array());
    unlink("uploads/documents/" . htmlspecialchars($document['id']) . '.' . htmlspecialchars($document['file_ext']));
  }
  // If you want to delete a tag it will be deleted
  if (isset($_POST['delete_tag_submit'])) {
      $tag_choice = filter_input(INPUT_POST, 'tag_choice', FILTER_VALIDATE_INT);
      $sql = "DELETE FROM doc_tags WHERE (doc_id = :doc_id AND tag_id = :tag_id);";
        $params = array(
            ':doc_id' => $document['id'],
            ':tag_id' => $tag_choice
        );
      $result = exec_sql_query($db,$sql,$params);
  }
// If you want to add a tag
  if (isset($_POST['add_tag_submit'])) {
    $tagtype = filter_input(INPUT_POST, 'tag_type', FILTER_SANITIZE_STRING);
    $tagchoice = filter_input(INPUT_POST, 'tag_choice', FILTER_VALIDATE_INT);
    $newtag = filter_input(INPUT_POST, 'tag_input', FILTER_SANITIZE_STRING);
    // Getting all tag names for unique verification
    $sql = "SELECT tags.name FROM tags;";
    $all_tagnames = exec_sql_query($db,$sql);

    // Adding an existing tag
    if($tagtype == "Existing"){
        $sql = "SELECT doc_tags.tag_id FROM doc_tags WHERE (doc_id = :doc_id AND tag_id = :tag_id);"; //getting all tag ids associated with this picture
        $params = array(
            ':doc_id' => $document['id'],
            ':tag_id' => $tagchoice
        );
        $result = exec_sql_query($db,$sql,$params)->fetchAll();
        // if tagid aka the existing tag you're adding in, truly is unique, we will put that in the joint table database and add that new tag
        if (count($result)==0){
            $sql = "INSERT INTO doc_tags (doc_id, tag_id) VALUES (:doc_id, :tag_id);";
            $params = array(
                ':doc_id' => $document['id'],
                ':tag_id' => $tagchoice
            );
            $result = exec_sql_query($db,$sql,$params);
        }
    }
    // Adding a new tag
    elseif($tagtype == "New"){
      // Seeing the names for all the tagids
        $sql = "SELECT tags.id FROM tags WHERE (tags.name = :newtag);";
        $params = array(
            ':newtag' => $newtag
        );
        $result = exec_sql_query($db,$sql,$params)->fetchAll();

        // Seeing all the tagids for tags of this specific photo
        $sql = "SELECT doc_tags.tag_id FROM doc_tags WHERE (doc_id = :doc_id AND tag_id = :tag_id);"; //select the tag ids of tags on the existing photo
        $params = array(
            ':doc_id' => $document['id'],
            ':tag_id' => $tagchoice
        );
        $result1 = exec_sql_query($db,$sql,$params)->fetchAll();

        // Seeing if the new tag truly is a new unique tag
        if((count($result)==0) && (count($result1)== 0)){
          // adding new tag into the tags database
            $sql = "INSERT INTO tags (name) VALUES (:newname);";
            $params = array(
                ':newname' => $newtag
            );
            $result = exec_sql_query($db,$sql,$params);

          // adding new tag-photo relationship into the doc_tags database
            $sql = "INSERT INTO doc_tags (doc_id, tag_id) VALUES (:doc_id, :tag_id);";
            $params = array(
                ':doc_id' => $document['id'],
                ':tag_id' => $db->lastInsertId('id')
            );
            $result = exec_sql_query($db,$sql,$params);
        }
    }
  }
}

?>

<body>

  <div id="content-wrap">
    <!-- clicking on a photo -->
    <?php if ( isset($document) ) {?>
      <!-- displaying name of photo -->
      <h2><?php echo htmlspecialchars($document['file_name']) ?>  </h2>
      <!-- displaying desc -->
      <h3><?php echo $document['description']; ?></h3>
      <!-- displaying the image -->
      <figure>
        <img class = "bigpic pic" src="uploads/documents/<?php echo $document['id']; ?>.<?php echo $document["file_ext"] ?>" alt="<?php echo htmlspecialchars($document['id']); ?>"/>
      </figure>


      <?php
      // displaying all the tags associated to the photo
      $sql = "SELECT tags.id, tags.name FROM doc_tags INNER JOIN tags ON doc_tags.tag_id = tags.id WHERE doc_tags.doc_id = :image_id;";
      $params = array(
          ':image_id' => $document['id']
      );
      $tags = exec_sql_query($db,$sql,$params) -> fetchAll();
      echo '<div class=\'center\'>';
      foreach($tags as $tag){
        echo '<a class=\'biggerfont\' href="vacation.php?' . http_build_query(array('tag' => $tag['id'])) . '"> #' . $tag['name'] . '</a>' . PHP_EOL;
        echo ' ★ ';
        }
      echo '</div>';
      ?>

      </div>
<?php
  $sql = "SELECT * FROM tags";
  $all_tags = exec_sql_query($db, $sql)->fetchAll();
?>
<!-- Adding tags -->
<h2 class = "padright">Add tags</h2>
<div class="padleft">
    <form  id="add_tags" method="post" action="picture.php?<?php echo http_build_query( array( 'id' => $id ) )  ?>">
    <ul>
      <li>
        <label for="tag_type">Tag Type:</label>
        <select name="tag_type" >
            <option value="" selected disabled>Choose Type</option>
            <option value="Existing" >existing</option>
            <option value="New" >new</option>
        </select>
      </li>
      <li>
        <label for="tag_choice">Existing:</label>
        <select name="tag_choice" >
            <option value="" selected disabled>Choose Tag</option>
            <?php
            foreach($all_tags as $tag){
            ?>
              <option value=<?php echo $tag['id']?>><?php echo $tag['name']?></option>
            <?php }
            ?>
        </select>
       </li>
      <li>
        <label for="tag_input">New:</label>
        <input type="text" name="tag_input">
      </li>
      <li>
        <button name="add_tag_submit" type="submit">add</button>
      </li>
    </ul>
  </form>
</div>


<?php
// Can only delete tags if you uploaded the photo
if ( is_user_logged_in() && $document['user_id'] == $current_user['id']) { ?>
  <h2 class="padright">Delete Tags</h2>
  <div class="padright">
    <form class= "center" id="delete_tags" method="post" action="picture.php?<?php echo http_build_query( array( 'id' => $id ) )  ?>">
      <select name="tag_choice" >
        <option value="" selected disabled>Choose Tag</option>
          <?php
          foreach($tags as $tag){
          ?>
            <option value=<?php echo $tag['id']?>><?php echo $tag['name']?></option>
          <?php
          }?>
      </select>
      <button name="delete_tag_submit" type="submit">delete</button>
    </form>
  </div>

  <!-- Deleting the photo, again only if you uploaded it -->
  <div class="lesspad">
    <form id= "delete_button" class="center" action="picture.php?<?php echo http_build_query( array( 'id' => $id ) )  ?>" method="POST">
      <button name="delete" type="submit">Delete Photo</button>
    </form>
  </div>
<?php }?>

<?php } else { ?>
<p>Sorry, we couldn't find that picture.</p>
    <?php } ?>
</div>

</body>

</html>
