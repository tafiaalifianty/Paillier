<!DOCTYPE html>
<html>
<head>
    <title>Form Input Penjualan</title>
    <!-- Load file CSS Bootstrap offline -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

</head>
<body>
<div class="container">
    <?php
    //Include file koneksi, untuk koneksikan ke database
    include "koneksi.php";
    
    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nama=input($_POST["nama"]);
        $penjualan=input($_POST["penjualan"]);
        $a = input($_POST["inputa"]);
        $b = input($_POST["inputb"]);
        $c = input($_POST["inputc"]);
        $numbit = input($_POST["numbit"]);
        // $penjualan=$penjualan.$a.$b.$c;

        //Query input menginput data kedalam tabel penjualan
        $sql="insert into penjualan (nama,total_penjualan,kunci,nilai_satu,nilai_dua) values
		('$nama','$penjualan','$numbit',$a,$b)";

        //Mengeksekusi/menjalankan query diatas
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hasil) {
            header("Location:index.php");
        }
        else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

        }

    }
    ?>
    <h2>Input Data</h2>


    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukan Nama" required/>

        </div>
        
        <div class="form-group">
            <label>Penjualan</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="inputa" id="inputA" class="form-control" placeholder="Harga Barang 1" required/>        
                </div>    
                <div class="col-md-6">
                    <input type="text" name="inputb" id="inputB" class="form-control" placeholder="Harga Barang 2" required/>        
                </div>    
            </div>
            
        </div>

        <div class="form-group">
            <label for="inputNumbits">Number of bits</label>
            <!-- <input value="1024" type="text" id="inputNumbits" placeholder="Integer"> -->
            <select id="inputNumbits" name="numbit" class="form-control" onchange="">
            <?php 
                $sql="select * from kunci order by id asc";
                $hasil=mysqli_query($kon,$sql);
                $no=0;
                while ($data = mysqli_fetch_array($hasil)) {
            ?>
                <option value='<?php echo $data["numbit"];?>'><?php echo $data["numbit"];?></option>
            <?php 
                }
            ?>
            </select>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <button type="button" id="btn_genkeypair" class="btn btn-primary">Generate keypair</button>
                </div>
                <div class="col-md-4">
                    <p>public n: <span id="pubn" class="ciphertext">-</span></p>
                </div>
                <div class="col-md-4">
                    <p>private lambda: <span id="privl" class="ciphertext">-</span></p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <button id="btn_encrypt" type="button" class="btn btn-danger">Encrypt</button>
                </div>
                <div class="col-md-4">
                    <p>[A] = <span class="ciphertext" id="encA">-</span></p>
                </div>
                <div class="col-md-4">
                    <p>[B] = <span class="ciphertext" id="encB">-</span></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button id="btn_add" type="button" class="btn btn-danger">Calculate [A+B]</button>
                </div>
                <div class="col-md-4">
                    <p>[A + B] = <span class="ciphertext" id="encAB">-</span></p>
                </div>
                <div class="col-md-4">
                    
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Kalikan Penjualan Dengan</label>
            <input type="text" id="inputC" name="inputc" class="form-control" placeholder="Masukkan nilai Pengali" required />
            <br>
            <div class="row">
                <div class="col-md-4">
                    <button id="btn_mult" type="button" class="btn btn-danger">Calculate [(A+B)*C]
                    </button>
                </div>
                <div class="col-md-2">
                    <p>[(A + B)*C] = <!-- <span class="ciphertext" id="encABC">-</span> --></p>
                </div>
                <div class="col-md-6">
                    <input type="text" name="penjualan" id="penjualan" class="form-control" required />
                </div>
            </div>
        </div>
        <input type="submit" name="submit" value="Simpan ke Database" class="btn btn-primary"></input>
    </form>
</div>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/jsbn/jsbn.js"></script>
<script type="text/javascript" src="js/jsbn/jsbn2.js"></script>
<script type="text/javascript" src="js/jsbn/prng4.js"></script>
<script type="text/javascript" src="js/jsbn/rng.js"></script>
<script type="text/javascript" src="js/paillier.js"></script>

<script type="text/javascript">
var keys, encA, encB, encAB, encABC;
$(function() {
    $('#btn_genkeypair').click(function(event) {
        var e = document.getElementById("inputNumbits");
        var numBits = parseInt($('#inputNumbits').text());
        if (numBits % 2 === 0) {
            var startTime = new Date().getTime(),
                elapsed;
            
            var myArr = JSON.parse(e.value);
            console.log(numBits);
            keys = paillier.generateKeys(numBits);
            // keys = myArr;
            elapsed = new Date().getTime() - startTime;
            // myArrString = JSON.stringify(keys);
            console.log(keys);
            
            $('#keygentime').html(elapsed);
            $('#pubn').html(keys.pub.n.toString());
            $('#privl').html(keys.sec.lambda.toString());           
        } else {
            alert("Please enter an even number of bits :)");
        }
    });
    $('#btn_precompute').click(function(event) {
        var numPrecomputations = parseInt($('#inputNumPrecompute').val()),
            startTime, elapsed;
        startTime = new Date().getTime();
        keys.pub.precompute(numPrecomputations);
        elapsed = new Date().getTime() - startTime;
        $('#precomputetime').html(elapsed);
    });
    $('#btn_encrypt').click(function(event) {
        var valA = parseInt($('#inputA').val()),
            valB = parseInt($('#inputB').val()),
            startTime,
            elapsed;

        startTime = new Date().getTime();
        encA = keys.pub.encrypt(nbv(valA));
        elapsed = new Date().getTime() - startTime;
        $('#encA').html(encA.toString());
        $('#encAtime').html(elapsed);

        startTime = new Date().getTime();
        encB = keys.pub.encrypt(nbv(valB));
        elapsed = new Date().getTime() - startTime;
        $('#encBtime').html(elapsed);
        $('#encB').html(encB.toString());
    });
    $('#btn_add').click(function(event) {
        var startTime,
            elapsed;
        startTime = new Date().getTime();
        encAB = keys.pub.add(encA,encB);
        elapsed = new Date().getTime() - startTime;
        $('#addtime').html(elapsed);
        $('#encAB').html(encAB.toString());
    });
    $('#btn_randadd').click(function() {
        var startTime,
            elapsed;
        startTime = new Date().getTime();
        encAB = keys.pub.randomize(encAB);
        elapsed = new Date().getTime() - startTime;
        $('#randaddtime').html(elapsed);
        $('#encAB').html(encAB.toString());
    });
    $('#btn_mult').click(function(event) {
        var valC = parseInt($('#inputC').val()),
            startTime,
            elapsed;
        startTime = new Date().getTime();
        encABC = keys.pub.mult(encAB,nbv(valC));
        elapsed = new Date().getTime() - startTime;
        $('#multtime').html(elapsed);
        $('#encABC').html(encABC.toString());
        document.getElementById("penjualan").value = encABC.toString();
    });
    $('#btn_randmult').click(function() {
        var startTime,
            elapsed;
        startTime = new Date().getTime();       
        encABC = keys.pub.randomize(encABC);
        elapsed = new Date().getTime() - startTime;
        $('#randmulttime').html(elapsed);       
        $('#encABC').html(encABC.toString());
    }); 
    $('#btn_decrypt').click(function() {
        var plaintext,
            startTime,
            chipp,
            elapsed;
        startTime = new Date().getTime();
        plaintext = keys.sec.decrypt(encABC).toString(10)
        elapsed = new Date().getTime() - startTime;
        $('#plainABC').html(plaintext);
        $('#decrypttime').html(elapsed);
    });
});
</script>

</body>
</html>