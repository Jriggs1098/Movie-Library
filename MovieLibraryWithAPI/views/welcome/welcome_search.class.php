<?php
/*
 * Author: Jack Riggs
 * Date: Apr 15, 2019
 * File: welcome_search.class.php
 * Description: Display method to display search results.
 */

class WelcomeSearch extends IndexView {

    //Display movies function.
    public function display($query_terms, $movies, $tvs, $episodes) {
        //display page header
        parent::displayHeader("Search Results");
        ?>

        <div class="content">
            <h1>Movies Matching: <?= $query_terms ?></h1>
            <div class="content-row">
                <table>
                    <?php
                    if (!is_array($movies)) {
                        echo "<p>No movies were found matching that search.</p>";
                    } else {
                        //Display movies with the "blur" hover effect.
                        foreach ($movies as $i => $movie) {
                            $m_id = $movie->getId();
                            $m_title = $movie->getTitle();
                            $year = $movie->getYear();
                            $runtime = $movie->getRuntime();
                            $m_rating = $movie->getRating();
                            $m_imdb = $movie->getImdb();
                            $m_image = $movie->getImage();

                            //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s").
                            if (!strpos($m_image, "https://")) {
                                $m_image = str_replace("http://", "https://", $m_image);
                            }

                            echo "<tr><div class='blur-back'><a href='", BASE_URL, "/movie/detail/$m_id'><img src='" . $m_image . "' class='poster'/><h2>" . $m_title . "</h2><h3>" . $year . "<br>" . $runtime . "<br>" . $m_rating . "<br><br>IMDB: " . $m_imdb . "</h3></a></div></tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="content">
            <h1>Shows Matching: <?= $query_terms ?></h1>
            <div class="content-row">
                <table>
                    <?php
                    if ($tvs === 0) {
                        echo "<p>No shows were found matching that search.</p>";

                    } else {
                        //Display tv with the "blur" hover effect.
                        foreach ($tvs as $i => $tv) {
                            $t_id = $tv->getId();
                            $t_title = $tv->getTitle();
                            $seasons = $tv->getSeasons();
                            $t_rating = $tv->getRating();
                            $t_imdb = $tv->getImdb();
                            $t_image = $tv->getImage();

                            //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
                            if (!strpos($t_image, "https://")) {
                                $t_image = str_replace("http://", "https://", $t_image);
                            }

                            //Determine if there is more than 1 season.
                            if ($seasons == 1) {
                                $seasons_text = " Season";
                            } else {
                                $seasons_text = " Seasons";
                            }

                            echo "<tr><div class='blur-back'><a href='", BASE_URL, "/tv/detail/$t_id'><img src='" . $t_image . "' class='poster'/><h2>" . $t_title . "</h2><h3>" . $seasons . $seasons_text . "<br>" . $t_rating . "<br><br><br>IMDB: " . $t_imdb . "</h3></a></div></tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="content">
            <h1>Episodes Matching: <?= $query_terms ?></h1>
            <div class="content-row">
                <table>
                    <?php
                    if ($episodes === 0) {
                        echo "<p>No episodes were found matching that search.</p>";
                        //display page footer
                        parent::displayFooter();
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
                            } else {
                                $series_names = $this->series_names();
                                $_SESSION['series_names'] = $series_names;
                            }
                            
                            echo "<tr><div class='blur-back'><a href='". BASE_URL. "/tv/detail/$series_id'><img src='" . $e_image . "' class='poster' style='width:150px; height:142px;'/><h2>" . $e_title . "</h2><h3>" . $series_name . "<br>Season " . $season . ", Ep. " . $episode_number . "</h3></a></div></tr>";
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
