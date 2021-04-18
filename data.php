<?php include("connection.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Pharma</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Rubik:400,700|Crimson+Text:400,400i" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">


  <link rel="stylesheet" href="css/aos.css">

  <link rel="stylesheet" href="css/style.css">

</head>

<body>

  <div class="site-wrap">


    <div class="site-navbar py-2">

      <div class="search-wrap">
        <div class="container">
          <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
          <form action="#" method="post">
            <input type="text" class="form-control" placeholder="Search keyword and hit enter...">
          </form>
        </div>
      </div>

      <div class="container">
        <div class="d-flex align-items-center justify-content-between">
          <div class="logo">
            <div class="site-logo">
              <a href="index.html" class="js-logo-clone">Pharma</a>
            </div>
          </div>
          <div class="main-nav d-none d-lg-block">
            <nav class="site-navigation text-right text-md-center" role="navigation">
              <ul class="site-menu js-clone-nav d-none d-lg-block">
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="data.php">Data Obat</a></li>
                <li><a href="clustering.php">Clustering</a></li>
              </ul>
            </nav>
          </div>
          
        </div>
      </div>
    </div>
    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0">
            <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
            <strong class="text-black">Data Obat</strong></div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row mb-5">
          <form class="col-md-12" method="post">
            <div class="row">
              
              
              <div class="col-md-4">
                <a href="upload.php" class="btn btn-primary btn-md px-4">upload file</a>
                
                <a href="hapus.php" class="btn btn-primary btn-md px-4">hapus data</a> 
              </div>
            
              <div class="col-md-4">
                
                <a href="transformasi.php" class="btn btn-primary btn-md px-4">normalisasi</a>
            </div>
          </div> 
          </div>

          <?php 
            if(isset($_GET['berhasil'])){
            echo "<p>".$_GET['berhasil']." Data berhasil di import.</p>";
            }
          ?>

            <div class="site-blocks-table">
              <table id="customers">
                <thead>
                  <tr>
                    <th>NO. </th>
                    <th>NAMA OBAT</th>
                    <th>SATUAN</th>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM tahun");
                    // var_dump($sql); exit();
                    foreach ($sql as $row) {
                      ?>
                      <th><?php echo $row['tahun'] ?></th>
                      <?php
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $select_obat = mysqli_query($conn, "SELECT * FROM obat");
                    $no = 0;
                    foreach ($select_obat as $obat) {
                      $id_obat = $obat['id_obat'];
                      ?>
                      <tr>
                        <td><?php echo ++$no; ?></td>
                        <td><?php echo $obat['nama_obat']; ?></td>
                        <td><?php echo $obat['satuan']; ?></td>
                        <?php
                        $select_tahun = mysqli_query($conn, "SELECT * FROM tahun");
                        foreach ($select_tahun as $tahun) {
                          $id_tahun = $tahun['id_tahun'];
                          $select_data = mysqli_query($conn, "SELECT * FROM detail_obat WHERE id_obat = $id_obat AND id_tahun = $id_tahun");
                          $data = mysqli_fetch_assoc($select_data); 
                          ?>
                          <td><?php echo $data['data_obat']; ?></td>
                          <?php
                        }
                        ?>
                      </tr>
                      <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
<?php include("footer.php"); ?>