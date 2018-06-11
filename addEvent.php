<?php

require 'quickstart.php';

$client = getClient();
$service = new Google_Service_Calendar($client);

$freebusy = new Google_Service_Calendar_FreeBusyRequest();
$freebusy->setTimeMin($_POST['starting-time']);
$freebusy->setTimeMax($_POST['ending-time']);
$freebusy->setTimeZone('Europe/Amsterdam');

$primaryCal = new Google_Service_Calendar_FreeBusyRequest();
$primaryCal->setId('xxamberrrr12@gmail.com');

$schoolCal = new Google_Service_Calendar_FreeBusyRequest();
$schoolCal->setId('ihl73aqpljlu9u67srth0657s8@group.calendar.google.com');

$smartPlannerCal = new Google_Service_Calendar_FreeBusyRequest();
$smartPlannerCal->setId('6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com');

$workCal = new Google_Service_Calendar_FreeBusyRequest();
$workCal->setId('k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com');

$MT4Cal = new Google_Service_Calendar_FreeBusyRequest();
$MT4Cal->setId('tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com');

$freebusy->setItems(array($primaryCal, $schoolCal, $smartPlannerCal, $workCal, $MT4Cal));

$query = $service->freebusy->query($freebusy);

$queryPrimary = $query->getCalendars()['xxamberrrr12@gmail.com']['modelData']['busy'];
$querySchool = $query->getCalendars()['ihl73aqpljlu9u67srth0657s8@group.calendar.google.com']['modelData']['busy'];
$querySmartPlanner = $query->getCalendars()['6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com']['modelData']['busy'];
$queryWork = $query->getCalendars()['k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com']['modelData']['busy'];
$queryMT4 = $query->getCalendars()['tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com']['modelData']['busy'];

for($i = 0; $i < count($queryPrimary); $i++) {
    $primaryStart = $queryPrimary[$i]["start"];
    $primaryEnd = $queryPrimary[$i]["end"];
}

for($i = 0; $i < count($querySchool); $i++) {
    $schoolStart = $querySchool[$i]["start"];
    $schoolEnd = $querySchool[$i]["end"];
}

for($i = 0; $i < count($querySmartPlanner); $i++) {
    $smartPlannerStart = $querySmartPlanner[$i]["start"];
    $smartPlannerEnd = $querySmartPlanner[$i]["end"];
}

for($i = 0; $i < count($queryWork); $i++) {
    $workStart = $queryWork[$i]["start"];
    $workEnd = $queryWork[$i]["end"];
}

for($i = 0; $i < count($queryMT4); $i++) {
    $MT4Start = $queryMT4[$i]["start"];
    $MT4End = $queryMT4[$i]["end"];
}

var_dump($queryPrimary);
var_dump($querySchool);
var_dump($querySmartPlanner);
var_dump($queryWork);
var_dump($queryMT4);