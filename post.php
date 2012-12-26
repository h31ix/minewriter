<?php 
require("functions.php"); 
require("../private/config.php");

function connectDB($user, $pass, $db) {
	try {	
		return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
	} catch(PDOException $ex) {
		die($user);
		return $ex;
	}
	
}

function hasHtml($str){
  if(strlen($str) != strlen(strip_tags($str))) {
      return true;
	}
  else {	 
  	return false;
  }
}


$author = $_POST["author"];
$title = $_POST["title"];
$license = $_POST["license"];
$content = $_POST["bookContent"];
$ip = $_SERVER['REMOTE_ADDR'];
$username = "anonymous";

if($author == null || $title == null || $license == null || $content == null) {?>
		<script type="text/javascript">
			window.alert("Please fill in all the fields!");
		</script>
	
}
if(hasHtml($author) || hasHtml($title) || hasHtml($content)) {
	?>
		<script type="text/javascript">
			window.alert("You are not allowed to use html on this form!");
			history.go(-1);
		</script>
	<?php
	return;
}

$db = connectDB($dbUser, $dbPass, $dbName);
if ($db instanceof PDOException) {
	die ($db->getMessage());
}
$title = preg_replace('/[^A-Za-z0-9 ]/', '', $title);
$author = preg_replace('/#[^a-z0-9_.-]#i/', '', str_replace(".", "", $author));
$content = str_replace("\r\n", "\n", $content);
$sql = "INSERT INTO Books VALUES(NULL,:author,:title,:content,:license,NOW(),:username,:ip,0)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':author', $author);
$stmt->bindParam(':title', $title);
$stmt->bindParam(':content', $content);
$stmt->bindParam(':license', $license);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':ip', $ip);
$stmt->execute();

/*$sql = "SHOW TABLES FROM $dbName";
$stmt = $db->prepare($sql);
$stmt->execute();
while($showtablerow = $stmt->fetch())
{
	echo $showtablerow[0]."<br />";
}*/

$id = $db->lastInsertId();
header("Location: http://minewriter.net/read.php?id=" . $id)
?>
