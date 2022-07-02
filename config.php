<?php
#!/usr/bin/php

#daca se afla in alt folder decat in folderul radacina al serverului,
#inlocuiti cu "$fakeroot = '/[nume]'" !!
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $fakeroot = "/web/9canal";

  if (!file_exists($doc_root.'/'.$fakeroot)) {
    $fakeroot = "/9canal";
    if (!file_exists($doc_root.'/'.$fakeroot)) {
      $fakeroot = '';
    }
  }

$root = realpath(__DIR__);
?>
