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
        $params["body"] = $body;

        $responseCount = $client->count($params);

        if ($_GET["field"] == "author.person.affiliation") {
            $params["_source"] = ["_id","author"];
        } elseif ($_GET["field"] == "funder") {
            $params["_source"] = ["_id","funder"];
        }
        $params["size"] = 2000;
        if (isset($_GET["field"])) {
            $params["from"] = $_GET["from"];
        }              

        $response = $client->search($params);

        echo 'Total de registros faltantes: '.$responseCount["count"].'';



        ?>
        <title>Autoridades</title>
    </head>
    <body>

        <div class="uk-container uk-container-center uk-margin-large-bottom">

        <?php
        // Pega cada um dos registros da resposta
        foreach ($response["hits"]["hits"] as $record) {

            if ($_GET["field"] == "author.person.affiliation") {

                $i = 0;
                $body_upsert["doc"]["author"] = $record['_source']['author'];
    
                // Para cada autor no registro
                foreach ($record['_source']['author'] as $author) {
    
                    if (isset($author["person"]["affiliation"])) {
                        $i_aff = 0;
                        foreach ($author["person"]["affiliation"] as $affiliation) {
    
                            if (isset($affiliation["tematres"])) {
                                if ($affiliation["tematres"] == "false") {
    
                                    $termCleaned = str_replace("&", "e", $affiliation["name"]);
                                    $result_tematres = Authorities::tematres($termCleaned, $tematres_url);
                                    
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
                unset($body_upsert);

            } elseif ($_GET["field"] == "funder") {

                $i = 0;
                $body_upsert["doc"]["funder"] = $record['_source']['funder'];
    
                // Para cada funder no registro
                $i_funder = 0;
                foreach ($record['_source']['funder'] as $funder) {
                    // echo "<br/>";
                    // print_r($funder);
                    // echo "<br/>";

                    if ($funder["tematres"] == "false") {
                        $termCleaned = str_replace("&", "e", $funder["name"]);
                        $result_tematres = Authorities::tematres($termCleaned, $tematres_url);
                        
                        //print_r($result_tematres);

                        if (!empty($result_tematres["found_term"])) {
                            $body_upsert["doc"]["funder"][$i_funder]["name"] = $result_tematres["found_term"];
                            $body_upsert["doc"]["funder"][$i_funder]["tematres"] = "true";
                            $body_upsert["doc"]["funder"][$i_funder]["location"] = $result_tematres["country"];
                            $body_upsert["doc_as_upsert"] = true;
                            echo "<br/>Tem alterações<br/>";                  
                        }

                        if (!empty($result_tematres["term_not_found"])) {
                            echo $result_tematres["term_not_found"];
                            echo "<br/>";

                        }                        

                    }
                    $i_funder++;   
                    $i++;
                }
                

                //print_r($body_upsert);
                $resultado_upsert = Elasticsearch::update($record["_id"], $body_upsert);
                unset($body_upsert);                

            } else {
                echo "Campo não configurado";
            }

        }

        ?>

        </div>
    </body>
</html>
