<?php
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );

  $to = "dealdrift@gmail.com";
  $subject = "Comment Submission";
  $body = "name: " . $_POST["name"] . "\n" .
          "email: " . $_POST["email"] . "\n\n" .
          $_POST["comments"];
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - Feedback Submit</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="feedback submit, feedback, dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( "include/header_template.php" ); ?>
      <div id="content_div">
        <?php
          if( mail( $to, $subject, $body ) )
            echo( "<p>Message successfully sent!</p>" );
          else
            echo( "<p>Message delivery failed...</p>" );
        ?>
      </div>
      <?php include_once( "include/footer_template.php" ); ?>
    </div>
  </body>
</html>
