                    <!-- /.col-md-3 -->
                    <?php $shibe = $pdo->query("SELECT * FROM shibes where id = '".$row["id_shibe"]."' limit 1")->fetch(); ?>
                    <div class="col-md-3">
                      <div class="card card-primary cardShibe">
                        <div class="card-body" style="overflow:hidden; padding:0px">
                        <span class="badge badge-light" style="border-radius: 50px; position:absolute; right:10px; top: 10px"><a href="/?d=shop&shibe=<?php echo $shibe["id"]; ?>" style="color:#444">by <?php echo $shibe["name"]; ?></a><?php if (isset($shibe["twitter"])){ ?> <a href="https://twitter.com/<?php $shibe["twitter"] = str_replace("@", "", $shibe["twitter"]); echo $shibe["twitter"]; ?>" style="color:#0099FF" target="_blank"><i class="fa-brands fa-twitter"></i></a><?php }; ?></span>
                          <?php if (isset($row["imgs"]) and $row["imgs"] != ""){ $imgs = explode(",", $row["imgs"]); ?><a href="/?d=product&product=<?php echo $row["id"];?>"><div class="card-img-top" style="background-image: url('/fl/<?php echo $imgs[0]; ?>'); background-position: center; background-size: cover; min-width: 100%; min-height: 150px; border-top-left-radius:0.5rem; border-top-right-radius:0.5rem"></div></a><?php }else{ ?>
                            <a href="/?d=product&product=<?php echo $row["id"];?>"><div class="card-img-top" style="background-image: url('/img/no-shibe.png'); background-position: center; background-size: cover; min-width: 100%; min-height: 150px; border-top-left-radius:0.5rem; border-top-right-radius:0.5rem"></div></a>
                          <?php }; ?>                          
                          <div style="padding:1.25rem; padding-top:10px">
                            <div class="row">
                              <div><?php echo substr($row["title"], 0, 20); if (strlen($row["title"]) > 20) { echo "..."; }; ?></div>
                            </div>                            
                                              
                            <!--<div class="row" style="min-height: 50px; display: none;">
                              <div class="col-6">&nbsp;<?php if ($row["moon_new"] > 0){ ?><li class="fas fa-circle"></li> <?php echo $lang["moon_new"]; ?> -<?php echo $row["moon_new"];?>%<?php  }; ?>&nbsp;</div>
                              <div class="col-6" style="text-align: right;">&nbsp;<?php if ($row["moon_full"] > 0){ ?><li class="far fa-circle"></li> <?php echo $lang["moon_full"]; ?> -<?php echo $row["moon_full"];?>%<?php  }; ?>&nbsp;</div>
                            </div>-->

                            <div class="row">                            
                              <!--<div class="col-3">
                                <a href="/?d=product&product=<?php echo $row["id"];?>" style="" ><i class="fas fa fa-shopping-cart"></i><?php echo $lang["buy"]; ?></a>
                              </div>-->
                              <div>                         
                                  <b>Ð</b> <?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?>
                                  <?php if ($row["moon_new"] > 0){ ?> <li class="fas fa-circle"></li><?php }; ?><?php if ($row["moon_full"] > 0){ ?> <li class="far fa-circle"></li><?php }; ?>
                              </div>                            
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-md-3 -->