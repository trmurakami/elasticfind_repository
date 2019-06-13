<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php
    require 'inc/config.php';
    //require 'inc/meta-header.php';
?>
<head>
    
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $branch; ?></title>
    <link rel="stylesheet" href="advanced-bootstrap/dist/foundation.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <script src="https://use.fontawesome.com/01ac7e2496.js"></script>

    <style>
        .docs-section-margin {
            margin-top: 3rem;
            margin-bottom: 3rem;
        }

    </style>
</head>
<body>

<div class="grid-container">

<div class="row">
    <div class="large-12 columns">
        <div class="top-bar">
            <div class="row column">
                <div class="top-bar-left">
                    <ul class="dropdown menu" data-dropdown-menu>
                        <li class="menu-text">BDPI USP |
                            <small>Biblioteca Digital da Produção Intelectual da Universidade de São Paulo</small>
                        </li>
                    </ul>
                </div>
                <div class="top-bar-right">
                    <ul class="dropdown menu" data-dropdown-menu>
                        <li>
                            <a href="#"><?php echo $t->gettext('Busca avançada'); ?></a>
                            <ul class="menu vertical" >
                                <li><a href="#"><?php echo $t->gettext('One'); ?></a></li>
                                <li><a href="#"><?php echo $t->gettext('Two'); ?></a></li>
                                <li><a href="#"><?php echo $t->gettext('Three'); ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><?php echo $t->gettext('Institucional'); ?></a>
                            <ul class="menu vertical" >
                                <li><a href="#"><?php echo $t->gettext('Contato'); ?></a></li>
                                <li><a href="#"><?php echo $t->gettext('Sobre'); ?></a></li>
                                <li><a href="#"><?php echo $t->gettext('Login'); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="#">English</a></li>                        
                        <li><a href="#">Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>     
</div>

<div class="docs-section-margin">&nbsp;</div>

<div class="row">
    <div class="large-12 medium-12 columns">
        <div class="large-8 columns">
            <h1 class="subheader"><span class="highlighted">Biblioteca Digital da Produção Intelectual</span></h1>
            <h4 class="subheader">Universidade de São Paulo</h4>
            <form>
                <div class="row">
                    <div class="large-12 columns">
                        <label>&nbsp;</label>
                        <input type="text" placeholder="Termos de busca"/>
                    </div>
                </div>
            </form>
            <a class="button">Pesquisar</a><a class="button white">Cancelar</a>            
        </div>
        <div class="large-4 columns">
            <h3>Explore <a data-open="code-vert-menu" class=""><small> <i class="fa fa-code ">&nbsp;</i></small></a></h3>
            <ul class="menu vertical" style="max-width: 360px">
                <li><a href="#"><i class="fa fa-home"></i>&nbsp; Unidades USP</a></li>
                <li><a href="#"><i class="fa fa-book"></i>&nbsp; Autores</a></li>
                <li><a href="#"><i class="fa fa-pencil"></i>&nbsp; Títulos</a></li>
                <li><a href="#"><i class="fa fa-cog"></i>&nbsp; Assuntos</a></li>
                <li><a href="#"><i class="fa fa-comments-o"></i>&nbsp; Acesso Aberto na USP</a></li>
            </ul>
        </div>    
    </div>
</div>

<hr class="docs-section-margin">

<div class="row column">
    <ul class="vertical medium-horizontal menu expanded text-center">
        <li><a href="#"><div class="stat">793.616</div><span>Produção científica</span></a></li>
        <li><a href="#"><div class="stat">161.715</div><span>Teses e dissertações</span></a></li>
        <li><a href="#"><div class="stat">48</div><span>Unidades USP</span></a></li>
    </ul>
</div>

<hr class="docs-section-margin">


<div class="row">
    <div class="large-12 medium-12 columns">
    <h3>Navegar por Unidade <a data-open="code-dlist" class=""><small> <i class="fa fa-code ">&nbsp;</i></small></a></h3>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>
    <div class="large-1 columns"><img class="thumbnail" src="https://placehold.it/60x60"></div>

    </div> 
</div>

<hr class="docs-section-margin">

<div class="row">
    <div class="large-12 medium-12 columns">
        <h3>Últimos registros <a data-open="code-dlist" class=""><small> <i class="fa fa-code ">&nbsp;</i></small></a></h3>
        <dl>
            <dt>Grupos que pesquisam sobre formação de professores e educação a distância no Brasil: um retrato (2019)</dt>
            <dd>Nascimento, Jean Lopes Ordéas | Kenski, Vani Moreira</dd>
            <dt>Facetas estéticas em compósito APS (Advanced Polymerization System): relato de caso (2018)</dt>
            <dd>Santin, D. C. | Scotti, C. K. | Velo, M. M. A. C. | Bombonatti, Juliana Fraga Soares | Mondelli, Rafael Francisco Lia</dd>
        </dl>
    </div>
</div>



<script src="advanced-bootstrap/dist/foundation.js"></script>
<script>
    jQuery(document).ready(function () {
        jQuery(document).foundation();
    });
</script>
</body>
</html>