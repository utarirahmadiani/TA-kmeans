<?php
session_start(); 
include("connection.php"); ?>
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
                <li><a href="data.php">Data Obat</a></li>
                <li  class="active"><a href="clustering.php">Clustering</a></li>
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
              
              <div class="col-md-4 float-md-right">
              <div class="col-md-12 mb-0">
              <a href="hasil.php" class="btn btn-primary btn-md px-4">Lihat Hasil Clustering</a></div>
            </div>
          </div>
        </form>
      </div>
    <div class="row mb-5">
      <br>
      <?php
      // $k = array();
      if(isset($_POST['submit'])){
        $c1 = $_POST['c1'];
        $c2 = $_POST['c2'];
        $c3 = $_POST['c3'];

        $centroid_1 = array();
        $centroid_2 = array();
        $centroid_3 = array();

        // mencari min dan max
        $min_max = array();
        $sql_select = mysqli_query($conn, "SELECT COUNT(nama_obat), satuan FROM obat GROUP BY satuan");
        foreach ($sql_select as $data){
          $sql_select = mysqli_query($conn, "SELECT MIN(persediaan) AS min_persediaan, MIN(keluar) AS min_keluar, MIN(sisa_stok) AS min_sisa, MAX(persediaan) AS max_persediaan, MAX(keluar) AS max_keluar, MAX(sisa_stok) AS max_sisa FROM obat WHERE satuan = '".$data['satuan']."'");
          foreach ($sql_select as $datas){
            $min_max[$data['satuan']] = ['min' => min($datas['min_persediaan'], $datas['min_keluar'], $datas['min_sisa']), 'max' => max($datas['max_persediaan'], $datas['max_keluar'], $datas['max_sisa'])];
          }
        }

        // normalisasi
        $normalisasi = array();
        $min = 1;
        $max = 10;
        $sql_select = mysqli_query($conn, "SELECT * FROM obat");
        foreach ($sql_select as $data){
        // 𝑛𝑒𝑤𝑑𝑎𝑡𝑎 = (data−min)∗(newmax−newmin) / (max− 𝑚𝑖𝑛) + 𝑛𝑒𝑤𝑚𝑖𝑛
          $persediaan = round(($data['persediaan'] - $min_max[$data['satuan']]['min']) * ($max - $min) / ($min_max[$data['satuan']]['max'] - $min_max[$data['satuan']]['min']) + $min, 2);

          $keluar = round(($data['keluar'] - $min_max[$data['satuan']]['min']) * ($max - $min) / ($min_max[$data['satuan']]['max'] - $min_max[$data['satuan']]['min']) + $min, 2);

          $sisa_stok = round(($data['sisa_stok'] - $min_max[$data['satuan']]['min']) * ($max - $min) / ($min_max[$data['satuan']]['max'] - $min_max[$data['satuan']]['min']) + $min, 2);

          array_push($normalisasi, ['nama_obat' => $data['nama_obat'], 'satuan' => $data['satuan'], 'persediaan' => $persediaan, 'keluar' => $keluar, 'sisa_stok' => $sisa_stok]);

          if($data['id_obat'] == $c1){
            $centroid_1 = ['persediaan' => $persediaan, 'keluar' => $keluar, 'sisa_stok' => $sisa_stok];
          }

          if($data['id_obat'] == $c2){
            $centroid_2 = ['persediaan' => $persediaan, 'keluar' => $keluar, 'sisa_stok' => $sisa_stok];
          }

          if($data['id_obat'] == $c3){
            $centroid_3 = ['persediaan' => $persediaan, 'keluar' => $keluar, 'sisa_stok' => $sisa_stok];
          }
        }
        // print("<pre>".print_r($normalisasi[0]['persediaan'], true)."</pre>"); exit();
        // print("<pre>".print_r($centroid_1, true)."</pre>"); exit();
        ?>

        <br>

        <h6 >Centroid Awal</h6>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th colspan="3" class="text-center">Cluster 1</th>
              <th colspan="3" class="text-center">Cluster 2</th>
              <th colspan="3" class="text-center">Cluster 3</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td align="center"><?php echo $centroid_1['persediaan']; ?></td>
              <td align="center"><?php echo $centroid_1['keluar']; ?></td>
              <td align="center"><?php echo $centroid_1['sisa_stok']; ?></td>
              <td align="center"><?php echo $centroid_2['persediaan']; ?></td>
              <td align="center"><?php echo $centroid_2['keluar']; ?></td>
              <td align="center"><?php echo $centroid_2['sisa_stok']; ?></td>
              <td align="center"><?php echo $centroid_3['persediaan']; ?></td>
              <td align="center"><?php echo $centroid_3['keluar']; ?></td>
              <td align="center"><?php echo $centroid_3['sisa_stok']; ?></td>
            </tr>
          </tbody>
        </table>
        <div id="accordion">
          <?php
          $iterasi = 1;

          $nu_centr_1 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);
          $nu_centr_2 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);
          $nu_centr_3 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);

          $c1_count = 0;
          $c2_count = 0;
          $c3_count = 0;
          do{
            ?>
            <div class="card">
              <div class="card-header" id="heading_<?php echo $iterasi; ?>">
                <h5 class="mb-0">
                  <button class="btn btn-link" data-toggle="collapse" data-target="#collapse_<?php echo $iterasi; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $iterasi; ?>">
                    Iterasi <?php echo $iterasi ?>
                  </button>
                </h5>
              </div>

              <div id="collapse_<?php echo $iterasi; ?>" class="collapse" aria-labelledby="heading_<?php echo $iterasi; ?>" data-parent="#accordion">
                <div class="card-body">
                  <?php
                  if($c1_count + $c2_count + $c3_count > 0){
                    $centroid_1 = array();
                    $array_object = new ArrayObject($nu_centr_1);
                    $centroid_1 = $array_object->getArrayCopy();

                    $centroid_2 = array();
                    $array_object = new ArrayObject($nu_centr_2);
                    $centroid_2 = $array_object->getArrayCopy();

                    $centroid_3 = array();
                    $array_object = new ArrayObject($nu_centr_3);
                    $centroid_3 = $array_object->getArrayCopy();

                    $c1_count = 0;
                    $c2_count = 0;
                    $c3_count = 0;
                  }
                  ?>
                  <?php
                    $sum_1 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);
                    $sum_2 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);
                    $sum_3 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);

                    $sql_select = mysqli_query($conn, "SELECT * FROM obat");
                    $no=0;
                    $k = array();
                    // Perhitungan data
                    foreach ($normalisasi as $data){
                      // Centroid 1
                      $x = round(sqrt(pow($data['persediaan'] - $centroid_1['persediaan'], 2) + pow($data['keluar'] - $centroid_1['keluar'], 2) + pow($data['sisa_stok'] - $centroid_1['sisa_stok'], 2)), 2);

                      // Centroid 2
                      $y = round(sqrt(pow($data['persediaan'] - $centroid_2['persediaan'], 2) + pow($data['keluar'] - $centroid_2['keluar'], 2) + pow($data['sisa_stok'] - $centroid_2['sisa_stok'], 2)), 2);

                      // Centroid 3
                      $z = round(sqrt(pow($data['persediaan'] - $centroid_3['persediaan'], 2) + pow($data['keluar'] - $centroid_3['keluar'], 2) + pow($data['sisa_stok'] - $centroid_3['sisa_stok'], 2)), 2);

                      $clust = 0;

                      // print("<pre>".print_r([$x, $y, $z], true)."</pre>"); exit();

                      if ($x < $y AND $x < $z) {
                        $sum_1['persediaan'] += $data['persediaan'];
                        $sum_1['keluar'] += $data['keluar'];
                        $sum_1['sisa_stok'] += $data['sisa_stok'];
                        // $nu_centr_1 = $cluster + $x;
                        $c1_count += 1;
                        $clust = 1;
                      }
                      else if($y < $x AND $y < $z){
                        $sum_2['persediaan'] += $data['persediaan'];
                        $sum_2['keluar'] += $data['keluar'];
                        $sum_2['sisa_stok'] += $data['sisa_stok'];

                        $c2_count += 1;
                        $clust = 2;
                      }
                      else{
                        $sum_3['persediaan'] += $data['persediaan'];
                        $sum_3['keluar'] += $data['keluar'];
                        $sum_3['sisa_stok'] += $data['sisa_stok'];

                        $c3_count += 1;
                        $clust = 3;
                      }
                      array_push($k, ['cluster' => $clust, 'nama_obat' => $data['nama_obat'], 'satuan' => $data['satuan'], 'persediaan' => round($x, 2), 'keluar' => round($y, 2), 'sisa_stok' => round($z, 2)]);
                    }
                    $nu_centr_1['persediaan'] = round($sum_1['persediaan']/$c1_count, 2);
                    $nu_centr_1['keluar'] = round($sum_1['keluar']/$c1_count, 2);
                    $nu_centr_1['sisa_stok'] = round($sum_1['sisa_stok']/$c1_count, 2);

                    $nu_centr_2['persediaan'] = round($sum_2['persediaan']/$c2_count, 2);
                    $nu_centr_2['keluar'] = round($sum_2['keluar']/$c2_count, 2);
                    $nu_centr_2['sisa_stok'] = round($sum_2['sisa_stok']/$c2_count, 2);

                    $nu_centr_3['persediaan'] = round($sum_3['persediaan']/$c3_count, 2);
                    $nu_centr_3['keluar'] = round($sum_3['keluar']/$c3_count, 2);
                    $nu_centr_3['sisa_stok'] = round($sum_3['sisa_stok']/$c3_count, 2);

                    // print("<pre>".print_r([$nu_centr_1, $nu_centr_2, $nu_centr_3], true)."</pre>");

                    $iterasi++;
                  ?>
                  <!-- Menampilkan new centroid -->
                  <div class="site-blocks-table">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th colspan="3" class="text-center">Cluster 1</th>
                        <th colspan="3" class="text-center">Cluster 2</th>
                        <th colspan="3" class="text-center">Cluster 3</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo $nu_centr_1['persediaan']; ?></td>
                        <td><?php echo $nu_centr_1['keluar']; ?></td>
                        <td><?php echo $nu_centr_1['sisa_stok']; ?></td>
                        <td><?php echo $nu_centr_2['persediaan']; ?></td>
                        <td><?php echo $nu_centr_2['keluar']; ?></td>
                        <td><?php echo $nu_centr_2['sisa_stok']; ?></td>
                        <td><?php echo $nu_centr_3['persediaan']; ?></td>
                        <td><?php echo $nu_centr_3['keluar']; ?></td>
                        <td><?php echo $nu_centr_3['sisa_stok']; ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                  <!-- End Menampilkan new centroid -->

                  <!-- Menampilkan perhitungan data -->
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Obat</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Persediaan</th>
                        <th class="text-center">Keluar</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Cluster</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 0;
                      foreach ($k as $value) {
                        ?>
                        <tr>
                          <td align="center"><?php echo ++$no; ?></td>
                          <td><?php echo $value['nama_obat']; ?></td>
                          <td align="center"><?php echo $value['satuan']; ?></td>
                          <td align="center"><?php echo $value['persediaan']; ?></td>
                          <td align="center"><?php echo $value['keluar']; ?></td>
                          <td align="center"><?php echo $value['sisa_stok']; ?></td>
                          <td align="center"><?php echo $value['cluster']; ?></td>
                        </tr>
                        <?php
                      }
                      ?>
                    </tbody>
                  </table>
                  <!-- End Menampilkan perhitungan data -->
                </div>
              </div>
            </div>
            <?php
          }while($nu_centr_1 != $nu_centr_1 || $centroid_2 != $nu_centr_2 || $centroid_3 != $nu_centr_3);
          // print("<pre>".print_r($k, true)."</pre>"); exit();
          
          $_SESSION['normalisasi'] = $k;
          ?>
        </div>
        <?php
      }
      else{
        echo "Tentukan centroid dahulu&nbsp;<a href='clustering.php'> di sini </a>";
      }
      ?>
    </div>
  </div>
</div>
<?php include("footer.php"); ?>