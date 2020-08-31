@echo off
set arg1=%1
set arg2=%2
set arg3=%3

echo param1=%arg1% param2=%arg2%

rem C:\wamp64\bin\php\php5.6.25\php.exe -f C:\wamp64\www\monitorcsg\parseplanstat.php !servernames[%%n]! !planstatrptfiles[%%n]! !planstat_mailbody!

C:\wamp64\bin\php\php5.6.25\php.exe -f C:\wamp64\www\GTSMSC\send_smppclient.php %arg1% %arg2% %arg3%

pause
