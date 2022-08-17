<?php
  session_start();
  require '../config.php';
  require '../func.php';

  # Doar localhost poate vizita site-ul
  is_localhost() or die;

  if (isset($_POST["postare"]))
     unlink($_POST["postare"]);
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
      <link rel="stylesheet" href="styles.css" />
      <style>
        th             { font-weight: bold                    }
        th, td         { border: 1px solid #ccc; padding: 3px }
        table          { border-collapse: collapse            }
        form           { margin:  0 }
        #page #sidebar { padding: 0 }
      </style>
     </head>
  <body>
    <div id="top">
      <div id="bar">
        <a href=<?=$fakeroot?>/vip/>announcements</a>
         | 
        <a href=<?=$fakeroot?>/blotter.php>blotter</a>
         | 
        <a href=<?=$fakeroot?>/all/>overboard</a>
      </div>
      <div id="title">
        <h1>janitor menu</h1>
      </div>
    </div>
    <div id="page">
      <div id="sidebar">
<table>
<tbody>
<tr>
  <th>Adresa IP  </th>
  <th>ID Postare </th>
  <th>Vizualizare</th>
  <th>Optiuni</th>
</tr>
<?php
  $csv = fopen("ip_log.csv", "r");
  $lcount = 0;
  while (($row = fgetcsv($csv)) !== false) {
    $post_link = glob("../all/*/".$row[1].".txt")[0];
    if (!file_exists($post_link)) {
      ##
      # Stergem randul daca nu mai exista fisierul
      ##
      $csr = file("ip_log.csv");
      unset($csr[$lcount]);
      file_put_contents("ip_log.csv", implode("", $csr));

      continue;
    }
    echo "<tr>";

    # Afisam fiecare celula
    foreach($row as $cell)
      echo "<td>$cell</td>";

     #######################
     ## Buton vizualizare ##
     #######################
    echo "<td>
          <a href=$post_link>txt</a>
           | 
          <a href=".dirname($post_link).">fir</a>
          </td>";

     ####################
     ## Buton STERGERE ##
     ####################
    echo "<td>
          <form method='post' name='del'>
            <input  type='hidden' value='$post_link' name='postare'></input>
            <button type='submit'>Stergere</button>
          </form>
          </td>";

    echo "</tr>";
    ++$lcount; # Creste contorul
  }
?>
</tbody>
</table>
      </div>
      <div id="main">
      </div>
    </div>
  </body>
</html>
