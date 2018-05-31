<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$response = esolat()
    ->timeline()
    ->zone('PNG01')
    ->displayAs(1) // must be 1
    ->setDate('2018-10-10') // if this is not set, it will automatically get current date
    ->fetch();
//
//$response = esolat()
//    ->timeline()
//    ->zone('PNG01')
//    ->displayAs(2) // default is 2 (Week)
//    ->fetch();
//
//$data = esolat()
//    ->timeline()
//    ->zone('PNG01')
//    ->displayAs(3)
//    ->month(8) // if this is set, displayAs() will automatically use as type '4'
//    ->year(2018)
//    ->fetch();
//
//$data = esolat()
//    ->timeline()
//    ->zone('PNG01')
//    ->displayAs(4)
//    ->year(2018)
//    ->fetch();

//$response = esolat()->location_list('negeri sembilan');

header('Content-type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
