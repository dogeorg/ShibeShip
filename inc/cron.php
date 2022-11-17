<?php
// include the configuration and functions
include("config.php");

$db = $pdo->query("SELECT * FROM generated where paid = 0"); // we get all orders not paid
while ($row = $db->fetch()) {
    $doge_payment = $d->DogeBalance($row["doge_public"]);

    if ($doge_payment > ($row["amount"] - 0.01)){

        // we get the seller ID from the product sold
        $product = $pdo->query("SELECT * FROM products where id = '".$row["id_product"]."' limit 1")->fetch();

        // we get the seller details from the product id sold
        $shibe = $pdo->query("SELECT * FROM shibes where id = '".$product["id_shibe"]."' limit 1")->fetch();

        $logo = "<img src='https://shibeship.com/img/shibeship.png' style='width:100%' /><br><br>";

        // we send an email to the Buyer confirming the payment recived
        $mail_subject = "Much Wow! Payment in Doge Confirmed!!";
        $mail_message = $logo."Hello ".$row["name"].",<br><br>Thank you for your recent purchase of <b>".$product["title"]."</b>. Your payment is now confirmed.<br><br>The seller will be informed to contact you.<br><br>Much Thanks!";
        $d->SendEmail($config["mail_name_from"],$config["email_from"],$row["email"],$mail_subject,$mail_message);

        // we send an email to Seller informing that the payment was detected
        $mail_subject = "Much Wow! You sold ".$product["title"]."!";
        $mail_message = $logo."Hello ".$shibe["name"].",<br><br>You just sold <b>".$product["title"]."</b> for <br><br>Ð ".$row["amount"]. "<br><br> Here is you Doge Wallet that stores your Dogecoin, please import that wallet to any wallet that supports like the Dogecoin Core Wallet, and move/send your Doge to your own wallet. We do not recomend leaving Doge in this wallet because was temporarly stored on our DataBase<br><br>Public Key: ".$row["doge_public"]."<br>Private Key: ".$row["doge_private"]."<br><br>Much Thanks!";
        $d->SendEmail($config["mail_name_from"],$config["email_from"],$shibe["email"],$mail_subject,$mail_message);

        // we update the status to paid
        $d->UpdatePaidBuy($row["id"]);
    }
}
?>