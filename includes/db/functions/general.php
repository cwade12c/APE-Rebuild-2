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
    } catch (PDOException $e) {
        throw new LogicException("Failed to rollback", 0, $e);
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
    } catch (PDOException $e) {
        throw new LogicException("Failed to rollback", 0, $e);
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
    } catch (PDOException $e) {
        throw new LogicException("Failed to commit", 0, $e);
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
 * @param string $tableName     name of table
 * @param string $attributeName name of attribute
 *
 * @return mixed                associative array of result
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
    return getTableAttributeDetailsQuery($tableName, $attributeName);
    // TODO: check return to see if valid
    /// if false, no records were found
}