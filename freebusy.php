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
$freebusy->setTimeMax('2018-06-25T00:00:00+02:00');
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

$dateStart = randomDate('2018-06-18', '2018-06-21', '09:00:00', '17:00:00');
$dateEnd = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($dateStart)));

$startFormat = new DateTime($dateStart, new DateTimeZone('Europe/Amsterdam'));
$endFormat = new DateTime($dateEnd, new DateTimeZone('Europe/Amsterdam'));

$dateStartISO = $startFormat->format('c');
$dateEndISO = $endFormat->format('c');

//$startISO = date('c', strtotime($dateStart));

//print_r($endFormat->format(DateTime::ATOM));

//        echo "<pre>";
//        echo 'startiso: ';
//        print_r($startISO);
//        echo "</pre>";

//        echo "<pre>";
//        echo 'endiso: ';
//        print_r($endISO);
//        echo "</pre>";

$busy = false;
for($i = 0; $i < 2; $i++) {
    for ($j = 0; $j < $calendars; $j++) {
//
//        echo "<pre>";
//        print_r($createdReq['calendars'][$calendars[$j]]['busy']);
//        echo "</pre>";

        foreach ($createdReq['calendars'][$calendars[$i]]['busy'] as $calendar) {
////        if($endISO > $calendar['start'] && $startISO < $calendar['end']) {
////            print 'Busy';
////            $busy = true;
////        }
////        elseif($endISO > $calendar['end'] && $endISO < $calendar['end']) {
////            print 'Busy';
////            $busy = true;
////        }
////        elseif($startISO < $calendar['start'] && $endISO > $calendar['start']) {
////            print 'Busy';
////            $busy = true;
////        }
////        elseif($startISO < $calendar['end'] && $endISO > $calendar['end']) {
////            print 'Busy';
////            $busy = true;
////        }
////        else {
////            print 'Free';
////            $busy = false;
////        }
////
//
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

            echo $busy ? 'true' : 'false';

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

//        foreach ($calendar['start'] as $start) {
////            foreach ($calendar['end'] as $end) {
////                if ($endISO > $start && $startISO < $end) {
////                    $busy = true;
////                }
////                elseif($endISO > $end && $endISO < $end) {
////                    $busy = true;
////                }
////                elseif($startISO < $start && $endISO > $start) {
////                    $busy = true;
////                }
////                elseif($startISO < $end && $endISO > $end) {
////                    $busy = true;
////                }
////            }
//
//            echo "<pre>";
//            print_r($start);
//            echo "</pre>";
//
//        }
            //}
        }
    }
}

//echo $busy ? 'true' : 'false';


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

