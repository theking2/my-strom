Simple my-Strom web application. 
Without telling other too much about our switching behaviour.

    create database mystrom;
    create user mystrom identified by "mystrom";
    grant all privileges on mystrom.* to `mystrom`@`%`;

the db has currently one table in use:

    CREATE TABLE `Switch` (
      `switch_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(32) NOT NULL,
      `ip` int(11) unsigned NOT NULL,
      `room_id` int(11) unsigned NOT NULL,
      PRIMARY KEY (`switch_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SwitchID, SwitchName are clear, RoomID is FK in table Room.
IPaddresses are stored as unsigend int as that is what ipaddresses are 32bit numbers. The view vmSwitch reverses this.


To edit in phpmyadmin use the INET_ATON() format. This will convert for instance 192.168.111.12 in 3232263948

TODO:
Easy editor for adding removing switches!!!
Room function switch on / off all switches in one room (using table Room)
Logging function (table Measurements and for consolidating hourMeasurement)

If someone has a briliant idea how to get from MAC addresses to IPs that would great help the setup. I now assign fixed IPs to the switches


