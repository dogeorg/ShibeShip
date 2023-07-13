<style type="text/css">
.pin-code{
  padding: 0;
  margin: 0 auto;
  display: flex;
  justify-content:center;

}

.pin-code input {
  border: none;
  text-align: center;
  width: 48px;
  height:48px;
  font-size: 36px;
  background-color: #F3F3F3;
  margin-right:5px;
}

.pin-code input:focus {
  border: 1px solid #573D8B;
  outline:none;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.dogeemonicBTN{
  margin: 5px;
}
.dogeemonic{
  display: inline-block;
  margin: 5px;
  font-weight: bold;
}
.copied{
  font-weight: normal;
  background: #fff3cd;
  display:none;
  position:absolute;
  left:100px;
  top: 0px;
  padding: 0px;
  padding-left: 5px;
  padding-right: 5px;
  border-radius: 25px;
}
</style>
<script>
  $( document ).ready(function() {
    const dogeemonic = ["69","420","DOGECOIN","DOGE","MOON","HAPPY","COMMUNITY","KIND","KINDNESS","SMILE","PEOPLE","CURRENCY","SO","PAW","DOGELEY","HELP","POSITIVE","TRUE","GIVE","GIVEN","SHARE","LOVE","HUG","KISS","DEV","DESCENTRALIZE","SELF","CUSTODY","WALLET","KEYS","88","13","2013","NINTONDO","BILLY","JACKSON"];
    function randomDogeemonic(min, max) {
        return Math.floor(Math.random() * ((max - 1) - min + 1) + min)
    };
    pin1 = dogeemonic[randomDogeemonic(0, dogeemonic.length)];
    pin2 = dogeemonic[randomDogeemonic(0, dogeemonic.length)];
    pin3 = dogeemonic[randomDogeemonic(0, dogeemonic.length)];
    pin4 = dogeemonic[randomDogeemonic(0, dogeemonic.length)];
    pin5 = dogeemonic[randomDogeemonic(0, dogeemonic.length)];
    pin6 = dogeemonic[randomDogeemonic(0, dogeemonic.length)];
    $('#dogeemonic1').html(pin1);
    $('#dogeemonic2').html(pin2);
    $('#dogeemonic3').html(pin3);
    $('#dogeemonic4').html(pin4);
    $('#dogeemonic5').html(pin5);
    $('#dogeemonic6').html(pin6);
    $('#pin').val(pin1 + " " + pin2 + " " + pin3 + " " + pin4 + " " + pin5 + " " + pin6);
});
</script>

                  <?php
                      $row = $pdo->query("SELECT * FROM products where id = '".$_GET["product"]."' and active = 1 limit 1")->fetch();
                      if (!isset($row["id_cat"])){
                      ?>
                          <script>
                                  window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
                          </script>
                      <?php 
                      exit();                       
                      };
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
                            }else{
                              $imgs[0] = "../img/no-shibe.png";
                            }
                           ?>

              <div class="col-12" style="overflow:auto; max-height: 600px">
                <img src="/fl/<?php echo $imgs[0];?>" class="product-image dogepic" alt="<?php echo $row["title"]; ?>" style="border-radius:1rem;">
                

              </div>
              <div class="col-12 product-image-thumbs" style="padding-bottom: 20px;">
                            <?php
                            for( $i=0 ; $i < $total ; $i++ ) {
                              if ($imgs[$i] != ""){
                              ?>
                                <div class="product-image-thumb <?php if ($i == 0){ echo "active"; }; ?>" ><img src="/fl/<?php echo $imgs[$i];?>" alt="<?php echo $row["title"]; ?>" onmouseover="$('.dogepic').attr('src', 'fl/<?php echo $imgs[$i];?>');" style="border-radius:0.5rem"></div>
                              <?php
                                };
                            };
                            ?>
              </div>
            </div>
            <div class="col-12 col-sm-6">
                <span class="badge badge-light" style="padding: 10px; border-radius: 50px;"><a href="/?d=shop&shibe=<?php echo $shibe["id"]; ?>" style="color:#444">by <?php echo $shibe["name"]; ?></a><?php if (isset($shibe["twitter"])){ ?> <a href="https://twitter.com/<?php $shibe["twitter"] = str_replace("@", "", $shibe["twitter"]); echo $shibe["twitter"]; ?>" style="color:#0099FF" target="_blank"><i class="fa-brands fa-twitter"></i></a><?php }; ?></span>
              <h3 class="my-3"><?php echo $row["title"]; ?></h3>
              <p>
              </p>

              <hr>
                          <?php if ($row["moon_new"] > 0 or $row["moon_full"] > 0){ ?>
                            <div class="row">
                            <div class="col-6">&nbsp;<?php if ($row["moon_new"] > 0){ ?><li class="fas fa-circle"></li> <?php echo $lang["moon_new"]; ?> <?php echo $row["moon_new"];?>%<?php  }; ?>&nbsp;</div>
                            <div class="col-6" style="text-align: right">&nbsp;<?php if ($row["moon_full"] > 0){ ?><li class="far fa-circle"></li> <?php echo $lang["moon_full"]; ?> <?php echo $row["moon_full"];?>%<?php  }; ?>&nbsp;</div>
                            </div>
                          <?php }; ?>
              <div class="bg-gray py-2 px-3 mt-4" style="border-top-left-radius:1rem;border-top-right-radius:1rem; background-color:#f8f9fa !important; color:#444 !important;">
                <h2 class="mb-0" style="font-weight:bold !important">
                  Ð <?php if ($row["doge"] == 0){ echo $amount = $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo $amount = number_format((float)($row["doge"]), 2, '.', ''); };?>
                </h2>
                <h4 class="mt-0" style="display: none">
                  <small>Ex Tax: Ð <?php echo $row["doge"]; ?> </small>
                </h4>
              </div>

<div>
 <div class="card card-warning"  style="border-top-left-radius:0px;border-top-right-radius:0px">
              <!-- /.card-header -->
              <div class="card-body">
                  <?php if (isset($_GET["do"])){

 // if shibe try to refresh
//if (!isset($_POST["name"])){
if (!isset($_POST["email"])){
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
    </script>
<?php
exit();
};

                    $ldoge = $LibDogecoin->generatePrivPubKeypair();
                    $d->InsertBuy($row["id"],trim($ldoge->public),trim($ldoge->private), $amount, $d->CleanString($_POST["doge_address"]),$d->CleanString($_POST["name"]),$d->CleanEmail($_POST["email"]),$d->CleanString($_POST["address"]),$d->CleanString($_POST["postal_code"]),$d->CleanString($_POST["country"]),$d->CleanString($_POST["city"]),$d->CleanString($_POST["phone"]),$d->CleanString($_POST["pin"]));

                    $products_email = "____________________<br>";
                    $products_email .= $row["title"]." [Id: ".$row["id"]."]<br>";
                    $products_email .= $row["text"]."<br>";
                    $products_email .= "____________________<br>";

                    // we send an email to the Shibe to send a copy to pay
                    $logo = "<img src='https://shibeship.com/img/shibeship.png' style='width:100%' /><br><br>";
                    $mail_subject = "Much Wow! Such Doge Payment Waiting for ".$row["title"]."!";
                    //$mail_message = $logo."Hello ".$d->CleanString($_POST["name"]).",<br><br>Thank you for your recent purchase. Please make a total payment of<br><br>Ð ".$amount." <br><br>to the Dogecoin address:<br><br> <b>".trim($ldoge->public)."</b><br><br>PIN: <b>".$d->CleanString($_POST["pin"])."</b><br><br> After payment you will receive a confirmation by the seller, and wen you both agree, you have to share the <b>PIN</b> code for the seller to have access to your Doge payment, without the correct PIN the money you send is lost forever without recovery.<br><br>".$products_email."<br><br>Much Thanks!";
                    $mail_message = $logo."Hello Shibe,<br><br>Thank you for your recent purchase. Please make a total payment of<br><br>Ð ".$amount." <br><br>to the Dogecoin address:<br><br> <b>".trim($ldoge->public)."</b><br><br>PIN: <b>".$d->CleanString($_POST["pin"])."</b><br><br> After payment you will receive a confirmation by the seller, and wen you both agree, you have to share the <b>PIN</b> code for the seller to have access to your Doge payment, without the correct PIN the money you send is lost forever without recovery.<br><br>".$products_email."<br><br>Much Thanks!";
                    //$d->SendEmail($config["mail_name_from"],$config["email_from"],$d->CleanEmail($_POST["email"]),$mail_subject,$mail_message);
                    $d->mailx($d->CleanEmail($_POST["email"]),$config["email_from"],$config["mail_name_from"],$config["email_from"],$config["email_password"],$config["email_port"],$config["email_stmp"],$mail_subject,$mail_message);

                   ?>
                    <div class="alert alert-danger" role="alert" style=" margin-bottom: 10px; display:none">
                      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Make sure you store your <b>PIN</b> in a safe place before making any payment. Without the PIN, the money you send is lost forever without any recovery for you or for the seller.
                    </div>
                    <div class="alert alert-warning" role="alert" style="">
                      <i class="fa fa-qrcode" aria-hidden="true"></i> Click, Scan the QR code or copy the Doge Address to pay the exact amount of <b>Ð <?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?></b> to buy it.
                      You can close the browser after payment.
                    </div>
                  <div class="card-body" style="text-align: center">
                            <a style="cursor:pointer" href="dogecoin:<?php echo trim($ldoge->public); ?>?amount=<?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>" target="_blank"><img id="qrcode" src="//api.qrserver.com/v1/create-qr-code/?data=dogecoin:<?php echo trim($ldoge->public); ?>?amount=<?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>&amp;size=200x200" alt="" title="Such QR Code!" class="card-img-top" style="max-width: 200px"></a>
                                <div style="font-size: 16px; font-weight: bold; background-color: #FFFFFF;color:#000; border: 1px solid #999999; padding: 10px; border-radius: 50px; margin: 10px; cursor:pointer" id="dogeAddress" onclick="navigator.clipboard.writeText($('#dogeAddress').html());alert('Doge Address Copied!')"><?php echo trim($ldoge->public); ?></div>
                  </div>
<!-- We add the option to pay using MyDoge Mask (MyDoge.com) -->
<script>      
  $(document).ready(function(){

    let myDogeMask = null;

    // Listen to the window event which ensures the extension script is injected
    window.addEventListener(
    'doge#initialized',
    () => {
      myDogeMask = window.doge;

      let checkConn = async function() {
        const connectionStatusRes = await myDogeMask.getConnectionStatus();
        console.log(connectionStatusRes);
        if (!connectionStatusRes?.connected) {        
          $("#SuchConnect").show();
          $("#SuchConnect").html('Connect to MyDogeMask');
          } else {
            //$("#SuchConnect").show();
            $("#SuchConnect").hide();
            $("#MuchPay").show();
            const balanceRes = await myDogeMask.getBalance();
            console.log('balance result', balanceRes);
          }
      }

      checkConn();

    $('#SuchConnect').bind('click', async function() {
        const connectionStatusRes = await myDogeMask
            .getConnectionStatus()
              .catch(console.error);
        if (!connectionStatusRes?.connected) {
          const connectRes = await myDogeMask.connect();
              console.log(connectRes);
              if (connectRes.approved) {
                //$("#SuchConnect").html('Disconnect MyDogeMask');
                $("#SuchConnect").hide();
                $("#MuchPay").show();
              }
        } else {
              const disconnectRes = await myDogeMask.disconnect(/*onSuccess, onError*/);
              console.log('disconnect result', disconnectRes);
              if (disconnectRes.disconnected) {
                $("#SuchConnect").show();
                $("#SuchConnect").html('<i class="fa fa-paw" aria-hidden="true"></i> Connect to MyDogeMask');
                $("#MuchPay").hide();
              }
        }

    });

    $('#MuchPay').bind('click', async function() {
        const connectionStatusRes = await myDogeMask
            .getConnectionStatus()
              .catch(console.error);
        if (!connectionStatusRes?.connected) {
          return;
              } else {
              const txReqRes = await myDogeMask.requestTransaction(
                  {
                      recipientAddress: '<?php echo trim($ldoge->public); ?>',
                      dogeAmount: <?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>,
                  }
                  //onSuccess,
                // onError(console.log(error));
              );
              console.log('request transaction result', txReqRes);
      }
    });


    });
  });
</script> 
<!-- We add the option to pay using MyDoge Mask (MyDoge.com) -->
<div class="row">
<div class="btn" style="width: 100%;">
                          <button style="width: 100%;" type="button" class="btn btn-success" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-paw" aria-hidden="true"></i> Choose a Wallet to Pay</button>
                          <span class="sr-only"><i class="fa fa-paw" aria-hidden="true"></i> Choose a Wallet to Pay</span>
                        </button>
                        <div class="dropdown-menu" role="menu" style="border-radius:25px">
                          <button class="dropdown-item" id="SuchConnect"> <i class="fa fa-wallet" aria-hidden="true"></i> Connect to MyDoge Mask</button>
                          <button class="dropdown-item" id="MuchPay" style="display:none"> <i class="fa fa-wallet" aria-hidden="true"></i> MyDoge Mask</button>
                          <a class="dropdown-item" href="https://sodogetip.xyz/pay?doge=<?php echo trim($ldoge->public); ?>&amount=<?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>" target="_blank" > <i class="fa fa-wallet" aria-hidden="true"></i> SodogeTip</a>
                          <a class="dropdown-item" href="dogecoin:<?php echo trim($ldoge->public); ?>?amount=<?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>" target="_blank" > <i class="fa fa-wallet" aria-hidden="true"></i> Any Doge Wallets</a>
                        </div>
                    </div>
</div>
                  <?php }else{  ?>
                    <div id="shibeout" style="display:none">
                <div class="alert alert-warning" role="alert" style="border-radius: 2rem;">
                  <i class="fa fa-paw" aria-hidden="true"></i> To buy it, please fill the fields below and click on <b>Pay in Doge</b> button below to generate a Doge Address for you to be able to pay.
                </div>
                <form method="post" action="?d=<?php echo $_GET["d"]; ?>&product=<?php echo $_GET["product"]; ?>&do=buy" id="ShibePay">
                  <input type="hidden" name="action" value="save" />
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="pin">PIN code <div  class="alert alert-warning copied" role="alert" id="copied">Copied to Clipboard</div></label><br>
                        
                        <button type="button" class="btn btn-light dogeemonicBTN" onclick="navigator.clipboard.writeText($('#dogeemonic1').text());$( '#copied' ).fadeIn( 'slow');$( '#copied' ).fadeOut( 'slow');"><span class="badge text-bg-secondary">1</span><div class="dogeemonic" id="dogeemonic1"></div></button>
                        <button type="button" class="btn btn-light dogeemonicBTN" onclick="navigator.clipboard.writeText($('#dogeemonic2').text());$( '#copied' ).fadeIn( 'slow');$( '#copied' ).fadeOut( 'slow');"><span class="badge text-bg-secondary">2</span><div class="dogeemonic" id="dogeemonic2"></div></button>
                        <button type="button" class="btn btn-light dogeemonicBTN" onclick="navigator.clipboard.writeText($('#dogeemonic3').text());$( '#copied' ).fadeIn( 'slow');$( '#copied' ).fadeOut( 'slow');"><span class="badge text-bg-secondary">3</span><div class="dogeemonic" id="dogeemonic3"></div></button>
                        <button type="button" class="btn btn-light dogeemonicBTN" onclick="navigator.clipboard.writeText($('#dogeemonic4').text());$( '#copied' ).fadeIn( 'slow');$( '#copied' ).fadeOut( 'slow');"><span class="badge text-bg-secondary">4</span><div class="dogeemonic" id="dogeemonic4"></div></button>
                        <button type="button" class="btn btn-light dogeemonicBTN" onclick="navigator.clipboard.writeText($('#dogeemonic5').text());$( '#copied' ).fadeIn( 'slow');$( '#copied' ).fadeOut( 'slow');"><span class="badge text-bg-secondary">5</span><div class="dogeemonic" id="dogeemonic5"></div></button>
                        <button type="button" class="btn btn-light dogeemonicBTN" onclick="navigator.clipboard.writeText($('#dogeemonic6').text());$( '#copied' ).fadeIn( 'slow');$( '#copied' ).fadeOut( 'slow');"><span class="badge text-bg-secondary">6</span><div class="dogeemonic" id="dogeemonic6"></div></button>
                        <input type="hidden" name="pin" id="pin" class="form-control" value="">
                        <div id="pin" class="invalid-feedback" style="display: block !important; background-color:#f8f9fa; padding:10px">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Please carefully note down the 6-word PIN code provided above in order and store it in a secure location. Keep in mind, only upon sharing this PIN code with the seller after making a payment, will they be able to access your funds.
                        </div>
                      </div>
                    </div>
<!--
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
-->                    
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["email"]; ?></label>
                        <input type="email" name="email" class="form-control" value="<?php if (isset($row["email"])){ echo $row["email"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                    <div class="col-sm-6">
                        <?php
                          $shipto_array = json_decode($row["shipto"]);    
                          //$shippingTo =  $lang["countries"];
                          $allWorld = 0;
                          foreach ($shipto_array as &$country) {
                            $shippingTo = $shippingTo.'<option value="'.$country.'">'.$country.'</option>';
                            if ($country == "All World"){ $allWorld = 1; };
                          }
                        ?>
                      <div class="form-group">
                        <label><?php echo $lang["country"]; ?></label>
                        <select class="form-control" name="country" required="required">
                          <option value="">----</option>
                          <?php 
                          if ($allWorld == 1){
                            echo $lang["countries"];
                          }else{
                            if (isset($shippingTo)){
                              echo $shippingTo;
                            }else{
                              echo $lang["countries"];                              
                            }
                          }
                          ?>                         
                        </select>
                      </div>                                            
                    </div>
<!--                    
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
-->                    
                    <div class="col-sm-12" style="padding-bottom: 10px;">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="iCopied" name="iCopied" required="required">
                        <label class="form-check-label" for="flexCheckDefault">
                          I have copied the 6 words of the PIN code above!
                        </label>
                      </div>                    
                    </div>
                  </div>
                <div><button type="submit" class="btn btn-block btn-success" ><i class="fa-solid fa-paw nav-icon"></i> <?php echo $lang["payindoge"]; ?></button></div>
                </form>
              </div>
              <div>
              <a class="btn btn-block btn-warning btn-warning-buy" onclick=" $(this).hide();$('#shibeout').slideDown('slow');" ><i class="fa-solid fa-cart-shopping nav-icon"></i> <?php echo $lang["buy"]; ?></a>
              </div>
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

   
            <nav class="w-100" style="padding-top: 10px;">
              <div class="nav nav-tabs" id="product-tab" role="tablist">
                <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true"><?php echo $lang["description"]; ?></a>
              </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab" style="overflow: auto"> <?php echo $row["text"]; ?> </div>
            </div>
          </div>

          </div>
          <div class="row mt-4">
            <nav class="w-100">
              <div class="nav nav-tabs" id="relate-tab" role="tablist">
                <a class="nav-item nav-link active" id="product-relate-tab" data-toggle="tab" href="#product-relate" role="tab" aria-controls="product-desc" aria-selected="true"><?php echo $lang["related"]; ?></a>
              </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent" style="width:100%">
              <div class="tab-pane fade show active" id="product-relate" role="tabpanel" aria-labelledby="product-relate-tab">

              <div class="row">
                  <?php // we list all products
                      $db = $pdo->query("SELECT p.* FROM products as p JOIN categories as c on p.id_cat = c.id and c.lang = '".$_SESSION["l"]."' where p.id_cat = '".$row["id_cat"]."' and p.id <> '".$_GET["product"]."' and p.active = 1 order by p.ord ASC");
                      while ($row = $db->fetch()) {
                         // we get the TAX for that product
                         $rowt["tax"] = 0; // we set default to zero
                         if (isset($_SESSION["country"])){ // we check if Shibe is logged in and we get only shipping from all countries or his own country
                                $rowt = $pdo->query("SELECT * FROM tax where category = '".$row["cat_tax"]."' and country = '".$_SESSION["country"]."' limit 1")->fetch();
                            }else{
                                $rowt = $pdo->query("SELECT * FROM tax where category = '".$row["cat_tax"]."' limit 1")->fetch();
                            }
                  ?>
                    <?php include("product_main.php"); //we include the product design ?>
                  <?php
                  };
                    ?>
                
              </div>
        <!-- /.row -->              

              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->     