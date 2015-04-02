<?php

  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );

  class DriftedSites
  {
    private $sql_install_query = "
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
    private $feedback = '';
    private $db = null;
    
    public function __construct( $database )
    {
      $this->db = $database;
    }
    
    public function create_table()
    {
      // Create the table if it does not exist.
      $this->feedback = $this->db->query( $sql_install_query );
      return $this->feedback;
    }
    
    public function add( $site_url, $tags, $users )
    {
      // Make sure the input value is valid before putting it into the table.
      if( !filter_var( $site_url, FILTER_SANITIZE_URL ) || !filter_var( $site_url, FILTER_VALIDATE_URL ))
      {
        $this->feedback = "Invalid site_url ";
        return false;
      }
      
      // If the url is not already in the database.
      // Assumes there exist entries in the database.
      $result = $this->db->query( "SELECT * FROM drifted_sites WHERE site_url = '" . mysql_real_escape_string( trim( $site_url ) ) . "'" );
      if( mysql_num_rows( $result ) < 1 )
      {
        $discovering_user = $users->get_user_id();
        if( $discovering_user == 0 )
          $discovering_user = 1;
        
        // Add the new entry to the table.
        $this->db->query( "INSERT INTO drifted_sites (site_url, discovering_user) VALUES ('" . mysql_real_escape_string( trim( $site_url ) ) . "', '" . $discovering_user . "')" );
      }
      
      // Add tags.
      
      return true;
    }
    
    public function drift( $users )
    {
      // Choose the next site to drift.
      // For now, choose a random entry to drift next.
      $siteIds = $this->db->query( "SELECT site_id FROM drifted_sites" );
      $numRows = mysql_num_rows( $siteIds );
      $nextDriftIndex = rand( 0, $numRows - 1 );
      $result = $this->db->query( "SELECT * FROM drifted_sites LIMIT " . $nextDriftIndex . ", 1" );
      
      if( mysql_num_rows( $result ) > 0 )
      {
        $row = mysql_fetch_array( $result );
        
        // Update num_drifts count.
        $this->db->query( "UPDATE drifted_sites SET num_drifts=num_drifts + 1 WHERE site_id='" . $row["site_id"] . "'" );
        return $row;
			}
      
      $this->feedback = "Could not drift ";
      return null;
    }
    
    public function like( $site_id )
    {
      if( filter_var( $site_id, FILTER_VALIDATE_INT ) )
        $this->db->query( "UPDATE drifted_sites SET num_likes=num_likes + 1 WHERE site_id='$site_id'" );
      else
      {
        $this->feedback = "site_id invalid ";
        return false;
      }
      return true;
    }
    
    public function dislike( $site_id )
    {
      if( filter_var( $site_id, FILTER_VALIDATE_INT ) )
        $this->db->query( "UPDATE drifted_sites SET num_dislikes=num_dislikes + 1 WHERE site_id='$site_id'" );
      else
      {
        $this->feedback = "site_id invalid ";
        return false;
      }
      return true;
    }
    
    public function dead( $site_id )
    {
      if( filter_var( $site_id, FILTER_VALIDATE_INT ) )
        $this->db->query( "UPDATE drifted_sites SET num_deaths=num_deaths + 1 WHERE site_id='$site_id'" );
      else
      {
        $this->feedback = "site_id invalid ";
        return false;
      }
      return true;
    }
  }

?>