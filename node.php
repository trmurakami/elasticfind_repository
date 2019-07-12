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

    <?php
    require 'inc/meta-header.php';
    ?>   
    <title><?php echo $branch_abrev; ?> - Detalhe do registro: <?php echo $cursor["_source"]['name'];?></title>


    <?php
    /* DSpace */
    if (isset($dspaceRest)) {

        $actual_link = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (!empty($_SESSION["DSpaceCookies"])) {

            /* Search for existing record on DSpace */
            $itemID = DSpaceREST::searchItemDSpace($cursor["_id"], $_SESSION["DSpaceCookies"]); 

            if (!empty($itemID)) {

                $createFormDSpace = null;
                $bitstreamsDSpace = DSpaceREST::getBitstreamDSpace($itemID, $_SESSION["DSpaceCookies"]);

                /* Update Bitstreams on Elastic */
                $countBitstream = count($bitstreamsDSpace);               
                if (isset($cursor["_source"]["USP"]["fullTextFiles"])) {
                    $countBitstreamElastic = count($cursor["_source"]["USP"]["fullTextFiles"]);                   
                } else {
                    $countBitstreamElastic = 0;
                }
                if ($countBitstream != $countBitstreamElastic) {
                    $body["doc"]["USP"]["fullTextFiles"] = $bitstreamsDSpace;
                    $resultUpdateFilesElastic = Elasticsearch::update($_GET['_id'], $body);
                    sleep(5);
                    header("Refresh:0");                            
                } 

                if (isset($_FILES['file'])) {
                    $userBitstream = ''.$_POST["version"].'-'.$_POST["codpes"].'';
                    $resultAddBitstream = DSpaceREST::addBitstreamDSpace($itemID, $_FILES, $userBitstream, $_SESSION["DSpaceCookies"]);
                    sleep(5);
                    header("Refresh:0");
                }

                /* Manage policies of bitstream */

                if (isset($_POST['makePrivateBitstream'])) {
                    if (isset($_POST['embargoYear'])) {
                        $embargoStart = date("Y-m-d");
                        $embargoEnd = ''.$_POST['embargoYear'].'-'.$_POST['embargoMonth'].'-01';
                        /* Delete Annonymous Policy */
                        $resultDeleteBitstreamPolicyDSpace = DSpaceREST::deleteBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyID'], $_SESSION["DSpaceCookies"]);
                        /* Add Restricted Policy */
                        $resultAddBitstreamPolicyDSpace = DSpaceREST::addBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyAction'], $dspaceRestrictedID, $_POST['policyResourceType'], $_POST['policyRpType'], $_SESSION["DSpaceCookies"], $embargoStart, $embargoEnd);
                        /* Add Public Policy for Annonymous after Embargo end Date */
                        $resultAddBitstreamPolicyDSpace = DSpaceREST::addBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyAction'], $dspaceAnnonymousID, $_POST['policyResourceType'], $_POST['policyRpType'], $_SESSION["DSpaceCookies"], $embargoEnd, "");                                                
                    } else {
                        /* Delete Annonymous Policy */
                        $resultDeleteBitstreamPolicyDSpace = DSpaceREST::deleteBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyID'], $_SESSION["DSpaceCookies"]);
                        /* Add Restricted Policy */
                        $resultAddBitstreamPolicyDSpace = DSpaceREST::addBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyAction'], $dspaceRestrictedID, $_POST['policyResourceType'], $_POST['policyRpType'], $_SESSION["DSpaceCookies"]);
                    }
                }
                if (isset($_POST['makePublicBitstream'])) {
                    /* Delete Annonymous Policy */
                    $resultDeleteBitstreamPolicyDSpace = DSpaceREST::deleteBitstreamPolicyDSpace($_POST['makePublicBitstream'], $_POST['policyID'], $_SESSION["DSpaceCookies"]);
                    /* Add Public Policy */
                    $resultAddBitstreamPolicyDSpace = DSpaceREST::addBitstreamPolicyDSpace($_POST['makePublicBitstream'], $_POST['policyAction'], $dspaceAnnonymousID, $_POST['policyResourceType'], $_POST['policyRpType'], $_SESSION["DSpaceCookies"]);
                }

                /* Delete bitstream */
                if (isset($_POST['deleteBitstream'])) {
                    $resultDeleteBitstream = DSpaceREST::deleteBitstreamDSpace($_POST['deleteBitstream'], $_SESSION["DSpaceCookies"]);

                    echo '<div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p>Arquivo excluído com sucesso</p>
                    </div>';

                    sleep(5);
                    header("Refresh:0");
                }                

            } else {

                $createFormDSpace["alert"]  = '
                <div class="uk-alert-danger" uk-alert>
                <p>'.$t->gettext('Não possui itens digitais').'</p>
                </div>
                ';

                $createFormDSpace["form"]  = '
                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <h5>'.$t->gettext('Gestão do documento digital').'</h5>
                    <form action="'.$actual_link.'" method="post">
                        <input type="hidden" name="createRecord" value="true" />
                        <button class="uk-button uk-button-danger" name="btn_submit">'.$t->gettext('Criar registro no DSpace').'</button>
                    </form>                
                </div>
                ';

                /* Create record on DSpace */
                if (isset($_POST["createRecord"])) {
                    if ($_POST["createRecord"] == "true") {

                        $dataString = DSpaceREST::buildDC($cursor, $_GET['_id']);
                        $resultCreateItemDSpace = DSpaceREST::createItemDSpace($dataString, $dspaceCollection, $_SESSION["DSpaceCookies"]);

                        sleep(5);
                        header("Refresh:0");

                    }
                }                   
            }            
        }
    }
    ?>


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

            <?php 
            if (!empty($itemID)) {

                if (isset($sherpaAPIKEY)) {
                    if (isset($cursor["_source"]["isPartOf"]["issn"][0])) {
                        $sherpaResult = API::sherpaAPI($cursor["_source"]["isPartOf"]["issn"][0], $sherpaAPIKEY);

                        if ($sherpaResult["header"]["numhits"] == 1) {
                            echo '<div class="uk-alert-danger" uk-alert>
                            <h2><a href="http://sherpa.ac.uk/romeo/search.php?issn='.$cursor["_source"]["isPartOf"]["issn"][0].'">Sherpa / ROMEO</a></h2>
                            <a class="uk-alert-close" uk-close></a>
                            <p><a href="http://sherpa.ac.uk/romeo/search.php?issn='.$cursor["_source"]["isPartOf"]["issn"][0].'">Link para o Sherpa - ISSN: '.$cursor["_source"]["isPartOf"]["issn"][0].'</a></p>
                            <p>
                                <b>Título da publicação:</b> '.$sherpaResult["journals"]["journal"]["jtitle"].'<br/>
                                <b>ISSN:</b> '.$sherpaResult["journals"]["journal"]["issn"].'<br/>
                                <b>Imprenta:</b> '.$sherpaResult["journals"]["journal"]["zetocpub"].'<br/>
                            </p>
                            <p>
                                <b>Editora:</b> '.$sherpaResult["publishers"]["publisher"]["name"].'<br/>
                                <b>URL:</b> '.$sherpaResult["publishers"]["publisher"]["homeurl"].'<br/><br/>
                                <p>
                                    <b>Pré-prints:</b> '.$sherpaResult["publishers"]["publisher"]["preprints"]["prearchiving"].'<br/>
                                    <b>Pós-prints:</b> '.$sherpaResult["publishers"]["publisher"]["postprints"]["postarchiving"].'<br/>
                                    <b>Versão PDF:</b> '.$sherpaResult["publishers"]["publisher"]["pdfversion"]["pdfarchiving"].'<br/>
                                </p>
                                <p><b>Condições:</b><br/>';
                            foreach ($sherpaResult["publishers"]["publisher"]["conditions"]["condition"] as $sherpaConditions) {
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;$sherpaConditions<br/>";
                            }                                
                            echo '</p></p>
                            </div>';                                  
                        }
                    }
                }



                /* Create Upload Form */
                //if (isset($_SESSION['oauthuserdata'])) {
                    echo '
                    <div class="uk-alert-warning" uk-alert>
                        <form class="uk-form" action="'.$actual_link.'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <fieldset data-uk-margin>
                                <legend>Enviar um arquivo</legend>
                                <input type="file" name="file">
                                <select class="uk-select" name="version">
                                    <option disabled selected value>Selecione a versão</option>
                                    <option value="publishedVersion">Versão final publicada</option>
                                    <option value="submittedVersion">Versão enviada (Pré-print)</option>
                                    <option value="acceptedVersion">Versão aceita para publicação(Pós-print)</option>
                                    <option value="updatedVersion">Versão alterada</option>
                                </select>
                                <input type="text" name="codpes" value="3473118" hidden>
                                <button class="uk-button uk-button-primary" name="btn_submit">Upload</button>
                            </fieldset>
                        </form>
                    </div>
                    ';
                //} 

                if (!empty($bitstreamsDSpace)) {
                    
                    echo '<div class="uk-alert-primary" uk-alert>
                    <h4>Gerenciamento de políticas para o texto completo</h4>

                    <table class="uk-table uk-table-justify uk-table-divider">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nome do arquivo</th>
                            <th>Tipo de acesso</th>
                            <th>Link</th>
                            <th>Excluir</th>
                            <th>Privado/Público</th>
                            <th>Embargado até</th>
                        </tr>
                    </thead>
                    <tbody>';

                    foreach ($bitstreamsDSpace as $key => $value) {

                        $bitstreamPolicy = DSpaceREST::getBitstreamPolicyDSpace($value["uuid"], $_SESSION["DSpaceCookies"]);

                        foreach ($bitstreamPolicy as $bitstreamPolicyUnit) {
                            if ($bitstreamPolicyUnit["groupId"] == $dspaceAnnonymousID) {
                                echo '<tr>';
                                echo '<th><a href="http://'.$_SERVER["SERVER_NAME"].'/bitstreams/'.$value["uuid"].'" target="_blank" rel="noopener noreferrer nofollow"><img data-src="'.$url_base.'/inc/images/pdf.png" width="70" height="70" alt="" uk-img></a></th>';
                                echo '<th>'.$value["name"].'</th>';
                                echo '<th><img width="48" alt="Open Access logo PLoS white" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/25/Open_Access_logo_PLoS_white.svg/64px-Open_Access_logo_PLoS_white.svg.png"></th>';
                                echo '<th><a href="http://'.$_SERVER["SERVER_NAME"].'/directbitstream/'.$value["uuid"].'/'.$value["name"].'" target="_blank" rel="noopener noreferrer nofollow">Direct link</a></th>';
                                //if ($isOfThisUnit == true) {
                                    echo '<th><button class="uk-button uk-button-danger uk-margin-small-right" type="button" uk-toggle="target: #modal-deleteBitstream-'.$value["uuid"].'">Excluir</button></th>';
                                    echo '<div id="modal-deleteBitstream-'.$value["uuid"].'" uk-modal>';
                                    echo '<div class="uk-modal-dialog uk-modal-body">';
                                    echo '<h2 class="uk-modal-title">Excluir arquivo</h2>';
                                    echo '<p>Tem certeza que quer excluir o arquivo '.$value["name"].'?</p>';
                                    echo '<p class="uk-text-right">';
                                    echo '<button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>';
                                    echo '<form action="' . $actual_link . '" method="post">';
                                    echo '<input type="hidden" name="deleteBitstream" value="'.$value["uuid"].'" />';
                                    echo '<button class="uk-button uk-button-danger" name="btn_submit">Excluir</button>';
                                    echo '</form>';
                                    echo '</p>';
                                    echo '</div>';
                                    echo '</div>';


                                    echo '<th><button class="uk-button uk-button-secondary uk-margin-small-right" type="button" uk-toggle="target: #modal-Private-'.$value["uuid"].'">Tornar privado</button></th>';

                                    echo '<div id="modal-Private-'.$value["uuid"].'" uk-modal>
                                        <div class="uk-modal-dialog uk-modal-body">
                                            <h2 class="uk-modal-title">Tornar privado</h2>
                                            <p>Tem certeza que quer tornar privado o arquivo '.$value["name"].'?</p>
                                            <p class="uk-text-right">                                                
                                                <form action="' . $actual_link . '" method="post" class="uk-form-horizontal uk-margin-large">
                                                    <div class="uk-alert-danger" uk-alert>
                                                        <a class="uk-alert-close" uk-close></a>
                                                        <p>Caso o trabalho tenha embargo, favor informar a data de fim do embargo, senão, deixe em branco</p>
                                                        <div class="uk-margin">
                                                            <label class="uk-form-label" for="form-horizontal-text">Ano</label>
                                                            <div class="uk-form-controls">
                                                                <select class="uk-select" name="embargoYear">
                                                                    <option disabled selected value>Selecione o ano</option>
                                                                    <option value="2019">2019</option>
                                                                    <option value="2020">2020</option>
                                                                    <option value="2021">2021</option>
                                                                    <option value="2022">2022</option>
                                                                    <option value="2023">2023</option>
                                                                    <option value="2024">2024</option>
                                                                    <option value="2025">2025</option>
                                                                    <option value="2026">2026</option>
                                                                    <option value="2027">2027</option>
                                                                    <option value="2028">2028</option>
                                                                    <option value="2029">2029</option>
                                                                    <option value="2030">2030</option>
                                                                </select> 
                                                            </div>
                                                        </div>
                                                        <div class="uk-margin">
                                                            <label class="uk-form-label" for="form-horizontal-text">Mês (O trabalho estará disponível no ínicio do mês selecionado)</label>
                                                            <div class="uk-form-controls">
                                                                <select class="uk-select" name="embargoMonth">
                                                                    <option disabled selected value>Selecione a mês</option>
                                                                    <option value="01">Janeiro</option>
                                                                    <option value="02">Fevereiro</option>
                                                                    <option value="03">Março</option>
                                                                    <option value="04">Abril</option>
                                                                    <option value="05">Maio</option>
                                                                    <option value="06">Junho</option>
                                                                    <option value="07">Julho</option>
                                                                    <option value="08">Agosto</option>
                                                                    <option value="09">Setembro</option>
                                                                    <option value="10">Outubro</option>
                                                                    <option value="11">Novembro</option>
                                                                    <option value="12">Dezembro</option>
                                                                </select>
                                                            </div>
                                                        </div>                                                                                                                     
                                                    </div>
                                                    <input type="hidden" name="makePrivateBitstream" value="'.$value["uuid"].'" />
                                                    <input type="hidden" name="policyID" value="'.$bitstreamPolicyUnit["id"].'" />
                                                    <input type="hidden" name="policyAction" value="'.$bitstreamPolicyUnit["action"].'" />
                                                    <input type="hidden" name="policyGroupId" value="'.$bitstreamPolicyUnit["groupId"].'" />
                                                    <input type="hidden" name="policyResourceType" value="'.$bitstreamPolicyUnit["resourceType"].'" />
                                                    <input type="hidden" name="policyRpType" value="'.$bitstreamPolicyUnit["rpType"].'" />                                                    
                                                    <button class="uk-button uk-button-secondary" name="btn_submit">Tornar privado</button>
                                                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                                                </form>
                                            </p>
                                        </div>
                                    </div>';


                                //}
                                echo '<th></th>';

                            } elseif ($bitstreamPolicyUnit["groupId"] == $dspaceRestrictedID) {


                                //if (isset($_SESSION['oauthuserdata'])) {

                                    echo '<tr>';
                                    echo '<th><a href="http://'.$_SERVER["SERVER_NAME"].'/bitstreams/'.$value["uuid"].'" target="_blank" rel="noopener noreferrer nofollow"><img data-src="'.$url_base.'/inc/images/pdf.png" width="70" height="70" alt="" uk-img></a></th>';
                                    echo '<th>'.$value["name"].'</th>';
                                    echo '<th><img width="48" alt="Closed Access logo white" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Closed_Access_logo_white.svg/64px-Closed_Access_logo_white.svg.png"></th>';
                                    echo '<th><a href="http://'.$_SERVER["SERVER_NAME"].'/directbitstream/'.$value["uuid"].'/'.$value["name"].'" target="_blank" rel="noopener noreferrer nofollow">Direct link</a></th>';

                                    //if (in_array($_SESSION['oauthuserdata']->{'loginUsuario'}, $staffUsers)) {

                                        echo '<th><button class="uk-button uk-button-danger uk-margin-small-right" type="button" uk-toggle="target: #modal-deleteBitstream-'.$value["uuid"].'">Excluir</button></th>';

                                        echo '<div id="modal-deleteBitstream-'.$value["uuid"].'" uk-modal>
                                            <div class="uk-modal-dialog uk-modal-body">
                                                <h2 class="uk-modal-title">Excluir arquivo</h2>
                                                <p>Tem certeza que quer excluir o arquivo '.$value["name"].'?</p>
                                                <p class="uk-text-right">
                                                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                                                    <form action="' . $actual_link . '" method="post">
                                                        <input type="hidden" name="deleteBitstream" value="'.$value["uuid"].'" />
                                                        <button class="uk-button uk-button-danger" name="btn_submit">Excluir</button>
                                                    </form>
                                                </p>
                                            </div>
                                        </div>';

                                        echo '<th><button class="uk-button uk-button-primary uk-margin-small-right" type="button" uk-toggle="target: #modal-Public-'.$value["uuid"].'">Tornar público</button></th>';

                                        echo '<div id="modal-Public-'.$value["uuid"].'" uk-modal>
                                            <div class="uk-modal-dialog uk-modal-body">
                                                <h2 class="uk-modal-title">Tornar público</h2>
                                                <p>Tem certeza que quer tornar público o arquivo '.$value["name"].'?</p>
                                                <p class="uk-text-right">
                                                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                                                    <form action="' . $actual_link . '" method="post">
                                                        <input type="hidden" name="makePublicBitstream" value="'.$value["uuid"].'" />
                                                        <input type="hidden" name="policyID" value="'.$bitstreamPolicyUnit["id"].'" />
                                                        <input type="hidden" name="policyAction" value="'.$bitstreamPolicyUnit["action"].'" />
                                                        <input type="hidden" name="policyGroupId" value="'.$bitstreamPolicyUnit["groupId"].'" />
                                                        <input type="hidden" name="policyResourceType" value="'.$bitstreamPolicyUnit["resourceType"].'" />
                                                        <input type="hidden" name="policyRpType" value="'.$bitstreamPolicyUnit["rpType"].'" />
                                                        <button class="uk-button uk-button-primary" name="btn_submit">Tornar público</button>
                                                    </form>
                                                </p>
                                            </div>
                                        </div>';

                                        echo '<th></th>';
                                    //}
                                }

                            //} else {

                            //}

                        }

                    }
                    echo '</tbody></table></div>';
                }                


            }                        
            ?>

                <div class="uk-grid" data-ukgrid>
                    <div class="uk-width-3-4@m">

                        <?php
                        $record = new Record($cursor, $show_metrics);
                        $record->completeRecordMetadata($t, $url_base, $createFormDSpace);
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