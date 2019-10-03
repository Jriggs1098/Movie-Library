<?php
/*
 * Author: Jack Riggs
 * Date: April 7, 2019
 * Name: index.class.php
 * Description: This class defines the method "display" that displays the home page.
 */

class WelcomeIndex extends IndexView {

    public function display($movies) {
        //display page header
        parent::displayHeader("Digital Video Home");
        ?> 
        <div class="content">
            <h1>About</h1>
            <div class="content-row" style="padding-top: 20px; padding-left: 5px; text-align: left;">
                <p>We are a digital video platform that allows you to watch different movies and TV Shows.</p>
                <p>As some would say, this is a better version of Netflix...with less content...</p><br><br>
                <p>You can view our entire inventory of movies and shows using the links above.</p><br><br>
                <p>Below, you can see some recent theater-releases that will become available to you as soon as possible.</p>
            </div>
        </div>

        <div class="content">
            <h1>Coming Soon</h1>
            <div class="content-row">
                <table>
                    <?php
                    //Set current date.
                    $current_date = strtotime(date("d M Y"));

                    if ($movies === 0) {
                        echo "No movie was found.";
                    } else {
                        //Display movies with the "blur" hover effect.
                        foreach ($movies as $i => $movie) {
                            $id = $movie->getId();
                            $title = $movie->getTitle();
                            $year = $movie->getYear();
                            $runtime = $movie->getRuntime();
                            $rating = $movie->getRating();
                            $imdb = $movie->getImdb();
                            $image = $movie->getImage();
                            $dvd = $movie->getDvd();

                            //Check the current date vs the dvd date of a movie.  If it is greater than the current date or is unkown (0000-00-00), display it in table.
                            if (strtotime($dvd) > $current_date || $dvd == "N/A") {
                                echo "<tr><div class='blur-back'><a href='", BASE_URL, "/movie/detail/$id'><img src='" . $image . "' class='poster'/><h2>" . $title . "</h2><h3>" . $year . "<br>" . $runtime . "<br>" . $rating . "<br><br>IMDB: " . $imdb . "</h3></a></div></tr>";
                            }
                        }
                    }
                    ?>
                </table>

            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

}
