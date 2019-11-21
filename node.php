<?php
/**
 * Item page
 */

require 'inc/config.php';


/* Citeproc-PHP*/
require 'inc/citeproc-php/CiteProc.php';
$csl_abnt = file_get_contents('../csl/ecausp-abnt.csl');
$lang = "br";
$citeproc_abnt = new citeproc($csl_abnt, $lang, $csl_abnt);
$mode = "reference";


/* QUERY */
$cursor = Elasticsearch::get($_GET['_id'], null);

?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>

    <?php
    require 'inc/meta-header.php';
    ?>   
    <title><?php echo $branch_abrev; ?> - Detalhe do registro: <?php echo $cursor["_source"]['name'];?></title>

</head>
<body>
    <?php
    if (file_exists("inc/analyticstracking.php")) {
        include_once "inc/analyticstracking.php";
    }
    ?>
    <!-- TOP -->
    <div class="top-wrap uk-position-relative uk-background-secondary">
        <div class="uk-section uk-section-default" style="padding:0">

            <!-- NAV -->
            <?php require 'inc/navbar.php'; ?>
            <!-- /NAV -->
        
            <div class="uk-container">
                <div class="container">
                    <div class="row">
                        <div class="col-8">   
                

                        <?php
                        $record = new Record($cursor, $show_metrics);
                        $record->completeRecordMetadata($t, $url_base, $csl_abnt);
                        ?>

                        </div>
                        <div class="col-4">  

                        <div class="uk-card uk-card-body">
                            <h5 class="uk-panel-title">Ver registro no DEDALUS</h5>
                            <ul class="uk-nav uk-margin-top uk-margin-bottom">
                                <hr>
                                <li>
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary uk-button-small" href="http://dedalus.usp.br/F/?func=direct&doc_number=<?php echo $cursor["_id"];?>" target="_blank" rel="noopener noreferrer nofollow">Ver no Dedalus</a>
                                </li>
                            </ul>
                            <h5 class="uk-panel-title">Exportar registro bibliográfico</h5>
                            <ul class="uk-nav uk-margin-top uk-margin-bottom">
                                <hr>
                                <li>
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary" href="<?php echo $url_base; ?>/tools/export.php?search[]=(sysno.keyword%3A<?php echo $cursor["_id"];?>)&format=ris" rel="noopener noreferrer nofollow">RIS (EndNote)</a>
                                </li>
                                <li class="uk-nav-divider">
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary" href="<?php echo $url_base; ?>/tools/export.php?search[]=(sysno.keyword%3A<?php echo $cursor["_id"];?>)&format=bibtex" rel="noopener noreferrer nofollow">Bibtex</a>
                                </li>
                                <li class="uk-nav-divider">
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary" href="<?php echo $url_base; ?>/tools/export.php?search[]=(sysno.keyword%3A<?php echo $cursor["_id"];?>)&format=csvThesis" rel="noopener noreferrer nofollow">Tabela (TSV)</a>
                                </li>
                            </ul>                            
                        </div>

                        <!-- Other works of same authors - Start -->
                        <?php
                        if (isset($cursor["_source"]["authorUSP"])) {
                            foreach ($cursor["_source"]["authorUSP"] as $authorUSPArray) {
                                $authorUSPArrayCodpes[] = $authorUSPArray["codpes"];
                            }
                            $queryOtherWorks["query"]["bool"]["must"]["query_string"]["query"] = 'authorUSP.codpes:('.implode(" OR ", $authorUSPArrayCodpes).')';
                            $queryOtherWorks["query"]["bool"]["must_not"]["term"]["name.keyword"] = $cursor["_source"]["name"];
                            $resultOtherWorks = Elasticsearch::search(["_id","name"], 10, $queryOtherWorks);
                            echo '<div class="uk-alert-primary" uk-alert>';
                            echo '<h5>Últimas obras dos mesmos autores vinculados com a USP cadastradas na BDPI:</h5><ul class="list-group list-group-flush">';
                            foreach ($resultOtherWorks["hits"]["hits"] as $othersTitles) {
                                //print_r($othersTitles);
                                echo '<li class="list-group-item"><a href="'.$url_base.'/item/'.$othersTitles["_id"].'" target="_blank">'.$othersTitles["_source"]["name"].'</a></li>';
                            }
                            echo '</ul></div>';
                        }
                        ?>
                        <!-- Other works of same authors - End -->

                    </div>
                </div>
                <hr class="uk-grid-divider">
            <?php require 'inc/footer.php'; ?>                   
            
            </div>
                       

</body>
</html>