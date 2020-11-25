<?php

    /* Exibir erros */ 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    $branch = "E-books";
    $branch_abrev = 'E-books';
    $branch_description = "";
    $url_base = "http://localhost/ebooks";
    $background_1 = "http://imagens.usp.br/wp-content/uploads/Faculdade-de-Direito-312-15-Foto-Marcos-Santos-028.jpg";
    $facebook_image = "";

    // Definir Instituição
    $instituicao = "";

	/* Endereço do server, sem http:// */ 
    $hosts = ['elastic:elastic@localhost'];
    
    /* Configurações do Elasticsearch */
    //define("INDEX", "unifesp");
    $index = "ebooks";

	/* Load libraries for PHP composer */ 
    require (__DIR__.'/../vendor/autoload.php'); 

	/* Load Elasticsearch Client */ 
	$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build(); 

?>