<?php
/*
 * Author: Jack Riggs
 * Date: April 7, 2019
 * File: database.class.php
 * Description: Set database details.
 */

class Database {

    //Define database parameters.
    private $param = array(
        'host' => 'localhost',
        'login' => 'phpuser',
        'password' => 'phpuser',
        'database' => 'digitalvideo_db',
        'tblMovie' => 'movies',
        'tblTv' => 'tv_series',
        'tblEpisode' => 'tv_episodes',
        'tblUser' => 'users',
        'tblLibrary' => 'libraries'
    );
    //Define the database connection object.
    private $objDBConnection = NULL;
    static private $_instance = NULL;

    //Constructor.
    private function __construct() {
        try {
        $this->objDBConnection = @new mysqli(
                $this->param['host'], $this->param['login'], $this->param['password'], $this->param['database']
        );
        if (mysqli_connect_errno() != 0) {
            throw new DatabaseException("There was an error connecting to the database.");
        }
        
        } catch (DatabaseException $e) {
            $message = $e->getMessage();
            include 'error.php';
            exit();
        } catch (Exception $e) {
            $message = $e->getMessage();
            include 'error.php';
            exit();
        }
    }

    //Static method to ensure there is just one Database instance.
    static public function getDatabase() {
        if (self::$_instance == NULL)
            self::$_instance = new Database();
        return self::$_instance;
    }

    //Return database connection object.
    public function getConnection() {
        return $this->objDBConnection;
    }

    //Return name of movies table.
    public function getMovieTable() {
        return $this->param['tblMovie'];
    }

    //Return name of tv series table.
    public function getTvTable() {
        return $this->param['tblTv'];
    }
    
    //Return name of episode table.
    public function getEpisodeTable() {
        return $this->param['tblEpisode'];
    }
    
    //Return name of users table.
    public function getUserTable() {
        return $this->param['tblUser'];
    }
    
    //Return name of library table.
    public function getLibraryTable() {
        return $this->param['tblLibrary'];
    }

}
