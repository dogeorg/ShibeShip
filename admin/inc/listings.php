<?php

  // make sure there is no atempt to access this file
  if (!isset($_SESSION["admin"])){
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=login";
    </script>
<?php
};
?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-list"></i> <?php echo $lang["manage_listings"]; ?></h3>
                <!--<div style="float: right"><a class="btn btn-block btn-success" href="?d=<?php echo $_GET["d"]; ?>&do=insert"><i class="far fa fa-plus-square nav-icon"></i> <?php echo $lang["insert"]; ?> Listing</a></div>-->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
<?php
// we check if there is a variable defined
if(isset($_GET["do"])){


if(isset($_POST["action"])){
    if ( $_GET["do"] == "update"){ // to do update status in admin
        //$d->UpdateProductStatus($_POST["id"]);
    };
    $_GET["id"] = null; $_GET["do"] = null; $_GET["action"] = null;
};

    if ( $_GET["do"] == "remove"){
        $d->RemoveProduct($_GET["id"]);
        $_GET["id"] = null; $_GET["do"] = null;
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
                    <th><?php echo $lang["id"]; ?></th>
                    <th><?php echo $lang["cat"]; ?></th>
                    <th><?php echo $lang["title"]; ?></th>
                    <th data-priority="1">Ð <?php echo $lang["doge"]; ?></th>
                    <th><?php echo strtoupper($config["fiat"]); ?></th>
                    <!--<th><?php echo $lang["moon_new"]; ?></th>
                    <th><?php echo $lang["moon_full"]; ?></th>-->
                    <th data-priority="1"><?php echo $lang["img"]; ?></th>
                    <th data-priority="1"><?php echo $lang["active"]; ?></th>
                    <th><?php echo $lang["options"]; ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                      $db = $pdo->query("SELECT * FROM products");
                      while ($row = $db->fetch()) {
                  ?>
                  <tr>
                    <td><?php echo $row["id"];?></td>
                    <td>
                    <?php
                      if (isset($row["id_cat"])){




                        $dbsub = $pdo->query("SELECT * FROM categories where id = '".$row["id_cat"]."' limit 1");
                        while ($rowsub = $dbsub->fetch()) {
                          //if ($rowsub["lang"] == "gb"){ $rowsub["lang"] = "EN"; };
                          if(isset($rowsub["id_cat"])){
                            $dbsubc = $pdo->query("SELECT * FROM categories where id = '".$rowsub["id_cat"]."' limit 1")->fetch();
                          };

                          ?>
                                  <span class="flag-icon flag-icon-<?php echo $rowsub["lang"];?>"></span> <?php if(isset($dbsubc["title"])){ echo $dbsubc["title"]." > "; }; ?><?php echo $rowsub["title"];?>
                          <?php
                              };
                          }else{
                            echo "---";
                          }
                        ?>
                    </td>
                    <td><?php echo $row["title"];?></td>
                    <td>Ð <?php if ($row["doge"] == 0){ echo $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ echo number_format((float)($row["doge"]), 2, '.', ''); };?> <?php if ($row["moon_new"] > 0){ ?> <li class="fas fa-circle"></li><?php }; ?><?php if ($row["moon_full"] > 0){ ?> <li class="far fa-circle"></li><?php }; ?></td>
                    <td><?php echo number_format((float)($row["fiat"]), 2, '.', '');?></td>
                    <!--<td><?php echo $row["moon_new"];?></td>
                    <td><?php echo $row["moon_full"];?></td>-->
                    <td style="text-align:center">
                    <a href="?d=<?php echo $_GET["d"]; ?>&do=update&id=<?php echo $row["id"];?>">
                        <?php if (isset($row["imgs"]) and $row["imgs"] != ""){ $imgs = explode(",", $row["imgs"]); ?><img src="../fl/<?php echo $imgs[0]; ?>" style="max-width: 50px; border-radius:1rem"><?php }else{ ?>
                        <img src="../img/no-shibe.png" style="max-width: 50px; border-radius:1rem"></div></a><?php }; ?>
                    </a>
                    </td>
                    <td style="text-align: center">
                            <?php if ($row["active"] == 1 ){ echo '<i style="color:#1e7e34" class="fa-solid fa-check"></i>'; }; ?>
                            <?php if ($row["active"] == 0 ){ echo '<i style="color:#dc3545"  class="fa-solid fa-xmark"></i>'; }; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning" data-toggle="dropdown" ><i class="far fa fa-edit nav-icon"></i> <?php echo $lang["options"]; ?></button>
                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                              <span class="sr-only"><?php echo $lang["options"]; ?></span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
<!--                              <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=update&id=<?php echo $row["id"];?>"><i class="far fa fa-edit nav-icon"></i> <?php echo $lang["update"]; ?></a>
                              <div class="dropdown-divider"></div>
-->                              
                              <a class="dropdown-item remove" href="?d=<?php echo $_GET["d"]; ?>&do=remove&id=<?php echo $row["id"];?>" ><i class="far fa fa-trash-alt nav-icon"></i> <?php echo $lang["remove"]; ?></a>
                            </div>
                        </div>
                    </td>
                  </tr>
                  <?php
                  };
                  ?>
                  </tbody>
              </table>
<?php
    };
?>
              </div>
            </div>