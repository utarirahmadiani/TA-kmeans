<!-- import excel ke mysql -->
<!-- www.malasngoding.com -->

<?php 
// menghubungkan dengan koneksi
include 'connection.php';
// menghubungkan dengan library excel reader
include "excel_reader2.php";
?>

<?php
// upload file xls
$select_all ="SELECT * FROM obat;";
$result = mysqli_query($conn, $select_all);
$data_count = mysqli_num_rows($result);

// print("<pre>".print_r($data_count, true)."</pre>");  exit();

if($data_count > 0){
	$disable_fk_check ="SET FOREIGN_KEY_CHECKS = 0;";
	mysqli_query($conn, $disable_fk_check);

	$truncate_obat ="TRUNCATE TABLE obat;";
	mysqli_query($conn, $truncate_obat);

	$truncate_tahun ="TRUNCATE TABLE tahun;";
	mysqli_query($conn, $truncate_tahun);

	$truncate_detail ="TRUNCATE TABLE detail_obat;";
	mysqli_query($conn, $truncate_detail);
}


$target = basename($_FILES['fileobat']['name']) ;
// print("<pre>".print_r($target, true)."</pre>");  exit();

move_uploaded_file($_FILES['fileobat']['tmp_name'], $target);

// beri permisi agar file xls dapat di baca
chmod($_FILES['fileobat']['name'],0777);

// mengambil isi file xls
$data = new Spreadsheet_Excel_Reader($_FILES['fileobat']['name'],false);
// menghitung jumlah baris data yang ada
$jumlah_baris = $data->rowcount($sheet_index=0);
$jumlah_kolom = $data->colcount($sheet_index=0);

// var_dump($jumlah_kolom); exit();
for ($i = 3; $i <= $jumlah_kolom; $i++){
	$tahun = $data->val(1, $i);
	mysqli_query($conn, "INSERT INTO tahun (tahun) VALUES ($tahun)");
	// print("<pre>".print_r($tahun, true)."</pre>"); // exit();
}

// jumlah default data yang berhasil di import
$berhasil = 0;
for ($i=2; $i<=$jumlah_baris; $i++){
	$nama_obat = $data->val($i, 1);
	$satuan = $data->val($i, 2);
	mysqli_query($conn,"INSERT INTO obat (nama_obat, satuan) VALUES ('$nama_obat','$satuan')");
	for($j = 3; $j <= $jumlah_kolom; $j++){
		$data_obat = $data->val($i, $j);
		$tahun = $data->val(1, $j);

		mysqli_query($conn,"INSERT INTO detail_obat VALUES ('', (SELECT id_obat FROM obat WHERE nama_obat = '$nama_obat'), (SELECT id_tahun FROM tahun WHERE tahun = '$tahun'), '$data_obat')");

		// print("<pre>".print_r([$id_tahun, $id_obat, $data_obat], true)."</pre>"); // exit();		
	}
	$berhasil++;
}


// hapus kembali file .xls yang di upload tadi
unlink($_FILES['fileobat']['name']);

// alihkan halaman ke index.php
header("location:data.php?berhasil=$berhasil");
?>