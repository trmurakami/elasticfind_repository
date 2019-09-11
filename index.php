<!doctype html>
<html lang="en">
<head>

<?php
require 'inc/config.php';
require 'inc/meta-header.php';
?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $branch; ?></title>

    <link rel="canonical" href="https://bdpi.usp.br">

    <!-- Bootstrap core CSS -->
    <link href="/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <style>
        .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        }

        @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">
</head>
<body>

<!-- NAV -->
<?php require 'inc/navbar.php'; ?>
<!-- /NAV --> 

<main role="main">

    <div class="jumbotron">
        <div class="container">
        <h1 class="display-5"><?php echo $branch; ?></h1>
        <p><?php echo $branch_description; ?></p>

        <form action="result.php">
            <div class="form-group">
                <label for="searchQuery"><?php echo $t->gettext('Termos de busca'); ?></label>
                <input type="text" name="search[]" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="<?php echo $t->gettext('Pesquise por termo ou autor'); ?>">
                <small id="searchHelp" class="form-text text-muted"><?php echo $t->gettext('Dica: Use * para busca por radical. Ex: biblio*.'); ?></small>
            </div>

            <div class="form-group">
                <label for="selectBase"><?php echo $t->gettext('Filtre sua busca por base'); ?></label>
                <select class="form-control" id="selectBase" name="filter[]">
                    <option disabled selected value><?php echo $t->gettext('Todas as bases'); ?></option>
                    <option value="base:&quot;Produção científica&quot;" style="color:#333"><?php echo $t->gettext('Produção Científica'); ?></option>
                    <option value="base:&quot;Teses e dissertações&quot;" style="color:#333"><?php echo $t->gettext('Teses e Dissertações'); ?></option>
                </select>
            </div> 

            <div class="form-group">
                <label for="selectUnidadeUSP"><?php echo $t->gettext('Filtre sua busca por base'); ?></label>
                <select class="form-control" id="selectUnidadeUSP" name="filter[]">
                    <option disabled selected value><?php echo $t->gettext('Todas as Unidades USP'); ?></option>
                    <option value="unidadeUSP:&quot;EACH&quot;" style="color:#333"><?php echo $t->gettext('Escola de Artes, Ciências e Humanidades (EACH)'); ?></option>
                    <option value="unidadeUSP:&quot;ECA&quot;" style="color:#333"><?php echo $t->gettext('Escola de Comunicações e Artes (ECA)'); ?></option>
                    <option value="unidadeUSP:&quot;EE&quot;" style="color:#333"><?php echo $t->gettext('Escola de Enfermagem (EE)'); ?></option>
                    <option value="unidadeUSP:&quot;EERP&quot;" style="color:#333"><?php echo $t->gettext('Escola de Enfermagem de Ribeirão Preto (EERP)'); ?></option>
                    <option value="unidadeUSP:&quot;EEFE&quot;" style="color:#333"><?php echo $t->gettext('Escola de Educação Física e Esporte (EEFE)'); ?></option>
                    <option value="unidadeUSP:&quot;EEFERP&quot;" style="color:#333"><?php echo $t->gettext('Escola de Educação Física e Esporte de Ribeirão Preto (EEFERP)'); ?></option>
                    <option value="unidadeUSP:&quot;EEL&quot;" style="color:#333"><?php echo $t->gettext('Escola de Engenharia de Lorena (EEL)'); ?></option>
                    <option value="unidadeUSP:&quot;EESC&quot;" style="color:#333"><?php echo $t->gettext('Escola de Engenharia de São Carlos (EESC)'); ?></option>
                    <option value="unidadeUSP:&quot;EP&quot;" style="color:#333"><?php echo $t->gettext('Escola Politécnica (EP)'); ?></option>
                    <option value="unidadeUSP:&quot;ESALQ&quot;" style="color:#333"><?php echo $t->gettext('Escola Superior de Agricultura “Luiz de Queiroz” (ESALQ)'); ?></option>
                    <option value="unidadeUSP:&quot;FAU&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Arquitetura e Urbanismo (FAU)'); ?></option>
                    <option value="unidadeUSP:&quot;FCF&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Ciências Farmacêuticas (FCF)'); ?></option>
                    <option value="unidadeUSP:&quot;FCFRP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Ciências Farmacêuticas de Ribeirão Preto (FCFRP)'); ?></option>
                    <option value="unidadeUSP:&quot;FD&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Direito (FD)'); ?></option>
                    <option value="unidadeUSP:&quot;FDRP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Direito de Ribeirão Preto (FDRP)'); ?></option>
                    <option value="unidadeUSP:&quot;FEA&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Economia, Administração e Contabilidade (FEA)'); ?></option>
                    <option value="unidadeUSP:&quot;FEARP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Economia, Administração e Contabilidade de Ribeirão Preto (FEARP)'); ?></option>
                    <option value="unidadeUSP:&quot;FE&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Educação (FE)'); ?></option>
                    <option value="unidadeUSP:&quot;FFCLRP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Filosofia, Ciências e Letras de Ribeirão Preto (FFCLRP)'); ?></option>
                    <option value="unidadeUSP:&quot;FFLCH&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Filosofia, Letras e Ciências Humanas (FFLCH)'); ?></option>
                    <option value="unidadeUSP:&quot;FM&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Medicina (FM)'); ?></option>
                    <option value="unidadeUSP:&quot;FMRP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Medicina de Ribeirão Preto (FMRP)'); ?></option>
                    <option value="unidadeUSP:&quot;FMVZ&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Medicina Veterinária e Zootecnia (FMVZ)'); ?></option>
                    <option value="unidadeUSP:&quot;FO&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Odontologia (FO)'); ?></option>
                    <option value="unidadeUSP:&quot;FOB&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Odontologia de Bauru (FOB)'); ?></option>
                    <option value="unidadeUSP:&quot;FORP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Odontologia de Ribeirão Preto (FORP)'); ?></option>
                    <option value="unidadeUSP:&quot;FSP&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Saúde Pública (FSP)'); ?></option>
                    <option value="unidadeUSP:&quot;FZEA&quot;" style="color:#333"><?php echo $t->gettext('Faculdade de Zootecnia e Engenharia de Alimentos (FZEA)'); ?></option>
                    <option value="unidadeUSP:&quot;IAU&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Arquitetura e Urbanismo (IAU)'); ?></option>
                    <option value="unidadeUSP:&quot;IAG&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Astronomia, Geofísica e Ciências Atmosféricas (IAG)'); ?></option>
                    <option value="unidadeUSP:&quot;IB&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Biociências (IB)'); ?></option>
                    <option value="unidadeUSP:&quot;ICB&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Ciências Biomédicas (ICB)'); ?></option>
                    <option value="unidadeUSP:&quot;ICMC&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Ciências Matemáticas e de Computação (ICMC)'); ?></option>
                    <option value="unidadeUSP:&quot;IF&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Física (IF)'); ?></option>
                    <option value="unidadeUSP:&quot;IFSC&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Física de São Carlos (IFSC)'); ?></option>
                    <option value="unidadeUSP:&quot;IGC&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Geociências (IGc)'); ?></option>
                    <option value="unidadeUSP:&quot;IME&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Matemática e Estatística (IME)'); ?></option>
                    <option value="unidadeUSP:&quot;IMT&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Medicina Tropical de São Paulo (IMT)'); ?></option>
                    <option value="unidadeUSP:&quot;IP&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Psicologia (IP)'); ?></option>
                    <option value="unidadeUSP:&quot;IQ&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Química (IQ)'); ?></option>
                    <option value="unidadeUSP:&quot;IQSC&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Química de São Carlos (IQSC)'); ?></option>
                    <option value="unidadeUSP:&quot;IRI&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Relações Internacionais (IRI)'); ?></option>
                    <option value="unidadeUSP:&quot;IO&quot;" style="color:#333"><?php echo $t->gettext('Instituto Oceanográfico (IO)'); ?></option>
                    <option disabled value><?php echo $t->gettext('Centros, Hospitais, Institutos especializados e Museus'); ?></option>
                    <option value="unidadeUSP:&quot;CEBIMAR&quot;" style="color:#333"><?php echo $t->gettext('Centro de Biologia Marinha (CEBIMAR)'); ?></option>
                    <option value="unidadeUSP:&quot;CDCC&quot;" style="color:#333"><?php echo $t->gettext('Centro de Divulgação Científica e Cultural (CDCC)'); ?></option>
                    <option value="unidadeUSP:&quot;CENA&quot;" style="color:#333"><?php echo $t->gettext('Centro de Energia Nuclear na Agricultura (CENA)'); ?></option>
                    <option value="unidadeUSP:&quot;HRAC&quot;" style="color:#333"><?php echo $t->gettext('Hospital de Reabilitação de Anomalias Craniofaciais (HRAC)'); ?></option>
                    <option value="unidadeUSP:&quot;HU&quot;" style="color:#333"><?php echo $t->gettext('Hospital Universitário (HU)'); ?></option>
                    <option value="unidadeUSP:&quot;IEE&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Energia e Ambiente (IEE)'); ?></option>
                    <option value="unidadeUSP:&quot;IEB&quot;" style="color:#333"><?php echo $t->gettext('Instituto de Estudos Brasileiros (IEB)'); ?></option>
                    <option value="unidadeUSP:&quot;MAE&quot;" style="color:#333"><?php echo $t->gettext('Museu de Arqueologia e Etnologia (MAE)'); ?></option>
                    <option value="unidadeUSP:&quot;MAC&quot;" style="color:#333"><?php echo $t->gettext('Museu de Arte Contemporânea (MAC)'); ?></option>
                    <option value="unidadeUSP:&quot;MZ&quot;" style="color:#333"><?php echo $t->gettext('Museu de Zoologia (MZ)'); ?></option>
                    <option value="unidadeUSP:&quot;MP&quot;" style="color:#333"><?php echo $t->gettext('Museu Paulista (MP)'); ?></option>
                </select>
            </div>                         
            <button type="submit" class="btn btn-primary"><?php echo $t->gettext('Pesquisar'); ?></button>
        </form>


        </div>
    </div>

  <div class="container">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
    </div>

    <hr>

    <?php Homepage::getLastRecords();?>

  </div>

</main>

<!-- FOOTER -->
<?php require 'inc/footer.php'; ?>
<!-- /FOOTER -->
</html>