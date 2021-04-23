<!DOCTYPE html>
<html lang="en">

<head>
  <title>Pharma</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

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
	      	<div class="col-md-12 mb-0">
	      		<a href="data.php">Data</a>
	      		<span class="mx-2 mb-0">/</span>
	      		<strong class="text-black">Hasil Clustering</strong>
	    	</div>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="site-section">
		<div class="container">
			<div class="accordion" id="accordionExample">
				<?php
				include 'connection.php';
				include 'action.php';
				// print("<pre>".print_r($normalisasi, true)."</pre>"); exit();
				$graph = array();
				$grafik = array();
				$cluster_1 = $_POST['c1'];
				$cluster_2 = $_POST['c2'];
				$cluster_3 = $_POST['c3'];

				$centroid_awal = array(1 => [], 2 => [], 3 => []);
				$slct_tahun = mysqli_query($conn, "SELECT * FROM tahun");
				foreach ($slct_tahun as $thn) {
					$centroid_awal[1][$thn['id_tahun']] =  $normalisasi[$cluster_1][$thn['id_tahun']];
					$centroid_awal[2][$thn['id_tahun']] =  $normalisasi[$cluster_2][$thn['id_tahun']];
					$centroid_awal[3][$thn['id_tahun']] =  $normalisasi[$cluster_3][$thn['id_tahun']];
				}
				$clusters = array();
				$cluster = 3;
				$centroid = array();
				$nu_centr = array();
				$iterasi = 1;
				$last = array();
				$color = array();

				$ssw = array();
				?>
				<div class="accordion-item">
					<?php
					do {
						?>
					    <h2 class="accordion-header" id="heading_<?php echo $iterasi; ?>">
					    	<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $iterasi; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $iterasi; ?>">
					        <?php echo "Iterasi $iterasi"; ?></button>
					    </h2>
					    <div id="collapse_<?php echo $iterasi; ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo $iterasi; ?>" data-bs-parent="#accordionExample">
					    	<div class="accordion-body">
								<div class="row mb-5">
				          			<div class="col-md-8">
				          				<div class="site-blocks-table">
				          					<table class="iterasi">
						          				<?php
												if(count($centroid) == 0){
													$centroid_awal_array_object = new ArrayObject($centroid_awal);
													$centroid = $centroid_awal_array_object->getArrayCopy();
												}
												else{
													$nu_centr_array_object = new ArrayObject($nu_centr);
													$centroid = $nu_centr_array_object->getArrayCopy();
												}

												$count = array(1 => 0, 2 => 0, 3 => 0);
												$sum = array();
												for($i = 1; $i <= $cluster; $i++){
													// array_push($sum, $i);
													foreach ($slct_tahun as $value) {
														$sum[$i][$value['id_tahun']] = 0;
													}	
												}
												// print("<pre>".print_r($sum, true)."</pre>"); exit();
												?>
												<thead>
													<tr>
														<th>NO</th>
														<th>NAMA OBAT</th>
														<th>SATUAN</th>
														<th>C1</th>
														<th>C2</th>
														<th>C3</th>
													</tr>
												</thead>
												<tbody>
												<?php
												foreach ($normalisasi as $key => $value) {
												  ?>
												  <tr>
												  	<td><?php echo $key; ?></td>
												  	<td><?php echo $value['nama_obat']; ?></td>
												  	<td><?php echo $value['satuan']; ?></td>
												  	<?php
													$c1 = 0;
													$c2 = 0;
													$c3 = 0;
													foreach ($slct_tahun as $tahun) {
													  $data = $value[$tahun['id_tahun']];
													  $c1 += pow($data - $centroid[1][$tahun['id_tahun']], 2);
													  $c2 += pow($data - $centroid[2][$tahun['id_tahun']], 2);
													  $c3 += pow($data - $centroid[3][$tahun['id_tahun']], 2);
													}
													//jarak centroid
													$clust_1 = round(sqrt($c1), 5);
													$clust_2 = round(sqrt($c2), 5);
													$clust_3 = round(sqrt($c3), 5);
													// print("<pre>".print_r([$clust_1, $clust_2, $clust_3], true)."</pre>");
													$colored = "";
												    if($clust_1 < $clust_2 && $clust_1 < $clust_3){
												    	$colored = "c1";
												  		$count[1]++;
												  		$clusters[$key] = 1;
												  		$last[$key] = $clust_1;
												  		$color[$key] = "#DE3163";
														foreach ($slct_tahun as $value) {
															$sum[1][$value['id_tahun']] += $normalisasi[$key][$value['id_tahun']];
														}
														$graph[$key] =  ['x' => mt_rand(5, 15)/10, 'y' => $clust_1];
												  	}
												  	else if($clust_2 < $clust_1 && $clust_2 < $clust_3){
												  		$colored = "c2";
												  		$count[2]++;
												  		$clusters[$key] = 2;
												  		$last[$key] = $clust_2;
												  		$color[$key] = "#FFBF00";
												  		$graph[$key] = ['x' => mt_rand(15, 25)/10, 'y' => $clust_2];
												    	foreach ($slct_tahun as $value) {
												    		$sum[2][$value['id_tahun']] += $normalisasi[$key][$value['id_tahun']];
												    	}
												  	}
												  	else{
												  		$colored = "c3";
														$count[3]++;
														$clusters[$key] = 3;
														$last[$key] = $clust_3;
														$color[$key] = "#DFFF00";
														$graph[$key] = ['x' => mt_rand(25, 35)/10, 'y' => $clust_3];
														foreach ($slct_tahun as $value) {
													  		$sum[3][$value['id_tahun']] += $normalisasi[$key][$value['id_tahun']];
														}
												  	}
												  	?>
													<td style="background-color:<?php echo $colored == 'c1' ? '#A9A9A9' : ''; ?>"><?php echo round($clust_1, 5); ?></td>
													<td style="background-color:<?php echo $colored == 'c2' ? '#A9A9A9' : ''; ?>"><?php echo round($clust_2, 5); ?></td>
													<td style="background-color:<?php echo $colored == 'c3' ? '#A9A9A9' : ''; ?>"><?php echo round($clust_3, 5); ?></td>
												  </tr>
												  <?php
												  // $clusters[]
												}
												foreach ($slct_tahun as $value) {
													$nu_centr[1][$value['id_tahun']] = round($sum[1][$value['id_tahun']]/$count[1], 5);
													$nu_centr[2][$value['id_tahun']] = round($sum[2][$value['id_tahun']]/$count[2], 5);
													$nu_centr[3][$value['id_tahun']] = round($sum[3][$value['id_tahun']]/$count[3], 5);
												}
												// print("<pre>".print_r([$sum, $iterasi, $centroid, $nu_centr, $centroid != $nu_centr], true)."</pre>");
												$iterasi++;
												?>
											</table>
										</div>
									</div>
									<div class="col-md-4">
										<div class="site-blocks-table">
				          					<table class="iterasi">
				          						<thead>
				          							<tr>
				          								<th></th>
				          								<th>SUM</th>
				          								<th>COUNT</th>
				          								<th>NEW CENTROID</th>
				          							</tr>
				          						</thead>
				          						<tbody>
				          							<?php
				          							$numb = mysqli_num_rows($slct_tahun);
				          							for($i = 1; $i <= $cluster; $i++){
				          								$rowspan = true;
				          								?>
				      									<?php
				      									foreach ($slct_tahun as $value) {
				      										?>
				      										<tr>
				      											<?php
				      											if($rowspan == true){
				      												?>
				      												<td rowspan="<?php echo $numb; ?>"><?php echo "C".$i ?></td>
				      												<?php
				      											}
				      											?>	
				          										<td><?php echo $sum[$i][$value['id_tahun']]; ?></td>
				          										<?php
				      											if($rowspan == true){
				      												?>
				      												<td rowspan="<?php echo $numb; ?>"><?php echo $count[$i]; ?></td>
				      												<?php
				      											}
				      											?>
				          										<td><?php echo $nu_centr[$i][$value['id_tahun']]; ?></td>
				          									</tr>
				      									<?php
				      									$rowspan = false;
					          							}
				          							}
				          							?>
				          						</tbody>
				          						<tfoot>
				          							
				          						</tfoot>
				          					</table>
				          				</div>
									</div>
								</div>
					      	</div>
					    </div>
						<?php
					} while($centroid != $nu_centr);
					// print("<pre>".print_r($last, true)."</pre>");
					?>
				</div>
			</div>
		</div>
		<hr>
		<div class="container">
			<div class="row mb-5">
				<div class="col-md-8">
					<?php
					$total = array();
					for ($i = 1; $i <= $cluster; $i++) { 
						$total[$i] = 0;
					}

					for ($i=1; $i <= $cluster; $i++) {
						$no = 0;
						?>
						<h5>CLUSTER <?php echo $i; ?></h5>
						<div class="site-blocks-table">
	      					<table class="iterasi">
	      						<thead>
	      							<tr>
	      								<th>NO</th>
	      								<th>NAMA OBAT</th>
	      								<th>SATUAN</th>
	      								<?php
	      								foreach ($slct_tahun as $value) {
	      									?>
	      									<th><?php echo $value['tahun']; ?></th>
	      									<?php
	      								}
	      								?>
	      							</tr>
	      						</thead>
	      						<tbody>
	      							<?php
	      							$distance[$i] = 0;
	      							foreach ($normalisasi as $key => $value) {
	      								array_push($grafik, ['nama_obat' => $value['nama_obat'], 'cluster' => $clusters[$key], 'x' => mt_rand(($clusters[$key] - 0.4)*10, ($clusters[$key] + 0.4)*10)/10, 'y' => $last[$key], 'color' => $color[$key]]);
      									$square = 0;
	      								if($clusters[$key] == $i){
	      									?>
		      								<tr>
		      									<td><?php echo ++$no ?></td>
		      									<td><?php echo $normalisasi[$key]['nama_obat']; ?></td>
		      									<td><?php echo $normalisasi[$key]['satuan']; ?></td>
		      									<?php
		      									foreach ($slct_tahun as $thn) {
		      										?>
		      										<td><?php echo round($normalisasi[$key][$thn['id_tahun']], 5); ?></td>
		      										<?php
		      										$total[$i] += $normalisasi[$key][$thn['id_tahun']];
		      										$square += pow($normalisasi[$key][$thn['id_tahun']] - $nu_centr[$i][$thn['id_tahun']], 2);
		      									}
		      									// print("<pre>".print_r("distance = ".$distance, true)."</pre>");
		      									?>
		      								</tr>
		      								<?php
	      								}
	      								$distance[$i] += sqrt($square);
	      							}
	      							?>
	      						</tbody>
	      					</table>
	      					<br>
	      				</div>
		      		<?php
		      		$ssw[$i] = $distance[$i]/$count[$i];
					}
					// print("<pre>".print_r($grafik, true)."</pre>");
					?>
				</div>
				<div class="col-md-4">
					<h5>TINGKAT PEMAKAIAN OBAT</h5>
					<div class="site-blocks-table">
	      				<table class="iterasi">
	      					<thead>
	      						<tr>
	      							<th>CLUSTER</th>
	      							<th>TOTAL</th>
	      							<th>KETERANGAN</th>
	      						</tr>
	      					</thead>
	      					<tbody>
	      						<?php
	      						// print("<pre>".print_r(json_encode($graph), true)."</pre>");
	      						foreach ($total as $key => $value) {
	      							?>
	      							<tr>
		      							<td>CLUSTER<?php echo " ".$key; ?></td>
		      							<td><?php echo $value ?></td>
		      							<td>
		      								<?php
		      								if(max($total) == $value)
		      									echo "TINGGI";
		      								else if(min($total) == $value)
		      									echo "RENDAH";
		      								else
		      									echo "SEDANG";
		      								?>
		      							</td>
		      						</tr>
	      							<?php
	      						}
	      						?>
	      					</tbody>
	      				</table>
	      			</div>
	      			<br>
	      			<h5>SUM SQUARE WITHIN (SSW)</h5>
	      			<div class="site-blocks-table">
	      				<table class="iterasi">
	      					<thead>
	      						<tr>
	      							<th>CLUSTER</th>
	      							<th>SSW</th>
	      						</tr>
	      					</thead>
	      					<tbody>
	      						<?php
	      						foreach ($ssw as $key => $value) {
	      							?>
	      							<tr>
	      								<td><?php echo $key; ?></td>
	      								<td><?php echo round($value, 5); ?></td>
	      							</tr>
	      							<?php
	      						}
	      						?>
	      					</tbody>
	      				</table>
	      			</div>
	      			<?php $max = 0; ?>
	      			<br>
	      			<h5>SUM SQUARE BEETWEEN (SSB)</h5>
	      			<div class="site-blocks-table">
	      				<table class="iterasi">
	      					<thead>
	      						<tr>
	      							<th>CLUSTER</th>
	      							<th>SSB</th>
	      							<th>RASIO</th>
	      						</tr>
	      					</thead>
	      					<tbody>
	      						<?php
	      						for ($i = 1; $i <= count($nu_centr); $i++) { 
	      							for ($j = $i + 1; $j <= count($nu_centr); $j++) {
	      								$kuadrat = 0;
	      								foreach ($slct_tahun as $tahuns) {
	      									$kuadrat += pow($nu_centr[$i][$tahuns['id_tahun']] - $nu_centr[$j][$tahuns['id_tahun']], 2);
	      								}
	      								?>
	      								<tr>
	      									<td><?php echo "CLUSTER $i : $j"; ?></td>
	      									<td><?php echo round(sqrt($kuadrat), 5); ?></td>
	      									<td><?php echo round(array_sum($ssw)/sqrt($kuadrat), 5) ?></td>
	      									<?php
	      									$max = array_sum($ssw)/sqrt($kuadrat) > $max ? array_sum($ssw)/sqrt($kuadrat) : $max;
	      									?>
	      								</tr>
	      								<?php
	      							}
	      						}
	      						// print("<pre>".print_r($graph, true)."</pre>");
	      						?>
	      					</tbody>
	      				</table>
	      			</div>
	      			<br>
	      			<div class="site-blocks-table">
	      				<table class="iterasi">
	      					<thead>
	      						<tr>
	      							<th>DAVIES-BOULDIN INDEX (DBI)</th>
	      						</tr>
	      					</thead>
	      					<tbody>
	      						<tr><td><?php echo round((1/$cluster)*$max, 5); ?></td></tr>
	      					</tbody>
	      				</table>
	      			</div>
	      			<br>
	      			<?php // print("<pre>".print_r($grafik, true)."</pre>"); ?>
	      			
				</div>
			</div>
		</div>
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
	</div>	
  </div>
  <script>
		window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light2", 
			title:{
				text: "CLUSTERING"
			},
			axisX:{
				title: "Cluster",
				suffix: "", 
				interval : 1
			},
			axisY:{
				title: "Distance",
				suffix: ""
			},
			data: [{
				type: "scatter",
				markerType: "circle",
				markerSize: 5,
				markerColor : "#6495ED",
				toolTipContent: "Nama Obat : {nama_obat}<br>Cluster : {cluster} <br>Data : {color}",
				dataPoints: <?php echo json_encode($grafik, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart.render();
		 
		}
  </script>
  <!-- <script type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script> -->
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPEInjeksiNzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
  <?php include("footer.php"); ?>