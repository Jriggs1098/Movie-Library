<?php
/*
 * Author: Jack Riggs
 * Date: Apr 15, 2019
 * File: omdb.class.php
 * Description: This class defines the OMDB API Object.
 */

class Omdb {
    
    //Request url variable.
    private static $request_url = 'omdbapi.com/?apikey=c261a98a&';

    //Class instance.  
    private static $instance = null;
    
    // Private Constructor.
    private function __construct() {
        
    }

    //Static method to ensure there is just one instance of the Omdb class.
    static public function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new Omdb();
        }
        return self::$instance;
    }

    //Take request url as parameter and make request to API server.  Returns a JSON object.
    static private function make_request($request_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: application/json"
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //Get information on a single product by its title.
    static public function get_info_by_title( $title, $type, $year) {
        $request_url = self::$request_url;
        $request_url .= "t=" . $title . "&type=" . $type . "&y=" . $year;
        $data = self::make_request($request_url);
        $json = json_decode($data);
        
        //Check if the response request was successful.
        if ($json->Response == "False") {
            return false;
        }
        return ($json);        
    }
    
    //Get the information for each episode in a specific season of a tv show.
    static public function get_info_by_season($title, $season_number, $type) {
        $request_url = self::$request_url;
        $request_url .= "t=" . $title . "&season=" . $season_number. "&type=" . $type;
        $data = self::make_request($request_url);
        $json = json_decode($data);
        //Check if the response request was successful.
        if ($json->Response == "False") {
            return false;
        }
        return ($json); 
    }
    
    //Get the information on a specific episode from a tv show using its season and episode number.
    static public function get_info_by_episode_number($title, $season_number, $episode_number, $type) {
        $request_url = self::$request_url;
        $request_url .= "t=" . $title . "&season=" . $season_number . "&episode=" . $episode_number. "&type=" . $type;
        $data = self::make_request($request_url);
        $json = json_decode($data);
        //Check if the response request was successful.
        if ($json->Response == "False") {
            return false;
        }
        return ($json); 
    }

    //Get the basic information on 10 products at a time that relate to the title searched. When searching, the "type" is optional,
    //but selecting an option can help narrow the search.
    static public function get_info_by_search($search_terms, $type) {
        $request_url = self::$request_url;
        $request_url .= "s=" . $search_terms . "&type=" . $type;
        $data = self::make_request($request_url);
        
        //Return search results as an associative array to access each item's information.
        $json = json_decode($data, true);
        //Check if the response request was successful.
        if ($json['Response'] == "False") {
            return false;
        }
        return ($json); 
    }

}
