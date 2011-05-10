<?php
/*
This script was downloaded at:
LightPHPScripts.com
Please support us by visiting
out website and letting people
know of it.
Produced under: LGPL
*/

/* Main Options */
//----------------

/* Which page user goes to after logoss */
$logoutPage = 'login.php';

/* Secure page to redirect to after login */
$loginPage = 'example.php';

/* Start session? Set this to false, if
you are already starting the session elsewhere
*/
$startSession = TRUE;

/* Use Cookies with sessions*/
$useCookies = TRUE;

/* Stay loged in for? -> cookies */
/* in seconds:
3600 ->  1 hr, 86400 -> 1 day
604800 -> 1 week, 2419200 -> 1 month
29030400 -> 1 year
*/
$logedInFor = 2419200;

/* Domain name -> cookies */
$domainName = 'sno.wamunity.com';

/*
Notes: Please note that using sessions,
will store a cookie with the ID on userside.
To make this work for users without cookies,
propagate the ID through the URLS
in this manner: 
nextpage.php?<?php echo htmlspecialchars(SID); ?>
*/

/* Connect to database? Set to false, if you
are already conneted */
$connectDatabase = TRUE;

/* Database Info */
$databaseUserName = 'waterto_sno';
$databaseUserPassword = 'SNoctopus11';
$databaseHostName = 'localhost';
$databaseName = 'waterto_sno';

/* Table Info */
$tableName = 'userlist';
$userNameField = 'user_name';
$userPasswordField = 'user_password';

/** SEC 334 **/

define('TWITTER_CONSUMER_KEY', '87l3QJ3z5UYrGEI6njrekA');
define('TWITTER_CONSUMER_SECRET', '2wiFiQ79tjTBPVHC6mo6dDtIUhfPQDdfPYZTFOGg');
define('TWITTER_OAUTH_CALLBACK', 'http://sno.wamunity.com/build/plugins/twitter/sno_twitter.php');

?>