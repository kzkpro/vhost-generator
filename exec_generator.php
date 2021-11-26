<?php
function createExecFile( $content){
  file_put_contents('tmp_exec.php', $content);
  file_put_contents('exec_runner.bat', 'php '.dirname(__FILE__).'\tmp_exec.php');
  var_dump(dirname(__FILE__));

}
function prepareExecFile($servername){
  $filename = 'C:\Windows\System32\drivers\etc\hosts';

  $cont = file_get_contents($filename);
  $cont.= "127.0.0.1 ".$servername. " #Vhost Generator";
  $code = <<<EOT
  <?php
    \$filename = "$filename";
    \$cont = file_get_contents(\$filename);
    \$cont.= "127.0.0.1 $servername #Vhost Generator";
    file_put_contents(\$filename, \$cont);
  ?>
  EOT;
  createExecFile($code);
}

?>
