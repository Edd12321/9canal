<?php
$nlh = !is_localhost();

########################################
# Conditii ca postarea sa fie trimisa. #
########################################

$trimite = $_POST["submit"];
$id_fals = (strpos($_POST["trip"], '!'));
$captcha = ($_SESSION["solve"] == $_POST["ver"]);

if (isset($trimite)
&& (($nlh && $board != "vip" && $captcha) || !$nlh)
&& !$id_fals) {

  switch(file_exists("ISROOT")) {
  case 0: {
    #Tripcode
    $name = explode('#', $_POST["trip"])[0];
    $trip = tripcode($_POST["trip"]);

    #Subiect
    $subject = $_POST["subject"];

    #Continut
    $content = $_POST["content"];

    #Url foto
    $image = $_POST["image"];

    #ID
    $number = file_get_contents(realpath(__DIR__).'/postnum.txt') + 1;

    #Countryball (/int/, /pol/)
    $flag = '';
    if (isset($_POST['cball'])) {
      $flag = basename($_POST['cball'], '.png');
    }

    #Creste contorul urmatoarei postari
    file_put_contents(realpath(__DIR__).'/postnum.txt', @$number);
    $ok = 0;
    if (!is_numeric($board)) {

      #Creare fir
      mkdir($number);

      #Firul apare in /all/
      symlink("../$board/$number",
	      "../all/$number");

      #Putem vedea postarea
      chdir($number);
      symlink("../../index.php",
	          "./index.php");

      #Totul e bine...
      $ok = 1;
    }

    #Stocam ce a scris utilizatorul in fisier:
    file_put_contents(($number . ".txt"), (htmlspecialchars($name). ## NUME
      $trip.PHP_EOL.                                                ## TRIPCODE
      htmlspecialchars($subject).PHP_EOL.                           ## SUBIECT
      htmlspecialchars($image).PHP_EOL.                             ## URL FOTO
      $flag.PHP_EOL.                                                ## CBALL
      str_replace(PHP_EOL,
        '<br />',
        (htmlspecialchars($content)))));                            ## CONTINUT
    if ($ok) {
      chdir("../");
    }
    break;
  } case 1: {
	  #Nu putem posta pe pagina principala !!
      echo '<center>
              <font color="red">
               <b>
                 <br />
                 Selecteaza un board inainte de a posta...
               </b>
              </font>
            </center>';
       break;
   }}
  session_destroy();
  header("Refresh:0");
}

#Titlu
$titlek = getcwd();

#"$board" este echiv. pwd, nu neaparat numele board-ului !!
if (is_numeric($board)) {
  #Ne ducem inapoi daca suntem intr-un thread.
  $titlek .= "/..";
}
$title = '/'.basename(realpath($titlek)).'/ - '.file_get_contents($titlek."/title.txt");
echo "<h2 id=\"btitle\">$title</h2>";
echo "<title>$title</title>";
?>
  <div id="post-dp">
    <h2>[<a href='#' id="post-button" onclick="modifyDiv('post-menu', 'post-dp')">Postare nouă</a>]</h2>
  </div>

  <!-- SECTIUNE TABEL -->
  <div class="table" style="display: none;" id="post-menu">
    <form method="post">
       <div class="tr">
         <div class="td" id="pt1">
           <label for="trip">
             <b>
               Identificare
             </b>
            </label>
         </div>
         <div class="td" id="pt2">
           <input type="text" name="trip" autocomplete="off" placeholder="Nume#Tripcode"
<?php
if ($board == 'vip' && $nlh)
  echo 'disabled';
?>
           >
           <br />
         </div>
       </div>
       <div class="tr">
         <div class="td" id="pt1">
           <label for="subject">
             <b>
               Subiect
             </b>
           </label>
         </div>
         <div class="td" id="pt2">
<?php
echo '<input type="text" name="subject" ';
if ($board == 'vip' && $nlh)
  echo 'disabled';
else if (!is_numeric($board))
  echo 'required';
echo '><br />';
?>
        </div>
      </div>
      <div class="tr">
        <div class="td" id="pt1">
          <label for="image">
            <b>
              URL Foto
            </b>
          </label>
        </div>
        <div class="td" id="pt2">
          <input type="text" autocomplete="off" name="image"
<?php
if (($board == 'txt' || $board == 'vip') && $nlh) {
  #Nu exista poze nici pe /txt/, nici pe /vip/.
  #(regula doar pt utilizatori neprivilegiati)^
  echo 'disabled';
}
?>
          >
          <br />
        </div>
      </div>
<?php

$tmp = basename(realpath($titlek));

if ($tmp != 'pol' && $tmp != 'int') {
  #Iconite doar pe /pol/ si /int/.
  goto noBall;
 }
?>
      <div class="tr">
        <div class="td" id="pt1">
          <label for="cball">
            <b>
              Polandball
            </b>
          </label>
        </div>
        <div class="td">
          <select method="post" name="cball" style="width:100%;">
<?php

#Lista cu countryball-uri (iconite pentru /pol/&/int/);
$cballs = scandir($root . '/_res/cball');
foreach($cballs as $cball) {
  $qname = basename($cball);
  if (!is_dir($cball))
    echo '<option value="' . $qname . '" style="background-image:url(/_res/cball/' . $qname . ')">' . basename($cball, ".png") . '</option>';
}
?>
          </select>
        </div>
      </div>
<?php
#Sarim aici daca nu suntem pe unul dintre acele 2 board-uri...
noBall:
?>
    <div class="tr">
      <div class="td" id="pt1">
        <label for="content">
           <b>
             Conținut
           </b>
        </label>
      </div>
      <div class="td">
<?php
echo '<textarea name="content" style="width:100%;"';
if ($board == 'vip' && $nlh) {
  echo 'disabled';
} else if (!is_numeric($board)) {
  #Putem posta ca localhost...
  echo 'required';
}
echo '></textarea><br />';
?>
      </div>
    </div>
    <div class="tr">
      <div class="td" id="pt1">
          <label for="submit">
            <b>
<?php
if (is_numeric($board)) {
  echo 'Postare';
} else {
  echo 'Fir';
}
?>
            </b>
          </label>
      </div>
      <div class="td">
          <?php echo $_SESSION["captcha"]; ?>
          <input type="text" autocomplete="off" name="ver"
<?php
if ($nlh) {
  if ($board == 'vip')
    echo 'disabled';
  else echo 'required';
}
?>
          >
          <br />
          <button type="submit" name="submit" class="button"
            style="
              float: left;
            "
        <?php
            if ($board == 'vip' && $nlh)
          echo 'disabled';
        ?>
          >Trimite!</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <hr width="468px" />
</center>

<div style="width:467px;height:fit-content;margin-left:auto;margin-right:auto;">
<div id="blot-open">
<?php
   ######################################
   # AICI SE FACE AFISAREA BLOTTER-ULUI #
   ######################################
	#Afisam buton blotter.
    echo '<font size="2px">';
    $k = 0;
    $f = fopen($root.'/blotter.csv', "r");
    #Este stocat in fisierul de mai sus ^
    if ($f) {
      while (($linie = fgetcsv($f)) !== false) {
        foreach ($linie as $cel) {
          if ($cel[0] == '?') {
            --$k;
	    #Pentru anunturi care urmeaza a fi actualizate...
	  } else if ($k && $k <= 3) {
            echo $cel . ' ';
          }
        }
        if ($k == 3) {
          goto label;
        }
        if ($k) {
          echo '<br />';
        }
        ++$k;
      }
    }
label:
    #Inchidem fisierul pt. blotter.
    if ($f) {
      fclose($f);
    }

    #Butoanele [inchide] & [blotter]:
    echo '</font>
    <div style="text-align:right;">
      [<a href="#" onclick="modifyDiv(\'blot-close\', \'blot-open\')">inchide</a>]
      [<a href="'.$fakeroot.'/blotter.php">blotter</a>]
    </div>';
 ?>
</div>
<div id="blot-close" style="display:none">
<?php
	echo '<div style="text-align:right;">[<a href="#" onclick="modifyDiv(\'blot-open\', \'blot-close\')">deschide</a>]</div>';
 ?>
</div>
</div>
