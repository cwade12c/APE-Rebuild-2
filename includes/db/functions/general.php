<?php
/**
 * General, helper functions
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Check if current datetime has passed the given datetime
 *
 * @param datetime $check
 *
 * @return bool
 */
function currentDatePassed(datetime $check)
{
    $diff = date_diff(new DateTime(), $check);
    return ($diff->invert) ? true : false;
}

/**
 * Start a transaction
 *
 * @return bool             If succeeds
 *
 * @throws LogicException   PDO::startTransaction
 *                          should only happen if a transaction is active
 */
function startTransaction()
{
    try {
        return startTransactionQuery();
    } catch (Exception $e) {
        throw new RuntimeException(
            "Failed to start transaction", ERROR_CODE_DB, $e
        );
    }
}

/**
 * Rollback a transaction
 *
 * @return bool             If succeeds
 *
 * @throws LogicException   PDO::rollBack
 *                          should only happen if no transaction is active
 */
function rollback()
{
    try {
        return rollBackQuery();
    } catch (Exception $e) {
        throw new RuntimeException("Failed to rollback", ERROR_CODE_DB, $e);
    }
}

/**
 * Commit a transaction
 *
 * @return bool             If succeeds
 *
 * @throws LogicException   DO::commit exception
 *                          should only happen if no transaction is active
 */
function commit()
{
    try {
        return commitQuery();
    } catch (Exception $e) {
        throw new RuntimeException("Failed to commit", ERROR_CODE_DB, $e);
    }
}

/**
 * Check if within a transaction
 * Only for internal/backend use.
 *
 * @return bool If in transaction
 */
function inTransaction()
{
    return inTransactionQuery();
}

/**
 * Gets details about a table attribute
 *
 * @param string $tableName name of table
 * @param string $attributeName name of attribute
 *
 * @return array                associative array of result
 *                              'DATA_TYPE' => mysql datatype as string
 *                              'CHARACTER_MAXIMUM_LENGTH' => max string length
 *                                  if applicable, else null
 *                              'NUMERIC_PRECISION' => number precision
 *                                  if applicable, else null
 *                              if table/attribute does not exist,
 *                                  false is returned
 */
function getTableAttributeDetails(string $tableName, string $attributeName)
{
    try {
        return getTableAttributeDetailsQuery($tableName, $attributeName);
    } catch (Exception $e) {
        throw new RuntimeException(
            "Exception fetching table attributes", ERROR_CODE_DB, $e
        );
    }

}

/**
 * Handle an exception from a backend function call.
 *
 * @param Exception $exception
 *
 * @return string              Message to display to the user
 *                             If an empty/null string is returned, do nothing
 *
 */
function handleBackendException(Exception $exception)
{
    logBackendException($exception);

    $code = $exception->getCode();
    if ($code > 0) {
        switch ($code) {
            case ERROR_CODE_ARG:
            case ERROR_CODE_ACTION:
                return $exception->getMessage();
            case ERROR_CODE_DB:
            default:
                return GENERIC_BACKEND_EXCEPTION_MESSAGE;
        }
    }

    switch(get_class($exception)) {
        case 'InvalidArgumentException':
            return $exception->getMessage();
        case 'RuntimeException':
        case 'LogicException':
        case 'PDOException':
        default:
            return GENERIC_BACKEND_EXCEPTION_MESSAGE;
    }
}

/**
 * Internal function, helper for handleBackendException()
 * Log the given exception from the backend
 *
 * @param Exception $exception
 */
function logBackendException(Exception $exception)
{
    $type = gettype($exception);
    $code = $exception->getCode();
    $msg = $exception->getMessage();
    $trace = $exception->getTraceAsString();
    error_log(
        "Backend exception({$type}, {$code}): \"{$msg}\". Trace: {$trace}"
    );
}