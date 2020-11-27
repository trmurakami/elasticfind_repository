<!DOCTYPE html>
<?php

require 'inc/config.php'; 
require 'inc/functions.php';

if (!empty($_POST)) {
    foreach ($_POST as $key=>$value) {
        $var_concluido["doc"]["concluido"] = $value;
        $var_concluido["doc"]["doc_as_upsert"] = true; 
        Elasticsearch::update($key, $var_concluido);
    }
    sleep(6);
    header("Refresh:0");
}

if (isset($fields)) {
    $_GET["fields"] = $fields;
}
$result_get = Requests::getParser($_GET);
$limit = $result_get['limit'];
$page = $result_get['page'];
$params = [];
$params["index"] = $index;
$params["body"] = $result_get['query'];
$cursorTotal = $client->count($params);
$total = $cursorTotal["count"];
if (isset($_GET["sort"])) {
    $result_get['query']["sort"][$_GET["sort"]]["unmapped_type"] = "long";
    $result_get['query']["sort"][$_GET["sort"]]["missing"] = "_last";
    $result_get['query']["sort"][$_GET["sort"]]["order"] = "desc";
    $result_get['query']["sort"][$_GET["sort"]]["mode"] = "max";
} else {
    $result_get['query']['sort']['datePublished.keyword']['order'] = "desc";
    $result_get['query']["sort"]["_uid"]["unmapped_type"] = "long";
    $result_get['query']["sort"]["_uid"]["missing"] = "_last";
    $result_get['query']["sort"]["_uid"]["order"] = "desc";
    $result_get['query']["sort"]["_uid"]["mode"] = "max";
}
$params["body"] = $result_get['query'];
$params["size"] = $limit;
$params["from"] = $result_get['skip'];
$cursor = $client->search($params);

/*pagination - start*/
$get_data = $_GET;    
/*pagination - end*/      

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include('inc/meta-header.php'); 
        ?>        
        <title><?php echo $branch_abrev ?> - <?php echo $t->gettext('Resultado de Busca'); ?></title>
        
    </head>
    <body>

        <!-- NAV -->
        <?php require 'inc/navbar.php'; ?>
        <!-- /NAV -->
        <br/><br/><br/><br/>

        <main role="main">
            <div class="container">

            <div class="row">
                <div class="col-8">   

                    <!-- Navegador de resultados - Início -->
                    <?php ui::pagination($page, $total, $limit); ?>
                    <!-- Navegador de resultados - Fim -->

                    <?php if($total == 0) : ?>
                        <br/>
                        <div class="alert alert-info" role="alert">
                        Sua busca não obteve resultado. Você pode refazer sua busca abaixo:<br/><br/>
                            <form action="result.php">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="Pesquise por termo ou autor">
                                    <small id="searchHelp" class="form-text text-muted">Dica: Use * para busca por radical. Ex: biblio*.</small>
                                    <small id="searchHelp" class="form-text text-muted">Dica 2: Para buscas exatas, coloque entre ""</small>
                                    <small id="searchHelp" class="form-text text-muted">Dica 3: Você também pode usar operadores booleanos: AND, OR</small>
                                </div>                       
                                <button type="submit" class="btn btn-primary">Pesquisar</button>
                                
                            </form>
                        </div>
                        <br/><br/>
                    
                    <?php endif; ?>

                    <?php foreach ($cursor["hits"]["hits"] as $r) : ?>

                        <?php //print_r($r); ?>
                        <?php if (empty($r["_source"]['datePublished'])) {
                            $r["_source"]['datePublished'] = "";
                        }
                        ?>

                        <div class="card">
                            <div class="card-body">

                            <div class="row">
                                <div class="col">
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['base'];?> | <?php echo $r["_source"]['type'];?></h6>
                                    <h5 class="card-title text-dark"><?php echo $r["_source"]['name']; ?> (<?php echo $r["_source"]['datePublished'];?>)</h5>
                                    <p class="text-muted"><b>Autores:</b>
                                    <?php if (!empty($r["_source"]['author'])) : ?>
                                        <?php foreach ($r["_source"]['author'] as $autores) {
                                            $authors_array[]='<a href="result.php?filter[]=author.person.name:&quot;'.$autores["person"]["name"].'&quot;">'.$autores["person"]["name"].'</a>';
                                        } 
                                        $array_aut = implode(", ",$authors_array);
                                        unset($authors_array);
                                        print_r($array_aut);
                                        ?>
                                    <?php endif; ?>
                                    </p>
                                    <?php if (!empty($r["_source"]['isPartOf']['name'])) : ?>
                                    <p class="text-muted"><b>In:</b> <a href="result.php?filter[]=isPartOf.name:&quot;<?php echo $r["_source"]['isPartOf']['name'];?>&quot;"><?php echo $r["_source"]['isPartOf']['name'];?></a></p>
                                    <?php endif; ?>
                                    <?php if (!empty($r["_source"]['isPartOf']['issn'])) : ?>
                                        <p class="text-muted"><b>ISSN:</b> <a href="result.php?filter[]=isPartOf.issn:&quot;<?php echo $r["_source"]['isPartOf']['issn'];?>&quot;"><?php echo $r["_source"]['isPartOf']['issn'];?></a></li>                                        
                                    <?php endif; ?>
                                    <?php if (!empty($r["_source"]['isbn'])) : ?>
                                        <p class="text-muted"><b>ISBN:</b> <a href="result.php?filter[]=isbn:&quot;<?php echo $r["_source"]['isbn'];?>&quot;"><?php echo $r["_source"]['isbn'];?></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($r["_source"]['EducationEvent']['name'])) : ?>
                                        <p class="text-muted"><b>Nome do evento:</b> <?php echo $r["_source"]['EducationEvent']['name'];?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($r["_source"]['doi'])) : ?>
                                        <p class="text-muted"><b>DOI:</b>    <a href="https://doi.org/<?php echo $r["_source"]['doi'];?>"><span id="<?php echo $r['_id'] ?>"><?php echo $r["_source"]['doi'];?></span></a> <button class="btn btn-info" onclick="copyToClipboard('#<?=$r['_id']?>')">Copiar DOI</button> <a class="btn btn-warning" href="doi_to_elastic.php?doi=<?php echo $r['_source']['doi'];?>&tag=<?php echo $r['_source']['tag'][0];?>">Coletar dados da Crossref</a></p>                                        
                                    <?php endif; ?>

                                    <?php if (!empty($r["_source"]['url'])) : ?>
                                        <p class="text-muted"><b>URL:</b> <a href="<?php echo str_replace("]", "", str_replace("[", "", $r["_source"]['url'])); ?>"><?php echo str_replace("]", "", str_replace("[", "", $r["_source"]['url']));?></a></p>
                                    <?php endif; ?>
                                    <?php if (!empty($r["_source"]['ExternalData']['crossref']['message']['is-referenced-by-count'])) : ?>
                                        <p class="text-muted"><b>Citações na Crossref:</b> <?php echo $r["_source"]['ExternalData']['crossref']['message']['is-referenced-by-count'];?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($r["_source"]['ids_match'])) : ?>  
                                        <?php foreach ($r["_source"]['ids_match'] as $id_match) : ?>
                                            <?php compararRegistros::match_id($id_match["id_match"], $id_match["nota"]);?>
                                        <?php endforeach;?>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-auto">
                                    <?php
                                        if (!empty($r["_source"]['isbn'])) {
                                            if (!empty($r["_source"]['isbn'])) {
                                                $file = 'inc/images/covers/'.$r["_source"]['isbn'].'.jpg';
                                            } else {
                                                $file = "";
                                            }
                                            if (file_exists($file)) {
                                                echo '<div class="card" style="width: 18rem;">
                                                <img class="card-img-top" src="'.$file.'" alt="Book Cover">
                                            </div>';
                                            } else {
                                                if ($download_covers == true) {
                                                    if (isset($r["_source"]["url"])) {
                                                        if (strpos($r["_source"]["url"], '.pdf') !== false) {
                                                            if (file_exists('inc/images/covers/'.$r["_source"]['isbn'].'.jpg')) {
                                                                echo '<img src="inc/images/covers/'.$r["_source"]['isbn'].'.jpg">';
                                                            } else {
                                                                if(file_put_contents('tmp/'.$r["_source"]['isbn'].'.pdf',file_get_contents($r["_source"]["url"]))) { 
                                                                    echo "File downloaded successfully";
                                                                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                                    $pdf_file_to_test   = 'tmp/'.$r["_source"]['isbn'].'.pdf';
                                                                    if (finfo_file($finfo, $pdf_file_to_test) === 'application/pdf') {
                                                                        $pdf_file   = 'tmp/'.$r["_source"]['isbn'].'.pdf[0]';
                                                                        $save_to    = 'inc/images/covers/'.$r["_source"]['isbn'].'.jpg';
                                                                        $img = new imagick($pdf_file);
                                                                        $img->setResolution(60, 60);
                                                                        //set new format
                                                                        $img->setImageFormat('jpg');
                                                                        //save image file
                                                                        $img->writeImage($save_to);
                                                                        //echo '<img src="inc/images/covers/'.$r["_source"]['isbn'].'.jpg">';
                                                                    }
                                                                } else { 
                                                                    echo "File downloading failed."; 
                                                                }
        
        
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                                    <?php

                                        ?>
                                </div>
                            </div>

                            </div>
                        </div>
                        <?php endforeach;?>


                        <!-- Navegador de resultados - Início -->
                        <?php ui::pagination($page, $total, $limit); ?>
                        <!-- Navegador de resultados - Fim -->  

                </div>
                <div class="col-4">
                
                <hr>                
                <h3>Refinar meus resultados</h3>
                <hr>
                <?php
                    $facets = new facets();
                    $facets->query = $result_get['query'];

                    if (!isset($_GET)) {
                        $_GET = null;
                    }   
                    
                    $facets->facet(basename(__FILE__), "vinculo.ppg_nome", 100, "Nome do PPG", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.campus", 100, "Campus", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.desc_gestora", 100, "Gestora", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.unidade", 100, "Unidade", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.departamento", 100, "Departamento", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.divisao", 100, "Divisão", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.secao", 100, "Seção", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.tipvin", 100, "Tipo de vínculo", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.genero", 100, "Genero", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.desc_nivel", 100, "Nível", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.desc_curso", 100, "Curso", null, "_term", $_GET);                    
                    
                    $facets->facet(basename(__FILE__), "Lattes.natureza", 100, "Natureza", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "tipo", 100, "Tipo de material", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "tag", 100, "Tag", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "match.tag", 100, "Tag de correspondência", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "match.string", 100, "Tag de correspondência", null, "_term", $_GET);
                    
                    $facets->facet(basename(__FILE__), "author.person.name", 100, "Nome completo do autor", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "lattes_ids", 100, "Número do lattes", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "vinculo.nome",100,"Nome do autor vinculado à instituição",null,"_term",$_GET);
                    
                    $facets->facet(basename(__FILE__), "country",200,"País de publicação",null,"_term",$_GET);
                    $facets->facet(basename(__FILE__), "datePublished",120,"Ano de publicação","desc","_term",$_GET);
                    $facets->facet(basename(__FILE__), "language",40,"Idioma",null,"_term",$_GET);
                    $facets->facet(basename(__FILE__), "Lattes.meioDeDivulgacao",100,"Meio de divulgação",null,"_term",$_GET);
                    $facets->facet(basename(__FILE__), "about",100,"Palavras-chave",null,"_term",$_GET);
                    $facets->facet(basename(__FILE__), "agencia_de_fomento",100,"Agências de fomento",null,"_term",$_GET);

                    $facets->facet(basename(__FILE__), "Lattes.flagRelevancia",100,"Relevância",null,"_term",$_GET);
                    $facets->facet(basename(__FILE__), "Lattes.flagDivulgacaoCientifica",100,"Divulgação científica",null,"_term",$_GET);
                    
                    $facets->facet(basename(__FILE__), "area_do_conhecimento.nomeGrandeAreaDoConhecimento", 100, "Nome da Grande Área do Conhecimento", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "area_do_conhecimento.nomeDaAreaDoConhecimento", 100, "Nome da Área do Conhecimento", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "area_do_conhecimento.nomeDaSubAreaDoConhecimento", 100, "Nome da Sub Área do Conhecimento", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "area_do_conhecimento.nomeDaEspecialidade", 100, "Nome da Especialidade", null, "_term", $_GET);
                    
                    $facets->facet(basename(__FILE__), "trabalhoEmEventos.classificacaoDoEvento", 100, "Classificação do evento", null, "_term", $_GET); 
                    $facets->facet(basename(__FILE__), "EducationEvent.name", 100, "Nome do evento", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "publisher.organization.name", 100, "Editora", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "publisher.organization.location", 100, "Cidade", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "trabalhoEmEventos.anoDeRealizacao", 100, "Ano de realização do evento", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "trabalhoEmEventos.tituloDosAnaisOuProceedings", 100, "Título dos anais", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "trabalhoEmEventos.isbn", 100, "ISBN dos anais", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "trabalhoEmEventos.nomeDaEditora", 100, "Editora dos anais", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "trabalhoEmEventos.cidadeDaEditora", 100, "Cidade da editora", null, "_term", $_GET);

                    $facets->facet(basename(__FILE__), "midiaSocialWebsiteBlog.formacao_maxima", 100, "Formação máxima - Blogs e mídias sociais", null, "_term", $_GET);
                    
                    $facets->facet(basename(__FILE__), "isPartOf.name", 100, "Título do periódico", null, "_term", $_GET);

                    $facets->facet(basename(__FILE__), "concluido", 100, "Concluído", null, "_term", $_GET);
                    $facets->facet(basename(__FILE__), "bdpi.existe", 100, "Está na FONTE?", null, "_term", $_GET);

                ?>
                </ul>
                <!-- Limitar por data - Início -->
                <form action="result.php?" method="GET">
                    <h5 class="mt-3">Filtrar por ano de publicação</h5>
                    <?php 
                        parse_str($_SERVER["QUERY_STRING"], $parsedQuery);
                        foreach ($parsedQuery as $k => $v) {
                            if (is_array($v)) {
                                foreach ($v as $v_unit) {
                                    echo '<input type="hidden" name="'.$k.'[]" value="'.htmlentities($v_unit).'">';
                                }
                            } else {
                                if ($k == "initialYear") {
                                    $initialYearValue = $v;
                                } elseif ($k == "finalYear") {
                                    $finalYearValue = $v;
                                } else {
                                    echo '<input type="hidden" name="'.$k.'" value="'.htmlentities($v).'">';
                                }
                            }
                        }

                        if (!isset($initialYearValue)) {
                            $initialYearValue = "";
                        }
                        if (!isset($finalYearValue)) {
                            $finalYearValue = "";
                        }

                    ?>
                    <div class="form-group">
                        <label for="initialYear">Ano inicial</label>
                        <input type="text" class="form-control" id="initialYear" name="initialYear" pattern="\d{4}" placeholder="Ex. 2010" value="<?php echo $initialYearValue; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="finalYear">Ano final</label>
                        <input type="text" class="form-control" id="finalYear" name="finalYear" pattern="\d{4}" placeholder="Ex. 2020" value="<?php echo $finalYearValue; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>   
                <!-- Limitar por data - Fim -->
                <hr>
                <h3>Exportar</h3>
                <p><a href="tools/export.php?<?php echo $_SERVER["QUERY_STRING"] ?>&format=ris">Exportar em formato RIS</a></p>
                <p><a href="tools/export.php?<?php echo $_SERVER["QUERY_STRING"] ?>&format=bibtex">Exportar em formato BIBTEX</a></p>
                <p><a href="tools/export.php?<?php echo $_SERVER["QUERY_STRING"] ?>&format=dspace">Exportar em formato CSV para o DSpace</a></p>
                <p><a href="tools/export.php?<?php echo $_SERVER["QUERY_STRING"] ?>&format=authorNetwork">Exportar em formato CSV para o Gephi da Rede de Co-Autoria incluindo publicações</a></p>
                <p><a href="tools/export.php?<?php echo $_SERVER["QUERY_STRING"] ?>&format=authorNetworkWithoutPapers">Exportar em formato CSV para o Gephi da Rede de Co-Autoria sem publicações</a></p>
                <hr>
            </div>
        </div>

        <?php include('inc/footer.php'); ?>

        </div>

        <script>
            function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            }
        </script>
        
    </body>
</html>