<?php
/**
 * PHP version 7
 * Result page
 *
 * The page for display results of search.
 *
 * @category Search
 * @package  uspfind
 * @author   Tiago Murakami <tiago.murakami@dt.sibi.usp.br>
 * @link     https://github.com/SIBiUSP/uspfind
 */

require 'inc/config.php';

// if (isset($_GET["search"])) {
//     foreach ($_GET["search"] as $getSearch) {
//         $getCleaned[] = htmlspecialchars($getSearch, ENT_QUOTES);
//     }
//     unset($_GET["search"]);
//     $_GET["search"] = $getCleaned;
// }

if (isset($fields)) {
    $_GET["fields"] = $fields;
}

$result_get = Requests::getParser($_GET);
$limit = $result_get['limit'];
$page = $result_get['page'];

// if (isset($_GET["sort"])) {
//     $result_get['query']["sort"][$_GET["sort"]]["unmapped_type"] = "long";
//     $result_get['query']["sort"][$_GET["sort"]]["missing"] = "_last";
//     $result_get['query']["sort"][$_GET["sort"]]["order"] = "desc";
//     $result_get['query']["sort"][$_GET["sort"]]["mode"] = "max";
// } else {
//     $result_get['query']['sort']['datePublished.keyword']['order'] = "desc";
// }

$params = [];
$params["index"] = $index;
$params["type"] = $type;
$params["size"] = $limit;
$params["from"] = $result_get['skip'];
$params["body"] = $result_get['query'];

echo "<br/><br/>";
print_r($params);
echo "<br/><br/>";

$cursor = $client->search($params);
$total = $cursor["hits"]["total"];

print_r($cursor);

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Resultado de Busca - BDPI USP</title>
		<link rel="icon" href="img/favicon.ico">
		<!-- CSS FILES -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/css/uikit.min.css">
		<link rel="stylesheet" type="text/css" href="https://zzseba78.github.io/Kick-Off/css/marketing.css">
	</head>
	<body>
		<!-- TOP -->
		<div class="top-wrap uk-position-relative uk-background-secondary">
			
			<!-- NAV -->
			<?php require 'inc/navbar.php'; ?>
            <!-- /NAV -->
            
        </div>
    </body>
</html>