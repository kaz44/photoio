<?php
// DO NOT REMOVE!
include("includes/init.php");
// DO NOT REMOVE!
$title = "Photos";

// Getting the header title to be whatever the tag is if you click on a tag to see the photos for it
if (isset($_GET['tag'])){
    $the_tag = $_GET['tag'];
    $sql = "SELECT tags.name FROM tags WHERE tags.id = :tagid;";
    $params = array(
        ':tagid'=>$the_tag['name']
    );
    $all_tagnames = exec_sql_query($db,$sql,$params)->fetchAll();
    $header_title = '#' . $all_tagnames[0][0];
}
else{
// If no tag clicked, it's just the title vacation
    $header_title = "vacation";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $title; ?></title>

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
</head>

<body>
  <!-- Source of all seed photos: (original: Kathy Zhang)
all photos taken during my vacation over spring break a week ago. -->

  <?php
  include("includes/header.php");
  ?>
  <?php
  // Showing all the tags and links
  $sql = "SELECT * FROM tags";
  $all_tags = exec_sql_query($db, $sql)->fetchAll();
  echo '<div class=\'center padtop\'>';
  echo '<a class=\'biggerfont\' href=vacation.php> all photos </a>' . PHP_EOL;
  echo '  ★  ';
  foreach($all_tags as $tag){
    echo '<a class=\'biggerfont\' href="vacation.php?' . http_build_query(array('tag' => $tag['id'])) . '"> #' . $tag['name'] . '</a>' . PHP_EOL;
    echo '  ★  ';
  }
  echo '</div>';

// If a tag is clicked, we want all the photos for that tag
  if (isset($_GET['tag'])){
    $the_tag = $_GET['tag'];
    $header_title = '#' . $the_tag['name'];
    $sql = "SELECT documents.id, documents.file_ext FROM documents INNER JOIN doc_tags ON doc_tags.doc_id = documents.id WHERE doc_tags.tag_id = :tag_id;";
    $params = array(
      ':tag_id' => $the_tag['id']
    );
    $records = exec_sql_query($db,$sql,$params) -> fetchAll();
  }else{
// Else, we just get all the photos in general
    $records = exec_sql_query(
      $db,
      "SELECT * FROM documents;",
      array()
      )->fetchAll();
    }
  if (count($records) > 0) {
    foreach($records as $record){
    echo '<a href="picture.php?' . http_build_query( array( 'id' => $record['id'] ) ) . '"><img class =\'squarepic\' src="uploads/documents/' . $record["id"] . "." . $record["file_ext"] . '"alt="' . htmlspecialchars($record['id']) . '"/></a>' . PHP_EOL;
    }
  } else {
    echo '<p class = \'center\'><strong>Nothing uploaded yet. Try uploading a picture!</strong></p>';
    }
        ?>




</body>
</html>
