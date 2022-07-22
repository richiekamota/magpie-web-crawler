## PHP based Webcrawler

This code is for gathering data about the products listed on https://www.magpiehq.com/developer-challenge/smartphones

The final output of this script is an array of objects similar to the one below:

```
{
    "title": "iPhone 11 Pro 64GB",
    "price": 123.45,
    "imageUrl": "https://example.com/image.png",
    "capacityMB": 64000,
    "colour": "red",
    "availabilityText": "In Stock",
    "isAvailable": true,
    "shippingText": "Delivered from 25th March",
    "shippingDate": "2021-03-25"
}

```
### Features
* There is de-dupe functionality of data. This is to prevent the same product from appearing twice.
* All product variants are captured. Each colour is treated as a separate product.
* Device capacity is captured in MB for all products (not GB)
* The final output is an array of products, outputted to output.json

### Requirements

* PHP 7.4+
* Composer

### Setup

```
mkdir magpie-scrape
cd magpie-scrape
git clone https://github.com/richiekamota/magpie-web-crawler/tree/master .

composer install
```

To run the scrape you can use `php src/Scrape.php`
