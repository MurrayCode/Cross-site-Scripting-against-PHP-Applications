@ECHO OFF
Rem Original App by Dr Colin Mclean designed for remote code excecution 
Rem New App by Murray Stewart designed for XSS against PHP applications

CLS

Rem make folder tmp and db_backup_restore if they dont exist
if not exist "tmp" mkdir tmp
if not exist "db_backup_restore" mkdir db_backup_restore
if not exist %cd%\core\apache2\logs mkdir %cd%\core\apache2\logs

Rem if webid then alter a file.  www\includes\config.inc.php must have path the www.
set mypath=%cd%\phpapps\webid\includes\config.inc.php
set mypath2=%cd%\www\
set mypath2=%mypath2:\=\\%

echo ^<^?php $DbHost = ^"localhost^"; $DbDatabase = ^"webid^"; $DbUser	 = ^"root^"; $DbPassword = ^"hacklab2019^"; $DBPrefix	= ^"webid_^"; $main_path	= ^"%mypath2%"; $MD5_PREFIX = "7548744d014f15c0add4a958f338053c^"; ^?^> >%mypath%

ECHO ...............................................................................
ECHO ** Original Application - Remotely exploitable PHP/MySQL Applications on a USB stick - By Doctor_Hacker.
ECHO ** New Application - PHP applications Vulnerable to Cross-site Scripting - By Murray Stewart
ECHO ...............................................................................
ECHO This batch file will install a PHP/MySQL app that is vulnerable to Cross-site Scripting under UniServerZ.
ECHO (1) The apps can be exploited manually (e.g. by walking through the app and exploiting).
ECHO (2) They can be exploited using exploits that are available on sites such as exploit-db.com and github.
ECHO You could also write your own exploit.  
ECHO Use at your own risk but DO NOT use this on a live system.
ECHO Default Admin Credentials - Username - Admin - Password - password
ECHO This is for educational purposes only.
ECHO ...............................................................................
ECHO.
ECHO a - DBHcms - Stored and Reflected XSS Available
ECHO b - osTicket - Stored XSS Available
ECHO c - CodoForum - Reflected XSS Available
ECHO d - Simple Machines Forum - Reflected XSS Available
ECHO e - Bilbo Planet - Stored XSS AVailable
ECHO f - insanely simple blog - Reflected XSS Available
ECHO g - brim - Stored XSS Available
ECHO h - BlogPHPv2 - Reflected XSS Available
ECHO i - gekkocms - Reflected and Stored XSS Available
ECHO j - UL_Forum - Stored XSS Available
ECHO x - EXIT
ECHO.
rem links choice to folder of php app and installs it M = folder d = database 
CHOICE /C abcdefghijklx /N /M "Choose the PHP app that you want to install under UniServerZ or press x to EXIT."     
IF ERRORLEVEL 1 SET M=DBHcms & SET d=null
IF ERRORLEVEL 2 SET M=0eaefbc356d4ed6814dd475145a7f5d2-osTicket-v1.12 & SET d=null
IF ERRORLEVEL 3 SET M=CodoForum & SET d=null
IF ERRORLEVEL 4 SET M=SimpleMachinesForum & SET d=null
IF ERRORLEVEL 5 SET M=bilboplanet & SET d=null
IF ERRORLEVEL 6 SET M=insanely_simple_blog0.5 & SET d=insanely_simple_blog
IF ERRORLEVEL 7 SET M=brim & SET d=null
IF ERRORLEVEL 8 SET M=BlogPHPv2 & SET d=null
IF ERRORLEVEL 9 SET M=gekkocms & SET d=null
IF ERRORLEVEL 10 SET M=UL_Forum_1.1 & SET d=null
IF ERRORLEVEL 11 GOTO:EOF



Rem Delete everything in the www folder.
echo ***** Deleting and re-creating the www folder.
rmdir www /S /Q
mkdir www

Rem now copy the required folder contents to the www folder.
echo ***** Copying files to www folder. This may take a minute...
xcopy .\phpapps\%M% .\www\  /s /e /q

echo ***** Ready to run Unicontroller 
Pause

Rem now start Unicontroller if not already started. 
tasklist /nh /fi "imagename eq UniController.exe" | find /i "UniController.exe" > nul || (echo Running Unicontroller - it should start Apache and MySQL automatically - unless another app is running on port 80 && start .\UniController.exe pc_win_start)

Rem Small delay to wait for services to start.
PING localhost -n 5 >NUL

echo ***** Ready to import the MySQL database.
Pause
Rem Import the database if needed. Not important that the password is here!
if NOT %d% == null ( 
echo ***** Importing MySQL database....
.\core\mysql\bin\mysql.exe -uroot %d% -phacklab2019 < sql\%d%.sql
)

echo ***** You can now browse to 127.0.0.1 to see the vulnerable application.
pause
