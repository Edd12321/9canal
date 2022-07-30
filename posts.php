<?php
  session_start();
  require 'bbcode.php';

function display_post($file, $fakeroot, $k) {
   ##################################
   # SUBRUTINA FOARTE IMPORTANTA !! #
   ##################################
  ## ID
  $id = basename($file, ".txt");
  if (is_numeric($id) || $id == "pin") {
    $access = $file;
    if (is_dir($file)) {
      $access .= "/$file.txt";
    }

    #Accesam fisierul.
    $lines = file($access);

    #Extragem datele stocate despre postare din fisierul sau.
    $rest = implode('\n', array_splice($lines, 4));
    $info = $lines;

    ## NUME
    $name = $info[0];
    if (ctype_space($name)) {
      $name = "Anonim";
    }

    ## TRIPCODE
    $trip = '';
    if (strpos($name, '!') !== false) {
      #initializare...
      $trips = array();

      if (strpos($name, "!!") !== false) {
        #tripcode securizat
	$trips = explode("!!", $name);
	$trip = "!!";
      } else {
        #tripcode normal
        $trips = explode('!', $name);
        $trip = '!';
      }

      #numele este inainte de delimitator
      $name = $trips[0];

      #tripcode-ul contine delimitatorul
      $trip .= $trips[1];
    }

    ## CAPCODE
    $cap = '';
    if (strpos($name, "##") !== false) {
      $caps = explode("##", $name);
      $name = $caps[0];
      $cap = "## ".$caps[1];
    }

    ## TITLU
    $subject = $info[1];

    ## URL FOTO
    $img = $info[2];
    ## STEAG
    $flag = '<img src="'."$fakeroot/_res/cball/".$info[3].'.png" style="width:0.90em;height:auto;" />';
    if (ctype_space($info[3])) {
      $flag = "";
    }

    ##ICON
    $time = filemtime($access);
    $icon = '';

    $imple = 0;

    if (is_file($file)) {
      if (file_exists(dirname($file)."/../ISROOT")) {
        #Este o postare simpla, nu un thread.
        $imple = 1;	    
        $icon .= "<img src=\"$fakeroot/_res/closed.gif \" />";
      } else {
        $icon .= "▶";
      }
    }

    if (!$imple && time() - $time > 24 * 60 * 60 && is_dir($file)) {
      #A trecut o zi de cand postare a fost facuta.
      $icon .= "<img src=\"$fakeroot/_res/archived.gif\" />";
    }


    if ($file == "pin.txt") {
      #Postarea este fixata pe board.
      $icon .= "<img src=\"$fakeroot/_res/sticky.gif\" />";
    }

    ## DATA
    $time = strftime("%m/%d/%y(%a)%H:%M", $time);


##### AFISARE POSTARE #####
echo '
  <div class="post">
    <div class="postinfo">
      <span class="deletespan">
        <span class="delete">
          <input type="checkbox" name="'.$id.'" value="delete">
	</span>
      </span>
      <span class="subjectspan">
        <span class="subject">'.$subject.'</span>
      </span>
      <span class="flagspan">
        <span class="flag">'.$flag.'</span>
      </span>
      <span class="namespan">
        <span class="name">'.$name.'</span><span class="tripcode">'.$trip.'</span>
        <span class="capcode">'.$cap.'</span>
      </span>
      <span class="timespan">
        <span class="time">'.$time.'</span>
      </span>
      <span class="idenspan">
        <span class="id">No.'.$id.'</span>
      </span>
      <span class="iconspan">
        <span class="icon">'.$icon.'</span>
      </span>
    </div>
    <blockquote class="message">';
    if (!ctype_space($img)) {
        echo "<img src=\"".htmlspecialchars($img)."\" />";
    }
echo bbcode_fmt(nl3br(make_links_clickable(green($rest)))).
   '</blockquote>';
    $is_thread = is_dir($file);

    echo '</div>';
    #Daca este un fir, aratam un "preview" al comentariilor.
    if ($is_thread) {
      $replies = count(scandir($file)) - 4;

      #Reply-urile sunt indentate diferit...
      echo '<div class="indent">';

      $replies = count(scandir($file)) - 4;
      if ($replies) {
	$preview = array();

	#Fisiere cu reply-uri
	$reply_arr = scandir($file);
        $reply_del = [ ".", "..", "$file.txt", "index.php" ];

        $reply_arr = array_values(array_diff($reply_arr, $reply_del));

        $k = 0;
	foreach ($reply_arr as $reply) {
	  ++$k;
          if ($k == 5) {
            break;
	  }
          $preview[] = "$file/$reply";
        }
        foreach ($preview as $newfile) {
	  display_post($newfile, $fakeroot, 1);
	}
	echo "[<a href=\"$file\">Vizualizare fir (+$replies)</a>]";
      } else {
        echo "[<a href=\"$file\">Vizualizare fir</a>]";
      }
      echo "</div><hr />";
    } else if ($file == "pin.txt") {
      echo "<hr id=\"pin\" />";
    }
  }
}
?>
      <hr />
      <script src="<?=$fakeroot?>/theme.js"></script>


      <form action="" method="post">
		<select id="theme" onchange="this.form.submit();whichTheme();" style="font-size: 15px;">
        <?php 
            $themes = scandir("$root/_res/themes");
            $themes = array_splice($themes, 2);

             ###################
			 ## Tema curenta! ##
             ###################
            echo '<option value="'.$_COOKIE["theme"].'">Tema '.basename($_COOKIE["theme"], ".css").'</option>';
            
            foreach ($themes as $theme) {
              if ($theme != $_COOKIE["theme"]) echo '<option value="'.$theme.'">Tema '.basename($theme, ".css").'</option>';
            }
         ?>
        </select>

	<select name="view_order" style="font-size: 15px;">
          <option value="bumped">Ordine bump</option>
          <option value="time">Ordine cronologica</option>
	</select>
        <button type="submit" class="button">OK</button>
        <hr />
      </form>
<?php

  echo '<div id="thread">';

  $files = scandir('.');
  if ($_POST["view_order"] == "time") {
    natsort($files);	
  } else {
    #Sortam vectorul
    usort($files, build_sorter('.'));
  }


  # 0 -> Vizualizam postarile din board
  # 1 -> Vizualizam postarile din thread
  $k = 1;
  if (file_exists("../ISROOT")) {
    $files = array_reverse($files);	
    $k = 0;
	
    # Postarea fixata este mereu in varful board-ului.

    if (in_array("pin.txt", $files)) {
      $arr_del = array("pin.txt");
      $files = array_values(array_diff($files, $arr_del));
      array_unshift($files, "pin.txt");
    }
  }  
  # Aici se face, in sfarsit, afisarea ...
  foreach ($files as $file) {
    display_post($file, $fakeroot, $k);
  }
?>
