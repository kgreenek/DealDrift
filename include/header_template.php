<div id="banner_div">
  <a href="http://dealdrift.com/index.php"><img src="images/DealDrift_Banner.jpg"></a>
</div>
<div id="account_bar_div">
  <?php
    if( $users->is_authorized() )
      echo( '<a href="http://dealdrift.com/settings.php">settings</a>  |  <a href="http://dealdrift.com/login.php?logout=1">logout</a>' );
    else
      echo( '<a href="http://dealdrift.com/login.php">login</a>  |  <a href="http://dealdrift.com/register.php">sign up</a>' );
  ?>
</div>
