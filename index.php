<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Smart Planner</title>
</head>
<body>

<form action="addEvent.php" method="post">
    To do:
    <br>
    <input type="text" name="task"/>
    <br>
    <br>
    Werktijd per dag:
    <br>
    <input type="time" name="working-time"/>
    <br>
    <br>
    Deadline:
    <br>
    <input type="datetime-local" name="deadline"/>
    <br>
    <br>
    <input type="submit" value="Taak toevoegen"/>
</form>

<br>
<br>
<input type=button onClick="parent.location='settings.php'" value="settings"/>
<input type=button onClick="parent.location='quickstart.php'" value="Get events"/>
    
</body>
</html>