**How to run code :**

1- install & setup composer

2- @project path run this command "composer install"

3- setup project in local host

    1- install xampp or any web server
    2- add host in hosts file for example : "127.0.0.1 hotel_task.local" @path (C:\Windows\System32\drivers\etc)
    3- add Virtual Host in "Virtual Hosts" @path (C:\xampp\apache\conf\extra)
      <VirtualHost hotel_task.local:80>
        DocumentRoot "C:\xampp\htdocs\hotel_task"
        ServerName hotel_task.local
      </VirtualHost>
    4- open "http://hotel_task.local" (open in browser or post man)

4- run project tests

There are about 4 tests available . To run tests write this command in powershell or cmd in project path:

`.\vendor\bin\phpunit .\tests\Unit\AdvertisedHotelRoomsTest.php`

