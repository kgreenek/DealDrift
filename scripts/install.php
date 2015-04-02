<?php 
  // Connect to the database and select driftdb.
  // Connect to the server localhost using username dealdrift and password ruJE2REb
  $connection = mysql_connect( "driftdb.db.5688615.hostedresource.com", "driftdb", "ruJE2REb" );

  // Check if connected to the server
  if( !$connection )
  {
    die( 'Could not connect: ' . mysql_error() );
  }

  // Create the database
  if( mysql_query( "CREATE DATABASE driftdb", $connection ) )
  {
    echo "Database created";
  }
  else
  {
    echo "Error creating database: " . mysql_error() . "<br />";
  }

  // Open the database driftdb
  mysql_select_db( "driftdb", $connection );

  // Write the sql statement to create a table for driftedSites.
  $sql = "
  CREATE TABLE drifted_sites (
    site_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    site_url TEXT NOT NULL DEFAULT '',
    num_drifts INT NOT NULL DEFAULT 0,
    num_likes INT NOT NULL DEFAULT 0,
    num_dislikes INT NOT NULL DEFAULT 0,
    num_deaths INT NOT NULL DEFAULT 0,
    discovery_date TIMESTAMP DEFAULT NOW(),
    discovering_user INT NOT NULL DEFAULT 1
  )";

  // Create the table in driftdb database
  if( mysql_query( $sql, $connection ) )
  {
    echo "Table, driftedSites, Created";
  }
  else
  {
    echo "Error Creating Table, driftedSites: " . mysql_error() . "<br />";
  }
  
  $sql = "
    CREATE TABLE users (
      user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      user_name VARCHAR( 255 ) NOT NULL UNIQUE KEY,
      email VARCHAR( 255 ) NOT NULL UNIQUE KEY,
      password VARCHAR( 255 ) NOT NULL,
      token VARCHAR( 255 ),
      ip VARCHAR( 255 ) NOT NULL,
      auth INT NOT NULL DEFAULT 1, 
      is_confirmed INT( 1 ) NOT NULL DEFAULT 0,
      is_banned INT( 1 ) NOT NULL DEFAULT 0,
      real_name VARCHAR( 255 ),
      remote_website VARCHAR( 255 ),
      date_of_birth DATE,
      gender ENUM( 'M', 'F' ),
      city VARCHAR( 255 ),
      state VARCHAR( 255 ),
      country VARCHAR( 255 ),
      timezone VARCHAR( 255 ),
      email_pms INT( 1 ) NOT NULL DEFAULT 0,
      email_updates INT( 1 ) NOT NULL DEFAULT 0,
      email_weekly INT( 1 ) NOT NULL DEFAULT 0
    )";
  
  // Create the table in driftdb database
  if( mysql_query( $sql, $connection ) )
  {
    echo "Table, users, Created";
  }
  else
  {
    echo "Error Creating Table, users: " . mysql_error() . "<br />";
  }

  // Close the connection
  mysql_close( $connection );
?>
