<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: movie_edit.class.php
 * Description: Display movie details in a form to edit.
 */

class MovieEdit extends IndexView {

    public function display($movie) {
        //Display page header.
        parent::displayHeader("Edit Movie");

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
        ?>
        <!-- display movie details in a form-->
        <div class="detail-row" style="margin-bottom: 0px;">
            <h1>Edit Movie: <?= $title ?></h1>
            <hr>  
            <div class="content-row" style="border-top: none; padding-top: 0px; padding-bottom: 60px;">
                <form action="<?= BASE_URL . "/movie/update/" . $id ?>" method="post" class="edit-form">
                    <input type="hidden" name="id" value="<?= $id ?>" >
                    <p class="titles">Image:</p> 
                    <p><input name="image" type="text" value="<?= $image ?>" style="width: 50%; right: 22.6%;" readonly/></p>
                    <p class="titles">Title:</p>
                    <p><input name="title" type="text" value="<?= $title ?>" style="color: red;" /></p>
                    <p class="titles">Director:</p> 
                    <p><input name="director" type="text" value="<?= $director ?>" readonly/></p>
                    <p class="titles">Year:</p>
                    <p><input name="year" type="number" value="<?= $year ?>" style="color: red;" /></p>
                    <p class="titles">Runtime (Minutes):</p>
                    <p><input name="runtime" type="text" value="<?= $runtime ?>" readonly /></p>
                    <p class="titles">IMDB:</p>
                    <p><input name="imdb" type="number" step="0.1" value="<?= $imdb ?>" readonly /></p>  
                    <p class="titles">DVD Release Date:</p>
                    <p><input name="dvd" type="text" value="<?= $dvd ?>" readonly/></p>  
                    <p class="titles">Plot:</p>
                    <p><textarea name="plot" readonly style=" width: 300px; height: 250px;"><?= $plot ?></textarea></p>

                    <input type="submit" name="action" value="Update Movie" style="width: 13%; right: 34.7%;" class="edit-button">
                    <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . "/movie/detail/" . $id ?>"' style="width: 13%;right: 29%;" class="edit-button">
                </form>
            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
