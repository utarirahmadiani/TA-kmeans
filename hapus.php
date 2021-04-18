<?php

	include ('connection.php');
	 $query = "TRUNCATE TABLE obat"; 
	 mysqli_query($conn, $query);
	 echo "<script>window.alert('Sukses Menghapus Data Objek. . .');
        window.location=('data.php')</script>";  


?>	