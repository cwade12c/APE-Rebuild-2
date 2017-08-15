<?php
/**
 * General query functions, for use by other query functions
 * Not intended for outside use
 *
 * @author        Mathew McCain
 * @author        Curran Higgins
 * @category      APE
 * @package       APE_includes
 * @subpackage    Database
 */

/**
 * general query method
 * prepares given query string, executes
 *
 * @param $query        query string
 * @param $params       set of parameters for prepared statement
 *                      must be in format of
 *                      [ ['name', value], ... ]
 *                      each subarray can contain a 3rd index for the datatype
 *                      example: ['name', value, PDO::PARAM_STR]
 *
 * @return PDOStatement, result of executing query
 */
function executeQuery(string $query, array $params = array())
{
    global $db;

    $sql = $db->prepare($query);

    // bind parameters
    foreach ($params as $arr) {
        if (count($arr) == 2) {
            // just bind
            $sql->bindParam($arr[0], $arr[1]);
        } elseif (count($arr) == 3) {
            // bind w/ given data type
            $sql->bindParam($arr[0], $arr[1], $arr[2]);
        }
    }

    try {
        $sql->execute();

        return $sql;
    } catch (PDOException $error) {
        if (DEBUG) {
            echo "<div class=\"debug\">";
            print_r($sql->errorInfo());
            $error->getMessage();
            echo "</div>";
        }
    }
}

/**
 * gets single column of row from query result
 *
 * @param $sql          pdo statement to use
 * @param $index        column number to grab (default 0)
 *
 * @return mixed        single value of column
 */
function getQueryResult(PDOStatement $sql, int $index = 0)
{
    try {
        $result = $sql->fetchColumn($index);

        return $result;
    } catch (PDOException $error) {
        if (DEBUG) {
            print_r($sql->errorInfo());
            die($error->getMessage());
        }
    }
}

/**
 * get single row from query result
 *
 * @param PDOStatement $sql
 *
 * @return mixed        single row as associative array
 */
function getQueryResultRow(PDOStatement $sql)
{
    try {
        $results = $sql->fetch(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $error) {
        if (DEBUG) {
            print_r($sql->errorInfo());
            die($error->getMessage());
        }
    }
}

/**
 * gets all results from a query
 *
 * @param $sql      pdo statement to use
 *
 * @return mixed    array with each row as an associative array
 */
function getQueryResults(PDOStatement $sql)
{
    try {
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $error) {
        if (DEBUG) {
            print_r($sql->errorInfo());
            die($error->getMessage());
        }
    }
}

// TODO: check for string size / value type for given table/column

/**
 * Converts a PHP DateTime object to the parameter string for a query
 *
 * @param string   $key      key for the parameter
 * @param DateTime $datetime datetime object to convert
 *
 * @return array
 */
function buildDateTimeStrParam(string $key, DateTime $datetime)
{
    $param = array($key, $datetime->format(DATETIME_FORMAT),
                   PDO::PARAM_STR);
    return $param;
}

/**
 * Convert string representation of mysql datetime to php datetime
 *
 * @param string $value
 *
 * @return mixed
 */
function buildDateTimeFromQuery(string $value)
{
    $datetime = DateTime::date_create_from_format(DATETIME_FORMAT, $value);
    return $datetime;
}