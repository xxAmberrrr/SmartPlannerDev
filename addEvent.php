<?php

require 'quickstart.php';
//require 'index.php';

function randomDate($minDate, $maxDate, $minTime, $maxTime) {

    $minEpoch = strtotime($minDate);
    $maxEpoch = strtotime($maxDate);
    $minTimeEpoch = strtotime($minTime);
    $maxTimeEpoch = strtotime($maxTime);

    $randomEpoch = rand($minEpoch, $maxEpoch);
    $randomTimeEpoch = rand($minTimeEpoch, $maxTimeEpoch);

    $date = date('Y-m-d', $randomEpoch);
    $time = date('H:i:s', $randomTimeEpoch);

    return $date . 'T' . $time;

}

$client = getClient();
$service = new Google_Service_Calendar($client);

$freebusy = new Google_Service_Calendar_FreeBusyRequest();
$freebusy->setTimeMin(date('Y-m-d\TH:i:s'));
$freebusy->setTimeMax($_POST['deadline']);
$freebusy->setTimeZone('Europe/Amsterdam');

$primaryCal = new Google_Service_Calendar_FreeBusyRequestItem();
$primaryCal->setId('xxamberrrr12@gmail.com');

$schoolCal = new Google_Service_Calendar_FreeBusyRequestItem();
$schoolCal->setId('ihl73aqpljlu9u67srth0657s8@group.calendar.google.com');

$smartPlannerCal = new Google_Service_Calendar_FreeBusyRequestItem();
$smartPlannerCal->setId('6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com');

$workCal = new Google_Service_Calendar_FreeBusyRequestItem();
$workCal->setId('k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com');

$MT4Cal = new Google_Service_Calendar_FreeBusyRequestItem();
$MT4Cal->setId('tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com');

$freebusy->setItems(array($primaryCal, $schoolCal, $smartPlannerCal, $workCal, $MT4Cal));

$query = $service->freebusy->query($freebusy);

$queryPrimary = $query->getCalendars()['xxamberrrr12@gmail.com']['modelData']['busy'];
$querySchool = $query->getCalendars()['ihl73aqpljlu9u67srth0657s8@group.calendar.google.com']['modelData']['busy'];
$querySmartPlanner = $query->getCalendars()['6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com']['modelData']['busy'];
$queryWork = $query->getCalendars()['k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com']['modelData']['busy'];
$queryMT4 = $query->getCalendars()['tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com']['modelData']['busy'];

//$scheduleStart = [];
//$scheduleEnd = [];

for($i = 0; $i < count($queryPrimary); $i++) {
    array_push($scheduleStart, $queryPrimary[$i]["start"]);
    array_push($scheduleStart, $queryPrimary[$i]["end"]);
}

for($i = 0; $i < count($querySchool); $i++) {
    array_push($scheduleStart, $querySchool[$i]["start"]);
    array_push($scheduleStart, $querySchool[$i]["end"]);
}

for($i = 0; $i < count($querySmartPlanner); $i++) {
    array_push($scheduleStart, $querySmartPlanner[$i]["start"]);
    array_push($scheduleStart, $querySmartPlanner[$i]["end"]);
}

for($i = 0; $i < count($queryWork); $i++) {
    array_push($scheduleStart, $queryWork[$i]["start"]);
    array_push($scheduleStart, $queryWork[$i]["end"]);
}

for($i = 0; $i < count($queryMT4); $i++) {
    array_push($scheduleStart, $queryMT4[$i]["start"]);
    array_push($scheduleStart, $queryMT4[$i]["end"]);
}

for($i = 0; $i < 2; $i++) {
    $dateStart = randomDate(date('Y-m-d'), '2018-06-18', '09:00:00', '17:00:00');
    $dateEnd = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($dateStart)));

    $dateStartToTime = new DateTime($dateStart);
    $dateStartUnix = $dateStartToTime->getTimestamp();

    $dateEndToTime = new DateTime($dateEnd);
    $dateEndUnix = $dateEndToTime->getTimestamp();

    $overlap = false;

    $amount = min(count($scheduleStart), count($scheduleEnd));

    for ($j = 0; $j < $amount; $j++) {
        $scheduleStartToTime = new DateTime($scheduleStart[$j]);
        $scheduleStartUnix = $scheduleStartToTime->getTimeStamp();

        $scheduleEndToTime = new DateTime($scheduleEnd[$j]);
        $scheduleEndUnix = $scheduleEndToTime->getTimestamp();

        if ($dateStartUnix > $scheduleStartUnix && $dateStartUnix < $scheduleEndUnix) {
            $overlap = true;
            var_dump($dateStartUnix);
            var_dump($scheduleStartUnix);
            var_dump($scheduleEndUnix);
        } elseif ($dateEndUnix > $scheduleEndUnix && $dateEndUnix < $scheduleEndUnix) {
            $overlap = true;
            var_dump($dateStartUnix);
            var_dump($dateEndUnix);
            var_dump($scheduleStartUnix);
            var_dump($scheduleEndUnix);
        } elseif ($dateStartUnix < $scheduleStartUnix && $dateEndUnix > $scheduleStartUnix) {
            $overlap = true;
            var_dump($dateStartUnix);
            var_dump($scheduleStartUnix);
            var_dump($dateEndUnix);
        } elseif ($dateStartUnix < $scheduleEndUnix && $dateEndUnix > $scheduleEndUnix) {
            $overlap = true;
            var_dump($dateStartUnix);
            var_dump($dateEndUnix);
            var_dump($scheduleEndUnix);
        }
    }

    $event = new Google_Service_Calendar_Event(array(
        'summary' => 'test' . ($overlap ? "Overlaps" : "Doesn't overlap"),
        'start' => array(
            'dateTime' => $dateStart,
            'timeZone' => 'Europe/Amsterdam',
        ),
        'end' => array(
            'dateTime' => $dateEnd,
            'timeZone' => 'Europe/Amsterdam',
        ),
    ));

    if(!$overlap)
    {
        $event = $service->events->insert($calendarId, $event);
        printf('Events have been created');
    }
}

