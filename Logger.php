<?php


Class Logger {

    function logRequest($request, $timeDifference){
        $method = $request->requestMethod;
        $uri = $request->requestUri;
        $status = $request->redirectStatus;
        $responseTime = round(($timeDifference * 1000),2);

        $log = ($method.'  '. $uri.'  '.$status.'  '.$responseTime.'ms ').PHP_EOL;

        if (getenv()['USER'] == 'vagrant') {
            file_put_contents('/home/vagrant/code/sdg-covid-19-estimator/log.txt', $log, FILE_APPEND | LOCK_EX);
        }else{
            file_put_contents('/home/kuttoh/code/sdg-covid-19-estimator/log.txt', $log, FILE_APPEND | LOCK_EX);
        }
    }
}
