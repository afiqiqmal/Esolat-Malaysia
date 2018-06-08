<?php

namespace Afiqiqmal\SolatJakim\Library;

class Constant
{
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'mont';
    const YEAR = 'year';

    const DAY_VAL = 1;
    const WEEK_VAL = 2;
    const MONTH_VAL = 3;
    const YEAR_VAL = 4;

    const ALL_MONTH = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    const WAKTU_SOLAT = ['imsak', 'subuh', 'syuruk', 'zohor', 'asar', 'maghrib', 'isyak'];

    const FILE_LOCATION = __DIR__ . '/../Sources/location.csv';
    const FILE_LOCATION_EXTRA = __DIR__ . '/../Sources/location_extra.csv';
}