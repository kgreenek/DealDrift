<? php

  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );

  class Friends
  {
    private $sql_install_query = "CREATE TABLE friends (
      friendship_id INT NOT NULL AUTO_INCREMENT,
      friend1_id INT NOT NULL,
      friend2_id INT NOT NULL,
      PRIMARY KEY( friendship_id ),
      FOREIGN KEY( friend1_id ) REFERENCES users( user_id ),
      FOREIGN KEY( friend2_id ) REFERENCES users( user_id )
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
    
    public function add( $friend1_id, $friend2_id )
    {
      // Clean the input.
      if( !filter_var( $friend2_id, FILTER_VALIDATE_INT ) )
      {
        $this->feedback = "Invalid friend1_id ";
        return false;
      }
      if( !filter_var( $friend1_id, FILTER_VALIDATE_INT ) )
      {
        $this->feedback = "Invalid friend2_id ";
        return false;
      }
      
      // Make sure the friendship is not already in the table.
      $result = $this->db->query( "SELECT * FROM friends WHERE ((friend1_id = $friend1_id) && (friend2_id = $friend2_id)) ||
                                                               ((friend1_id = $friend2_id) && (friend2_id = $friend1_id))" );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $this->feedback = "Users are already friends ";
        return false;
      }
      
      // Add a new entry to the table.
      $result = $this->db->query( "INSERT INTO friends (friend1_id, friend2_id) VALUES ($friend1_id, $friend2_id)" );
      if( !$result || mysql_num_rows( $result ) != 1 )
      {
        $this->feedback = "Could not create friendship ";
        return false;
      }
      
      return true;
    }
  }
?>