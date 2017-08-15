<?php
/**
 * file for testing queries
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_test
 * @subpackage     Blank
 */
require_once 'include.php';

if ( ! isPost()) {
    $queryResult = 'N/A';
} else {
    // get query text, build, execute query
    $queryStr = $_POST['queryText'];
    try {
        $sql     = executeQuery($queryStr);
        $results = getQueryResults($sql);

        // build result string
        if ( ! is_array($results)) {
            $queryResult = $results;
        } else {
            $queryResult = '';
            foreach ($results as $row) {
                if (is_array($row)) {
                    $queryResult = $queryResult . implode(",", $row) . '<br>';
                } else {
                    $queryResult = $queryResult . $row . '<br>';
                }
            }
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

    <!-- query result -->
    <h5>Result</h5>
    <?php
    echo "<p>$queryResult</p><br/>"
    ?>

</form>

</body>
</html>
