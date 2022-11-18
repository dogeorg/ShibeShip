<?php
/**
*   Project: HappyShibes
*   Author: A lot of good shibes
*   Description: Real use case of the Dogecoin BlockChain to sell products and services
*   License: Well, do what you want with this, be creative, you have the wheel, just reenvent and do it better! Do Only Good Everyday
*/
//ini_set('display_errors', 1);
session_start();
    // Add your Data Base credentials here!
    $config["dbhost"] = "localhost";  // put here you database adress
    $config["dbname"] = ""; // your DB name
    $config["dbuser"] = ""; // your DB username
    $config["dbpass"] = ""; // your DB password

    $config["mail_name_from"] = "HappyShibes"; //name to show on all emails sent
    $config["email_from"] = "store@localhost"; // email to show and reply on all emails sent

    $config["admin_user"] = "wow"; // your admin user
    $config["admin_pass"] = ""; // your admin password

    $config["demo"] = 0; // to active demo mode change to 1, or 0 to disable
    $config["fiat"] = "usd"; // to active fiat option convertion insert and fiat currency eur/usd/jpy
    $lang["default"] = "EN"; // Default Language

    define('ROOTPATH', __DIR__);
    // include functions
    include("functions.php");
    // we include the DogeGarden version
    include("v.php");
?>
