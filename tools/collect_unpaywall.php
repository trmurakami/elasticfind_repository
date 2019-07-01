<!DOCTYPE html>
<?php

    require '../inc/config.php'; 

    $query["query"]["query_string"]["query"] = "+_exists_:doi -_exists_:USP.unpaywall";
    $query['sort'] = [
        ['datePublished.keyword' => ['order' => 'desc']],
    ];    

    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 2;
    $params["body"] = $query; 

    $cursor = $client->search($params);
    $total = $cursor["hits"]["total"]["value"];

    echo "Registros restantes: $total<br/><br/>";

    foreach ($cursor["hits"]["hits"] as $r) {

        $json_url = 'https://api.unpaywall.org/v2/'.$r["_source"]["doi"].'?email=tiago.murakami@dt.sibi.usp.br';
        $json = file_get_contents($json_url);
        $data = json_decode($json, true);

        if (!empty($data)) {
            echo "<br/>Encontrado<br/>";
            $body["doc"]["USP"]["unpaywall"] = $data;
            $body["doc"]["USP"]["unpaywall"]["found"] = true;
            $body["doc"]["USP"]["unpaywall"]["collectDate"] = date("Ymd");
            $body["doc_as_upsert"] = true;
            $result = Elasticsearch::storeRecord($r["_id"], $body);
            print_r($result);
            ob_flush();
            flush();             
        } else {
            echo "<br/>Não encontrado<br/>";
            $body["doc"]["USP"]["unpaywall"]["found"] = false;
            $body["doc"]["USP"]["unpaywall"]["collectDate"] = date("Ymd");
            $body["doc_as_upsert"] = true;
            $result = Elasticsearch::storeRecord($r["_id"], $body);
            print_r($result);
            ob_flush();
            flush();
        }
    }

?>
