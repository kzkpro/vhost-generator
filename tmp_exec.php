<?php
  $filename = "C:\Windows\System32\drivers\etc\hosts";
  $cont = file_get_contents($filename);
  $cont.= "127.0.0.1 chocolate #Vhost Generator";
  file_put_contents($filename, $cont);
?>