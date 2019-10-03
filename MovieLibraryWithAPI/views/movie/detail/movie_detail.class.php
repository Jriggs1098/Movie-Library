<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: movie_detail.class.php
 * Description: Defines display method for displaying a movie's details.
 */

class MovieDetail extends IndexView {

    public function display($movie, $confirm = "") {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Display page header from parent class.
        parent::displayHeader("Movie Details");

        $id = $movie->getId();
        $title = $movie->getTitle();
        $year = $movie->getYear();
        $runtime = $movie->getRuntime();
        $rating = $movie->getRating();
        $imdb = $movie->getImdb();
        $dvd = $movie->getDvd();
        $director = $movie->getDirector();
        $genre = $movie->getGenre();
        $image = $movie->getImage();
        $plot = $movie->getPlot();

        //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
        if (!strpos($image, "https://")) {
            $image = str_replace("http://", "https://", $image);
        }
        ?>

        <!-- display movie details-->
        <div class="detail-row">
            <h1>Movie Details <?php
                if ($confirm != "") {
                    echo "- ", $confirm;
                }
                ?></h1>
            <hr>
            <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {

                echo "<a href='" . BASE_URL . "/movie/delete/$id' onclick='return confirm(\"Are you sure you want to remove this from the database\")'>";
            }
            ?>
            <img src="<?= $image ?>" alt="<?= $title ?>" class="poster-details"/>
            <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
                echo "</a>";
            }
            ?>
            <a href="<?= BASE_URL ?>/movie/index" class="return">Return</a> 
            <?php
            //If user is an admin, show edit button.
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
                echo "<a href='" . BASE_URL . "/movie/edit/$id' class='edit'>Edit</a>";
            } else {
                //Blank <a> to keep the formatting when "Edit" is not visible.
                echo "<a href='' class='edit' style='visibility:hidden'>####</a>";
            }
            ?>
            <table id="info">
                <tr>
                    <td style="width: 10px;">
                        <h2 style="visibility: hidden">Title:</h2><br>
                        <p>Genre:</p>
                        <p>Director:</p>
                        <p>Year:</p>
                        <p>Runtime:</p>
                        <p>Rating:</p>
                        <p>IMDB:</p><br>                         
                        <p>Plot:</p><br><br><br><br><br><br><br><br><br><br>

                        <!-- If user is admin, show and let them change the dvd date. -->
                        <p style="visibility: hidden">DVD Release Date</p>
                    </td>
                    <td>
                        <h2><?= $title ?></h2><br>
                        <p><?= $genre ?></p>
                        <p><?= $director ?></p>
                        <p><?= $year ?></p>
                        <p><?= $runtime ?></p>
                        <p><?= $rating ?></p>
                        <p><?= $imdb ?></p><br>                         
                        <p style=" width: 300px; height: 250px;"><?= $plot ?></p><br>

                        <!-- If user is admin, show and let them change the dvd date. -->
                        <p style="visibility: hidden"><?= $dvd ?></p>
                    </td>
                </tr>    
            </table>
            <?php
            //Set current date.
            $current_date = strtotime(date("d M Y"));
            if (strtotime($dvd) > $current_date || $dvd == "N/A") { 
               $visibility = "hidden";
                echo "<p style='margin-top:10px; border:none; color:#d5d9f2;' class='library-add'>This title is not yet available.</p>";
            }
            ?>
            <p style="margin-top: 10px; visibility:<?= $visibility ?>"><a href='<?= BASE_URL ?>/user/movie/<?= $id ?>' class='library-add'>Add to Library</a></p>
        </div>



        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
