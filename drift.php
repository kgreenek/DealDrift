<?php
  // Connect to the database and select driftdb.
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/users.php' );
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/driftedsites.php' );
	
  $db = new Database();
  $users = new Users( $db );
  $drifted_sites = new DriftedSites( $db );
  
	// If the user just clicked drift.
	// Assumes driftdb is not empty.
	if( isset( $_POST["driftButton"] ) )
	{
    $drift_site = $drifted_sites->drift( $users );
    // Redirect the user to the next drift if valid.
    if( $drift_site != null )
      header( "Location: http://dealdrift.com/dd.php?id=" . $drift_site["site_id"] . "&drift=" . $drift_site["site_url"] );
		else
			header( "Location: http://dealdrift.com/dd.php?drift=error.php" );
		exit;
	}
  else if( isset( $_GET["id"] ) )
  {
    if( $_GET["action"] == "like" )
      $drifted_sites->like( $GET["id"] );
    else if( $_GET["action"] == "dislike" )
      $drifted_sites->dislike( $GET["id"] );
    if( $_GET["action"] == "dead" )
      $drifted_sites->dead( $GET["id"] );
    echo $_GET["action"]; // AJAX response.
  }
	else
	{
		header( "Location: http://dealdrift.com/" );
		exit;
	}
?>
