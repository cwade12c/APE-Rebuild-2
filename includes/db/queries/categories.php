<?php
/**
 * Query functions for categories
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Query to create a category
 *
 * @param string $name
 * @param int    $points
 */
function createCategoryQuery(string $name, int $points)
{
    $query = "INSERT INTO `categories` "
        . " (`name`, `points`) "
        . " VALUES (:name, :points)";
    $sql   = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR),
            array(':points', $points, PDO::PARAM_INT)
        )
    );
    // TODO: return inserted row id ?
}

/**
 * Query to delete a single category
 *
 * @param int $id
 */
function deleteCategoryQuery(int $id)
{
    $query = "DELETE FROM `categories` "
        . " WHERE `id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT))
    );
}

/**
 * Query to update a single category
 *
 * @param int    $id
 * @param string $name
 * @param int    $points
 */
function updateCategoryQuery(int $id, string $name, int $points)
{
    $query = "UPDATE `categories` "
        . " SET `name`=:name, `points`=:points "
        . " WHERE `id`=:id;";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':name', $name, PDO::PARAM_STR),
            array(':points', $points, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to update a single category name
 *
 * @param int    $id
 * @param string $name
 */
function updateCategoryNameQuery(int $id, string $name)
{
    $query = "UPDATE `categories` "
        . " SET `name`=:name "
        . " WHERE `id`=:id;";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':name', $name, PDO::PARAM_STR),
        )
    );
}

/**
 * Query to update a single
 *
 * @param int $id
 * @param int $points
 */
function updateCategoryPointsQuery(int $id, int $points)
{
    $query = "UPDATE `categories` "
        . " SET `points`=:points "
        . " WHERE `id`=:id;";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':points', $points, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to get the info (name, points) for a category
 *
 * @param int $id
 *
 * @return mixed
 */
function getCategoryInfoQuery(int $id)
{
    $query = "SELECT `name`, `points` FROM `categories` "
        . " WHERE `id` = :id;";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT)
        )
    );

    return getQueryResultRow($sql);
}

/**
 * Query to get all category IDs
 *
 * @return mixed
 */
function getCategoriesQuery()
{
    $query = "SELECT `id` FROM `categories`;";
    $sql   = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Query to get all default category IDs
 *
 * @return mixed
 */
function getDefaultCategoriesQuery()
{
    $query = "SELECT `id` FROM `default_categories`;";
    $sql   = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Query to wipe the default categories list
 */
function clearDefaultCategoriesQuery()
{
    $query = "DELETE FROM `default_categories`";
    $sql   = executeQuery($query);
}

/**
 * Query to set the list of default categories
 * Does not wipe the list before, really just adds multiple IDs
 *
 * @param array $idList
 */
function setDefaultCategoriesQuery(array $idList)
{
    // build values str and param list
    list($setStr, $params) = buildDefaultCategoriesStringParams($idList);

    $query = sprintf(
        "INSERT INTO `default_categories` "
        . " (`id`) "
        . " VALUES %s", $setStr
    );
    $sql   = executeQuery($query, $params);
}

/**
 * Used by function setDefaultCategoriesQuery()
 * Not intended for outside use.
 * Builds the values string for insert and the parameters
 *
 * @param array $ids
 *
 * @return array
 */
function buildDefaultCategoriesStringParams(array $ids)
{
    $params = array();
    $values = array();

    foreach ($ids as $i => $id) {
        // determine param name
        $val = sprintf(':id%d', $i);
        array_push($values, sprintf('(:%s)', $val));
        // add parameter
        array_push($params, array($val, $id, PDO::PARAM_INT));
    }

    // build values string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}

/**
 * Query to add a single default category
 *
 * @param int $id
 */
function addDefaultCategoryQuery(int $id)
{
    $query = "INSERT INTO `default_categories` "
        . " (`id`) "
        . " VALUES (:id)";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to remove a single default category
 *
 * @param int $id
 */
function removeDefaultCategoryQuery(int $id)
{
    $query = "DELETE FROM `default_categories` "
        . " WHERE `id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT))
    );
}