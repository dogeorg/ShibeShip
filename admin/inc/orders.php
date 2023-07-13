<?php
// if Shibe not loged in, redirect to login
if (!isset($_SESSION["admin"])){
?>
    <script>
            window.location.href = "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?d=login";
    </script>
<?php
exit();
};
?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-file-invoice"></i> <?php echo $lang["admin_manage_orders"]; ?></h3>
                <div style="float:right"><button onclick="history.back()" type="button" class="btn btn-light"><i class="fa-solid fa-arrow-left"></i> Go Back</button></div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">



                <table id="tabled" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th><?php echo $lang["admin_orders_id"]; ?></th>
                    <th data-priority="1"><?php echo $lang["img"]; ?></th>
                    <th><?php echo $lang["name"]; ?></th>
                    <th><?php echo $lang["doge_in_address"]; ?></th>
                    <th><?php echo $lang["admin_orders_total_doge"]; ?></th>
                    <th data-priority="1"><?php echo $lang["admin_orders_status"]; ?></th>
                    <th><?php echo $lang["admin_orders_date"]; ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                        $db = $pdo->query("SELECT * FROM products");
                      while ($products = $db->fetch()) {


                      $ordersdb = $pdo->query("SELECT * FROM generated where id_product = '".$products["id"]."'");
                      while ($orders = $ordersdb->fetch()) {
                        $status_btn = '<i class="fa fa-paw" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["paid"]; $status_color = "success";
                        if ($orders["paid"] == 0){ $status_btn = '<i class="fa fa-hourglass-end" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["pending"]; $status_color = "warning"; };
                        if ($orders["paid"] == 2){ $status_btn = '<i class="fa fa-truck" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["sended"]; $status_color = "success"; };
                        if ($orders["paid"] == 3){ $status_btn = '<i class="fa fa-paw" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i>  ' . $lang["withdraw"]; $status_color = "light"; };                      
                        if ($orders["paid"] == 4){ $status_btn = '<i class="fa fa-handshake" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["refunded"]; $status_color = "secondary"; };
                        if ($orders["paid"] == 5){ $status_btn = '<i class="fa fa-check-circle" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["finish"]; $status_color = "light"; };
                        if ($orders["paid"] == 6){ $status_btn = '<i class="fa fa-ban" aria-hidden="true" style="margin-top:5px; margin-right:2px;"></i> ' . $lang["canceled"]; $status_color = "light"; };
                         if (isset($orders["paid"])){
                  ?>
                  <tr>
                    <td><?php echo $orders["id"];?></td>
                    <td style="text-align:center"><?php if (isset($products["imgs"]) and $products["imgs"] != ""){ $imgs = explode(",", $products["imgs"]); ?><a href="/?d=product&product=<?php echo $products["id"];?>" target="_blank" ><img src="/fl/<?php echo $imgs[0]; ?>" style="max-height: 50px; border-radius:1rem" alt="" /></a><?php }; ?></td>
                    <td><?php echo $products["title"];?></td>
                    <td style="word-break: break-word;"><a href="https://dogechain.info/address/<?php echo $orders["doge_public"];?>" target="_blank"><?php echo $orders["doge_public"];?></a></td>
                    <td>&ETH; <?php echo $orders["amount"];?></td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-<?php echo $status_color; ?>" data-toggle="dropdown" style="display: flex;" ><?php echo $status_btn; ?></button>
                      </div>

                    </td>
                    <td><?php echo $orders["date"];?></td>
                  </tr>
                  <?php
                  };};};
                  ?>
                  </tbody>
              </table>
          </div>