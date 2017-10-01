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

/*
 * TODO: add more error checking to functions
 * Functions are meant to act as wrappers for the query functions
 * Need to catch and handle errors
 */

/**
 * Check if given type has given permission
 * Can be used to avoid multiple queries in short span of time
 *
 * @param int $type       , type for account
 * @param int $permission , account type value (check 'constants.php')
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
 * @param string $accountID
 * @param int    $permission
 *
 * @return bool
 */
function accountTypeHas(string $accountID, int $permission)
{
    // get type
    $type = getAccountTypeQuery($accountID);

    return typeHas($type, $permission);
}

/**
 * Check if given account has any type set
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountHasPermissions(string $accountID)
{
    $type = getAccountTypeQuery($accountID);

    return $type != ACCOUNT_TYPE_NONE;
}

/**
 * Check if account has temp permission
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountIsTemp(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_TEMP);
}

/**
 * Check if account has student permission
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountIsStudent(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_STUDENT);
}

/**
 * Check if account has grader permission
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountIsGrader(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_GRADER);
}

/**
 * Check if account has teacher permission
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountIsTeacher(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_TEACHER);
}

/**
 * Check if account has admin permission
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountIsAdmin(string $accountID)
{
    return accountTypeHas($accountID, ACCOUNT_TYPE_ADMIN);
}

/**
 * Set account to given type
 * Not intended for outside use
 *
 * @param string $accountID
 * @param int    $type
 */
function setAccountType(string $accountID, int $type)
{
    setAccountTypeQuery($accountID, $type);

    // TODO: return type for success/failure ?
}

/**
 * Strip account of all permissions
 * Intended to be used over a delete from the accounts table
 *
 * @param string $accountID
 */
function stripAccountType(string $accountID)
{
    setAccountType($accountID, ACCOUNT_TYPE_NONE);

    // TODO: check for success
}

/**
 * Check if given account exists
 *
 * @param string $accountID
 *
 * @return bool
 */
function accountExists(string $accountID)
{
    return accountExistsQuery($accountID);
}

/**
 * Create account w/ given information
 * Not intended for outside use
 *
 * @param string      $accountID
 * @param int         $type
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 */
function createAccount(string $accountID, int $type = ACCOUNT_TYPE_NONE,
    string $firstName = null, string $lastName = null, string $email = null
) {
    // TODO: check that account ID is valid for type (temp or EWU ID format)
    // TODO: check that information provided (names, email), can be combined with
    //      information already available for complete set
    //      temp accounts, need at-least 1 identification field (name, email)
    //      all other accounts must have them all filled

    createAccountQuery($accountID, $type, $firstName, $lastName, $email);

    // TODO: check for success
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
    // TODO: check at least 1 identification field filled

    // TODO: generate random/unique temp ID
    // generate random temp id, check if exists
    // may need to lock DB someway
    // create account
    $id = generateTempID();
    // TODO: validate random id is unqiue / does not exist

    $type = ACCOUNT_TYPE_TEMP | ACCOUNT_TYPE_STUDENT;
    createAccount($id, $type, $firstName, $lastName, $email);

    // TODO: check for success
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
    // TODO: check if student exists, update information if so or throw exception
    // if account exists, ensure that type matches (student)
    // TODO: check all identification information available

    createAccount($id, ACCOUNT_TYPE_STUDENT, $firstName, $lastName, $email);

    // TODO: check for success
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
    // TODO: check if grader exists, update information if so or throw exception
    // if account exists, ensure that type matches (grader)
    // TODO: ensure information is not empty
    // TODO: check all identification information available

    createAccount($id, ACCOUNT_TYPE_GRADER, $firstName, $lastName, $email);

    // TODO: check for success
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
    // TODO: check if teacher exists, update information if so or throw exception
    // if account exists, ensure that type matches (teacher at least)
    // TODO: check all identification information available

    createAccount(
        $id, ACCOUNT_TYPE_TEACHER | ACCOUNT_TYPE_GRADER, $firstName, $lastName,
        $email
    );

    // TODO: check for success
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
    // TODO: check if admin exists, update information if so or throw exception
    // if account exists, ensure that type matches (admin)
    // TODO: check all identification information available

    createAccount($id, ACCOUNT_TYPE_ADMIN, $firstName, $lastName, $email);

    // TODO: check for success
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
    // TODO: check if temp id is actually temp account
    // TODO: check if new id exists, combine accounts
    // TODO: lock db / create transaction for all queries involved
    // TODO: include parameters for updating account information (names, email)
    // TODO: ensure identification information exists/available

    updateAccountIDQuery($tempID, $id);
    setAccountType($id, ACCOUNT_TYPE_STUDENT);

    // TODO: check for success
}

/**
 * Used by promoteTempToStudent()
 * Not intended for outside use
 *
 * @param string $tempID
 * @param string $id
 */
function combineStudent(string $tempID, string $id)
{
    // TODO: merge temp account to student account
    // will move over all related table entries
    // TODO: check if update operation will update all tables correctly
    // this operation may get complex

    // TODO: check for success
}

/**
 * Generate a random temporary ID
 * Is not guaranteed unique/ existing
 *
 * @return string
 */
function generateTempID()
{
    // TODO: generate random ID according to defined format
    // TODO: find library to generate strings off regex?
    return "unimplemented";
}

/**
 * Check if given ID follows EWU ID format
 *
 * @param string $id
 *
 * @return bool
 */
function validID(string $id)
{
    // TODO: pull regex from config file to check against
    return false;
}

/**
 * Check if given id follows temp ID format
 *
 * @param string $id
 *
 * @return bool
 */
function validTempID(string $id)
{
    // TODO: pull regex from config file to check against
    return false;
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
 * @return mixed
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