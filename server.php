<?php
require_once 'api.php';
$task = $_GET['task'];
switch ($task) {
  case 'savevhostlocation':
    // code...
    $vhost = $_POST['vhost'];
    if(!empty($vhost) && file_exists($vhost)){
      updateSettingsXml($vhost);
      echo "Vhost path updated!!!";
    }else{
      echo "Invalid file Path or file doesn't exist";
    }

    break;

  case 'getvhostlocation':
    // code...
    echo json_encode(['vhost'=> getSettingsXml('vhostlocation')]);
    break;


  case 'getvhosts':
    // code...
    $fileConfig = readConfig(getSettingsXml('vhostlocation'));
    echo json_encode(['data'=>$fileConfig]);
    break;
  case 'addvhost':
    // code...
    $servername = $_POST['ServerName'];
    $directory = $_POST['Directory'];
    echo addVhost($servername, $directory);
    break;
  case 'editvhost':
    // code...
    break;

  case 'deletevhost':
    // code...
    $servername = $_GET['servername'];
    deleteVhost($servername);
    break;

  default:
    // code...
    break;
}
?>
