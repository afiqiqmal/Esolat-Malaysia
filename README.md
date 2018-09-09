# E-Solat Malaysia
[![Build Status](https://travis-ci.org/afiqiqmal/Esolat-Malaysia.svg?branch=master)](https://travis-ci.org/afiqiqmal/Esolat-Malaysia)
[![Coverage](https://img.shields.io/codecov/c/github/afiqiqmal/Esolat-Malaysia.svg)](https://codecov.io/gh/afiqiqmal/Esolat-Malaysia)
[![Packagist](https://img.shields.io/packagist/dt/afiqiqmal/Esolat-Malaysia.svg)](https://packagist.org/packages/afiqiqmal/Esolat-Malaysia)
[![Packagist](https://img.shields.io/packagist/v/afiqiqmal/Esolat-Malaysia.svg)](https://packagist.org/packages/afiqiqmal/Esolat-Malaysia)

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

Refer this for [Location Code](https://github.com/afiqiqmal/Esolat-Malaysia/blob/master/location_guide.md)

#### Sample
```php
$data = esolat()
        ->timeline()
        ->zone('PNG01')
        ->displayAs(Period::WEEK)
        ->fetch();
```

or Replace with
```php
$data = (new WaktuSolat())
        ->zone('PNG01')
        ->displayAs(Period:WEEK)
        ->fetch();
```

#### Sample for Day
```php
$response = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(Period::DAY)
        ->setDate('2018-10-10') // if this is not set, it will automatically get current date
        ->fetch();
```

#### Sample for Today
```php
$response = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(Period::TODAY)
        ->fetch();
```

#### Sample for Current Week
```php
$response = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(Period::Week)
        ->setDate('2018-10-10') //if this is set, it will display the date range of startof week to end of week of the date
        ->fetch();
```

#### Sample for Month
```php
$data = esolat()
        ->timeline()
        ->zone('P1') // P1 or PNG01
        ->displayAs(Period::Month)
        ->month(4)
        ->fetch();
```

#### Sample for Year
```php
$data = esolat()
        ->timeline()
        ->zone('PNG01') // P1 or PNG01
        ->displayAs(Period::Year)
        ->fetch();
```

#### Want to search by Coordinate?
- Just replace `zone()` with `locationProvider()`. For Example:
```php
$data = esolat()
        ->timeline()
        ->locationProvider(6.6626, 100.3217, GOOGLE API KEY)
        ->displayAs(Period::Year)
        ->fetch();
```
To get Only the Address Location
```php
$location = (new LocationProvider())
            ->setGoogleMapKey($key)
            ->setCoordinate($latitude, $longitude)
            ->fetch(); // Return ZoneData model
            
$location->getCode(); //R1            
$location->getJakimCode(); //PLS01            
$location->getState(); //Perlis            
$location->getZone(); //Padang Besar            
$location->toObject(); //return Object           
$location->toArray(); //return Array           
```

#### Type of Display
- Period::Day
- Period::Today
- Period::Week
- Period::Month
- Period::Year


#### Get Location List By State
```php
$data = esolat()->getLocations(); //return all
$data = esolat()->getLocations('negeri sembilan');
//or
$data = Location::getLocations($state);
```

#### Get Location By Code
```php
$data = esolat()->getLocationByCode('PLS01');
$data = esolat()->getLocationByCode('R1');
//or
$data = Location::getLocationByCode($code);
```

### Extra Usage
* `$adjustment` - By default is -2 day to fit with Malaysia Zone Date
##### Convert Date to Hijri Date
```php
$date = esolat()->date_to_hijri(\Carbon\Carbon::now(), $adjustment); // Return IslamicCarbon
$date->toDateIslamicString(); // 17-Ramadhan-1439
$date->month; // 9
$date->year; // 1439
$date->day; // 17
$date->islamic_month; // Ramadhan
```

##### Convert Hijri Date to Date
```php
$date = esolat()->hijri_to_date(17, 9, 1439, $adjustment); // Return Carbon
```

#### Get List of Nearby Mosque
```php
$data = esolat()->getNearbyMosque(2.9474,101.8451, "GOOGLE API", "RADIUS ex: 10000");
// or
$data = (new NearbyProvider("AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ"))
    ->getNearbyLocation('mosque', 2.9474,101.8451);
```

### Result
You should getting data similarly like SAMPLE below:
```json
{
    "code": 200,
    "error": false,
    "data": {
        "bearing":"291° 2′ 45″",
        "location":{
            "state":"Perlis",
            "zone":"Padang Besar",
            "jakim_code":"PLS01",
            "code":"R2",
            "longitude":100.3217,
            "latitude":6.6626
        },
        "timeline":[
            {
                "hijri_date":"1439-9-20",
                "date":"2018-06-04",
                "day":"Monday",
                "waktu":{
                    "imsak":1528090140,
                    "subuh":1528090740,
                    "syuruk":1528095780,
                    "zohor":1528118340,
                    "asar":1528130700,
                    "maghrib":1528140780,
                    "isyak":1528145280
                }
            },
            {
                "hijri_date":"1439-9-21",
                "date":"2018-06-05",
                "day":"Tuesday",
                "waktu":{
                    "imsak":1528176540,
                    "subuh":1528177140,
                    "syuruk":1528182180,
                    "zohor":1528204740,
                    "asar":1528217100,
                    "maghrib":1528227180,
                    "isyak":1528231740
                }
            },
            {
                "hijri_date":"1439-9-22",
                "date":"2018-06-06",
                "day":"Wednesday",
                "waktu":{
                    "imsak":1528262940,
                    "subuh":1528263540,
                    "syuruk":1528268580,
                    "zohor":1528291200,
                    "asar":1528303500,
                    "maghrib":1528313580,
                    "isyak":1528318140
                }
            },
            {
                "hijri_date":"1439-9-23",
                "date":"2018-06-07",
                "day":"Thursday",
                "waktu":{
                    "imsak":1528349340,
                    "subuh":1528349940,
                    "syuruk":1528354980,
                    "zohor":1528377600,
                    "asar":1528389900,
                    "maghrib":1528399980,
                    "isyak":1528404540
                }
            },
            {
                "hijri_date":"1439-9-24",
                "date":"2018-06-08",
                "day":"Friday",
                "waktu":{
                    "imsak":1528435740,
                    "subuh":1528436340,
                    "syuruk":1528441380,
                    "zohor":1528464000,
                    "asar":1528476360,
                    "maghrib":1528486440,
                    "isyak":1528491000
                }
            },
            {
                "hijri_date":"1439-9-25",
                "date":"2018-06-09",
                "day":"Saturday",
                "waktu":{
                    "imsak":1528522200,
                    "subuh":1528522800,
                    "syuruk":1528527840,
                    "zohor":1528550400,
                    "asar":1528562760,
                    "maghrib":1528572840,
                    "isyak":1528577400
                }
            },
            {
                "hijri_date":"1439-9-26",
                "date":"2018-06-10",
                "day":"Sunday",
                "waktu":{
                    "imsak":1528608600,
                    "subuh":1528609200,
                    "syuruk":1528614240,
                    "zohor":1528636800,
                    "asar":1528649160,
                    "maghrib":1528659240,
                    "isyak":1528663800
                }
            }
        ]
    },
    "generated_at": "2018-06-02 12:58:40",
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

## TODO
- Get waktu solat by coordinate

## Credit
- Jakim
- Location Extra - Malaysia Prayer Times (mpt)

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)
