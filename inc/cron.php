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
        $mail_message = $logo."Hello ".$row["name"].",<br><br>Thank you for your recent purchase of <b>".$product["title"]."</b>. Your payment is now confirmed.<br><br>The seller will be informed to contact you. Dont forget to make sure you have the PIN code in a safe place to be able to send to the Seller.<br><br>Much Thanks!";
        $d->SendEmail($config["mail_name_from"],$config["email_from"],$row["email"],$mail_subject,$mail_message);

        // we send an email to Seller informing that the payment was detected
        $mail_subject = "Much Wow! You sold ".$product["title"]."!";
        $mail_buyer = "Buyer Details<br><br> Name: ".$row["name"]."Email: ".$row["email"]."<br>Address: ".$row["address"]."<br>Postal Code: ".$row["posta_code"]."<br>Country: ".$row["country"]."<br>City: ".$row["city"]."<br>Phone: ".$row["phone"];
        $mail_message = $logo."Hello ".$shibe["name"].",<br><br>You just sold <b>".$product["title"]."</b> for <br><br>Ð ".$row["amount"]. "<br><br>Here is your Doge Wallet that stores your Dogecoin, please contact the buyer and ask him for the PIN code, then login to the the website click on Such, ".$shibe["name"].", then on My Orders, check the order status Paid and click on Options->View and enter the PIN code to access the Private Key of your Wallet.<br><br>You will have to install Dogecoin Core Wallet from here <a href='https://dogecoin.com/wallets/'>dogecoin.com/wallets/</a> click on File->Import Private Key, and you will imidiatly have acess to your money.<br><br>We do not recomend leaving Doge in this wallet because is encrypted with the buyer PIN on our DataBase, so you can simple send the money to another Dogecoin Wallet that belongs to you, and will be safe.<br><br>Public Key: ".$row["doge_public"]."<br>Private Key: (ask the PIN code to the Buyer)<br><br>".$mail_buyer."<br><br>Much Thanks!";

        $d->SendEmail($config["mail_name_from"],$config["email_from"],$shibe["email"],$mail_subject,$mail_message);

        // we update the status to paid
        $d->UpdatePaidBuy($row["id"]);
    }
}
?>