<?php

function config_php_errors()
{
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}
config_php_errors();

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename)
{
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (file_exists($init_sql_filename)) {
      $db_init_sql = file_get_contents($init_sql_filename);
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    } else {
      unlink($db_filename);
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

function exec_sql_query($db, $sql, $params = array())
{
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
}

// Navigation
$pages = [
  ['index.php', 'Home'],
  ['vacation.php', 'Photos','VACATION'],
  ['upload.php', 'Upload', 'UPLOAD']
];
$current_file = basename($_SERVER['PHP_SELF']);

function print_title() {
  global $pages, $current_file;
  $title = '';

  // Find the current page
  foreach( $pages as $page ) {
    $file = $page[0];
    $name = $page[2];

    if ($current_file == $file) {
      $title = $name;
      break;
    }
  }
  // $title = $title . "INFO 2300";
  echo htmlspecialchars($title);
}

// open connection to database
$db = open_or_init_sqlite_db("secure/site.sqlite", "secure/init.sql");


/* Inspiration source for login logout functionality: Kyle Harms
Source: Kyle Harms
*/

define('COOKIE_TIME', 60*60*1);

$messages_s = array();

function is_user_logged_in() {
  global $current_user;
  if($current_user != NULL){
    return true;
  }
  else{
    return false;
  }
}
function get_user($user_id) {
  global $db;

  $sql = "SELECT * FROM users WHERE id = :newuser_id;";
  $params = array(
    ':newuser_id' => $user_id
  );
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if (count($records)!=0) {
    return $records[0];
  }
  return NULL;
}

function log_in($username, $password) {
  global $db;
  global $current_user;
  global $messages_s;

  if (isset($password) && isset($username) ) {
    // see if username even exists
    $sql = "SELECT * FROM users WHERE username = :newusername;";
    $params = array(
      ':newusername' => $username
    );
    $users = exec_sql_query($db, $sql, $params)->fetchAll();
    //notnull
    if ($users) {
      $account = $users[0];

      if (password_verify($password, $account['password'])) {
        // make new session
        $session = session_create_id();

        $sql = "INSERT INTO sessions (user_id, session) VALUES (:user_id, :session);";
        $params = array(
          ':user_id' => $account['id'],
          ':session' => $session
        );
        $result = exec_sql_query($db, $sql, $params);
        if ($result) {
          //setting the cookie time to refresh
          setcookie("session", $session, time() + COOKIE_TIME);

          $current_user = $account;
          return $current_user;
        }else {
          array_push($messages_s, "No login session.");
        }
      }else {
        array_push($messages_s, "Password unverified.");
      }
    }else {
      array_push($messages_s, "Invalid username.");
    }
  }else {
    array_push($messages_s, "Log in failed.");
  }
  $current_user = NULL;
  return NULL;
}

function get_session($session) {
  global $db;

  if (isset($session)) {
    $sql = "SELECT * FROM sessions WHERE session = :newsession;";
    $params = array(
      ':newsession' => $session
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      return $records[0];
    }
  }
  return NULL;
}

function log_out() {
  global $current_user;

  // force session to expire with cookie time
  setcookie('session', '', time() - COOKIE_TIME);
  $current_user = NULL;
}

function session_login() {
  global $db;
  global $current_user;

  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];

    $session_record = get_session($session);

    if ( isset($session_record) ) {
      $current_user = get_user($session_record['user_id']);

      // renewing the cookie for more time
      setcookie("session", $session, time() + COOKIE_TIME);

      return $current_user;
    }
  }
  $current_user = NULL;
  return NULL;
}

// logging in the user
if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
  $username = ( $_POST['username'] );
  $password = ( $_POST['password'] );

  log_in($username, $password);
} else {
  // check if cookie is still ok so the user is still logged in
  session_login();
}

if ( (isset($_GET['logout']) || isset($_POST['logout'])) && isset($current_user)) {
  log_out();
}

?>
