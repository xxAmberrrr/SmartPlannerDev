<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Smart Planner</title>
</head>
<body>

<form action="getWorkTime.php" method="post">
    Start tijd: 
    <br>
    <input type="time" name="starting-time"/>
    <br>
    <br>
    Eind tijd: 
    <br>
    <input type="time" name="ending-time"/>
    <br>
    <br>
    <input type="submit" value="Opslaan"/>
</form>

<input type=button onClick="parent.location='index.php'" value="Terug"/>
    
</body>
</html>