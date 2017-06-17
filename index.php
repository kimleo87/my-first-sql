<?php
// Hardcoded array of profiles, used for early stage dev.
$people = array(
    "kim"=> array("name"=>"Kim", "lastname"=> "Christensen", "age"=> 30, "city"=> "Ballerup", "street"=> "Ballerup Byvej", "img"=> "kim1.jpg"),
    "karsten"=> array("name"=>"karsten", "lastname"=> "Christensen", "age"=> 32, "city"=> "Ballerup", "street"=> "Ballerup Byvej", "img"=> "karsten.png"),
    "knud"=> array("name"=>"knud", "lastname"=> "Christensen", "age"=> 20, "city"=> "Ballerup", "street"=> "Ballerup Byvej", "img"=> "knud.png"),
);

// If form is posted, get data and add person to people array.
if (isset($_GET["name"]) && isset($_GET["lastname"]) && isset($_GET["age"]) && isset($_GET["city"]) && isset($_GET["street"]) ) {
    $name = htmlspecialchars($_GET["name"]);
    $lastname = htmlspecialchars($_GET["lastname"]);
    $age = htmlspecialchars($_GET["age"]);
    $city = htmlspecialchars($_GET["city"]);
    $street = htmlspecialchars($_GET["street"]);
    $people[$name] = array("name"=>$name, "lastname"=> $lastname, "age"=> $age, "city"=> $city, "street"=> $street);
}
 
?>
<!doctype html>
<html>
<head>
    <title>profil</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <form action="index.php">
        <fieldset>
        <legend>add person</legend>
        <div class="formdiv">
            <label for="name">first name:</label>
            <input type="text" name="name"/><br/>
            <label>last name:</label>
            <input type="text" name="lastname"/><br/>
            <label>age:</label>
            <input type="number" name="age"/>
        </div>
        <div class="formdiv">
            <label>city:</label>
            <input type="text" name="city"/><br/>
            <label>street:</label>
            <input type="text" name="street"/><br/>
            <input class="button" type="submit" value="add person"/>
        </div>
        </fieldset>
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
        echo '</div><div class="right"><img src="img/'. ((isset($person["img"])) ? $person["img"] : "default.png") .'"/></div></div></div>';
    }
    ?>
</body>

</html>
