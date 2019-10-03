<?php
/*
 * Author: Jack Riggs
 * Date: April 7, 2019
 * File: movie_model.class.php
 * Description: Movie model. 
 */

class MovieModel {

    //Private data members.
    private $db;
    private $dbConnection;
    static private $_instance = NULL;
    private $tblMovie;

    //To use singleton pattern, this constructor is made private. To get an instance of the class, the getMovieModel method must be called.
    private function __construct() {
        $this->db = Database::getDatabase();
        $this->dbConnection = $this->db->getConnection();
        $this->tblMovie = $this->db->getMovieTable();

        //Escapes special characters in a string for use in an SQL statement. This stops SQL inject in POST vars. 
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $this->dbConnection->real_escape_string($value);
        }

        //Escapes special characters in a string for use in an SQL statement. This stops SQL Injection in GET vars 
        foreach ($_GET as $key => $value) {
            $_GET[$key] = $this->dbConnection->real_escape_string($value);
        }
    }

    //Static method to ensure there is just one MovieModel instance.
    public static function getMovieModel() {
        if (self::$_instance == NULL) {
            self::$_instance = new MovieModel();
        }
        return self::$_instance;
    }

    //Retrieves all movies from database and stores them into array.  Returns false if failed.
    public function list_movie() {

        //Select statement. Order movies by title.
        $sql = "SELECT * FROM " . $this->tblMovie . " ORDER BY title";
        try {
            //Execute the query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query || $query->num_rows == 0) {
                throw new DatabaseException("There was an error listing the movies.");
            }

            //Create an array to store all returned movies.
            $movies = array();

            //Loop through all rows in the returned recordsets
            while ($obj = $query->fetch_object()) {
                $title = urlencode($obj->title);
                $year = urlencode($obj->year);
                $type = "movie";
                $json = Omdb::get_info_by_title($title, $type, $year);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $movie = new Movie(($json->Title), stripslashes($json->Year), stripslashes($json->Rated), stripslashes($json->Runtime), stripslashes($json->imdbRating), stripslashes($json->Genre), stripslashes($json->Director), stripslashes($json->DVD), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the movie
                $movie->setId($obj->id);

                //add the movie into the array
                $movies[] = $movie;
            }
            return $movies;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataExceptionException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Retieve movie details based on id of that movie object. Return false if failed.
    public function view_movie($id) {
        //The select sql statement.
        $sql = "SELECT * FROM " . $this->tblMovie . " WHERE id=$id";

        try {
            //Execute the query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query || $query->num_rows == 0) {
                throw new DatabaseException("There was an error listing the movies.");
            }

            //Create an array to store all returned movies.
            $movies = array();

            if ($query && $query->num_rows > 0) {
                $obj = $query->fetch_object();
                $title = urlencode($obj->title);
                $year = urlencode($obj->year);
                $type = "movie";
                $json = Omdb::get_info_by_title($title, $type, $year);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $movie = new Movie(stripslashes($json->Title), stripslashes($json->Year), stripslashes($json->Rated), stripslashes($json->Runtime), stripslashes($json->imdbRating), stripslashes($json->Genre), stripslashes($json->Director), stripslashes($json->DVD), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the movie
                $movie->setId($obj->id);

                return $movie;
            }
            return $movies;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataExceptionException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Update an existing movie in the database. Details of the movie are posted in a form. Return true if succeed; false otherwise.
    public function update_movie($id) {
        try {
            //If the script did not received post data, display an error message and then terminite the script immediately
            if (!filter_has_var(INPUT_POST, 'title') || !filter_has_var(INPUT_POST, 'year')) {

                throw new DataMissingException("Please fill in all fields.");
            }

            //Retrieve new data for the movie; data are sanitized and escaped for security.
            $title = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING)));
            $year = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING)));
            if (strlen($year) != 4) {
                throw new DataLengthException("Please ensure your year is 4 digits (such as '2019').");
            }

            if ($title == "" || $year == "") {
                throw new DataMissingException("Please fill in all fields.");
            }

            //query string for update 
            $sql = "UPDATE " . $this->tblMovie .
                    " SET title='$title', year='$year' WHERE id='$id'";

            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error updating the movie.");
            }
            return $query;
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DataLengthException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

//Search the database for movies that match words in titles. Return an array of movies if succeed; false otherwise.
    public function search_movie($query_terms) {
        $terms = explode(" ", $query_terms); //explode multiple terms into an array
        //select statement for AND search
        $sql = "SELECT * FROM " . $this->tblMovie . " WHERE 1 ";

        //Search in titles, directors, year, genre. User can search for an exact year if they know what year they want but not the specific item.
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
                throw new DatabaseException("There was an error searching for the movies.");
            }

            //search succeeded, and found at least 1 movie found.
            //create an array to store all the returned movies
            $movies = array();

            //Loop through all rows in the returned recordsets
            while ($obj = $query->fetch_object()) {
                $title = urlencode($obj->title);
                $year = urlencode($obj->year);
                $type = "movie";
                $json = Omdb::get_info_by_title($title, $type, $year);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $movie = new Movie(($json->Title), stripslashes($json->Year), stripslashes($json->Rated), stripslashes($json->Runtime), stripslashes($json->imdbRating), stripslashes($json->Genre), stripslashes($json->Director), stripslashes($json->DVD), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the movie
                $movie->setId($obj->id);

                //add the movie into the array
                $movies[] = $movie;
            }
            return $movies;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insert_movie() {
        try {
            //If the script did not received post data, display an error message and then terminite the script immediately
            if (!filter_has_var(INPUT_POST, 'title-add') || !filter_has_var(INPUT_POST, 'year-add')) {

                throw new DataMissingException("Please fill in all fields.");
            }

            //Retrieve new data for the movie; data are sanitized and escaped for security.
            $title = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'title-add', FILTER_SANITIZE_STRING)));
            $year = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'year-add', FILTER_SANITIZE_STRING)));
            if (strlen($year) != 4) {
                throw new DataLengthException("Please ensure your year is 4 digits (such as '2019').");
            }

            if ($title == "" || $year == "") {
                throw new DataMissingException("Please fill in all fields.");
            }

            //Define the MySQL insert statement.
            $sql = "INSERT INTO " . $this->tblMovie .
                    " VALUES (NULL, '$title', '$year')";

            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error updating the movie.");
            }
            return $query;
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DataLengthException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return$e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Delete a movie from the database.
    public function delete_movie($id) {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        try {
            if (!isset($_SESSION['user_id']) || $user_id != 1) {
                throw new InvalidAccountException("You do not have access to this feature.");
            }

            //Define the MySQL delete statement.
            $sql = "DELETE FROM $this->tblMovie WHERE id = $id";

            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error removing the movie.");
            }
            return $query;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (InvalidAccountException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Suggest movies when adding a new movie.   
    public function suggest_movie($query_terms) {
        try {
            //create an array to store all the returned movies
            $movies = array();
            $query_terms = urlencode($query_terms);
            $type = "movie";
            $json = Omdb::get_info_by_search($query_terms, $type);

            //If returned false, throw an error.
            if (!$json) {
                throw new ApiDataException("There was an error connecting to the api.");
            }

            //show 3 results at a time to avoid errors.
            $i = 0;
            while ($i < 3) {

                $movie = new Movie(stripslashes($json['Search'][$i]["Title"]), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""), stripslashes(""));

                //set the id for the movie
                $movie->setId($i);
                //add the movie into the array
                $movies[] = $movie;

                $i++;
            }

            return $movies;
        } catch (ApiDataException $e) {
            return $e->getMessage();
        }
    }

}
