<?php
/**
 * Created by PhpStorm.
 * User: Amber
 * Date: 13-06-18
 * Time: 11:18
 */

require 'quickstart.php';

//$client = getClient();
$service = new Google_Service_Calendar($client);

$calendarId = '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com'; //SmartPlanner

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

// Create an authorized calendar service object
$calendarList = $service->calendarList->listCalendarList();
$calendarArray = [];

// Put together our calendar array
while(true) {
    foreach ($calendarList->getItems() as $calendarListEntry) {
        $calendarArray[] = ['id' => $calendarListEntry->id ];
    }
    $pageToken = $calendarList->getNextPageToken();
    if ($pageToken) {
        $optParams = array('pageToken' => $pageToken);
        $calendarList = $service->calendarList->listCalendarList($optParams);
    } else {
        break;
    }
}

// Make our Freebusy request
$freebusy = new Google_Service_Calendar_FreeBusyRequest();
$freebusy->setTimeMin(date('Y-m-d\TH:i:s+00:00'));
$freebusy->setTimeMax('2018-06-17T00:00:00+00:00');
$freebusy->setTimeZone('Europe/Amsterdam');
$freebusy->setItems( $calendarArray );
$createdReq = $service->freebusy->query($freebusy);

$primaryId = 'xxamberrrr12@gmail.com';
$schoolId = 'ihl73aqpljlu9u67srth0657s8@group.calendar.google.com';
$smartPlannerId = '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com';
$workId = 'k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com';
$mt4Id = 'tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com';

$calendars = [$primaryId, $schoolId, $smartPlannerId, $workId, $mt4Id];

//for($i = 0; $i < 2; $i++) {
$dateStart = randomDate(date('Y-m-d'), '2018-06-21', '09:00:00', '17:00:00');
$dateEnd = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($dateStart)));

$dateStartToTime = new DateTime($dateStart, new DateTimeZone('Europe/Amsterdam'));
$dateStartISO = $dateStartToTime->format('c');

$dateEndToTime = new DateTime($dateEnd, new DateTimeZone('Europe/Amsterdam'));
$dateEndISO = $dateEndToTime->format('c');

$busy = false;

for($j = 0; $j < $calendars; $j++) {
    echo "<pre>";
    print_r($createdReq['calendars']);
    echo "</pre>";


    foreach ($createdReq['calendars'][$calendars[$j]]['busy'] as $calendar) {
        printf('B');
        if ($dateStartISO > $calendar['start'] && $dateStartISO < $calendar['end']) {
            $busy = true;
        } elseif ($dateEndISO > $calendar['start'] && $dateEndISO < $calendar['end']) {
            $busy = true;
        } elseif ($calendar['start'] > $dateStartISO && $calendar['start'] < $dateEndISO) {
            $busy = true;
        } elseif ($calendar['end'] > $dateStartISO && $calendar['end'] < $dateEndISO) {
            $busy = true;
        } else {
            $busy = false;
        }

        $event = new Google_Service_Calendar_Event(array(
            'summary' => 'test',
            'start' => array(
                'dateTime' => $dateStart,
                'timeZone' => 'Europe/Amsterdam',
            ),
            'end' => array(
                'dateTime' => $dateEnd,
                'timeZone' => 'Europe/Amsterdam',
            ),
        ));

        printf('Overlap: ' . $busy);

        if (!$busy) {
            $event = $service->events->insert($calendarId, $event);
            printf('Events have been created');
        } else {
            printf('Events can not be created');
        }
    }
}
//}

