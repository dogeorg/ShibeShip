                  <?php
                      $row = $pdo->query("SELECT * FROM products where id = '".$_GET["product"]."' limit 1")->fetch();
                      $rowc = $pdo->query("SELECT * FROM categories where id = '".$row["id_cat"]."' limit 1")->fetch();
                      $shibe = $pdo->query("SELECT * FROM shibes where id = '".$row["id_shibe"]."' limit 1")->fetch();
                          // we get the TAX for that product
                         $rowt["tax"] = 0; // we set default to zero
                         if (isset($_SESSION["country"])){ // we check if Shibe is logged in and we get only shipping from all countries or his own country
                            $rowt = $pdo->query("SELECT * FROM tax where category = '".$row["cat_tax"]."' and country = '".$_SESSION["country"]."' limit 1")->fetch();
                         }else{
                            $rowt = $pdo->query("SELECT * FROM tax where category = '".$row["cat_tax"]."' limit 1")->fetch();
                         }

                      // the moon discounts
                      if ($d->moon() == "new"){ $moon = ($row["doge"] / 100) * $row["moon_new"]; $row["doge"] = $row["doge"] - $moon; };
                      if ($d->moon() == "full"){ $moon = ($row["doge"] / 100) * $row["moon_full"]; $row["doge"] = $row["doge"] - $moon; };
                  ?>
   <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-sm-6">
              <h3 class="d-inline-block d-sm-none"><?php echo $row["title"]; ?></h3>

                          <?php if (isset($row["imgs"]) and $row["imgs"] != ""){ ?>
                            <?php
                            $imgs = explode(",", $row["imgs"]);
                            $total = count($imgs);
                            ?>
                          <?php }; ?>

              <div class="col-12">
                <img src="fl/<?php echo $imgs[0];?>" class="product-image dogepic" alt="Product Image">
              </div>
              <div class="col-12 product-image-thumbs">
                            <?php
                            for( $i=0 ; $i < $total ; $i++ ) {
                              if ($imgs[$i] != ""){
                              ?>
                                <div class="product-image-thumb <?php if ($i == 0){ echo "active"; }; ?>" ><img src="fl/<?php echo $imgs[$i];?>" alt="<?php echo $row["title"]; ?>" onmouseover="$('.dogepic').attr('src', 'fl/<?php echo $imgs[$i];?>');"></div>
                              <?php
                                };
                            };
                            ?>
              </div>
            </div>
            <div class="col-12 col-sm-6">
                <span class="badge badge-warning" style="padding: 10px; border-radius: 50px; font">by <?php echo $shibe["name"]; ?></span>
              <h3 class="my-3"><?php echo $row["title"]; ?></h3>
              <p>
              </p>

              <hr>
                          <div class="row">
                          <div class="col-6">&nbsp;<?php if ($row["moon_new"] > 0){ ?><li class="fas fa-circle"></li> <?php echo $lang["moon_new"]; ?> <?php echo $row["moon_new"];?>%<?php  }; ?>&nbsp;</div>
                          <div class="col-6" style="text-align: right">&nbsp;<?php if ($row["moon_full"] > 0){ ?><li class="far fa-circle"></li> <?php echo $lang["moon_full"]; ?> <?php echo $row["moon_full"];?>%<?php  }; ?>&nbsp;</div>
                          </div>
              <div class="bg-gray py-2 px-3 mt-4">
                <h2 class="mb-0">
                  Ð <?php if ($row["doge"] == 0){ echo $amount = $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo $amount = number_format((float)($row["doge"]), 2, '.', ''); };?>
                </h2>
                <h4 class="mt-0" style="display: none">
                  <small>Ex Tax: Ð <?php echo $row["doge"]; ?> </small>
                </h4>
              </div>

<div>
 <div class="card card-warning">
              <!-- /.card-header -->
              <div class="card-body">
                  <?php if (isset($_GET["do"])){

 // if shibe try to refresh
if (!isset($_POST["name"])){
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
    </script>
<?php
exit();
};


                  $ldoge = $LibDogecoin->generatePrivPubKeypair();
                  $d->InsertBuy($d->CleanString($row["id"]),trim($ldoge->public),trim($ldoge->private), $amount, $d->CleanString($_POST["doge_address"]),$d->CleanString($_POST["name"]),$d->CleanEmail($_POST["email"]),$d->CleanString($_POST["address"]),$d->CleanString($_POST["postal_code"]),$d->CleanString($_POST["country"]),$d->CleanString($_POST["city"]),$d->CleanString($_POST["phone"]));

                $products_email = "____________________<br>";
                $products_email .= $row["title"]." [Id: ".$row["id"]."]<br>";
                $products_email .= $row["text"]."<br>";
                $products_email .= "____________________<br>";


              // we send an email to the Shibe to send a copy to pay
              $logo = "<img src='https://shibeship.com/img/shibeship.png' style='width:100%' /><br><br>";
              $mail_subject = "Much Wow! Such Doge Payment Waiting for ".$row["title"]."!";
              $mail_message = $logo."Hello ".$d->CleanString($_POST["name"]).",<br><br>Thank you for your recent purchase. Please make the an total payment of<br><br>Ð ".$amount." <br><br>to the address<br><br> ".trim($ldoge->public)."<br><br> After payment you will recive a confirmation by the seller.<br><br>".$products_email."<br><br>Much Thanks!";
              $d->SendEmail($config["mail_name_from"],$config["email_from"],$d->CleanEmail($_POST["email"]),$mail_subject,$mail_message);


                   ?>
                    <div class="alert alert-warning" role="alert" style="">
                      Scan the QR code or copy the Doge Address to pay the exact amount of <b>Ð <?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ number_format((float)($row["doge"]), 2, '.', ''); };?></b> to buy it.
                      You can close the browser after payment.
                    </div>
                  <div class="card-body" style="text-align: center">
                            <img id="qrcode" src="//api.qrserver.com/v1/create-qr-code/?data=dogecoin:<?php echo trim($ldoge->public); ?>?amount=<?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>&amp;size=200x200" alt="" title="Such QR Code!" class="card-img-top" style="max-width: 200px">
                                <div style="font-size: 16px; font-weight: bold; background-color: #FFFFFF; border: 1px solid #999999; padding: 10px; border-radius: 50px; margin: 10px"><?php echo trim($ldoge->public); ?></div>
                  </div>
                  <?php }else{  ?>

<div class="alert alert-warning" role="alert">
  To buy it, please fill the fields below and click on <b>Much Buy</b> button below to generate a Doge Address for you to be able to pay.
</div>
                <form method="post" action="?d=<?php echo $_GET["d"]; ?>&product=<?php echo $_GET["product"]; ?>&do=buy">
                  <input type="hidden" name="action" value="save" />
                  <div class="row">
                    <div class="col-sm-12" style="display: none">
                      <div class="form-group">
                        <label>Your <?php echo $lang["doge_address"]; ?> to recive refunds, if needed.</label>
                        <input type="text" name="doge_address" class="form-control" value="<?php if (isset($row["doge_address"])){ echo $row["doge_address"]; }; ?>" placeholder="">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["name"]; ?></label>
                        <input type="text" name="name" class="form-control" value="<?php if (isset($row["name"])){ echo $row["name"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["email"]; ?></label>
                        <input type="email" name="email" class="form-control" value="<?php if (isset($row["email"])){ echo $row["email"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["country"]; ?></label>
                        <select class="form-control" name="country" required="required">
                          <option value="">----</option>
                            <?php echo $lang["countries"]; ?>
                          <?php if (isset($row["country"])){ ?> <option value="<?php echo $row["country"];?>" selected="selected"><?php if ($row["country"] == ""){ echo "----"; }else{ echo $row["country"]; }; ?></option><?php }; ?>
                        </select>
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["city"]; ?></label>
                        <input type="text" name="city" class="form-control" value="<?php if (isset($row["city"])){ echo $row["city"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["address"]; ?></label>
                        <input type="text" name="address" class="form-control" value="<?php if (isset($row["address"])){ echo $row["address"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["postal_code"]; ?></label>
                        <input type="text" name="postal_code" class="form-control" value="<?php if (isset($row["postal_code"])){ echo $row["postal_code"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["phone"]; ?></label>
                        <input type="number" name="phone" class="form-control" value="<?php if (isset($row["phone"])){ echo $row["phone"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                  </div>
                <div><button type="submit" class="btn btn-block btn-success" ><i class="far fa fa-cart-arrow-down nav-icon"></i> <?php echo $lang["buy"]; ?></button></div>
                </form>
                <?php };?>
              </div>
              <!-- /.card-body -->
            </div>

</div>

              <div class="mt-4 product-share">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" target="_blank" class="text-gray">
                  <i class="fab fa-facebook-square fa-2x"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" class="text-gray">
                  <i class="fab fa-twitter-square fa-2x"></i>
                </a>
                <a href="mailto:?subject=<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" class="text-gray">
                  <i class="fas fa-envelope-square fa-2x"></i>
                </a>
              </div>

            </div>
          </div>
          <div class="row mt-4">
            <nav class="w-100">
              <div class="nav nav-tabs" id="product-tab" role="tablist">
                <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true"><?php echo $lang["description"]; ?></a>
              </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab"> <?php echo $row["text"]; ?> </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->