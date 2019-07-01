<?php
/**
 * PHP version 7
 * File: Functions
 *
 * @category Functions
 * @package  Functions
 * @author   Tiago Rodrigo Marçal Murakami <tiago.murakami@dt.sibi.usp.br>
 */
if (file_exists('uspfind_core/uspfind_core.php')) {
    include 'uspfind_core/uspfind_core.php';
} elseif (file_exists('../uspfind_core/uspfind_core.php')) {
    include '../uspfind_core/uspfind_core.php';
} else {
    include '../../uspfind_core/uspfind_core.php';
}

use Seboettg\CiteProc\CiteProc;


/**
 * Record
 *
 * @category Class
 * @package  Record
 * @author   Tiago Rodrigo Marçal Murakami <tiago.murakami@dt.sibi.usp.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://github.com/sibiusp/nav_elastic
 */
class Record
{

    public function __construct($record, $showMetrics)
    {
        $this->id = $record["_id"];
        $this->name = $record["_source"]["name"];
        $this->base = $record["_source"]["base"][0];
        $this->type = ucfirst(strtolower($record["_source"]["type"]));
        $this->originalType = ucfirst(strtolower($record["_source"]["original"]["type"]));
        if (isset($record["_source"]["datePublished"])) {
            $this->datePublished = $record["_source"]["datePublished"];
        }
        if (isset($record["_source"]["dateCreated"])) {
            $this->dateCreated = $record["_source"]["dateCreated"];
        }
        $this->languageArray = $record["_source"]["language"];
        if (isset($record["_source"]["country"])) {
            $this->countryArray = $record["_source"]["country"];
        }
        $this->authorArray = $record["_source"]["author"];
        if (isset($record["_source"]["description"])) {
            $this->descriptionArray = $record["_source"]["description"];
        }
        if (isset($record["_source"]["numberOfPages"])) {
            $this->numberOfPages = $record["_source"]["numberOfPages"];
        }
        if (isset($record["_source"]["publisher"])) {
            $this->publisherArray = $record["_source"]["publisher"];
        }
        if (isset($record["_source"]["isPartOf"])) {
            $this->isPartOfArray = $record["_source"]["isPartOf"];
        }
        if (isset($record["_source"]["releasedEvent"])) {
            $this->releasedEvent = $record["_source"]["releasedEvent"];
        }
        if (isset($record["_source"]["about"])) {
            $this->aboutArray = $record["_source"]["about"];
        }
        if (isset($record["_source"]["USP"]["about_BDTD"])) {
            $this->aboutBDTDArray = $record["_source"]["USP"]["about_BDTD"];
        } else {
            $this->aboutBDTDArray = 0;
        }
        if (isset($record["_source"]['funder'])) {
            $this->funderArray = $record["_source"]['funder'];
        } else {
            $this->funderArray = 0;
        }
        if (isset($record["_source"]["USP"]["crossref"]["message"]["funder"])) {
            $this->funderCrossrefArray = $record["_source"]["USP"]["crossref"]["message"]["funder"];
        } else {
            $this->funderCrossrefArray = 0;
        }
        if (isset($record["_source"]["USP"]["crossref"]["message"])) {
            $this->crossrefArray = $record["_source"]["USP"]["crossref"]["message"];
        } else {
            $this->crossrefArray = 0;
        }
        if (isset($record["_source"]["USP"])) {
            $this->USPArray = $record["_source"]["USP"];
        }
        if (isset($record["_source"]["authorUSP"])) {
            $this->authorUSPArray = $record["_source"]["authorUSP"];
        }
        if (isset($record["_source"]["unidadeUSP"])) {
            $this->unidadeUSPArray = $record["_source"]["unidadeUSP"];
        }
        if (isset($record["_source"]["USP"]["programa_pos_sigla"])) {
            $this->programa_pos_sigla = $record["_source"]["USP"]["programa_pos_sigla"];
        }        
        if (isset($record["_source"]["isbn"])) {
            $this->isbn = $record["_source"]["isbn"];
        }
        if (isset($record["_source"]["url"])) {
            $this->url = $record["_source"]["url"];
        }
        if (isset($record["_source"]["doi"])) {
            $this->doi = $record["_source"]["doi"];
        }
        if (isset($record["_source"]["USP"]["titleSearchCrossrefDOI"])) {
            $this->searchDOICrossRef = $record["_source"]["USP"]["titleSearchCrossrefDOI"];
        }
        if (isset($record["_source"]["award"])) {
            $this->awardArray = $record["_source"]["award"];
        }
        if (isset($record["_source"]["issn"])) {
            $this->issnArray = $record["_source"]["issn"];
        } else {
            $this->issnArray[] = "Não informado";
        }
        $this->completeRecord = $record;
        $this->showMetrics = $showMetrics;
    }

    public function simpleRecordMetadata($t)
    {
        echo '<li>';
        echo '<div class="uk-grid-divider uk-padding-small" uk-grid>';
        echo '<div class="uk-width-1-5@m">';
        echo '<p><a href="http://'.$_SERVER['SERVER_NAME'].''.$_SERVER['SCRIPT_NAME'].''.$_SERVER['QUERY_STRING'].'&filter[]=type:&quot;'.$this->type.'&quot;">'.$this->type.'</a></p>';
        echo '<p>Unidades USP: ';
        if (!empty($this->unidadeUSPArray)) {
            $unique =  array_unique($this->unidadeUSPArray);
            foreach ($unique as $unidadeUSP) {
                echo '<a href="result.php?filter[]=unidadeUSP:&quot;'.$unidadeUSP.'&quot;">'.$unidadeUSP.' </a>';
            }
        }
        echo '</p>';
        if (!empty($this->isbn)) {
            $cover_link = 'https://covers.openlibrary.org/b/isbn/'.$this->isbn.'-M.jpg';
            echo  '<p><img src="'.$cover_link.'"></p>';
        }
        echo '</div>';
        echo '<div class="uk-width-4-5@m">';
        echo '<article class="uk-article">';
        echo '<p class="uk-text-lead uk-margin-remove" style="font-size:115%"><a class="uk-link-reset" href="item/'.$this->id.'">'.$this->name.' ('.$this->datePublished.')</a></p>';

        /* Authors */
        echo '<p class="uk-article-meta uk-margin-remove">'.$t->gettext('Autores').': ';
        foreach ($this->authorArray as $authors) {
            if (!empty($authors["person"]["orcid"])) {
                $orcidLink = ' <a href="'.$authors["person"]["orcid"].'"><img src="https://orcid.org/sites/default/files/images/orcid_16x16.png"></a>';
            } else {
                $orcidLink = '';
            }
            if (!empty($authors["person"]["potentialAction"])) {
                $authors_array[]='<a href="result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].' ('.$authors["person"]["potentialAction"].')</a>'.$orcidLink.'';
            } else {
                $authors_array[]='<a href="result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].'</a>'.$orcidLink.'';
            }
            unset($orcidLink);
        }
        $array_aut = implode("; ", $authors_array);
        print_r($array_aut);
        echo '</p>';

        /* IsPartOf */
        if (!empty($this->isPartOfArray["name"])) {
            echo '<p class="uk-text-small uk-margin-remove">In: <a href="result.php?filter[]=isPartOf.name:&quot;'.$this->isPartOfArray["name"].'&quot;">'.$this->isPartOfArray["name"].'</a></p>';
        }

        /*  releasedEvent */
        if (!empty($this->releasedEvent)) {
            echo '<p class="uk-text-small uk-margin-remove">'.$t->gettext('Nome do evento').': <a href="result.php?filter[]=releasedEvent:&quot;'.$this->releasedEvent.'&quot;">'.$this->releasedEvent.'</a></p>';
        }

        /* Subjects */
        if (!empty($this->aboutArray)) {
            echo '<p class="uk-text-small uk-margin-remove">'.$t->gettext('Assuntos').': ';
            foreach ($this->aboutArray as $subject) {
                echo '<a href="result.php?filter[]=about:&quot;'.$subject.'&quot;">'.$subject.'</a> ';
            }
        }

        if (!empty($this->url)||!empty($this->doi)) {
            $this->onlineAccess($t);
        }
        
        /* Implementar AJAX */
        //if ($this->showMetrics == true) {
        //    if (!empty($this->doi)) {
        //        $this->metrics($t, $this->doi, $this->completeRecord);
        //    }
        //}

        echo '</article>';

        echo '</div>';
        echo '</div>';

        echo '</li>';
        flush();
        ob_flush();

    }

    public function completeRecordMetadata($t,$url_base)
    {
        echo '<article class="uk-article">';
        echo '<p class="uk-article-meta">';
        echo '<a href="<?php echo $url_base ?>/result.php?filter[]=type:&quot;'.$this->type.'&quot;">'.$this->type.'</a> | <a href="<?php echo $url_base ?>/result.php?filter[]=type:&quot;'.$this->originalType.'&quot;">'.$this->originalType.'</a>';
        echo '</p>';
        echo '<h1 class="uk-article-title uk-margin-remove-top uk-link-reset" style="font-size:150%">'.$this->name.' ('.$this->datePublished.')</h1>';
        echo '<ul class="uk-list uk-list-striped uk-text-small">';
        /* Authors */
        foreach ($this->authorArray as $authors) {
            if (!empty($authors["person"]["orcid"])) {
                $orcidLink = ' <a href="'.$authors["person"]["orcid"].'"><img src="https://orcid.org/sites/default/files/images/orcid_16x16.png"></a>';
            } else {
                $orcidLink = '';
            }
            if (!empty($authors["person"]["affiliation"]["name"])) {
                $authorsList[] =  '<li><a href="'.$url_base.'/result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].' - <span class="uk-text-muted">'.$authors["person"]["affiliation"]["name"].'</span></a>'.$orcidLink.'</li>';
            } elseif (!empty($authors["person"]["potentialAction"])) {
                $authorsList[] = '<li><a href="'.$url_base.'/result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].' <span class="uk-text-muted">('.$authors["person"]["potentialAction"].')</span></a>'.$orcidLink.'</li>';
            } else {
                $authorsList[] = '<li><a href="'.$url_base.'/result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].'</a>'.$orcidLink.'</li>';
            }
            unset($orcidLink);
        }
        echo '<li>'.$t->gettext('Autores').': <ul>'.implode("", $authorsList).'</ul></li>';
        /* USP Authors */
        if (!empty($this->authorUSPArray)) {
            foreach ($this->authorUSPArray as $autoresUSP) {
                $authorsUSPList[] = '<a href="'.$url_base.'/result.php?filter[]=authorUSP.name:&quot;'.$autoresUSP["name"].'&quot;">'.$autoresUSP["name"].' - '.$autoresUSP["unidadeUSP"].' </a>';
            }
            echo '<li>'.$t->gettext('Autores USP').': '.implode("; ", $authorsUSPList).'</li>';
        }
        /* USP Units */
        if (!empty($this->unidadeUSPArray)) {
            foreach ($this->unidadeUSPArray as $unidadeUSP) {
                $unidadeUSPList[] = '<a href="'.$url_base.'/result.php?filter[]=unidadeUSP:&quot;'.$unidadeUSP.'&quot;">'.$unidadeUSP.'</a>';
            }
            echo '<li>'.$t->gettext('Unidades USP').': '.implode("; ", $unidadeUSPList).'</li>';
        }

        /* Programa Sigla Pós */
        if (!empty($this->programa_pos_sigla)) {
            $programa_pos_sigla = $this->programa_pos_sigla;
            $programa_pos_siglaList[] = '<a href="'.$url_base.'/result.php?filter[]=programa_pos_sigla:&quot;'.$programa_pos_sigla.'&quot;">'.$programa_pos_sigla.'</a>';
            echo '<li>'.$t->gettext('Sigla do Departamento').': '.implode("; ", $programa_pos_siglaList).'</li>';
        }        

        /* DOI */
        if (!empty($this->doi)) {
            echo '<li>DOI: <a href="https://doi.org/'.$this->doi.'" target="_blank" rel="noopener noreferrer">'.$this->doi.'</a></li>';
        }

        /* DOI */
        if (isset($_SESSION['oauthuserdata'])) {
            if (!empty($this->searchDOICrossRef)) {
                echo '<div class="uk-alert-danger" uk-alert><li>DOI com base em busca na CrossRef: <a href="https://doi.org/'.$this->searchDOICrossRef.'" target="_blank" rel="noopener noreferrer">'.$this->searchDOICrossRef.'</a></li></div>';
            }
        }



        /* Subject */
        if (isset($this->aboutArray)) {
            foreach ($this->aboutArray as $subject) {
                $subjectList[] = '<a href="'.$url_base.'/result.php?filter[]=about:&quot;'.$subject.'&quot;">'.$subject.'</a>';
            }
            echo '<li>'.$t->gettext('Assuntos').': '.implode("; ", $subjectList).'</li>';
        }

        /* BDTD Subject */
        if ($this->aboutBDTDArray > 0) {
            foreach ($this->aboutBDTDArray as $subject_BDTD) {
                $subjectBDTDList[] = '<a href="'.$url_base.'/result.php?filter[]=USP.about_BDTD:&quot;'.$subject_BDTD.'&quot;">'.$subject_BDTD.'</a>';
            }
            echo '<li>'.$t->gettext('Palavras-chave do autor').': '.implode("; ", $subjectBDTDList).'</li>';
        }

        /* Funder */
        if ($this->funderArray > 0) {
            echo '<li>'.$t->gettext('Agências de fomento').': ';
            echo '<ul class="uk-list uk-text-small">';
            foreach ($this->funderArray as $funder) {
                echo '<li><a href="'.$url_base.'/result.php?filter[]=funder:&quot;'.$funder["name"].'&quot;">'.$funder["name"].'</a>';
                if (!empty($funder["projectNumber"]) && $funder["name"] == "Fundação de Amparo à Pesquisa do Estado de São Paulo (FAPESP)") {
                    foreach ($funder["projectNumber"] as $projectNumber) {
                        $projectNumber = str_replace(" ", "", $projectNumber);
                        preg_match("/\d\d\/\d{5}-\d/", $projectNumber, $projectNumberMatchArray);
                        echo '<br/>Processo FAPESP: <a href="http://bv.fapesp.br/pt/processo/'.$projectNumberMatchArray[0].'" target="_blank" rel="noopener noreferrer">'.$projectNumber.'</a>';
                    }
                }
                echo '</li>';
            }
            echo '</ul></li>';
        }

        /* Funder - Crossref */
        if (isset($_SESSION['oauthuserdata'])) {
            if ($this->funderCrossrefArray > 0) {
                echo '<div class="uk-alert-danger" uk-alert><p class="uk-text-small uk-margin-remove">'.$t->gettext('Agências de fomento coletadas na CrossRef').': ';
                echo '<ul class="uk-list uk-text-small">';
                foreach ($this->funderCrossrefArray as $funder) {
                    echo '<li>';
                    echo 'Agência de fomento: '.$funder["name"].'</a><br/>';
                    if (isset($funder["award"])) {
                        foreach ($funder["award"] as $projectNumber) {
                            echo 'Projeto: '.$projectNumber.'</a><br/>';
                        }
                    }
                    echo "ALEPHSEQ - 536:<br/>";
                    if (isset($funder["award"])) {
                        foreach ($funder["award"] as $projectNumber) {
                            $projectNumberArray[] = '$$f'.$projectNumber.'';
                        }
                        echo '<p><b>$$a'.$funder["name"].''.implode("", $projectNumberArray).'</b></p>';
                        $projectNumberArray = [];

                    } else {

                        echo '<p><b>$$a'.$funder["name"].'</b></p>';
                    }
                    echo '</li>';
                }
                echo '</ul></p></div>';
            }
        }

        /* Language */
        foreach ($this->languageArray as $language) {
            $languageList[] = '<a href="'.$url_base.'/result.php?filter[]=language:&quot;'.$language.'&quot;">'.$language.'</a>';
        }
        echo '<li>'.$t->gettext('Idioma').': '.implode("; ", $languageList).'</li>';

        /* Abstract */
        if (!empty($this->descriptionArray)) {
            echo '<li class="uk-text-justify">'.$t->gettext('Resumo').': ';
            foreach ($this->descriptionArray as $resumo) {
                echo $resumo;
            }
            echo '</li>';
        }

        /* Imprint */
        if (!empty($this->publisherArray)) {
            echo '<li>'.$t->gettext('Imprenta').':';
            echo '<ul>';
                if (!empty($this->publisherArray["organization"]["name"])) {
                    echo '<li>'.$t->gettext('Editora').': <a href="'.$url_base.'/result.php?filter[]=publisher.organization.name:&quot;'.$this->publisherArray["organization"]["name"].'&quot;">'.$this->publisherArray["organization"]["name"].'</a></li>';
                }
                if (!empty($this->publisherArray["organization"]["location"])) {
                    echo '<li>'.$t->gettext('Local').': <a href="'.$url_base.'/result.php?filter[]=publisher.organization.location:&quot;'.$this->publisherArray["organization"]["location"].'&quot;">'.$this->publisherArray["organization"]["location"].'</a></li>';
                }
                if (!empty($this->datePublished)) {
                    echo '<li>'.$t->gettext('Data de publicação').': <a href="'.$url_base.'/result.php?filter[]=datePublished:&quot;'.$this->datePublished.'&quot;">'.$this->datePublished.'</a></li>';
                }
            echo '</ul></li>';
        }
        if (isset($_SESSION['oauthuserdata'])) {
            if ($this->crossrefArray > 0) {
                echo '<li class="uk-alert-danger">'.$t->gettext('Informações sobre o periódico coletadas na CrossRef').': ';
                echo '<ul class="uk-list uk-text-small">';
                if (!empty($this->crossrefArray["container-title"])) {
                    echo '<li>Título do periódico: '.$this->crossrefArray["container-title"][0].'</li>';
                }
                if (!empty($this->crossrefArray["issn-type"])) {
                    echo '<li>ISSN:<br/>';
                    foreach ($this->crossrefArray["issn-type"] as $crossrefISSN) {
                        echo ''.$crossrefISSN["type"].': '.$crossrefISSN["value"].'<br/>';                    }
                    echo '</li>';
                }
                if (!empty($this->crossrefArray["volume"])) {
                    echo '<li>Volume: '.$this->crossrefArray["volume"].'</li>';
                }
                if (!empty($this->crossrefArray["journal-issue"]["issue"])) {
                    echo '<li>Fascículo: '.$this->crossrefArray["journal-issue"]["issue"][0].'</li>';
                }
                if (!empty($this->crossrefArray["journal-issue"]["published-print"]["date-parts"])) {
                    echo '<li>Ano de publicação: '.$this->crossrefArray["journal-issue"]["published-print"]["date-parts"][0][0].'</li>';
                }
                if (!empty($this->crossrefArray["page"])) {
                    echo '<li>Paginação: '.$this->crossrefArray["page"].'</li>';
                }
                if (!empty($this->crossrefArray["publisher"])) {
                    echo '<li>Editora: '.$this->crossrefArray["publisher"].'</li>';
                }
                echo '</ul></li>';
            }
        }

        /* Data da defesa */
        if (!empty($this->dateCreated)) {
            echo '<li>Data da defesa: '.$this->dateCreated.'</a></li>';
        }

        /* Phisical description */
        if (!empty($this->numberOfPages)) {
            echo '<li>Descrição física: '.$this->numberOfPages.'</a></li>';
        }

        /* Award */
        if (isset($this->awardArray)) {
            foreach ($this->awardArray as $award) {
                $awardList[] = '<a href="'.$url_base.'/result.php?filter[]=award:&quot;'.$award.'&quot;">'.$award.'</a>';
            }
            echo '<li>'.$t->gettext('Premiações recebidas').': '.implode("; ", $awardList).'</li>';
        }

        /* ISBN */
        if (!empty($this->isbn)) {
            echo '<li>ISBN: '.$this->isbn.'</a></li>';
        }

        /* Source */
        if (!empty($this->isPartOfArray)) {
            echo '<li>'.$t->gettext('Fonte').':<ul>';
            if (!empty($this->isPartOfArray["name"])) {
                    echo '<li>Título do periódico: <a href="'.$url_base.'/result.php?filter[]=isPartOf.name:&quot;'.$this->isPartOfArray["name"].'&quot;">'.$this->isPartOfArray["name"].'</a></li>';
            }
            if (!empty($this->isPartOfArray['issn'][0])) {
                echo '<li>ISSN: <a href="'.$url_base.'/result.php?filter[]=issn:&quot;'.$this->isPartOfArray['issn'][0].'&quot;">'.$this->isPartOfArray['issn'][0].'</a></li>';
            }
            if (!empty($this->isPartOfArray["USP"]["dados_do_periodico"])) {
                echo '<li>Volume/Número/Paginação/Ano: '.$this->isPartOfArray["USP"]["dados_do_periodico"].'</li>';
            }
            echo '</ul></li>';
        }

        /*  releasedEvent */
        if (!empty($this->releasedEvent)) {
            echo '<li>'.$t->gettext('Nome do evento').': <a href="result.php?filter[]=releasedEvent:&quot;'.$this->releasedEvent.'&quot;">'.$this->releasedEvent.'</a></li>';
        }

        if (!empty($this->url)||!empty($this->doi)) {
            $this->onlineAccess($t);
        }

        $this->citation($t, $this->completeRecord);

    }

    public function onlineAccess($t)
    {

        echo '<div class="uk-alert-primary" style="padding:25px" uk-alert>';
        echo '<p class="uk-text-small">'.$t->gettext('Acesso online ao documento').'</p>';
        if (!empty($this->url)) {
            foreach ($this->url as $url) {
                echo '<a class="uk-button uk-button-primary uk-button-small" href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$t->gettext('Acesso online à fonte').'</a>';
            }
        }
        if (!empty($this->doi)) {
            echo '<a class="uk-button uk-button-primary uk-button-small" href="https://doi.org/'.$this->doi.'" target="_blank" rel="noopener noreferrer">DOI</a>';
        }

        $sfx_array[] = 'rft.atitle='.$this->name.'';
        $sfx_array[] = 'rft.year='.$this->datePublished.'';
        if (!empty($this->isPartOfArray["name"])) {
            $sfx_array[] = 'rft.jtitle='.$this->isPartOfArray["name"].'';
        }
        if (!empty($this->doi)) {
            $sfx_array[] = 'rft_id=info:doi/'.$this->doi.'';
        }
        if (!empty($this->issnArray[0]) && ($this->issnArray[0] != "Não informado")) {
            $sfx_array[] = 'rft.issn='.$this->issnArray[0].'';
        }
        if (!empty($r["_source"]['ispartof_data'][0])) {
            $sfx_array[] = 'rft.volume='.trim(str_replace("v.", "", $r["_source"]['ispartof_data'][0])).'';
        }
        echo ' <a class="uk-text-small" href="//www.sibi.usp.br/sfxlcl41?'.implode("&", $sfx_array).'" target="_blank" rel="noopener noreferrer">'.$t->gettext('ou pesquise este registro no').'<img src="https://www.sibi.usp.br/sfxlcl41/sfx.gif"></a>';
        echo '</div>';

    }

    public function holdings($id)
    {
        if ($dedalus == true) {
            Results::load_itens_aleph($id);
        }
    }

    public function metrics($t, $doi, $completeRecord)
    {

        if ($doi != "Não informado") {
            echo '<div class="uk-alert-warning" uk-alert>';
            echo '<p>'.$t->gettext('Métricas').'</p>';
            echo '<div uk-grid>';
            echo '<div data-badge-popover="right" data-badge-type="1" data-doi="'.$doi.'" data-hide-no-mentions="true" class="altmetric-embed"></div>';
            echo '<div><a href="https://plu.mx/plum/a/?doi='.$doi.'" class="plumx-plum-print-popup" data-hide-when-empty="true" data-badge="true" target="_blank" rel="noopener noreferrer"></a></div>';
            if ($doi != "Não informado") {
                echo '<div><object data="https://api.elsevier.com/content/abstract/citation-count?doi='.$doi.'&apiKey=c7af0f4beab764ecf68568961c2a21ea&httpAccept=text/html"></object></div>';
            }
            echo '<div><span class="__dimensions_badge_embed__" data-doi="'.$doi.'" data-hide-zero-citations="true" data-style="small_rectangle"></span></div>';
            if (!empty($completeRecord["_source"]["USP"]["opencitation"]["num_citations"])) {
                echo '<div>Citações no OpenCitations: '.$completeRecord["_source"]["USP"]["opencitation"]["num_citations"].'</div>';
            }
            if (isset($completeRecord["_source"]["USP"]["aminer"]["num_citation"])) {
                echo '<div>Citações no AMiner: '.$completeRecord["_source"]["USP"]["aminer"]["num_citation"].'</div>';
            }
            echo '</div>';
            echo '</div>';
        } else {
            if (isset($r["_source"]["USP"]["aminer"]["num_citation"])) {
                if ($r["_source"]["USP"]["aminer"]["num_citation"] > 0) {
                    echo '<div class="uk-alert-warning" uk-alert>';
                        echo '<p>'.$t->gettext('Métricas').':</p>';
                        echo '<div uk-grid>';
                        echo '<div>Citações no AMiner: <?php echo $r["_source"]["USP"]["aminer"]["num_citation"]; ?></div>';
                        echo '</div>';
                    echo '</div>';
                }
            }
        }
    }

    function citation($t, $record)
    {
        /* Citeproc-PHP*/

        global $style_abnt;
        global $style_apa;
        global $style_nlm;
        global $style_vancouver;
        
        
        $citeproc_abnt = new CiteProc($style_abnt, "pt-BR");
        $citeproc_apa = new CiteProc($style_apa, "en-US");
        $citeproc_nlm = new CiteProc($style_nlm, "en-US");
        $citeproc_vancouver = new CiteProc($style_vancouver, "en-US");
        echo '<div id="citacao'.$record['_id'].'" >';
            echo '<li class="uk-h6 uk-margin-top">';
                echo '<div class="uk-alert-danger" uk-alert>A citação é gerada automaticamente e pode não estar totalmente de acordo com as normas</div>';
                echo '<ul>';
                    echo '<li class="uk-margin-top">';
                    echo '<p><strong>ABNT</strong></p>';
                    $data = Citation::citationQuery($record["_source"]);
                    print_r($citeproc_abnt->render($data, "bibliography"));
                    echo '</li>';
                    echo '<li class="uk-margin-top">';
                    echo '<p><strong>APA</strong></p>';
                    print_r($citeproc_apa->render($data, "bibliography"));
                    echo '</li>';
                    echo '<li class="uk-margin-top">';
                    echo '<p><strong>NLM</strong></p>';
                    print_r($citeproc_nlm->render($data, "bibliography"));
                    echo '</li>';
                    echo '<li class="uk-margin-top">';
                    echo '<p><strong>Vancouver</strong></p>';
                    print_r($citeproc_vancouver->render($data, "bibliography"));
                    echo '</li>';
                echo '</ul>';
            echo '</li>';
        echo '</div>';
    }
}



?>