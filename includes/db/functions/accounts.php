<?php
/**
 * Functions for accounts in database
 * Intended for external use unless stated otherwise
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

function getAccountInfo(string $accountID)
{
    $info = getAccountInfoQuery($accountID);

    $info['firstName'] = $info['f_name'];
    unset($info['f_name']);

    $info['lastName'] = $info['l_name'];
    unset($info['l_name']);

    return $info;
}

/**
 * Check if given type has given permission
 * Can be used to avoid multiple queries in short span of time
 *
 * @param int $type       Type for account
 * @param int $permission Account type value (check 'constants.php')
 *
 * @return bool
 */
function typeHas(int $type, int $permission)
{
    return ($type & $permission) == $permission;
}

/**
 * Get the type for the account ID
 *
 * @param string $accountID Account ID
 *
 * @return int              Account type
 */
function getAccountType(string $accountID)
{
    return getAccountTypeQuery($accountID);
}

/**
 * check if account has given permission on type
 * permission values available in 'constants.php'
 *
 * @param string $accountID  Account ID
 * @param int    $permission Permission value
 *
 * @return bool              If account has given type
 */
function accountTypeHas(string $accountID, int $permission)
{
    $type = getAccountTypeQuery($accountID);

    return typeHas($type, $permission);
}

/**
 * Check if given account has any type set
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account has any type set
 */
function accountHasPermissions(string $accountID)
{
    $type = getAccountTypeQuery($accountID);

    return $type != ACCOUNT_TYPE_NONE;
}

/**
 * Check if account has temp permission
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account has temp type
 */
function accountIsTemp(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_TEMP);
}

/**
 * Check if account has student permission
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account has student type
 */
function accountIsStudent(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_STUDENT);
}

/**
 * Check if account has grader permission
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account has grader type
 */
function accountIsGrader(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_GRADER);
}

/**
 * Check if account has teacher permission
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account has teacher type
 */
function accountIsTeacher(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_TEACHER);
}

/**
 * Check if account has admin permission
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account has admin type
 */
function accountIsAdmin(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_ADMIN);
}

/**
 * Internal function, not intended for outside use
 * Set account to given type
 *
 * @param string $accountID Account ID
 * @param int    $type      Account type
 */
function setAccountType(string $accountID, int $type)
{
    setAccountTypeQuery($accountID, $type);
}

/**
 * Strip account of all permissions
 * Intended to be used over a delete from the accounts table
 * If account has the temp flag set, strips only down to temp
 *
 * @param string $accountID Account ID
 */
function stripAccountType(string $accountID)
{
    $type
        = accountTypeHas($accountID, ACCOUNT_TYPE_TEMP)
        ? ACCOUNT_TYPE_TEMP : ACCOUNT_TYPE_NONE;

    setAccountType($accountID, $type);
}

/**
 * Check if given account exists
 *
 * @param string $accountID Account ID
 *
 * @return bool             If account exists
 */
function accountExists(string $accountID)
{
    return accountExistsQuery($accountID);
}

/**
 * Internal function
 * Create account w/ given information
 *
 * @param string      $accountID Account ID
 * @param int         $type      Account type
 * @param string|null $firstName First name, can be null
 * @param string|null $lastName  Last name, can be null
 * @param string|null $email     Email, can be null
 */
function createAccount(string $accountID, int $type = ACCOUNT_TYPE_NONE,
    string $firstName = null, string $lastName = null, string $email = null
) {
    createAccountQuery($accountID, $type, $firstName, $lastName, $email);
}

/**
 * To update account information
 *
 * @param string      $accountID
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 */
function updateAccountInfo(string $accountID,
    string $firstName = null, string $lastName = null, string $email = null
) {
    updateAccountInfoQuery($accountID, $firstName, $lastName, $email);
}

/**
 * Create a temp student w/ given information
 * At-least 1 field must be filled in
 *
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 */
function createTempStudent(string $firstName = null, string $lastName = null,
    string $email = null
) {
    $id = generateTempID();

    $type = ACCOUNT_TYPE_TEMP | ACCOUNT_TYPE_STUDENT;
    createAccount($id, $type, $firstName, $lastName, $email);
}

/**
 * Create student
 *
 * @param string $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 */
function createStudent(string $id, string $firstName, string $lastName,
    string $email
) {
    createAccount($id, ACCOUNT_TYPE_STUDENT, $firstName, $lastName, $email);
}

/**
 * Create grader
 *
 * @param string $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 */
function createGrader(string $id, string $firstName, string $lastName,
    string $email
) {
    createAccount($id, ACCOUNT_TYPE_GRADER, $firstName, $lastName, $email);
}

/**
 * Create teacher
 * Has grader permissions as well
 *
 * @param string $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 */
function createTeacher(string $id, string $firstName, string $lastName,
    string $email
) {
    createAccount(
        $id, ACCOUNT_TYPE_TEACHER | ACCOUNT_TYPE_GRADER, $firstName, $lastName,
        $email
    );
}

/**
 * Create admin
 * No additional permissions
 *
 * @param string $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 */
function createAdmin(string $id, string $firstName, string $lastName,
    string $email
) {
    createAccount($id, ACCOUNT_TYPE_ADMIN, $firstName, $lastName, $email);
}

/**
 * Updates a temp student into a full student
 * A check should be performed before to ensure the new ID doesn't exist already
 *
 * @param string $tempID
 * @param string $id
 */
function promoteTempToStudent(string $tempID, string $id)
{
    if (accountExists($id)) {
        combineStudent($tempID, $id);
    } else {
        startTransaction();

        // strip off temp type - retain others
        $type = getAccountType($tempID) & (~ACCOUNT_TYPE_TEMP);
        setAccountType($tempID, $type);

        updateAccountIDQuery($tempID, $id);

        commit();
    }
}

/**
 * Internal function
 * Used by promoteTempToStudent()
 *
 * @param string $tempID
 * @param string $id
 */
function combineStudent(string $tempID, string $id)
{
    /*
     * goal:
     * want all foreign keys pointing to temp id to be updated to mew id
     * then, delete old key/row
     *
     * https://dba.stackexchange.com/questions/103828/update-all-foreign-keys-to-a-different-primary-key
     * exact situation
     * but have way too many tables to update for this
     *
     * should not have done the account ID as the primary keys
     *  would've solved a lot of issues.
     *
     * possible workaround,
     * only allow if no registered exams are in grading/finalization
     * once archived, just clean out all the old entries and do
     */
}

/**
 * Generate a random temporary ID
 * ID will be based off the existing time
 * Not guaranteed unique
 *
 * @return string
 */
function generateTempID()
{
    /*
     * See -
     * http://php.net/manual/en/function.uniqid.php
     * http://php.net/manual/en/function.random-bytes.php
     * Due to limitations of uniqid() and random_bytes(), made a mix
     * uniqid() puts in a unix timestamp in hex format and some random bytes
     * that are also in hex.
     * random_bytes() returns random bytes
     *
     * A mix approach was done as a temporary solution
     * Takes the timestamp, and a set of random bytes - packs/64 bit encodes
     * Due to how rarely temp account will be made, collisions unlikely
     * and if they do, the admin/teacher can try again
     *
     * this done over uniqid() provides - more possible characters (16 vs 64)
     * and a bit more randomness, but not much due to the timestamp.
     * the timestamp is kept in just as a guarantee.
     *
     * Some possible replacements:
     * 1) all random, if it fails then it fails - can just retry.
     * - can also implement an automatic retry, just hard-code a value for
     * max attempts to avoid any issues.
     * 2) have a table with an incrementing value to go off, can
     * implement a hash within the final string (but need more length)
     */

    $rand = random_bytes(TEMP_ID_BYTES_GENERATED);
    $pack = pack("iA*", time(), $rand);
    $encode = base64_encode($pack);

    // base 64 encoding will expand the number of bytes - need to trim
    $id = TEMP_ID_PREFIX . substr($encode, 0, TEMP_ID_BYTES_GENERATED);

    return $id;
}

/**
 * Check if given ID follows EWU ID format
 *
 * @param string $id
 *
 * @return mixed     may return 1 for true, 0 for false or boolean false
 *                   false will be returned for errors as well.
 *                   See php docs
 *                   http://php.net/manual/en/function.preg-match.php
 *                   In either case, the value is safe to be used
 *                   as a condition in an if statement.
 */
function validID(string $id)
{
    $id = trim($id);
    return ($id && preg_match(REGEX_ACCOUNT_ID, $id));
}

/**
 * Check if given id follows temp ID format
 *
 * @param string $id
 *
 * @return mixed     see validID() doc comment
 */
function validTempID(string $id)
{
    $id = trim($id);
    return ($id && preg_match(REGEX_TEMP_ID, $id));
}

// get all account (by id)

/**
 * Get all accounts with full information
 *
 * @return mixed
 */
function getAllAccounts()
{
    return getAccountsQuery();
}

// get all accounts by minimum type

/**
 * @param int $type
 *
 * Get all information of all accounts belonging to a specified type
 *
 * @return mixed
 */
function getAllAccountsByType(int $type)
{
    return getFullAccountInformationByTypeQuery($type);
}

/**
 * Get all temporary student accounts with full information
 *
 * @return mixed
 */
function getAllTemporaryStudents()
{
    return getTemporaryStudentsQuery();
}

/**
 * Get all student accounts with full information
 *
 * @return mixed
 */
function getAllStudents()
{
    return getStudentsQuery();
}

/**
 * Get all grader accounts with full information
 *
 * @return array
 */
function getAllGraders()
{
    return getGradersQuery();
}

/**
 * Get all teacher accounts with full information
 *
 * @return mixed
 */
function getAllTeachers()
{
    return getTeachersQuery();
}

/**
 * Get all admin accounts with full information
 *
 * @return mixed
 */
function getAllAdmins()
{
    return getAdminsQuery();
}

// search accounts by given identification exactly (including null)
/// all identification used

// search accounts by given identification (ignoring null)
/// all identification used, if ignore if null

// search accounts by given identification partial (any matches)
/// match only 1 piece of identification

/**
 * Check to see if the minimum number of admins exist as defined in the config
 * file
 *
 * @return bool
 */
function doesMinimumNumberOfAdminsExist()
{
    return getNumberOfAccountsByTypeQuery(ACCOUNT_TYPE_ADMIN)
        == MINIMUM_NUMBER_OF_ADMINS;
}

/**
 * Hash a given account ID according to the set algorithm and length
 *
 * @param string $accountID
 *
 * @return string
 */
function hashAccountID(string $accountID)
{
    $hash = hash(ACCOUNT_ID_HASH_ALGORITHM, $accountID);
    $hash = substr($hash, 0, ACCOUNT_ID_HASH_LENGTH);
    return $hash;
}