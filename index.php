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
    return covid19ImpactEstimator($request->getBody());
});

$router->post('/api/v1/on-covid-19/json', function($request) {
    return covid19ImpactEstimator($request->getBody());
});

$router->post('/api/v1/on-covid-19/xml', function($request) {
    $json = covid19ImpactEstimator($request->getBody());
    return json2xml($json);
});
