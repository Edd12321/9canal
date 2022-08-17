<?php
 ###############################
 # Verificare privilegii admin #
 ###############################
function
is_localhost($whitelist = ['127.0.0.1', '::1'])
{
  return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}
