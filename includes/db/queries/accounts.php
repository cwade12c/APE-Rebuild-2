<?php
/**
 * Query functions for accounts
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

// TODO: check account type query ? mySql supports binary operations

/**
 * Get names and email for an account
 *
 * @param string $accountID
 *
 * @return array            query row
 *                          'f_name', 'l_name', 'email'
 *                          any can be null, depends on type
 */
function getAccountInfoQuery(string $accountID)
{
    $query
        = "SELECT `f_name`, `l_name`, `email` "
        . "FROM `accounts` WHERE (`id` = :id);";
    $sql = executeQuery(
        $query, array(
            array(':id', $accountID, PDO::PARAM_STR))
    );

    return getQueryResultRow($sql);
}

/**
 * get type from a given account id
 *
 * @param string $accountID
 *
 * @return mixed, type value (int)
 */
function getAccountTypeQuery(string $accountID)
{
    $query = "SELECT `type` FROM `accounts` WHERE (`id` = :id);";
    $sql = executeQuery(
        $query, array(
            array(':id', $accountID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * set given type to given account id
 *
 * @param string $accountID
 * @param int    $type
 */
function setAccountTypeQuery(string $accountID, int $type)
{
    $query = "UPDATE `accounts` "
        . " SET `type`=:type "
        . " WHERE `id`=:id; ";
    $sql = executeQuery(
        $query, array(
            array(':id', $accountID, PDO::PARAM_STR),
            array(':type', $type, PDO::PARAM_INT)
        )
    );

    // TODO: check for success (PDO->rowCount()) ?
}

/**
 * update account id to new id
 * intended for upgrading temp accounts to full accounts
 *
 * @param string $accountID    , existing account id
 * @param string $newAccountID , new account id as replacement
 */
function updateAccountIDQuery(string $accountID, string $newAccountID)
{
    $query = "UPDATE `accounts` "
        . " SET `id`=:nid "
        . " WHERE `id`=:id;";
    $sql = executeQuery(
        $query, array(
            array(':id', $accountID, PDO::PARAM_STR),
            array(':nid', $newAccountID, PDO::PARAM_STR)
        )
    );

    // TODO: check for success (PDO->rowCount()) ?
}

/**
 * update fields for given account, if null - will not change
 *
 * @param string $accountID
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 */
function updateAccountInfoQuery(string $accountID,
    string $firstName = null, string $lastName = null, string $email = null
) {

    // get set string and params
    $ret = buildUpdateAccountParamStringArr($firstName, $lastName, $email);

    if (!$ret) {
        return;
    }
    list($setStr, $params) = $ret;
    array_push($params, array(':id', $accountID, PDO::PARAM_STR));

    // build, execute query
    $query = sprintf(
        "UPDATE `accounts` "
        . " SET %s "
        . " WHERE `id`=:id;", $setStr
    );

    $sql = executeQuery($query, $params);

    // TODO: check for success (PDO->rowCount()) ?
}

/**
 * Update the type field for given account
 * @param string $accountID
 * @param int    $type
 */
function updateAccountTypeQuery(string $accountID, int $type) {
    $query = "UPDATE `accounts` "
        . " SET `type`=:type "
        . " WHERE `id`=:accountID;";
    $sql = executeQuery(
        $query, array(
            array(':accountID', $accountID, PDO::PARAM_STR),
            array(':type', $type, PDO::PARAM_INT)
        )
    );
}

/**
 * Builds set string and param array for use in updateAccountInfoQuery()
 * Only intended for that function
 *
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 *
 * @return array|bool, returns set string and parameter array on success
 *                   returns false on error (or no values are set to update)
 */
function buildUpdateAccountParamStringArr(
    string $firstName = null, string $lastName = null, string $email = null
) {
    // determine values to update
    $updateFName = is_null($firstName);
    $updateLName = is_null($lastName);
    $updateEmail = is_null($email);

    if ($updateFName && !$updateLName && !$updateEmail) {
        return false;
    }

    // build query set string, and param array
    $params = array();
    $setArr = array();
    if (!$updateFName) {
        array_push($setArr, array('f_name', ':fName'));
        array_push($params, array(':fName', $firstName, PDO::PARAM_STR));
    }
    if (!$updateLName) {
        array_push($setArr, array('l_name', ':lName'));
        array_push($params, array(':lName', $lastName, PDO::PARAM_STR));
    }
    if (!$updateEmail) {
        array_push($setArr, array('email', ':email'));
        array_push($params, array(':email', $email, PDO::PARAM_STR));
    }
    // build set string for query
    $buildSetStr = function (array $keys) {
        return implode('=', $keys);
    };
    $setArr = array_map($buildSetStr, $setArr);
    $setStr = implode(',', $setArr);

    return array($setStr, $params);
}

/**
 * check if given account exists
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountExistsQuery(string $accountID)
{
    $query = "SELECT EXISTS( "
        . " SELECT `id` from `accounts` "
        . " WHERE `id`=:id);";
    $sql = executeQuery(
        $query, array(
            array(':id', $accountID, PDO::PARAM_STR))
    );
    $exists = getQueryResult($sql);

    return $exists;
}

/**
 * Query to insert new account w/ given values into table
 * TODO: test null values for strings work correctly
 * TODO: implement way to enforce string size limitation of column here
 *
 * @param string      $accountID
 * @param int         $type
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 */
function createAccountQuery(string $accountID, int $type = ACCOUNT_TYPE_NONE,
    string $firstName = null, string $lastName = null, string $email = null
) {
    $query = "INSERT INTO `accounts` "
        . " (`id`,`type`,`f_name`,`l_name`,`email`) "
        . "  VALUES (:id, :type, :fName, :lName, :email)";

    $sql = executeQuery(
        $query, array(
            array(':id', $accountID, PDO::PARAM_STR),
            array(':type', $type, PDO::PARAM_INT),
            array(':fName', $firstName, PDO::PARAM_STR),
            array(':lName', $lastName, PDO::PARAM_STR),
            array(':email', $email, PDO::PARAM_STR)
        )
    );

    // TODO: check for success ?
}

/**
 * Get all account IDs
 *
 * @return mixed
 */
function getAccountIDsQuery()
{
    $query = "SELECT `id` FROM `accounts`";
    $sql = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Get all accounts with full information
 *
 * @return mixed
 */
function getAccountsQuery()
{
    $query = "SELECT * FROM `accounts`";
    $sql = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * @param int $type
 *
 * Get the number of accounts belonging to a specified type
 *
 * @return mixed
 */
function getNumberOfAccountsByTypeQuery(int $type)
{
    $query = "SELECT COUNT(*) FROM `accounts` WHERE `type`=:type";
    $sql = executeQuery(
        $query, array(
            array(':type', $type, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

/**
 * @param int $type
 *
 * Get all IDs of all accounts belonging to a specified type
 *
 * @return mixed
 */
function getIdsByTypeQuery(int $type)
{
    $query = "SELECT `id` FROM `accounts` WHERE `type`=:type";
    $sql = executeQuery(
        $query, array(
            array(':type', $type, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}


/**
 * @param int $type
 *
 * Get all information of all accounts belonging to a specified type
 *
 * @return mixed
 */
function getFullAccountInformationByTypeQuery(int $type)
{
    $query
        = "SELECT 'id', 'f_name', 'l_name', 'email' "
        . " FROM `accounts` WHERE `type`=:type";
    $sql = executeQuery(
        $query, array(
            array(':type', $type, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}


/**
 * Get all temporary student account IDs
 *
 * @return mixed
 */
function getTempStudentIDsQuery()
{
    return getIdsByTypeQuery(ACCOUNT_TYPE_TEMP);
}

/**
 * Get all temporary student accounts with full information
 *
 * @return mixed
 */
function getTemporaryStudentsQuery()
{
    return getFullAccountInformationByTypeQuery(ACCOUNT_TYPE_TEMP);
}

/**
 * Get all student account IDs
 *
 * @return mixed
 */
function getStudentIDsQuery()
{
    return getIdsByTypeQuery(ACCOUNT_TYPE_STUDENT);
}

/**
 * Get all student accounts with full information
 *
 * @return mixed
 */
function getStudentsQuery()
{
    return getFullAccountInformationByTypeQuery(ACCOUNT_TYPE_STUDENT);
}

/**
 * Get all grader account IDs
 *
 * @return mixed
 */
function getGraderIDsQuery()
{
    return getIdsByTypeQuery(ACCOUNT_TYPE_GRADER);
}


/**
 * Get all grader accounts with full information
 *
 * @return mixed
 */
function getGradersQuery()
{
    return getFullAccountInformationByTypeQuery(ACCOUNT_TYPE_GRADER);
}

/**
 * Get all teacher account IDs
 *
 * @return mixed
 */
function getTeacherIDsQuery()
{
    return getIdsByTypeQuery(ACCOUNT_TYPE_TEACHER);
}

/**
 * Get all teacher accounts with full information
 *
 * @return mixed
 */
function getTeachersQuery()
{
    return getFullAccountInformationByTypeQuery(ACCOUNT_TYPE_TEACHER);
}

/**
 * Get all admin account IDs
 *
 * @return mixed
 */
function getAdminIDsQuery()
{
    return getIdsByTypeQuery(ACCOUNT_TYPE_ADMIN);
}

/**
 * Get all admin accounts with full information
 *
 * @return mixed
 */
function getAdminsQuery()
{
    return getFullAccountInformationByTypeQuery(ACCOUNT_TYPE_ADMIN);
}

// TODO: Check if size of string matches given table/column
/// make general query, have account specific one for quickly grabbing info
/// or have preset in a config file