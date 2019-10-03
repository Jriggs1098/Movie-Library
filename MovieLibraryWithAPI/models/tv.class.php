<?php
/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: tv.class.php
 * Description: This class models a tv series.
 */

class Tv {

    //Private data members.
    private $id, $title, $years, $rating, $imdb, $genre, $seasons, $image, $plot;

    //Constructor
    public function __construct($title, $years, $rating, $imdb, $genre, $seasons, $image, $plot) {
        $this->title = $title;
        $this->years = $years;
        $this->rating = $rating;
        $this->imdb = $imdb;
        $this->genre = $genre;
        $this->seasons = $seasons;
        $this->image = $image;
        $this->plot = $plot;
    }

    //Get series id.
    public function getId() {
        return $this->id;
    }
    
    //Get series title.
    public function getTitle() {
        return $this->title;
    }

    //Get series year.
    public function getYears() {
        return $this->years;
    }

    //Get series rating.
    public function getRating() {
        return $this->rating;
    }

    //Get series Imdb rating.
    public function getImdb() {
        return $this->imdb;
    }

    //Get series genre.
    public function getGenre() {
        return $this->genre;
    }

    //Get series seasons.
    public function getSeasons() {
        return $this->seasons;
    }
    
    //Get series image.
    public function getImage() {
        return $this->image;
    }

    //Get series plot.
    public function getPlot() {
        return $this->plot;
    }

    //Set series id.
    public function setId($id) {
        $this->id = $id;
    }

}
