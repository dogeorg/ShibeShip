<?php
/**
*   Project: November 2022 Hackathon / Dogeathon / Downunder Project HappyShibes aka ShibeShip
*   Author: A lot of good shibes
*   Description: Real use case of the Dogecoin BlockChain to sell products and services
*   License: Well, do what you want with this, be creative, you have the wheel, just reenvent and do it better! Do Only Good Everyday
*/
// include the configuration and functions

include("config.php");
if (isset($_POST["fiatvalue"])){
    $d->UpdateFiatValue($_POST["fiatvalue"],$_POST["fiatcurrency"]);
};
?>