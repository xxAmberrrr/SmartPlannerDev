<?php
/**
 * Created by PhpStorm.
 * User: Amber
 * Date: 13-06-18
 * Time: 11:43
 */

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

// Create an authorized calendar service object
$calendarService = new Google_Service_Calendar( $client );
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
$freebusy->setTimeMin(date('Y-m-d\TH:i:s+00:00'));
$freebusy->setTimeMax('2018-06-25T00:00:00+00:00');
$freebusy->setTimeZone('Europe/Amsterdam');
$freebusy->setItems( $calendarArray );
$createdReq = $calendarService->freebusy->query($freebusy);

// You will see each calendars and if there are any 'busy' items returned between the dates set.
//echo "<pre>";
//print_r($createdReq['calendars']['xxamberrrr12@gmail.com']['busy']);
//echo "</pre>";


$primaryId = 'xxamberrrr12@gmail.com';
$schoolId = 'ihl73aqpljlu9u67srth0657s8@group.calendar.google.com';
$smartPlannerId = '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com';
$workId = 'k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com';
$mt4Id = 'tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com';

$calendars = [$primaryId, $schoolId, $smartPlannerId, $workId, $mt4Id];

//for($i = 0; $i < $calendars; $i++) {
//    echo "<pre>";
//    print_r($calendars[$i]);
//    echo "</pre>";
//}

//for($i = 0; $i < $calendars; $i++) {
//    foreach ($createdReq['calendars'][$calendars[$i]]['busy'] as $calendar) {
//        echo "<pre>";
//        print_r($calendar['start']);
//        echo "</pre>";
//
//        echo "<pre>";
//        print_r($calendar['end']);
//        echo "</pre>";
//    }
//}

$dateStart = randomDate(date('Y-m-d'), '2018-06-17', 'H:i:s', '17:00:00');
$dateEnd = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($dateStart)));

$startFormat = new DateTime($dateStart);
$endFormat = new DateTime($dateEnd);

$startISO = $startFormat->format(DateTime::ATOM);
$endISO = $endFormat->format(DateTime::ATOM);

//print_r($endFormat->format(DateTime::ATOM));

for($i = 0; $i < $calendars; $i++) {
    foreach ($createdReq['calendars'][$calendars[$i]]['busy'] as $calendar) {
        if($calendar['busy']) {
            print 'Busy';
        }
        else {
            print 'Free';
        }

//        echo "<pre>";
//        print_r($calendar);
//        echo "</pre>";
    }
}

//echo 'startiso:';
//print_r($startISO);
//echo '<br>';
//echo 'endiso:';
//print_r($endISO);
//echo '<br>';
//echo 'startcal:';
//print_r($calendar['start']);
//echo '<br>';
//echo 'endcal:';
//print_r($calendar['end']);

