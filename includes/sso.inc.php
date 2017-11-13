<?php

function initCAS()
{
    phpCAS::client(SAML_VERSION_1_1, CAS_DOMAIN, 443, '/cas', false);
    phpCAS::setCasServerCACert(CAS_CERT_PATH);
    phpCAS::handleLogoutRequests(true, CAS_HOSTS);
}

function enforceAuthentication()
{
    phpCAS::forceAuthentication();

    $_SESSION['username'] = phpCAS::getUser();
    $_SESSION['loggedInLocally'] = true;

    if (isset($_SESSION['username'])) {
        if (isset($_SESSION['loggedInLocally'])) {
            setSessionVariables();
            checkFirstTimeLogin();
        }
    } else {
        //Independent student, redirect them to the ape registration page

    }

    $_SESSION['loggedInLocally'] = true;
}

function userIsLoggedIn()
{
    return phpCAS::isAuthenticated();
}

function setSessionVariables()
{
    global $db;
    $samlAttribs = phpCAS::getAttributes();

    //TODO remove this debug code before production
    if (DEBUG) {
        if (DEBUG_ROLE == ACCOUNT_TYPE_TEMP) {
            $_SESSION['firstName'] = 'Temporary';
            $_SESSION['lastName'] = 'Account';
            $_SESSION['userType'] = 'Temporary';
            $_SESSION['ewuid'] = '00111111';
            $_SESSION['email'] = 'temporary.a@eagles.ewu.edu';
        } elseif (DEBUG_ROLE == ACCOUNT_TYPE_STUDENT) {
            $_SESSION['firstName'] = 'Student';
            $_SESSION['lastName'] = 'Account';
            $_SESSION['userType'] = 'Student';
            $_SESSION['ewuid'] = '00111113';
            $_SESSION['email'] = 'student.a@eagles.ewu.edu';
        } elseif (DEBUG_ROLE == ACCOUNT_TYPE_GRADER) {
            $_SESSION['firstName'] = 'Grader';
            $_SESSION['lastName'] = 'Account';
            $_SESSION['userType'] = 'Grader';
            $_SESSION['ewuid'] = '00111128';
            $_SESSION['email'] = 'grader.a@eagles.ewu.edu';
        } elseif (DEBUG_ROLE == ACCOUNT_TYPE_TEACHER) {
            $_SESSION['firstName'] = 'Teacher';
            $_SESSION['lastName'] = 'Account';
            $_SESSION['userType'] = 'Teacher';
            $_SESSION['ewuid'] = '00111131';
            $_SESSION['email'] = 'teacher.a@eagles.ewu.edu';
        } elseif (DEBUG_ROLE == ACCOUNT_TYPE_ADMIN) {
            $_SESSION['firstName'] = 'Admin';
            $_SESSION['lastName'] = 'Account';
            $_SESSION['userType'] = 'Admin';
            $_SESSION['ewuid'] = '00111133';
            $_SESSION['email'] = 'admin.a@eagles.ewu.edu';
        }
    } else {
        $_SESSION['firstName'] = $samlAttribs['FirstName'];
        $_SESSION['lastName'] = $samlAttribs['LastName'];
        $_SESSION['userType'] = $samlAttribs['UserType'];
        $_SESSION['ewuid'] = $samlAttribs['Ewuid'];
        $_SESSION['email'] = $samlAttribs["Email"];
    }

    if ($_SESSION['ewuid'] && $_SESSION['username']) {
        $id = $_SESSION['ewuid'];
        $_SESSION['userGroup'] = getAccountType($id);
    }

    global $params;
    $params = array(
        'isLoggedIn' => true,
        'firstName' => $_SESSION['firstName'],
        'lastName' => $_SESSION['lastName'],
        'type' => getAccountType((string)$_SESSION['ewuid']),
        'id' => $_SESSION['ewuid'],
        'email' => $_SESSION['email'],
        'availableNavLinks' => getAvailableNavigationLinks()
    );

}

function getAvailableNavigationLinks()
{
    if (accountIsStudent((string)$_SESSION['ewuid'])) {
        return STUDENT_LINKS;
    } else {
        if (accountIsGrader((string)$_SESSION['ewuid'])) {
            return GRADER_LINKS;
        } else {
            if (accountIsTeacher((string)$_SESSION['ewuid'])) {
                return TEACHER_LINKS;
            } else {
                if (accountIsAdmin((string)$_SESSION['ewuid'])) {
                    return ADMIN_LINKS;
                }
            }
        }
    }

    return GUEST_LINKS;
}

function checkFirstTimeLogin()
{
    $id = $_SESSION['ewuid'];
    $lastName = $_SESSION['lastName'];
    $firstName = $_SESSION['firstName'];
    $email = $_SESSION['email'];

    if ($_SESSION['userType'] == "Student") {
        if (accountExists($id)) {
            updateAccountInfo($id, $firstName, $lastName, $email);
        } else {
            createStudent($id, $firstName, $lastName, $email);
        }
    } else {
        if ($_SESSION['userType'] == "Teacher") {
            if (accountExists($id)) {
                updateAccountInfo($id, $firstName, $lastName, $email);
            } else {
                createAccount(
                    $id, ACCOUNT_TYPE_NONE, $firstName, $lastName, $email
                );
            }
        }
    }
}

function logout()
{
    $helper = array_keys($_SESSION);
    foreach ($helper as $key) {
        unset($_SESSION[$key]);
    }
    session_unset();
    session_destroy();
    phpCAS::logout();
}