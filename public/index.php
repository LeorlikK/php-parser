<?php
require_once '../vendor/autoload.php';

use App\Parser;
use App\SaveFiles;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
const PROJECT_DIR = "D:\Language\PHP\Projects\parser-crawler";

$parser = new Parser("https://www.noob-club.ru/");
$save = new SaveFiles();

// Получение файла
$html = $parser->getContentPars();

// Временное сохранение данных
$save->saveFile($html, '\storage');

// Открытие сохраненного файла
$content = $save->openFile('\storage\parser.json');

// Парсинг нужной информации
$arrayResult = $parser->crawler($content);
//$arrayResult = $parser->crawlerTest($content);

// Сохранение полученной информации
$save->saveFile($arrayResult, '\storage', 'noob_club');

// Открытие сохраненного файла
$content = $save->openFile('\storage\noob_club.json', true);

echo '<pre>';
var_dump($content);
echo '</pre>';

?>
</body>
</html>