<?php

namespace App;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHelper
{
    public static function fetchDocument(string $url): Crawler
    {
        $client = new Client();

        $response = $client->get($url);

        //$response->filter()  
        //$days->each(function (Crawler $day) {
        // $rows = $day->filter('tr');
        // $rows->each(function (Crawler $row) {
        // $cells = $row->filter('td');
        // $cells->each(function (Crawler $cell) {
         //   dump($cell->text());
         //});
        //});
         //});
        
        return new Crawler($response->getBody()->getContents(), $url);
    }
}
