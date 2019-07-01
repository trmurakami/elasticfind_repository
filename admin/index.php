<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
    <head>
        <?php
            getcwd();
            chdir('../');
            // Include essencial files
            require 'inc/config.php'; 
            require 'inc/meta-header.php';
        ?>
        <title>BDPI USP - Administração</title>
    </head>

    <body>
        <?php require 'inc/navbar.php'; ?>
        <div class="uk-container uk-margin-large-top">

        <h3>Área de administração e gerenciamento</h3>


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

            <?php endif; ?>

        <?php else: ?>
            <br/><br/><br/>
            <p>Você não está logado</p>
            <br/><br/><br/>
        <?php endif; ?>
        <hr class="uk-grid-divider">
        <?php require 'inc/footer.php'; ?>

        </div>



    </body>
</html>
