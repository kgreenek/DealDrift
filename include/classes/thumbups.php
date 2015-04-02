<? php

  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );

  class ThumbUps
  {
    private $sql_install_query = "CREATE TABLE thumbups (
      thumbup_id INT NOT NULL AUTO_INCREMENT,
      user_id INT NOT NULL,
      site_id INT NOT NULL,
      PRIMARY KEY( thumbup_id ),
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
    
    public function add( $site_id, $user_id )
    {
      // Clean the input.
      if( !filter_var( $user_id, FILTER_VALIDATE_INT ) )
      {
        $this->feedback = "Invalid user_id ";
        return false;
      }
      if( !filter_var( $site_id, FILTER_VALIDATE_INT )
      {
        $this->feedback = "Invalid site_id ";
        return false;
      }
      
      // Check if the thumbup is already in the table.
      $result = $this->db->query( "SELECT * FROM thumbups WHERE user_id = $user_id && site_id = $site_id" );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $this->feedback = "Site already Liked ";
        return false;
      }
      
      // Add a new entry to the table.
      $result = $this->db->query( "INSERT INTO thumbsup (user_id, site_id) VALUES ($user_id, $site_id)" );
      if( !$result || mysql_num_rows( $result ) != 1 )
      {
        $this->feedback = "Could not Like site ";
        return false;
      }
      
      return true;
    }
    
    public function get_users( $site_id )
    {
      if( !filter_var( $site_id, FILTER_VALIDATE_INT )
      {
        $this->feedback = "Invalid site_id ";
        return null;
      }
      
      $result = $this->db->query( "SELECT * FROM thumbups WHERE site_id = $site_id" );
      if( !$result || mysql_num_rows( $result ) < 1 )
      {
        $this->feedback = "Could not find users ";
        return null;
      }
      return $result;
    }
    
    public function get_num_users( $site_id )
    {
      $result = get_users( $site_id );
      if( $result )
        return mysql_num_rows( $result );
      return 0;
    }
  }
?>