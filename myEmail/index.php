<?php include("inc/sessionStart.php");?>
<?php include("inc/sendemail.php");?>
<?php
  $_SESSION['programName'] = "myEmail";     // Change our program name in a single location (used in display and email)

  $runatUQ = true;                          // Set false if not run on your UQ zone

  if ($runatUQ) {
    require_once "uq/auth.php";             // Include UQ routines that handle UQ single-signon authentication
    auth_require();                         // User must authenticate once per session to continue running script
// Populate associative array containing UQ user details:
//  "user" — the user's UQ username (eg uqxxx or s4xxxxxx)
//  "email" — the user's primary email address
//  "name" — the preferred name for addressing the user, eg "John Smith"
//  "groups" — a list of AD and LDAP groups the user is a member of
    $UQ = auth_get_payload();
  } else {
   $UQ['user']   = "s4544684";
   $UQ['email']  = "s4544684@student.uq.edu.au";
   $UQ['name']   = "Triet Truong";
   $UQ['groups'] = "[No group, run from localhost]";
  }

// This will only be populated if we pressed the submit button on our form ealier
  $sendFlag = filter_input(INPUT_POST,'hiddenField',FILTER_SANITIZE_STRING);

// Write the email if we have been called from the form
  if ($sendFlag == "doit") {
    $from    = $UQ['email'];                                      // Sender (the user)
    $to      = $UQ['email'];                                      // Receiver (retrieve this from your DB)
    $subject = "Hello " . $UQ['name'] . " (" . $UQ['user'] . ")";                            // email subject line
// The actual message body (includes html formatting)
    $msg     = "<h1>Test of PHP code to send html emails</h1>"
             . "<p>Here is the email you asked for!</p>";

    $ok = sendemail($from,$to,$subject,$msg);

// Check if mail was sent, display result later on form
    if (!$ok) {
      $result = $_SESSION['mailerror'];
    } else {
      $result = "OK, check your inbox for " . $UQ['user'];
    }
  } else {
    $result = "";
  }
//IMPORTANT: You must not write anything to the browser before the html header, i.e be careful not to include a single space outside the php tags!
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="Alex Pudmenzky" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Description" content="How to code and theme a jQuery Mobile script." />
    <meta name="keywords" content="UQ, ITEE, Final Year Thesis Topic" />

    <title><?php echo $_SESSION["programName"];?></title>

<?php
// CDN hosted (local fallback for css and js)
// The // at the beginning of each URL cause the files to be fetched over the same protocol as the current page (http: or https:)
?>
    <link rel="stylesheet" href="css/themes/myApp.min.css" />
    <link rel="stylesheet" href="css/themes/jquery.mobile.icons.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" />

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript">
      if (typeof jQuery == 'undefined') {
        document.write(unescape("%3Clink rel='stylesheet' href='css/jquery.mobile.structure-1.4.5.min.css' /%3E"));
        document.write(unescape("%3Cscript src='js/jquery-1.11.1.min.js' type='text/javascript'%3E%3C/script%3E"));
        document.write(unescape("%3Cscript src='js/jquery.mobile-1.4.5.min.js' type='text/javascript'%3E%3C/script%3E"));
      }
    </script>

  </head>
  <body>
    <div data-role="page" data-theme="c">

      <div data-role="header" data-position="fixed" data-theme="c">
        <h1><?php echo $_SESSION["programName"];?></h1>
      </div>

      <div data-role="content" data-theme="c">

        <form id="createemailform" action="index.php" method="post">
          <input type="hidden" name="hiddenField" value="doit">
          <ul data-role="listview" data-inset="true" data-theme="c" data-divider-theme="c">
            <li data-role="list-divider">Send me an email to myself</li>
            <li class="ui-li-static"><?php echo $result;?></li>
            <input class="button" data-icon="mail" name="submit" data-iconpos="right" data-theme="c" value="Submit" type="submit">
          </ul>
        </form>
      </div>

      <div data-role="footer" data-position="fixed" data-theme="c">
        <h1>Sending emails</h1>
      </div>

    </div>
  </body>
</html>