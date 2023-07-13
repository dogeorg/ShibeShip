<?php
// we check if there is a variable defined
if(isset($_GET["do"])){
      // we verify and login the Sibe
      if ($_GET["do"] == "login" and isset($_POST["email"]) and isset($_POST["password"])){
                      $row = $pdo->query("SELECT id,country FROM shibes where email = '".$d->CleanEmail($_POST["email"])."' and password = '".hash('sha256', $_POST["password"])."' and active = 1 limit 1")->fetch();
                      if (isset($row["id"])){
                        $_SESSION["shibe"] = $row["id"];
                        $_SESSION["country"] = $row["country"];
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=login&d=orders";
    </script>
<?php
                      }else{
// if the login is not valid we redirect to main page
?>
          <script>
                  window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
          </script>
<?php
                      }
      }else{
// if the account is not activated we redirect to main page
        if ( $_GET["do"] == "logout"){
?>
          <script>
                  window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
          </script>
<?php
        }
    };
      // we logout the shib
      if ( $_GET["do"] == "logout"){
            $_SESSION["shibe"] = NULL;
            $_SESSION["country"] = NULL;
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=logout";
    </script>
<?php
      };

if(isset($_POST["action"])){
?>
<?php


if (isset($_FILES['banner']['name'])){
  $_POST["banner"] = $d->UploadBanner($_FILES);
};
if (!isset($_POST["banner"]) or $_POST["banner"] == ""){

  if (isset($_POST["bannerh"])){
      $_POST["banner"] = $_POST["bannerh"];
  }else{
      $_POST["banner"] = "";
  };
};

    if ( $_GET["do"] == "insert" and isset($_POST["email"])){

        // we verify if the Shibe email alredy exists
        $exists = $pdo->query("SELECT id FROM shibes where email = '".$d->CleanEmail($_POST["email"])."' limit 1")->fetch();

        if (!isset($exists["id"])){
            $d->InsertShibe($d->CleanString($_POST["name"]),$d->CleanEmail($_POST["email"]),$d->CleanString($_POST["twitter"]),$_POST["password"],$d->CleanString($_POST["tax_id"]),$d->CleanString($_POST["address"]),$d->CleanString($_POST["postal_code"]),$d->CleanString($_POST["country"]),$d->CleanString($_POST["city"]),$d->CleanString($_POST["phone"]),$d->CleanString($_POST["doge_address"]),$d->CleanString($_POST["banner"]),$_POST["text"],$d->CleanString($_POST["public_info"]),0,date("Y-m-d H:i:s"));

            $logo = "<img src='https://shibeship.com/img/shibeship.png' style='width:100%' /><br><br>";
            $mail_subject = "Much verify Shibe Account!";
            $mail_message = $logo."Hello ".$d->CleanString($_POST["name"]).",<br><br>To activate your Shibe account please <a href='https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?shibe=activate&hash=".hash('sha256', $_POST["password"])."&email=".$d->CleanEmail($_POST["email"])."'>click here</a><br><br>Much Thanks!";
            //$d->SendEmail($config["mail_name_from"],$config["email_from"],$d->CleanEmail($_POST["email"]),$mail_subject,$mail_message);
            $d->mailx($d->CleanEmail($_POST["email"]),$config["email_from"],$config["mail_name_from"],$config["email_from"],$config["email_password"],$config["email_port"],$config["email_stmp"],$mail_subject,$mail_message);    
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=verify";
    </script>
<?php
        }else{

?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=exists";
    </script>
<?php
        };
    }
?>
<?php
  // make sure there is no atempt to access this file
  if (!isset($_SESSION["shibe"])){
    ?>
        <script>
                window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=login";
        </script>
    <?php
      exit();
      };    
    if ( $_GET["do"] == "update" and isset($_SESSION["shibe"])){
 
        // we verify if the Shibe email alredy exists
        $exists = $pdo->query("SELECT id FROM shibes where id <> '".$_SESSION["shibe"]."' and email = '".$d->CleanEmail($_POST["email"])."' limit 1")->fetch();
      
        if (!isset($exists["id"])){

            $d->UpdateShibe($d->CleanString($_POST["name"]),$d->CleanEmail($_POST["email"]),$d->CleanString($_POST["twitter"]),$_POST["password"],$d->CleanString($_POST["tax_id"]),$d->CleanString($_POST["address"]),$d->CleanString($_POST["postal_code"]),$d->CleanString($_POST["country"]),$d->CleanString($_POST["city"]),$d->CleanString($_POST["phone"]),$d->CleanString($_POST["doge_address"]),$d->CleanString($_POST["banner"]),$_POST["text"],$d->CleanString($_POST["public_info"]),1,date("Y-m-d H:i:s"),$_SESSION["shibe"]);

            $_SESSION["country"] = $_POST["country"];
        };
    };

    $_GET["id"] = null; $_GET["do"] = null; $_GET["action"] = null;

?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?d=shibe&do=update";
    </script>
<?php
    exit();
};
    // add later for compilace with RGPD EU Laws
    //if ( $_GET["do"] == "remove"){
        //$d->RemoveShibe($d->CleanString($_GET["id"]));
        //$_GET["id"] = null; $_GET["do"] = null;
    //};
  // we check are going to insert a new record or update
    if ($_GET["do"] == "insert" or $_GET["do"] == "update"){
?>
<?php
  // make sure there is no atempt to access this file
  if ($_GET["do"] == "update" and !isset($_SESSION["shibe"])){
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=login";
    </script>
<?php
    exit();
};
?>
<?php
      // if we are goin to update will get only one record
      if ($_GET["do"] == "update" and isset($_SESSION["shibe"])){
                      $row = $pdo->query("SELECT * FROM shibes where id = '".$_SESSION["shibe"]."' limit 1")->fetch();
      };
?>
<!--
bs58caddr.bundle.min.js

The MIT License (MIT)

Copyright (c) 2023 Patrick Lodder

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->
<script src="js/bs58caddr.bundle.min.js"></script>
<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="far fa-solid fa-paw"></i> <?php echo $lang["shibes_insert_title"]; ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <?php if (isset($_SESSION["shibe"])){ ?>
              <a class="btn btn-block btn-secondary" style="margin-bottom: 10px;" href="https://shibeship.com/d-shop/shibe-<?php echo $_SESSION["shibe"]; ?>"><i class="far fa-solid fa-shop"></i> My Shop URL <b>https://shibeship.com/d-shop/shibe-<?php echo $_SESSION["shibe"]; ?></b></a>
              <?php } ?>

                <form method="post" id="shibeInfo" action="?d=<?php echo $_GET["d"]; ?>&do=<?php echo $_GET["do"]; ?>" ENCTYPE="multipart/form-data">
                  <input type="hidden" name="action" value="save" />
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label><?php echo $lang["doge_address"]; ?></label>
                        <input type="text" name="doge_address" id="doge_address" class="form-control" value="<?php if (isset($row["doge_address"])){ echo $row["doge_address"]; }; ?>" placeholder="">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["name"]; ?></label>
                        <input type="text" name="name" class="form-control" value="<?php if (isset($row["name"])){ echo $row["name"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["email"]; ?></label>
                        <input type="email" name="email" class="form-control" value="<?php if (isset($row["email"])){ echo $row["email"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window Twitter User</label>
                        <input type="text" name="twitter" class="form-control" value="<?php if (isset($row["twitter"])){ echo $row["twitter"]; }; ?>" placeholder="Example: dogecoin" >
                      </div>
                    </div>                    
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label><?php echo $lang["password"]; ?></label>
                        <input type="password" name="password" class="form-control" value="" placeholder="" <?php if (!isset($_GET["id"])){ ?>required="required"<?php }; ?>>
                      </div>
                    </div>
                     <div class="col-sm-6" style="display: none">
                      <div class="form-group">
                        <label><?php echo $lang["tax_id"]; ?></label>
                        <input type="text" name="tax_id" class="form-control" value="<?php if (isset($row["tax_id"])){ echo $row["tax_id"]; }; ?>" placeholder="" >
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["country"]; ?></label>
                        <select class="form-control" name="country" required="required">
                          <option value="">----</option>
                            <?php echo $lang["countries"]; ?>
                          <?php if (isset($row["country"])){ ?> <option value="<?php echo $row["country"];?>" selected="selected"><?php if ($row["country"] == ""){ echo "----"; }else{ echo $row["country"]; }; ?></option><?php }; ?>
                        </select>
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["city"]; ?></label>
                        <input type="text" name="city" class="form-control" value="<?php if (isset($row["city"])){ echo $row["city"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["address"]; ?></label>
                        <input type="text" name="address" class="form-control" value="<?php if (isset($row["address"])){ echo $row["address"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["postal_code"]; ?></label>
                        <input type="text" name="postal_code" class="form-control" value="<?php if (isset($row["postal_code"])){ echo $row["postal_code"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>
                     <div class="col-sm-6">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["phone"]; ?></label>
                        <input type="number" name="phone" class="form-control" value="<?php if (isset($row["phone"])){ echo $row["phone"]; }; ?>" placeholder="" required="required">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Shop Window <?php echo $lang["banner"]; ?> (Recommended: 2000x200)</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="banner" name="banner" multiple="multiple" accept=".jpg,.png,.gif,.webp" <?php if (!isset($row["banner"])){ ?> required="required"<?php }; ?>>
                          <label class="custom-file-label" for="banner"><?php echo $lang["banner"]; ?></label>
                        </div>
                          <?php if (isset($row["banner"]) and $row["banner"] != ""){ ?>
                            <input type="hidden" name="bannerh" value="<?php echo $row["banner"];?>" /> <br>
                                <img src="../fl/<?php echo $row["banner"];?>" style="max-width: 200px; padding: 3px; border-radius: 1rem;">
                          <?php }; ?>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label><?php echo $lang["additional_info"]; ?></label>
                        <textarea id="summernote" name="text" required="required">
                          <?php if (isset($row["text"])){ echo $row["text"]; }; ?>
                        </textarea>
                      </div>
                    </div>
                    <div class="col-sm-12" style="padding-bottom:10px; display:none">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="public_info" <?php if ($row["public_info"] == 1){ echo 'checked="checked"'; }; ?> >
                        <label class="form-check-label" for="flexCheckDefault">
                          Do you want to show on your Shop Window the Email, Address, Postal Code and Phone Number? (If not, we will only display the Shop Window Name, Twitter, Country and Banner and Adittional Info)
                        </label>
                      </div>                    
                    </div>                    
                  </div>
                <div><button type="submit" class="btn btn-block btn-success" ><i class="far fa fa-save nav-icon"></i> <?php echo $lang["save"]; ?></button></div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <script>
            // Thanks to https://twitter.com/patricklodder we can check if the DogeAddress is valid
            $( "#shibeInfo" ).submit(function( event ) {
                const dogecoinAddress = $( "#doge_address" ).val();
                if (!bs58caddr.validateCoinAddress('DOGE', dogecoinAddress)) {
                    event.preventDefault();

                    swal.fire({
                    icon: 'warning',
                    title: '<?php echo $lang["sad"]; ?>',
                    showConfirmButton: true,
                    confirmButtonColor: '#666666',
                    html:
                      '<img src="img/sad_doge.gif"><br><br>' +
                      'Sorry Shibe, Doge Address is not valid!',
                  })
                }
            });
        </script>            
<?php
    };
};

?>