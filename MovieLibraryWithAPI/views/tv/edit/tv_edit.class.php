<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: tv_edit.class.php
 * Description: Display tv series details in an editable form.
 */

class TvEdit extends IndexView {
  
    public function display($tv) {
        //Display page header.
        parent::displayHeader("Edit Show");

        $id = $tv->getId();
        $title = $tv->getTitle();
        $years = $tv->getYears();
        $rating = $tv->getRating();
        $imdb = $tv->getImdb();
        $genre = $tv->getGenre();
        $seasons = $tv->getSeasons();
        $image = $tv->getImage();
        $plot = $tv->getPlot();
        ?>
        <!-- display show information in a form-->
        <div class="detail-row" style="margin-bottom: 0px;">
            <h1>Edit Show: <?= $title ?></h1>
            <hr>  
            <div class="content-row" style="border-top: none; padding-top: 0px; padding-bottom: 50px;">
                <form action="<?= BASE_URL . "/tv/update/" . $id ?>" method="post" class="edit-form">
                    <input type="hidden" name="id" value="<?= $id ?>" >
                    <p class="titles">Image:</p> 
                    <p><input name="image" type="text" value="<?= $image ?>" style="width: 50%; right: 22.6%;" readonly/></p>
                    <p class="titles">Title:</p>
                    <p><input name="title" type="text" value="<?= $title ?>" style="color: red;" /></p>
                    <p class="titles">Seasons:</p> 
                    <p><input name="seasons" type="number" value="<?= $seasons ?>" readonly /></p>
                    <p class="titles">Years:</p>
                    <p><input name="years" type="text" value="<?= $years ?>" readonly /></p>
                    <p class="titles">IMDB:</p>
                    <p><input name="imdb" type="number" step="0.1" value="<?= $imdb ?>" readonly/></p>
                    <p class="titles">Plot:</p>
                    <p><textarea name="plot" readonly style=" width: 300px; height: 250px;"><?= $plot ?></textarea></p>

                    <input type="submit" name="action" value="Update Show" style="width: 13%; right: 34.7%;" class="edit-button">
                    <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . "/tv/detail/" . $id ?>"' style="width: 13%;right: 29%;" class="edit-button">
                </form>
            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
