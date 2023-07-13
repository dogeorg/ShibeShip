<?php
/**
*   Project: November 2022 Hackathon / Dogeathon / Downunder Project HappyShibes aka ShibeShip
*   Author: A lot of good shibes
*   Description: Real use case of the Dogecoin BlockChain to sell products and services
*   License: Well, do what you want with this, be creative, you have the wheel, just reenvent and do it better! Do Only Good Everyday
*/
// include the configuration and functions
include("inc/config.php");

// check if its installed and configured
if(isset($config["dbuser"]) and $config["dbpass"] == "" ){
    $url = 'http'.(isset($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url_details = parse_url($url);
    header('Location: http'.(isset($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['HTTP_HOST'].$url_details['path'].'install');
    exit;
};

// check if shibe is loged in
if(isset($_SESSION["shibe"])){

    $db = $pdo->prepare("SELECT name,email,country FROM shibes where id = ? and active = 1 limit 1");
    $db->execute([$_SESSION["shibe"]]); 
    $row = $db->fetch(); 

    $shibe["name"] = explode(" ",$row["name"]);
    $shibe["name"] = $shibe["name"][0];
    $shibe["email"] = $row["email"];
    $shibe["country"] = $row["country"];

}

// get current URL for SEO
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// to help the seller we add some SEO of each listing
if ($_GET["d"] =="product" and $_GET["product"] > 0){

  $db = $pdo->prepare("SELECT * FROM products where id = ? and active = 1 limit 1");
  $db->execute([$_GET["product"]]); 
  $row = $db->fetch();

  if (isset($row["imgs"]) and $row["imgs"] != ""){
    $imgs = explode(",", $row["imgs"]);
    $config["image"] = "/fl/".$imgs[0];
  };
  if ($row["doge"] == 0){ $amount = $d->FiatDogeRates($row["fiat"], $config["fiat"]); }else{ $amount = number_format((float)($row["doge"]), 2, '.', ''); };
  $title = $row["title"];
  if ($amount > 0){
    $title = "Ð ".$amount." - " . $row["title"];
  }
  
  if (isset($row["text"])){ $description = substr(trim(strip_tags($row["text"])),0,200); };    

  $db = $pdo->prepare("SELECT * from shibes where id = ? and active = 1 limit 1");
  $db->execute([$row["id_shibe"]]); 
  $b = $db->fetch();
  
  if (isset($b["twitter"])){ $twitter = str_replace("@", "", $b["twitter"]); };
}

// to help the seller we add some SEO of each shop
if ($_GET["d"] =="shop" and $_GET["shibe"] > 0){

  $db = $pdo->prepare("SELECT * from shibes where id = ? and active = 1 limit 1");
  $db->execute([$_GET["shibe"]]); 
  $b = $db->fetch();

  if (isset($b["banner"])){ $config["image"] = "/fl/".$b["banner"]; };
  if (isset($b["text"])){ $description = substr(trim(strip_tags($b["text"])),0,200); };
  if (isset($b["twitter"])){ $twitter = str_replace("@", "", $b["twitter"]); };
  $title = $b["name"];

}

?>
<!DOCTYPE HTML>
<html lang="<?php echo $lang["header"]; ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?php if (isset($title)){ echo $title." - "; }; ?><?php echo $lang["store_title"]; ?></title>

  <meta name="description" content="<?php if (isset($description)){ echo $description; }else{ echo $lang["store_description"]; }; ?>">
  <meta name="author" content="<?php echo $lang["author"]; ?>">
  <meta name="generator" content="<?php echo $lang["generator"]; ?>">
  <base href="<?php echo $config["base_url"]; ?>" >

	<link rel="manifest" href="appmanifest.json" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-icon" sizes="256x256" href="img/logo_ss.png" />
	<meta name="HandheldFriendly" content="true" />
	<meta name="mobile-web-app-capable" content="yes" />

  <meta property="og:site_name" content="<?php echo $lang["store_name"]; ?>" />
  <meta property="og:type" content="Marketplace" />
  <meta property="og:url" content="<?php echo $url.$_SERVER["REQUEST_URI"]; ?>" />
  <meta property="og:title" content="<?php if (isset($title)){ echo $title; }else{ echo $lang["store_title"]; }; ?>" />
  <meta property="og:description" content="<?php if (isset($description)){ echo $description; }else{ echo $lang["store_description"]; }; ?>" />
  <meta property="og:image" content="<?php echo $url.$config["image"];?>" />
  <meta property="og:image:url" content="<?php echo $url.$config["image"];?>" />
  <meta property="og:image:secure_url" content="<?php echo $url.$config["image"];?>" />
  <meta property="og:image:width" content="1600" />
  <meta property="og:image:height" content="900" />
  <meta property="og:locale" content="en" />

  <meta property="article:section" content="Marketplace" />
  <meta property="article:tag" content="<?php if (isset($title)){ echo $title; }else{ echo $lang["store_title"]; }; ?>" />
  <meta property="article:tag" content="Doge" />
  <meta property="article:tag" content="Dogecoin" />
  <meta property="article:tag" content="Cryptocurrency" />
  <meta property="article:published_time" content="<?php echo date('Y-m-d');?>T<?php echo date(" H:i:s");?>+0000" />
  <meta property="article:modified_time" content="<?php echo date('Y-m-d');?>T<?php echo date(" H:i:s");?>+0000" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@<?php if (isset($twitter)){ echo $twitter; }else{ echo "dogecoin"; }; ?>" />
  <meta name="twitter:description" content="<?php if (isset($description)){ echo $description; }else{ echo $lang["store_description"]; }; ?>" />
  <meta name="twitter:title" content="<?php if (isset($title)){ echo $title; }else{ echo $lang["store_title"]; }; ?>" />
  <meta name="twitter:creator" content="@<?php if (isset($twitter)){ echo $twitter; }else{ echo "dogecoin"; }; ?>" />
  <meta name="twitter:image:alt" content="<?php if (isset($title)){ echo $title; }else{ echo $lang["store_title"]; }; ?>" />
  <meta name="twitter:image" content="<?php echo $url.$config["image"];?>" />

  <link href="<?php echo $config["base_url"]; ?>img/logo_ss.png" rel="icon" />

  <link href="//fonts.googleapis.com/css2?family=Comic+Neue&amp;display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- <link rel="stylesheet" href="/inc/vendors/AdminLTE/plugins/fontawesome-free/css/all.min.css"> -->
  <link rel="stylesheet" href="/inc/vendors/AdminLTE/plugins/flag-icon-css/css/flag-icon.min.css">
<?php if(isset($_SESSION["shibe"])){ ?>
  <link rel="stylesheet" href="/inc/vendors/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/inc/vendors/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/inc/vendors/AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />  
<?php }; ?> 
<?php if(isset($_SESSION["shibe"]) or $_GET["d"] == "shibe"){ ?>  
  <link rel="stylesheet" href="/inc/vendors/AdminLTE/plugins/summernote/summernote-bs4.min.css">
<?php }; ?> 
    <!-- Theme style -->
  <link rel="stylesheet" href="/inc/vendors/AdminLTE/dist/css/adminlte.min.css">
  <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">  -->
  <link rel="stylesheet" href="/css/doge.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>


<!-- REQUIRED SCRIPTS -->
<!-- Bootstrap 4 -->
<script src="/inc/vendors/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<?php if(isset($_SESSION["shibe"])){ ?>
<!-- DataTables  & Plugins -->
<script src="/inc/vendors/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/jszip/jszip.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/inc/vendors/AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
<?php }; ?>
<?php if(isset($_SESSION["shibe"]) or $_GET["d"] == "shibe"){ ?>  
<!-- Summernote -->
<script src="/inc/vendors/AdminLTE/plugins/summernote/summernote-bs4.min.js"></script>
<?php }; ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!--<script src="/inc/vendors/AdminLTE/dist/js/adminlte.min.js"></script>-->
<script>
$(document).ready(function(){

   // we change the theme dark/light modes
   var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    var currentTheme = localStorage.getItem('theme');
    var mainHeader = document.querySelector('.main-header');
    var leftMenu = document.querySelector('.main-sidebar');
    var moon = document.querySelector('.sun-moon');

    if (currentTheme) {
        if (currentTheme === 'dark') {
            if (!document.body.classList.contains('dark-mode')) {
                document.body.classList.add("dark-mode");
            }
            if (mainHeader.classList.contains('navbar-light')) {
                mainHeader.classList.remove('navbar-light');
                mainHeader.classList.add('navbar-dark');
                moon.classList.remove('fa-moon');
                moon.classList.add('fa-sun');

                leftMenu.classList.remove('sidebar-light-primary');
                leftMenu.classList.add('sidebar-dark-primary');
            }
            toggleSwitch.checked = true;
        }
    }

    function switchTheme(e) {
        if (e.target.checked) {
            if (!document.body.classList.contains('dark-mode')) {
                document.body.classList.add("dark-mode");
            //}
            //if (mainHeader.classList.contains('navbar-light')) {
                mainHeader.classList.remove('navbar-light');              
                mainHeader.classList.add('navbar-dark');
                moon.classList.remove('fa-moon');
                moon.classList.add('fa-sun');       

                leftMenu.classList.remove('sidebar-light-primary');
                leftMenu.classList.add('sidebar-dark-primary');                
            }
            localStorage.setItem('theme', 'dark');
        } else {
            if (document.body.classList.contains('dark-mode')) {
                document.body.classList.remove("dark-mode");
                moon.classList.add('fa-moon');
                moon.classList.remove('fa-sun');
            //}
            //if (mainHeader.classList.contains('navbar-dark')) {
                mainHeader.classList.remove('navbar-dark');              
                mainHeader.classList.add('navbar-light');

                leftMenu.classList.remove('sidebar-dark-primary');
                leftMenu.classList.add('sidebar-light-primary');
            }
                        
            localStorage.setItem('theme', 'light');
        }
    }

    toggleSwitch.addEventListener('change', switchTheme, false);



$("#runningdoge").mouseenter(function () {

    $(this).animate({
        top: Math.random() * 100 + "%"
    }, 100);
    $(this).animate({
        left: Math.random() * 100 + "%"
    }, 100);

});
<?php if(isset($_SESSION["shibe"])){ ?>


  $("#tabled").on('click','.remove', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        return swal.fire({
        title: "Are you sure you want to remove?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#CC0000',
        cancelButtonColor: '#666666',
        confirmButtonText: 'Wof, yes!',
        cancelButtonText: 'Much, No!'
    })
    .then((result) => {
      if (result.isConfirmed) {
        window.location.href = href;
      }
    });

    });






	$("#tabled").DataTable({
        "language": {
            "paginate": {
              "previous": "<?php echo $lang["previous"];?>",
              "next": "<?php echo $lang["next"];?>"
            },
            "lengthMenu": "<?php echo $lang["lengthMenu"];?>",
            "zeroRecords": "<?php echo $lang["zeroRecords"];?>",
            "info": "<?php echo $lang["data_info"];?>",
            "infoEmpty": "<?php echo $lang["infoEmpty"];?>",
            "infoFiltered": "<?php echo $lang["infoFiltered"];?>"
        },
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["csv", "pdf", "print"],
      "order": [[ 0, "desc" ]],
    }).buttons().container().appendTo('#tabled_wrapper .col-md-6:eq(0)');
<?php }; ?>
<?php if(isset($_SESSION["shibe"]) or $_GET["d"] == "shibe"){ ?>
    // Summernote
    $('#summernote').summernote();    
<?php }; ?>

    // we get the search results and dispaly
    $( "#fetch" ).on('keyup', function () {
      var fetch = this.value;
      if ($(this).val().length > 2){
              $.ajax({
                  url: "/inc/ajax-fetch.php", // path to myphp file which returns the price
                  method: "post", // POST request
                  data: "fetch=" + fetch, // I retrieve this data in my$_POST variable in ajax.php : $_POST[id]
                  success: function(response) { // The function to execute if the request is a -success-, response will be the price
                            //var datashow = response;
                      if (response == null){
                           //alert('No records');
                      }else{
                          $("#fetchresultscard").show();
                          $("#fetchresults").html(response);

                      }
                  },
                  error: function(){
                  //alert('Error!');
                  }
              });
              };
    });

// wen we a shibe is registered
<?php if (isset($_GET["shibe"]) and $_GET["shibe"] == "verify"){ ?>
                  swal.fire({
                    icon: 'success',
                    title: '<?php echo $lang["wow"]; ?>',
                    showConfirmButton: true,
                    confirmButtonColor: '#666666',
                    html:
                      '<?php echo $lang["verify_account"]; ?>',
                  })
<?php }; ?>
// wen we verify that the Dogecoin Node is not running!
<?php if (isset($_GET["d"]) and $_GET["d"] == "shipping"){ ?>
                  swal.fire({
                    icon: 'warning',
                    title: '<?php echo $lang["sad"]; ?>',
                    showConfirmButton: true,
                    confirmButtonColor: '#666666',
                    html:
                      '<img src="/img/sad_doge.gif"><br><br>' +
                      'Sorry there is no active shipping for your country!',
                  })
<?php }; ?>
// wen a shibe is alredy registered
<?php if (isset($_GET["shibe"]) and $_GET["shibe"] == "exists"){ ?>
                  swal.fire({
                    icon: 'warning',
                    title: '<?php echo $lang["sad"]; ?>',
                    showConfirmButton: true,
                    confirmButtonColor: '#666666',
                    html:
                      '<?php echo $lang["exists_account"]; ?>',
                  })
<?php }; ?>
// wen we a shibe is loged in
<?php if (isset($_GET["shibe"]) and $_GET["shibe"] == "login"){ ?>
                  swal.fire({
                    icon: 'success',
                    title: '<?php echo $lang["wow"]; ?>',
                    showConfirmButton: false,
                    timer: 1500,
                    html:
                      '<img src="/img/loading_screen.gif">',
                  })
<?php }; ?>
// wen we a shibe is loged out
<?php if (isset($_GET["shibe"]) and $_GET["shibe"] == "logout"){ ?>
                  swal.fire({
                    icon: 'success',
                    title: '<?php echo $lang["sad"]; ?>',
                    showConfirmButton: false,
                    timer: 1500,
                    html:
                      '<img src="/img/sad_doge.gif">',
                  })
<?php }; ?>
// wen we a shibe is verified
<?php if (isset($_GET["shibe"]) and isset($_GET["hash"]) and isset($_GET["email"]) and $_GET["shibe"] == "activate"){

$d->ActivateShibe($_GET["hash"],$_GET["email"]);

 ?>
                  swal.fire({
                    icon: 'success',
                    title: '<?php echo $lang["wow"]; ?>',
                    showConfirmButton: false,
                    timer: 1500,
                    html:
                      '<img src="/img/loading_screen.gif">',
                  })
<?php }; ?>
// wen we recover a shibe password
<?php if (isset($_GET["shibe"]) and $_GET["shibe"] == "recover"){
if (isset($_GET["hash"]) and isset($_GET["email"])){
    $d->RecoverShibe($_GET["hash"],$_GET["email"]);
};

 ?>
                  swal.fire({
                    icon: 'success',
                    title: 'Much check your email!',
                    showConfirmButton: true,
                    confirmButtonColor: '#666666',
                    html:
                      '<img src="/img/loading_screen.gif">',
                  })
<?php }; ?>
});
  // prevent resubmiting page
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>
<?php
// I implemented an hack to bypass coingecko restrictions and block and it uses the client local IP to get fiat value of doge to update the DB now
if (!isset($_SESSION["fiatupdated"])){ ?>
<script type="text/javascript">
  $(document).ready(function() {
    <?php 
        $db = $pdo->prepare("SELECT currency FROM fiat");
        $db->execute();
        while ($fiat = $db->fetch()) {
    ?>      
              $.getJSON("https://api.coingecko.com/api/v3/simple/price?ids=dogecoin&vs_currencies=<?php echo strtolower($fiat["currency"]); ?>", function(data){          
                $.post( "inc/fiat.php", { fiatvalue: data["dogecoin"]["<?php echo strtolower($fiat["currency"]); ?>"], fiatcurrency: "<?php echo strtolower($fiat["currency"]); ?>" } )
                .done(function( data ) {
                  <?php $_SESSION["fiatupdated"] = 1;  ?>
                  //alert( "Data Loaded: " + data );
                });
              });
    <?php
      };
    ?>
  });


</script>
<?php }; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse">
<!-- <img src="/img/fantoumi_doge.gif" style="max-width: 100px; position: absolute !important; z-index: 1069 !important; left: 10px; bottom: 15px; display:none" id="runningdoge" alt="Running Doge"> -->
<div class="wrapper">

  <!-- Preloader
  <div class="preloader flex-column justify-content-center align-items-center" style="margin-top: -20px;">
    <img class="animation__shake" src="/img/loading_screen.gif" alt="DogeGardeen" height="161">
    <?php echo $lang["loading"]; ?>
  </div> 
  -->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
                  <?php
                      $db = $pdo->prepare("SELECT * FROM pages where type = 0 and id_page = 0 and lang = ? and active = 1 order by ord ASC");
                      $db->execute([$_SESSION["l"]]); 

                      while ($row = $db->fetch()) {
                  ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/?d=page&page=<?php echo $row["id"]; ?>" class="nav-link"><?php echo $row["title"]; ?></a>
      </li>
                  <?php
                  };
                  ?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown dogeh">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa-solid fa-ellipsis"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                  <?php
                      $db = $pdo->prepare("SELECT * FROM pages where type = 0 and id_page = 0 and lang = ? and active = 1 order by ord ASC");
                      $db->execute([$_SESSION["l"]]);                       
                      while ($row = $db->fetch()) {
                  ?>
                    <a href="/?d=page&page=<?php echo $row["id"]; ?>" class="dropdown-item">
                      <i class="fa-solid fa-angle-right mr-2"></i> <?php echo $row["title"]; ?>
                    </a>
                    <div class="dropdown-divider"></div>
                  <?php
                  };
                  ?>
        </div>
      </li>

      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" id="fetch" type="search" placeholder="<?php echo $lang["fetch"]; ?>" aria-label="<?php echo $lang["fetch"]; ?>">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search" onclick='$("#fetchresultscard").hide();'>
                  <i class="fa-regular fa-circle-xmark"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Language Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="flag-icon flag-icon-<?php if ($_SESSION["l"] == $lang["default"] ){ echo $lang["default"]; }else{ echo $_SESSION["l"]; };?>"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-0">
          <?php
          $languages = scandir("inc/lang/");
          foreach($languages as $language){
            if(strpos($language, ".php") !== false){              
              $language_file = explode(".",$language);
           ?>
          <a href="/?l=<?php echo $language_file[0]; ?>" class="dropdown-item <?php if ($_SESSION["l"] == $language_file[0]){ echo "active"; }; ?>">
            <i class="flag-icon flag-icon-<?php echo $language_file[0]; ?> mr-2"></i> <?php echo $lang[$language_file[0]]; ?>
          </a>
          <?php
            }
          }
          ?>
        </div>
      </li>
      <li class="nav-item">
          <div class="theme-switch-wrapper nav-link">
              <label class="theme-switch">
                  <input type="checkbox" class="btn-check" id="moon" autocomplete="off" style="display: none">
                  <i class="sun-moon fa-solid fa-moon" for="moon" style="cursor: pointer"></i>
              </label>
          </div>
      </li>    
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fa-solid fa-maximize"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
   <!-- Brand Logo -->
    <a href="/index.php" class="brand-link">
      <img class="shibeship" src="/img/logo_ss.png" alt="<?php echo $lang["store_name"]; ?>" style="max-width: 30px">
      <span class="brand-text font-weight-light"><?php echo $lang["store_name"]; ?></span>
<?php if ($config["demo"] == 1){ ?>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning" style="padding-top:-10px">
        <?php echo $lang["store_demo"]; ?>
      </span>
<?php }; ?>
    </a>
    <!-- Sidebar -->
    <div class="sidebar" style="padding-top: 20px">
      <!-- Sidebar user panel (optional) -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

<?php if(!isset($_SESSION["shibe"])){ ?>
          <li class="nav-item" style="border-bottom: 1px solid #666666">
            <a href="#" class="nav-link nav-linkShibe nav-linkShibe btn-light active" style="color: #333">
              <i class="nav-icon fa-solid fa-plus nav-icon"></i>
              <p>
              <?php echo $lang["sell"]; ?>
                <i class="right fa-solid fa-angle-left"></i>
              </p>
            </a>
        <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/?d=login" class="nav-link nav-linkShibe">
                  <i class="nav-icon far fa-solid fa-lock"></i>
                  <p><?php echo $lang["login"]; ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/?d=shibe&do=insert" class="nav-link">
                  <i class="nav-icon fa-regular fa-address-card"></i>
                  <p><?php echo $lang["register"]; ?></p>
                </a>
              </li>
          </li>
        </ul>
        </li>
<?php }else{ ?>
          <li class="nav-item" style="border-bottom: 1px solid #666666">
            <a href="#" class="nav-link nav-linkShibe btn-light" style="color: #333">
              <i class="nav-icon fa-solid fa-lock-open nav-icon"></i>
              <p>
              <?php echo $lang["such"]; ?>, <?php echo $shibe["name"]; ?>
                <i class="right fas fa fa-angle-left"></i>
              </p>
            </a>
        <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/?d=listings" class="nav-link nav-linkShibe">
                  <i class="nav-icon far fas fa fa-list"></i>
                  <p><?php echo $lang["my_listings"]; ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/?d=orders" class="nav-link" nav-linkShibe>
                  <i class="far fas fa-file-invoice nav-icon"></i>
                  <p><?php echo $lang["orders"]; ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/?d=shibe&do=update" class="nav-link nav-linkShibe">
                  <i class="nav-icon far fa fa-id-card"></i>
                  <p><?php echo $lang["my_shop"]; ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/?d=shibe&do=logout" class="nav-link nav-linkShibe">
                  <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                  <p><?php echo $lang["logout"]; ?></p>
                </a>
              </li>
          </li>
        </ul>
        </li>
<?php }; ?>


                  <?php
                      $db = $pdo->prepare("SELECT id,icon,title FROM categories where id_cat = 0 and lang = ? and active = 1 order by ord ASC");
                      $db->execute([$_SESSION["l"]]);
                      while ($row = $db->fetch()) {
                        $active = "";
                        // we find the category associeted with the product
                        if (isset($_GET["product"])){

                          $dbmp = $pdo->prepare("SELECT id_cat FROM products where id = ? and active = 1 limit 1");
                          $dbmp->execute([$d->CleanString($_GET["product"])]); 
                          $dbmp = $dbmp->fetch();

                          $dbm = $pdo->prepare("SELECT id FROM categories where id = ? and id_cat = ? and lang = ? and active = 1 limit 1");
                          $dbm->execute([$dbmp["id_cat"],$row["id"],$_SESSION["l"]]); 
                          $dbm = $dbm->fetch();

                          if ($dbm["id"] > 0){
                            $active = "active";
                          };
                        }else{
                            // we find the category associeted with the sub category
                            if (isset($_GET["c"])){

                              $dbm = $pdo->prepare("SELECT id FROM categories where id = ? and id_cat = ? and lang = ? and active = 1 limit 1");
                              $dbm->execute([$d->CleanString($_GET["c"]),$row["id"],$_SESSION["l"]]); 
                              $dbm = $dbm->fetch();

                              if ($dbm["id"] > 0){
                                $active = "active";
                              }else{
                                if ($d->CleanString($_GET["c"]) == $row["id"]){
                                  $active = "active";
                                };
                              }
                            };
                        };

                  ?>

          <li class="nav-item">
            <a href="/?d=products&c=<?php echo $row["id"]; ?>" class="nav-link  nav-linkShibe <?php echo $active; ?>" >
              <i class="nav-icon <?php echo $row["icon"]; ?> nav-icon"></i>
              <p>
                <?php echo $row["title"]; ?>
                <i class="right fa-solid fa-angle-left"></i>
              </p>
            </a>
                  <?php
                      $dbs = $pdo->prepare("SELECT id,icon,title FROM categories where id_cat = ? and lang = ? and active = 1 order by ord ASC");
                      $dbs->execute([$row["id"],$_SESSION["l"]]); 

                      while ($rows = $dbs->fetch()) {
                        $active = "";
                        // we find the category associeted with the product
                        if (isset($_GET["product"])){
                          if ($dbmp["id_cat"] == $rows["id"]){
                            $active = "active";
                          };
                        }else{
                            // we find the category associeted with the sub category
                            if (isset($_GET["c"])){
                            if ($_GET["c"] == $rows["id"]){
                                $active = "active";
                              };
                            };
                        };
                  ?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/?d=products&c=<?php echo $rows["id"]; ?>" class="nav-link  nav-linkShibe <?php echo $active; ?>">
                  <i class="<?php echo $rows["icon"]; ?> nav-icon"></i>
                  <p>
                <?php echo $rows["title"]; ?>
                  </p>
                </a>
              </li>
            </ul>
                  <?php }; ?>
          </li>
                  <?php }; ?>



          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa-solid fa-wallet nav-icon"></i>
              <p>
                <?php echo $lang["wallets"]; ?>
                <i class="right fas fa fa-angle-left"></i>
              </p>
            </a>
        <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="https://dogecoin.com/" target="_blank" class="nav-link">
                  <i class="nav-icon fa-solid fa-dog"></i>
                  <p><?php echo $lang["corewallet"]; ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="https://mydoge.com/" target="_blank" class="nav-link">
                  <i><img class="img-circle elevation-2" src="/img/mydoge.png" style="max-width: 25px">&nbsp;&nbsp;</i>
                  <p><?php echo $lang["mydogewallet"]; ?></p>
                </a>
              </li>
          </li>
        </ul>
          </li>
          <?php
              $db = $pdo->prepare("SELECT * FROM pages where type = 1 and id_page = 0 and lang = ? and active = 1 order by ord ASC");
              $db->execute([$_SESSION["l"]]);

              while ($row = $db->fetch()) {
          ?>
            <li class="nav-item">
                <a href="/?d=page&page=<?php echo $row["id"]; ?>" class="nav-link <?php if ($_GET["page"] == $row["id"]){ echo "active"; }; ?>">
                  <i class="nav-icon fa-solid fa-angle-right"></i>
                  <p>
                    <?php echo $row["title"]; ?>
                  </p>
                </a>
            </li>
          <?php
              };
          ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
      <?php if (isset($config["fiat"]) and $config["fiat"] != ""){ ?>
        <span class="brand-text font-weight-light">
          <div style="color: #666666; padding: 10px; position: relative; bottom: 0px;" id="fiat">
          1 &ETH; = <?php echo $d->DogeFiatRates($config["fiat"]); ?> <?php echo strtoupper($config["fiat"]);?><br>
          1 <?php echo strtoupper($config["fiat"]);?> = <?php echo $d->FiatDogeRates("1.00", $config["fiat"]); ?> &ETH;
          </div>
        </span>
    <?php };?>      
    </div>

    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="card" style="display: none" id="fetchresultscard">
              <div class="card-header">
                <h3 class="card-title"><li class="fa-solid fa-dog"></li> <?php echo $lang["fetch"]; ?> <li class="fa fas fa-search"></li> </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row" id="fetchresults">
                </div>
              </div>
            </div>


        <?php
            $filter = "id_cat = 0 and id_prod = 0 and id_page = 0";
            if (isset($_GET["d"])){ $filter = "id_page < 0"; }; // to fix the shibe account pages to not show banners
            if (isset($_GET["c"])){ $filter = "id_cat = ".$d->CleanString($_GET["c"]); };
            if (isset($_GET["product"])){ $filter = "id_prod = ".$d->CleanString($_GET["product"]); };
            if (isset($_GET["page"])){ $filter = "id_page = ".$d->CleanString($_GET["page"]); };

            $bd = $pdo->prepare("SELECT * FROM banners where lang = ? and $filter and active = 1");
            $bd->execute([$_SESSION["l"]]);
            $bd = $bd->fetch();

            if (isset($bd["id"])){
         ?>
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                  <div id="carouselDogeIndicators" class="carousel slide pointer-event" data-ride="carousel">
                    <ol class="carousel-indicators">
                  <?php
                      $i = 0;

                      $db = $pdo->prepare("SELECT * FROM banners where lang = ? and $filter and active = 1 order by ord ASC");
                      $db->execute([$_SESSION["l"]]);

                      while ($row = $db->fetch()) {
                  ?>
                      <li data-target="#carouselDogeIndicators" data-slide-to="<?php echo $i; ?>" class="<?php if ($i == 0){ ?>active<?php };?>"></li>
                  <?php
                  $i++;
                  };
                  ?>
                    </ol>
                    <div class="carousel-inner" style="max-height: 300px;">

                  <?php
                      $i = 0;

                      $db = $pdo->prepare("SELECT * FROM banners where lang = ? and $filter and active = 1 order by ord ASC");
                      $db->execute([$_SESSION["l"]]);

                      while ($row = $db->fetch()) {
                  ?>
                      <div class="carousel-item <?php if ($i == 0){ ?>active<?php };?>" style="background-image: url(fl/<?php echo $row["img"]; ?>); background-size: cover; background-position: center; background-repeat: no-repeat">
                      <?php
                      if ($row["video"] != ""){
                      ?>
                      <div style="position: absolute; width: 100%;height: 56.25vw !important;margin-top: -5.9vw;">
                        <div data-video="data-video" data-autoplay="true" data-hide-controls="true" data-loop="true" class="video-block" style="position: absolute;height: 100%;width: 100%;margin: 0;padding: 0;">
                            <iframe src="<?php echo $row["video"];?>?rel=0&controls=0&showinfo=0&wmode=opaque&autoplay=1&modestbranding=1&loop=1&mute=1" width="100%" height="100%" class="video-block__media" style="opacity: 1; transition: opacity 0.5s ease-in-out 0s;" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; playlist; loop" ></iframe>
                        </div>
                      </div>
                      <div style=" width: 100%; min-height: 300px;">&nbsp;</div>
                        <?php
                        }else{
                        ?>
                        <img src="fl/<?php echo $row["img"]; ?>" alt="ShibeShip" style="width: 100%">
<!--                        <div style=" width: 100%; min-height: 300px;">&nbsp;</div>-->
                        <?php
                        };
                        ?>
                      </div>
                  <?php
                  $i++;
                  };
                  ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselDogeIndicators" role="button" data-slide="prev">
                      <span class="carousel-control-custom-icon" aria-hidden="true">
                        <i class="fa-solid fa-angle-left"></i>
                      </span>
                      <span class="sr-only"><?php echo $lang["previous"]; ?></span>
                    </a>
                    <a class="carousel-control-next" href="#carouselDogeIndicators" role="button" data-slide="next">
                      <span class="carousel-control-custom-icon" aria-hidden="true">
                        <i class="fa-solid fa-angle-right"></i>
                      </span>
                      <span class="sr-only"><?php echo $lang["next"]; ?></span>
                    </a>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
            <!-- /.card -->
          </div>
        </div>
        <?php }; ?>

    <?php
    // we check if the file exists befor importing
    if (isset($_GET["d"])){
      $p = $d->CleanString($_GET["d"]);

      $files = array("product", "products", "page", "login", "shibe", "recover", "listings", "orders", "shop");
      if (in_array($p, $files)) {
          include("inc/".$p.".php");
      }else{
          include("inc/main.php");
      };
    }else{
        include("inc/main.php");
    };

    ?>
    </div>
  </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" id="cartright">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <?php echo $lang["version"]; ?>
    </div>
    <!-- Default to the left -->
    <strong><?php echo $lang["copyrigh"]; ?></strong>
  </footer>
</div>
<!-- ./wrapper -->
</body>
</html>