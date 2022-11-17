                    <!-- /.col-md-3 -->
                    <div class="col-lg-3">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h5 class="m-0"><?php echo $row["title"];?></h5>
                        </div>
                        <div class="card-body">
                          <?php if (isset($row["imgs"]) and $row["imgs"] != ""){ $imgs = explode(",", $row["imgs"]); ?><a href="#" ><div class="card-img-top" style="background-image: url('../fl/<?php echo $imgs[0]; ?>'); background-position: center; background-size: cover; min-width: 100%; min-height: 200px"></div></a><?php }; ?>
                          <div class="row">
                          <div class="col-6">&nbsp;<?php if ($row["moon_new"] > 0){ ?><li class="fas fa-circle"></li> <?php echo $lang["moon_new"]; ?> <?php echo $row["moon_new"];?>%<?php  }; ?>&nbsp;</div>
                          <div class="col-6" style="text-align: right">&nbsp;<?php if ($row["moon_full"] > 0){ ?><li class="far fa-circle"></li> <?php echo $lang["moon_full"]; ?> <?php echo $row["moon_full"];?>%<?php  }; ?>&nbsp;</div>
                          </div>
                          <div style="padding-top: 10px; position: relative">
                            <a href="#" class="btn btn-light" style="width: 100%; text-align: left">Ð <?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?></a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-md-3 -->