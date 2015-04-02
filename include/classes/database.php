<?php  
  // database.php
  // Kevin Greene
  
  class Database
  {
    private $db_host     = "driftdb.db.5688615.hostedresource.com";
    private $db_user     = "driftdb";
    private $db_password = "ruJE2REb";
    private $connection  = null;
    
    public function __construct()
    {
      $this->connect();
    }
    
    public function __destruct()
    {
      $this->disconnect();
    }
    
    public function connect()
    {
      // Connect to the server localhost using username dealdrift and password ruJE2REb.
      $this->connection = mysql_connect( $this->db_host, $this->db_user, $this->db_password );

      // Check if connected to the server.
      if( !$this->connection )
        die( 'Could not connect: ' . mysql_error() );

      // Open the database driftdb.
      return mysql_select_db( "driftdb", $this->connection );
    }
    
    public function disconnect()
    {
      $return_value = mysql_close( $this->connection );
      $this->connection = null;
      return $return_value;
    }
    
    public function query( $query )
    {
      return mysql_query( $query );
    }
    
    public function clean( $var )
    {
      return mysql_real_escape_string( trim( $var ) );
    }
  }
?>