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

// TODO: change try/catch to just allow exceptions to be thrown
/// once error checking is implemented in the db functions

/**
 * Starts transaction on DB PDO object
 * Only for internal use
 *
 * @return bool         If succeeds
 *
 * @throws PDOException See PDO:beginTransaction()
 */
function startTransactionQuery()
{
    global $db;
    return $db->beginTransaction();
}

/**
 * Rollback a DB PDO transaction
 * Only for internal use
 *
 * @return bool         If succeeds
 *
 * @throws PDOException See PDO:beginTransaction()
 */
function rollBackQuery()
{
    global $db;
    return $db->rollBack();
}

/**
 * Commits transaction on DB PDO object
 * Only for internal use
 *
 * @return bool         If succeeds
 *
 * @throws PDOException See PDO:commit()
 */
function commitQuery()
{
    global $db;
    return $db->commit();
}

/**
 * Check if DB PDO object in a transaction
 * Only for internal use
 *
 * @return bool If in Transaction
 */
function inTransactionQuery()
{
    global $db;
    return $db->inTransaction();
}

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
        if ($sql->execute()) {
            return $sql;
        }
        throw new RuntimeException("Query execute failed");
    } catch (Exception $error) {
        if (DEBUG) {
            // TODO make debug error tag stand out more
            echo "<div class=\"debug\">";
            print_r($sql->errorInfo());
            $error->getMessage();
            echo "</div>";
        }
        error_log("query execute exception, {$error}");
        throw new RuntimeException(
            "Query execute exception", ERROR_CODE_DB, $error
        );
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
    } catch (Exception $error) {
        if (DEBUG) {
            // TODO make debug error tag stand out more
            print_r($sql->errorInfo());
            die($error->getMessage());
        }
        error_log("get query result exception, {$error}");
        throw new RuntimeException(
            "get query result exception", ERROR_CODE_DB, $error
        );
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
        if ($results = $sql->fetch(PDO::FETCH_ASSOC)) {
            return $results;
        }
        throw new RuntimeException("False return from fetch row");
    } catch (Exception $error) {
        if (DEBUG) {
            // TODO make debug error tag stand out more
            print_r($sql->errorInfo());
            die($error->getMessage());
        }
        error_log("get query result row exception, {$error}");
        throw new RuntimeException(
            "get query result row exception", ERROR_CODE_DB, $error
        );
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
        if ($results = $sql->fetchAll(PDO::FETCH_ASSOC)) {
            return $results;
        }
        throw new RuntimeException("False return from fetch rows");
    } catch (Exception $error) {
        if (DEBUG) {
            // TODO make debug error tag stand out more
            print_r($sql->errorInfo());
            die($error->getMessage());
        }
        error_log("get query result rows exception, {$error}");
        throw new RuntimeException(
            "get query result rows exception", ERROR_CODE_DB, $error
        );
    }
}

/**
 * Grab the ID of the last inserted row.
 * Just a wrapper for PDO::lastInsertId()
 * Appears to only work correctly w/ tables that use AUTO_INCREMENT
 * for the primary key.
 * Return is the value parsed from the return string.
 *
 * @param string $name
 *
 * @return int
 */
function getLastInsertedID(string $name = null)
{
    try {
        global $db;
        $lastIDStr = $db->lastInsertId($name);
        $lastID = intval($lastIDStr);
        return $lastID;
    } catch (Exception $error) {
        if (DEBUG) {
            // TODO make debug error tag stand out more
            print_r($db->errorInfo());
            die($error->getMessage());
        }
        error_log("get last inserted id exception, {$error}");
        throw new RuntimeException(
            "get last inserted id exception", ERROR_CODE_DB, $error
        );
    }
}

/**
 * Query to get details about a table attribute
 * Intended to be used by the wrapping function getTableAttributeDetails()
 *
 * @param string $tableName     name of table
 * @param string $attributeName name of attribute
 *
 * @return mixed                associative array of result
 *                              'DATA_TYPE' => mysql datatype as string
 *                              'CHARACTER_MAXIMUM_LENGTH' => max string length
 *                                  if applicable, else null
 *                              'NUMERIC_PRECISION' => number precision
 *                                  if applicable, else null
 *                              if no row found, false is returned
 */
function getTableAttributeDetailsQuery(string $tableName, string $attributeName)
{
    // additional details about query:
    // https://dev.mysql.com/doc/refman/5.7/en/columns-table.html
    $query
        = "SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION "
        . "FROM INFORMATION_SCHEMA.COLUMNS "
        . "WHERE table_schema = :schemaName "
        . " && table_name = :tableName "
        . " && COLUMN_NAME = :attributeName ";
    $sql = executeQuery(
        $query, array(
            array(':schemaName', DB, PDO::PARAM_STR),
            array(':tableName', $tableName, PDO::PARAM_STR),
            array(':attributeName', $attributeName, PDO::PARAM_STR)
        )
    );

    return getQueryResultRow($sql);
}

/*
 * SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = 'accounts' && table_schema = 'test_new_ape_database';
 */


/**
 * Converts a PHP DateTime object to the parameter string for a query
 *
 * @param string   $key      key for the parameter
 * @param DateTime $datetime datetime object to convert
 *
 * @return array    array in format of ('key', value, param_type),
 *                  used as param for prepared statement
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
    $datetime = DateTime::createFromFormat(DATETIME_FORMAT, $value);

    return $datetime;
}