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
            $query["query"]["query_string"]["query"] = "*";
            $paramsTotal = [];
            $paramsTotal["index"] = $index;
            $paramsTotal["body"] = $query; 
            $cursorTotal = $client->count($paramsTotal);
            $totalRecords = $cursorTotal["count"];            

            /* Get number of records with doi */
            $query["query"]["query_string"]["query"] = "+_exists_:doi";
            $params = [];
            $params["index"] = $index;
            $params["body"] = $query; 
            $cursor = $client->count($params);
            $totalWithDOI = $cursor["count"];

            /* Get Stats */

            $resultCrossref = AdminStats::source("crossref");
            $resultDimensions = AdminStats::source("dimensions");  
            $resultUnpaywall = AdminStats::source("unpaywall");       
            
            
            $resultAff = AdminStats::field("author.person.affiliation.tematres");
            $resultFunder = AdminStats::field("funder.tematres");

        ?>
        <title><?php echo $branch_abrev ?> - <?php echo $t->gettext('Administração'); ?></title>
    </head>

    <body>
        <?php require 'inc/navbar.php'; ?>
        <br/><br/><br/><br/>
        <main role="main" class="container">

            <h3><?php echo $t->gettext('Área de administração e gerenciamento'); ?></h3>

            <h4><?php echo $t->gettext('Estatísticas de coleta de fontes externas'); ?></h4>

        <div class="alert alert-warning" role="alert">
            <p><?php echo $t->gettext('Total de registros'); ?>: <b><?php echo $totalRecords; ?></b></p>
            <p><?php echo $t->gettext('Total de registros com DOI'); ?>: <b><?php echo $totalWithDOI; ?></b></p>
        </div>

        <table class="table">
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
                    <td><?php echo $resultCrossref["notFoundInSource"]; ?></td>
                    <td><?php echo $resultCrossref["totalCollectedInSource"]; ?></td>
                    <td><?php echo number_format(($resultCrossref["totalCollectedInSource"] * 100) / $totalWithDOI, 2, '.', ''); ?>%</td>
                    <td><a target="_blank" rel="noopener noreferrer" href="../tools/collect_crossref.php">Script</a></td>
                </tr>
                <tr>
                    <td class="uk-text-bold"><a href="https://dimensions.figshare.com/articles/Dimensions_Metrics_API_Documentation/5783694">Dimensions</a></td>
                    <td><?php echo $resultDimensions["foundInSource"]; ?></td>
                    <td><?php echo $resultDimensions["notFoundInSource"]; ?></td>
                    <td><?php echo $resultDimensions["totalCollectedInSource"]; ?></td>
                    <td><?php echo number_format(($resultDimensions["totalCollectedInSource"] * 100) / $totalWithDOI, 2, '.', ''); ?>%</td>
                    <td><a target="_blank" rel="noopener noreferrer" href="../tools/collect_dimensions.php">Script</a></td>
                </tr>
                <tr>
                    <td class="uk-text-bold"><a href="https://unpaywall.org/products/api">Unpaywall</a></td>
                    <td><?php echo $resultUnpaywall["foundInSource"]; ?></td>
                    <td><?php echo $resultUnpaywall["notFoundInSource"]; ?></td>
                    <td><?php echo $resultUnpaywall["totalCollectedInSource"]; ?></td>
                    <td><?php echo number_format(($resultUnpaywall["totalCollectedInSource"] * 100) / $totalWithDOI, 2, '.', ''); ?>%</td>
                    <td><a target="_blank" rel="noopener noreferrer" href="../tools/collect_unpaywall.php">Script</a></td>
                </tr>                
            </tbody>
        </table>

        <hr>
        <h4>Correções de metadados</h4>

        <table class="table">
            <caption><?php echo $t->gettext('Correções de metadados'); ?></caption>
            <thead>
                <tr>
                    <th><?php echo $t->gettext('Fonte'); ?></th>
                    <th><?php echo $t->gettext('Corrigidos'); ?></th>
                    <th><?php echo $t->gettext('Não corrigidos'); ?></th>
                    <th><?php echo $t->gettext('Total de ocorrências'); ?></th>
                    <th><?php echo $t->gettext('Percentual corrigido'); ?></th>
                    <th><?php echo $t->gettext('Script'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="uk-text-bold"><a href="">Instituições externas</a></td>
                    <td><?php echo $resultAff["correct"]; ?></td>
                    <td><?php echo $resultAff["notCorrect"]; ?></td>
                    <td><?php echo $resultAff["totalOccorrences"]; ?></td>
                    <td><?php echo number_format(($resultAff["correct"] * 100) / $resultAff["totalOccorrences"], 2, '.', ''); ?>%</td>
                    <td><a target="_blank" rel="noopener noreferrer" href='../tools/tematres.php?field=author.person.affiliation'>Script</a></td>
                </tr>
                <tr>
                    <td class="uk-text-bold"><a href="">Agências de fomento</a></td>
                    <td><?php echo $resultFunder["correct"]; ?></td>
                    <td><?php echo $resultFunder["notCorrect"]; ?></td>
                    <td><?php echo $resultFunder["totalOccorrences"]; ?></td>
                    <td><?php echo number_format(($resultFunder["correct"] * 100) / $resultFunder["totalOccorrences"], 2, '.', ''); ?>%</td>
                    <td><a target="_blank" rel="noopener noreferrer" href='../tools/tematres.php?field=funder'>Script</a></td>
                </tr>                               
            </tbody>
        </table>

        <hr>
        <h4>DSpace</h4>
            <?php
            if (strpos($_SESSION["DSpaceCookies"], 'JSESSIONID') !== false) {
                echo '
                <div class="alert alert-success" role="alert">
                    <p>Integração com o DSpace está funcionando.</p>
                </div>            
                ';
            } else {
                echo '
                <div class="alert alert-danger" role="alert">
                    <p>Integração com o DSpace NÃO está funcionando.</p>
                </div>            
                ';
            }
            $resultFullTextFilesQuery = AdminStats::fullTextFiles();

            ?>

            <table class="table">
                <caption><?php echo $t->gettext('Registros com texto completo disponível'); ?></caption>
                <thead>
                    <tr>
                        <th><?php echo $t->gettext('Total de registros'); ?></th>
                        <th><?php echo $t->gettext('Com texto completo'); ?></th>
                        <th><?php echo $t->gettext('Percentual'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a target="_blank" rel="noopener noreferrer" href="<?php echo "$url_base/"?>result.php?search[]=(-_exists_:USP.fullTextFiles)"><?php echo $totalRecords; ?></a></td>
                        <td><a target="_blank" rel="noopener noreferrer" href="<?php echo "$url_base/"?>result.php?search[]=(_exists_:USP.fullTextFiles)"><?php echo $resultFullTextFilesQuery["hits"]["total"]["value"]; ?></a></td>
                        <td><?php echo number_format(($resultFullTextFilesQuery["hits"]["total"]["value"] * 100) / $totalRecords, 2, '.', ''); ?>%</td>
                    </tr>            
                </tbody>
            </table>   



        <hr class="uk-grid-divider">
        <h4>Ferramentas</h4>

        <p><a href="translate_en.php">Atualizar tradução para o Inglês - Converte o arquivo messages.po para o en.php</a></p>




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
