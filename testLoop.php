<?php

require 'quickstart.php';

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
$calService = new Google_Service_Calendar($client);
$calList = $calService->calendarList->listCalendarList();
$calArray = [];

while(true) {
    foreach ($calList->getItems() as $calListEntry) {
        $calArray[] = ['id' => $calListEntry->id];
    }
    $pageToken = $calList->getNextPageToken();
    if($pageToken) {
        $optParams = array('pageToken' => $pageToken);
        $calList = $calService->calendarList->listCalendarList($optParams);
    } else {
        break;
    }
}

$dateTimeNow = new DateTime('now', new DateTimeZone('Europe/Amsterdam'));
$dateTimeMax = new DateTime('2018-06-25T17:00:00', new DateTimeZone('Europe/Amsterdam'));

$freebusy = new Google_Service_Calendar_FreeBusyRequest();
$freebusy->setTimeMin($dateTimeNow->format('c'));
$freebusy->setTimeMax($dateTimeMax->format('c'));
$freebusy->setTimeZone('Europe/Amsterdam');
$freebusy->setItems($calArray);
$createdReq = $calService->freebusy->query($freebusy);

$primaryId = 'xxamberrrr12@gmail.com';
$schoolId = 'ihl73aqpljlu9u67srth0657s8@group.calendar.google.com';
$smartPlannerId = '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com';
$workId = 'k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com';
$mt4Id = 'tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com';

$calendars = [$primaryId, $schoolId, $smartPlannerId, $workId, $mt4Id];

$calendarId = 'g4eho8b6pjgftcv3ll6uqs9328@group.calendar.google.com'; //SmartPlanner

for($j = 0; $j < 2; $j++) {
    $dateStart = randomDate('2018-06-19', '2018-06-22', '09:00:00', '17:00:00');
    $dateEnd = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($dateStart)));

    $startDateTime = new DateTime($dateStart, new DateTimeZone('Europe/Amsterdam'));
    $endDateTime = new DateTime($dateEnd, new DateTimeZone('Europe/Amsterdam'));

    $dateStartISO = $startDateTime->format('c');
    $dateEndISO = $endDateTime->format('c');

    $busy = false;

    for ($i = 0; $i < $calendars; $i++) {
        foreach ($calendars[$i] as $cal) {
            foreach ($createdReq['calendars'][$cal]['busy'] as $calendar) {
                if ($dateStartISO > $calendar['start'] && $dateStartISO < $calendar['end']) {
                    echo 'Busy';
                    $busy = true;
                } elseif ($dateEndISO > $calendar['start'] && $dateEndISO < $calendar['end']) {
                    echo 'Busy';
                    $busy = true;
                } elseif ($calendar['start'] > $dateStartISO && $calendar['start'] < $dateEndISO) {
                    echo 'Busy';
                    $busy = true;
                } elseif ($calendar['end'] > $dateStartISO && $calendar['end'] < $dateEndISO) {
                    echo 'Busy';
                    $busy = true;
                } else {
                    echo 'Free';
                    $busy = false;
                }

                echo "<pre>";
                echo 'isostart:';
                print_r($dateStartISO);
                echo "</pre>";

                echo "<pre>";
                echo 'isoend:';
                print_r($dateEndISO);
                echo "</pre>";

                echo "<pre>";
                echo 'calendarstart:';
                print_r($calendar['start']);
                echo "</pre>";

                echo "<pre>";
                echo 'calendarend: ';
                print_r($calendar['end']);
                echo "</pre>";

            }
        }
    }
}

