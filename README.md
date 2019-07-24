Simple my-Strom web application. 
Without telling other too much about our switching behaviour.

the db has currently one table in use:

CREATE TABLE `Switch` (
  `SwitchID` int(11) UNSIGNED NOT NULL,
  `SwitchName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `IPAddr` int(11) UNSIGNED NOT NULL,
  `RoomID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

IPaddresses are stored as unsigend int as that is what ipaddresses are 32bit numbers. The view vmSwitch reverses this.

To edit in phpmyadmin use the INET_ATON() format. This will convert for instance 192.168.111.12 in 3232263948

TODO:
Easy editor for adding removing switches!!!
Room function switch on / off all switches in one room (using table Room)
Logging function (table Measurements and for consolidating hourMeasurement)

If someone has a briliant idea how to get from MAC addresses to IPs that would great help the setup. I now assign fixed IPs to the switches


