<?php
require("../private/config.php");
require("functions.php");

function require_multi($files) {
    $files = func_get_args();
    foreach($files as $file)
        require_once($file);
}

function connectDB($user, $pass, $db) {
    try {
        return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
    } catch(PDOException $ex) {
        return $ex;
    }

}


if (isset($_POST['author']) || isset($_POST['title']) || isset($_POST['date'])) {
	search();
}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Browse</title>
	<?php headIncludes(); ?>
  </head>
  <body>
    <?php navigation(); ?>
    <div id = "wrap">
    <div class="container">
    
    <div class="content">
      <div class="page-header">
        <h1>Browse <small>Discover other MineWriter books!</small></h1>
      </div>
      <h2 class="big">Search</h2>
      <form method="post" class="form-search" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="author" class="input-medium search-query" placeholder="Author">
        <input type="text" name="title" class="input-medium search-query" placeholder="Title">
        <input type="text" name="date" class="input-medium search-query" placeholder="Date">
        <button type="submit" class="btn">Search</button>
      </form>
      <div class="results">
        <?php
		function search() {
			global $dbUser, $dbPass, $dbName;
			$db = connectDB($dbUser, $dbPass, $dbName);
			if ($db instanceof PDOException) {
				die ($db->getMessage());
			}

			if(isset($_POST['author']) && !is_null($_POST['author'])) {
				$query = "SELECT * FROM `Books` WHERE `Author` LIKE :author LIMIT 10";
				$stmt = $db->prepare($query);
				$stmt->bindValue(':author', "%".$_POST['author']."%");
			} else if(isset($_POST['title']) && !is_null($_POST['title'])) {
				$query = "SELECT * FROM `Books` WHERE `Title` LIKE :title LIMIT 10";
				$stmt = $db->prepare($query);
				$stmt->bindValue(':title', "%".$_POST['title']."%");
			} else if(isset($_POST['date']) && !is_null($_POST['date'])) {
				// This needs to be smarter, theres no way people will come up with an EXACT date for a book's creation
				$query = "SELECT * FROM `Books` WHERE `Date` LIKE :date LIMIT 10";
				$stmt = $db->prepare($query);
				$stmt->bindValue(':date', "%".$_POST['date']."%");
			}
			$stmt->execute();
			$rows = $stmt->fetchAll();
			
			if (!$stmt->rowCount() == 0) {
            while ($results = $stmt->fetchAll()) {
               $title = $row['Title'];			
               $author = $row['Author'];
               $genre = $row['genre'];
               $date = $row['Date'];
               $downloads = $row['downloads'];
            }

        } else {
            echo 'Nothing found';
        }		
		?>
			<table class="table table-striped">
            	<tr style="font-weight: bold;">
        			<td>Title</td>
        			<td>Author</td>
                    <td>Genre</td>
                    <td>Date created</td>
                    <td>Downloads</td>
     		 	</tr>
			<tr onclick="document.location='read.php?id=<?php echo $row['ID'] ?>';">
            	<td><?php echo $title; ?></td>
           	 	<td><?php echo $author; ?></td>
           	 	<td><?php echo $genre; ?></td>
           	 	<td><?php echo $date; ?></td>
            	<td><?php echo $downloads; ?></td>
            </tr>    
		<?php
			}
		?>
             </table>
      </div>
    </div>
  </div>
  <?php footer(); ?>
</div>
</body>
</html>
