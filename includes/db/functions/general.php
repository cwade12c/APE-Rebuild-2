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