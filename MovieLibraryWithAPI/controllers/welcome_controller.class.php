<?php

/*
 * Author: Jack Riggs
 * Date: April 7, 2019
 * File: welcome_controller.class.php
 * Description: This scripts define the class for the welcome controller; this is the default controller.
 */

class WelcomeController {

    //Access Models to get information.
    private $movie_model;

    //Default constructor.
    public function __construct() {
        //Create an instance of each Model class.
        $this->movie_model = MovieModel::getMovieModel();
        $this->tv_model = TvModel::getTvModel();
        $this->episode_model = EpisodeModel::getEpisodeModel();
    }

    //Get movies to show as "Coming soon".
    public function index() {

        //Retrieve all movies and store them in an array.
        $movies = $this->movie_model->list_movie();

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        $view = new WelcomeIndex();
        $view->display($movies);
    }

    //Search movies, tv, and episodes.
    public function search() {
        //Retrieve query terms from search form
        $query_terms = trim($_GET['query-terms']);

        //Search the database for matching movies.
        $movies = $this->movie_model->search_movie($query_terms);

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        //Search the database for matching shows.
        $tvs = $this->tv_model->search_tv($query_terms);

        //If the return value is a string (message).
        if (is_string($tvs)) {
            //Display an error
            $this->error($tvs);
            return;
        }
        
        //Search the database for matching episodes.
        $episodes = $this->episode_model->search_episode($query_terms);

        //If the return value is a string (message).
        if (is_string($episodes)) {
            //Display an error
            $this->error($episodes);
            return;
        }
        
        //Display matched items.
        $search = new WelcomeSearch();
        $search->display($query_terms, $movies, $tvs, $episodes);
    }

    //autosuggestion
    public function suggest($terms) {
        //retrieve query terms
        $query_terms = urldecode(trim($terms));
        //Search the database for matching movies.
        $movies = $this->movie_model->search_movie($query_terms);

        //If the return value is a string (message).
        if (is_string($movies)) {
            //Display an error
            $this->error($movies);
            return;
        }

        //Search the database for matching shows.
        $tvs = $this->tv_model->search_tv($query_terms);

        //If the return value is a string (message).
        if (is_string($tvs)) {
            //Display an error
            $this->error($tvs);
            return;
        }
        
        //Search the database for matching episodes.
        $episodes = $this->episode_model->search_episode($query_terms);

        //If the return value is a string (message).
        if (is_string($episodes)) {
            //Display an error
            $this->error($episodes);
            return;
        }

        //retrieve all movie titles and store them in an array
        $titles = array();
        if ($movies) {
            foreach ($movies as $movie) {
                $titles[] = $movie->getTitle();
            }
        }

        if ($tvs) {
            foreach ($tvs as $tv) {
                $titles[] = $tv->getTitle();
            }
        }

        if ($episodes) {
            foreach ($episodes as $episode) {
                $titles[] = $episode->getTitle();
            }
        }

        echo json_encode($titles);
    }
    
    //Handle an error.
    public function error($message) {
        //Create an object of the Error class.
        $error = new WelcomeError();

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
