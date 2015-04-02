<?php
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - Register</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="register, dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . "/include/header_template.php" ); ?>
        <div id="content_div">
          <div id="register_div" style="text-align:left; margin:auto; width:700px;">
            <?php
              if( isset( $_POST["submit"] ) )
              {
                echo( '<h3>Register With DealDrift</h3>' );
                $user_name = $_POST["user_name"];
                $password1 = $_POST["password1"];
                $password2 = $_POST["password2"];
                $email = $_POST["email"];
                if( $users->register_new( $user_name, $email, $password1, $password2 ) )
                {
                  echo( '<p>Success! Thank you for registering with DealDrift. You will receive a confirmation email shortly.</p>' );
                  if( !$users->send_confirmation_email( $user_name, $email ) )
                    echo( '<p>Failed to send confirmation email!</p>' );
                  $success = true;
                }
                else
                  echo '<font color="red"><h2>' . $users->get_feedback() . '</h2></font>';
              }
              if( !$success )
              {
                echo '<p>
                  Quickly fill in this info and a confirmation email will be sent to you.
                  <p>
                  Your email address and info will never be sold to third parties and will 
                  not be used to contact you from DealDrift.com without express permission. 
                  If this policy changes in the future, the changes will only apply to 
                  future registrations and it will be so noted at that time.
                  <p>
                  <form action="' . $PHP_SELF . '" method="post">
                  <b>User Name:</b><br />
                  <input type="text" name="user_name" value="' . $user_name . '" size="10" maxlength="15">
                  <p>
                  <b>Email (Required - Must be accurate to confirm):</b><br />
                  <input type="text" name="email" value="' . $email . '" size="20" maxlength="35">
                  <p>
                  <b>Password:</b><br />
                  <input type="password" name="password1" value="" size="10" maxlength="15">
                  <p>
                  <b>Password (again):</b><br />
                  <input type="password" name="password2" value="" size="10" maxlength="15">
                  <p>
                  <input type="submit" name="submit" value="Register">
                  </form>';
              }
            ?>
          </div>
        </div>
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . "/include/footer_template.php" ); ?>
    </div>
  </body>
</html>
