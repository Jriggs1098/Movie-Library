<?php

/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: movie_controller.class.php
 * Description: Controller for movies.
 */

class MovieController {

    private $movie_model;

    //Default constructor.
    public function __construct() {
        //Create an instance of the MovieModel class.
        $this->movie_model = MovieModel::getMovieModel();
    }

    //Index action. Display all movies.
    public function index() {
        //Retrieve all movies and store them in an array.
        $movies = $this->movie_model->list_movie();

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        //Display all movies.
        $view = new MovieIndex();
        $view->display($movies);
    }

    //Detail action.  Show details of a specific movie based on its id.
    public function detail($id) {
        //Retrieve the specific movie using id.
        $movie = $this->movie_model->view_movie($id);

        //If the return value is a string (message).
        if (is_string($movie)) {
            //Display an error
            $this->error($movie);
            return;
        }

        //display movie details
        $view = new MovieDetail();
        $view->display($movie);
    }

    //Display a movie edit form.
    public function edit($id) {
        //Retrieve the specific movie.
        $movie = $this->movie_model->view_movie($id);

        //If the return value is a string (message).
        if (is_string($movie)) {
            //Display an error
            $this->error($movie);
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

        $view = new MovieEdit();
        $view->display($movie);
    }

    //Update an edited movie in the database.
    public function update($id) {
        //Update the movie.
        $update = $this->movie_model->update_movie($id);

        //If the return value is a string (message).
        if (is_string($update)) {
            //Display an error
            $this->error($update);
            return;
        }

        //Display updated movie.
        $confirm = "The movie was successfully updated.";
        $movie = $this->movie_model->view_movie($id);

        //If the return value is a string (message).
        if (is_string($movie)) {
            //Display an error
            $this->error($movie);
            return;
        }

        $view = new MovieDetail();
        $view->display($movie, $confirm);
    }

    //Display form for user to add a movie.
    public function add() {
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

        $view = new MovieAdd();
        $view->display();
    }

    //Insert a new movie into the database. If successful, show the new movie in the index page.
    public function insert() {
        //Insert the movie.
        $insert = $this->movie_model->insert_movie();

        if (is_string($insert)) {
            //handle errors
            $this->error($insert);
            return;
        }

        //Display new movie.
        $confirm = "The movie was successfully added.";

        //Retrieve all movies and store them in an array.
        $movies = $this->movie_model->list_movie();

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        $view = new MovieIndex();
        $view->display($movies, $confirm);
    }

    //Delete a movie from the database.
    public function delete($id) {
        $delete = $this->movie_model->delete_movie($id);

        //If the return value is a string (message).
        if (is_string($delete)) {
            //Display an error
            $this->error($delete);
            return;
        }

        //Display confirmation message.
        $confirm = "The movie was successfully removed from your library.";

        //Retrieve all movies and store them in an array.
        $movies = $this->movie_model->list_movie();

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        $view = new MovieIndex();
        $view->display($movies, $confirm);
    }

    //autosuggestion for adding new movies.
    public function suggest($terms) {
        
        //retrieve query terms
        $query_terms = urldecode(trim($terms));
        //Search the database for matching movies.
        $movies = $this->movie_model->suggest_movie($query_terms);

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        //retrieve all movie titles and store them in an array
        $titles = array();
        if ($movies) {
            foreach ($movies as $movie) {
                $titles[] = $movie->getTitle();
            }
        }
        echo json_encode($titles);
    }

    //Handle an error.
    public function error($message) {
        //Create an object of the Error class.
        $error = new MovieError();

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
