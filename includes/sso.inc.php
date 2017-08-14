<?php

function enforceAuthentication()
{
    phpCAS::client(SAML_VERSION_1_1, CAS_DOMAIN, 443, '/cas', false);
    phpCAS::setCasServerCACert(CAS_CERT_PATH);
    phpCAS::handleLogoutRequests(true, CAS_HOSTS);
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
    phpCAS::client(SAML_VERSION_1_1, CAS_DOMAIN, 443, '/cas', false);
    phpCAS::setCasServerCACert(CAS_CERT_PATH);
    phpCAS::handleLogoutRequests(true, CAS_HOSTS);
    return phpCAS::isAuthenticated();
}

function setSessionVariables()
{
    global $db;
    $samlAttribs = phpCAS::getAttributes();

    $_SESSION['firstName'] = $samlAttribs['FirstName'];
    $_SESSION['lastName'] = $samlAttribs['LastName'];
    $_SESSION['userType'] = $samlAttribs['UserType'];
    $_SESSION['ewuid'] = $samlAttribs['Ewuid'];
    $_SESSION['email'] = $samlAttribs["Email"];

    if ($_SESSION['ewuid'] && $_SESSION['username']) {
        $id = $_SESSION['ewuid'];
        $sql = executeQuery("SELECT type FROM accounts WHERE id = $id");
        $result = getQueryResult($sql);

        $_SESSION['userGroup'] = $result;
    }

}

function checkFirstTimeLogin()
{
    if ($_SESSION['userType'] == "Student") {

        $id = $_SESSION['ewuid'];
        $type = ACCOUNT_TYPE_STUDENT;
        $lastName = $_SESSION['lastName'];
        $firstName = $_SESSION['firstName'];
        $email = $_SESSION['email'];

        $sql = executeQuery("SELECT * FROM accounts WHERE id = $id");
        $result = getQueryResult($sql);

        if ($result == false) {
            $sql = executeQuery("INSERT INTO accounts(id, type, f_name, l_name, email) values('$id', '$type', '$firstName', '$lastName', '$email');");
        }
    }
    else if($_SESSION['userType'] == "Teacher") {

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