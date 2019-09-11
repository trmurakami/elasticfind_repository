<?php
/**
 * PHP version 7
 * Result page
 *
 * The page for display results of search.
 *
 * @category Search
 * @package  Uspfind
 * @author   Tiago Murakami <tiago.murakami@dt.sibi.usp.br>
 * @link     https://github.com/SIBiUSP/uspfind
 */

require 'inc/config.php';


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


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
        require 'inc/meta-header.php';        
        ?>
        <title><?php echo $branch_abrev ?> - <?php echo $t->gettext('Resultado de Busca'); ?></title>

    </head>
    <body>
        <?php
        if (file_exists("inc/analyticstracking.php")) {
            include_once "inc/analyticstracking.php";
        }
        ?>

        <!-- NAV -->
        <?php require 'inc/navbar.php'; ?>
        <!-- /NAV -->

        <br/><br/><br/><br/>

        <main role="main">
            <div class="container">

            <div class="row">
                <div class="col-8">                
                    <!-- PAGINATION -->
                    <?php UI::pagination($page, $total, $limit, $t); ?>
                    <!-- /PAGINATION -->   

                    <!-- RECORDS -->
                    <?php

                    foreach ($cursor["hits"]["hits"] as $r) {
                        $record = new Record($r, $show_metrics);
                        $record->simpleRecordMetadata($t);  
                    }
                    
                    ?>  
                    <!-- /RECORDS -->

                    <!-- PAGINATION -->
                    <?php UI::pagination($page, $total, $limit, $t); ?>
                    <!-- /PAGINATION -->                                 
                
                </div>
                <div class="col-4">

                    <!-- FACETS -->
                    <h3><?php echo $t->gettext('Refinar busca'); ?></h3>
                    <hr>
                    <ul class="uk-nav-default uk-nav-parent-icon" uk-nav="multiple: true">
                    <?php
                    $facets = new Facets();
                    $facets->query = $result_get['query'];

                    $facets->facet("base", 10, $t->gettext('Bases'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("type", 100, $t->gettext('Tipo de material'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("unidadeUSP", 50, $t->gettext('Unidades USP'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("authorUSP.departament", 50, $t->gettext('Departamento'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("author.person.name", 50, $t->gettext('Autores'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("authorUSP.name", 50, $t->gettext('Autores USP'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("datePublished", 80, $t->gettext('Ano de publicação'), "desc", "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("about", 50, $t->gettext('Assuntos'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("language", 40, $t->gettext('Idioma'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("isPartOf.name", 50, $t->gettext('Título da fonte'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("publisher.organization.name", 50, $t->gettext('Editora'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("releasedEvent", 50, $t->gettext('Nome do evento'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("country", 200, $t->gettext('País de publicação'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("USP.grupopesquisa", 100, "Grupo de pesquisa", null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("funder.name", 50, $t->gettext('Agência de fomento'), null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.indexacao", 50, $t->gettext('Indexado em'), null, "_term", $_SERVER["QUERY_STRING"]);
                    ?>
                    <li class="uk-nav-header"><?php echo $t->gettext('Colaboração institucional'); ?></li>
                    <?php
                    $facets->facet("author.person.affiliation.name", 50, $t->gettext('Afiliação dos autores externos'), null, "_term", $_SERVER["QUERY_STRING"], true);
                    $facets->facet("author.person.affiliation.location", 50, $t->gettext('País das instituições de afiliação dos autores externos'), null, "_term", $_SERVER["QUERY_STRING"]);
                    ?>
                    <li class="uk-nav-header"><?php echo $t->gettext('Métricas do periódico'); ?></li>
                    <?php
                    $facets->facet("USP.qualis.qualis.2016.area", 50, $t->gettext('Qualis 2013/2016 - Área'), null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.qualis.qualis.2016.nota", 50, $t->gettext('Qualis 2013/2016 - Nota'), null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.qualis.qualis.2016.area_nota", 50, $t->gettext('Qualis 2013/2016 - Área / Nota'), null, "_term", $_SERVER["QUERY_STRING"]);
                    ?>
                    <li class="uk-nav-header"><?php echo $t->gettext('Teses e Dissertações'); ?></li>
                    <?php
                    $facets->facet("inSupportOf", 30, $t->gettext('Tipo de tese'), null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.areaconcentracao", 100, "Área de concentração", null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.programa_pos_sigla", 100, "Sigla do Departamento/Programa de Pós Graduação", null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.programa_pos_nome", 100, "Departamento/Programa de Pós Graduação", null, "_term", $_SERVER["QUERY_STRING"]);
                    $facets->facet("USP.about_BDTD", 50, $t->gettext('Palavras-chave do autor'), null, "_term", $_SERVER["QUERY_STRING"]);
                    ?>
                    </ul>
                    <!-- < ?php if (!empty($_SESSION['oauthuserdata'])) : ?> -->
                        <h3 class="uk-panel-title uk-margin-top">Informações administrativas</h3>
                        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav="multiple: true">
                        <hr>
                        <?php
                        $facets->facet("original.type", 100, $t->gettext('Especificidade de tipo de material'), null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("author.person.affiliation.locationTematres", 50, $t->gettext('País Tematres'), null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.internacionalizacao", 10, "Internacionalização", null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.fatorimpacto", 100, "Fator de impacto - 590m", null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("authorUSP.regime_de_trabalho", 50, $t->gettext('Regime de trabalho'), null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("authorUSP.funcao", 50, $t->gettext('Função'), null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.CAT.date", 100, "Data de registro e alterações", "desc", "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.CAT.cataloger", 100, "Catalogador", "desc", "_count", $_SERVER["QUERY_STRING"]);
                        $facets->facet("authorUSP.codpes", 100, "Número USP", null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("isPartOf.issn", 100, "ISSN", null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("doi", 100, "DOI", null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.crossref.message.funder.name", 50, $t->gettext('Agência de fomento obtida na CrossRef'), null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.fullTextFiles.name", 10, $t->gettext('Texto completo'), null, "_term", $_SERVER["QUERY_STRING"]);
                        $facets->facet("USP.fullTextFiles.description", 10, $t->gettext('Texto completo - Descrição'), null, "_term", $_SERVER["QUERY_STRING"]);                
                        //$facets->rebuild_facet("author.person.affiliation.name_not_found", 50, $t->gettext('Afiliação dos autores externos não normalizada'), null, "_term", $_SERVER["QUERY_STRING"]);
                        ?>
                        </ul>
                    <!-- < ?php endif; ?> -->
                    <!-- /FACETS --> 



                    <form action="result.php?" method="GET">
                        <h5><?php echo $t->gettext('Filtrar por ano de publicação') ?></h5>
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
                            <label for="initialYear"><?php echo $t->gettext('Ano inicial') ?></label>
                            <input type="text" class="form-control" id="initialYear" name="initialYear" pattern="\d{4}" placeholder="Ex. 2010" value="<?php echo $initialYearValue; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="finalYear"><?php echo $t->gettext('Ano final') ?></label>
                            <input type="text" class="form-control" id="finalYear" name="finalYear" pattern="\d{4}" placeholder="Ex. 2020" value="<?php echo $finalYearValue; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $t->gettext('Filtrar'); ?></button>
                    </form>                                   
                
                </div>


            </div>

            <?php require 'inc/footer.php'; ?>
            
            </div>
        </main>

        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

        <!-- TOP -->
        <div class="top-wrap uk-position-relative uk-background-secondary">
            <div class="uk-section uk-section-default" style="padding:0">
            
                <div class="uk-container">

                <!-- NEW SEARCH -->

                <div class="uk-width-1-1@s uk-width-1-1@m">
                    <nav class="uk-navbar-container uk-margin" uk-navbar>
                        <div class="nav-overlay uk-navbar-left">
                            <a class="uk-navbar-item uk-logo" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"><?php echo $t->gettext('Clique para uma nova pesquisa'); ?></a>
                        </div>
                        <div class="nav-overlay uk-navbar-right">
                            <a class="uk-navbar-toggle" uk-search-icon uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"></a>
                        </div>
                        <div class="nav-overlay uk-navbar-left uk-flex-1" hidden>
                        <div class="uk-navbar-item uk-width-expand">
                        <form class="uk-search uk-search-navbar uk-width-1-1">
                            <input class="uk-search-input" type="search" name="search[]" placeholder="<?php echo $t->gettext('Nova pesquisa...'); ?>" autofocus>
                            </form>
                        </div>

                        <a class="uk-navbar-toggle" uk-close uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"></a>

                        </div>
                    </nav>
                </div>

                <!-- /NEW SEARCH -->


                <!-- FILTERS -->
                <?php if (!empty($_SERVER["QUERY_STRING"])) : ?>
                <p class="uk-margin-top" uk-margin>
                    <a class="uk-button uk-button-default uk-button-small" href="index.php"><?php echo $t->gettext('Começar novamente'); ?></a>
                    <?php
                    if (!empty($_GET["search"])) {
                        foreach ($_GET["search"] as $querySearch) {
                            $querySearchArray[] = $querySearch;
                            $name_field = explode(":", $querySearch);
                            $querySearch = str_replace($name_field[0].":", "", $querySearch);
                            $diff["search"] = array_diff($_GET["search"], $querySearchArray);
                            $url_push = $_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"].'?'.http_build_query($diff);
                            echo '<a class="uk-button uk-button-default uk-button-small" href="http://'.$url_push.'">'.$querySearch.' <span uk-icon="icon: close; ratio: 1"></span></a>';
                            unset($querySearchArray);
                        }
                    }

                    if (!empty($_GET["filter"])) {
                        foreach ($_GET["filter"] as $filters) {
                            $filters_array[] = $filters;
                            $name_field = explode(":", $filters);
                            $filters = str_replace($name_field[0].":", "", $filters);
                            $diff["filter"] = array_diff($_GET["filter"], $filters_array);
                            $url_push = $_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"].'?'.http_build_query($diff);
                            echo '<a class="uk-button uk-button-primary uk-button-small" href="http://'.$url_push.'">Filtrado por: '.$filters.' <span uk-icon="icon: close; ratio: 1"></span></a>';
                            unset($filters_array);
                        }
                    }

                    if (!empty($_GET["notFilter"])) {
                        foreach ($_GET["notFilter"] as $notFilters) {
                            $notFiltersArray[] = $notFilters;
                            $name_field = explode(":", $notFilters);
                            $notFilters = str_replace($name_field[0].":", "", $notFilters);
                            $diff["notFilter"] = array_diff($_GET["notFilter"], $notFiltersArray);
                            $url_push = $_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"].'?'.http_build_query($diff);
                            echo '<a class="uk-button uk-button-danger uk-button-small" href="http://'.$url_push.'">Ocultando: '.$notFilters.' <span uk-icon="icon: close; ratio: 1"></span></a>';
                            unset($notFiltersArray);
                        }
                    }
                    ?>

                </p>
                <?php endif;?>
                <!-- /FILTERS -->



                    <div class="uk-grid" data-ukgrid>
                        <div class="uk-width-2-3@m">

                      

                        </div>
                        <div class="uk-width-1-3@m">

                     

                        </div>
                    </div>
                    <hr class="uk-grid-divider">
                    

                </div>
            </div>
        </div>

     

        <!-- JS FILES -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit-icons.min.js"></script>
        <script>
        $('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){
            var url = window.location.href.split('&page')[0];
            window.location=url +'&page='+ (pageIndex+1);
        });
        </script>

    </body>
</html>