<?php

include_once 'Request.php';
include_once 'Router.php';
include_once 'src/estimator.php';
include_once 'src/json2xml.php';

$router = new Router(new Request);

$router->get('/', function() {
    return <<<HTML
  <h2>Covid 19 Impact Estimator #BuildForSDG Challenge 2020</h2>
HTML;
});

$router->post('/api/v1/on-covid-19', function($request) {
    $data = json_decode($request->getBody(), true);
    return json_encode(covid19ImpactEstimator($data), true);
});

$router->post('/api/v1/on-covid-19/json', function($request) {
    $data = json_decode($request->getBody(), true);
    return json_encode(covid19ImpactEstimator($data), true);
});

$router->post('/api/v1/on-covid-19/xml', function($request) {
    $data = json_decode($request->getBody(), true);
    $json = json_encode(covid19ImpactEstimator($data, true));
    return json2xml($json);
});
