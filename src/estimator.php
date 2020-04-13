<?php

function covid19ImpactEstimator($data)
{
    $input = json_decode($data);

    $reportedCases = $input->reportedCases;

    $impactCurrentlyInfected = $reportedCases * 10;
    $severeImpactCurrentlyInfected = $reportedCases * 50;

    $period = $input->timeToElapse;
    if ($input->periodType == 'days') {
        $period = $input->timeToElapse;
    } elseif ($input->periodType == 'weeks') {
        $period = $input->timeToElapse * 7;
    } elseif ($input->periodType == 'months') {
        $period = $input->timeToElapse * 30;
    }

    $factor = intval($period / 3);
    $impactInfectionsByRequestedTime = intval($impactCurrentlyInfected * pow(2, $factor));
    $severeInfectionsByRequestedTime = intval($severeImpactCurrentlyInfected * pow(2, $factor));

    $impactSevereCasesByRequestedTime = intval(0.15 * $impactInfectionsByRequestedTime);
    $severeImpactSevereCasesByRequestedTime = intval(0.15 * $severeInfectionsByRequestedTime);

    $expectedBeds = intval(0.35 * $input->totalHospitalBeds);
    $impactHospitalBedsByRequestedTime = $expectedBeds - $impactSevereCasesByRequestedTime;
    $severeHospitalBedsByRequestedTime = $expectedBeds - $severeImpactSevereCasesByRequestedTime;

    $impactCasesForICUByRequestedTime = intval(0.05 * $impactInfectionsByRequestedTime);
    $severeCasesForICUByRequestedTime = intval(0.05 * $severeInfectionsByRequestedTime);

    $impactCasesForVentilatorsByRequestedTime = intval(0.02 * $impactInfectionsByRequestedTime);
    $severeCasesForVentilatorsByRequestedTime = intval(0.02 * $severeInfectionsByRequestedTime);

    $impactDollarsInFlight = intval(($impactInfectionsByRequestedTime * $input->region->avgDailyIncomePopulation * $input->region->avgDailyIncomeInUSD) / $period);
    $severeDollarsInFlight = intval(($severeInfectionsByRequestedTime * $input->region->avgDailyIncomePopulation * $input->region->avgDailyIncomeInUSD) / $period);

    $output = [
        'data' => $input,
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

    return json_encode($output);
}


