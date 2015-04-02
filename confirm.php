<?php

  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - Confirm Email</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="confirm, email, dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . "/include/header_template.php" ); ?>
        <div id="content_div">
          <div id="confirm_div" style="text-align:left; margin:auto; width:700px;">
            <?php
              if( isset( $_GET["hash"] ) && isset( $_GET["email"] ) && isset( $_GET["user_name"] ) )
              {
                $hash = $_GET["hash"];
                $email = $_GET["email"];
                $user_name = $_GET["user_name"];
                $worked = $users->confirm_email( $user_name, $email, $hash );
                if( !$worked )
                  echo '<font color="red"><h2>' . $users->get_feedback() . '</h2></font>';
              } 
              else
                echo( '<font color="red"><h2>Error: Missing Params</h2></font>' );

              if( $worked )
                echo( '<p>Success! Your account is confirmed. Now you can login and begin drifting.</p>' );
              else
              {
                echo '<p><h1>Having Trouble Confirming?</h1>
                <p>Try the <a href="http://dealdrift.com/changeemail.php">Change Your Email Address</a> 
                page to receive a new confirmation email.
                <p>If the problem persists, <a href="http://dealdrift.com/feedback.php">Contact Us</a> 
                so we can fix it';
              }
            ?>
          </div>
        </div>
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . "/include/footer_template.php" ); ?>
    </div>
  </body>
</html>
