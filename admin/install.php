<?php 

require ('../inc/config.php');
require ('../inc/functions.php'); 

/* Connect to Elasticsearch */
try {
    $client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build(); 
    //print("<pre>".print_r($client,true)."</pre>");
    $indexParams['index']  = $index;   
    $testIndex = $client->indices()->exists($indexParams);
} catch (Exception $e) {    
    $error_connection_message = '<div class="alert alert-danger" role="alert">Elasticsearch não foi encontrado. Favor executar o arquivo elasticsearch.lnk.</div>';
}
/* Create index if not exists */
if (isset($testIndex) && $testIndex == false) {
    Elasticsearch::createIndex($index, $client);
    Elasticsearch::mappingsIndex($index, $client);
}

header("Location: ../index.php"); die();

?>