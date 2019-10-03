<?php

/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: episode_controller.class.php
 * Description: Controller for individual episodes.
 */

class EpisodeController {

    private $episode_model;

    //Default constructor.
    public function __construct() {
        //Create an instance of the EpisodeModel class.
        $this->episode_model = EpisodeModel::getEpisodeModel();
    }

    //Index action. Display all episodes for that season.
    public function index($series_id) {

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //Get the season variable.  If the session variable is not set, use the session variable (used for Update).
        if (isset($_POST['season'])) {
            $season = $_POST['season'];
        } else {
            $season = $_SESSION['season'];
        }

        //Retrieve all episodes and store them in an array.
        $episodes = $this->episode_model->list_episode($series_id, $season);

        //If the return value is a string (message).
        if (is_string($episodes)) {
            //Display an error
            $this->error($episodes);
            return;
        }
        
        //Display all episodes for that season.
        $view = new EpisodeIndex();
        $view->display($episodes, $season, $series_id);
    }

    //Detail action.  Get the details for a specific episode, but dont need to display on a separate page.
    public function detail($id) {
        //Retrieve the specific episode using id.
        $episode = $this->episode_model->view_episode($id);

        //If the return value is a string (message).
        if (is_string($episode)) {
            //Display an error
            $this->error($episode);
            return;
        }
    }

    //Display an episode's edit form.
    public function edit($id) {
        //Retrieve the specific episode.
        $episode = $this->episode_model->view_episode($id);

        //If the return value is a string (message).
        if (is_string($episode)) {
            //Display an error
            $this->error($episode);
            return;
        }

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Do not give access unless user is an admin.
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 1) {
            $message = "You do not have access to this page.";
            $this->error($message);
            return;
        }

        $view = new EpisodeEdit();
        $view->display($episode);
    }

    //Update an edited episode in the database.
    public function update($id) {
        //Update the episode.
        $update = $this->episode_model->update_episode($id);

        //Get the season variable.
        if (isset($_POST['season'])) {
            $season = $_POST['season'];
        }

        //If the return value is a string (message).
        if (is_string($update)) {
            //Display an error
            $this->error($update);
            return;
        }

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //Get series id from session variable.
        if (isset($_SESSION['series_id'])) {
            $series_id = $_SESSION['series_id'];
        }

        //Display updated list of episodes.
        $confirm = "The episode was successfully updated.";
        $episodes = $this->episode_model->list_episode($series_id, $season);
        
        //If the return value is a string (message).
        if (is_string($episodes)) {
            //Display an error
            $this->error($episodes);
            return;
        }

        $view = new EpisodeIndex();
        $view->display($episodes, $season, $series_id, $confirm);
    }

    //Display form for user to add an episode.
    public function add($id) {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Do not give access unless user is an admin.
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 1) {
            $message = "You do not have access to this page.";
            $this->error($message);
            return;
        }

        $view = new EpisodeAdd();
        $view->display($id);
    }

    //Insert a new show into the database. If successful, show the new show in the index page.
    public function insert($id) {
        //Insert the show.
        $insert = $this->episode_model->insert_episode($id);

        //If the return value is a string (message).
        if (is_string($insert)) {
            //Display an error
            $this->error($insert);
            return;
        }

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //Get season from session variable.
        if (isset($_SESSION['season'])) {
            $season = $_SESSION['season'];
        }

        //Get series id from session variable.
        if (isset($_SESSION['series_id'])) {
            $series_id = $_SESSION['series_id'];
        }

        //Display new episode.
        $confirm = "The episodes were successfully added.";

        //Retrieve all movies and store them in an array.
        $episodes = $this->episode_model->list_episode($series_id, $season);
        
        //If the return value is a string (message).
        if (is_string($episodes)) {
            //Display an error
            $this->error($episodes);
            return;
        }

        $view = new EpisodeIndex();
        $view->display($episodes, $season, $series_id, $confirm);
    }

    //Handle an error.
    public function error($message) {
        //Create an object of the Error class.
        $error = new EpisodeError();

        //Display the error page.
        $error->display($message);
    }

    //Handle calling inaccessible methods.
    public function __call($name, $arguments) {
        // Note: value of $name is case sensitive.
        $message = "Calling method '$name' caused errors. Route does not exist.";
        $this->error($message);
        return;
    }

}
