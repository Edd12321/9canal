<div id="bar">
    <i><b><font color="#bcaf4f">9</font>canal</b></i>
    &ensp;
	[
<b><a href=<?php echo "$fakeroot/all/";?>>all</a></b>
	/
<b><a href=<?php echo "$fakeroot/rss/"; ?> style="color:orange;">rss</a></b>
	][
<?php

## Declaram variabila path:
#  Este mai eficient sa calculam doar odata directorul curent.

$path = basename(getcwd());
if (is_numeric($path) && !file_exists('lock.dat')) {
  # Suntem intr-un thread
  $path = '../..';
} else if (file_exists('ISROOT')) {
  # Suntem in radacina
  $path = '.';
} else {
  # Suntem in lista de postari dintr-un board
  $path = '..';
}

# Vedem fiecare board ca pe un fisier.
$files = scandir($path, 0);
$files = array_slice($files, 2);
$fsize = count($files);

# Incepem cu contorul=0;
$i = 0;

# Foldere care nu sunt board-uri
$nepermis = array("_admin",
	          "_res",
                  "all",
                  "rss",
                 ".git"
            );

# Pentru fiecare board, il afisam pe bara.
foreach($files as $file) {
  ++$i;
  if (is_dir($path.'/'.$file)) {
    if (!in_array($file, $nepermis)) {
      echo '<a href="'.$fakeroot.'/'.$file.'/">&nbsp;'.$file.'</a>';
      if ($i != $fsize)
		echo '&nbsp;/';
	}
  }
}

?>

	][
<a href=<?="$fakeroot/_admin"   ?>> janitor </a>
<a href=<?="$fakeroot/rules.txt"?>> rules </a>
	]
</div>
