<?php
    $photo_id = 0;

    if(!isset($_GET['id'])){
        header("Location: EPS-Photos.php"); 
    }
    else{
        $photo_id = $_GET['id'];

        include "localHostConnect.php";
        
        //load Photos from DB
        $sql = "SELECT * FROM photos WHERE photos_id= $photo_id";
        $photo_caption_result = $conn->query($sql);
    }

   
?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-photoCaption.css">
	<title>PHOTOS</title>
</head>
<body>
        <div id="photo">
        <?php 
            foreach($photo_caption_result as $photo_caption){
        ?>
            <img src="<?php echo $photo_caption['photos_link'];?>" />
            <h2 class="caption"><?php echo $photo_caption['photos_caption'];?></h2>
        </div>
        <?php
            }
        ?>
        
</body>
</html>