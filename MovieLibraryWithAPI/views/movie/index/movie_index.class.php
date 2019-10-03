<?php
/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: movie_index.class.php
 * Description: Defines the display method to display all movies.
 */

class MovieIndex extends IndexView {

    //Display movies function.
    public function display($movies, $confirm = "") {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //display page header
        parent::displayHeader("All Movies");
        ?>

        <div class="content">
            <h1>All Movies <?php if ($confirm != "") {
            echo "- ", $confirm;
        } ?></h1>
            <?php
            //If user is an admin, show add button.
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
                echo "<a href='" . BASE_URL . "/movie/add' class='add'>Add Movie</a>";
            }

            //Genre Filters.
            if (filter_has_var(INPUT_GET, 'genre')) {
                $genre_select = filter_input(INPUT_GET, 'genre', FILTER_SANITIZE_STRING);
            } else {
                $genre_select = "All Genres";
            }
            $genre_array = ["All Genres", "Action", "Comedy", "Horror", "Sci-Fi", "Drama", "Mystery"];
            ?>
            <form action="<?= BASE_URL ?>/movie/index" method="get">
                <select name="genre" class='change-season' onchange="this.form.submit();" style="right: 17%;">
                    <?php
                    for ($i = 0; $i <= 6; $i++) {
                        if ($genre_array[$i] == $genre_select) {
                            echo "<option value='$genre_array[$i]' selected>$genre_array[$i]</option>";
                        } else {
                        echo "<option value='$genre_array[$i]'>$genre_array[$i]</option>";
                        }
                    }
                    ?>
                </select>
            </form>
            <div class="content-row">
                <table>
                    <?php
                    if ($movies === 0) {
                        echo "<p>No movie was found.</p>";
                        //display page footer
                        parent::displayFooter();
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
                            $genre = $movie->getGenre();

                            //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
                            if (!strpos($image, "https://")) {
                                $image = str_replace("http://", "https://", $image);
                            }
                            
                            if (strpos($genre, $genre_select) !== False || $genre_select == "All Genres") {
                                echo "<tr><div class='blur-back'><a href='". BASE_URL. "/movie/detail/$id'><img src='" . $image . "' class='poster'/><h2>" . $title . "</h2><h3>" . $year . "<br>" . $runtime . "<br>" . $rating . "<br><br>IMDB: " . $imdb . "</h3></a></div></tr>";
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

//end of display method
}
