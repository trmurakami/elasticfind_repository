<!DOCTYPE html>
<?php

    require '../inc/config.php'; 

    $query["query"]["query_string"]["query"] = "+_exists_:doi -_exists_:USP.crossref";
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

    echo 'Registros restantes: '.($total - $params["size"]).'<br/>';

    foreach ($cursor["hits"]["hits"] as $r) {

        $clientCrossref = new RenanBr\CrossRefClient();
        $clientCrossref->setUserAgent('GroovyBib/1.1 (https://bdpi.usp.br/; mailto:tiago.murakami@dt.sibi.usp.br)');
        $exists = $clientCrossref->exists('works/'.$r["_source"]["doi"].'');

        if ($exists == true) {

            $work = $clientCrossref->request('works/'.$r["_source"]["doi"].'');
            echo "<br/>Encontrado<br/>";
            $body["doc"]["USP"]["crossref"] = $work;
            $body["doc"]["USP"]["crossref"]["found"] = true;
            $body["doc"]["USP"]["crossref"]["collectDate"] = date("Ymd");
            $body["doc_as_upsert"] = true;
            $resultado_crossref = Elasticsearch::storeRecord($r["_id"], $body);
            print_r($resultado_crossref);
            sleep(11);
            ob_flush();
            flush();          

        } else {
            echo "<br/>Não encontrado<br/>";
            $body["doc"]["USP"]["crossref"]["found"] = false;
            $body["doc"]["USP"]["crossref"]["collectDate"] = date("Ymd");
            $body["doc_as_upsert"] = true;
            $resultado_crossref = Elasticsearch::storeRecord($r["_id"], $body);
            print_r($resultado_crossref);
            sleep(2);
            ob_flush();
            flush();
        }

    }

?>
