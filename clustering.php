<?php
include 'connection.php';
include 'action.php';

$cluster_1 = $_POST['c1'];
$cluster_2 = $_POST['c2'];
$cluster_3 = $_POST['c3'];

$cluster = array();

// $select_cluster_data = mysqli_query($conn, "SELECT * FROM detail_obat WHERE id_obat = $cluster_1 OR id_obat = $cluster_2 OR id_obat = $cluster_3");
// foreach ($select_cluster_data as $clusters) {
//   if($clusters['id_obat'] == $cluster_1){
//     $cluster['c1'][$clusters['id_tahun']] = $clusters['data_obat'];
//   }
//   // $cluster[$cluster_1][$clusters['id_tahun']] = $clusters['data_obat'];

//   // $cluster['c1'][$clusters['id_tahun']] = $clusters['data_obat'];
//   // $cluster['c2'][$clusters['id_tahun']] = $clusters['data_obat'];
//   // $cluster['c3'][$clusters['id_tahun']] = $clusters['data_obat'];
// }
// print("<pre>".print_r($cluster, true)."</pre>"); exit();

$count = array();
$sum = array();

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
exit();
?>