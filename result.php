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

// if (isset($_GET["search"])) {
//     foreach ($_GET["search"] as $getSearch) {
//         $getCleaned[] = htmlspecialchars($getSearch, ENT_QUOTES);
//     }
//     unset($_GET["search"]);
//     $_GET["search"] = $getCleaned;
// }

if (isset($fields)) {
    $_GET["fields"] = $fields;
}

$result_get = Requests::getParser($_GET);
$limit = $result_get['limit'];
$page = $result_get['page'];

// if (isset($_GET["sort"])) {
//     $result_get['query']["sort"][$_GET["sort"]]["unmapped_type"] = "long";
//     $result_get['query']["sort"][$_GET["sort"]]["missing"] = "_last";
//     $result_get['query']["sort"][$_GET["sort"]]["order"] = "desc";
//     $result_get['query']["sort"][$_GET["sort"]]["mode"] = "max";
// } else {
//     $result_get['query']['sort']['datePublished.keyword']['order'] = "desc";
// }

$params = [];
$params["index"] = $index;
$params["size"] = $limit;
$params["from"] = $result_get['skip'];
$params["body"] = $result_get['query'];

$cursor = $client->search($params);
$total = $cursor["hits"]["total"];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BDPI USP - Resultado de Busca - </title>
        <link rel="icon" href="img/favicon.ico">
        <!-- CSS FILES -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/css/uikit.min.css">

    </head>
    <body>

        <?php
        if (file_exists("inc/analyticstracking.php")) {
            include_once "inc/analyticstracking.php";
        }
        ?>
        <!-- TOP -->
        <div class="top-wrap uk-position-relative uk-background-secondary">

            <!-- NAV -->
            <div class="uk-section uk-section-default">
                <?php require 'inc/navbar.php'; ?>
            </div>
            <!-- /NAV -->
            
            <div class="uk-section uk-section-default">
                <div class="uk-container">
                    <div class="uk-grid" data-ukgrid>
                        <div class="uk-width-2-3@m">

                            <!-- RECORDS -->
                            <div class="uk-width-1-1 uk-margin-top uk-description-list-divider">
                                <ul class="uk-list uk-list-divider">
                                    <?php
                                    foreach ($cursor["hits"]["hits"] as $r) {
                                        $record = new Record($r, $show_metrics);
                                        $record->simpleRecordMetadata($t);
                                    }
                                    ?>
                                </ul>
                            </div>
                            <hr class="uk-grid-divider">
                            <!-- /RECORDS -->  

                        </div>
                        <div class="uk-width-1-3@m">

                            <!-- FACETS -->
                            <h3><?php echo $t->gettext('Refinar busca'); ?></h3>
                            <hr>
                            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav="multiple: true">
                            <?php
                            $facets = new Facets();
                            $facets->query = $result_get['query'];

                            if (!isset($_GET["search"])) {
                                $_GET["search"] = null;
                            }

                            $facets->facet("base", 10, $t->gettext('Bases'), null, "_term", $_GET["search"]);
                            $facets->facet("type", 100, $t->gettext('Tipo de material'), null, "_term", $_GET["search"]);
                            $facets->facet("unidadeUSP", 200, $t->gettext('Unidades USP'), null, "_term", $_GET["search"]);
                            $facets->facet("authorUSP.departament", 100, $t->gettext('Departamento'), null, "_term", $_GET["search"]);
                            $facets->facet("author.person.name", 150, $t->gettext('Autores'), null, "_term", $_GET["search"]);
                            $facets->facet("authorUSP.name", 150, $t->gettext('Autores USP'), null, "_term", $_GET["search"]);
                            $facets->facet("datePublished", 120, $t->gettext('Ano de publicação'), "desc", "_term", $_GET["search"]);
                            $facets->facet("about", 50, $t->gettext('Assuntos'), null, "_term", $_GET["search"]);
                            $facets->facet("language", 40, $t->gettext('Idioma'), null, "_term", $_GET["search"]);
                            $facets->facet("isPartOf.name", 50, $t->gettext('Título da fonte'), null, "_term", $_GET["search"]);
                            $facets->facet("publisher.organization.name", 50, $t->gettext('Editora'), null, "_term", $_GET["search"]);
                            $facets->facet("releasedEvent", 50, $t->gettext('Nome do evento'), null, "_term", $_GET["search"]);
                            $facets->facet("country", 200, $t->gettext('País de publicação'), null, "_term", $_GET["search"]);
                            $facets->facet("USP.grupopesquisa", 100, "Grupo de pesquisa", null, "_term", $_GET["search"]);
                            $facets->facet("funder.name", 50, $t->gettext('Agência de fomento'), null, "_term", $_GET["search"]);
                            $facets->facet("USP.indexacao", 50, $t->gettext('Indexado em'), null, "_term", $_GET["search"]);
                            ?>
                            <li class="uk-nav-header"><?php echo $t->gettext('Colaboração institucional'); ?></li>
                            <?php
                            $facets->facet("author.person.affiliation.name", 50, $t->gettext('Afiliação dos autores externos normalizada'), null, "_term", $_GET["search"]);
                            $facets->facet("author.person.affiliation.name_not_found", 50, $t->gettext('Afiliação dos autores externos não normalizada'), null, "_term", $_GET["search"]);
                            $facets->facet("author.person.affiliation.location", 50, $t->gettext('País das instituições de afiliação dos autores externos'), null, "_term", $_GET["search"]);
                            ?>
                            <li class="uk-nav-header"><?php echo $t->gettext('Métricas do periódico'); ?></li>
                            <?php
                            $facets->facet("USP.qualis.qualis.2016.area", 50, $t->gettext('Qualis 2013/2016 - Área'), null, "_term", $_GET["search"]);
                            $facets->facet("USP.qualis.qualis.2016.nota", 50, $t->gettext('Qualis 2013/2016 - Nota'), null, "_term", $_GET["search"]);
                            $facets->facet("USP.qualis.qualis.2016.area_nota", 50, $t->gettext('Qualis 2013/2016 - Área / Nota'), null, "_term", $_GET["search"]);
                            ?>
                            <li class="uk-nav-header"><?php echo $t->gettext('Teses e Dissertações'); ?></li>
                            <?php
                            $facets->facet("inSupportOf", 30, $t->gettext('Tipo de tese'), null, "_term", $_GET["search"]);
                            $facets->facet("USP.areaconcentracao", 100, "Área de concentração", null, "_term", $_GET["search"]);
                            $facets->facet("USP.programa_pos_sigla", 100, "Sigla do Departamento/Programa de Pós Graduação", null, "_term", $_GET["search"]);
                            $facets->facet("USP.programa_pos_nome", 100, "Departamento/Programa de Pós Graduação", null, "_term", $_GET["search"]);
                            $facets->facet("USP.about_BDTD", 50, $t->gettext('Palavras-chave do autor'), null, "_term", $_GET["search"]);
                            ?>
                            </ul>
                            <?php if (!empty($_SESSION['oauthuserdata'])) : ?>
                                <h3 class="uk-panel-title uk-margin-top">Informações administrativas</h3>
                                <ul class="uk-nav-default uk-nav-parent-icon" uk-nav="multiple: true">
                                <hr>
                                <?php
                                $facets->facet("author.person.affiliation.locationTematres", 50, $t->gettext('País Tematres'), null, "_term", $_GET["search"]);
                                $facets->facet("USP.internacionalizacao", 10, "Internacionalização", null, "_term", $_GET["search"]);
                                $facets->facet("USP.fatorimpacto", 100, "Fator de impacto - 590m", null, "_term", $_GET["search"]);
                                $facets->facet("authorUSP.regime_de_trabalho", 50, $t->gettext('Regime de trabalho'), null, "_term", $_GET["search"]);
                                $facets->facet("authorUSP.funcao", 50, $t->gettext('Função'), null, "_term", $_GET["search"]);
                                $facets->facet("USP.CAT.date", 100, "Data de registro e alterações", "desc", "_term", $_GET["search"]);
                                $facets->facet("USP.CAT.cataloger", 100, "Catalogador", "desc", "_count", $_GET["search"]);
                                $facets->facet("authorUSP.codpes", 100, "Número USP", null, "_term", $_GET["search"]);
                                $facets->facet("isPartOf.issn", 100, "ISSN", null, "_term", $_GET["search"]);
                                $facets->facet("doi", 100, "DOI", null, "_term", $_GET["search"]);
                                $facets->facet("USP.crossref.message.funder.name", 50, $t->gettext('Agência de fomento obtida na CrossRef'), null, "_term", $_GET["search"]);
                                $facets->facet("USP.fullTextFiles.name", 10, $t->gettext('Texto completo'), null, "_term", $_GET["search"]);
                                $facets->facet("USP.fullTextFiles.description", 10, $t->gettext('Texto completo - Descrição'), null, "_term", $_GET["search"]);                                                                  
                                $facets->rebuild_facet("author.person.affiliation.name_not_found", 50, $t->gettext('Afiliação dos autores externos não normalizada'), null, "_term", $_GET["search"]);
                                ?>
                                </ul>
                            <?php endif; ?>
                            <!-- /FACETS -->                      

                        </div>
                    </div>
                </div>
            </div>           
        </div>

        <!-- JS FILES -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit-icons.min.js"></script>

    </body>
</html>