<?php
session_start();

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

# SCHIMBATI DACA 9CANAL SE AFLA IN DIRECTORUL RADACINA !!
include realpath(__DIR__).'/config.php';

header("Cache-Control: no cache");
session_cache_limiter("private_no_expire");
?>

<html>
    <head>
<?php
setlocale(LC_ALL, "ro_RO");
$board = basename(getcwd());

 ####################
 # Postare in /vip/ #
 ####################
function
is_localhost($whitelist = ['127.0.0.1', '::1'])
{
  return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

 #######################################
 # Functie care sa inlocuiasca nl2br() #
 #######################################
function
nl3br($text)
{
  return str_replace(array('\r\n',
    '\r',
    '\n',
    '\\r\\n',
    '\\r',
    '\\n'),
  '<br />',
  $text);
}

 ############################################
 # Functie pentru "greentext"-uri (quoting) #
 ############################################
function
green($text)
{
  return preg_replace('/(?:\n)?&gt;([^<]*)/m',
    '<font color="#789922">&gt;$1</font>',
    $text);
}

 ###########################################
 # Functie pentru a putea apasa pe URL-uri #
 ###########################################
function
make_links_clickable($text)
{
  return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i',
  '<a href="$1">$1</a>',
    $text);
}

 #####################################
 # Functie tripcode simplu/securizat # 
 #####################################
function
tripcode($text)
{
  $match = explode('#' , $text);
  $sec = explode('##', $text);
  $fake = explode('!', $text);

  #Trebuie minim 2 caractere pt. salt:
  if (strlen($match[1]) < 3) {
    $match[1] .= "H.";
  }

  if (strpos($text, '!') !== false) {
    return '';
  } else if (strpos($text, "##") !== false) {
    #Tripcode nou
    $len = strlen($sec[1]);

    #SHA in loc de DES
    $sec[1] = hash('sha256', $sec[1], true);

    $sec[1] = base64_encode($sec[1]);
    $sec[1] = substr($sec[1], 0, -1);
    $sec[1] = substr($sec[1], $len, 10);

    return '!!'.$sec[1];

  } else if (strpos($text, '#') !== false) {
    #Tripcode simplu
    $lm = htmlspecialchars($match[1]);
    $sare = substr($lm, 1, 2);
    return '!'.substr(crypt($lm, $sare), -10);

  } else {
    return '';
  }
}

 #################################
 # Functie-helper sortare vector #
 #################################
function
build_sorter($dir)
{
  return function ($f1, $f2) use ($dir) {
    return filemtime($dir.'/'.$f1) <= filemtime($dir.'/'.$f2) ? -1 : 1;
  };
}

$ver = 'verify.php';
if (!file_exists('ISROOT')) {
  $ver = '../'.$ver;
  if (is_numeric($board))
    $ver = '../'.$ver;
}

# Includem declaratii
include $ver;

$blotter = '';
if (isset($_GET['b'])) {
  $blotter = $_GET['b'];
}
?>
  <link rel="stylesheet" type="text/css" href="<?=$fakeroot?>/global.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo "$fakeroot/_res/themes/".(!isset($_COOKIE["theme"])?"photon.css":$_COOKIE["theme"]) ?>" />
  <link rel="icon" type="image/x-icon" href=<?php echo "$fakeroot/favicon.ico"; ?>>
  <meta name="viewport" content="width=device-width,
               initial-scale=1,
               maximum-scale=0.80,
               minimum-scale=0.80,
               user-scalable=no,
               user-scrollable=no,
               minimal-ui">
  </head>
  <body>
  <?php
      ####################
      # BARA DE NAVIGARE #
      ####################
     require "navbar.php";
   ?>
  <br />
  <center>
    <?php
       ####################################
       # AICI INCLUDEM RESTUL FISIERELOR! #
       ####################################
      require "new.php";
      require "banner.php";
      require "posts.php";
     ?>
  </center>
  </div>
  </body>
</html>
