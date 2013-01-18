<?php
require("functions.php"); 
require("../private/config.php");

function connectDB($user, $pass, $db) {
	try {	
		return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
	} catch(PDOException $ex) {
		return $ex;
	}
	
}


$db = connectDB($dbUser, $dbPass, $dbName);
if ($db instanceof PDOException) {
	die ($db->getMessage());
}
$query = "SELECT * FROM `Stats` WHERE 1";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MineWriter Metrics</title>
	<?php headIncludes(); ?>
  </head>
  <body>
    <?php navigation(); ?>
    <div id = "wrap">
    <div class="container">   
    <div class="content">
    	<p>Crude stats page, this will be improved</p>
    	<p><?php echo("Book Count = " . $row['BookCount']);?></p>
    	<p><?php echo("Total Characters = " . $row['TotalChars']);?></p>
    	<p><?php echo("Average Characters = " . $row['AverageChars']);?></p>
    	<p><?php echo("Most Used Word = " . $row['MostUsedWord']);?></p>
    	<p><?php echo("Longest Book = " . $row['LongestBook']);?></p>			
        <?php divider(); ?>        
    </div>
    </div>
       <?php footer(); ?>    
    </div>
  </body>
</html>