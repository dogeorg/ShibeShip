   <!-- Main content -->
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo count($pdo->query("SELECT * FROM products")->fetchAll()); ?></h3>

                <p><?php echo $lang["admin_listings"]; ?></p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <a href="?d=listings" class="small-box-footer"><?php echo $lang["admin_more_info"]; ?> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo count($pdo->query("SELECT * FROM shibes")->fetchAll()); ?></h3>

                <p><?php echo $lang["admin_total_shibes"]; ?></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <a href="?d=shibes" class="small-box-footer"><?php echo $lang["admin_more_info"]; ?> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo count($pdo->query("SELECT * FROM generated where paid = 1")->fetchAll()); ?></h3>

                <p><?php echo $lang["admin_pending_cart"]; ?></p>
              </div>
              <div class="icon">
                <i class="fas fa-cart-arrow-down"></i>
              </div>
              <a href="?d=orders" class="small-box-footer"><?php echo $lang["admin_more_info"]; ?> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
    <?php include("orders.php"); ?>