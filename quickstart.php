<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Smart Planner</title>
</head>
<body>

<input type=button onClick="parent.location='index.php'" value="Terug"/>
<br>
<br>
    
</body>
</html>

<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY, Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('client_secret.json');
    //$client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory('credentials.json');
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);

$calendarsSelected = array();

array_push($calendarsSelected, 'xxamberrrr12@gmail.com'); //Amber Hoogland primary
array_push($calendarsSelected, 'ihl73aqpljlu9u67srth0657s8@group.calendar.google.com'); //School
array_push($calendarsSelected, '6o49r0i8sivl2juaaf9h0rld0k@group.calendar.google.com'); //SmartPlanner
array_push($calendarsSelected, 'k6m04v2tp7ortm5ol5kg8clkuk@group.calendar.google.com'); //Werk
array_push($calendarsSelected, 'tl0eblmcdpkitdtgc8fhiocpb0@group.calendar.google.com'); //MT4

$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
  ); 

for($i = 0; $i < count($calendarsSelected); $i++) {
  $results = $service->events->listEvents($calendarsSelected[$i], $optParams);

  foreach($results->getItems() as $event) {
    $start = null;
    $end = null;

    if(isset($event->start->dateTime)) {
      $start = $event->start->dateTime;
    }

    if(isset($event->end->dateTime)) {
      $end = $event->end->dateTime;
    }

    if(empty($start)) {
      if(isset($event->start->date)) {
          $start = $event->start->date;
      }
    }
  
    if(empty($end)) {
      if(isset($event->end->date)) {
          $end = $event->end->date;
      }
    }

    printf("%s (%s)(%s)\n <br>", $event->getSummary(), $start, $end);
  }
}

$pageToken = $calendarList->getNextPageToken();

if ($pageToken) {
    $optParams = array('pageToken' => $pageToken);
    $calendarList = $service->calendarList->listCalendarList($optParams);
}
?>