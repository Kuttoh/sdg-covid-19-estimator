<?php

include_once 'Request.php';
include_once 'Router.php';
include_once 'src/estimator.php';
include_once 'src/json2xml.php';
include_once 'Logger.php';

$router = new Router(new Request);

$router->get('/', function () {
    return <<<HTML
  <h2>Covid 19 Impact Estimator #BuildForSDG Challenge 2020</h2>
HTML;
});

$router->post('/api/v1/on-covid-19', function ($request) {

    $data = json_decode($request->getBody(), true);

    $start = microtime(true);
    $result = json_encode(covid19ImpactEstimator($data), true);
    $end = microtime(true);
    $timeDifference = $end - $start;

    (new Logger())->logRequest($request, $timeDifference);

    header("Content-Type: application/json; charset=utf-8");
    return $result;
});

$router->post('/api/v1/on-covid-19/json', function ($request) {

    $data = json_decode($request->getBody(), true);

    $start = microtime(true);
    $result = json_encode(covid19ImpactEstimator($data), true);
    $end = microtime(true);
    $timeDifference = $end - $start;

    (new Logger())->logRequest($request, $timeDifference);

    header("Content-Type: application/json; charset=utf-8");
    return $result;
});

$router->post('/api/v1/on-covid-19/xml', function ($request) {
    $data = json_decode($request->getBody(), true);

    $start = microtime(true);
    $json = json_encode(covid19ImpactEstimator($data), true);
    $end = microtime(true);
    $timeDifference = $end - $start;

    (new Logger())->logRequest($request, $timeDifference);

    header("Content-type: application/xml; charset=utf-8");
    return json2xml($json);
});

$router->get('/api/v1/on-covid-19/log', function ($request){

    $start = microtime(true);
    $end = microtime(true);
    $timeDifference = $end - $start;

    (new Logger())->logRequest($request, $timeDifference);

    header("Content-Type: application/text; charset=UTF-8");
    if (getenv('USER') == 'vagrant'){
        return gettext(nl2br(file_get_contents('/home/vagrant/code/sdg-covid-19-estimator/log.txt')));
    }
    return gettext(nl2br(file_get_contents('/home/kuttoh/code/sdg-covid-19-estimator/log.txt')));
});
