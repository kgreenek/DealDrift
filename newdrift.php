<?php
  // Connect to the database and select driftdb.
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/driftedsites.php' );
	
  $db = new Database();
  $users = new Users( $db );
  $drifted_sites = new DriftedSites( $db );

	if( isset( $_POST["newUrl"] ) )
	{
    if( $drifted_sites->add( $_POST["newUrl"], $_POST["tags"], $users ) )
    {
      // Redirect the user to the page they just added.
      header( "Location: dd.php?drift=" . $_POST["newUrl"] );
      exit;
    }
    else
    {
      header( "Location: error.php" );
      exit;
    }
	}
  else
  {
    header( "Location: http://www.dealdrift.com/" );
    exit;
  }
?>

<!--
<html>
	<body>
		<form name="newUrlForm" action="newdrift.php" method="post">
			<p>URL: </p> <input type="text" name="newUrl" />
			<input type="submit" name="submit" />
		</form>
	</body>
</html>
-->
