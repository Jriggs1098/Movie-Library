<?php

/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: tv_model.class.php
 * Description: TV model.
 */

class TvModel {

    //Private data members.
    private $db;
    private $dbConnection;
    static private $_instance = NULL;
    private $tblTv;

    //To use singleton pattern, this constructor is made private. To get an instance of the class, the getTvModel method must be called.
    private function __construct() {
        $this->db = Database::getDatabase();
        $this->dbConnection = $this->db->getConnection();
        $this->tblTv = $this->db->getTvTable();

        //Escapes special characters in a string for use in an SQL statement. This stops SQL inject in POST vars. 
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $this->dbConnection->real_escape_string($value);
        }

        //Escapes special characters in a string for use in an SQL statement. This stops SQL Injection in GET vars 
        foreach ($_GET as $key => $value) {
            $_GET[$key] = $this->dbConnection->real_escape_string($value);
        }
    }

    //Static method to ensure there is just one TvModel instance.
    public static function getTvModel() {
        if (self::$_instance == NULL) {
            self::$_instance = new TvModel();
        }
        return self::$_instance;
    }

    //Retrieves all tv series from database and stores them into array.  Returns false if failed.
    public function list_tv() {

        //Select statement. Order series by title.
        $sql = "SELECT * FROM " . $this->tblTv . " ORDER BY title";
        try {
            //Execute the query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query || $query->num_rows == 0) {
                throw new DatabaseException("There was an error listing the shows.");
            }
            //Create an array to store all returned series.
            $tvs = array();

            //Loop through all rows in the returned recordsets
            while ($obj = $query->fetch_object()) {
                $title = urlencode($obj->title);
                $type = "series";
                $year = "";
                $json = Omdb::get_info_by_title($title, $type, $year);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $tv = new Tv(stripslashes($json->Title), stripslashes($json->Year), stripslashes($json->Rated), stripslashes($json->imdbRating), stripslashes($json->Genre), stripslashes($json->totalSeasons), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the movie
                $tv->setId($obj->id);

                //add the movie into the array
                $tvs[] = $tv;
            }
            return $tvs;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Retieve tv series details based on id of that tv object. Return false if failed.
    public function view_tv($id) {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //The select sql statement.
        $sql = "SELECT * FROM " . $this->tblTv . " WHERE id=$id";

        try {
            //Execute the query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query || $query->num_rows == 0) {
                throw new DatabaseException("There was an error viewing the show.");
            }

            if ($query && $query->num_rows > 0) {
                $obj = $query->fetch_object();
                $title = urlencode($obj->title);
                $type = "series";
                $year = "";
                $json = Omdb::get_info_by_title($title, $type, $year);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $tv = new Tv(stripslashes($json->Title), stripslashes($json->Year), stripslashes($json->Rated), stripslashes($json->imdbRating), stripslashes($json->Genre), stripslashes($json->totalSeasons), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the movie
                $tv->setId($obj->id);

                //Session to store number of seasons.
                $_SESSION['num_seasons'] = $json->totalSeasons;

                return $tv;
            }
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Update an existing show in the database. Details of the show are posted in a form. Return true if succeed; false otherwise.
    public function update_tv($id) {
        try {
            //If the script did not received post data, display an error message and then terminite the script immediately
            if (!filter_has_var(INPUT_POST, 'title')) {

                throw new DataMissingException("Please fill in all fields.");
            }

            //Retrieve data for the new show; data are sanitized and escaped for security.
            $title = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING)));

            if ($title == "") {
                throw new DataMissingException("Please fill in all fields.");
            }

            //query string for update 
            $sql = "UPDATE " . $this->tblTv .
                    " SET title='$title' WHERE id='$id'";

            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error updating the show.");
            }
            return $query;
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Search the database for shows that match words in titles. Return an array of movies if succeed; false otherwise.
    public function search_tv($query_terms) {
        $terms = explode(" ", $query_terms); //explode multiple terms into an array
        //select statement for AND search
        $sql = "SELECT * FROM " . $this->tblTv . " WHERE 1 ";

        //Search in titles or genre.
        foreach ($terms as $term) {
            $sql .= " AND (title LIKE '%" . $term . "%')";
        }

        //Order by title
        $sql .= " ORDER BY title ";

        try {
            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query) {
                throw new DatabaseException("There was an error searching for the shows.");
            }

            //search succeeded, and found at least 1show found.
            //create an array to store all the returned shows.
            $tvs = array();

            //Loop through all rows in the returned recordsets
            while ($obj = $query->fetch_object()) {
                $title = urlencode($obj->title);
                $type = "series";
                $year = "";
                $json = Omdb::get_info_by_title($title, $type, $year);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $tv = new Tv(stripslashes($json->Title), stripslashes($json->Year), stripslashes($json->Rated), stripslashes($json->imdbRating), stripslashes($json->Genre), stripslashes($json->totalSeasons), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the movie
                $tv->setId($obj->id);

                //add the movie into the array
                $tvs[] = $tv;
            }
            return $tvs;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insert_tv() {
        try {
            //If the script did not received post data, display an error message and then terminite the script immediately
            if (!filter_has_var(INPUT_POST, 'title-add')) {

                throw new DataMissingException("Please fill in all fields.");
            }

            //Retrieve data for the new movie; data are sanitized and escaped for security.
            $title = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'title-add', FILTER_SANITIZE_STRING)));

            if ($title == "") {
                throw new DataMissingException("Please fill in all fields.");
            }

            //Define the MySQL insert statement.
            $sql = "INSERT INTO " . $this->tblTv .
                    " VALUES (NULL, '$title')";

            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error updating the show.");
            }
            return $query;
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Suggest movies when adding a new movie.   
    public function suggest_tv($query_terms) {
        try {
            //create an array to store all the returned movies
            $tvs = array();
            $query_terms = urlencode($query_terms);
            $type = "series";
            $json = Omdb::get_info_by_search($query_terms, $type);
 
            //If the query failed, return false. 
            if (!$json) {
                throw new DatabaseException("There was an error searching for the shows.");
            }

            //show 3 results at a time to avoid errors.
            $i = 0;
            while ($i < 3) {

                $tv = new Tv(stripslashes($json['Search'][$i]["Title"]), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""));

                //set the id for the movie
                $tv->setId($i);
                //add the movie into the array
                $tvs[] = $tv;

                $i++;
            }

            return $tvs;
        } catch (ApiDataException $e) {
            return $e->getMessage();
        }
    }

}
