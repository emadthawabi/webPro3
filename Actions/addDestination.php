
<?php
$continent = 0;
$country = 0;
$city =0;
$description = 0;

if (isset($_POST['continent']) and isset($_POST['country']) and isset($_POST['city']) and isset($_POST['description'])
    and !empty($_POST['continent']) and !empty($_POST['country']) and !empty($_POST['city']))
{
    $continent = $_POST['continent'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $description = $_POST['description'];

    try {
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
        $query = "INSERT INTO `destination` (`destid`, `continent`, `country`, `city`, `description`) VALUES (NULL, '$continent', '$country', '$city', '$description');";
        $db->query($query);
        $db->commit();

        $db->close();
        header("Location:../admin.php ");


    } catch (Exception $e) {

        echo 'Caught (:}{:) exception: ', $e->getMessage(), "\n";
    }






}





?>