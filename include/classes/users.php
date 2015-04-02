<?php
  // Users.php
  // Kevin Greene
  
  require_once( getenv( 'DOCUMENT_ROOT' ) . '/include/classes/database.php' );
  
  class Users
  {
    protected $sql_install_query = "CREATE TABLE users (
      user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      user_name VARCHAR( 255 ) NOT NULL UNIQUE KEY,
      email VARCHAR( 255 ) NOT NULL UNIQUE KEY,
      password VARCHAR( 255 ) NOT NULL,
      token VARCHAR( 255 ),
      ip VARCHAR( 255 ) NOT NULL,
      auth INT NOT NULL DEFAULT 1,
      is_confirmed INT( 1 ) NOT NULL DEFAULT 0,
      is_banned INT( 1 ) NOT NULL DEFAULT 0,
      num_sites_added INT NOT NULL DEFAULT 0,
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
    ) ENGINE=InnoDB";
    protected $super_secret_hash_var = "dealdrift_*123_secret_password";
    private $user = null;
    private $feedback = '';
    private $db = null;
  
    public function __construct( $database )
    {
      $this->db = $database;
      if( !$this->check_session() ) 
        $this->check_cookie();
    }
    
    // Returns feedback string from last function called (e.g. any error message).
    public function get_feedback()
    {
      return $this->feedback;
    }
    
    public function create_table()
    {
      // Create the table if it does not exist.
      $this->feedback = $this->db->query( $sql_install_query );
      return $this->feedback;
    }
    
    public function get_user_name_from_user_id( $user_id )
    {
      $result = $this->db->query( 'SELECT * FROM users WHERE user_id = "' . $user_id . '"' );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $row = mysql_fetch_assoc( $result );
        return $row["user_name"];
      }
      
      // If we get here, no user with $user_id was found.
      $this->feedback = 'User ID not found. ';
      return '';
    }
    
    public function register_new( $user_name, $email, $password1, $password2 )
    {
      // Validate user_name.
      $user_name = strtolower( $this->db->clean( $user_name ) );
      if( !$this->user_name_isvalid( $user_name ) )
        return false;

      // Make sure user_name isn't already in the table.
      $result = $this->db->query( 'SELECT * FROM users WHERE user_name = "' . $user_name . '"' );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $this->feedback = 'Username already taken. ';
        return false;
      }
      
      // Validate email.
      $email = strtolower( $this->db->clean( $email ) );
      if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
      {
        $this->feedback = 'Email invalid. ';
        return false;
      }
      
      // Make sure email isn't already in the table.
      $result = $this->db->query( 'SELECT * FROM users WHERE email = "' . $email . '"' );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $this->feedback = 'Email already in use. ';
        return false;
      }
      
      // Compare password1 and password2.
      if( $password1 != $password2 )
      {
        $this->feedback = 'Passwords do not match. ';
        return false;
      }
      
      // Validate password.
      if( !$this->password_isvalid( $password1 ) )
        return false;
      
      // Get the user's ip address.
      if( $_SERVER['HTTP_X_FORWARD_FOR'] )
        $ip = $_SERVER['HTTP_X_FORWARD_FOR'];
      else
        $ip = $_SERVER['REMOTE_ADDR'];
      
      // All necessary input is assumed valid if we make it to this point!
      // Create an entry in the users table.
      $token_hash = $this->get_token_hash( $email );
      $password_hash = $this->get_password_hash( $password1 );
      $result = $this->db->query( "INSERT INTO users (user_name, email, password, token, ip) VALUES ('" . 
                                  $user_name . "', '" . 
                                  $email . "', '" . 
                                  $password_hash . "', '" . 
                                  $token_hash . "', '" . 
                                  $ip . "')" );
      if( !$result )
      {
        $this->feedback = "mysql_error: " . mysql_error();
        return false;
      }
      
      return true;
    }
    
    // Returns an array of arrays, where each index is an array of user data.
    // The users are ardered by num_sites_added.
    //  Ex: Reference the name of the 3rd user with $return_array[2]['name'].
    public function get_top_drifters( $num_drifters )
    {
      $num_drifters = $this->db->clean( $num_drifters );
      $result = $this->db->query( "SELECT * FROM users ORDER BY num_sites_added DESC" );
      if( !$result || mysql_num_rows( $result ) == 0 )
      {
        $this->feedback = "No users found in db ";
        return null;
      }
      
      $return_array = array();
      for( $i = 0; $i < $num_drifters; $i++ )
      {
        $row = mysql_fetch_array( $result, MYSQL_ASSOC );
        $return_array[$i] = $row;
      }
      
      return $return_array;
    }
    
    public function get_user_id()
    {
      if( $this->is_authorized() )
        return $this->user["user_id"];
      return 0; // 0 is an invalid user_id.
    }
    
    public function get_user_name()
    {
      if( $this->is_authorized() )
        return $this->user["user_name"];
      return ''; // '' is an invalid user_name.
    }
    
    // Used in the initial registration function as well as the change email address function.    
    public function send_confirmation_email( $user_name, $email )
    {
      $token_hash = $this->get_token_hash( $email );
      $message = "Thank You For Registering at DealDrift" .
                 "\nSimply follow this link to confirm your registration: " .
                 "\n\nhttp://www.dealdrift.com/confirm.php?email=" . urlencode( $email ) . "&user_name=" . urlencode( $user_name ) . "&hash=" . $token_hash .
                 "\n\nOnce you confirm, you can login and start drifting.";
      return mail( $email, 'DealDrift Registration Confirmation', $message, 'From: noreply@dealdrift.com' );
    }

    public function confirm_email( $user_name, $email, $token_hash )
    {
      // Make sure a user with user_name and email exist in the database.
      $result = $this->db->query( "SELECT * FROM users WHERE email = '" . $email . "' AND user_name = '" . $user_name . "' AND token ='" . $token_hash . "' LIMIT 1" );
      if( !$result || mysql_num_rows( $result ) != 1 )
      {
        $this->feedback = "Confirmation invalid.";
        return false;
      }

      // Mark the account as confirmed in the database.
      $result = $this->db->query( "UPDATE users SET is_confirmed = '1' WHERE user_name = '" . $user_name . "' AND email = '" . $email . "' AND token = '" . $token_hash . "'" );
      if( !$result )
      {
        $this->feedback = "mysql_error: " . mysql_error();
        return false;
      }
      
      return true;
    }

    public function login( $user_name, $password, $remember = false )
    {
      // Validate user_name and password and get user settings from table.
      $user_name = $this->db->clean( $user_name );
      $password = $this->db->clean( $this->get_password_hash( $password ) );
      $result = $this->db->query( "SELECT * FROM users WHERE (user_name = '" . $user_name . "' OR email = '" . $user_name . "') AND password = '" . $password . "' LIMIT 1" );
      if( !$result || mysql_num_rows( $result ) < 1 )
      {
        $this->feedback = "Invalid username / password. ";
        return false;
      }
      
      // Set session and cookie.
      $id = mysql_result( $result, 0, "user_id" );
      return $this->restore( $id, true );
    }
    
    public function logout()
    {
      // Clear session.
      $this->clear_session();
      // Clear cookie.
      $this->clear_cookies();
      // Clear private login data.
      $this->user = array();
      // Should ever return false?
      return true;
    }

    public function is_authorized( $level = 1 )
    {
      if( isset( $this->user ) && isset( $this->user['auth'] ) && $this->user['auth'] >= $level )
        return true;
      return false;
    }
    
    public function change_password( $old_password, $new_password1, $new_password2 )
    {
      // Make sure user is logged in?
      // Validate old_password.
      // Validate and compare new_password1 and new_password2.
      // Update password in database.
    }
    
    public function change_email( $new_email, $user_id = "" )
    {
      // Validate new email.
      // Change token, but don't change email in database.
      // Send confirmation email.
    }
    
    public function purge_registration()
    {
      // Delete unconfirmed entries older than purge_time.
    }
    
    public function get_settings( $user_id = "" )
    {
      if( $user_id == "" )
        return $this->user;
        
      // Get row.
      $result = $this->db->query( "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1" );
      if( $result && mysql_num_rows( $result ) > 0 )
        return mysql_fetch_assoc( $result );
        
      // Invalid $user_id.
      $this->feedback = "Invalid user_id when getting settings. ";
      return false;
    }
    
    public function get_setting( $field, $user_id = "" )
    {
      // Get the corresponding field from settings.
    }
    
    public function set_settings( $new_settings, $user_id = "" )
    {
      // Set row from new_settings array.
    }
    
    public function set_setting( $field, $new_setting, $user_id = "" )
    {
      // Set the corresponding field to the new_setting.
    }
    
    public function reset_password( $user_id = "" )
    {
      // Send a new password to the user.
    }
    
    private function user_name_isvalid( $user_name )
    {
      // No spaces.
      if( strrpos( $user_name, " " ) > 0 ) 
      {
        return false;
        $this->feedback = "There cannot be any spaces in username. ";
      }

      // Must have at least one character.
      if( strspn( $user_name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ) == 0 )
      {
        $this->feedback = "There must be at least one character in username. ";
        return false;
      }

      // Must contain all legal characters.
      if( strspn( $user_name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_" )
          != strlen( $user_name ) )
      {
        $this->feedback = "Illegal character in username. ";
        return false;
      }

      // Min and max length.
      if( strlen( $user_name ) < 5 )
      {
        $this->feedback = "Username too short. It must be at least 5 characters. ";
        return false;
      }
      if (strlen($user_name) > 15 )
      {
        $this->feedback = "Username too long. It must be less than 15 characters. ";
        return false;
      }

      // illegal names
      if( eregi( "^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)"
                 . "|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)"
                 . "|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$", $user_name ) )
      {
        $this->feedback = "Username is reserved. ";
        return false;
      }
      if( eregi( "^(anoncvs_)", $user_name ) )
      {
        $this->feedback = "Username is reserved for CVS. ";
        return false;
      }

      return true;
    }
    
    private function password_isvalid( $password )
    {
      if( strlen( $password ) < 6 )
      {
        $this->feedback = "Password must be at least 6 characters. ";
        return false;
      }
      return true;
    }
    
    private function restore( $id, $login = false )
    {
      $id = $this->db->clean( $id );
      $result = $this->db->query( "SELECT * FROM users WHERE is_confirmed = '1' AND is_banned = '0' AND user_id = '" . $id . "' LIMIT 1" );
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $row = mysql_fetch_assoc( $result );
        $this->user = $row;
        
        $user_id = $row["user_id"];
        $password = $row["password"];
        $this->set_session( $user_id, $password );
        
        /* // Not yet Implemented
        if( $row['timezone'] != "" )
          date_default_timezone_set( $row['timezone'] );
        */
        
        if( $login )
          $this->set_cookies();
        
        /* // Not Implemented (ever?).
        $login = ($login) ? "date_loggedin = '" . mktime() . "', token = '', " : "";
          return $this->db->query( "UPDATE users SET $login`date_activity` = '" . mktime() . "', ip = '{$_SERVER['REMOTE_ADDR']}' WHERE id = '{$this->user['id']}' LIMIT 1");
        */
        
        return true;
      }
      $this->feedback = "Login attempt unsuccessful. ";
      return false;
    }
    
    private function set_cookies( $duration = 2592000 )
    {
      // Set the cookies if not present.
      $exp = time() + $duration;
      
      if( !$this->user )
      {
        $this->feedback = 'User data missing when setting cookie. ';
        return false;
      }

      setcookie( 'dd_users_user_id', $this->user["user_id"], (time() + 2592000), '/', '', 0 );
      setcookie( 'dd_users_password_hash', $this->user["password"],  (time() + 2592000), '/', '', 0 );
      
      return true;
    }
    
    private function clear_cookies()
    {
      // Delete the cookies if present.
      setcookie( 'dd_users_user_id', '', (time() + 2592000), '/', '', 0 );
      setcookie( 'dd_users_password_hash', '', (time() + 2592000), '/', '', 0 );
      return true;
    }
    
    private function set_session( $id, $password )
    {
      // Setup session.
      $_SESSION['users_user_id'] = $id;
      $_SESSION['users_user_password'] = $password;
      return true;
    }
    
    private function clear_session()
    {
      $_SESSION['users_user_id'] = '';
      $_SESSION['users_user_password'] = '';
      return true;
    }
    
    private function check_session()
    {
      if( isset( $_SESSION['users_user_id'] ) && isset( $_SESSION['users_user_password'] ) )
      {
        $id = $this->db->clean( $_SESSION['users_user_id'] );
        $password = $this->db->clean( $_SESSION['users_user_password'] );
        $result = $this->db->query( "SELECT * FROM users WHERE user_id = '$id' AND password = '$password' LIMIT 1" );
        
        if( $result && mysql_num_rows( $result ) > 0 )
        {
          $row = mysql_fetch_assoc( $result );
          return $this->restore( $row['user_id'] );
        }
      }
      
      return false;
    }

    private function check_cookie()
    {
      $id = $_COOKIE['dd_users_user_id'];
      $password_hash = $_COOKIE['dd_users_password_hash'];
      
      $id = $this->db->clean( $id );
      $password_hash = $this->db->clean( $password_hash );
      $result = $this->db->query( "SELECT * FROM users WHERE user_id = '$id' AND password = '$password_hash' LIMIT 1" );
      
      if( $result && mysql_num_rows( $result ) > 0 )
      {
        $row = mysql_fetch_assoc( $result );
        return $this->restore( $row['user_id'] );
      }
      
      return false;
    }
    
    private function get_password_hash( $password, $string = ""  )
    {
      // Create hash from password, user_id, and super_secret_hash_var.
      if( $string == "" )
        $string = $this->super_secret_hash_var;
      return md5( "~$password~$string!" );
    }
    
    private function get_token_hash( $email, $string = "" )
    {
      // Create hash from email and string.
      if( $string == "" )
        $string = $this->super_secret_hash_var;
      return md5( "~$email~$string!" );
    }
    
  }
?>