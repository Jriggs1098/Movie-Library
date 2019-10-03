<?php

/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: episode.class.php
 * Description: This class models an episode object.
 */

class Episode {

    //Private data members.
    private $id, $series_id, $season, $episode, $title, $release_date, $runtime, $image, $plot;

    //Constructor
    public function __construct($series_id, $season, $episode, $title, $release_date, $runtime, $image, $plot) {
        $this->series_id = $series_id;
        $this->season = $season;
        $this->episode = $episode;
        $this->title = $title;
        $this->release_date = $release_date;
        $this->runtime = $runtime;
        $this->image = $image;
        $this->plot = $plot;
    }
    
    //Get episode id.
    public function getId() {
        return $this->id;
    }

    //Get series id.
    public function getSeries_id() {
        return $this->series_id;
    }

    //Get episode season number.
    public function getSeason() {
        return $this->season;
    }

    //Get episode number.
    public function getEpisode() {
        return $this->episode;
    }

    //Get episode title.
    public function getTitle() {
        return $this->title;
    }

    //Get episode release date.
    public function getRelease_date() {
        return $this->release_date;
    }
    
    //Get episode runtime.
    public function getRuntime() {
        return $this->runtime;
    }

    //Get episode image.
    public function getImage() {
        return $this->image;
    }

    //Get episode plot.
    public function getPlot() {
        return $this->plot;
    }

    //Need id for when editing/deleting
    //Set series id.
    public function setId($id) {
        $this->id = $id;
    }

}
