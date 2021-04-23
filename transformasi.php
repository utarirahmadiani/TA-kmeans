<?php include("connection.php"); 
?>
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
            <div class="col-md-12 mb-0"><a href="data.php">Data</a>
              <span class="mx-2 mb-0">/</span>
              <strong class="text-black">Data Normalisasi</strong>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row mb-12">
          <div class="col-md-12">
            <div class="row mb-3">
              <div class="col-md-4">
                <!-- Button trigger modal --> 
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">CLUSTERING</button>

              <!-- Modal -->
              <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">INPUTKAN CENTROID</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">

                      <form method="post" class="row" action="hasil.php">
                        <div class="col-md-12">
                          <div class="p-3 p-lg-5 border">
                            <div class="form-group row">
                              <div class="col-md-4">
                                <label for="c_fname" class="text-black">Centroids C1</label>
                                <input type="number" class="form-control" id="c1" name="c1" placeholder="baris ke" required="">
                              </div>
                              
                              <div class="col-md-4">
                                <label for="c_email" class="text-black">Centroid C2 </label>
                                <input type="number" class="form-control" id="c2" name="c2" placeholder="baris ke" required="">
                              </div>

                              <div class="col-md-4">
                                <label for="c_subject" class="text-black">Centroid C3 </label>
                                <input type="number" class="form-control" id="c3" name="c3" placeholder="baris ke" required="">
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class="col-lg-12">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" name="submit" value="Proses">
                              </div>
                            </div>

                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              </div>
            </div> 
          </div>

            <div class="site-blocks-table col-md-12">
              <table class="iterasi">
                <thead>
                  <tr>
                    <th>NO. </th>
                    <th>NAMA OBAT</th>
                    <th>SATUAN</th>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM tahun");
                    foreach ($sql as $row) {
                      ?>
                      <th><?php echo $row['tahun']; ?></th>
                      <?php
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    include 'action.php';
                    $no = 0;
                    foreach ($normalisasi as $data) {
                      ?>
                      <tr>
                        <td><?php echo ++$no; ?></td>
                        <td><?php echo $data['nama_obat'] ?></td>
                        <td><?php echo $data['satuan'] ?></td>
                        <?php
                        $data_thn = mysqli_query($conn, "SELECT * FROM tahun");
                        foreach ($data_thn as $row) {
                          ?>
                          <td><?php echo $data[$row['id_tahun']]; ?></td>
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
          </div>
        </div>
      </div>
    </div>
<?php include("footer.php"); ?>
</body>
</html>