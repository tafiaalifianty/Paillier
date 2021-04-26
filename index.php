<!DOCTYPE html>
<html>
<head>
    <!-- Load file CSS Bootstrap offline -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <br>
    <h4>Data Penjualan Mengggunakan Enkripsi Paillier</h4>
    <a href="create.php" class="btn btn-primary" role="button">Tambah Data</a>
    <br>
	<?php

    include "koneksi.php";

    //Cek apakah ada nilai dari method GET dengan nama id
    if (isset($_GET['id'])) {
        $id=htmlspecialchars($_GET["id"]);

        $sql="delete from penjualan where id='$id' ";
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak
            if ($hasil) {
                header("Location:index.php");

            }
            else {
                echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";

            }
        }
	?>


    <table class="table table-bordered table-hover">
        <br>
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Jumlah yang Terenkripsi</th>
            <th colspan='2'>Aksi</th>

        </tr>
        </thead>
        <?php
        include "koneksi.php";
        $sql="select * from penjualan order by id desc";

        $hasil=mysqli_query($kon,$sql);
        $no=0;
        while ($data = mysqli_fetch_array($hasil)) {
            $no++;

            ?>
            <tbody>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $data["nama"];   ?></td>
                <td><?php echo $data["tanggal"];   ?></td>
                <td><?php echo $data["total_penjualan"];   ?></td>
                <td>
                    <a href="update.php?id=<?php echo htmlspecialchars($data['id']); ?>" class="btn btn-warning" role="button">Update</a>
                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id=<?php echo $data['id']; ?>" class="btn btn-danger" role="button">Delete</a>
                </td>
            </tr>
            </tbody>
            <?php
        }
        ?>
    </table>

</div>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/jsbn/jsbn.js"></script>
<script type="text/javascript" src="js/jsbn/jsbn2.js"></script>
<script type="text/javascript" src="js/jsbn/prng4.js"></script>
<script type="text/javascript" src="js/jsbn/rng.js"></script>
<script type="text/javascript" src="js/paillier.js"></script>
<script type="text/javascript">
// var keys, encA, encB, encAB, encABC;
// 	window.onload = function() {
// 		var numBits = parseInt("32");
// 		if (numBits % 2 === 0) {

// 			keys = paillier.generateKeys(numBits);
// 			// keys = myArr;
// 			elapsed = new Date().getTime() - startTime;
// 			// myArrString = JSON.stringify(keys);
// 			console.log(keys);
// 			// alert("mantabs");
					
// 		} else {
// 			alert("Please enter an even number of bits :)");
// 		}


// 	}

</script>

</body>
</html>