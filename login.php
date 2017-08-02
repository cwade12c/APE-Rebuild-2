<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();


function casAuth()
{
    require_once('includes/CAS.php');
    $cas_server_ca_cert_path = 'includes/comodo_combo.pem';
    $cas_real_hosts = array(
        'it-adfs01.eastern.ewu.edu',
        'it-casauth01.eastern.ewu.edu'
    );

    phpCAS::client(SAML_VERSION_1_1, 'login.ewu.edu', 443, '/cas', false);
    phpCAS::setCasServerCACert($cas_server_ca_cert_path);
    phpCAS::handleLogoutRequests(true, $cas_real_hosts);
    phpCAS::forceAuthentication();

    $_SESSION['loggedInLocally']
        = true; //set a local variable telling the program we are logged in
    $_SESSION['username'] = phpCAS::getUser(
    ); //this stores their network user id
    $samlAttribs = phpCAS::getAttributes();

    var_dump($samlAttribs);


    //$user      = new User_Model();
    //$user_info = $_SESSION['username'];

    /*if ($user_info) {
        //var_dump($user_info);
        return true;
    } else {
        return false;
    }*/

    /*if ($user_info) {
    // Registered and set SESSION vars
    $_SESSION['user_id'] = $user_info->user_id;
    // What if their name changes, EWU ID shouldn't changem but we'll correct it anyway
    if(strcmp($user_info->first_name, $samlAttribs['FirstName']) != 0
    || strcmp($user_info->last_name, $samlAttribs['LastName']) != 0
    || strcmp($user_info->ewuid, $samlAttribs['Ewuid']) != 0) {
    //$user_info->first_name = $samlAttribs['FirstName'];
    //$user_info->last_name = $samlAttribs['LastName'];
    //$user_info->ewuid = $samlAttribs['Ewuid'];
    //$user_info->save();
    }
    $_SESSION['first_name'] = $samlAttribs['FirstName'];
    $_SESSION['last_name'] = $samlAttribs['LastName'];
    $_SESSION['ewuid'] = $samlAttribs['Ewuid'];

    /*$_SESSION['ewuid'] = $user_info->ewuid;
    $_SESSION['first_name'] = $user_info->first_name;
    $_SESSION['last_name'] = $user_info->last_name;
    $_SESSION['email'] = $user_info->email;
    $_SESSION['uniqueid'] = $user_info->uniqueid;
    $_SESSION['verify'] = $user_info->verify;
    $_SESSION['active'] = $user_info->active;
    $_SESSION['grader'] = $user_info->grader;*/
    //return true;
    //} else {
    // Not Registered, redirect to Registration
    //$_SESSION['first_name'] = $samlAttribs['FirstName'];
    //$_SESSION['last_name'] = $samlAttribs['LastName'];
    //$_SESSION['ewuid'] = $samlAttribs['Ewuid'];
    //return false;
    //}
}


casAuth();

//if (isset($_GET['ticket'])) {
//    echo '<pre>' . var_dump($_SESSION) . '</pre>';
//} else {
//if (casAuth()) {
//    header('Location: http://146.187.134.42/test.php');
//    exit(0);
//} else {
//    echo 'hi';
//    header('Location: http://146.187.134.42/register');
//}
//}

?>