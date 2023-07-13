        <div class="row">
          <?php
          $b = $pdo->query("SELECT * from shibes where id = '".$_GET["shibe"]."' and active = 1 limit 1")->fetch();
          if (isset($b["banner"])){
            $b["twitter"] = str_replace("@", "", $b["twitter"]);
          ?>
          <div class="col" style="background-image: url('/fl/<?php echo $b["banner"]; ?>'); background-position: center; background-size: cover; min-width: 100%; min-height: 200px; border-radius:0.5rem;"></div>
          <?php }; ?>

          <div class="accordion" id="accordionShibe" style="width:100%">
            <div class="card">
              <div class="card-header" id="heading1">
                <h2 class="mb-0">
                  <button class="alert alert-light" style="display:inline; font-size:20px; width:100%"  type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                  <i class="fa fa-paw" aria-hidden="true"></i> Much welcome to <b><?php echo $b["name"]; ?></b> <?php if(isset($b["twitter"])){ ?><a href="https://twitter.com/<?php echo $b["twitter"]; ?>" style="color:#0099FF" target="_blank"><i class="fa-brands fa-twitter"></i></a><?php }; ?> Shop
                  </button>
                </h2>
              </div>

              <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordionShibe">
                <div class="card-body">
                  <div class="row">
                  <?php if(isset($b["text"])){ ?><div class="col"><?php echo $b["text"]; ?></div><?php };?>
                    <div class="col" >
                      <?php
                      if (isset($b["doge_address"])){
                      ?>  
                        <div class="card-body" style="text-align: center; max-width:350px; float:right" id="tips">
                          <div><b>Tips</b></div>
                          <a href="dogecoin:<?php echo $b["doge_address"]; ?>?amount=1" target="_blank" style="cursor:pointer"><img id="qrcode" src="//api.qrserver.com/v1/create-qr-code/?data=dogecoin:<?php echo $b["doge_address"]; ?>?amount=1&amp;size=200x200" alt="" title="Such QR Code!" class="card-img-top" style="max-width: 200px"></a>
                          <div style="font-size: 12px; font-weight: bold; background-color: #FFFFFF;color:#000; border: 1px solid #999999; padding: 10px; border-radius: 50px; margin: 10px; cursor:pointer" id="dogeAddress" onclick="navigator.clipboard.writeText($('#dogeAddress').html());alert('Doge Address Copied!')"><?php echo $b["doge_address"]; ?></div>
                        </div>
                      <?php }; ?>
                    </div>
                </div>
                </div>
              </div>
            </div>  
          </div>          


        </div>
        
        <div class="row">
                  <?php // we list all products
                      $db = $pdo->query("SELECT p.* FROM products as p JOIN categories as c on p.id_cat = c.id and c.lang = '".$_SESSION["l"]."' where p.id_shibe = '".$_GET["shibe"]."' and p.active = 1 order by p.ord ASC");
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