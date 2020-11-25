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
        <h1 class="display-5 mt-5 mb-3"><?php echo $branch; ?></h1>
        <p><?php echo $branch_description; ?></p>

        <form action="result.php">
            <div class="form-group">
                <label for="searchQuery"><?php echo $t->gettext('Termos de busca'); ?></label>
                <input type="text" name="search" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="<?php echo $t->gettext('Pesquise por termo ou autor'); ?>">
                <small id="searchHelp" class="form-text text-muted"><?php echo $t->gettext('Dica: Use * para busca por radical. Ex: biblio*.'); ?></small>
            </div>

            <div class="form-group">
                <label for="selectBase"><?php echo $t->gettext('Filtre sua busca por base'); ?></label>
                <select class="form-control" id="selectBase" name="filter[]">
                    <option disabled selected value><?php echo $t->gettext('Todas as bases'); ?></option>
                    <option value="base:&quot;Livros&quot;" style="color:#333"><?php echo $t->gettext('Livros'); ?></option>
                    <option value="base:&quot;Partituras&quot;" style="color:#333"><?php echo $t->gettext('Partituras'); ?></option>
                    <option value="base:&quot;Produção científica&quot;" style="color:#333"><?php echo $t->gettext('Produção Científica'); ?></option>
                    <option value="base:&quot;Teses e dissertações&quot;" style="color:#333"><?php echo $t->gettext('Teses e Dissertações'); ?></option>
                    <option value="base:&quot;Trabalhos acadêmicos&quot;" style="color:#333"><?php echo $t->gettext('Trabalhos acadêmicos'); ?></option>
                </select>
            </div>                         
            <button type="submit" class="btn btn-primary mt-3"><?php echo $t->gettext('Pesquisar'); ?></button>
        </form>


        </div>
    </div>

  <div class="container">
    <!-- Example row of columns -->
    <div class="row">
    <!--
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
    -->
      <div class="col-md-4">
        <h2>Bases</h2>
        <ul class="list-group">
            <?php Homepage::fieldAgg("base");?>
        </ul>
      </div>
    </div>

    <hr>

    <?php Homepage::getLastRecords("_id");?>

  </div>

</main>

<!-- FOOTER -->
<?php require 'inc/footer.php'; ?>
<!-- /FOOTER -->
</html>