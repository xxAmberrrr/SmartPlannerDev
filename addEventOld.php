<?php
/**
 * Created by PhpStorm.
 * User: Amber
 * Date: 13-06-18
 * Time: 11:18
 */

require 'quickstart.php';

$client = getClient();
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
$calendarList = $calendarService->calendarList->listCalendarList();
$calendarArray = [];

// Put together our calendar array
while(true) {
    foreach ($calendarList->getItems() as $calendarListEntry) {
        $calendarArray[] = ['id' => $calendarListEntry->id ];
    }
    $pageToken = $calendarList->getNextPageToken();
    if ($pageToken) {
        $optParams = array('pageToken' => $pageToken);
        $calendarList = $calendarService->calendarList->listCalendarList($optParams);
    } else {
        break;
    }
}

// Make our Freebusy request
$freebusy = new Google_Service_Calendar_FreeBusyRequest();
$freebusy->setTimeMin('Y-m-d\TH:i:s');
$freebusy->setTimeMax('2018-06-18');
$freebusy->setTimeZone('Europe/Amsterdam');
$freebusy->setItems( $calendarArray );
$createdReq = $calendarService->freebusy->query($freebusy);

$primaryId = 'xxamberrrr12@gmail.com';
$schoolId = 'ihl73aqpljlu9u67srth0657s8@group.calendar.google.com';
$smartPlannerId = '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com';
$workId = 'k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com';
$mt4Id = 'tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com';

$calendars = [$primaryId, $schoolId, $smartPlannerId, $workId, $mt4Id];

for($i = 0; $i < 2; $i++) {
    $dateStart = randomDate(date('Y-m-d'), '2018-06-18', '09:00:00', '17:00:00');
    $dateEnd = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($dateStart)));

    $dateStartToTime = new DateTime($dateStart);
    $dateStartUnix = $dateStartToTime->getTimestamp();

    $dateEndToTime = new DateTime($dateEnd);
    $dateEndUnix = $dateEndToTime->getTimestamp();

    $overlap = false;

    for($i = 0; $i < $calendars; $i++) {
        foreach ($createdReq['calendars'][$calendars[$i]]['busy'] as $calendar) {
            if($dateStartUnix > $calendar['start'] && $dateStartUnix < $calendar['end']) {
                $overlap = true;
            }
            elseif($dateEndUnix > $calendar['end'] && $dateEndUnix < $calendar['end']) {
                $overlap = true;
            }
            elseif($dateStartUnix < $calendar['start'] && $dateEndUnix > $calendar['end']) {
                $overlap = true;
            }
            elseif($dateStartUnix < $calendar['end'] && $dateEndUnix > $calendar['end']) {
                $overlap = true;
            }
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

    if($overlap = false)
    {
        $event = $service->events->insert($calendarId, $event);
        printf('Events have been created');
    }
}