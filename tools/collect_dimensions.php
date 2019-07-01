<!DOCTYPE html>
<?php

    require '../inc/config.php'; 

    $query["query"]["query_string"]["query"] = "+_exists_:doi -_exists_:USP.dimensions"; 
    $query['sort'] = [
        ['datePublished.keyword' => ['order' => 'desc']],
    ];    

    $params = [];
    $params["index"] = $index;
    $params["size"] = 20;
    $params["body"] = $query; 

    $cursor = $client->search($params);
    $total = $cursor["hits"]["total"]["value"];

    echo 'Quantidade de registros restantes: '.($total - $params["size"]).'';
    echo '<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {
        echo "<br/>Registro: ";

        $dimensionsData = API::dimensionsAPI($r["_source"]["doi"]);

        if (!empty($dimensionsData["doi"])) {
            echo "Encontrado";
            $body["doc"]["USP"]["dimensions"] = $dimensionsData;
            $body["doc"]["USP"]["dimensions"]["found"] = true;
            $body["doc"]["USP"]["dimensions"]["collectDate"] = date("Ymd");
            $body["doc_as_upsert"] = true;      
        } else {
            echo "Não encontrado";
            $body["doc"]["USP"]["dimensions"]["found"] = false;
            $body["doc"]["USP"]["dimensions"]["collectDate"] = date("Ymd");
            $body["doc_as_upsert"] = true;
        }      

        echo "<br/>";
        $resultado_dimensions = Elasticsearch::storeRecord($r["_id"], $body);
        print_r($resultado_dimensions);
    }
?>