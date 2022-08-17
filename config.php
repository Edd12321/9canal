<?php
#!/usr/bin/php
#####################################################
#                   __ _               _            #
#   ___ ___  _ __  / _(_) __ _   _ __ | |__  _ __   #
#  / __/ _ \| '_ \| |_| |/ _` | | '_ \| '_ \| '_ \  #
# | (_| (_) | | | |  _| | (_| |_| |_) | | | | |_) | #
#  \___\___/|_| |_|_| |_|\__, (_) .__/|_| |_| .__/  #
#                        |___/  |_|         |_|     #
#####################################################

#daca se afla in alt folder decat in folderul radacina al serverului,
#inlocuiti cu "$fakeroot = '/[nume]'" !!
  $doc_root = $_SERVER["DOCUMENT_ROOT"]; # Locatia site-ului (Document Root)
  $fakeroot = "/web/9canal";             # Locatia site-ului (URL)
  $log_post =  true;                     # IP log pentru banare


if (!file_exists($doc_root.'/'.$fakeroot)) {
  $fakeroot = "/9canal";
  if (!file_exists($doc_root.'/'.$fakeroot)) {
    $fakeroot = '';
  }
}

$root = realpath(__DIR__);               # Locatia site-ului (Server Root)
?>
