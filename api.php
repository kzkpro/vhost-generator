<?php
require_once 'exec_generator.php';
function readSettingsXml(){
}

function updateSettingsXml($vhostFilePath){
    var_dump(file_exists('settings.xml'));
  if (!file_exists('settings.xml')) {
    createSettingsXml();
    }
 $xml = new DOMDocument('1.0', 'utf-8');
 $xml->formatOutput = true;
 $xml->load('settings.xml');

 //Get item Element
 $xml->getElementsByTagName('vhostlocation')->item(0)->nodeValue = $vhostFilePath;
 file_put_contents("settings.xml", $xml->saveXML());
}

function createSettingsXml(){
    $xml = new DOMDocument("1.0");
    $xml->formatOutput=true;
    $vhostlocation = $xml->createElement("vhostlocation", "");
    $xml->appendChild($vhostlocation);
    file_put_contents("settings.xml", $xml->saveXML());
    var_dump( "xml created");
}

function getSettingsXml($tag){
  $xml = new DOMDocument('1.0', 'utf-8');
  $xml->formatOutput = true;
  $xml->load('settings.xml');

  //Get item Element
  return $xml->getElementsByTagName($tag)->item(0)->nodeValue;

}

//write Vhost
function writeConfig( $filename, $config ) {
    file_put_contents($filename, $config);

    return true;
}

function readConfig( $filename ) {
  $cont = file_get_contents($filename);
  $vhosts = [];
  //preg_match_all('/<VirtualHost(.*?)>(.*?)<\/VirtualHost>/s', $cont, $matches);
  preg_match_all('/<VirtualHost (.*?)>(.*?)<\/VirtualHost>/s', $cont, $matches);
  //var_dump($matches[0]);
  foreach ($matches[0] as $string) {
    // code...
    preg_match('/ServerName (.*?)\n/s', $string, $domain);
    preg_match('/<Directory (.*?)>/s', $string, $directory);
    array_push($vhosts, ["ServerName"=> (count($domain)>1)? $domain[1]: '', "Directory"=> $directory[1] ]);
  }
  return $vhosts;
}
function addVhost($servername, $directory){
  $filename = getSettingsXml('vhostlocation');
  $cont = file_get_contents($filename);
  if(!is_dir($directory)){
    return "Invalid Directory";
  }elseif (vhostExists($servername, $cont)) {
    // code...
    return "ServerName already Esists";
  }else{
    $vhostString = <<<EOT
    <VirtualHost *:80>
        DocumentRoot "$directory"
        ServerName $servername
        ServerAlias *.$servername
        <Directory "$directory">
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    EOT;
    $cont.=$vhostString;
  //  WriteToHosts($servername);
    writeConfig($filename,$cont);
    return "vhost added";
  }


}
function deleteVhost($servername){
  $filename = getSettingsXml('vhostlocation');
  $cont = file_get_contents($filename);
  preg_match_all('/<VirtualHost (.*?)>(.*?)<\/VirtualHost>/s', $cont, $matches);
  foreach ($matches[0] as $string) {
    if (strpos($string, 'ServerName '.$servername) !== false) {
      $x = $string;
      //$x=explode(PHP_EOL, $string);
      //$x = implode('\n', $x);
      $x = str_replace('*', '\*', $x);
        $x = str_replace('/', '\/', $x);
      var_dump($x);
        $cont = preg_replace('/'.$x.'/s', '', $cont);
    }
  }

  writeConfig($filename, $cont);
  return true;
}
function WriteToHosts($servername){
  prepareExecFile($servername);
}
function vhostExists($servername, $string){
  return strpos($string, 'ServerName '.$servername) !== false;
}

?>
