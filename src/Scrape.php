<?php

namespace App;

require 'vendor/autoload.php';

class Scrape
{
    private array $products = [];

    public function run(): void
    {
        $document = ScrapeHelper::fetchDocument('https://www.magpiehq.com/developer-challenge/smartphones');
        
        // Get the number of pages so that we know how many pages we have to iterate through
        $numberOfPages = $document->filter('#pages .px.6')->count();

        // https://www.magpiehq.com/developer-challenge/smartphones/?page={pageNumber}

        // On each page we loop through each div with class product px-4 w-full md:w-1/2 mx-auto max-w-md mb-12

        // On each page we loop through each card div with class bg-white p-4 rounded-md

        // We must then de-dupe after the first parse

        // .........
        // remember capacity 1GB=1000MB
        // IsAvailable value dependent on the availabilityText
               

        file_put_contents('output.json', json_encode($this->products));
    }
}

$scrape = new Scrape();
$scrape->run();
