<?php
/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: movie.class.php
 * Description: This class models a movie object.
 */

class Movie {

    //Private data members.
    private $id, $title, $year, $rating, $runtime, $imdb, $genre, $director, $dvd, $image, $plot;

    //Constructor
    public function __construct($title, $year, $rating, $runtime, $imdb, $genre, $director, $dvd, $image, $plot) {
        $this->title = $title;
        $this->year = $year;
        $this->rating = $rating;
        $this->runtime = $runtime;
        $this->imdb = $imdb;
        $this->genre = $genre;
        $this->director = $director;
        $this->dvd = $dvd;
        $this->image = $image;
        $this->plot = $plot;
    }

    //Get movie id.
    public function getId() {
        return $this->id;
    }

    //Get movie title.
    public function getTitle() {
        return $this->title;
    }

    //Get movie year.
    public function getYear() {
        return $this->year;
    }
    
    //Get movie rating.
    public function getRating() {
        return $this->rating;
    }
    
    //Get movie runtime.
    public function getRuntime() {
        return $this->runtime;
    }

    //Get movie imdb rating.
    public function getImdb() {
        return $this->imdb;
    }

    //Get movie genre.
    public function getGenre() {
        return $this->genre;
    }

    //Get movie director.
    public function getDirector() {
        return $this->director;
    }

    //Get movie dvd date.
    public function getDvd() {
        return $this->dvd;
    }

    //Get movie image.
    public function getImage() {
        return $this->image;
    }

    //Get movie plot.
    public function getPlot() {
        return $this->plot;
    }

    //Set movie id.
    public function setId($id) {
        $this->id = $id;
    }

}
