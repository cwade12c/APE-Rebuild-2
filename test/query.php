<?php
/**
 * file for testing queries
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_test
 * @subpackage     Blank
 */

require_once '../config.php';

// TODO: remove

if (!isPost()) {
    $queryResult = 'N/A';
} else {
    // get query text, build, execute query
    $queryStr = $_POST['queryText'];
    $getResult = empty($_POST['getResult']) ? false : true;

    try {
        $sql = executeQuery($queryStr);

        if ($getResult) {
            $results = getQueryResults($sql);
            // build result string
            if (!is_array($results)) {
                $queryResult = $results;
            } else {
                $queryResult = '';
                foreach ($results as $row) {
                    if (is_array($row)) {
                        $queryResult = $queryResult . implode(",", $row)
                            . '<br>';
                    } else {
                        $queryResult = $queryResult . $row . '<br>';
                    }
                }
            }
        } else {
            // get last inserted ID
            $lastID = getLastInsertedID();
            $queryResult = "<b>Last ID</b>: {$lastID}<br>";

            $sql = executeQuery("SELECT LAST_INSERT_ID()");
            $lastID = getQueryResult($sql);
            $queryResult = $queryResult
                . "<b>Last ID (query)</b>: {$lastID}<br>";
        }

    } catch (Exception $e) {
        $queryResult = 'Exception(\"' . get_class($e) . '\")<br>'
            . $e->getMessage() . '<br>'
            . 'Trace<br>' . $e->getTraceAsString();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Query</title>
</head>
<body>

<form method="post">
    <!-- query input -->
    <h5>Query</h5>
    <textarea name="queryText" rows="20" cols="75"></textarea>
    <br/>
    <input type="submit" value="execute">
    <br/>

    <!-- settings -->
    <h5>Get Results?</h5>
    <input type="checkbox" name="getResult" value="true" checked/>
    <br/>

    <!-- query result -->
    <h5>Result</h5>
    <?php
    echo "<p>$queryResult</p><br/>"
    ?>

</form>

</body>
</html>
