<?php
/**
 * General query functions, for use by other query functions
 * Not intended for outside use
 *
 * @author		Mathew McCain
 * @author      Curran Higgins
 * @category    APE
 * @package     APE_includes
 * @subpackage	Database
 */

require_once 'include.php';

/**
 * general query method
 * prepares given query string, executes
 *
 * @param $query, query string
 * @param $params, set of parameters for prepared statement
 *                 must be in format of
 *                  [ ['name', value], ... ]
 *                  each subarray can contain a 3rd index for the datatype
 *                      example: ['name', value, PDO::PARAM_STR]
 *
 * @return PDOStatement, result of executing query
 */
function executeQuery(string $query, array $params = array()) {
    global $db;

    $sql = $db->prepare($query);

    // bind parameters
    foreach($params as $arr) {
        if (count($arr) == 2) {
            // just bind
            $sql->bindParam($arr[0], $arr[1]);
        }else if (count($arr) == 3) {
            // bind w/ given data type
            $sql->bindParam($arr[0], $arr[1], $arr[2]);
        }
    }

    if (!$sql->execute()) {
        // TODO: figure out what exception to throw
        $errArr = $sql->errorInfo();
        die(sprintf("PDO Execute Failed(%d, %d): \"%s\"",
            $errArr[0], $errArr[1], $errArr[2]));
    }

    return $sql;
}

/**
 * general query method
 * gets single result from query
 *
 * @param $sql, pdo statement to use
 * @param $index, column number to grab (default 0)
 *
 * @return mixed
 */
function getQueryResult(PDOStatement $sql, int $index = 0) {
    $result = $sql->fetchColumn($index);
    return $result;
}

/**
 * general query method
 * gets all results from a query
 *
 * @param $sql, pdo statement to use
 *
 * @return mixed, array with each row as an associative array
 */
function getQueryResults(PDOStatement $sql) {
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

// check for string size / value type for given table/column