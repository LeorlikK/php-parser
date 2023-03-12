<?php

namespace App;

use phpQuery;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public SaveFiles $saveFile;
    private string $address;

    public function __construct($address)
    {
        $this->address = $address;
        $this->saveFile = new SaveFiles();
    }

    public function getContentPars():string
    {
        return file_get_contents($this->address);
    }


    public function crawler($content):array
    {
        $dataArray = [];

        $crawler = new Crawler($content);
        $crawler = $crawler->filter('.content .entry')->each(function (Crawler $crawler, $i)use(&$dataArray){
            $url = $this->address . $crawler->filter('.entry-header h1 a')->attr('href');
            $title = $crawler->filter('.entry-header h1 a')->text();
            $short = $crawler->filter('.entry-content')->text();
            $bodyHtml = new Crawler(file_get_contents($url));
            $text = $bodyHtml->filter('.inner')->text();
            preg_match('/[а-яА-Я]+.+/', $text, $body);
            $imgUrl = $crawler->filter('.entry-content div img')->attr('src');
            $this->saveFile->saveImg($imgUrl);
            $dataArray[] = [
                'url' => $url,
                'title' => $title,
                'short' => $short,
                'body' => $body[0],
                'img' => $imgUrl,
            ];
            return [
                'url' => $url,
                'title' => $title,
                'short' => $short,
                'body' => $body[0],
                'img' => $imgUrl,
            ];
        });
        return $dataArray;
    }

    public function crawlerTest(string $content):array
    {
        $dataArray = [];
        $content = <<<'HTML'
        <!DOCTYPE html>
        <html>
            <body>
                <p class="message">Hello World!</p>
                <p class="hello">Hello Crawler!<a href="aaaaa">add</a></p>
                <p class="one">Hello One!</p>
                <p class="one">Hello Two!<a href="https://www.noob-club.ru/index.php?topic=83051.0">address</a></p>
                <img src="imageusrl" >
                <h1>ONE</h1>
            </body>
        </html>
        HTML;
        $crawler = new Crawler($content);
//        $crawler = $crawler->filter('body p')->eq(1);
//        $crawler = $crawler->filter('body p')->first();
//        $crawler = $crawler->filter('body p')->last();
//        $crawler = $crawler->filter('body p')->siblings();
//        $crawler = $crawler->filter('body p')->nextAll();
//        $crawler = $crawler->filter('body p')->eq(3)->previousAll();
//        $crawler = $crawler->filter('body')->children();
//        $crawler = $crawler->filter('body')->ancestors();
//        $crawler = $crawler->filter('body p.one');
//        $crawler = $crawler->filter('body p')->first()->closest('body');
        $crawler = $crawler->selectLink('address');
        if ($crawler) echo count($crawler) . PHP_EOL;
//        $i = 0;
//        foreach ($crawler as $c) {
//            $c = new Crawler($c);
//            $c = $c->text();
//            echo $c . PHP_EOL;
//            echo "I: $i" . PHP_EOL;
//            $i++;
//        }
//        $res = $crawler->text('Default text');
//        $res = $crawler->html();
//        $res = $crawler->innerText();
//        $res = $crawler->filter('a')->attr('href');
//        $res = $crawler->extract(['_name', '_text', 'class']);
//        $res = $crawler->each(function (Crawler $node, $i){
//            return $node->text();
//        });
        $res = $crawler->text();

        echo '============================='.PHP_EOL;
        var_dump($res);
//        echo $res;

        return $dataArray;
    }

//    public function phpQuery(string $content):array
//    {
//        $dataArray = [];
//        $pq = phpQuery::newDocument($content);
//
//        $findText = $pq->find('.content')->children();
//        var_dump(strlen($findText));
//        foreach ($findText as $text) {
//            $needHtml = pq($text)->find('.entry-header h1 a');
//            $link = $this->address . $needHtml->attr('href');
//            $title = $needHtml->text();
//            $short = trim(pq($text)->find('.entry-content')->text());
//
//            if ($link !== $this->address) $dataArray[$link] = [
//                'title' => $title,
//                'short' => $short
//            ];
//        }
//
//        phpQuery::unloadDocuments();
//
//        /**
//         * Примеры функций
//        $array = $pq->find('.content')->children(':not(.features-box)');
//        $array = $pq->find('.content');
//        var_dump(count($array));
//        $array->find('.features-box')->remove();
//        $array->append('askdkjashhd');
//        $array = $array->children();
//         */
//
//        return $dataArray;
//    }


}