<?php
/**
*   File: Functions used on the HappyShibes
*   Author: A lot of good shibes
*   Description: Real use case of the Dogecoin BlockChain to sell products and services
*   License: Well, do what you want with this, be creative, you have the wheel, just reenvent and do it better! Do Only Good Everyday
*/

// We define the root path
define('ROOTPATH', __DIR__);

// Include the LibDogecoin
require_once ROOTPATH.'/vendors/libdogecoin-php/libdogecoin-bind.php';

// Add the PDO DB Connection
$db = 'mysql:host='.$config["dbhost"].';dbname='.$config["dbname"];
$opt = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false, ];
try {
    $pdo = new PDO($db, $config["dbuser"], $config["dbpass"], $opt);
}
catch (PDOException $e) {
    echo '<div style="top: 50%;left: 50%; position: absolute;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);font-family: Comic Sans MS;word-break: break-all;max-width:416px"><img src="img/sad_doge.gif"><br>Sorry Shibe, there is an temporary problem and we are working on it! I will try to check in 5 seconds.</div>'; header("Refresh:5"); exit();
};

// class DogeBridge to be able to interact beetwin DB and Dogecoin
class DogeBridge {

    private $LibDogecoin;     // include LibDogecoin Bindings
    private $pdo;     // include PDO connections
    private $config;     // include PDO connections
    public function __construct($LibDogecoin, $pdo,$config) {
        $this->LibDogecoin = $LibDogecoin;
        $this->pdo = $pdo;
        $this->config = $config;
    }


//// LibDoge Address ////
  // Add Buy
  public function InsertBuy($id_product,$doge_public,$doge_private,$amount,$doge_address,$name,$email,$address,$postal_code,$country,$city,$phone,$pin)
    {

      $this->pdo->query("INSERT INTO `generated` (
      `id_product`,
      `doge_public`,
      `doge_private`,
      `amount`,
      `doge_address`,
      `name`,
      `email`,
      `address`,
      `postal_code`,
      `country`,
      `city`,
      `phone`,
      `date`
      ) VALUES (
      '".$id_product."',
      '".$doge_public."',
      '".$this->EncryptPrivate($pin,$doge_private)."',
      '".$amount."',
      '".$doge_address."',
      '".$name."',
      '".$email."',
      '".$address."',
      '".$postal_code."',
      '".$country."',
      '".$city."',
      '".$phone."',
      '".date("Y-m-d H:i:s")."'
      );");

      return null;
    }

  // update Doge Address Amount
  public function UpdateBuy($id_product,$amount)
    {

      $this->pdo->query("UPDATE generated SET
      id_product = '".$id_product."',
      amount = '".$amount."'
      WHERE id = '".$id."' limit 1");

      return null;
    }

  // update Doge status to Paid and Quantity
  public function UpdatePaidBuy($id,$qty,$id_product)
    {
      // we update the quantity and disable the listing if none
      if ($qty == 1){
        $this->pdo->query("UPDATE products SET
        qty = ($qty - 1),
        active = 0
        WHERE id = '".$id_product."' limit 1");
      }else{
        if ($qty > 0){
          $this->pdo->query("UPDATE products SET
          qty = ($qty - 1)
          WHERE id = '".$id_product."' limit 1");        
        }
      }

      // we update the status
      $this->pdo->query("UPDATE generated SET
      paid = '1'
      WHERE id = '".$id."' limit 1");

      return null;
    }

  // update Doge status
  public function UpdateStatus($id_shibe,$status,$id)
    {

        // we verify if the Shibe changing the order is the owner
        $verify = $this->pdo->query("SELECT id FROM products where id_shibe = '".$id_shibe."' and id = '".$id."' limit 1")->fetch();
      
        if (!isset($verify["id"])){
          $this->pdo->query("UPDATE generated SET
          paid = $status
          WHERE id = '".$id."' limit 1");
        };

      return null;
    }    

  // Encrypt Doge Private with a pin from the buyer
  public function EncryptPrivate($pin,$doge_private)
    {
      return openssl_encrypt($doge_private, "AES-128-CTR", $pin, 0, 1234567891011121);;
    }

  // Decrypt Doge Private with a pin from the buyer
  public function DecryptPrivate($pin,$doge_private)
    {
      return openssl_decrypt($doge_private, "AES-128-CTR", $pin, 0, 1234567891011121);
    }

//// Pages /////////
  // Add page
  public function InsertPage($lang,$id_page,$type,$title,$text,$ord,$active)
    {

      $this->pdo->query("INSERT INTO `pages` (
      `lang`,
      `id_page`,
      `type`,
      `title`,
      `text`,
      `ord`,
      `active`
      ) VALUES (
      '".$lang."',
      '".$id_page."',
      '".$type."',
      '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$ord."',
      '".$active."'
      );");

      return null;
    }

  // update an existent page
  public function UpdatePage($lang,$id_page,$type,$title,$text,$ord,$active,$id)
    {

      $this->pdo->query("UPDATE pages SET
      lang = '".$lang."',
      id_page = '".$id_page."',
      type = '".$type."',
      title = '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      text = '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      ord = '".$ord."',
      active = '".$active."'
      WHERE id = '".$id."' limit 1");

      return null;
    }

  // Reemoves page
  public function RemovePage($id)
    {

      $this->pdo->query("DELETE FROM pages where id='".$id."' limit 1");

      return null;
    }
//// END Pages /////////

//// Banners /////////
  // Add Banner
  public function InsertBanner($lang,$id_cat,$id_prod,$id_page,$img,$video,$link,$ord,$active)
    {

      $this->pdo->query("INSERT INTO `banners` (
      `lang`,
      `id_cat`,
      `id_prod`,
      `id_page`,
      `img`,
      `video`,
      `link`,
      `ord`,
      `active`
      ) VALUES (
      '".$lang."',
      '".$id_cat."',
      '".$id_prod."',
      '".$id_page."',
      '".$img."',
      '".$video."',
      '".$link."',
      '".$ord."',
      '".$active."'
      );");

      return null;
    }

  // upload files
  public function UploadFile($file)
  {
    $uploaddir = ROOTPATH."/../fl";
    $random = date("mdyHis");
    if (isset($file['name'])){
      if(is_uploaded_file($file['tmp_name'])){
        $img = str_replace(' ', '',$random.$file['name']);
        move_uploaded_file($file['tmp_name'],$uploaddir.'/'.str_replace(' ', '',$random.$file['name']));
      };
    };
    if (!isset($img)){ $img = ""; };

    return $img;

  }

  // upload multi files
  public function UploadFiles($files)
  {

    $targetDirectory = ROOTPATH."/../fl/";
    $total = count($files["imgs"]["name"]);
    $imgs = "";
    $maxFileSize = 4 * 1024 * 1024;
    for( $i=0 ; $i < $total ; $i++ ) {
    $imageFile = $files['imgs']['tmp_name'][$i];
    $imageFileName = $files['imgs']['name'][$i];

    // Check file size
    if ($files['imgs']['size'][$i] > $maxFileSize) {
       // echo 'Error: File size exceeds the maximum allowed size.';
       return NULL;
      exit;
    }

    // Check if the file is an image
    $imageInfo = getimagesize($imageFile);
    if (!$imageInfo) {
        //echo 'Error: Invalid image file.';
        return NULL;
        exit;
    }

    // Determine the file extension
    $imageFileType = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));

    // Generate a unique file name
    $newFileName = uniqid('shibeShip_') . '.' . $imageFileType;
    $imgs = $imgs . str_replace(' ', '',$newFileName) . ",";

    // Set the target path
    $targetPath = $targetDirectory . $newFileName;

    // Resize and compress the image using GD library
    $maxWidth = 800; // Maximum width of the resized image
    $quality = 80; // Image quality (0-100)

    list($srcWidth, $srcHeight) = $imageInfo;
    $ratio = $srcWidth / $srcHeight;

    if ($srcWidth > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;
    } else {
        $newWidth = $srcWidth;
        $newHeight = $srcHeight;
    }

    $srcImage = imagecreatefromstring(file_get_contents($imageFile));
    $dstImage = imagecreatetruecolor($newWidth, $newHeight);

    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

    if ($imageFileType === 'jpeg' || $imageFileType === 'jpg') {
        imagejpeg($dstImage, $targetPath, $quality);
    } elseif ($imageFileType === 'png') {
        imagepng($dstImage, $targetPath, floor($quality / 10));
    } elseif ($imageFileType === 'gif') {
        imagegif($dstImage, $targetPath);
    } elseif ($imageFileType === 'webp') {
        imagewebp($dstImage, $targetPath);
  }

    imagedestroy($srcImage);
    imagedestroy($dstImage);
  
  };

    if (!isset($imgs)){ $imgs = ""; };

    return $imgs;

  }

    // upload banner image
    public function UploadBanner($files)
    {
  
      $targetDirectory = ROOTPATH."/../fl/";
      $banner = "";
      $maxFileSize = 4 * 1024 * 1024;

      $imageFile = $files['banner']['tmp_name'];
      $imageFileName = $files['banner']['name'];
  
      // Check file size
      if ($files['banner']['size'] > $maxFileSize) {
         // echo 'Error: File size exceeds the maximum allowed size.';
         return NULL;
        exit;
      }
  
      // Check if the file is an image
      $imageInfo = getimagesize($imageFile);
      if (!$imageInfo) {
          //echo 'Error: Invalid image file.';
          return NULL;
          exit;
      }
  
      // Determine the file extension
      $imageFileType = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));
  
      // Generate a unique file name
      $newFileName = uniqid('banner_') . '.' . $imageFileType;
      $banner = $newFileName;
  
      // Set the target path
      $targetPath = $targetDirectory . $newFileName;
  
      // Resize and compress the image using GD library
      $maxWidth = 2000; // Maximum width of the resized image
      $quality = 80; // Image quality (0-100)
  
      list($srcWidth, $srcHeight) = $imageInfo;
      $ratio = $srcWidth / $srcHeight;
  
      if ($srcWidth > $maxWidth) {
          $newWidth = $maxWidth;
          $newHeight = $maxWidth / $ratio;
      } else {
          $newWidth = $srcWidth;
          $newHeight = $srcHeight;
      }
  
      $srcImage = imagecreatefromstring(file_get_contents($imageFile));
      $dstImage = imagecreatetruecolor($newWidth, $newHeight);
  
      imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
  
      if ($imageFileType === 'jpeg' || $imageFileType === 'jpg') {
          imagejpeg($dstImage, $targetPath, $quality);
      } elseif ($imageFileType === 'png') {
          imagepng($dstImage, $targetPath, floor($quality / 10));
      } elseif ($imageFileType === 'gif') {
          imagegif($dstImage, $targetPath);
      }
  
      imagedestroy($srcImage);
      imagedestroy($dstImage);
    
      if (!isset($banner)){ $banner = ""; };
  
      return $banner;
  
    }


  // update an existent banner
  public function UpdateBanner($lang,$id_cat,$id_prod,$id_page,$img,$video,$link,$ord,$active,$id)
    {

      $this->pdo->query("UPDATE banners SET
      lang = '".$lang."',
      id_cat = '".$id_cat."',
      id_prod = '".$id_prod."',
      id_page = '".$id_page."',
      img = '".$img."',
      video = '".$video."',
      link = '".$link."',
      ord = '".$ord."',
      active = '".$active."'
      WHERE id = '".$id."' limit 1");

      return null;
    }

  // Reemoves banner
  public function RemoveBanner($id)
    {

      $this->pdo->query("DELETE FROM banners where id='".$id."' limit 1");

      return null;
    }
//// END Banners /////////


//// Categories /////////
  // Add Category
  public function InsertCategory($lang,$id_cat,$icon,$title,$text,$img,$ord,$active)
    {
      $this->pdo->query("INSERT INTO `categories` (
      `lang`,
      `id_cat`,
      `icon`,
      `title`,
      `text`,
      `img`,
      `ord`,
      `active`
      ) VALUES (
      '".$lang."',
      '".$id_cat."',
      '".$icon."',
      '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$img."',
      '".$ord."',
      '".$active."'
      );");

      return null;
    }

  // update an existent category
  public function UpdateCategory($lang,$id_cat,$icon,$title,$text,$img,$ord,$active,$id)
    {

      $this->pdo->query("UPDATE categories SET
      lang = '".$lang."',
      id_cat = '".$id_cat."',
      icon = '".$icon."',
      title = '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      text = '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      img = '".$img."',
      ord = '".$ord."',
      active = '".$active."'
      WHERE id = '".$id."' limit 1");

      return null;
    }

  // Reemoves category
  public function RemoveCategory($id)
    {

     $this->pdo->query("DELETE FROM categories where id='".$id."' limit 1");

      return null;
    }
//// END Categories /////////


//// Products /////////
  // Add Product
  public function InsertProduct($id_shibe,$id_cat,$cat_tax,$doge,$fiat,$moon_new,$moon_full,$qty,$weight,$highlighted,$title,$text,$imgs,$shipto,$ord,$date,$active)
    {

     $this->pdo->query("INSERT INTO `products` (
      `id_shibe`,
      `id_cat`,
      `cat_tax`,
      `doge`,
      `fiat`,
      `moon_new`,
      `moon_full`,
      `qty`,
      `weight`,
      `highlighted`,
      `title`,
      `text`,
      `imgs`,
      `shipto`,
      `ord`,
      `date`,
      `active`
      ) VALUES (
      '".$id_shibe."',
      '".$id_cat."',
      '".filter_var($cat_tax, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$doge."',
      '".$fiat."',
      '".$moon_new."',
      '".$moon_full."',
      '".$qty."',
      '".$weight."',
      '".$highlighted."',
      '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$imgs."',
      '".$shipto."',
      '".$ord."',
      '".$date."',
      '".$active."'
      );");

      return null;
    }

  // update an existent Product
  public function UpdateProduct($id_cat,$cat_tax,$doge,$fiat,$moon_new,$moon_full,$qty,$weight,$highlighted,$title,$text,$imgs,$shipto,$ord,$date,$active,$id,$id_shibe)
    {

      $this->pdo->query("UPDATE products SET
      id_cat = '".$id_cat."',
      cat_tax = '".filter_var($cat_tax, FILTER_SANITIZE_ADD_SLASHES)."',
      doge = '".$doge."',
      fiat = '".$fiat."',
      moon_new = '".$moon_new."',
      moon_full = '".$moon_full."',
      qty = '".$qty."',
      weight = '".$weight."',
      highlighted = '".$highlighted."',
      title = '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      text = '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      imgs = '".$imgs."',
      shipto = '".$shipto."',
      ord = '".$ord."',
      date = '".$date."',
      active = '".$active."'
      WHERE id = '".$id."' and id_shibe = '".$id_shibe."' limit 1");

      return;
    }
    

  // Removes Product
  public function RemoveProduct($id,$id_shibe)
    {

      $this->pdo->query("DELETE FROM products where id='".$id."' and id_shibe = '".$id_shibe."' limit 1");

      return null;
    }
//// END Products /////////

//// Shibes /////////
  // Add Shibe
  public function InsertShibe($name,$email,$twitter,$password,$tax_id,$address,$postal_code,$country,$city,$phone,$doge_address,$banner,$text,$public_info,$active,$date)
    {
      // we encrypt the password
      $password = hash('sha256', $password);

      $this->pdo->query("INSERT INTO `shibes` (
      `name`,
      `email`,
      `twitter`,
      `password`,
      `tax_id`,
      `address`,
      `postal_code`,
      `country`,
      `city`,
      `phone`,
      `doge_address`,
      `banner`,
      `public_info`,
      `text`,
      `active`,
      `date`
      ) VALUES (
      '".filter_var($name, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$email."',
      '".$twitter."',
      '".$password."',
      '".$tax_id."',
      '".filter_var($address, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$postal_code."',
      '".filter_var($country, FILTER_SANITIZE_ADD_SLASHES)."',
      '".filter_var($city, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$phone."',
      '".$doge_address."',
      '".$banner."',
      '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',      
      '".$public_info."',      
      '".$active."',
      '".$date."'
      );");

      return null;
    }

  // Update an existent Shibe
  public function UpdateShibe($name,$email,$twitter,$password = null,$tax_id,$address,$postal_code,$country,$city,$phone,$doge_address,$banner,$text,$public_info,$active,$date,$id)
    {

      // we encrypt the password if submited new
      if (isset($password) and $password != ""){
        $password = hash('sha256', $password);
        $this->pdo->query("UPDATE shibes SET
        password = '".$password."'
        WHERE id = '".$id."' limit 1");
      };

      $this->pdo->query("UPDATE shibes SET
      name = '".filter_var($name, FILTER_SANITIZE_ADD_SLASHES)."',
      email = '".$email."',
      twitter = '".$twitter."',
      tax_id = '".$tax_id."',
      address = '".filter_var($address, FILTER_SANITIZE_ADD_SLASHES)."',
      postal_code = '".$postal_code."',
      country = '".filter_var($country, FILTER_SANITIZE_ADD_SLASHES)."',
      city = '".filter_var($city, FILTER_SANITIZE_ADD_SLASHES)."',
      phone = '".$phone."',
      doge_address = '".$doge_address."',
      banner = '".$banner."',
      text = '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      public_info = '".$public_info."',
      active = '".$active."',
      date = '".$date."'
      WHERE id = '".$id."' limit 1");

      return null;
    }
   

  // Activate a Shibe Account
  public function ActivateShibe($hash,$email)
    {
      // we activate the Shibe account
        $this->pdo->query("UPDATE shibes SET
        active = '1'
        WHERE password = '".$this->CleanString($hash)."' and email = '".$this->CleanEmail($email)."' limit 1");

      return null;
    }

  // Recover an existent Shibe
  public function RecoverShibe($hash,$email)
    {
        // we verify if the Shibe email alredy exists
        $row = $this->pdo->query("SELECT id,name,email,password FROM shibes where email = '".$this->CleanEmail($email)."' limit 1")->fetch();

        if (isset($row["password"])){

          $password_verify = hash('sha256', $row["password"]); // doble hash for security, the password the verify against the original

          if ($password_verify == $hash){ // we check if the doble hashed password is the same has on the email link

              $password_email = bin2hex(random_bytes(10)); // we randomly generate a new password
              $password = hash('sha256', $password_email); // we hash in sha256

            // we update the Shibe password
              $this->pdo->query("UPDATE shibes SET
              password = '".$password."',
              active = '1'
              WHERE email = '".$this->CleanEmail($email)."' limit 1");

            // we send the email to the shibe with the new password
              $mail_subject = "Much recover Shibe Password!";
              $mail_message = "Hello ".$row["name"].",<br><br>Here it is you new generated password: ".$password_email." <br><br><a href='https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?d=login'>click here</a><br><br>Much Thanks!";
              //$this->SendEmail($this->config["mail_name_from"],$this->config["email_from"],$this->CleanEmail($row["email"]),$mail_subject,$mail_message);
              $this->mailx($row["email"],$this->config["email_from"],$this->config["mail_name_from"],$this->config["email_from"],$this->config["email_password"],$this->config["email_port"],$this->config["email_stmp"],$mail_subject,$mail_message);

          }
        }
      return null;
    }

  // Reemoves Product
  public function RemoveShibe($id)
    {

      $this->pdo->query("DELETE FROM shibes where id='".$id."' limit 1");

      return null;
    }
//// END Shibes /////////


//// shipping /////////
  // Add shipping
  public function InsertShipping($id_shibe,$country,$title,$text,$weight,$doge,$fiat,$active)
    {

      $this->pdo->query("INSERT INTO `shipping` (
      `id_shibe`,
      `country`,
      `title`,
      `text`,
      `weight`,
      `doge`,
      `fiat`,
      `active`
      ) VALUES (
      '".$id_shibe."',
      '".$country."',
      '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$weight."',
      '".$doge."',
      '".$fiat."',
      '".$active."'
      );");

      return null;
    }

  // update an existent shipping
  public function UpdateShipping($country,$title,$text,$weight,$doge,$fiat,$active,$id,$id_shibe)
    {

      $this->pdo->query("UPDATE shipping SET
      country = '".$country."',
      title = '".filter_var($title, FILTER_SANITIZE_ADD_SLASHES)."',
      text = '".filter_var($text, FILTER_SANITIZE_ADD_SLASHES)."',
      weight = '".$weight."',
      doge = '".$doge."',
      fiat = '".$fiat."',
      active = '".$active."'
      WHERE id = '".$id."' and id_shibe = '".$id_shibe."' limit 1");

      return null;
    }

  // Reemoves shipping
  public function RemoveShipping($id)
    {

      $this->pdo->query("DELETE FROM shipping where id='".$id."' limit 1");

      return null;
    }
//// END shipping /////////


//// tax /////////
  // Add tax
  public function InsertTax($category,$country,$tax,$active)
    {

      $this->pdo->query("INSERT INTO `tax` (
      `category`,
      `country`,
      `tax`,
      `active`
      ) VALUES (
      '".filter_var($category, FILTER_SANITIZE_ADD_SLASHES)."',
      '".$country."',
      '".$tax."',
      '".$active."'
      );");

      return null;
    }

  // update an existent shipping
  public function UpdateTax($category,$country,$tax,$active,$id)
    {

      $this->pdo->query("UPDATE tax SET
      category = '".filter_var($category, FILTER_SANITIZE_ADD_SLASHES)."',
      country = '".$country."',
      tax = '".$tax."',
      active = '".$active."'
      WHERE id = '".$id."' limit 1");

      return null;
    }

  // Removes tax
  public function RemoveTax($id)
    {

      $this->pdo->query("DELETE FROM tax where id='".$id."' limit 1");

      return null;
    }
//// END tax /////////


  // cleans the sting complitly to prevent injection attacks
    public function CleanString($string)
      {
        return filter_var(trim($string), FILTER_SANITIZE_ADD_SLASHES);// we clean the string
      }

  // cleans the email complitly to prevent injection attacks
    public function CleanEmail($string)
      {
        $string = str_replace(' ', '', $string); // removes all spaces
        return filter_var($string, FILTER_SANITIZE_EMAIL); // removes special characters from emails
      }

  // cleans the sting from tags to prevent injection attacks
    public function StripString($string)
      {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
      }

  // we get the current moon fase to apply Doge discount on prices
    public function moon()
        {

        // Use current UTC date
        date_default_timezone_set('UTC');
        $thedate = date('Y-m-d H:i:s');
        $unixdate = strtotime($thedate);

        // The duration in days of a lunar cycle
        $lunardays = 29.53058770576;
        // Seconds in lunar cycle
        $lunarsecs = $lunardays * (24 * 60 *60);
        // Date time of first new moon in year 2000
        $new2000 = strtotime("2000-01-06 18:14");

        // Calculate seconds between date and new moon 2000
        $totalsecs = $unixdate - $new2000;

        // Calculate modulus to drop completed cycles
        // Note: for real numbers use fmod() instead of % operator
        $currentsecs = fmod($totalsecs, $lunarsecs);

        // If negative number (date before new moon 2000) add $lunarsecs
        if ( $currentsecs < 0 ) {
            $currentsecs += $lunarsecs;
        }

        // Calculate the fraction of the moon cycle
        $currentfrac = $currentsecs / $lunarsecs;

        // Calculate days in current cycle (moon age)
        $currentdays = $currentfrac * $lunardays;

        // Array with start and end of each phase
        // In this array 'new', 'first quarter', 'full' and
        // 'last quarter' each get a duration of 2 days.
        $phases = array
            (
            array("new", 0, 1),
            array("waxing_crescent", 1, 6.38264692644),
            array("first_quarter", 6.38264692644, 8.38264692644),
            array("waxing_gibbous", 8.38264692644, 13.76529385288),
            array("full", 13.76529385288, 15.76529385288),
            array("waning_gibbous", 15.76529385288, 21.14794077932),
            array("last_quarter", 21.14794077932, 23.14794077932),
            array("waning_crescent", 23.14794077932, 28.53058770576),
            array("new", 28.53058770576, 29.53058770576),
            );

        // Find current phase in the array
        for ( $i=0; $i<9; $i++ ){
            if ( ($currentdays >= $phases[$i][1]) && ($currentdays <= $phases[$i][2]) ) {
                $thephase = $phases[$i][0];
                break;
            }
        }
        if ($thephase == "new" or $thephase == "full"){ return $thephase; };

        return null;
    }

    public function SendEmail($mail_name_from,$email_from,$email_to,$mail_subject,$mail_message)
      {
        // Create headres for mail() function
        $headers  = "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: $mail_name_from <$email_from>\r\n";
        $headers .= "Reply-To: $email_from\r\n";

        // Send mail
        mail($email_to, $mail_subject, $mail_message, $headers);
      }

    // This functions is to get the visitor real IP
    public function getIPAddress() {
     //whether ip is from the share internet
      if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
      }
      //whether ip is from the proxy
      elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
       }
      //whether ip is from the remote address
      else{
          $ip = $_SERVER['REMOTE_ADDR'];
      }
       return $ip;
    }

    // This function converts 1 Doge to Fiat
  public function DogeFiatRates($fiat) {

        $db = $this->pdo->prepare("SELECT * FROM fiat where currency = ? ");
        $db->execute([$fiat]);
        $db = $db->fetch();

        return round($db["value"], 4);
  }

// This function gets the current Fiat Value of Doge each 60 seconds and updates the local DB
public function UpdateFiatValue($fiatvalue,$fiatcurrency){
    /*
        $ctx = stream_context_create(array('http'=>
          array(
              'timeout' => 2,
          )
        ));
    */
    //$db = $this->pdo->prepare("SELECT currency FROM fiat");
    // $db->execute();

    //while ($fiat = $db->fetch()) {
      //$value = json_decode(file_get_contents("https://api.coingecko.com/api/v3/coins/markets?vs_currency=".$fiat["currency"]."&ids=dogecoin&per_page=1&page=1&sparkline=false", false, $ctx));

        if (isset($fiatvalue) and $fiatvalue > 0.0000){

              $dbu = $this->pdo->prepare("UPDATE fiat SET
              value = :value,
              date = :date
              WHERE currency = :currency limit 1");
              $dbu->execute([':value'=>$fiatvalue,':date'=>date("Y-m-d H:i:s"),':currency'=>$fiatcurrency]);

        //}else{

          //break;

        //}
    };

}

    // This function converts current Fiat to Doge
  public function FiatDogeRates($price = 0, $fiat) {
        $price = $price / $this->DogeFiatRates($fiat);
        return number_format((float)($price), 2, '.', '');
  }

    // This function gets a Doge Address Balance
  public function DogeBalance($doge_address) {
        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => 2,  //1200 Seconds is 20 Minutes
            )
        ));
        $doge = json_decode(file_get_contents("https://dogechain.info/api/v1/address/balance/".$doge_address, false, $ctx));
        return $doge->balance;
  }


  public function upload($files){
      if(is_array($files)) {
  
  
          $uploadedFile = $files['file']['tmp_name']; 
          $sourceProperties = getimagesize($uploadedFile);
          $newFileName = time();
          $dirPath = "img/";
          $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
          $imageType = $sourceProperties[2];
  
  
          switch ($imageType) {
  
              case IMAGETYPE_PNG:
                  $imageSrc = imagecreatefrompng($uploadedFile); 
                  $tmp = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
                  imagepng($tmp,$dirPath.$newFileName."_thump.".$ext);
                  break;           
  
              case IMAGETYPE_JPEG:
                  $imageSrc = imagecreatefromjpeg($uploadedFile); 
                  $tmp = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
                  imagejpeg($tmp,$dirPath.$newFileName."_thump.".$ext);
                  break;
              
              case IMAGETYPE_GIF:
                  $imageSrc = imagecreatefromgif($uploadedFile); 
                  $tmp = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
                  imagegif($tmp,$dirPath.$newFileName."_thump.".$ext);
                  break;
  
              default:
                  echo"Invalid Image type.";
                  exit;
                  break;
          }
  
          move_uploaded_file($uploadedFile, $dirPath.$newFileName.".".$ext);

      }
  }
  
  
  public function imageResize($imageSrc,$imageWidth,$imageHeight) {
  
      $newImageWidth=200;
      $newImageHeight=200;
  
      $newImageLayer=imagecreatetruecolor($newImageWidth,$newImageHeight);
      imagecopyresampled($newImageLayer,$imageSrc,0,0,0,0,$newImageWidth,$newImageHeight,$imageWidth,$imageHeight);
  
      return $newImageLayer;
  }

  // Send emails using SMTP
  public function mailx($email_to,$email_from,$email_from_name,$email_username,$email_password,$email_port,$email_stmp,$email_subject,$email_body){


    if (!class_exists('PHPMailer\PHPMailer\Exception'))
    {
      require("vendors/PHPMailer/src/PHPMailer.php");
      require("vendors/PHPMailer/src/SMTP.php");
      require("vendors/PHPMailer/src/Exception.php");
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPOptions = array(
      'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
      )
    );

    $mail->CharSet="UTF-8";
    $mail->Host = $email_stmp;
    $mail->SMTPDebug = 0;
    $mail->Port = $email_port ; //465 or 587

    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->IsHTML(true);

    //Authentication
    $mail->Username = $email_username;
    $mail->Password = $email_password;

    //Set Params
    $mail->SetFrom($email_from, $email_from_name);
    $mail->AddAddress($email_to);
    $mail->addReplyTo($email_from, $email_from_name);
    $mail->Subject = $email_subject;
    $mail->Body = $email_body;

     if(!$mail->Send()) {
      //echo "Mailer Error: " . $mail->ErrorInfo;
     } else {
      //echo "Message has been sent";
     }
  return null;
  }

};

    $d = new DogeBridge($LibDogecoin,$pdo,$config);


  // global sanityse
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
  if (isset($_POST["text"])){ $text = $_POST["text"]; }; // patch to bypass media
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
  if (isset($_POST["text"])){ $_POST["text"] = $text; };  // patch to bypass media    

  // clean public vars to prevent injection attempts
  if(isset($_POST["fetch"])){ $_POST["fetch"] = $d->CleanString($_POST["fetch"]); };
  if(isset($_GET["insert"])){ $_GET["insert"] = $d->CleanString($_GET["insert"]); };
  if(isset($_GET["remove"])){ $_GET["remove"] = $d->CleanString($_GET["remove"]); };
  if(isset($_GET["c"])){ $_GET["c"] = $d->CleanString($_GET["c"]); };
  if(isset($_GET["product"])){ $_GET["product"] = $d->CleanString($_GET["product"]); };
  if(isset($_GET["page"])){ $_GET["page"] = $d->CleanString($_GET["page"]); };

  // check if the language was changed
  if(isset($_GET["l"])){
    $l = $d->CleanString($_GET["l"]);
    if (!file_exists(ROOTPATH."/lang/".$l.".php")) {
        $_SESSION["l"] = $lang["default"];
    }else{
        $_SESSION["l"] = $l;
    };
  };
  // check if the language is defined
  if (!isset($_SESSION["l"])){
      $_SESSION["l"] = $lang["default"];
  }

  // we include the language file
  include("lang/".$_SESSION["l"].".php");

  // we include the version
  include("v.php");
?>