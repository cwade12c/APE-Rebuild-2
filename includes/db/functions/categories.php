<?php
/**
 * Functions for categories in database
 *
 * @author         Mathew McCain
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Check if category w/ name exists
 *
 * @param string $name
 *
 * @return bool
 */
function categoryNameExists(string $name)
{
    // TODO: populate
    return false;
}

/**
 * Check if category w/ ID exists
 *
 * @param int $id
 *
 * @return bool
 */
function categoryExists(int $id)
{
    // TODO: populate
    return false;
}

/**
 * Creates a category
 *
 * @param string $name
 * @param int    $points
 */
function createCategory(string $name, int $points)
{
    // TODO: check if name exists
    // TODO: check that name is valid (size, regex)
    // TODO: check for success?
    createCategoryQuery($name, $points);
    // TODO: return id, PDO->lastInsertID() ?
}

/**
 * Deletes a category
 *
 * @param int $id
 */
function deleteCategory(int $id)
{
    // TODO: check if id exists
    // TODO: check for success?
    deleteCategoryQuery($id);
}

/**
 * Update a categories name/points
 *
 * @param int    $id
 * @param string $name
 * @param int    $points
 */
function updateCategory(int $id, string $name, int $points)
{
    // TODO: check if id exists
    // TODO: check that name is valid (size, regex)
    // TODO: check for success?
    updateCategoryQuery($id, $name, $points);
}

/**
 * Updates a categories name
 *
 * @param int    $id
 * @param string $name
 */
function updateCategoryName(int $id, string $name)
{
    // TODO: check if id exists
    // TODO: check that name is valid (size, regex)
    // TODO: check for success?
    updateCategoryNameQuery($id, $name);
}

/**
 * Updates a categories points
 *
 * @param int $id
 * @param int $points
 */
function updateCategoryPoints(int $id, int $points)
{
    // TODO: check if id exists
    // TODO: check for success?
    updateCategoryPointsQuery($id, $points);
}

/**
 * Gets the name/points of a category
 *
 * @param int $id
 *
 * @return mixed
 */
function getCategoryInfo(int $id)
{
    return getCategoryInfoQuery($id);
}

/**
 * Get a list of all categories
 *
 * @return mixed
 */
function getCategories()
{
    // TODO: convert return to non - associative array (just int ids)?
    return getCategoriesQuery();
}

/**
 * Get a list of all default category IDs
 *
 * @return mixed
 */
function getDefaultCategories()
{
    // TODO: check if any exist?
    return getDefaultCategoriesQuery();
}

/**
 * Clear list of default categories
 */
function clearDefaultCategories()
{
    // TODO: check for success?
    clearDefaultCategoriesQuery();
}

/**
 * Sets the list of default categories
 *
 * @param array $idList
 */
function setDefaultCategories(array $idList)
{
    // TODO: check list of IDs are all valid
    // TODO: check for success
    // TODO: multiple queries, transaction?
    clearDefaultCategories();
    setDefaultCategoriesQuery($idList);
}

/**
 * Adds a default category
 *
 * @param int $id
 */
function addDefaultCategory(int $id)
{
    // TODO: check if id exists
    // TODO: check if id in default category table
    // TODO: check for success
    addDefaultCategoryQuery($id);
}

/**
 * Removes a default category
 *
 * @param int $id
 */
function removeDefaultCategory(int $id)
{
    // TODO: check if id exists
    // TODO: check if id in default category table
    // TODO: check for success
    removeDefaultCategoryQuery($id);
}
