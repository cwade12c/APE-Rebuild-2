<!DOCTYPE HTML>
<html>
<img src="../img/ewu_logo.png" alt="EWU Logo">
<?php include "admin_navbar.php";
include "creds.php" ?>
<head>
    <title>Locations</title>
</head>
<body>
<br>
<fieldset>
    <legend>Create Location</legend>
    <form method="post" action="create_location.php">
        Name:
        <input type="text" name="room" required="required"><br>
        Seats:
        <input type="number" name="seats" min="1" max="200" value="35"><br>
        <input type="submit" value="Create">
    </form>
</fieldset>

<fieldset>
    <legend>Delete Location</legend>
    <form method="post" action="delete_location.php">
        Location:
        <select name="location">
            <?php
            try {
                $db = new PDO(
                    "mysql:host=$server;dbname=$dbname", $user, $pass
                );
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = $db->prepare("select * from location");

                $sql->execute();

                $res = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($res as $val) {

                    ?>
                    <option value=<?php echo $val['id']; ?>><?php echo $val['name']
                            . " Seats: " . $val['seats']; ?></option>
                    <?php
                }
            } catch (Exception $e) {
                echo "SOMETHING WRONG" . "<br>" . $e->getMessage();
            }

            $db = null;
            ?>
        </select>
        <input type="submit" value="Delete">
    </form>


</body>
</html>