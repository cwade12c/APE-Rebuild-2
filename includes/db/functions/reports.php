<?php
/**
 * Functions for reports in database
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Get all report IDs
 *
 * @return mixed
 */
function getReportIDs()
{
    // TODO: check for errors from query
    return getReportIDsQuery();
}

/**
 * Get all report IDs and names
 *
 * @return mixed
 */
function getReportSet()
{
    // TODO: check for errors from query
    // TODO: format array to correct format?
    return getReportIDsNamesQuery();
}

/**
 * Get rows for given report id
 *
 * @param int $id
 *
 * @return mixed
 */
function getReportRows(int $id)
{
    // TODO: check for errors from query
    return getReportRowsQuery($id);
}

/**
 * Add new report w/ given name and rows
 *
 * @param string $name
 * @param array  $rows
 */
function addNewReport(string $name, array $rows = array())
{
    // TODO: check that all entries in rows are valid integers
    // TODO: check that name is valid (sanitized) (length)
    // TODO: check for errors from queries
    // TODO: check if report exists ?
    // TODO: at least 3 queries, build transaction ?

    // add new report
    addNewReportQuery($name);
    $id = getReportIDQuery($name);
    // add rows
    setReportRows($id, $rows);
}

/**
 * Wipe rows for a given report
 * Intended for internal function use.
 *
 * @param int $id
 */
function wipeReportRows(int $id)
{
    // TODO: check for errors from query
    // TODO: check if report exists ?
    wipeReportRowsQuery($id);
}

/**
 * Sets rows for a given report
 *
 * @param int   $id
 * @param array $rows
 */
function setReportRows(int $id, array $rows = array())
{
    // TODO: check for errors from query
    // TODO: check that all row values are valid
    // TODO: multiple queries, transaction ?
    wipeReportRows($id);
    addReportRowsQuery($id, $rows);
}

/**
 * Used internally for functions to validate the rows array
 * Checks that only positive integers present
 *
 * @param array $rows
 *
 * @return bool
 */
function isValidRows(array $rows)
{
    foreach ($rows as $val) {
        if (!is_int($val)) {
            return false;
        } elseif ($val < 0) {
            return false;
        }
    }
    return true;
}