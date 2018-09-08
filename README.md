# E-Solat Malaysia (In Maintainance)
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
        ->displayAs(3) // default is 2 (Week)
        ->fetch();
```

or Replace with
```php
$data = (new WaktuSolat())
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
        ->displayAs(2)
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

#### Want to search by Coordinate?
- Just replace `zone()` with `locationProvider()`. For Example:
```php
$data = esolat()
        ->timeline()
        ->locationProvider(6.6626, 100.3217, GOOGLE API KEY)
        ->displayAs(4)
        ->year(2018)
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
- (1) Day
- (2) Week
- (3) Month
- (4) Year


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
        "month": "06",
        "year": "2018",
        "location": {
            "state": "Perlis",
            "zone": "Padang Besar",
            "jakim_code": "PLS01",
            "code": "R2",
            "longitude": 100.3217,
            "latitude": 6.6626
        },
        "timeline": [
            {
                "date": "2018-06-02",
                "hijri_date": "17-Ramadhan-1439",
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
                "hijri_date": "18-Ramadhan-1439",
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
                "hijri_date": "19-Ramadhan-1439",
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
            },
            {
                "date": "2018-06-05",
                "hijri_date": "20-Ramadhan-1439",
                "day": "Tuesday",
                "waktu": {
                    "imsak": 1528176660,
                    "subuh": 1528177260,
                    "syuruk": 1528182180,
                    "zohor": 1528204740,
                    "asar": 1528217100,
                    "maghrib": 1528227060,
                    "isyak": 1528231620
                }
            },
            {
                "date": "2018-06-06",
                "hijri_date": "21-Ramadhan-1439",
                "day": "Wednesday",
                "waktu": {
                    "imsak": 1528263060,
                    "subuh": 1528263660,
                    "syuruk": 1528268580,
                    "zohor": 1528291140,
                    "asar": 1528303500,
                    "maghrib": 1528313460,
                    "isyak": 1528318020
                }
            },
            {
                "date": "2018-06-07",
                "hijri_date": "22-Ramadhan-1439",
                "day": "Thursday",
                "waktu": {
                    "imsak": 1528349520,
                    "subuh": 1528350120,
                    "syuruk": 1528354980,
                    "zohor": 1528377600,
                    "asar": 1528389900,
                    "maghrib": 1528399860,
                    "isyak": 1528404420
                }
            },
            {
                "date": "2018-06-08",
                "hijri_date": "23-Ramadhan-1439",
                "day": "Friday",
                "waktu": {
                    "imsak": 1528435920,
                    "subuh": 1528436520,
                    "syuruk": 1528441380,
                    "zohor": 1528464000,
                    "asar": 1528476360,
                    "maghrib": 1528486320,
                    "isyak": 1528490880
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
