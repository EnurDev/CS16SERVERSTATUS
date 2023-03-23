<!DOCTYPE html>
<html>
<head>
	<title>CS 1.6 Server Status</title>
</head>
<body>
<?php
    $server_ip = "YOUR_SERVER_IP";
    $server_port = "27015"; // Default port for CS 1.6

    $socket = @fsockopen("udp://" . $server_ip, $server_port , $errno, $errstr, 1);

    if (!$socket) {
        echo "Server is offline";
    } else {
        $data = "\xFF\xFF\xFF\xFFTSource Engine Query\x00";
        fwrite($socket, $data);
        $response = fread($socket, 4096);
        fclose($socket);

        if (substr($response, 0, 4) == "\xFF\xFF\xFF\xFF") {
            $response = substr($response, 6);
            $parts = explode("\x00", $response);
            $server_name = $parts[0];
            $map_name = $parts[1];
            $current_players = ord($parts[5]);
            $max_players = 32;
            $server_type = $parts[3];
            $player_count = $current_players . "/" . $max_players;

            echo "<h1>Server is online</h1>";
            echo "<p>Server Name: " . $server_name . "</p>";
            echo "<p>IP Address: " . $server_ip . ":" . $server_port . "</p>";
            echo "<p>Player count: " . $player_count . "</p>";
            echo "<p>Map info: " . $map_name . "</p>";
            echo "<p>Server type: " . $server_type . "</p>";

        } else {
            echo "Invalid response from server";
        }
    }
?>

</body>
</html>
