<?php
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - Feedback</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="feedback, dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( "include/header_template.php" ); ?>
      <div id="content_div">
        <form id="comments_form" action="feedbacksubmit.php" method="post">
          <input type="text" name="name" value="your name (optional)" size="32" onclick="if( this.value=='your name (optional)' ) this.value='';" onblur="if( this.value=='' ) this.value='your name (optional)';"/> <br />
          <input type="text" name="email" value="your email (optional)" size="32" onclick="if( this.value=='your email (optional)' ) this.value='';" onblur="if( this.value=='' ) this.value='your email (optional)';"/> <br />
          <textarea name="comments" rows="8" cols="30" style="max-width:700px;">comments</textarea> <br />
          <input type="submit" value="Send Comments!">
        </form>
      </div>
      <?php include_once( "include/footer_template.php" ); ?>
    </div>
  </body>
</html>

