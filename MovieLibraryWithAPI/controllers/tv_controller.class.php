<?php

/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: tv_controller.class.php
 * Description: Controller for tv shows.
 */

class TvController {

    private $tv_model;

    //Default constructor.
    public function __construct() {
        //Create an instance of the TvModel class.
        $this->tv_model = TvModel::getTvModel();
    }

    //Index action. Display all tv shows.
    public function index() {
        //Retrieve all tv shows and store them in an array.
        $tvs = $this->tv_model->list_tv();

        //If the return value is a string (message).
        if (is_string($tvs)) {
            //Display an error
            $this->error($tvs);
            return;
        }

        //Display all tv shows.
        $view = new TvIndex();
        $view->display($tvs);
    }

    //Detail action.  Show details of a specific tv series based on its id.
    public function detail($id) {
        //Retrieve the specific movie using id.
        $tv = $this->tv_model->view_tv($id);

        //If the return value is a string (message).
        if (is_string($tv)) {
            //Display an error
            $this->error($tv);
            return;
        }

        //display movie details
        $view = new TvDetail();
        $view->display($tv);
    }

    //Display a show edit form.
    public function edit($id) {
        //Retrieve the specific show.
        $tv = $this->tv_model->view_tv($id);

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

        //If the return value is a string (message).
        if (is_string($tv)) {
            //Display an error
            $this->error($tv);
            return;
        }

        $view = new TvEdit();
        $view->display($tv);
    }

    //Update an edited show in the database.
    public function update($id) {
        //Update the show.
        $update = $this->tv_model->update_tv($id);

        //If the return value is a string (message).
        if (is_string($update)) {
            //Display an error
            $this->error($update);
            return;
        }

        //Display updated movie.
        $confirm = "The show was successfully updated.";
        $tv = $this->tv_model->view_tv($id);
        
        //If the return value is a string (message).
        if (is_string($tv)) {
            //Display an error
            $this->error($tv);
            return;
        }

        $view = new TvDetail();
        $view->display($tv, $confirm);
    }

    //Display form for user to add a tv show.
    public function add() {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Do not give access unless user is an admin.
        if (!isset($_SESSION['user_type']) ||$_SESSION['user_type'] != 1) {
            $message = "You do not have access to this page.";
            $this->error($message);
            return;
        }
        
        $view = new TvAdd();
        $view->display();
    }

    //Insert a new show into the database. If successful, show the new show in the index page.
    public function insert() {
        //Insert the show.
        $insert = $this->tv_model->insert_tv();

        //If the return value is a string (message).
        if (is_string($insert)) {
            //Display an error
            $this->error($insert);
            return;
        }

        //Display new movie.
        $confirm = "The show was successfully added.";

        //Retrieve all movies and store them in an array.
        $tvs = $this->tv_model->list_tv();
        
        //If the return value is a string (message).
        if (is_string($tvs)) {
            //Display an error
            $this->error($tvs);
            return;
        }

        $view = new TvIndex();
        $view->display($tvs, $confirm);
    }
    
    //autosuggestion for adding new shows.
    public function suggest($terms) {
        
        //retrieve query terms
        $query_terms = urldecode(trim($terms));
        //Search the database for matching movies.
        $tvs = $this->tv_model->suggest_tv($query_terms);

        //If the return value is a string (message).
        if (is_string($tvs)) {
            //Display an error
            $this->error($tvs);
            return;
        }

        //retrieve all movie titles and store them in an array
        $titles = array();
        if ($tvs) {
            foreach ($tvs as $tv) {
                $titles[] = $tv->getTitle();
            }
        }
        echo json_encode($titles);
    }

    //Handle an error.
    public function error($message) {
        //Create an object of the Error class.
        $error = new TvError();

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
