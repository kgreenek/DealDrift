<?php
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  
  $db = new Database();
  $users = new Users( $db );
?>

<html>
  <head>
    <link rel="stylesheet" href="style/main.css" type="text/css" />
    <title>DealDrift - Settings</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico" /> 
    <meta name="description" content="DealDrift.com - The new way to discover the best deals online" /> 
    <meta name="keywords" content="settings, user, dealdrift, deal, drift, amazon coupons, dell coupons, free after rebate, deals, computer hardware, software, digital camera, iPod, MP3 players, plasma televisions, DVD-R, DVD movies, DVD players, LCD monitors, deal, price, hard drives, printers, wireless networking, computer memory, games, coupon, PC, rebates, HDTV, Compact Flash card, DVD-RW, DVD+RW, notebook computer, laptop" />
  </head>
  <body>
    <div id="main_div">
      <?php include_once( getenv( 'DOCUMENT_ROOT' ) . "/include/header_template.php" ); ?>
        <div id="content_div">
          <div id="settings_div" style="text-align:left; margin:auto; width:700px;">
            <?php
              if( isset( $_POST["submit"] ) )
              {
                echo( '<h3>Settings</h3>' );
                $real_name = $_POST["real_name"];
                
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
                  <form action="' . $PHP_SELF . '" method="post">
                  <b>Name:</b><br />
                  <input type="text" name="real_name" value="' . $real_name . '" size="20" maxlength="35">
                  <p>
                  <b>Website:</b><br />
                  <input type="text" name="remote_website" value="' . $remote_website . '" size="20" maxlength="35">
                  <p>
                  <b>Date of Birth:</b><br />
                  <input type="text" name="date_of_birth" value="' . $date_of_birth . '" size="20" maxlength="35">
                  <p>
                  <b>Gender:</b><br />
                  <input type="text" name="gender" value="' . $gender . '" size="20" maxlength="35">
                  <p>
                  <b>City:</b><br />
                  <input type="text" name="city" value="' . $city . '" size="20" maxlength="35">
                  <p>
                  <b>State:</b><br />
                  <input type="text" name="state" value="' . $state . '" size="20" maxlength="35">
                  <p>
                  <b>Country:</b><br />
                  <input type="text" name="country" value="' . $country . '" size="20" maxlength="35">
                  <p>
                  <b>Timezone:</b><br />
                  <input type="text" name="timezone" value="' . $timezone . '" size="20" maxlength="35">
                  <p>
                  <b>Email Private Messages?:</b><br />
                  <input type="text" name="email_pms" value="' . $email_pms . '" size="20" maxlength="35">
                  <p>
                  <b>Email Updates?:</b><br />
                  <input type="text" name="email_updates" value="' . $email_updates . '" size="20" maxlength="35">
                  <p>
                  <b>Email Weekly Deals?:</b><br />
                  <input type="text" name="email_weekly" value="' . $email_weekly . '" size="20" maxlength="35">
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
