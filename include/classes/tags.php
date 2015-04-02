<?php

  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  
  class Tags
  {
    private $sql_install_query = "CREATE TABLE tags (
      tag_id INT NOT NULL AUTO_INCREMENT,
      tag_name VARCHAR(255) NOT NULL,
      site_id INT NOT NULL,
      PRIMARY KEY( tag_name ),
      FOREIGN KEY( site_id ) REFERENCES drifted_sites( site_id )
    ) ENGINE=InnoDB";
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
    
    // Returns feedback string from last function called (e.g. any error message).
    public function get_feedback()
    {
      return $this->feedback;
    }
    
    public function add( $site_id, $tag )
    {
      // Clean the input.
      $tag = $this->db->clean( $tag );
      if( !filter_var( $site_id, FILTER_VALIDATE_INT ) )
      {
        $this->feedback = "Invalid site_id ";
        return false;
      }
      
      // Check if the tag is already in the table.
      $result = $this->db->query( "SELECT * FROM tags WHERE tag = $tag && site_id = $site_id" );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $this->feedback = "Site already Tagged ";
        return false;
      }
      
      // Add a new entry to the table.
      $result = $this->db->query( "INSERT INTO tags (tag_name, site_id) VALUES ($tag, $site_id)" );
      if( !$result || mysql_num_rows( $result ) != 1 )
      {
        $this->feedback = "Could not Tag site ";
        return false;
      }
      
      return true;
    }
    
    public function get_sites_tagged_as( $tag )
    {
      $tag = $this->db->clean( $tag );
      $result = $this->db->query( "SELECT * FROM tags WHERE tag = $tag" );
      if( !$result || mysql_num_rows( $result ) < 1 )
      {
        $this->feedback = "No sites found with specified tag ";
        return null;
      }
      return $result;
    }

    public function get_num_sites_tagged_as( $tag )
    {
      $result = $this->get_sites_tagged_as( $tag );
      if( $result )
        return mysql_num_rows( $result );
      else
        return 0;
    }
    
    public function get_tags_for_site( $site_id )
    {
      if( !filter_var( $site_id, FILTER_VALIDATE_INT ) )
      {
        $this->feedback = "Invalid site_id ";
        return false;
      }
      $result = $this->db->query( "SELECT * FROM tags WHERE site_id = $site_id" );
      if( !$result || mysql_num_rows( $result ) < 1 )
      {
        $this->feedback = "No tags found for specified site_id ";
        return null;
      }
      return $result;
    }
  }

?>