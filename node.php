<?php
/**
 * Item page
 */

require 'inc/config.php';


/* QUERY */
$cursor = Elasticsearch::get($_GET['_id'], null);

?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $branch_abrev; ?> - Detalhe do registro: <?php echo $cursor["_source"]['name'];?></title>
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
        <div class="uk-section uk-section-default" style="padding:0">

            <!-- NAV -->
            <?php require 'inc/navbar.php'; ?>
            <!-- /NAV -->
        
            <div class="uk-container">

                <div class="uk-grid" data-ukgrid>
                    <div class="uk-width-3-4@m">

                        <?php
                        $record = new Record($cursor, $show_metrics);
                        $record->completeRecordMetadata($t, $url_base);
                        ?>

                    </div>
                    <div class="uk-width-1-4@m">

                        <div class="uk-card uk-card-body">
                            <h5 class="uk-panel-title">Ver registro no DEDALUS</h5>
                            <ul class="uk-nav uk-margin-top uk-margin-bottom">
                                <hr>
                                <li>
                                    <a class="uk-button uk-button-primary uk-button-small" href="http://dedalus.usp.br/F/?func=direct&doc_number=<?php echo $cursor["_id"];?>" target="_blank" rel="noopener noreferrer nofollow">Ver no Dedalus</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <hr class="uk-grid-divider">
            <?php require 'inc/footer.php'; ?>                   
            
            </div>
                       

</body>
</html>