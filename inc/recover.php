<?php
// we check if there is a variable defined
if(isset($_GET["do"])){

if(isset($_POST["action"])){
    if ( $_GET["do"] == "password" and isset($_POST["email"])){

        // we verify if the Shibe email exists
        $row = $pdo->query("SELECT id,name,email,password FROM shibes where email = '".$d->CleanEmail($_POST["email"])."' limit 1")->fetch();

        if (isset($row["id"])){
            $password_verify = hash('sha256', $row["password"]); // we hash twice the password for security
            $mail_subject = "Much recover Shibe Password!";
            $mail_message = "Hello ".$row["name"].",<br><br>To recover your password please<br><br><a href='https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?shibe=recover&hash=".$password_verify."&email=".$d->CleanEmail($_POST["email"])."' >click here</a><br><br>Much Thanks!";

            $d->mailx($d->CleanEmail($_POST["email"]),$config["email_from"],$config["mail_name_from"],$config["email_from"],$config["email_password"],$config["email_port"],$config["email_stmp"],$mail_subject,$mail_message);
            //$d->SendEmail($config["mail_name_from"],$config["email_from"],$d->CleanEmail($_POST["email"]),$mail_subject,$mail_message);
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?shibe=recover";
    </script>
<?php
        }else{
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
    </script>
<?php
        };
    };
}else{
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>";
    </script>
<?php
};
}else{
?>
  <div class="lockscreen-item" style="margin-top:40px">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="img/logo_ss.png" alt="ShibeShip">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials" action="?d=recover&do=password" method="post">
    <input type="hidden" name="action" value="recover" />
      <div class="input-group">
        <input type="email" name="email" class="form-control" placeholder="<?php echo $lang["email"]; ?>" required="required">

        <div class="input-group-append">
          <button type="submit" class="btn">
            <i class="fas fa-arrow-right text-muted"></i>
          </button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
<?php
    };
?>