<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        require 'inc/config.php';
        require 'inc/meta-header.php';
        ?>    

        <title>Biblioteca Digital de Produção Intelectual da Universidade de São Paulo</title>

        <link rel="stylesheet" type="text/css" href="https://zzseba78.github.io/Kick-Off/css/marketing.css">

    </head>
    <body>
        <!-- TOP -->
        <div class="top-wrap uk-position-relative uk-light uk-background-secondary">

            <!-- NAV -->
            <?php require 'inc/navbar.php'; ?>
            <!-- /NAV -->

            <div class="uk-cover-container uk-light uk-flex uk-flex-middle top-wrap-height">

            <!-- TOP CONTAINER -->
            <div class="uk-container uk-flex-auto top-container uk-position-relative uk-margin-medium-top" data-uk-parallax="y: 0,50; easing:0; opacity:0.2">
                <div class="uk-width-1-2@s" data-uk-scrollspy="cls: uk-animation-slide-right-medium; target: > *; delay: 150">
                    <h6 class="uk-text-primary uk-margin-small-bottom">Universidade de São Paulo</h6>
                    <h1 class="uk-margin-remove-top">Biblioteca Digital da Produção Intelectual</h1>
                    <p class="subtitle-text uk-visible@s">Memória documental da produção científica, técnica e artística gerada nas Unidades da Universidade de São Paulo.</p>
                    <a href="#search" title="Explore" class="uk-button uk-button-primary uk-border-pill" data-uk-scrollspy-class="uk-animation-fade">Explore</a>
                </div>
            </div>
            <!-- /TOP CONTAINER -->
            <!-- TOP IMAGE -->
            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
            data-srcset="https://bdpi.usp.br/inc/images/cover/Cientificamente_Oficina-CSI_020-16_foto-Cec%C3%ADlia-Bastos-37.jpg 640w,
            https://bdpi.usp.br/inc/images/cover/Cientificamente_Oficina-CSI_020-16_foto-Cec%C3%ADlia-Bastos-37.jpg 960w,
            https://bdpi.usp.br/inc/images/cover/Cientificamente_Oficina-CSI_020-16_foto-Cec%C3%ADlia-Bastos-37.jpg 1200w,
            https://bdpi.usp.br/inc/images/cover/Cientificamente_Oficina-CSI_020-16_foto-Cec%C3%ADlia-Bastos-37.jpg 2000w"
            data-sizes="100vw"
            data-src="https://bdpi.usp.br/inc/images/cover/Cientificamente_Oficina-CSI_020-16_foto-Cec%C3%ADlia-Bastos-37.jpg" 
            alt="" data-uk-cover data-uk-img data-uk-parallax="opacity: 1,0.1; easing:0"
            >
            <!-- /TOP IMAGE -->
            </div>
            <div class="uk-position-bottom-center uk-position-medium uk-position-z-index uk-text-center">
                <a href="#search" data-uk-scroll="duration: 500" data-uk-icon="icon: arrow-down; ratio: 2"></a>
            </div>
        </div>
        <!-- /TOP -->
        <section id="search" class="uk-section uk-section-secondary uk-section-large">
            <div class="uk-container">
                <div class="uk-grid uk-child-width-1-2@l uk-flex-middle">
                    <div>
                        <h6>PESQUISA</h6>
                        <h2 class="uk-margin-small-top uk-h1">Faça uma busca:</h2>


                        <form class="uk-form-stacked" action="result.php">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="form-stacked-text"><?php echo $t->gettext('Termos de busca'); ?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="form-stacked-text" type="text" placeholder="<?php echo $t->gettext('Pesquise por termo ou autor'); ?>" name="search[]" data-validation="required">
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="form-stacked-select"><?php echo $t->gettext('Filtre sua busca por base'); ?></label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="form-stacked-select" name="filter[]">
                                        <option disabled selected value><?php echo $t->gettext('Todas as bases'); ?></option>
                                        <option value="base:&quot;Produção científica&quot;" style="color:#333"><?php echo $t->gettext('Produção Científica'); ?></option>
                                        <option value="base:&quot;Teses e dissertações&quot;" style="color:#333"><?php echo $t->gettext('Teses e Dissertações'); ?></option>
                                    </select>
                                </div>
                            </div>
                             <div class="uk-margin">
                                <label class="uk-form-label" for="form-stacked-select"><?php echo $t->gettext('Filtre sua busca por Unidade USP'); ?></label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="form-stacked-select" name="filter[]">
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
                             </div>
                             <div class="uk-child-width-1-2 uk-text-center" uk-grid>
                                <div><button class="uk-button uk-button-primary"><?php echo $t->gettext('Buscar'); ?></button></div>
                                <div><button class="uk-button uk-button-danger" type="reset" value="Reset"><?php echo $t->gettext('Limpar formulário'); ?></button></div>
                            </div>
                        </form>

                    </div>
                    <div data-uk-scrollspy="cls: uk-animation-fade">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-2.svg" data-uk-img alt="Image">
                    </div>
                </div>
            </div>     
        </section> 
        <section id="lastRecords" class="uk-section uk-section-default"> 
            <div class="uk-container">
                <h1 class="uk-heading-line uk-text-center"><span><?php echo $t->gettext('Últimos registros'); ?></span></h1>
                <?php Homepage::getLastRecords();?>
            </div>
        </section>

<!--
        <section class="uk-section uk-section-secondary uk-section-large">
            <div class="uk-container">
                <div class="uk-grid uk-child-width-1-2@l uk-flex-middle">
                    <div>
                        <h6>SIMPLIFY THINGS</h6>
                        <h2 class="uk-margin-small-top uk-h1">Manage all your data from one place only.</h2>
                        <p class="subtitle-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua.
                        </p>
                        <a href="#" class="uk-button uk-button-primary uk-border-pill" data-uk-icon="arrow-right">LEARN MORE</a>
                    </div>
                    <div data-uk-scrollspy="cls: uk-animation-fade">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/marketing-2.svg" data-uk-img alt="Image">
                    </div>
                </div>
            </div>
        </section>            

        <section id="content" class="uk-section uk-section-default">
          
            <div class="uk-container">
                <div class="uk-section uk-section-small uk-padding-remove-top">
                    <ul class="uk-subnav uk-subnav-pill uk-flex uk-flex-center" data-uk-switcher="connect: .uk-switcher; animation: uk-animation-fade">
                        <li><a class="uk-border-pill" href="#">Discover</a></li>
                        <li><a class="uk-border-pill" href="#">Benefits</a></li>
                        <li><a class="uk-border-pill" href="#">Features</a></li>
                    </ul>
                </div>

            <ul class="uk-switcher uk-margin">
                <li>
                    <div class="uk-grid uk-child-width-1-2@l uk-flex-middle" data-uk-grid data-uk-scrollspy="target: > div; cls: uk-animation-slide-left-medium">
                        <div>
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/marketing-1.svg" alt="" data-uk-img>
                        </div>
                        <div data-uk-scrollspy-class="uk-animation-slide-right-medium">
                            <h6 class="uk-text-primary">MAIN REASONS</h6>
                            <h2 class="uk-margin-small-top">Take decisions with real time data based on users interaction.</h2>
                            <p class="subtitle-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation.
                            </p>
                            <div class="uk-grid uk-child-width-1-2@s" data-uk-grid>
                                <div>
                                    <h4>Great stuff</h4>
                                    <p>Ut enim ad minim veniam, quis nostrud magna aliqua exercitation. <a href="">Learn more.</a></p>
                                </div>
                                <div>
                                    <h4>Data analysis</h4>
                                    <p>Ut enim ad minim veniam, quis nostrud magna aliqua exercitation. <a href="">Learn more.</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="uk-grid uk-child-width-1-2@l uk-flex-middle" data-uk-grid data-uk-scrollspy="target: > div; cls: uk-animation-slide-left-medium">
                        <div>
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/marketing-8.svg" alt="" data-uk-img>
                        </div>
                        <div data-uk-scrollspy-class="uk-animation-slide-right-medium">
                            <h6 class="uk-text-primary">MAIN REASONS</h6>
                            <h2 class="uk-margin-small-top">Take decisions with real time data based on users interaction.</h2>
                            <p class="subtitle-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation.
                            </p>
                            <div class="uk-grid uk-child-width-1-2@s" data-uk-grid>
                                <div>
                                    <h4>Great stuff</h4>
                                    <p>Ut enim ad minim veniam, quis nostrud magna aliqua exercitation. <a href="">Learn more.</a></p>
                                </div>
                                <div>
                                    <h4>Data analysis</h4>
                                    <p>Ut enim ad minim veniam, quis nostrud magna aliqua exercitation. <a href="">Learn more.</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="uk-grid uk-child-width-1-2@l uk-flex-middle" data-uk-grid data-uk-scrollspy="target: > div; cls: uk-animation-slide-left-medium">
                        <div>
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/marketing-9.svg" alt="" data-uk-img>
                        </div>
                        <div data-uk-scrollspy-class="uk-animation-slide-right-medium">
                            <h6 class="uk-text-primary">MAIN REASONS</h6>
                            <h2 class="uk-margin-small-top">Take decisions with real time data based on users interaction.</h2>
                            <p class="subtitle-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation.
                            </p>
                            <div class="uk-grid uk-child-width-1-2@s" data-uk-grid>
                                <div>
                                    <h4>Great stuff</h4>
                                    <p>Ut enim ad minim veniam, quis nostrud magna aliqua exercitation. <a href="">Learn more.</a></p>
                                </div>
                                <div>
                                    <h4>Data analysis</h4>
                                    <p>Ut enim ad minim veniam, quis nostrud magna aliqua exercitation. <a href="">Learn more.</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
                
                
            </div>
        </section>

        <section class="uk-cover-container overlay-wrap">
            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="https://picsum.photos/640/650/?image=770 640w,
            https://picsum.photos/960/650/?image=770 960w,
            https://picsum.photos/1200/650/?image=770 1200w,
            https://picsum.photos/2000/650/?image=770 2000w"
            data-sizes="100vw"
            data-src="https://picsum.photos/1200/650/?image=770" alt="" data-uk-cover data-uk-img
            >
            <div class="uk-container uk-position-z-index uk-position-relative uk-section uk-section-xlarge uk-light">
                <div class="uk-grid uk-flex-right">
                    
                    <div class="uk-width-2-5@m" data-uk-parallax="y: 50,-50; easing: 0; media:@l">
                        <h6>TESTIMONIALS</h6>
                        <div class="uk-position-relative uk-visible-toggle uk-light" data-uk-slider="easing: cubic-bezier(.16,.75,.47,1)">
                            <ul class="uk-slider-items uk-child-width-1-1">
                                <li>
                                    <div data-uk-slider-parallax="opacity: 0.2,1,0.2">
                                        <h2 class="uk-margin-small-top">"Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur"</h2>
                                        <p class="uk-text-meta">Lorena Smith, founder of Some Cool Startup</p>
                                    </div>
                                </li>
                                <li>
                                    <div data-uk-slider-parallax="opacity: 0.2,1,0.2">
                                        <h2 class="uk-margin-small-top">"Aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur"</h2>
                                        <p class="uk-text-meta">Lorena Smith, founder of Some Cool Startup</p>
                                    </div>
                                </li>
                                <li>
                                    <div data-uk-slider-parallax="opacity: 0.2,1,0.2">
                                        <h2 class="uk-margin-small-top">"Irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur"</h2>
                                        <p class="uk-text-meta">Lorena Smith, founder of Some Cool Startup</p>
                                    </div>
                                </li>
                            </ul>
                            <ul class="uk-slider-nav uk-dotnav uk-margin-top"><li></li></ul>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>       

        <section class="uk-section uk-section-default">
            
            <div class="uk-container uk-container-xsmall uk-text-center uk-section uk-padding-remove-top">
                <h5 class="uk-text-primary">ANALYTICS</h5>
                <h2 class="uk-margin-remove uk-h1">Know the behavior of your potential customers.</h2>
            </div>
            <div class="uk-container">
                <div class="uk-grid uk-grid-large uk-child-width-1-3@m" data-uk-grid data-uk-scrollspy="target: > div; delay: 150; cls: uk-animation-slide-bottom-medium">
                    <div class="uk-text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-3.svg" data-uk-img alt="Image">
                        <h4 class="uk-margin-small-bottom uk-margin-top uk-margin-remove-adjacent">Lorem ipsum dolor sit amet</h4>
                        <p>24/7 support. We’re always here for you no matter what time of day.</p>
                    </div>
                    <div class="uk-text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-4.svg" data-uk-img alt="Image">
                        <h4 class="uk-margin-small-bottom uk-margin-top uk-margin-remove-adjacent">Lorem ipsum dolor sit amet</h4>
                        <p>24/7 support. We’re always here for you no matter what time of day.</p>
                    </div>
                    <div class="uk-text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-5.svg" data-uk-img alt="Image">
                        <h4 class="uk-margin-small-bottom uk-margin-top uk-margin-remove-adjacent">Lorem ipsum dolor sit amet</h4>
                        <p>24/7 support. We’re always here for you no matter what time of day.</p>
                    </div>
                    <div class="uk-text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-5.svg" data-uk-img alt="Image">
                        <h4 class="uk-margin-small-bottom uk-margin-top uk-margin-remove-adjacent">Lorem ipsum dolor sit amet</h4>
                        <p>24/7 support. We’re always here for you no matter what time of day.</p>
                    </div>
                    <div class="uk-text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-4.svg" data-uk-img alt="Image">
                        <h4 class="uk-margin-small-bottom uk-margin-top uk-margin-remove-adjacent">Lorem ipsum dolor sit amet</h4>
                        <p>24/7 support. We’re always here for you no matter what time of day.</p>
                    </div>
                    <div class="uk-text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                        data-src="https://zzseba78.github.io/Kick-Off/img/marketing-3.svg" data-uk-img alt="Image">
                        <h4 class="uk-margin-small-bottom uk-margin-top uk-margin-remove-adjacent">Lorem ipsum dolor sit amet</h4>
                        <p>24/7 support. We’re always here for you no matter what time of day.</p>
                    </div>
                    
                </div>
            </div>
        </section>
        <div class="uk-section uk-section-small uk-section-muted">
            <div class="uk-container uk-container-small">
                <div class="uk-grid uk-child-width-1-4 uk-child-width-expand@m logos-grid" data-uk-grid data-uk-scrollspy="cls: uk-animation-scale-down; target: > div > img; delay: 100">
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="inc/images/logosusp/CDCC.jpg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="inc/images/logosusp/CEBIMAR.jpg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="inc/images/logosusp/CENA.jpg" data-uk-img alt="Image">
                    </div>                                           
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="inc/images/logosusp/ECA.jpg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="inc/images/logosusp/IF.jpg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/logo-3.svg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/logo-6.svg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/logo-7.svg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/logo-8.svg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/logo-4.svg" data-uk-img alt="Image">
                    </div>
                    <div>
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://zzseba78.github.io/Kick-Off/img/logo-5.svg" data-uk-img alt="Image">
                    </div>
                </div>
            </div>
        </div>
--> 
        <!-- FOOTER -->
        <footer class="uk-section uk-section-secondary uk-padding-remove-bottom">
            <div class="uk-container">
                <div class="uk-grid uk-grid-large" data-uk-grid>
                    <div class="uk-width-1-2@m">
                        <h5>Sistema Integrado de Bibliotecas</h5>
                        <p>
                            Rua da Praça do Relógio, 109 - Bloco L  Térreo - Cidade Universitária, São Paulo, SP<br/>
                            Tel: (0xx11) 3091-4195 e 3091-1547 | Fax: (0xx11) 3091-1567
                        </p>
                        <div>
                            <a href="https://twitter.com/SIBiUSP" class="uk-icon-button" data-uk-icon="twitter"></a>
                            <a href="https://www.facebook.com/sibiusp" class="uk-icon-button" data-uk-icon="facebook"></a>
                            <a href="https://github.com/SIBiUSP" class="uk-icon-button" data-uk-icon="github"></a>
                        </div>
                    </div>
                    <div class="uk-width-1-6@m">
                        <h5>PRODUCTS</h5>
                        <ul class="uk-list">
                            <li>Big Data</li>
                            <li>Marketing</li>
                            <li>Analytics</li>
                            <li>AI Lab</li>
                        </ul>
                    </div>
                    <div class="uk-width-1-6@m">
                        <h5>OUR COMPANY</h5>
                        <ul class="uk-list">
                            <li>Team</li>
                            <li>Work</li>
                            <li>Culture</li>
                            <li>Contact Us</li>
                        </ul>
                    </div>
                    <div class="uk-width-1-6@m">
                        <h5>OUR OFFICES</h5>
                        <ul class="uk-list">
                            <li>London</li>
                            <li>Chicago</li>
                            <li>Dubai</li>
                            <li>Brussels</li>
                        </ul>
                    </div>
                    
                </div>
            </div>
            
            <div class="uk-text-center uk-padding uk-padding-remove-horizontal">
                <span class="uk-text-small uk-text-muted">© 2019 Marketing Layout - <a href="https://github.com/zzseba78/Kick-Off">Created by KickOff</a> | Built with <a href="http://getuikit.com" title="Visit UIkit 3 site" target="_blank" data-uk-tooltip><span data-uk-icon="uikit"></span></a></span>
            </div>
        </footer>
        <!-- /FOOTER -->
        <!-- OFFCANVAS -->
        <div id="offcanvas-nav" data-uk-offcanvas="flip: true; overlay: false">
            <div class="uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide">
                <button class="uk-offcanvas-close uk-close uk-icon" type="button" data-uk-close></button>
                <ul class="uk-nav uk-nav-default">
                    <li class="uk-active"><a href="#">Active</a></li>
                    <li class="uk-parent">
                        <a href="#">Parent</a>
                        <ul class="uk-nav-sub">
                            <li><a href="#">Sub item</a></li>
                            <li><a href="#">Sub item</a></li>
                        </ul>
                    </li>
                    <li class="uk-nav-header">Header</li>
                    <li><a href="#js-options"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: table"></span> Item</a></li>
                    <li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: thumbnails"></span> Item</a></li>
                    <li class="uk-nav-divider"></li>
                    <li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: trash"></span> Item</a></li>
                </ul>
                <h3>Title</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
        </div>
        <!-- /OFFCANVAS -->        

    </body>
</html>
