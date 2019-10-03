<?php

/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: episode_model.class.php
 * Description: Episode Model.
 */

class EpisodeModel {

    //Private data members.
    private $db;
    private $dbConnection;
    static private $_instance = NULL;
    private $tblEpisode;
    private $tblTv;

    //To use singleton pattern, this constructor is made private. To get an instance of the class, the getEpisodeModel method must be called.
    private function __construct() {
        $this->db = Database::getDatabase();
        $this->dbConnection = $this->db->getConnection();
        $this->tblEpisode = $this->db->getEpisodeTable();
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

    //Static method to ensure there is just one EpisodeModel instance.
    public static function getEpisodeModel() {
        if (self::$_instance == NULL) {
            self::$_instance = new EpisodeModel();
        }
        return self::$_instance;
    }

    //Retrieves all episodes for a season.  Uses the series id and season number as a paramter  Returns false if failed.
    public function list_episode($id, $season) {

        //Select statement. Order episodes by episode number.
        $sql = "SELECT * FROM " . $this->tblEpisode .
                " WHERE series_id='$id'" .
                " AND season_number='$season' " .
                "ORDER BY episode_number";

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //Create session to store series name.
        $series_names = $this->series_names();
        $_SESSION['series_names'] = $series_names;


        try {
            //Execute the query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query) {
                throw new DatabaseException("There was an error listing the episodes.");
            }

            if ($query->num_rows == 0) {
                throw new DatabaseException("There was an error. We do not have episodes for this season.");
            }

            //Create an array to store all returned episodes.
            $episodes = array();

            //Get series name from session.
            if (isset($_SESSION['series_names'])) {
                $series_names = $_SESSION['series_names'];

                $series_name = $series_names[$id];
            }

            //Loop through all rows in the returned recordsets
            while ($obj = $query->fetch_object()) {

                $title_series = urlencode($series_name);
                $season_number = urlencode($obj->season_number);
                $type = "series";
                $episode_number = $obj->episode_number;
                $json = Omdb::get_info_by_episode_number($title_series, $season_number, $episode_number, $type);

                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }

                $episode = new Episode(stripslashes($series_name), stripslashes($json->Season), stripslashes($json->Episode), stripslashes($json->Title), stripslashes($json->Released), stripslashes($json->Runtime), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the episode
                $episode->setId($obj->id);

                //add the movie into the array
                $episodes[] = $episode;
            }
            return $episodes;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Retieve details for a specific episode. Return false if failed.
    public function view_episode($id) {
        //The select sql statement.
        $sql = "SELECT * FROM " . $this->tblEpisode .
                " WHERE " . $this->tblEpisode . ".id='$id'";

        try {
            //Execute the query.
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query || $query->num_rows == 0) {
                throw new DatabaseException("There was an error getting the episode details.");
            }

            if ($query && $query->num_rows > 0) {
                $obj = $query->fetch_object();
                $series_id = $obj->series_id;

                //See if session exists already, if not start one.
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                //Get series name from session.
                if (isset($_SESSION['series_names'])) {
                    $series_names = $_SESSION['series_names'];

                    $series_name = $series_names[$series_id];
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

                //Create an episode object.
                $episode = new Episode(stripslashes($obj->series_id), stripslashes($json->Season), stripslashes($json->Episode), stripslashes($json->Title), stripslashes($json->Released), stripslashes($json->Runtime), stripslashes($json->Poster), stripslashes($json->Plot));
                //set the id for the movie
                $episode->setId($obj->id);

                //Session to store number of seasons.
                $_SESSION['series_id'] = $obj->series_id;
                //Session to store current season (used for update).
                $_SESSION['season'] = $obj->season_number;

                return $episode;
            }
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Update an existing episode in the database. Details of the show are posted in a form. Return true if succeed; false otherwise.
    public function update_episode($id) {
        try {
            //If the script did not received post data, display an error message and then terminite the script immediately
            if (!filter_has_var(INPUT_POST, 'season') || !filter_has_var(INPUT_POST, 'episode')) {

                throw new DataMissingException("Please fill in all fields.");
            }

            //Retrieve data for the new movie; data are sanitized and escaped for security.
            $season_number = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'season', FILTER_SANITIZE_NUMBER_INT)));
            $episode_number = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'episode', FILTER_SANITIZE_NUMBER_INT)));

            if ($season_number == "" || $episode_number == "") {
                throw new DataMissingException("Please fill in all fields.");
            }

            //query string for update 
            $sql = "UPDATE " . $this->tblEpisode .
                    " SET season_number='$season_number', episode_number='$episode_number' WHERE id='$id'";

            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return error. 
            if (!$query) {
                throw new DatabaseException("There was an error updating the episode.");
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

    //Search the database for episodes that match words in titles. Return an array of episodes if succeed; false otherwise.
    public function search_episode($query_terms) {
        $terms = explode(" ", $query_terms); //explode multiple terms into an array
        //select statement for AND search
        $sql = "SELECT * FROM " . $this->tblEpisode . " WHERE series_id > 0 ";

        //Search in titles and directors. User can also search for an exact year, if they know what year they want but not the specific item.
        foreach ($terms as $term) {
            $sql .= " AND title LIKE '%" . $term . "%'";
        }

        //Order by title.
        $sql .= " ORDER BY series_id, title ";

        try {
            //execute the query
            $query = $this->dbConnection->query($sql);

            //If the query failed, return false. 
            if (!$query) {
                throw new DatabaseException("There was an error searching for the episodes.");
            }

            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            //Create session to store series name.
            $series_names = $this->series_names();
            $_SESSION['series_names'] = $series_names;


            //search succeeded, and found at least 1 movie found.
            //create an array to store all the returned shows.
            $episodes = array();

            //Loop through all rows in the returned recordsets
            while ($obj = $query->fetch_object()) {
                $series_id = $obj->series_id;
                //Get series name from session.
                if (isset($_SESSION['series_names'])) {
                    $series_names = $_SESSION['series_names'];

                    $series_name = $series_names[$series_id];
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

                $episode = new Episode(stripslashes($series_id), stripslashes($json->Season), stripslashes($json->Episode), stripslashes($json->Title), stripslashes($json->Released), stripslashes($json->Runtime), stripslashes($json->Poster), stripslashes($json->Plot));

                //set the id for the episode
                $episode->setId($obj->id);

                //add the movie into the array
                $episodes[] = $episode;
            }
            return $episodes;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function series_names() {
        $sql = "SELECT title, id FROM " . $this->tblTv;
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
                $series_names[$obj->id] = $obj->title;
            }
            return $series_names;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insert_episode($id) {
        try {
            //If the script did not received post data, display an error message and then terminite the script immediately
            if (!filter_has_var(INPUT_POST, 'season')) {

                throw new DataMissingException("Please fill in all fields.");
            }

            //Retrieve data for the new movie; data are sanitized and escaped for security.
            $season_number = $this->dbConnection->real_escape_string(trim(filter_input(INPUT_POST, 'season', FILTER_SANITIZE_NUMBER_INT)));

            if ($season_number == "") {
                throw new DataMissingException("Please fill in all fields.");
            }

            //See if session exists already, if not start one.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['series_names'])) {
                $series_names = $_SESSION['series_names'];
                $series_name = $series_names[$id];
            }

            $title_series = urlencode($series_name);
            $type = "series";
            //$json = Omdb::get_info_by_episode_number($title_series, $season_number, $episode_number, $type);
            $json = Omdb::get_info_by_season($title_series, $season_number, $type);

            //If returned false, throw an error.
            if (!$json) {
                throw new ApiDataException("There was an error connecting to the api.");
            }

            $title = urlencode($json->Title);
            $num_episodes = count($json->Episodes);

            //Save season and series_id into a session.
            $_SESSION['season'] = $season_number;
            $_SESSION['series_id'] = $id;

            for ($i = 1; $i <= $num_episodes; $i++) {
                $episode_number = $i;
                $json = Omdb::get_info_by_episode_number($title, $season_number, $episode_number, $type);
                
                //If returned false, throw an error.
                if (!$json) {
                    throw new ApiDataException("There was an error connecting to the api.");
                }
                
                //Define the MySQL insert statement.
                $ep_title = $json->Title;
                $sql = "INSERT INTO " . $this->tblEpisode .
                        " VALUES ('$season_number', '$episode_number', '$ep_title', NULL, '$id')";

                //execute the query
                $query = $this->dbConnection->query($sql);

                //If the query failed, return error. 
                if (!$query) {
                    throw new DatabaseException("There was an error adding that season.");
                }
            }
        } catch (DataMissingException $e) {
            return $e->getMessage();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        } catch (ApiDataException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
