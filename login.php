<?php
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );
  
  if( isset( $_GET["logout"] ) && $_GET["logout"] == '1' )
  {
    $users->logout();
    header( "Location: http://dealdrift.com/" );
    exit;
  }
  
  if( isset( $_POST["submit"] ) )
  {
    $user_name = $_POST["user_name"];
    $password = $_POST["password"];
    $result = $users->login( $user_name, $password );
    if( $result )
    {
      header( "Location: http://dealdrift.com/" );
      exit;
    }
    else
    {
      // echo( "result: $result" ); // For debugging.
      $error = true;
    }
  }
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - Login</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="login, dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . '/include/header_template.php' ); ?>
        <div id="content_div">
          <div id="login_div" style="text-align:left; margin:auto; width:700px;">
            <?php if( $error ) echo '<font color="red"><h2>' . $users->get_feedback() . '</h2></font>'; ?>
            <h3>Login to DealDrift</h3>
            <p>
            <form action="<?php echo( $PHP_SELF ); ?>" method="post">
            <b>User Name:</b><br />
            <input type="text" name="user_name" value="<?php echo( $user_name ); ?>" size="10" maxlength="15">
            <p>
            <b>Password:</b><br />
            <input type="password" name="password" value="" size="10" maxlength="15">
            <p>
            <input type="submit" name="submit" value="Login">
            </form>
          </div>
        </div>
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . '/include/footer_template.php' ); ?>
    </div>
  </body>
</html>
