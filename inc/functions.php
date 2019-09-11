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
        if (isset($record["_source"]["USP"]["fullTextFiles"])) {
            $this->bitstreamArray = $record["_source"]["USP"]["fullTextFiles"];
        }       
        $this->completeRecord = $record;
        $this->showMetrics = $showMetrics;
    }

    public function simpleRecordMetadata($t)
    {
        echo '
        <div class="card">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">'.$this->type.'</h6>
            <h5 class="card-title"><a class="text-dark" href="item/'.$this->id.'">'.$this->name.' ('.$this->datePublished.')</a></h5>';


        /* Authors */
        echo '<p class="card-text m-0"><small class="text-dark">'.$t->gettext('Autores').': ';
        foreach ($this->authorArray as $authors) {
            if (!empty($authors["person"]["orcid"])) {
                $orcidLink = ' <a href="'.$authors["person"]["orcid"].'"><img src="https://orcid.org/sites/default/files/images/orcid_16x16.png"></a>';
            } else {
                $orcidLink = '';
            }
            if (!empty($authors["person"]["potentialAction"])) {
                $authors_array[]='<a class="text-muted" href="result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].' ('.$authors["person"]["potentialAction"].')</a>'.$orcidLink.'';
            } else {
                $authors_array[]='<a class="text-muted" href="result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].'</a>'.$orcidLink.'';
            }
            unset($orcidLink);
        }
        echo implode("; ", $authors_array);
        echo '</small></p>';

        echo '<p class="card-text m-0"><small class="text-dark">Unidades USP: ';
        if (!empty($this->unidadeUSPArray)) {
            $unique =  array_unique($this->unidadeUSPArray);
            foreach ($unique as $unidadeUSP) {
                echo '<a class="text-muted" href="result.php?filter[]=unidadeUSP:&quot;'.$unidadeUSP.'&quot;">'.$unidadeUSP.' </a>';
            }
        }
        echo '</small></p>';        

        /* IsPartOf */
        if (!empty($this->isPartOfArray["name"])) {
            echo '<p class="card-text m-0"><small class="text-dark">In: <a class="text-muted" href="result.php?filter[]=isPartOf.name:&quot;'.$this->isPartOfArray["name"].'&quot;">'.$this->isPartOfArray["name"].'</a></small></p>';
        }

        /*  releasedEvent */
        if (!empty($this->releasedEvent)) {
            echo '<p class="card-text m-0"><small class="text-dark">'.$t->gettext('Nome do evento').': <a class="text-muted" href="result.php?filter[]=releasedEvent:&quot;'.$this->releasedEvent.'&quot;">'.$this->releasedEvent.'</a></small></p>';
        }

        /* Subjects */
        if (!empty($this->aboutArray)) {
            echo '<p class="card-text m-0"><small class="text-dark">'.$t->gettext('Assuntos').': ';
            foreach ($this->aboutArray as $subject) {
                $subject_array[] = '<a class="text-muted" href="result.php?filter[]=about:&quot;'.$subject.'&quot;">'.$subject.'</a>';
            }
            echo implode("; ", $subject_array);
        }
        echo '</small></p>';

        if (!empty($this->url)||!empty($this->doi)) {
            $this->onlineAccess($t);
        }        


        echo '
        </div>
        </div>
        ';
       
        flush();
        ob_flush();

    }

    public function completeRecordMetadata($t, $url_base, $createFormDSpace = null)
    {
        echo '<article class="uk-article">';
        echo '<p class="uk-article-meta">';
        echo '<a href="<?php echo $url_base ?>/result.php?filter[]=type:&quot;'.$this->type.'&quot;">'.$this->type.'</a> | <a href="<?php echo $url_base ?>/result.php?filter[]=type:&quot;'.$this->originalType.'&quot;">'.$this->originalType.'</a>';
        echo '</p>';
        echo '<h1 class="uk-article-title uk-margin-remove-top uk-link-reset" style="font-size:150%">'.$this->name.' ('.$this->datePublished.')</h1>';
        

        echo '<dl class="row">';
        /* Authors */
        foreach ($this->authorArray as $authors) {
            if (!empty($authors["person"]["orcid"])) {
                $orcidLink = ' <a href="'.$authors["person"]["orcid"].'"><img src="https://orcid.org/sites/default/files/images/orcid_16x16.png"></a>';
            } else {
                $orcidLink = '';
            }
            if (!empty($authors["person"]["affiliation"]["name"])) {
                $authorsList[] =  '<a href="'.$url_base.'/result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].' - <span class="uk-text-muted">'.$authors["person"]["affiliation"]["name"].'</span></a>'.$orcidLink.'';
            } elseif (!empty($authors["person"]["potentialAction"])) {
                $authorsList[] = '<a href="'.$url_base.'/result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].' <span class="uk-text-muted">('.$authors["person"]["potentialAction"].')</span></a>'.$orcidLink.'';
            } else {
                $authorsList[] = '<a href="'.$url_base.'/result.php?filter[]=author.person.name:&quot;'.$authors["person"]["name"].'&quot;">'.$authors["person"]["name"].'</a>'.$orcidLink.'';
            }
            unset($orcidLink);
        }
        echo '<dt class="col-sm-2">'.$t->gettext('Autor(es)').'</dt><dd class="col-sm-10">'.implode("; ", $authorsList).'</dd>';
        

        echo '<div class="uk-grid" data-ukgrid>';
        echo '<div class="uk-width-1-3@m">';

        if (!empty($this->bitstreamArray)) { 
            echo '<div class="uk-alert-primary" uk-alert>
            <h4>'.$t->gettext('Download na BDPI').'</h4>
            <ul class="uk-list uk-list-striped">
            ';

            

            foreach ($this->bitstreamArray as $key => $value) {
                echo ' 
                <li>
                <a class="uk-link-toggle" href="https://'.$_SERVER["SERVER_NAME"].'/bitstreams/'.$value["uuid"].'" target="_blank" rel="noopener noreferrer nofollow">
                <div uk-grid>
                    <div class="uk-width-auto"><img data-src="'.$url_base.'/inc/images/pdf.png" width="50" height="50" alt="PDF" uk-img></div>
                    <div class="uk-width-expand"><b>Nome:</b> '.$value["name"].'<br/><b>Tamanho:</b> '.$value["sizeBytes"].'</div>
                </div>
                </a>
                </li>';
            }

            echo '</dl></div>';
        }

        
        if (!empty($createFormDSpace)) {
            $this->itemsDSpace($createFormDSpace, $t);
        }

        if (!empty($this->url)||!empty($this->doi)) {
            $this->onlineAccess($t);
        }          

        echo '</div>';
        echo '<div>';

        echo '<dl class="row">';

        /* Abstract */
        if (!empty($this->descriptionArray)) {
            echo '<dt class="col-sm-3">'.$t->gettext('Resumo').': </dt>';
            foreach ($this->descriptionArray as $resumo) {
                echo '<dd class="col-sm-9">'.$resumo.'</dd>';
            }
        }        
        
        /* USP Units */
        if (!empty($this->unidadeUSPArray)) {
            foreach ($this->unidadeUSPArray as $unidadeUSP) {
                $unidadeUSPList[] = '<a href="'.$url_base.'/result.php?filter[]=unidadeUSP:&quot;'.$unidadeUSP.'&quot;">'.$unidadeUSP.'</a>';
            }
            $unidadeUSPListUnique = array_unique($unidadeUSPList);
            echo '<dt class="col-sm-3">'.$t->gettext('Unidades USP').'</dt><dd class="col-sm-9">'.implode("; ", $unidadeUSPListUnique).'</dd>';
        }
        
        /* Programa Sigla Pós */
        if (!empty($this->programa_pos_sigla)) {
            $programa_pos_sigla = $this->programa_pos_sigla;
            $programa_pos_siglaList[] = '<a href="'.$url_base.'/result.php?filter[]=programa_pos_sigla:&quot;'.$programa_pos_sigla.'&quot;">'.$programa_pos_sigla.'</a>';
            echo '<dt class="col-sm-3">'.$t->gettext('Sigla do Departamento').'</dt><dd class="col-sm-9>'.implode("; ", $programa_pos_siglaList).'</dd>';
        }
        
        /* DOI */
        if (!empty($this->doi)) {
            echo '<dt class="col-sm-3">DOI</dt><dd class="col-sm-9"><a href="https://doi.org/'.$this->doi.'" target="_blank" rel="noopener noreferrer">'.$this->doi.'</a></dd>';
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
            echo '<dt class="col-sm-3">'.$t->gettext('Assuntos').'</dt><dd class="col-sm-9">'.implode("; ", $subjectList).'</dd>';
        }
            
        /* BDTD Subject */
        if ($this->aboutBDTDArray > 0) {
            foreach ($this->aboutBDTDArray as $subject_BDTD) {
                $subjectBDTDList[] = '<a href="'.$url_base.'/result.php?filter[]=USP.about_BDTD:&quot;'.$subject_BDTD.'&quot;">'.$subject_BDTD.'</a>';
            }
            echo '<dt class="col-sm-3">'.$t->gettext('Palavras-chave do autor').'</dt><dd class="col-sm-9">'.implode("; ", $subjectBDTDList).'</dd>';
        }
        
        /* Funder */
        if ($this->funderArray > 0) {
            echo '<dt class="col-sm-3">'.$t->gettext('Agências de fomento').'</dt>';
            echo '<dd class="col-sm-9"><dl class="row">';
            foreach ($this->funderArray as $funder) {
                echo '<dd class="col-sm-12"><a href="'.$url_base.'/result.php?filter[]=funder:&quot;'.$funder["name"].'&quot;">'.$funder["name"].'</a>';
                if (!empty($funder["projectNumber"]) && $funder["name"] == "Fundação de Amparo à Pesquisa do Estado de São Paulo (FAPESP)") {
                    foreach ($funder["projectNumber"] as $projectNumber) {
                        $projectNumber = str_replace(" ", "", $projectNumber);
                        preg_match("/\d\d\/\d{5}-\d/", $projectNumber, $projectNumberMatchArray);
                        echo '<br/>Processo FAPESP: <a href="http://bv.fapesp.br/pt/processo/'.$projectNumberMatchArray[0].'" target="_blank" rel="noopener noreferrer">'.$projectNumber.'</a>';
                    }
                }
                echo '</dd>';
            }
            echo '</dl></dd>';
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

        /* Source */
        if (!empty($this->isPartOfArray)) {
            echo '<dt class="col-sm-3">'.$t->gettext('Fonte').'</dt><dd class="col-sm-9"><dl class="row">';
            if (!empty($this->isPartOfArray["name"])) {
                    echo '<dt class="col-sm-4">Título do periódico</dt><dd class="col-sm-8"><a href="'.$url_base.'/result.php?filter[]=isPartOf.name:&quot;'.$this->isPartOfArray["name"].'&quot;">'.$this->isPartOfArray["name"].'</a></dd>';
            }
            if (!empty($this->isPartOfArray['issn'][0])) {
                echo '<dt class="col-sm-4">ISSN</dt><dd class="col-sm-8"><a href="'.$url_base.'/result.php?filter[]=issn:&quot;'.$this->isPartOfArray['issn'][0].'&quot;">'.$this->isPartOfArray['issn'][0].'</a></dd>';
            }
            if (!empty($this->isPartOfArray["USP"]["dados_do_periodico"])) {
                echo '<dt class="col-sm-4">Volume/Número/Paginação/Ano</dt><dd class="col-sm-8">'.$this->isPartOfArray["USP"]["dados_do_periodico"].'</dd>';
            }
            echo '</dl></dd>';
        }

        /*  releasedEvent */
        if (!empty($this->releasedEvent)) {
            echo '<dt class="col-sm-3">'.$t->gettext('Nome do evento').'</dt><dd class="col-sm-9"><a href="'.$url_base.'/result.php?filter[]=releasedEvent:&quot;'.$this->releasedEvent.'&quot;">'.$this->releasedEvent.'</a></dd>';
        }          

        /* Language */
        foreach ($this->languageArray as $language) {
            $languageList[] = '<a href="'.$url_base.'/result.php?filter[]=language:&quot;'.$language.'&quot;">'.$language.'</a>';
        }
        echo '<dt class="col-sm-3">'.$t->gettext('Idioma').'</dt><dd class="col-sm-9">'.implode("; ", $languageList).'</dd>';
        
        /* Imprint */
        if (!empty($this->publisherArray)) {
            echo '<dt class="col-sm-3">'.$t->gettext('Imprenta').'</dt><dd class="col-sm-9"><dl class="row">';
            if (!empty($this->publisherArray["organization"]["name"])) {
                echo '<dt class="col-sm-4">'.$t->gettext('Editora').'</dt><dd class="col-sm-8"><a href="'.$url_base.'/result.php?filter[]=publisher.organization.name:&quot;'.$this->publisherArray["organization"]["name"].'&quot;">'.$this->publisherArray["organization"]["name"].'</a></dd>';
            }
            if (!empty($this->publisherArray["organization"]["location"])) {
                echo '<dt class="col-sm-4">'.$t->gettext('Local').'</dt><dd class="col-sm-8"><a href="'.$url_base.'/result.php?filter[]=publisher.organization.location:&quot;'.$this->publisherArray["organization"]["location"].'&quot;">'.$this->publisherArray["organization"]["location"].'</a></dd>';
            }
            if (!empty($this->datePublished)) {
                echo '<dt class="col-sm-4">'.$t->gettext('Data de publicação').'</dt><dd class="col-sm-8"><a href="'.$url_base.'/result.php?filter[]=datePublished:&quot;'.$this->datePublished.'&quot;">'.$this->datePublished.'</a></dd>';
            }
            echo '</dl></dd>';
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
                    echo '<li>'.$t->gettext('Volume').': '.$this->crossrefArray["volume"].'</li>';
                }
                if (!empty($this->crossrefArray["journal-issue"]["issue"])) {
                    echo '<li>'.$t->gettext('Fascículo').': '.$this->crossrefArray["journal-issue"]["issue"][0].'</li>';
                }
                if (!empty($this->crossrefArray["journal-issue"]["published-print"]["date-parts"])) {
                    echo '<li>'.$t->gettext('Ano de publicação').': '.$this->crossrefArray["journal-issue"]["published-print"]["date-parts"][0][0].'</li>';
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
            echo '<dt class="col-sm-3">'.$t->gettext('Data da defesa').'</dt><dd class="col-sm-9">'.$this->dateCreated.'</a></dd>';
        }

        /* Phisical description */
        if (!empty($this->numberOfPages)) {
            echo '<dt class="col-sm-3">'.$t->gettext('Descrição física').'</dt><dd class="col-sm-9">'.$this->numberOfPages.'</a></dd>';
        }

        /* Award */
        if (isset($this->awardArray)) {
            foreach ($this->awardArray as $award) {
                $awardList[] = '<a href="'.$url_base.'/result.php?filter[]=award:&quot;'.$award.'&quot;">'.$award.'</a>';
            }
            echo '<dt class="col-sm-3">'.$t->gettext('Premiações recebidas').'</dt><dd class="col-sm-9">'.implode("; ", $awardList).'</dd>';
        }

        /* ISBN */
        if (!empty($this->isbn)) {
            echo '<dt class="col-sm-3">ISBN</dt><dd class="col-sm-9">'.$this->isbn.'</a></dd>';
        }
        
        /* USP Authors */
        if (!empty($this->authorUSPArray)) {
            foreach ($this->authorUSPArray as $autoresUSP) {
                $authorsUSPList[] = '<dd class="col-sm-12"><a href="'.$url_base.'/result.php?filter[]=authorUSP.name:&quot;'.$autoresUSP["name"].'&quot;">'.$autoresUSP["name"].' - '.$autoresUSP["unidadeUSP"].' </a></dd>';
            }
            echo '<dt class="col-sm-3">'.$t->gettext('Autores USP').'</dt><dd class="col-sm-9"><dl class="row">'.implode("", $authorsUSPList).'</dl></dd>';
        }        

        echo '</div>';
        echo '</div>';   


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
        echo '<p><a class="uk-text-small" href="//www.sibi.usp.br/sfxlcl41?'.implode("&", $sfx_array).'" target="_blank" rel="noopener noreferrer">'.$t->gettext('Ou pesquise este registro no').'<img src="https://www.sibi.usp.br/sfxlcl41/sfx.gif"></a></p>';
        echo '</div>';

    }

    public function itemsDSpace($createFormDSpace, $t)
    {
        echo $createFormDSpace["alert"];
        echo $createFormDSpace["form"];
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

class AdminStats 
{
    public static function source($source) 
    {
        global $client;
        global $index;
        $params = [];
        $params["index"] = $index;
        $params["size"] = 0;

        /* Get total collected in source */
        $query["query"]["query_string"]["query"] = "+_exists_:USP.$source";        
        $params["body"] = $query; 
        $cursorTotal = $client->search($params);
        $result["totalCollectedInSource"] = $cursorTotal["hits"]["total"]["value"];

        /* Get total found in source */
        $query["query"]["query_string"]["query"] = "USP.$source.found:true";        
        $params["body"] = $query; 
        $cursorFound = $client->search($params);
        $result["foundInSource"] = $cursorFound["hits"]["total"]["value"]; 
        
        /* Get total not found in source */
        $query["query"]["query_string"]["query"] = "USP.$source.found:false";        
        $params["body"] = $query; 
        $cursorNotFound = $client->search($params);
        $result["notFoundInSource"] = $cursorNotFound["hits"]["total"]["value"];

        return $result;
    }

    public static function field($field) 
    {
        global $client;
        global $index;
        $params = [];
        $params["index"] = $index;
        
        $size = 50;

        $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
        $query["aggs"]["counts"]["terms"]["missing"] = "Não preenchido";
        $query["aggs"]["counts"]["terms"]["size"] = $size;

        $response = Elasticsearch::search(null, 0, $query);
        $result_count = count($response["aggregations"]["counts"]["buckets"]);     

        foreach ($response["aggregations"]["counts"]["buckets"] as $facets) {
            if ($facets["key"] == "false") {
                $result["notCorrect"] = $facets["doc_count"];
            } elseif ($facets["key"] == "true") {
                $result["correct"] = $facets["doc_count"];
            } else {
                $result["notFound"] = $facets["doc_count"];
            }
        }
        
        if (empty($result["correct"])) {
            $result["correct"] = 0;
        }
        if (empty($result["notCorrect"])) {
            $result["notCorrect"] = 0;
        }        
        $result["totalOccorrences"] = ($result["correct"] + $result["notCorrect"]);

        return $result;
    }
    
    public static function fullTextFiles() 
    {
        global $client;
        global $index;
        $params = [];
        $params["index"] = $index;
        $params["size"] = 0;
        $query["query"]["query_string"]["query"] = "+_exists_:USP.fullTextFiles";       
        $params["body"] = $query; 
        $cursor = $client->search($params);
        return $cursor;
    }    
}

/**
 * APIs
 *
 * @category Class
 * @package  APIs
 * @author   Tiago Rodrigo Marçal Murakami <tiago.murakami@dt.sibi.usp.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://github.com/sibiusp/nav_elastic
 */
class API
{
    public static function dimensionsAPI($doi)
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://metrics-api.dimensions.ai/doi/'.$doi.'',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
        )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $data = json_decode($resp, true);
        return $data;
        // Close request to clear up some resources
        curl_close($curl);
    }

    public static function sherpaAPI($issn, $sherpaAPIKEY)
    {
        $page = file_get_contents('http://www.sherpa.ac.uk/romeo/api29.php?issn='.$issn.'&ak='.$sherpaAPIKEY.'');
        $xml = simplexml_load_string($page);
        $json = json_encode($xml);
        $array = json_decode($json, true); 
        return $array;
    }    
}

/**
 * Página Inicial
 *
 * @category Class
 * @package  Homepage
 * @author   Tiago Rodrigo Marçal Murakami <tiago.murakami@dt.sibi.usp.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://github.com/sibiusp/nav_elastic
 */
class Homepage
{
    /**
     * Function last records
     *
     * @return array Last records
     */
    static function getLastRecords()
    {

        global $client;
        global $index;
        $params = [];
        $params["index"] = $index;
        $params["size"] = 0;
        $query["query"]["bool"]["must"]["query_string"]["query"] = "*";
        $query["sort"]["_uid"]["unmapped_type"] = "long";
        $query["sort"]["_uid"]["missing"] = "_last";
        $query["sort"]["_uid"]["order"] = "desc";
        $query["sort"]["_uid"]["mode"] = "max";         
        $params["body"] = $query; 
        $response = Elasticsearch::search(null, 10, $query);

        foreach ($response["hits"]["hits"] as $r) {
            echo '<article class="uk-comment">
            <header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>';
            if (!empty($r["_source"]['unidadeUSP'])) {
                $file = 'inc/images/logosusp/'.$r["_source"]['unidadeUSP'][0].'.jpg';
            } else {
                $file = "";
            }
            if (file_exists($file)) {
                echo '<div class="uk-width-auto"><img class="uk-comment-avatar" src="'.$file.'" width="60" height="60" alt=""></div>';
            } else {

            };
            echo '<div class="uk-width-expand">';
            if (!empty($r["_source"]['name'])) {
                echo '<a href="item/'.$r['_id'].'"><h4 class="uk-comment-title uk-margin-remove">'.$r["_source"]['name'].'';
                if (!empty($r["_source"]['datePublished'])) {
                    echo ' ('.$r["_source"]['datePublished'].')';
                }
                echo '</h4></a>';
            };
            echo '<ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-small">';
            if (!empty($r["_source"]['author'])) {
                foreach ($r["_source"]['author'] as $autores) {
                    if (!empty($autores["person"]["orcid"])) {
                        $orcidLink = ' <a href="'.$autores["person"]["orcid"].'"><img src="https://orcid.org/sites/default/files/images/orcid_16x16.png"></a>';
                    } else {
                        $orcidLink = '';
                    }
                    echo '<li><a href="result.php?filter[]=author.person.name:&quot;'.$autores["person"]["name"].'&quot;">'.$autores["person"]["name"].'</a>'.$orcidLink.'</li>';
                    unset($orcidLink);
                }
                echo '</ul></div>';
            };
            echo '</header>';
            echo '</article>';
        }

    }      
}

/**
 * Exporters
 *
 * @category Class
 * @package  Exporters
 * @author   Tiago Rodrigo Marçal Murakami <tiago.murakami@dt.sibi.usp.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://github.com/sibiusp/nav_elastic
 */
class Exporters
{

    static function RIS($cursor)
    {

        $record = [];
        switch ($cursor["_source"]["type"]) {
        case "ARTIGO DE PERIODICO":
            $record[] = "TY  - JOUR";
            break;
        case "PARTE DE MONOGRAFIA/LIVRO":
            $record[] = "TY  - CHAP";
            break;
        case "TRABALHO DE EVENTO-RESUMO":
            $record[] = "TY  - CPAPER";
            break;
        case "TEXTO NA WEB":
            $record[] = "TY  - ICOMM";
            break;
        }

        $record[] = "TI  - ".$cursor["_source"]['name']."";

        if (!empty($cursor["_source"]['datePublished'])) {
            $record[] = "PY  - ".$cursor["_source"]['datePublished']."";
        }

        foreach ($cursor["_source"]['author'] as $autores) {
            $record[] = "AU  - ".$autores["person"]["name"]."";
        }

        if (!empty($cursor["_source"]["releasedEvent"])) {
            $record[] = "T2  - ".$cursor["_source"]["releasedEvent"]."";
            if (!empty($cursor["_source"]["isPartOf"]["name"])) {
                $record[] = "J2  - ".$cursor["_source"]["isPartOf"]["name"]."";
            }
        } else {
            if (!empty($cursor["_source"]["isPartOf"]["name"])) {
                $record[] = "T2  - ".$cursor["_source"]["isPartOf"]["name"]."";
            }
        }

        if (!empty($cursor["_source"]['isPartOf']['issn'])) {
            $record[] = "SN  - ".$cursor["_source"]['isPartOf']['issn'][0]."";
        }

        if (!empty($cursor["_source"]["doi"])) {
            $record[] = "DO  - ".$cursor["_source"]["doi"]."";
        }

        if (!empty($cursor["_source"]["url"])) {
            $record[] = "UR  - ".$cursor["_source"]["url"][0]."";
        }

        if (!empty($cursor["_source"]["publisher"]["organization"]["location"])) {
            $record[] = "PP  - ".$cursor["_source"]["publisher"]["organization"]["location"]."";
        }

        if (!empty($cursor["_source"]["publisher"]["organization"]["name"])) {
            $record[] = "PB  - ".$cursor["_source"]["publisher"]["organization"]["name"]."";
        }

        if (!empty($cursor["_source"]["isPartOf"]["USP"]["dados_do_periodico"])) {
            $periodicos_array = explode(",", $cursor["_source"]["isPartOf"]["USP"]["dados_do_periodico"]);
            foreach ($periodicos_array as $periodicos_array_new) {
                if (strpos($periodicos_array_new, 'v.') !== false) {
                    $record[] = "VL  - ".trim(str_replace("v.", "", $periodicos_array_new))."";
                } elseif (strpos($periodicos_array_new, 'n.') !== false) {
                    $record[] = "IS  - ".str_replace("n.", "", trim(str_replace("n.", "", $periodicos_array_new)))."";
                } elseif (strpos($periodicos_array_new, 'p.') !== false) {
                    $record[] = "SP  - ".str_replace("p.", "", trim(str_replace("p.", "", $periodicos_array_new)))."";
                }

            }
        }

        $record[] = "ER  - ";
        $record[] = "";
        $record[] = "";

        $record_blob = implode("\\n", $record);

        return $record_blob;

    }

    static function bibtex($cursor)
    {

        $record = [];

        if (!empty($cursor["_source"]['name'])) {
            $recordContent[] = 'title   = {'.$cursor["_source"]['name'].'}';
        }

        if (!empty($cursor["_source"]['author'])) {
            $authorsArray = [];
            foreach ($cursor["_source"]['author'] as $author) {
                $authorsArray[] = $author["person"]["name"];
            }
            $recordContent[] = 'author = {'.implode(" and ", $authorsArray).'}';
        }

        if (!empty($cursor["_source"]['datePublished'])) {
            $recordContent[] = 'year = {'.$cursor["_source"]['datePublished'].'}';
        }

        if (!empty($cursor["_source"]['doi'])) {
            $recordContent[] = 'doi = {'.$cursor["_source"]['doi'].'}';
        }

        if (!empty($cursor["_source"]['publisher']['organization']['name'])) {
            $recordContent[] = 'publisher = {'.$cursor["_source"]['publisher']['organization']['name'].'}';
        }

        if (!empty($cursor["_source"]["releasedEvent"])) {
            $recordContent[] = 'booktitle   = {'.$cursor["_source"]["releasedEvent"].'}';
        } else {
            if (!empty($cursor["_source"]["isPartOf"]["name"])) {
                $recordContent[] = 'journal   = {'.$cursor["_source"]["isPartOf"]["name"].'}';
            }
        }


        $sha256 = hash('sha256', ''.implode("", $recordContent).'');

        switch ($cursor["_source"]["type"]) {
        case "ARTIGO DE PERIODICO":
            $record[] = '@article{article'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
            break;
        case "MONOGRAFIA/LIVRO":
            $record[] = '@book{book'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
            break;
        case "PARTE DE MONOGRAFIA/LIVRO":
            $record[] = '@inbook{inbook'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
            break;
        case "TRABALHO DE EVENTO":
            $record[] = '@inproceedings{inproceedings'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
            break;
        case "TRABALHO DE EVENTO-RESUMO":
            $record[] = '@inproceedings{inproceedings'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
            break;
        case "TESE":
            $record[] = '@mastersthesis{mastersthesis'.substr($sha256, 0, 8).',';
            $recordContent[] = 'school = {Universidade de São Paulo}';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
            break;
        default:
            $record[] = '@misc{misc'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';
        }


        $record_blob = implode("\\n", $record);

        return $record_blob;

    }

    static function table($r)
    {
        $fields[] = $r['_id'];
        $fields[] = "Não foi possível coletar";

        foreach ($r["_source"]['authorUSP'] as $numUSP_aut) {
            if (isset($numUSP_aut["codpes"])) {
                $fields[] = $numUSP_aut["codpes"];
            } else {
                $fields[] = "Não preenchido corretamente";
            }

            $fields[] = $numUSP_aut["name"];
        }


        foreach ($r["_source"]['author'] as $authors) {
            if (empty($authors["person"]["potentialAction"])) {
                $fields[] = $authors["person"]["name"];
            } else {
                $orientadores_array[] = $authors["person"]["name"];
            }
        }
        if (isset($orientadores_array)) {
            $array_orientadores = implode("; ", $orientadores_array);
            unset($orientadores_array);
            $fields[] = $array_orientadores;
        } else {
            $fields[] = "Não preenchido";
        }

        if (isset($r["_source"]['USP']['codpesOrientador'])) {
            foreach ($r["_source"]['USP']['codpesOrientador'] as $codpesOrientador) {
                $array_codpesOrientador[] = $codpesOrientador;
            }
        }
        if (isset($array_codpesOrientador)) {
            $array_codpesOrientadores = implode("; ", $array_codpesOrientador);
            unset($array_codpesOrientador);
            $fields[] = $array_codpesOrientadores;
        } else {
            $fields[] = "Não preenchido";
        }



        if (isset($r["_source"]['USP']['areaconcentracao'])) {
            $fields[] = $r["_source"]['USP']['areaconcentracao'];
        } else {
            $fields[] = "Não preenchido";
        }
        if (isset($r["_source"]['inSupportOf'])) {
            $fields[] = $r["_source"]['inSupportOf'];
        } else {
            $fields[] = "Não preenchido";
        }

        $fields[] = $r["_source"]['language'][0];
        $fields[] = $r["_source"]['name'];

        if (isset($r["_source"]['description'][0])) {
            $fields[] = $r["_source"]['description'][0];
        } else {
            $fields[] = "Não preenchido";
        }

        foreach ($r["_source"]['about'] as $subject) {
            $subject_array[]=$subject;
        }
        $array_subject = implode("; ", $subject_array);
        unset($subject_array);
        $fields[] = $array_subject;

        if (isset($r["_source"]['alternateName'])) {
            $fields[] = $r["_source"]['alternateName'];
        } else {
            $fields[] = "Não preenchido";
        }

        if (isset($r["_source"]['descriptionEn'])) {
            foreach ($r["_source"]['descriptionEn'] as $descriptionEn) {
                $descriptionEn_array[] = $descriptionEn;
            }
            $array_descriptionEn = implode(" ", $descriptionEn_array);
            unset($descriptionEn_array);
            $fields[] = $array_descriptionEn;
        } else {
            $fields[] = "Não preenchido";
        }

        $fields[] = $r["_source"]['datePublished'];

        $fields[] = $r["_source"]['publisher']['organization']['location'];

        if (isset($r["_source"]['dateCreated'])) {
            $fields[] = $r["_source"]['dateCreated'];
        }

        if (isset($r["_source"]['url'])) {
            foreach ($r["_source"]['url'] as $url) {
                $url_array[] = $url;
            }
            $array_url = implode("| ", $url_array);
            unset($url_array);
            $fields[] = $array_url;
        }


        // $content[] = implode("\t", $fields);

        return implode("\t", $fields)."\n";
        flush();

        unset($fields);        

    }

}


?>