<?php
session_start();
include_once 'config.php';
?>
<html>
    <head>
      <link rel="stylesheet" type="text/css" href="<?php echo "$fakeroot/_res/themes/".(!isset($_COOKIE["theme"])?"default.css":$_COOKIE["theme"]) ?>" />
      <meta name="viewport" content="width=device-width,
                   initial-scale=1,
                   maximum-scale=0.80,
                   minimum-scale=0.80,
                   user-scalable=no,
                   user-scrollable=no,
		   minimal-ui">
      <style>
        table {
          width: 95%;
        }
        td {
	   /* background-color: white; */
          border: 1px solid #ccc;
        }
      </style>
    </head>
    <body>
      <center>
        <br />
        <h2>Blotter 9canal</h2>
        <table>
          <tbody>
<?php
$k = 0;
$k1 = 0;
$f = fopen("blotter.csv", "r");
while (($linie = fgetcsv($f)) !== false) {
  echo '<tr>';
  foreach ($linie as $cel) {
    if (!$k) {
      echo '<th id="pt1" style="width:fit-content;">';
    } else {
      echo '<td>';
    }
    echo $cel;
    if (!$k) {
      echo '</th>';
    } else {
      echo '</td>';
    }
    ++$k1;
    if ($k1 > 2) {
      $k1 = 2;
    }
  }
  echo '</tr>';
  ++$k;
}
fclose($f);
?>
            
        </table>
      </tbody>
    </center>
  </body>
</html>
