# E-Solat

A Packagist for Malaysia E-solat Time table. Fully Scraped from [Jakim](www.e-solat.gov.my)

Tested in PHP 7.1 Only

## Installation

#### Step 1: Install from composer
```
composer require afiqiqmal/esolat-malaysia
```
Alternatively, you can specify as a dependency in your project's existing composer.json file
```
{
   "require": {
      "afiqiqmal/esolat-malaysia": "^1.0"
   }
}
```

## Usage
After installing, you need to require Composer's autoloader and add your code.

```php
require_once __DIR__ .'/../vendor/autoload.php';
```

#### Sample
```php
$data = esolat()
        ->timeline()
        ->zone('PNG01')
        ->displayAs(3) // default is 2 (Week)
        ->fetch();
```

#### Sample for Day
```php
$response = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(1) // must be 1
        ->setDate('2018-10-10') // if this is not set, it will automatically get current date
        ->fetch();
```

#### Sample for Current Week
```php
$response = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(2) // must be 1
        ->fetch();
```

#### Sample for Month
```php
$data = esolat()
        ->timeline()
        ->zone('P1') // P1 or PNG01
        ->displayAs(3)
        ->month(4) // if this is set, displayAs() will automatically use as type '4'
        ->year(2018)
        ->fetch();
```

#### Sample for Year
```php
$data = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(4)
        ->year(2018)
        ->fetch();
```

#### Type of Display
- (1) Day
- (2) Week
- (3) Month
- (4) Year


#### Get Location List By State
```php
$data = esolat()->location_list();
//or
$data = esolat()->location_list('negeri sembilan');
```

### Result
You should getting data similarly like below:
```json
{
    "code": 200,
    "error": false,
    "data": {
        "month": "05",
        "year": 2018,
        "zone": "Seluruh Negeri Pulau Pinang",
        "state": "Pulau Pinang",
        "code": "P1",
        "timeline": [
            {
                "date": "2018-05-29",
                "day": "Tuesday",
                "waktu": {
                    "imsak": 1527571860,
                    "subuh": 1527572460,
                    "syuruk": 1527577320,
                    "zohor": 1527599880,
                    "asar": 1527612180,
                    "maghrib": 1527622140,
                    "isyak": 1527626700
                }
            },
            {
                "date": "2018-05-30",
                "day": "Wednesday",
                "waktu": {
                    "imsak": 1527658260,
                    "subuh": 1527658860,
                    "syuruk": 1527663720,
                    "zohor": 1527686280,
                    "asar": 1527698580,
                    "maghrib": 1527708600,
                    "isyak": 1527713100
                }
            },
            {
                "date": "2018-05-31",
                "day": "Thursday",
                "waktu": {
                    "imsak": 1527744660,
                    "subuh": 1527745260,
                    "syuruk": 1527750180,
                    "zohor": 1527772680,
                    "asar": 1527784980,
                    "maghrib": 1527795000,
                    "isyak": 1527799500
                }
            },
            {
                "date": "2018-06-01",
                "day": "Friday",
                "waktu": {
                    "imsak": 1527831060,
                    "subuh": 1527831660,
                    "syuruk": 1527836580,
                    "zohor": 1527859140,
                    "asar": 1527871440,
                    "maghrib": 1527881400,
                    "isyak": 1527885900
                }
            },
            {
                "date": "2018-06-02",
                "day": "Saturday",
                "waktu": {
                    "imsak": 1527917460,
                    "subuh": 1527918060,
                    "syuruk": 1527922980,
                    "zohor": 1527945540,
                    "asar": 1527957840,
                    "maghrib": 1527967800,
                    "isyak": 1527972360
                }
            },
            {
                "date": "2018-06-03",
                "day": "Sunday",
                "waktu": {
                    "imsak": 1528003860,
                    "subuh": 1528004460,
                    "syuruk": 1528009380,
                    "zohor": 1528031940,
                    "asar": 1528044240,
                    "maghrib": 1528054260,
                    "isyak": 1528058760
                }
            },
            {
                "date": "2018-06-04",
                "day": "Monday",
                "waktu": {
                    "imsak": 1528090260,
                    "subuh": 1528090860,
                    "syuruk": 1528095780,
                    "zohor": 1528118340,
                    "asar": 1528130700,
                    "maghrib": 1528140660,
                    "isyak": 1528145160
                }
            }
        ]
    },
    "generated_at": "2018-05-29 14:55:03",
    "footer": {
        "source": "http://www.e-solat.gov.my/web/",
        "host": "JAKIM",
        "developer": {
            "name": "Hafiq",
            "homepage": "https://github.com/afiqiqmal"
        }
    }
}
```

### Result for location list
You should getting data similarly like below:
```json
{
    "code": 200,
    "error": false,
    "data": [
        {
            "state": "Negeri Sembilan",
            "zone": "Jempol",
            "jakim_code": "NGS01",
            "code": "N1"
        },
        {
            "state": "Negeri Sembilan",
            "zone": "Tampin",
            "jakim_code": "NGS01",
            "code": "N2"
        },
        {
            "state": "Negeri Sembilan",
            "zone": "Port Dickson",
            "jakim_code": "NGS02",
            "code": "N3"
        },
        {
            "state": "Negeri Sembilan",
            "zone": "Seremban",
            "jakim_code": "NGS02",
            "code": "N4"
        },
        {
            "state": "Negeri Sembilan",
            "zone": "Kuala Pilah",
            "jakim_code": "NGS02",
            "code": "N5"
        },
        {
            "state": "Negeri Sembilan",
            "zone": "Jelebu",
            "jakim_code": "NGS02",
            "code": "N6"
        },
        {
            "state": "Negeri Sembilan",
            "zone": "Rembau",
            "jakim_code": "NGS02",
            "code": "N7"
        }
    ],
    "generated_at": "2018-05-31 15:35:15",
    "footer": {
        "source": "http://www.e-solat.gov.my/web/",
        "host": "JAKIM",
        "developer": {
            "name": "Hafiq",
            "homepage": "https://github.com/afiqiqmal"
        }
    }
}
```

## Issue
- If Issue happen like the api always return empty [] after cross check with real site, just let me know =)

## ChangeLog
- See changelog.md

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)
