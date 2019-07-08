<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
    <head>
        <?php
        
        // Set directory to ROOT
        chdir('../');
        require 'inc/config.php';         
        require 'inc/meta-header.php';

        /* Consulta n registros ainda não corrigidos */
        if (isset($_GET["field"])) {
            $field = $_GET["field"];
            $body["query"]["bool"]["must"]["query_string"]["default_field"] = "$field.tematres";
            $body["query"]["bool"]["must"]["query_string"]["query"] = "false";            
        }

        if (isset($_GET["term"])) {
            $body["query"]["bool"]["must"]["query_string"]["query"] = ''.$field.'.name:'.$_GET["term"].'';
        }

        $params = [];
        $params["index"] = $index;
        $params["_source"] = ["_id","author"];
        $params["size"] = 1000;
        $params["body"] = $body;

        $response = $client->search($params);

        echo 'Total de registros faltantes: '.$response["hits"]["total"]["value"].'';



        ?>
        <title>Autoridades</title>
    </head>
    <body>

        <div class="uk-container uk-container-center uk-margin-large-bottom">

        <?php
        // Pega cada um dos registros da resposta
        foreach ($response["hits"]["hits"] as $record) {

            $i = 0;
            $body_upsert["doc"]["author"] = $record['_source']['author'];

        //     // Para cada autor no registro
            foreach ($record['_source']['author'] as $author) {

                // echo "<br/>";
                // print_r($record['_source']['author']);                   
                // echo "<br/>"; 

                
                if (isset($author["person"]["affiliation"])) {
                    $i_aff = 0;
                    foreach ($author["person"]["affiliation"] as $affiliation) {

                        if (isset($affiliation["tematres"])) {
                            if ($affiliation["tematres"] == "false") {
                                // echo "<br/>";
                                // print_r($affiliation);
                                // echo "SIM";
                                // echo "<br/>";

                                $termCleaned = str_replace("&", "e", $affiliation["name"]);
                                $result_tematres = Authorities::tematres($termCleaned, $tematres_url);
                                // echo "<br/>";
                                // print_r($result_tematres);                      
                                // echo "<br/>"; 
                                
                                if (!empty($result_tematres["found_term"])) {
                                    $body_upsert["doc"]["author"][$i]["person"]["affiliation"][$i_aff]["name"] = $result_tematres["found_term"];
                                    $body_upsert["doc"]["author"][$i]["person"]["affiliation"][$i_aff]["tematres"] = "true";
                                    $body_upsert["doc"]["author"][$i]["person"]["affiliation"][$i_aff]["locationTematres"] = $result_tematres["country"];
                                    $body_upsert["doc_as_upsert"] = true;
                                    echo "<br/>Tem alterações<br/>";                                    
                                }

                                if (!empty($result_tematres["term_not_found"])) {
                                    echo $result_tematres["term_not_found"];
                                    echo "<br/>";

                                }

                            }
                            $i_aff++;
                        }                        
                        
        
                    }  
                }  
                $i++;
            }

            $resultado_upsert = Elasticsearch::update($record["_id"], $body_upsert);
            echo '<br/><br/>';
            //print_r($resultado_upsert);
            unset($body_upsert);




        //             if (!empty($result_tematres["found_term"])) {

        //                 if (!empty($autor["person"]["name"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["name"] = $autor["person"]["name"];
        //                 }
        //                 if (!empty($autor["person"]["affiliation"]["location"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["location"] = $autor["person"]["affiliation"]["location"];
        //                 }
        //                 if (!empty($autor["person"]["date"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["date"] = $autor["person"]["date"];
        //                 }
        //                 if (!empty($autor["person"]["potentialAction"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["potentialAction"] = $autor["person"]["potentialAction"];
        //                 }
        //                 if (!empty($autor["person"]["USP"]["autor_funcao"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["USP"]["autor_funcao"] = $autor["person"]["USP"]["autor_funcao"];
        //                 }
        //                 echo '<br/>Encontrado: '.$result_tematres["found_term"].'<br/>';
        //                 $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["name"] = $result_tematres["found_term"];
        //                 $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["locationTematres"] = $result_tematres["country"];

        //             } else {

        //                 if (!empty($autor["person"]["name"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["name"] = $autor["person"]["name"];
        //                 }
        //                 if (!empty($autor["person"]["affiliation"]["location"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["location"] = $autor["person"]["affiliation"]["location"];
        //                 }
        //                 if (!empty($autor["person"]["date"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["date"] = $autor["person"]["date"];
        //                 }
        //                 if (!empty($autor["person"]["potentialAction"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["potentialAction"] = $autor["person"]["potentialAction"];
        //                 }
        //                 if (!empty($autor["person"]["USP"]["autor_funcao"])) {
        //                     $body_upsert["doc"]["author"][$i]["person"]["USP"]["autor_funcao"] = $autor["person"]["USP"]["autor_funcao"];
        //                 }
        //                 echo '<br/>Sem resultado: '.$result_tematres["term_not_found"].'<br/>';
        //                 $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["name_not_found"] = $result_tematres["term_not_found"];

        //             }
        //         } else {

        //             $resultado_get_id_tematres["resume"]["cant_result"] = 0;
        //             if (!empty($autor["person"]["name"])) {
        //                 $body_upsert["doc"]["author"][$i]["person"]["name"] = $autor["person"]["name"];
        //             }
        //             if (!empty($autor["person"]["affiliation"]["location"])) {
        //                 $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["location"] = $autor["person"]["affiliation"]["location"];
        //             }
        //             if (!empty($autor["person"]["date"])) {
        //                 $body_upsert["doc"]["author"][$i]["person"]["date"] = $autor["person"]["date"];
        //             }
        //             if (!empty($autor["person"]["potentialAction"])) {
        //                 $body_upsert["doc"]["author"][$i]["person"]["potentialAction"] = $autor["person"]["potentialAction"];
        //             }
        //             if (!empty($autor["person"]["USP"]["autor_funcao"])) {
        //                 $body_upsert["doc"]["author"][$i]["person"]["USP"]["autor_funcao"] = $autor["person"]["USP"]["autor_funcao"];
        //             }
        //             if (!empty($autor["person"]["affiliation"]["name"])) {
        //                 echo '<br/>Termo existente: '.$autor["person"]["affiliation"]["name"].'<br/>';
        //                 $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["name"] = $autor["person"]["affiliation"]["name"];
        //             }
        //             if (!empty($author["person"]["affiliation"]["locationTematres"])) {
        //                 echo '<br/>Local existente: '.$author["person"]["affiliation"]["locationTematres"].'<br/>';
        //                 $body_upsert["doc"]["author"][$i]["person"]["affiliation"]["locationTematres"] = $author["person"]["affiliation"]["locationTematres"];
        //             }



        //     $body_upsert["doc_as_upsert"] = true;
        //     echo '<br/>';
        //     print_r($body_upsert);
        //     $resultado_upsert = elasticsearch::elastic_update($registro["_id"], $type, $body_upsert);
        //     echo '<br/><br/>';
        //     print_r($resultado_upsert);
        //     unset($body_upsert);

        //     echo "<br/>=========================================================<br/><br/>";
        }

        ?>

        </div>
    </body>
</html>
