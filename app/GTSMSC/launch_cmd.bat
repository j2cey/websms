set rawfolder="C:\wamp64\www\GTSMSC\"
set process_pgm=sendsms
SET datetimef=%date:~-4%%date:~3,2%%date:~0,2%%time:~0,2%%time:~3,2%%time:~6,2%%time:~9,2%

CD %rawfolder%
C:

call %process_pgm% DSISMS 24165300354 hello_sms > mytest.log

pause
