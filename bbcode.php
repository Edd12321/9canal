<?php
#######################
# Implementare BBCode #
#######################
# BBCode este un limbaj de markup foarte simplu folosit des pe forum-uri.
# Este folosit ca o alternative sigura pentru HTML.

function
bbcode_fmt($text)
{
  #textul este deja cleaned
  #$text = htmlspecialchars($text);

  $text = str_replace("[b]", "<b>", $text);
  $text = str_replace("[/b]", "</b>", $text);

  $text = str_replace("[i]", "<i>", $text);
  $text = str_replace("[/i]", "</i>", $text);

  $text = str_replace("[s]", "<s>", $text);
  $text = str_replace("[/s]", "</s>", $text);

  $text = str_replace("[u]", "<u>", $text);
  $text = str_replace("[/u]", "</u>", $text);

  return "</b></i></s></u>".$text;
}
