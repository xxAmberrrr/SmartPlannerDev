<?php
/**
 * Created by PhpStorm.
 * User: Amber
 * Date: 11-06-18
 * Time: 16:21
 */

require 'quickstart.php';

$event = new Google_Service_Calendar_Event(array(
    'summary' => 'Google I/O 2015',
    'location' => '800 Howard St., San Francisco, CA 94103',
    'description' => 'A chance to hear more about Google\'s developer products.',
    'start' => array(
        'dateTime' => '2018-06-11T09:00:00',
        'timeZone' => 'Europe/Amsterdam',
    ),
    'end' => array(
        'dateTime' => '2018-06-11T17:00:00',
        'timeZone' => 'Europe/Amsterdam',
    ),
    'recurrence' => array(
        'RRULE:FREQ=DAILY;COUNT=2'
    ),
));

$calendarId = '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com';
$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);
