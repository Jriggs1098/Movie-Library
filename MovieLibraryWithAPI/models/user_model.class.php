<?php

/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_model.class.php
 * Description: User Model.
 * 
 * USER TYPES:
 *  1 == Admin
 *  2 == Normal User.
 * ADMIN LOGIN:
 *  Username: admin
 *  Password: password
 * 
 * GUEST LOGIN:
 *  Username: guest
 *  Password: password
 * 
 * GUEST2 LOGIN:
 *  Username: guest2
 *  Password: guest2
 */

class UserModel {

    //Private attributes.
    private $db, $dbConnection;
    private $tblUser, $tblLibrary, $tblMovie, $tblEpisode, $tblTv;

    //Constructor. Make sure only one instance of the database is running.
    public function __construct() {
        $this->db = Database::getDatabase();
        $this->dbConnection = $this->db->getConnection();
        $this->tblUser = $this->db->getUserTable();
        $this->tblLibrary = $this->db->getLibraryTable();
        $this->tblMovie = $this->db->getMovieTable();
        $this->tblEpisode = $this->db->getEpisodeTable();
        $this->tblTv = $this->db->getTvTable();
    }

    //Get user data and add to database.
    public function add_user() {
        try {
            //Retrieve user details.
            if (filter_has_var(INPUT_POST, 'username') && filter_has_var(INPUT_POST, 'password') && filter_has_var(INPUT_POST, 'firstname') && filter_has_var(INPUT_POST, 'lastname')) {
                $firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
                $lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
                $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
                $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            }

            if ($firstname == "" || $lastname == "" || $username == "" || $password == "") {
                throw new DataMissingException("Please fill in all fields.");
            }
            //Hash password.
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            //Add user details to database.
            $sql = "INSERT INTO " . $this->tblUser . " VALUES (NULL, 2, '$firstname', '$lastname', '$username', '$hashed_password')";


            //Execute the insert query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query) {
                throw new DatabaseException("There was an error adding the user.");
            }
            //Return true if successful.
            return true;
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Verify usernam and password.
    public function verify_user() {
        try {
            //Retrieve user name and password from the form in the login form.
            if (filter_has_var(INPUT_POST, 'username') && filter_has_var(INPUT_POST, 'password')) {
                $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
                $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            }

            if ($username == "" || $password == "") {
                throw new DataMissingException("Please fill in all fields.");
            }


            //Get username and password from database.
            $sql = "SELECT * FROM " . $this->tblUser . " WHERE username = '$username'";


            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query) {
                throw new DatabaseException("There was an error verifying the user.");
            }
            if ($query->num_rows == 0) {
                throw new InvalidAccountException("An account does not exist with that username or password.");
            }

            //Create new user object.
            if ($query->num_rows == 1) {
                $obj = $query->fetch_object();

                $user = new User(stripslashes($obj->user_type), stripslashes($obj->firstname), stripslashes($obj->lastname), stripslashes($obj->username), stripslashes($obj->password));
                //set the id for the user.
                $user->setId($obj->id);
            }

            //Get user's username, password, firstname, and user type.
            $username_db = $user->getUsername();
            $password_db = $user->getPassword();
            $name = $user->getFirstname();
            $user_type = $user->getUser_type();
            $user_id = $user->getId();

            //Set to true if username and password are the pre-created admin/guest account.
            if (($username_db == "admin" && $password == "password") || ($username_db == "guest" && $password == "password")) {
                $verify_password = true;
            } else {
                $verify_password = password_verify($password, $password_db);
            }

            if ($username != $username_db || !$verify_password) {
                throw new InvalidAccountException("An invalid username or password was entered.");
            }

            //If username and password match those in database, return true. Set session with user's first name and their user type.
            if ($username == $username_db && $verify_password) {
                //See if session exists already, if not start one.
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_type'] = $user_type;
                $_SESSION['name'] = $name;
                $_SESSION['user_id'] = $user_id;
                return true;
            }
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (InvalidAccountException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Unset login sessions to logout the user.
    public function logout() {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
            $_SESSION['name'] = array();
            $_SESSION['user_type'] = array();
            $_SESSION['user_id'] = array();
            $_SESSION['movies'] = array();
            $_SESSION['episodes'] = array();
            return true;
        }
    }

    //Add movie to user's library.
    public function add_movie($movie_id) {
        try {
            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            }

            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == array()) {
                throw new InvalidAccountException("You cannot add something to your library if you are not logged in.");
            }

            //Define the MySQL insert statement.
            $sql = "INSERT INTO " . $this->tblLibrary .
                    " VALUES (NULL, $user_id, $movie_id, NULL)";

            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error adding the movie to your library.");
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

    //Add episode to user's library.
    public function add_episode($episode_id) {
        try {
            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            //Create session to store series name.
            $series_names = $this->series_names();
            $_SESSION['series_names'] = $series_names;

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            }

            if (!isset($_SESSION['user_id'])) {
                throw new InvalidAccountException("You cannot add something to your library if you are not logged in.");
            }

            //Define the MySQL insert statement.
            $sql = "INSERT INTO " . $this->tblLibrary .
                    " VALUES (NULL, $user_id, NULL, $episode_id)";

            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error adding the episode to your library.");
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

    public function show_library() {
        try {
            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            }

            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == array()) {
                throw new InvalidAccountException("You do not have access to that library.");
            }

            //Select statement. Get the movie and episode information for the items in user's library.
            $sql_m = "SELECT * FROM " . $this->tblLibrary . "," . $this->tblMovie .
                    " WHERE " . $this->tblMovie . ".id=" . $this->tblLibrary . ".movie_id" .
                    " AND " . $this->tblLibrary . ".user_id=" . $user_id;

            $query_m = $this->dbConnection->query($sql_m);

            //If the query failed, return error. 
            if (!$query_m) {
                throw new DatabaseException("There was an error showing movies in your library.");
            }

            //Create an array to store all returned movies.
            $movies = array();

            //Loop through all rows in the returned recordsets
            while ($obj = $query_m->fetch_object()) {
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
            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            //Save the movies into a session.
            $_SESSION['movies'] = $movies;


            $sql_e = "SELECT * FROM " . $this->tblLibrary . "," . $this->tblEpisode .
                    " WHERE " . $this->tblEpisode . ".id=" . $this->tblLibrary . ".episode_id" .
                    " AND " . $this->tblLibrary . ".user_id=" . $user_id;

            $query_e = $this->dbConnection->query($sql_e);

            //If the query failed, return error. 
            if (!$query_e) {
                throw new DatabaseException("There was an error adding the movie to your library.");
            }

            //Create an array to store all returned episodes.
            $episodes = array();

            //Create session to store series name.
            $series_names = $this->series_names();
            $_SESSION['series_names'] = $series_names;

            //Loop through all rows in the returned recordsets
            while ($obj = $query_e->fetch_object()) {
                //Get series name from session.
                if (isset($_SESSION['series_names'])) {
                    $series_names = $_SESSION['series_names'];

                    $series_name = $series_names[$obj->series_id];
                }

                $title_series = urlencode($series_name);
                $season_number = urlencode($obj->season_number);
                $type = "series";
                $episode_number = $obj->episode_number;
                $json = Omdb::get_info_by_episode_number($title_series, $season_number, $episode_number, $type);
                
                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }
                
                $episode = new Episode(stripslashes($obj->series_id), stripslashes($json->Season), stripslashes($json->Episode), stripslashes($json->Title), stripslashes($json->Released), stripslashes($json->Runtime), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the episode
                $episode->setId($obj->id);

                //add the movie into the array
                $episodes[] = $episode;
            }
            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            //Save the episodes into a session.
            $_SESSION['episodes'] = $episodes;

        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (InvalidAccountException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Delete a movie from a library.
    public function delete_movie($movie_id) {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        if (isset($_SESSION['movies'])) {
            $movies = $_SESSION['movies'];
        }

        //Define the MySQL delete statement.
        $sql = "DELETE FROM libraries WHERE movie_id = $movie_id AND user_id = $user_id";

        //Remove from the movies session.
        $index = array_search($movie_id, $movies);
        unset($_SESSION["movies"][$index]);

        try {

            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error removing the movie from your library.");
            }
            return $query;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Delete an episode from a library.
    public function delete_episode($episode_id) {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        if (isset($_SESSION['episodes'])) {
            $episodes = $_SESSION['episodes'];
        }

        //Define the MySQL delete statement.
        $sql = "DELETE FROM libraries WHERE episode_id = $episode_id AND user_id = $user_id";

        //Remove from the episode session.
        $index = array_search($episode_id, $episodes);
        unset($_SESSION["episodes"][$index]);


        try {

            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error removing the episode from your library.");
            }
            return $query;
        } catch (DatabaseException $e) {
            $e->getMessage();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function series_names() {
        $sql = "SELECT " . $this->tblTv . ".title, " . $this->tblEpisode . ".series_id FROM " . $this->tblEpisode . "," . $this->tblTv . " WHERE " . $this->tblEpisode . ".series_id=" . $this->tblTv . ".id";
        try {
            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query || $query->num_rows == 0) {
                throw new DatabaseException("There was an error getting series names.");
            }

            //loop through all rows
            $series_names = array();
            while ($obj = $query->fetch_object()) {
                $series_names[$obj->series_id] = $obj->title;
            }
            return $series_names;
        } catch (DatabaseException $e) {
            $e->getMessage();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

}
