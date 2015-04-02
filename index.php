<!-- // With this declared, setting table/div height="100%" does not work.
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
-->

<?php
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - The new way to find deals</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - Let the deals come to you" /> 
    <meta name="keywords" content="dealdrift, deal, drift, stumble, stumbleupon, deals, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( "include/header_template.php" ); ?>
        <div id="content_div">
          <div id="above_fold_div">
            <div id="key_features">
              <p>The new way to find deals.<br />Click Start Drifting! to begin.</p>
              <form name="startDriftingForm" action="drift.php" method="Post">
                <input type="submit" name="driftButton" value="Start Drifting!" />
              </form>
            </div>
          </div>
          <div id="below_fold_div">
            <div id="extension_download_div">
            
            </div>
            <div id="top_drifters_div">
            
            </div>
            <div id="top_tags">
            
            </div>
          </div>
        </div>
      <?php include_once( "include/footer_template.php" ); ?>
    </div>
  </body>
</html>
