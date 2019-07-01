<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
    <head>
        <?php
            getcwd();
            chdir('../');
            // Include essencial files
            require 'inc/config.php'; 
            require 'inc/meta-header.php';

            /* Get number of records with doi */
            $query["query"]["query_string"]["query"] = "+_exists_:doi";
            $params = [];
            $params["index"] = $index;
            $params["size"] = 0;
            $params["body"] = $query; 
            $cursor = $client->search($params);
            $totalWithDOI = $cursor["hits"]["total"]["value"];

            /* Get Stats */

            $resultCrossref = AdminStats::source("crossref");
            $resultDimensions = AdminStats::source("dimensions");  
            $resultUnpaywall = AdminStats::source("unpaywall");          

        ?>
        <title><?php echo $branch_abrev ?> - <?php echo $t->gettext('Administração'); ?></title>
    </head>

    <body>
        <?php require 'inc/navbar.php'; ?>
        <div class="uk-container uk-margin-large-top">

        <h3><?php echo $t->gettext('Área de administração e gerenciamento'); ?></h3>

        <h4><?php echo $t->gettext('Estatísticas de coleta de fontes externas'); ?></h4>

        <div class="uk-alert-warning" uk-alert>
            <p><?php echo $t->gettext('Total de registros com DOI'); ?>: <b><?php echo $totalWithDOI; ?></b></p>
        </div>

        <table class="uk-table">
            <caption><?php echo $t->gettext('Coleta de fontes externas'); ?></caption>
            <thead>
                <tr>
                    <th><?php echo $t->gettext('Fonte'); ?></th>
                    <th><?php echo $t->gettext('Registros encontrados'); ?></th>
                    <th><?php echo $t->gettext('Registros registro com erro'); ?></th>
                    <th><?php echo $t->gettext('Total de registros coletados'); ?></th>
                    <th><?php echo $t->gettext('Percentual coletado'); ?></th>
                    <th><?php echo $t->gettext('Script'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="uk-text-bold"><a href="https://github.com/CrossRef/rest-api-doc">Crossref</a></td>
                    <td><?php echo $resultCrossref["foundInSource"]; ?></td>
                    <td><?php echo $resultCrossref["notFoundInSource"]; ?></td></td>
                    <td><?php echo $resultCrossref["totalCollectedInSource"]; ?></td>
                    <td><?php echo number_format(($resultCrossref["totalCollectedInSource"] * 100) / $totalWithDOI, 2, '.', ''); ?>%</td>
                    <td><a href="../tools/collect_crossref.php">Script</a></td>
                </tr>
                <tr>
                    <td class="uk-text-bold"><a href="https://dimensions.figshare.com/articles/Dimensions_Metrics_API_Documentation/5783694">Dimensions</a></td>
                    <td><?php echo $resultDimensions["foundInSource"]; ?></td>
                    <td><?php echo $resultDimensions["notFoundInSource"]; ?></td>
                    <td><?php echo $resultDimensions["totalCollectedInSource"]; ?></td>
                    <td><?php echo number_format(($resultDimensions["totalCollectedInSource"] * 100) / $totalWithDOI, 2, '.', ''); ?>%</td>
                    <td><a href="../tools/collect_dimensions.php">Script</a></td>
                </tr>
                <tr>
                    <td class="uk-text-bold"><a href="https://unpaywall.org/products/api">Unpaywall</a></td>
                    <td><?php echo $resultUnpaywall["foundInSource"]; ?></td>
                    <td><?php echo $resultUnpaywall["notFoundInSource"]; ?></td>
                    <td><?php echo $resultUnpaywall["totalCollectedInSource"]; ?></td>
                    <td><?php echo number_format(($resultUnpaywall["totalCollectedInSource"] * 100) / $totalWithDOI, 2, '.', ''); ?>%</td>
                    <td><a href="../tools/collect_unpaywall.php">Script</a></td>
                </tr>                
            </tbody>
        </table>

        <hr class="uk-grid-divider">
        <h4>Ferramentas</h4>

        <p><a href="autoridades.php">Atualizar autoridades</a></p>

        <p><a href="translate_en.php">Atualizar tradução para o Inglês</a></p>




        <?php if(!empty($_SESSION['oauthuserdata'])) : ?>

            <?php if (in_array($_SESSION['oauthuserdata']->{'loginUsuario'}, $staffUsers)) : ?>

                
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-2-4@">
                        <p><a href="autoridades.php">Atualizar autoridades</a></p>
                    </div>
                    <div class="uk-width-2-4@m">
                        <p><a href="translate_en.php">Atualizar tradução para o Inglês</a></p>
                    </div>
                </div>
                <hr class="uk-grid-divider">

            <?php else: ?>

                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p>Você não tem privilegios para acessar esta área.</p>
                </div>            

            <?php endif; ?>

        <?php else: ?>

            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>Você não está logado. Clique em login para se logar</p>
            </div>

        <?php endif; ?>
        <hr class="uk-grid-divider">
        <?php require 'inc/footer.php'; ?>

        </div>



    </body>
</html>
