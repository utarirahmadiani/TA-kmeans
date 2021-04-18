<?php
include('connection.php');

$min_max = array();
$satuan_slct = mysqli_query($conn, "SELECT MIN(data_obat) AS min, MAX(data_obat) AS max, obat.satuan AS satuan FROM detail_obat JOIN obat ON detail_obat.id_obat = obat.id_obat WHERE satuan = (SELECT satuan FROM obat WHERE detail_obat.id_obat = obat.id_obat) GROUP BY satuan;");
foreach ($satuan_slct as $dt_min_max) {
  // print("<pre>".print_r($datas, true)."</pre>");
  $min_max[$dt_min_max['satuan']] = ['min' => $dt_min_max['min'], 'max' => $dt_min_max['max']];
}
// print("<pre>".print_r($ min_max['ampul']['min'], true)."</pre>"); exit();

$normalisasi = array();
// $index = 1;
$min = 0;
$max = 1;
$select_obat = mysqli_query($conn, "SELECT * FROM obat");
foreach ($select_obat as $obat) {
  $id_obat = $obat['id_obat'];
  $normalisasi[$id_obat]['nama_obat'] = $obat['nama_obat'];
  $normalisasi[$id_obat]['satuan'] = $obat['satuan'];
  $select_tahun = mysqli_query($conn, "SELECT * FROM tahun");
  foreach ($select_tahun as $tahun) {
    $id_tahun = $tahun['id_tahun'];
    $select_data = mysqli_query($conn, "SELECT * FROM detail_obat WHERE id_obat = $id_obat AND id_tahun = $id_tahun");
    $data = mysqli_fetch_assoc($select_data); 
    // 𝑛𝑒𝑤𝑑𝑎𝑡𝑎 = (data−min)∗(newmax−newmin) / (max− 𝑚𝑖𝑛) + 𝑛𝑒𝑤𝑚𝑖𝑛
    $normalisasi[$id_obat][$id_tahun] = round(($data['data_obat'] - $min_max[$obat['satuan']]['min']) * ($max - $min) / ($min_max[$obat['satuan']]['max'] - $min_max[$obat['satuan']]['min']) + $min, 5);
  }
  // $index++;
}

// print("<pre>".print_r($normalisasi, true)."</pre>"); exit();

// array_push($min_max, [])
// $satuan_slct = mysqli_query($conn, "SELECT satuan FROM obat GROUP BY satuan");
// foreach ($satuan_slct as $dt_satuan){
//   $satuan = $dt_satuan['satuan'];
//   $satuan_slct = mysqli_query($conn, "SELECT MIN(data_obat) AS min, MAX(data_obat) AS max, obat.satuan AS satuan FROM detail_obat JOIN obat ON detail_obat.id_obat = obat.id_obat WHERE satuan = '$satuan'");
//   // print("<pre>".print_r($satuan_slct, true)."</pre>"); exit();
//   $dt_min_max = mysqli_fetch_assoc($satuan_slct);
//   array_push($min_max, ['satuan' => $dt_min_max['satuan'], 'min' => $dt_min_max['min'], 'max' => $dt_min_max['max']]);
// }
// print("<pre>".print_r($min_max, true)."</pre>"); exit();
/*
$nu_min = 0;
$nu_max = 1;
$data_slct = mysqli_query($conn, "SELECT MIN(data_obat) AS min, MAX(data_obat) AS max, obat.satuan AS satuan FROM detail_obat JOIN obat ON detail_obat.id_obat = obat.id_obat WHERE satuan = '$satuan'");

$sql_select = mysqli_query($conn, "SELECT * FROM obat");
foreach ($sql_select as $data){
  $id_obat  = $data['id_obat'];
  $satuan = $data['satuan'];
  // $select_min_max = mysqli_query($conn, "SELECT MIN(persediaan) AS min_persediaan, MIN(keluar) AS min_keluar, MIN(sisa_stok) AS min_sisa, MAX(persediaan) AS max_persediaan, MAX(keluar) AS max_keluar, MAX(sisa_stok) AS max_sisa FROM obat WHERE satuan = '".$satuan."'");
  $select_min_max = mysqli_query($conn, "SELECT * detail_obat WHERE ");

  $row = mysqli_fetch_assoc($select_min_max);
  $min = min($row['min_persediaan'], $row['min_keluar'], $row['min_sisa']);
  $max = max($row['max_persediaan'], $row['max_keluar'], $row['max_sisa']);

  // print("<pre>".print_r([$min, $max, $data['persediaan'], $data['keluar'], $data['sisa_stok']], true)."</pre>");

  $nu_min = 1;
  $nu_max = 10;
  $a = round(($data['persediaan'] - $min) * ($nu_max - $nu_min) / ($max - $min) + $nu_min, 2);
  $b = round(($data['keluar'] - $min) * ($nu_max - $nu_min) / ($max - $min) + $nu_min, 2);
  $c = round(($data['sisa_stok'] - $min) * ($nu_max - $nu_min) / ($max - $min) + $nu_min, 2);
  array_push($normalisasi, ['nama_obat' => $data['nama_obat'], 'satuan' => $data['satuan'], 'persediaan' => $a, 'keluar' => $b, 'sisa_stok' => $c]);
} */
// print("<pre>".print_r($normalisasi, true)."</pre>");
?>