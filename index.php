<!doctype html>
<html lang="en">
<head>

<?php
require 'inc/config.php';
require 'inc/functions.php';
require 'inc/meta-header.php';
?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $branch; ?></title>

    <link rel="canonical" href="">

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

    <div class="jumbotron" style="background-image: url(<?php echo $background_1; ?>); background-size: 100%;">
        <div class="container">
        <h1 class="display-5 mt-5 mb-3"><?php echo $branch; ?></h1>
        <p><?php echo $branch_description; ?></p>

        <form action="result.php">
            <div class="form-group">
                <label for="searchQuery"><?php echo $t->gettext('Termos de busca'); ?></label>
                <input type="text" name="search" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="<?php echo $t->gettext('Pesquise por termo ou autor'); ?>">
                <small id="searchHelp" class="form-text text-muted"><?php echo $t->gettext('Dica: Use * para busca por radical. Ex: biblio*.'); ?></small>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><?php echo $t->gettext('Pesquisar'); ?></button>
        </form>


        </div>
    </div>

  <div class="container">
    <!-- Example row of columns -->
    <div class="row">
    
      <div class="col-md-4">
        <h2>E-books de editoras universitárias</h2>
        <p>Esta base foi baseada no documento: E-books de editoras universitárias, elaborado pelo BU/UFSC em parceria com o SiBI/UFRJ. E disponibilizado no Google Docs.</p>
        <p><a class="btn btn-secondary" href="https://docs.google.com/spreadsheets/d/1HVsq2WU-_uvqPPOwoQgZIPB9Mbh4eFkDOHxQO4Dp6fE/edit#gid=0" role="button" target="_blank">Acessar o link no Google Docs &raquo;</a></p>
      </div>
      <!--
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      -->
    
      <div class="col-md-4">
        <h2>Bases</h2>
        <ul class="list-group">
            <?php Homepage::fieldAgg("base");?>
        </ul>
      </div>
    </div>

    <hr>
    <h3>Últimos registros</h3>
    <br/>
    <?php Homepage::getLastRecords("_id");?>

  </div>

</main>

<!-- FOOTER -->
<?php require 'inc/footer.php'; ?>
<!-- /FOOTER -->
</html>