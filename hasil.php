<!DOCTYPE html>
<html lang="en">

<head>
  <title>Pharmasss</title>
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
	      	<div class="col-md-12 mb-0"><a href="data.php">Data</a> <span class="mx-2 mb-0">/</span>
	        <strong class="text-black">Hasil Clustering</strong></div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php
	include 'connection.php';
	include 'action.php';

	// print("<pre>".print_r($normalisasi, true)."</pre>"); exit();

	$cluster_1 = $_POST['c1'];
	$cluster_2 = $_POST['c2'];
	$cluster_3 = $_POST['c3'];

	$centroid_awal = array('c1' => [], 'c2' => [], 'c3' => []);
	$slct_tahun = mysqli_query($conn, "SELECT * FROM tahun");
	foreach ($slct_tahun as $thn) {
		$centroid_awal['c1'][$thn['id_tahun']] =  $normalisasi[$cluster_1][$thn['id_tahun']];
		$centroid_awal['c2'][$thn['id_tahun']] =  $normalisasi[$cluster_2][$thn['id_tahun']];
		$centroid_awal['c3'][$thn['id_tahun']] =  $normalisasi[$cluster_3][$thn['id_tahun']];
	}
	// print("<pre>".print_r($centroid_awal, true)."</pre>"); exit();

	$centroid_awal_array_object = new ArrayObject($centroid_awal);
	$centroid = $centroid_awal_array_object->getArrayCopy();

	print("<pre>".print_r($centroid == $centroid_awal, true)."</pre>"); exit();

	$cluster = array();
	$count = array();
	$sum = array();

	$loop = 3;
	do {
		foreach ($normalisasi as $key => $value) {
		  $select_tahun = mysqli_query($conn, "SELECT * FROM tahun");
		  // $tahun = mysqli_fetch_assoc($select_tahun);
		  $c1 = 0;
		  $c2 = 0;
		  $c3 = 0;
		  foreach ($select_tahun as $tahun) {
		    $data = $value[$tahun['id_tahun']];
		    $centr_c1 = $normalisasi[$cluster_1][$tahun['id_tahun']];
		    $centr_c2 = $normalisasi[$cluster_2][$tahun['id_tahun']];
		    $centr_c3 = $normalisasi[$cluster_3][$tahun['id_tahun']];

		    $c1 += pow($data - $centr_c1, 2);
		    $c2 += pow($data - $centr_c2, 2);
		    $c3 += pow($data - $centr_c3, 2);
		  }
		  $clust_1 = round(sqrt($c1), 5);
		  $clust_2 = round(sqrt($c2), 5);
		  $clust_3 = round(sqrt($c3), 5);

		  print("<pre>".print_r([$clust_1, $clust_2, $clust_3], true)."</pre>");
		  /*
		  if($clust_1 < $clust_2 && $clust_1 < $clust_3){
		    $count['c1']++;
		    $sum['c1'] += $clust_1;
		  }
		  else if($clust_2 < $clust_1 && $clust_2 < $clust_3){
		    $count['c2']++;
		    $sum['c2'] += $clust_2;
		  }
		  else{
		    $count['c3']++;
		    $sum['c3'] += $clust_3;
		  }
		  */
		}
		// print("<pre>".print_r([$count, $sum], true)."</pre>");
		// exit();
	} while($centroid != $centroid_awal);

	?>

  </div>