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

    // SMTP Email Configuration
    $config["email_stmp"] = "localhost";  // email server SMTP port
    $config["email_port"] = 465;  // email server SMTP port
    $config["mail_name_from"] = "ShibeShip"; //name to show on all emails sent    
    $config["email_from"] = "marketplace@localhost"; // email SMTP username used also to send from        
    $config["email_password"] = "";   // email server SMTP password
    

    // Backoffice User and Pass
    $config["admin_user"] = ""; // your admin user
    $config["admin_pass"] = ""; // your admin password

    // Default configuration
    $config["base_url"] = "https://shibeship.com/"; // the domain/sub-domain name
    $config["demo"] = 1; // to active demo mode change to 1, or 0 to disable
    $config["fiat"] = "USD"; // to active fiat option convertion insert and fiat currency EUR/USD/JPY
    $config["image"] = "/img/shibeship_preview.png"; // default social share image

    $lang["default"] = "gb"; // Default Language

    // include functions
    include("functions.php");
?>