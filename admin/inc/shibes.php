<?php
  // make sure there is no atempt to access this file
  if (!isset($_SESSION["admin"])){ exit(); };
?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><?php echo $lang["admin_manage_shibes"]; ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
<?php
// we check if there is a variable defined
if(isset($_GET["do"])){


if(isset($_POST["action"])){
    if ( $_GET["do"] == "status"){
        $d->UpdateShibeStatus($_POST["status"],$_POST["id"]);
    };
    $_GET["id"] = null; $_GET["do"] = null; $_GET["action"] = null;
};

    if ( $_GET["do"] == "remove"){
        $d->RemoveShibe($_GET["id"]);
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
                    <th><?php echo $lang["name"]; ?></th>
                    <th><?php echo $lang["email"]; ?></th>
                    <th><?php echo $lang["phone"]; ?></th>
                    <th><?php echo $lang["country"]; ?></th>
                    <th><?php echo $lang["city"]; ?></th>
                    <th><?php echo $lang["date"]; ?></th>
                    <th><?php echo $lang["active"]; ?></th>
                    <th><?php echo $lang["options"]; ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                      $db = $pdo->query("SELECT * FROM shibes");
                      while ($row = $db->fetch()) {
                  ?>
                  <tr>
                    <td><?php echo $row["id"];?></td>
                    <td><?php echo $row["name"];?></td>
                    <td><?php echo $row["email"];?></td>
                    <td><?php echo $row["phone"];?></td>
                    <td><?php echo $row["country"];?></td>
                    <td><?php echo $row["city"];?></td>
                    <td><?php echo $row["date"];?></td>
                    <td>
                            <?php if ($row["active"] == 0 ){ echo $lang["disable"]; }; ?>
                            <?php if ($row["active"] == 1 ){ echo $lang["active"]; }; ?>
                    </td>
                    <td>
                    <div class="btn-group">
                    <button type="button" class="btn btn-warning" data-toggle="dropdown" ><i class="far fa fa-edit nav-icon"></i> <?php echo $lang["options"]; ?></button>
                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only"><?php echo $lang["options"]; ?></span>
                    </button>
                    <div class="dropdown-menu" role="menu" style="">
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