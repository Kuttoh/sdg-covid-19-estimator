<?php

function covid19ImpactEstimator($data)
{
    $reportedCases = $data['reportedCases'];

    $impactCurrentlyInfected = $reportedCases * 10;
    $severeImpactCurrentlyInfected = $reportedCases * 50;

    $period = $data['timeToElapse'];
    if ($data['periodType'] == 'days') {
        $period = $data['timeToElapse'];
    } elseif ($data['periodType'] == 'weeks') {
        $period = $data['timeToElapse'] * 7;
    } elseif ($data['periodType'] == 'months') {
        $period = $data['timeToElapse'] * 30;
    }

    $factor = intval($period / 3);
    $impactInfectionsByRequestedTime = $impactCurrentlyInfected * pow(2, $factor);
    $severeInfectionsByRequestedTime = $severeImpactCurrentlyInfected * pow(2, $factor);

    $impactSevereCasesByRequestedTime = intval(0.15 * $impactInfectionsByRequestedTime);
    $severeImpactSevereCasesByRequestedTime = intval(0.15 * $severeInfectionsByRequestedTime);

    $expectedBeds = 0.35 * $data['totalHospitalBeds'];
    $impactHospitalBedsByRequestedTime = intval($expectedBeds - $impactSevereCasesByRequestedTime);
    $severeHospitalBedsByRequestedTime = intval($expectedBeds - $severeImpactSevereCasesByRequestedTime);

    $impactCasesForICUByRequestedTime = intval(0.05 * $impactInfectionsByRequestedTime);
    $severeCasesForICUByRequestedTime = intval(0.05 * $severeInfectionsByRequestedTime);

    $impactCasesForVentilatorsByRequestedTime = intval(0.02 * $impactInfectionsByRequestedTime);
    $severeCasesForVentilatorsByRequestedTime = intval(0.02 * $severeInfectionsByRequestedTime);

    $impactDollarsInFlight = intval(($impactInfectionsByRequestedTime * $data['region']['avgDailyIncomePopulation'] * $data['region']['avgDailyIncomeInUSD']) / $period);
    $severeDollarsInFlight = intval(($severeInfectionsByRequestedTime * $data['region']['avgDailyIncomePopulation'] * $data['region']['avgDailyIncomeInUSD']) / $period);

    return [
        'data' => $data,
        'impact' => [
            'currentlyInfected' => $impactCurrentlyInfected,
            'infectionsByRequestedTime' => $impactInfectionsByRequestedTime,
            'severeCasesByRequestedTime' => $impactSevereCasesByRequestedTime,
            'hospitalBedsByRequestedTime' => $impactHospitalBedsByRequestedTime,
            'casesForICUByRequestedTime' => $impactCasesForICUByRequestedTime,
            'casesForVentilatorsByRequestedTime' => $impactCasesForVentilatorsByRequestedTime,
            'dollarsInFlight' => $impactDollarsInFlight
        ],
        'severeImpact' => [
            'currentlyInfected' => $severeImpactCurrentlyInfected,
            'infectionsByRequestedTime' => $severeInfectionsByRequestedTime,
            'severeCasesByRequestedTime' => $severeImpactSevereCasesByRequestedTime,
            'hospitalBedsByRequestedTime' => $severeHospitalBedsByRequestedTime,
            'casesForICUByRequestedTime' => $severeCasesForICUByRequestedTime,
            'casesForVentilatorsByRequestedTime' => $severeCasesForVentilatorsByRequestedTime,
            'dollarsInFlight' => $severeDollarsInFlight
        ]
    ];
}


