<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_library.class.php
 * Description: Show the current user's library.
 */

class UserLibrary extends IndexView {

    //Display movies function.
    public function display($confirm = "") {
        //display page header
        parent::displayHeader("User Library");

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['movies'])) {
            $movies = $_SESSION['movies'];
        }
        if (isset($_SESSION['episodes'])) {
            $episodes = $_SESSION['episodes'];
        }
        
        ?>

        <div class="content">
            <?php
            if ($confirm != "") {
                echo "<h1>$confirm</h1><br>";
            }
            //var_dump($movies);
            ?>
            <h1>Movies in My Library</h1>
            <div class="content-row">
                <table>
        <?php
        if ($movies === 0) {
            echo "<p>No movies have been saved in your library.</p>";

        } else {
            //Display movies with the "blur" hover effect.
            foreach ($movies as $i => $movie) {
                $m_id = $movie->getId();
                $title = $movie->getTitle();
                $image = $movie->getImage();

                //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
                if (!strpos($image, "https://")) {
                    $image = str_replace("http://", "https://", $image);
                }

                echo "<tr><div class='blur-back'><a href='". BASE_URL. "/user/mdelete/$m_id' onclick='return confirm(\"Are you sure you want to remove this from your library?\")'><img src='" . $image . "' class='poster'/><h2>" . $title . "</h2><h3>*Click to remove from library*</h3></a></div></tr>";
            }
        }
        ?>
                </table>
            </div>
        </div>                  

        <div class="content">
            <h1>Episodes in My Library</h1>
            <div class="content-row">
                <table>
        <?php
                    if ($episodes === 0) {
                        echo "<p>No episodes were found in your library.</p>";

                    } else {
                        //Display epsisodes for that season.
                        foreach ($episodes as $i => $episode) {
                            $e_id = $episode->getId();
                            $series_id = $episode->getSeries_id();
                            $episode_number = $episode->getEpisode();
                            $season = $episode->getSeason();
                            $e_title = $episode->getTitle();
                            $e_image = $episode->getImage();

                            //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
                            if (!strpos($e_image, "https://")) {
                                $e_image = str_replace("http://", "https://", $e_image);
                            }
                            
                            //Get series name from session.
                            if(isset($_SESSION['series_names'])) {
                                $series_names = $_SESSION['series_names'];
                                
                                $series_name = $series_names[$series_id];
                            }
                            
                            echo "<tr><div class='blur-back'><a href='". BASE_URL. "/user/edelete/$e_id' onclick='return confirm(\"Are you sure you want to remove this from your library?\")'><img src='" . $e_image . "' class='poster' style='width:150px; height:142px;'/><h2>" . $e_title . "<br>*Click to remove*</h2><h3>" . $series_name . "<br>Season " . $season . ", Ep. " . $episode_number . "</h3></a></div></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <?php
            //display page footer
            parent::displayFooter();
        }

//end of display method
    }

}
