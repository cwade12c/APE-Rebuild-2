<?php

require_once('config.php');
enforceAuthentication();

if (isset($_POST["submit"])) {
    registerStudentsFromCsv();
}

function registerStudent($ewuId, $firstName, $lastName, $ewuEmail)
{
    if ( ! empty($firstName) && ! empty($lastName) && $ewuId && $ewuEmail) {
        $firstName = sanitize($firstName);
        $lastName  = sanitize($lastName);
        $ewuId     = sanitize($ewuId);
        $ewuEmail  = sanitize($ewuEmail);
        $type      = ACCOUNT_TYPE_STUDENT;

        $sql = executeQuery(
            "INSERT INTO accounts(id, type, f_name, l_name, email) values('$ewuId', '$type', '$firstName', '$lastName', '$ewuEmail');"
        );
    } else {
        studentRegistrationError();
    }
}

function registerStudentsFromCsv()
{
    $csv = fopen($_FILES['file']['tmp_name'], 'rb');
    while (($line = fgets($csv)) !== false) {
        $studentProperties = explode(",", $line);
        if (count($studentProperties) == 4) {
            registerStudent(
                $studentProperties[0], $studentProperties[1],
                $studentProperties[2], $studentProperties[3]
            );
        }
    }
}

function studentRegistrationError()
{
    echo "Unable to add the student to the APE system. Please contact support.";
    if (DEBUG) {
        die("Error: Cannot have any empty fields!");
    }
}

?>

<form method="POST" enctype="multipart/form-data">
    <label for="csv">CSV File:</label>
    <br/>
    <input type="file" name="file" accept=".csv"/>
    <br/>
    <br/>
    <input type="submit" name="submit" value="Register Class"/>
</form>