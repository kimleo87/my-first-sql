<?php
header('Content-Type: text/html; charset=UTF-8');

// require functions file
require_once('functions.php');

// If form is posted, get data and add person to db.
if (isset($_POST["name"]) && isset($_POST["lastname"]) && isset($_POST["age"]) && isset($_POST["city"]) && isset($_POST["street"]) ) {
    $name = htmlspecialchars($_POST["name"]);
    $lastname = htmlspecialchars($_POST["lastname"]);
    $age = htmlspecialchars($_POST["age"]);
    $city = htmlspecialchars($_POST["city"]);
    $street = htmlspecialchars($_POST["street"]);
    $bio = "";

    //bio
    if (isset($_POST["bio"])){
        $bio = htmlspecialchars($_POST["bio"]);
    }

    if (isset($_FILES["fileToUpload"]["name"])) {
        // validate and prep image.
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $img_name = basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    

    $sql = "INSERT INTO profiles (name, lastname, age, city, street, img, bio)
    VALUES ('".$name."', '".$lastname."', ".$age.", '".$city."', '".$street."', '".$img_name."', '".$bio."')";

    if ($link->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
}

$people = array();

$where = "";
if (isset($_GET["search"])) {
    $where = "WHERE name LIKE '%" . $_GET["search"] . "%' OR lastname LIKE '%" . $_GET["search"] . "%'";
}

// get data from db
$sql = "SELECT id, name, lastname, age, city, street, img, bio FROM profiles " . $where;
$result = $link->query($sql);
if (!isset($result)) {
    echo "Error: " . $sql . "<br>" . $link->error;
}

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $person = array("id"=>$row["id"], "name"=>$row["name"], "lastname"=>$row["lastname"], "age"=>$row["age"], "city"=>$row["city"], "street"=>$row["street"], "img"=>$row["img"], "bio"=>$row["bio"]);
        $people[$row["id"]] = $person;
    }
} else {
    echo "0 results";
}

$link->close();

 
?>
<!doctype html>
<html>
<head>
    <title>profil</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <fieldset>
        <legend>add person</legend>
        <div class="formdiv">
            <label for="name">first name:</label>
            <input type="text" name="name"/><br/>
            <label>last name:</label>
            <input type="text" name="lastname"/><br/>
            <label>age:</label>
            <input type="number" name="age"/><br/>
            <label>bio:</label>
            <textarea rows="4" cols="59" name="bio"></textarea>
        </div>
        <div class="formdiv">
            <label>city:</label>
            <input type="text" name="city"/><br/>
            <label>street:</label>
            <input type="text" name="street"/><br/>
            <label>profile image:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            
        </div>
        <input class="button" type="submit" value="Add person"/>
        </fieldset>
    </form>
    
    <form action="index.php" method="GET">
        <h2>Search</h2>
        <input type="text" name="search"/>
        <input class="button" type="submit" value="Search"/>
    </form>

    <?php
    // Generate list of people from people array.
    foreach ($people as $person) {
     
        echo '<div class="bio"><header>';
        echo '<h1>' . ((true) ? $person["name"]." ".$person["lastname"] : "Name") . '</h1>';
        echo '</header><div class="container"><div class="left">';
        echo '<p>' . ((true) ? $person["name"] : "Name") . '</p>';
        echo '<p>' . ((true) ? $person["lastname"] : "Lastname") . '</p>';
        echo '<p>' . ((true) ? $person["age"] : "Age") . '</p>';
        echo '<p>' . ((true) ? $person["city"] : "City") . '</p>';
        echo '<p>' . ((true) ? $person["street"] : "Street") . '</p>';
        echo '<p>' . ((true) ? $person["bio"] : "bio") . '</p>';
        echo '</div><div class="right"><img src="img/'. ((strlen($person["img"]) > 0) ? $person["img"] : "default.png") .'"/></div></div></div>';
    }
    ?>
</body>

</html>
