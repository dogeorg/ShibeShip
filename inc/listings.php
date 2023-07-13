<?php

  // make sure there is no atempt to access this file
  if (!isset($_SESSION["shibe"])){
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
                <div style="float: right"><a class="btn btn-block btn-success" href="?d=<?php echo $_GET["d"]; ?>&do=insert"><i class="far fa fa-plus-square nav-icon"></i> <?php echo $lang["insert"]; ?> Listing</a></div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
<?php
// we check if there is a variable defined
if(isset($_GET["do"])){


if(isset($_POST["action"])){
    if (isset($_FILES['imgs']['name'][0])){
      $_POST["imgs"] = $d->UploadFiles($_FILES);
    };
    if (!isset($_POST["imgs"]) or $_POST["imgs"] == ""){

      if (isset($_POST["imgsh"])){
          $_POST["imgs"] = $_POST["imgsh"];
      }else{
          $_POST["imgs"] = "";
      };
    };

    if ( $_GET["do"] == "insert"){
        $d->InsertProduct($_SESSION["shibe"],$_POST["id_cat"],$_POST["cat_tax"],$_POST["doge"],$_POST["fiat"],$_POST["moon_new"],$_POST["moon_full"],$_POST["qty"],$_POST["weight"],$_POST["highlighted"],$_POST["title"],$_POST["text"],$_POST["imgs"],json_encode($_POST["shipto"]),$_POST["ord"],date('Y-m-d H:i:s'),$_POST["active"]);
    }
    if ( $_GET["do"] == "update"){
        $d->UpdateProduct($_POST["id_cat"],$_POST["cat_tax"],$_POST["doge"],$_POST["fiat"],$_POST["moon_new"],$_POST["moon_full"],$_POST["qty"],$_POST["weight"],$_POST["highlighted"],$_POST["title"],($_POST["text"]),$_POST["imgs"],json_encode($_POST["shipto"]),$_POST["ord"],date('Y-m-d H:i:s'),$_POST["active"],$_POST["id"],$_SESSION["shibe"]);
    };
    $_GET["id"] = null; $_GET["do"] = null; $_GET["action"] = null;
};

    if ( $_GET["do"] == "remove"){
        $d->RemoveProduct($_GET["id"],$_SESSION["shibe"]);
        $_GET["id"] = null; $_GET["do"] = null;
    };
  // we check are going to insert a new record or update
    if ($_GET["do"] == "insert" or $_GET["do"] == "update"){

      // if we are goin to update will get only one record
      if ( $_GET["do"] == "update"){
                      $row = $pdo->query("SELECT * FROM products where id = '".$_GET["id"]."' and id_shibe = '".$_SESSION["shibe"]."' limit 1")->fetch();
      };
?>

<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-bars-staggered"></i> <?php echo $lang["listings_title"]; ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="?d=<?php echo $_GET["d"]; ?>&do=<?php echo $_GET["do"]; ?>" ENCTYPE="multipart/form-data">
                  <input type="hidden" name="action" value="save" />
                  <?php if (isset($_GET["id"])){ ?><input type="hidden" name="id" value="<?php echo $_GET["id"];?>" /><?php }; ?>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["cat"]; ?></label>                                                  
                              <select class="form-control select-flags" name="id_cat" required="required">
                              <option value="">---</option>                                 
                        <?php
                            $dbsub = $pdo->query("SELECT * FROM categories where id_cat = 0");
                            //if(isset($_GET["id"])){ $dbsub = $pdo->query("SELECT * FROM categories where id <> ".$_GET["id"]); };                            
                            while ($rowsub = $dbsub->fetch()) {
                             // if ($rowsub["lang"] == "gb"){ $rowsub["lang"] = "EN"; };
                              $dbsubc = $pdo->query("SELECT * FROM categories where id_cat = ".$rowsub["id"]);
                              //if(isset($_GET["id"])){ $dbsub = $pdo->query("SELECT * FROM categories where id <> ".$_GET["id"]); };                            
                              while ($rowsubc = $dbsubc->fetch()) {
                                $subs = 1;
                          ?>
                                  <option value="<?php echo  $rowsubc["id"];?>" data-icon="flag-icon flag-icon-<?php echo $rowsub["lang"];?>" ><?php echo $rowsub["title"]." > ".$rowsubc["title"];?></option>
                          <?php
                              }
                              if (!isset($subs)){
                                $subs = NULL;
                        ?>
                                <option value="<?php echo  $rowsub["id"];?>" data-icon="flag-icon flag-icon-<?php echo $rowsub["lang"];?>" ><?php echo $rowsub["title"];?></option>
                        <?php
                        };
                        };
                        ?>
                        <?php
                        if ($row["id_cat"] > 0 ){
                            $dbsub = $pdo->query("SELECT * FROM categories where id = '".$row["id_cat"]."' limit 1");
                            while ($rowsub = $dbsub->fetch()) {
                              //if ($rowsub["lang"] == "gb"){ $rowsub["lang"] = "EN"; };
                              if(isset($rowsub["id_cat"])){
                                $dbsubc = $pdo->query("SELECT * FROM categories where id = '".$rowsub["id_cat"]."' limit 1")->fetch();
                              };
                        ?>
                                <option value="<?php echo  $rowsub["id"];?>" selected="selected" data-icon="flag-icon flag-icon-<?php echo $rowsub["lang"];?>" ><?php if (isset($dbsubc["title"])){ echo $dbsubc["title"] . " > "; } ?><?php echo $rowsub["title"];?></option>
                        <?php
                            };
                        }else{
                          ?>
                                <option value="">---</option>
                          <?php
                        }
                        ?>
                        </select>
                        <script>
                        $(document).ready(function() {
                          function formatText (icon) {
                            return $('<span><i class="' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>');
                          };   
                          $('.select-flags').select2({
                            templateSelection: formatText,
                            templateResult: formatText
                          });
                        }); 
                        </script>                        
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["title"]; ?></label>
                        <input type="text" name="title" class="form-control" value="<?php if (isset($row["title"])){ echo $row["title"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label><?php echo $lang["text"]; ?></label>
                        <textarea id="summernote" name="text" required="required">
                          <?php if (isset($row["text"])){ echo $row["text"]; }; ?>
                        </textarea>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Ð <?php echo $lang["doge"]; ?></label>
                        <input type="number" step="any" name="doge" class="form-control" min="0" value="<?php if (isset($row["doge"])){ echo $row["doge"]; }; ?>" placeholder="0">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo strtoupper($config["fiat"]); ?> Price</label>
                        <input type="number" step="any" name="fiat" class="form-control" min="0" value="<?php if (isset($row["fiat"])){ echo $row["fiat"]; }; ?>" placeholder="0">
                      </div>
                    </div>
<!--                    <div class="col-sm-4" >
                      <div class="form-group">
                        <label><?php echo $lang["tax"]; ?> %</label>
                        <select class="form-control" name="cat_tax">
                        <?php
                            $dbsub = $pdo->query("SELECT DISTINCT category FROM tax order by category ASC");
                            while ($rowsub = $dbsub->fetch()) {
                        ?>
                                <option value="<?php echo $rowsub["category"];?>" ><?php echo $rowsub["category"];?></option>
                        <?php
                        };
                        ?>
                        <?php
                        if (isset($row["cat_tax"])){
                        ?>
                                <option value="<?php echo $row["cat_tax"];?>" selected="selected" ><?php echo $row["cat_tax"];?></option>
                        <?php
                        }else{
                          ?>
                                <option value="" selected="selected" >---</option>
                          <?php
                        }
                        ?>
                                <option value="" >0</option>                        
                        </select>
                      </div>    
                    </div>
-->                                    
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label><?php echo $lang["qty"]; ?></label>
                        <input type="number" name="qty" class="form-control" min="0" value="<?php if (isset($row["qty"])){ echo $row["qty"]; }else{ echo "0"; }; ?>" placeholder="0">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label><li class="fas fa-circle"></li> <?php echo $lang["moon_new"]; ?> <?php echo $lang["discount"]; ?></label>
                        <input type="number" step="any" name="moon_new" class="form-control" min="0" value="<?php if (isset($row["moon_new"])){ echo $row["moon_new"]; }else{ echo "0.00"; }; ?>" placeholder="0">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label><li class="far fa-circle"></li> <?php echo $lang["moon_full"]; ?> <?php echo $lang["discount"]; ?></label>
                        <input type="number" step="any" name="moon_full" class="form-control" min="0" value="<?php if (isset($row["moon_full"])){ echo $row["moon_full"]; }else{ echo "0.00"; }; ?>" placeholder="0">
                      </div>
                    </div>
<!--
                    <div class="col-sm-4" style="display: none">
                      <div class="form-group">
                        <label><?php echo $lang["weight"]; ?></label>
                        <input type="number" step="any" name="weight" class="form-control" min="0" value="<?php if (isset($row["weight"])){ echo $row["weight"]; }else{ echo "0"; }; ?>" placeholder="0">
                      </div>
                    </div>

                    <div class="col-sm-4" style="display: none">
                      <div class="form-group">
                        <label><?php echo $lang["highlighted"]; ?></label>
                        <select class="form-control" name="highlighted">
                          <option value="1" ><?php echo $lang["active"]; ?></option>
                          <option value="0" ><?php echo $lang["disable"]; ?></option>
                          <?php if (isset($row["highlighted"])){ ?>
                          <option value="<?php echo $row["highlighted"]; ?>" selected="selected" >
                            <?php if ($row["active"] == 0 ){ echo $lang["disable"]; }; ?>
                            <?php if ($row["active"] == 1 ){ echo $lang["active"]; }; ?>
                          </option>
                          <?php };?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4" style="display: none">
                      <div class="form-group">
                        <label><?php echo $lang["ord"]; ?></label>
                        <input type="number" name="ord" class="form-control" min="0" value="<?php if (isset($row["ord"])){ echo $row["ord"]; }else{ echo "0"; }; ?>" placeholder="0">
                      </div>
                    </div>
<div class="col-sm-12" style="overflow:scroll; max-height: 200px; margin-bottom:10px">                    
-->
                    
                    <div class="col-sm-12" style="margin-bottom:10px">
                        <label><?php echo $lang["shipto"]; ?></label>
                        <?php
                          $shipto_array = json_decode($row["shipto"]);
                        ?>                        
                        <?php
                        //$shippingTo = str_replace('<option value="', '<div class="form-check"><input id="flexCheckShip" class="form-check-input" type="checkbox" name="shipto[]" value="', $lang["countries"]);
                        //$shippingTo = str_replace('">', '"><label class="form-check-label">', $shippingTo);
                        //$shippingTo = str_replace('</option>', '</label></div>', $shippingTo);
                        $selected = "";
                        //$checked = "";
                        foreach ($shipto_array as &$country) {
                          //$shippingTo = str_replace('value="'.$country, 'value="'.$country.'" checked="checked"', $shippingTo);
                          $lang["countries"] = str_replace('value="'.$country.'"', 'value="'.$country.'" selected', $lang["countries"]);
                          if ($country == $lang["all_world"]){ /*$checked = 'checked="checked"';*/ $selected = "selected"; };
                        }

                        if (!isset($_GET["id"])){ $selected = "selected"; };   // we by default activate the All World Shipping wen adding a new one

                        ?>
                            <!--<div class="form-check"><label class="form-check-label"><input id="flexCheckShip" class="form-check-input" type="checkbox" name="shipto[]" value="All World" <?php echo $checked; ?>><label class="form-check-label">All World</label></label></div>                        -->
                        <?php                        
                          //echo $shippingTo;
                        ?>
                          <style>
                            .select2-search__field{
                              width: 100% !important;
                              min-height: 33px !important;
                              border: none !important;
                            }
                            .select2-selection__choice{
                              padding: 0 20px !important;
                              color: black !important;
                              background-color: #ffc107 !important;
                            }
                            .select2-selection__choice__remove:hover{
                            background-color: #DFA800 !important;
                            color: #444 !important;  
                            }
                          </style>
                        <select class="select-countries form-control" name="shipto[]" multiple="multiple">
                          <option value="<?php echo $lang["all_world"]; ?>" <?php echo $selected; ?>><?php echo $lang["all_world"]; ?></option>
                          <?php echo $lang["countries"];?>
                        </select>
                        <script>
                          $(document).ready(function() {
                            $('.select-countries').select2();
                          }); 
                        </script>                           
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["img"]; ?></label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="imgs" name="imgs[]" multiple="multiple" accept=".jpg,.png,.gif,.webp" <?php if (!isset($row["imgs"])){ ?> required="required"<?php }; ?>>
                          <label class="custom-file-label" for="img"><?php echo $lang["img"]; ?></label>
                        </div>
                          <?php if (isset($row["imgs"]) and $row["imgs"] != ""){ ?>
                            <input type="hidden" name="imgsh" value="<?php echo $row["imgs"];?>" /> <br>
                            <?php
                            $imgs = explode(",", $row["imgs"]);
                            $total = count($imgs);
                            for( $i=0 ; $i < $total ; $i++ ) {
                              if ($imgs[$i] != ""){
                              ?>
                               <img src="../fl/<?php echo $imgs[$i];?>" style="max-width: 100px; padding: 3px; border-radius:1rem; padding:10px">
                              <?php
                                };
                            };
                            ?>
                          <?php }; ?>
                      </div>
                    </div>
                    <?php if (!isset($_GET["id"])){ $row["active"] = 1; }; // we by default activate the listing wen adding a new one ?>
                    <div class="col-sm-6">
                      <div class="form-group">
                      <label><?php echo $lang["active"]; ?></label>                        
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                          <input name="active" value="1" type="checkbox" class="custom-control-input" id="activelist" <?php if ($row["active"] == 1 ){ echo "checked"; }; ?>>
                          <label class="custom-control-label" for="activelist"></label>
                        </div>                      
                      </div>
                    </div>                    
                  </div>
                <div><button type="submit" class="btn btn-block btn-success" ><i class="far fa fa-save nav-icon"></i> <?php echo $lang["save"]; ?></button></div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>


<?php
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
                      $db = $pdo->query("SELECT * FROM products where id_shibe = '".$_SESSION["shibe"]."'");
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
                              <a class="dropdown-item" href="?d=<?php echo $_GET["d"]; ?>&do=update&id=<?php echo $row["id"];?>"><i class="far fa fa-edit nav-icon"></i> <?php echo $lang["update"]; ?></a>
                              <div class="dropdown-divider"></div>
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