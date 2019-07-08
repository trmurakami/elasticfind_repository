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

                echo "<br/><br/>";
                print_r($itemID);
                echo "<br/><br/>";
                $createFormDSpace = null;
                $bitstreamsDSpace = DSpaceREST::getBitstreamDSpace($itemID, $_SESSION["DSpaceCookies"]);

                echo "<br/>Bitstream no Elastic:<br/>";
                print_r($cursor["_source"]["USP"]["fullTextFiles"]);
                echo "<br/><br/>";

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





        // $actual_link = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



        // /* Verify if item exists on DSpace */
        // if (!empty($itemID)) {

        //     function removeElementWithValue($array, $key, $value)
        //     {
        //         foreach ($array as $subKey => $subArray) {
        //             if ($subArray[$key] == $value) {
        //                 unset($array[$subKey]);
        //             }
        //         }
        //         return $array;
        //     }

        //     if (isset($_SESSION['oauthuserdata'])) {
        //         $uploadForm = '<form class="uk-form" action="'.$actual_link.'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        //                 <fieldset data-uk-margin>
        //                 <legend>Enviar um arquivo</legend>
        //                 <input type="file" name="file">
        //                 <select class="uk-select" name="version">
        //                     <option disabled selected value>Selecione a versão</option>
        //                     <option value="publishedVersion">publishedVersion</option>
        //                     <option value="submittedVersion">submittedVersion</option>
        //                     <option value="acceptedVersion">acceptedVersion</option>
        //                     <option value="updatedVersion">updatedVersion</option>
        //                 </select>
        //                 <input type="text" name="codpes" value="'.$_SESSION['oauthuserdata']->{'loginUsuario'}.'" hidden>
        //                 <button class="uk-button uk-button-primary" name="btn_submit">Upload</button>
        //             </fieldset>
        //             </form>';
        //     }


        //     if (isset($_POST['deleteBitstream'])) {
        //         $resultDeleteBitstream = DSpaceREST::deleteBitstreamDSpace($_POST['deleteBitstream'], $_SESSION["DSpaceCookies"]);
        //         if (isset($cursor["_source"]["USP"]["fullTextFiles"])) {
        //             $body["doc"]["USP"]["fullTextFiles"] = $cursor["_source"]["USP"]["fullTextFiles"];
        //             $body["doc"]["USP"]["fullTextFiles"] = removeElementWithValue($body["doc"]["USP"]["fullTextFiles"], "uuid", $_POST['deleteBitstream']);
        //             //$body["doc"]["USP"]["fullTextFiles"] = [];
        //             $resultUpdateFilesElastic = elasticsearch::elastic_update($_GET['_id'], $type, $body);
        //             print_r($resultUpdateFilesElastic);
        //         }

        //         echo '<div class="uk-alert-danger" uk-alert>
        //         <a class="uk-alert-close" uk-close></a>
        //         <p>Arquivo excluído com sucesso</p>
        //         </div>';

        //         echo "<script type='text/javascript'>
        //         $(document).ready(function(){
        //                 //Reload the page
        //                 window.location = window.location.href;
        //         });
        //         </script>";


        //     }

        //     if (isset($_POST['makePrivateBitstream'])) {

        //         /* Delete Annonymous Policy */
        //         $resultDeleteBitstreamPolicyDSpace = DSpaceREST::deleteBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyID'], $_SESSION["DSpaceCookies"]);
        //         /* Add Restricted Policy */
        //         $resultAddBitstreamPolicyDSpace = DSpaceREST::addBitstreamPolicyDSpace($_POST['makePrivateBitstream'], $_POST['policyAction'], $dspaceRestrictedID, $_POST['policyResourceType'], $_POST['policyRpType'], $_SESSION["DSpaceCookies"]);

        //     }

        //     if (isset($_POST['makePublicBitstream'])) {

        //         /* Delete Annonymous Policy */
        //         $resultDeleteBitstreamPolicyDSpace = DSpaceREST::deleteBitstreamPolicyDSpace($_POST['makePublicBitstream'], $_POST['policyID'], $_SESSION["DSpaceCookies"]);
        //         /* Add Public Policy */
        //         $resultAddBitstreamPolicyDSpace = DSpaceREST::addBitstreamPolicyDSpace($_POST['makePublicBitstream'], $_POST['policyAction'], $dspaceAnnonymousID, $_POST['policyResourceType'], $_POST['policyRpType'], $_SESSION["DSpaceCookies"]);

        //     }

        //     

        // } else {



        // }

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

                /* Create Upload Form */
                //if (isset($_SESSION['oauthuserdata'])) {
                    $uploadForm = '
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
                
                echo $uploadForm;

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