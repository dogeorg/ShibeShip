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
                <h3 class="card-title"><?php echo $lang["admin_manage_orders"]; ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
<?php
// we check if there is a variable defined
if(isset($_GET["do"])){

    if ($_GET["do"] == "pin"){
      // We Show the private Key
?>
<div class="alert alert-warning" role="alert">
  <i class="fa fa-lock" aria-hidden="true"></i> Ask the Buyer the <b>PIN</b> code for you to be able to access the wallet Private Key
</div>
 <form method="post" action="?d=<?php echo $_GET["d"]; ?>&id=<?php echo $_GET["id"]; ?>&do=private">
                  <input type="hidden" name="action" value="save" />
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="pin">PIN code</label>
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
    };

    if ($_GET["do"] == "private"){

$orders = $pdo->query("SELECT * FROM generated where id = '".$d->CleanString($_GET["id"])."'")->fetch();
$products = $pdo->query("SELECT * FROM products where id = '".$orders["id_product"]."' and id_shibe = '".$_SESSION["shibe"]."' limit 1")->fetch();
if (isset($products["id"])){

?>
<div class="alert alert-dark" role="alert">
  Use this private key to be able to access your Dogecoin Wallet. We recomend to move the Dogecoin out of this wallet to anouther wallet. Privbate Key: <?php echo openssl_decrypt($orders["doge_private"], "AES-128-CTR", $_POST["pin"], 0, 1234567891011121); ?>
</div>


<?php
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
                    <th>Image</th>
                    <th>Title</th>
                    <th><?php echo $lang["doge_in_address"]; ?></th>
                    <th><?php echo $lang["admin_orders_total_doge"]; ?></th>
                    <th><?php echo $lang["admin_orders_status"]; ?></th>
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
                        $status_btn = $lang["sended"]; $status_color = "success";
                        if ($orders["paid"] == 0){ $status_btn = $lang["pending"]; $status_color = "warning"; };
                         if (isset($orders["paid"])){
                  ?>
                  <tr>
                    <td><?php echo $orders["id"];?></td>
                    <td><?php if (isset($products["imgs"]) and $products["imgs"] != ""){ $imgs = explode(",", $products["imgs"]); ?><a href="?d=product&product=<?php echo $products["id"];?>" ><img src="/fl/<?php echo $imgs[0]; ?>" style="max-height: 50px" alt="" /></a><?php }; ?></td>
                    <td><?php echo $products["title"];?></td>
                    <td style="word-break: break-word;"><?php echo $orders["doge_public"];?></td>
                    <td>&ETH; <?php echo $orders["amount"];?></td>
                    <td><span class="btn btn-block btn-<?php echo $status_color; ?>"><?php echo $status_btn; ?></span></td>
                    <td><?php echo $orders["date"];?></td>
                   <td>
                    <div class="btn-group">
                    <button type="button" class="btn btn-warning" data-toggle="dropdown" ><i class="far fa fa-edit nav-icon"></i> <?php echo $lang["options"]; ?></button>
                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only"><?php echo $lang["options"]; ?></span>
                    </button>
                    <div class="dropdown-menu" role="menu" style="">
                      <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=pin&id=<?php echo $orders["id"];?>"><i class="far fa fa-eye nav-icon"></i> <i class="far fa fa-lock" aria-hidden="true"></i> <?php echo $lang["view"]; ?></a>
                    </div>
                  </div>

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
            </div>