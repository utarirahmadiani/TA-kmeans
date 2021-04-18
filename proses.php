<?php
include('connection.php');


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
  $iterasi = 1;

  $nu_centr_1 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);
  $nu_centr_2 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);
  $nu_centr_3 = array('persediaan' => 0, 'keluar' => 0, 'sisa_stok' => 0);

  $c1_count = 0;
  $c2_count = 0;
  $c3_count = 0;
  do{
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
  }while($nu_centr_1 != $nu_centr_1 || $centroid_2 != $nu_centr_2 || $centroid_3 != $nu_centr_3);

}
else{
  echo "Tentukan centroid dahulu&nbsp;<a href='clustering.php'> di sini </a>";
}
// print("<pre>".print_r($k, true)."</pre>");

?>