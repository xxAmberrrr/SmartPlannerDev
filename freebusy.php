<?php
/**
 * Created by PhpStorm.
 * User: Amber
 * Date: 13-06-18
 * Time: 11:43
 */

require 'quickstart.php';

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
$freebusy->setTimeMin('2018-06-11T00:00:00-04:00');
$freebusy->setTimeMax('2018-06-15T00:00:00-04:00');
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

for($i = 0; $i < $calendars; $i++) {
    foreach ($createdReq['calendars'][$calendars[$i]]['busy'] as $calendar) {
        echo "<pre>";
        print_r($calendar['start']);
        echo "</pre>";

        echo "<pre>";
        print_r($calendar['end']);
        echo "</pre>";
    }
}