<?php

$server = 'localhost';
$username = 'poltekgt_ridwan';
$password = 'Bismillah14!';
$database = 'poltekgt_pmb';

// Koneksi dan memilih database di server
//mysql_connect($server,$username,$password) or die('Koneksi gagal');
//mysql_select_db($database) or die('Database tidak bisa dibuka');
 error_reporting(E_ALL ^ E_DEPRECATED);
$konek = mysqli_connect($server,$username,$password);
mysqli_select_db($konek,$database) or die('Database tidak bisa dibuka');

$sql = "SELECT barcode, nama FROM wisuda";

$result = mysqli_query($konek, $sql);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
        <div class="row">
        <div class="col">
            
        <table id="example" class="display" style="width:100%">
    <thead>
        <tr>
            <th>nim</th>
            <th>nama</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        if (mysqli_num_rows($result) > 0) {
             
              while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row["barcode"] . "</td><td>" . $row["nama"]. "</td></tr>";
            } 
        
        }
        
        else {
          echo "<tr><td colspan='2'>0 results</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>
</div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script>
    
    new DataTable('#example');
    
</script>
    </body>
</html>