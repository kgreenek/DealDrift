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
    <link rel="stylesheet" href="style/driftbar.css" type="text/css" />
    <?php
      if( isset( $_GET["drift"] ) )
        $titleSufix = $_GET["drift"];
      else
        $titleSufix = "Catch the Deal";

      echo "<title>DealDrift - " . $titleSufix . "</title>";
    ?>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
    <script type="text/javascript" src="include/driftbarclick.js"></script>
  </head>
  <body>
    <div id="pagewidth">
      <table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%">
        <tr class="driftbar" height="1%">
          <td class="driftBar">
            <?php include_once( getenv( 'DOCUMENT_ROOT' ) . '/include/driftbar.php' ); ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
              if( isset( $_GET["drift"] ) )
                $driftFrameSrc = $_GET["drift"];
              else
                $driftFrameSrc = "error.php";
              echo "<iframe src=\"" . $driftFrameSrc . "\" allowtransparency=\"true\" frameborder=\"0\" id=\"cf\" sandbox=\"allow-same-origin allow-forms allow-scripts\" scrolling=\"auto\" style=\"width:100%; height:100%\">
                  <p>DealDrift is the new way to discover the best deals online. Click Drift! to start drifting now.</p>
                </iframe>";
            ?>
          </td>
        </td>
      </table>
    </div>
    <?php include_once( getenv( 'DOCUMENT_ROOT' ) . '/include/analytics.php' ); ?>
  </body>
</html>
