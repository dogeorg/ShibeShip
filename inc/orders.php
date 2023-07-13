<?php
// if Shibe not loged in, redirect to login
if (!isset($_SESSION["shibe"])){
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?d=login";
    </script>
<?php
exit();
};
?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-file-invoice"></i> <?php echo $lang["admin_manage_orders"]; ?></h3>
                <div style="float:right"><button onclick="history.back()" type="button" class="btn btn-light"><i class="fa-solid fa-arrow-left"></i> Go Back</button></div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
<?php
// we check if there is a variable defined
if(isset($_GET["do"])){

    // We change the status
    if ($_GET["do"] == "status"){
      $d->UpdateStatus($_SESSION["shibe"],$d->CleanString($_GET["status"]),$d->CleanString($_GET["id"]));
      $_GET["do"] = NULL;
    }

    if ($_GET["do"] == "pin"){
      // We Show the private Key
?>
<div class="alert alert-secondary" role="alert">
  <i class="fa fa-lock" aria-hidden="true"></i> Ask the Buyer the <b>PIN</b> code for you to be able to access the wallet Private Key
</div>
 <form method="post" action="?d=<?php echo $_GET["d"]; ?>&id=<?php echo $_GET["id"]; ?>&do=private">
                  <input type="hidden" name="action" value="save" />
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="pin"><i class="fa-solid fa-key"></i> PIN code</label>
                        <input type="password" maxlength="255" name="pin" class="form-control is-invalid" value="" placeholder="" required="required">
                        <div id="pin" class="invalid-feedback">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Enter the <b>PIN</b> Code. Without the <b>PIN</b> the money is lost forever.
                        </div>
                      </div>
                    </div>
                    </div>
                <div><button type="submit" class="btn btn-block btn-warning" ><i class="fa fa-unlock-alt" aria-hidden="true"></i> Decrypt</button></div>
</form>

<?php
$orders = $pdo->query("SELECT * FROM generated where id = '".$d->CleanString($_GET["id"])."'")->fetch();
$products = $pdo->query("SELECT * FROM products where id = '".$orders["id_product"]."' and id_shibe = '".$_SESSION["shibe"]."' limit 1")->fetch();
if (isset($products["id"])){
?>
                  <h2 style="padding-top: 15px; padding-bottom: 15px"><i class="fa fa-paw" aria-hidden="true"></i> Buyer Details</h2>
 <div class="row">


<!--
                    <div class="col-sm-12" style="display: none">
                      <div class="form-group">
                        <label>Your <?php echo $lang["doge_address"]; ?> to recive refunds, if needed.</label>
                        <input type="text" name="doge_address" class="form-control" value="<?php if (isset($orders["doge_address"])){ echo $orders["doge_address"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["name"]; ?></label>
                        <input type="text" name="name" class="form-control" value="<?php if (isset($orders["name"])){ echo $orders["name"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
-->                    
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["email"]; ?></label>
                        <input type="email" name="email" class="form-control" value="<?php if (isset($orders["email"])){ echo $orders["email"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["country"]; ?></label>
                        <select class="form-control" name="country" disabled="disabled">
                          <option value="">----</option>
                            <?php echo $lang["countries"]; ?>
                          <?php if (isset($orders["country"])){ ?> <option value="<?php echo $orders["country"];?>" selected="selected"><?php if ($orders["country"] == ""){ echo "----"; }else{ echo $orders["country"]; }; ?></option><?php }; ?>
                        </select>
                      </div>
                    </div>
<!--                    
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["city"]; ?></label>
                        <input type="text" name="city" class="form-control" value="<?php if (isset($orders["city"])){ echo $orders["city"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["address"]; ?></label>
                        <input type="text" name="address" class="form-control" value="<?php if (isset($orders["address"])){ echo $orders["address"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["postal_code"]; ?></label>
                        <input type="text" name="postal_code" class="form-control" value="<?php if (isset($orders["postal_code"])){ echo $orders["postal_code"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["phone"]; ?></label>
                        <input type="number" name="phone" class="form-control" value="<?php if (isset($orders["phone"])){ echo $orders["phone"]; }; ?>" placeholder="" disabled="disabled">
                      </div>
                    </div>
-->                    
                  </div>
<?php }; ?>
 </div>
<?php
    };

    if ($_GET["do"] == "private"){

$orders = $pdo->query("SELECT * FROM generated where id = '".$d->CleanString($_GET["id"])."'")->fetch();
$products = $pdo->query("SELECT * FROM products where id = '".$orders["id_product"]."' and id_shibe = '".$_SESSION["shibe"]."' limit 1")->fetch();
if (isset($products["id"])){
 if (preg_match("/^[a-zA-Z0-9]+$/", openssl_decrypt($orders["doge_private"], "AES-128-CTR", $_POST["pin"], 0, 1234567891011121) )){
?>
<div class="alert alert-dark" role="alert">
  Use this private key below to be able to access your Dogecoin Wallet.<br><br>

  We suggest to use <a href="https://dogechain.info/wallet/" target="_blank">https://dogechain.info/wallet/</a> and click on "Sweep Coins" that enables you to put the private key below and you add your own Dogecoin Address and the Doge money will be sent to your own wallet or<br>
  You can install Dogecoin Core Wallet from here <a href='https://dogecoin.com/wallets/' target="_blank">dogecoin.com/wallets/</a> click on <b>File->Import Private Key</b>, insert your Private Key below and import to imidiatly have acess to your money.<br><br>
  We do not recomend leaving Doge in this wallet because is encrypted with the buyer PIN on our DataBase, so you can simple send the money to another Dogecoin Wallet that belongs to you, and will be safe.<br><br>
  Private Key: <b><?php echo openssl_decrypt($orders["doge_private"], "AES-128-CTR", $_POST["pin"], 0, 1234567891011121); ?></b>
</div>
 <?php
}else{
 ?>
<div class="alert alert-warning" role="alert">
    PIN Code is wrong. Please make sure the order and PIN code is correct. Without the correct PIN the money is lost forever without recover.
</div>
 <?php
}


}else{
?>
     <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
    </script>
<?php
}

    };
};
?>




<?php
// we list all records
if (!isset($_GET["do"])){
?>

                <table id="tabled" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th><?php echo $lang["admin_orders_id"]; ?></th>
                    <th data-priority="1"><?php echo $lang["img"]; ?></th>
                    <th><?php echo $lang["name"]; ?></th>
                    <th><?php echo $lang["doge_in_address"]; ?></th>
                    <th><?php echo $lang["email"]; ?></th>
                    <th><?php echo $lang["admin_orders_total_doge"]; ?></th>
                    <th data-priority="1"><?php echo $lang["admin_orders_status"]; ?></th>
                    <th><?php echo $lang["admin_orders_date"]; ?></th>
                    <th><?php echo $lang["options"]; ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                        $db = $pdo->query("SELECT * FROM products where id_shibe = '".$_SESSION["shibe"]."'");
                      while ($products = $db->fetch()) {


                      $ordersdb = $pdo->query("SELECT * FROM generated where id_product = '".$products["id"]."'");
                      while ($orders = $ordersdb->fetch()) {
                        $status_btn = '<i class="fa fa-paw" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["paid"]; $status_color = "success";
                        if ($orders["paid"] == 0){ $status_btn = '<i class="fa fa-hourglass-end" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["pending"]; $status_color = "warning"; };
                        if ($orders["paid"] == 2){ $status_btn = '<i class="fa fa-truck" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["sended"]; $status_color = "success"; };
                        if ($orders["paid"] == 3){ $status_btn = '<i class="fa fa-paw" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i>  ' . $lang["withdraw"]; $status_color = "light"; };                      
                        if ($orders["paid"] == 4){ $status_btn = '<i class="fa fa-handshake" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["refunded"]; $status_color = "secondary"; };
                        if ($orders["paid"] == 5){ $status_btn = '<i class="fa fa-check-circle" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["finish"]; $status_color = "light"; };
                        if ($orders["paid"] == 6){ $status_btn = '<i class="fa fa-ban" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["canceled"]; $status_color = "light"; };
                         if (isset($orders["paid"])){
                  ?>
                  <tr>
                    <td><?php echo $orders["id"];?></td>
                    <td style="text-align:center"><?php if (isset($products["imgs"]) and $products["imgs"] != ""){ $imgs = explode(",", $products["imgs"]); ?><a href="?d=product&product=<?php echo $products["id"];?>" ><img src="/fl/<?php echo $imgs[0]; ?>" style="max-height: 50px; border-radius:1rem" alt="" /></a><?php }; ?></td>
                    <td><?php echo $products["title"];?></td>
                    <td style="word-break: break-word;"><a href="https://dogechain.info/address/<?php echo $orders["doge_public"];?>" target="_blank"><?php echo $orders["doge_public"];?></a></td>
                    <td><a href="mailto:<?php echo $orders["email"]; ?>"><?php echo $orders["email"]; ?></a></td>
                    <td>&ETH; <?php echo $orders["amount"];?></td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-<?php echo $status_color; ?>" data-toggle="dropdown" style="display: flex;" ><?php echo $status_btn; ?></button>
                        <button type="button" class="btn btn-<?php echo $status_color; ?> dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                          <span class="sr-only"><?php echo $status_btn; ?></span>
                        </button>
                        <div class="dropdown-menu" role="menu" style="">
                          <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=status&status=2&id=<?php echo $orders["id"];?>"> <i class="fa fa-truck" aria-hidden="true"></i> <?php echo $lang["sended"]; ?></a>
                          <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=status&status=3&id=<?php echo $orders["id"];?>"> <i class="fa fa-paw" aria-hidden="true"></i> <?php echo $lang["withdraw"]; ?></a>                          
                          <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=status&status=4&id=<?php echo $orders["id"];?>"> <i class="fa fa-handshake" aria-hidden="true"></i> <?php echo $lang["refunded"]; ?></a>
                          <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=status&status=5&id=<?php echo $orders["id"];?>"> <i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $lang["finish"]; ?></a>
                          <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=status&status=6&id=<?php echo $orders["id"];?>"> <i class="fa fa-ban" aria-hidden="true"></i> <?php echo $lang["canceled"]; ?></a>
                        </div>
                      </div>

                    </td>
                    <td><?php echo $orders["date"];?></td>
                   <td>
                   <a class="btn btn-secondary" href="?d=<?php echo $_GET["d"]; ?>&do=pin&id=<?php echo $orders["id"];?>"><i class="far fa fa-eye nav-icon"></i> <i class="fa-solid fa-lock" aria-hidden="true"></i></a>
                   <!--<div class="btn-group">
                        <button type="button" class="btn btn-warning" data-toggle="dropdown" ><i class="far fa fa-edit nav-icon"></i><?php echo $lang["options"]; ?></button>
                        <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                          <span class="sr-only"><?php echo $lang["options"]; ?></span>
                        </button>
                        <div class="dropdown-menu" role="menu" style="">
                          
                        </div>
                      </div>-->
                    </td>
                  </tr>
                  <?php
                  };};};
                  ?>
                  </tbody>
              </table>
<?php
    };
?>
              </div>