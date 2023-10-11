<h1 align="center">
ShibeShip
<br><br>
<img src="img/shibeship_preview.png" alt="ShibeShip - Opensource Online marketplace using LibDogecoin on Dogecoin BlockChain"/>
<br><br>
</h1>

## What is the ShibeShip

Its a Opensource Marketplace like CraigList or eBay, powered by LibDogecoin and by GigaWallet, that anyone can buy and sell any product or services using only Dogecoin.

## How to Install 💻

1- Get an Linux Hosting Account or Web Server that supports ```PHP (V. 7.3 =>)``` + ```MySQL/MariaDB``` (also works locally with Docker or Xampp for exemple)

2- Upload all files (excluding shibeship.sql and readme.md) to your Hosting Account.

3- Now edit the file ```inc/_config.php```, rename it to ```inc/config.php``` and add your database conections and settings

4- Create a cron task to run every minute to poin to ```inc/cron.php```

5- Make sure the file ```inc/vendores/libdogecoin-php/libdogecoin-json-php``` have CHMOD 0755

###Notes:
- This is a Beta Version so can be some bugs that are not fixed yet
- It generates real Dogecoin Wallets and it stores on the DataBase to send to all Sellers wen a Buy is detected.
