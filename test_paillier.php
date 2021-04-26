<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Javascript Paillier demo page</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		div.result {
			background-color: #cccccc;
			word-wrap: break-word;
		}
		.cyphertext {
			 word-wrap: break-word;
		}
	</style>
</head>
<body>
<div class="container">
	<div class="page-header">
    <h1>Halaman Test Metode Paillier</small></h1>
    <?php include "koneksi.php"; ?>
	<h3>Key generation</h3>
    <div class="row">
    	<div class="span4">
    		<label for="inputNumbits">Number of bits</label>
    		<!-- <input value="1024" type="text" id="inputNumbits" placeholder="Integer"> -->
    		<select id="inputNumbits">
    		<?php 
    			$sql="select * from kunci order by id asc";
		        $hasil=mysqli_query($kon,$sql);
		        $no=0;
		        while ($data = mysqli_fetch_array($hasil)) {
    		?>
    			<option value='<?php echo $data["kunci"];?>'><?php echo $data["numbit"];?></option>
    		<?php 
    			}
    		?>
    		</select>
    		<button id="btn_genkeypair" class="btn btn-primary">Generate keypair</button>
    		<br><br>
    		<label for="inputNumPrecompute">Number of pre-computations</label>
    		<input value="4" type="text" id="inputNumPrecompute" placeholder="Integer">
    		<button id="btn_precompute" type="submit" class="btn">Pre-compute</button>
    	</div>
    	<div class="span7 offset1 result">
    		<p>public n: <span id="pubn" class="ciphertext">-</span></p>
    		<p>private lambda: <span id="privl" class="ciphertext">-</span></p>
    		<p>Elapsed time (keygen): <span id="keygentime">-</span> ms</p>
    		<p>Elapsed time (precomputation): <span id="precomputetime">-</span> ms</p>
    	</div>
    </div>


    <h3>Test input</h3>
    <div class="row">
    	<div class="span4">
    		<label class="control-label" for="inputA">Test input A</label>
    		<input value="" type="text" id="inputA" placeholder="Integer">
    		<label class="control-label" for="inputB">Test input B</label>
    		<input value="" type="text" id="inputB" placeholder="Integer">
    		<button id="btn_encrypt" type="submit" class="btn btn-primary">Encrypt</button>
        </div>
    	<div class="span7 offset1 result">
    		<p>[A] = <span class="ciphertext" id="encA">-</span></p>
    		<p>Elapsed time: <span id="encAtime">-</span> ms</p>    		
    		<p>[B] = <span class="ciphertext" id="encB">-</span></p>
    		<p>Elapsed time: <span id="encBtime">-</span> ms</p>
    	</div>
    </div>
    <h3>Encrypted addition</h3>
    <div class="row">
    	<div class="span4">
    		<button id="btn_add" type="submit" class="btn btn-primary">Calculate [A+B]</button>
    		<button id="btn_randadd" type="submit" class="btn">Randomize</button>
        </div>
    	<div class="span7 offset1 result">
    		<p>[A + B] = <span class="ciphertext" id="encAB">-</span></p>
    		<p>Elapsed time: <span id="addtime">-</span> ms</p>    		
    		<p>Elapsed time (randomize): <span id="randaddtime">-</span> ms</p>  
    	</div>
    </div>    
    <h3>Encrypted multiplication</h3>
    <div class="row">
    	<div class="span4">
    		<label class="control-label" for="inputC">Test input C</label>
    		<input type="text" id="inputC" placeholder="Integer">    		
    		<button id="btn_mult" type="submit" class="btn btn-primary">Calculate [(A+B)*C]</button>
    		<button id="btn_randmult" type="submit" class="btn">Randomize</button>
        </div>
    	<div class="span7 offset1 result">
    		<p>[(A + B)*C] = <span class="ciphertext" id="encABC">-</span></p>
    		<p>Elapsed time: <span id="multtime">-</span> ms</p>
    		<p>Elapsed time (randomize): <span id="randmulttime">-</span> ms</p>  		
    	</div>
    </div>     
    <h3>Decryption</h3>
    <div class="row">
    	<div class="span4">
    		<button id="btn_decrypt" type="submit" class="btn btn-primary">Decrypt</button>
        </div>
    	<div class="span7 offset1 result">
    		<p>(A + B)*C = <span class="plaintext" id="plainABC">-</span></p>
    		<p>Elapsed time: <span id="decrypttime">-</span> ms</p>    		
    	</div>
    </div> 
    	
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
			// console.log(numBits);
			keys = paillier.generateKeys(numBits);
			// keys = myArr;
			elapsed = new Date().getTime() - startTime;
			// myArrString = JSON.stringify(keys);
			// console.log(keys);
			
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
