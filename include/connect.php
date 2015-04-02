<?php
  // Connect to the server localhost using username dealdrift and password ruJE2REb
  $connection = mysql_connect( "driftdb.db.5688615.hostedresource.com", "driftdb", "ruJE2REb" );

  // Check if connected to the server
  if( !$connection )
  {
    die( 'Could not connect: ' . mysql_error() );
  }

  // Open the database driftdb
  mysql_select_db( "driftdb", $connection );
?>