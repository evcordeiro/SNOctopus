<?php 

  /**
   * File:     lib/db/sno_db_interface.php
   * Author:   Royce Stubbs
   * Purpose:  Provides SNOctopus-specific database functions using the
   *           PDO database abstraction layer.
   */
class sno_db_interface
{

    /**
     *  Creates a new database connection
     *
     *  @param    String $file    An .ini file that contains database information.
     *  @return   PDO Object      A handle for the open DB connection.
     *
     */    
    private static function newDbConnection($file = 'config.ini')
    {
        if (!$settings = parse_ini_file($file, true)) throw new exception('Unable to open ' . $file . '.');
        
        $dns = $settings['database']['driver'] 
             . ':host=' . $settings['database']['host'] 
             . ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') 
             . ';dbname=' . $settings['database']['dbname'];
        $user = $settings['db_auth']['username'];
        $pass = $settings['db_auth']['password'];

        try {
            $dbh = new PDO($dns, $user, $pass);
            return $dbh;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return null;
        }
    }

    /**
     *  Prepares and executes a query from the user.
     *
     *  @param    String $queryString    An SQL query string.
     *            Array  $paramArray     An array of parameters.
     *  @return   PDOStatment Object     An object that contains 
     */
    private static function executePreparedQuery($queryString, $paramArray)
    {
        try {
            $dbConn = self::newDbConnection();
            $stmt = $dbConn->prepare($queryString);
        } catch (PDOException $e) {
            echo 'Error in query preparation: ' . $e->getMessage();
            return null;
        }

	if ($stmt->execute($paramArray)) {
	   $dbConn = null; // Kill DB connection
	   return $stmt;
	}
	else {
	     echo 'Error on query execution\n';
	     $dbConn = null;
	     return null;
	}
	
    }

    /**
     *  Gets a result set array from a valid query. For random queries. Escape input!
     *
     *  @param    string $queryString    A valid SQL query string
     *  @return   array                  A result set array with named (associative)
     *                                    elements as well as numbered array elements.
     */
    public static function resultArrayFromQuery($queryString)
    {
        $dbh = self::newDbConnection();
        $resultSet = $dbh->query($queryString);
        $dbh = null;
        return $resultSet->fetchAll();
    }

    /**
     *  Gets array of network IDs for a particular user ID
     *
     *  @param     string $userId    String representation of a user's ID.
     *  @return    array             Array of network IDs.
     */
    public static function getNetworkIdArrayFromUserId($userId)
    {
        $pdoStatement = self::executePreparedQuery("select * from 'networks' where 'user_id'='?'", 
                                                   array($userId));
        return $pdoStatement->fetchAll();
    }

    /*
     * Checks for valid oauth key associated with a user's network service.
     *              NOTE: This function only checks if key exists for now. 2011.04.07
     *
     * @param       string $networkId    String representation of a user's network ID.
     * @return      boolean              True if key exists, false otherwise.
     *
     */
    public static function hasValidAuthForService($networkId)
    {
        $pdoStatement = self::executePreparedQuery("select 'credentials' from 'networks' where 'network_id'='?'", 
                                             array($networkId));
        $resultArray = $pdoStatement->fetch();

        if ($resultArray != null) {
            return true;
        } else {
            return false;
        }        
    }

    /**
     *  Gets credential array for specified service ID
     *
     *  @param    String $networkId    A string representation of a user's network ID.
     *  @return   Array                An array of user credentials, or null on error.
     *
     */
    public static function getCredentialsForService($networkId) 
    {
        $pdoStatement = self::executePreparedQuery("select 'credentials' from 'networks' where 'network_id'='?'", 
                                             array($networkId));
        $resultArray = $pdoStatement->fetch();

        if ($resultArray != null) {
            return unserialize(base64_decode($resultArray[0]));
        } else {
            return null;
        }
    }

    /**
     *  Sets user oauth credentials for a particular network
     *
     *  @param  Array  $credentialArray   An array of oauth credentials, anything else needed by 
     *                                     the service to authenicate the user.
     *          String $networkId         A string representation of a user's network ID.
     *  @return boolean                   True on success, false otherwise.
     *
     */
    public static function updateCredentials($credentialArray, $networkId)
    {
        $pdoStatement = self::executePreparedQuery("update 'networks' set 'networks'.'credentials'='"
                                             . base64_encode(serialize($credentialArray)) 
                                             . "' where 'network_id'='?'", array($networkId));
        if ($pdoStatement->rowCount() != 1) {
            echo 'Error setting credentials in sno_db_interface\n';
            return false;
        }
        else {
            return true;
        }
    }

    /**
     *  A boolean check if a specified network is active on a global scale.
     *
     *  @param   string $networkId   A string representation of a global network id for a user.
     *  @return  boolean             True if service is active, false otherwise.
     *
     */
    public static function isServiceActive($networkId)
    {
        $pdoStatement = self::executePreparedQuery("select 'active_status' from 'networks' where 'network_id'='?'",
                                             array($networkId));
        $resultArray = $pdoStatement->fetch();
                
        if ($resultArray[0] == '1') {
            return true;
        } else {
            return false;
        }
    }


    /**
     *  Insert a new social network for a user
     *
     *  
     */
    public static function setNewNetwork($userId, $networkName, $nickname, $credentialArray, $activeState = 1)
    {
	$enCred = base64_encode(serialize($credentialArray));
        $pdoStatement = self::executePreparedQuery("insert into 'networks' "
                                             . "('user_id, 'network_name', 'network_label', 'active_state') "
                                             . "values ('?', '?', '?', '?', '?')", 
                                             array($userId, $networkName, $nickname, $enCred, $activeState));
    }

    /**
     *  Make a new mapping from a feed to a network.
     *
     */
    public static function setNewFeedMap($feedUrl, $networkId, $activeState = 1)
    {
        $pdoStatement = self::executePreparedQuery("insert into 'maps' "
                                             . "('feed_url', 'network_id', 'active_state') "
                                             . "values ('?', '?', '?')",
                                             array($feedUrl, $networkId, $activeState));
    }

    /**
     *  Make a new post entry for a users network.
     * 
     *  
     */
    public static function setNewPost($feedUrl, $networkId, $publishDateTime, $bitlyUrl)
    {
        $pdoStatement = self::executePreparedQuery("insert into 'posts' "
                                             . "('feed_url', 'network_id', 'publish_date', 'bitly_link') "
                                             . "values ('?', '?', '?', '?')",
                                             array($feedUrl, $networkId, $publishDateTime, $bitlyUrl));
    }

    
}
