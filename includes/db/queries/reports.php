<?php
/**
 * Query functions for reports
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Get list of report IDs
 *
 * @return mixed
 */
function getReportIDsQuery()
{
    $query = "SELECT `id` FROM `reports`;";
    $sql   = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Get list of report IDs + Names
 *
 * @return mixed
 */
function getReportIDsNamesQuery()
{
    $query = "SELECT `id`, `name` FROM `reports`;";
    $sql   = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Get report ID by given name
 *
 * @param string $name
 *
 * @return mixed
 */
function getReportIDQuery(string $name)
{
    $query = "SELECT `id` FROM `reports` WHERE `name` = :name;";
    $sql   = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR)
        )
    );

    return getQueryResult($sql);
}

/**
 * Get list of rows for given report id
 *
 * @param int $id
 *
 * @return mixed
 */
function getReportRowsQuery(int $id)
{
    $query = "SELECT `type` FROM `report_rows` WHERE `id` = :id;";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT))
    );

    return getQueryResults($sql);
}

/**
 * Add new report
 *
 * @param string $name
 *
 * @return mixed
 */
function addNewReportQuery(string $name)
{
    $query = "INSERT INTO `reports` "
        . " (`name`)"
        . " VALUES (:name)";
    $sql   = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR))
    );

    // TODO: check for success ?
}

/**
 * Wipe rows for a report ID
 *
 * @param int $id
 */
function wipeReportRowsQuery(int $id)
{
    $query = "DELETE FROM `report_rows` "
        . " WHERE `id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT))
    );

    // TODO: check for success
}

/**
 * Add rows for given report ID
 *
 * @param int   $id
 * @param array $rows
 */
function addReportRowsQuery(int $id, array $rows)
{
    // build values string and params
    list($setStr, $params) = buildReportRowsValuesString($id, $rows);

    // build, execute query string
    $query = sprintf(
        "INSERT INTO `report_rows`(`id`, `type`) "
        . " VALUES %s", $setStr
    );
    $sql   = executeQuery($query, $params);

    // TODO: check for success?
}

/**
 * Builds the parameters and values string for the insert query
 * for function addReportRowsQuery()
 * Only intended for that function
 *
 * @param int   $id
 * @param array $rows
 *
 * @return array
 */
function buildReportRowsValuesString(int $id, array $rows)
{
    $params = array();
    $values = array();

    array_push($params, array(':id', $id, PDO::PARAM_INT));

    foreach ($rows as $i => $type) {
        // determine param name
        $val = sprintf(':row%d', $i);
        array_push($values, sprintf('(:id, %s)', $val));
        // add parameter
        array_push($params, array($val, $type, PDO::PARAM_INT));
    }

    // build values string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}