<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: episode_edit.class.php
 * Description: Display edit form for an episode.
 */

class EpisodeEdit extends IndexView {
     public function display($episode) {
        //Display page header.
        parent::displayHeader("Edit Episode");

        $id = $episode->getId();
        $series_id = $episode->getSeries_id();
        $season = $episode->getSeason();
        $episode_number = $episode->getEpisode();
        $title = $episode->getTitle();
        $release_date = $episode->getRelease_date();
        $runtime = $episode->getRuntime();
        $image = $episode->getImage();
        $plot = $episode->getPlot();
        ?>
        <!-- display show information in a form-->
        <div class="detail-row" style="margin-bottom: 0px;">
            <h1>Edit Episode: <?= $title ?></h1>
            <hr>  
            <div class="content-row" style="border-top: none; padding-top: 0px; padding-bottom: 50px;">
                <form action="<?= BASE_URL . "/episode/update/" . $id ?>" method="post" class="edit-form">
                    <input type="hidden" name="id" value="<?= $id ?>" >
                    <p class="titles">Image:</p> 
                    <p><input name="image" type="text" value="<?= $image ?>" readonly style="width: 50%; right: 22.6%;"/></p>
                    <p class="titles">Title:</p>
                    <p><input name="title" type="text" value="<?= $title ?>" readonly /></p>
                    <p class="titles">Season:</p>
                    <p><input name="season" type="number" value="<?= $season ?>" style="color: red;" /></p>
                    <p class="titles">Episode:</p>
                    <p><input name="episode" type="number" value="<?= $episode_number ?>" style="color: red;" /></p>
                    <p class="titles">Release Date:</p>
                    <p><input name="date" type="text" value="<?= $release_date ?>" readonly /></p> 
                    <p class="titles">Runtime (Minutes):</p>
                    <p><input name="runtime" type="text" value="<?= $runtime ?>" readonly /></p>
                    <p class="titles">Plot:</p>
                    <p><textarea name="plot" readonly style=" width: 300px; height: 250px;"><?= $plot ?></textarea></p>

                    <input type="submit" name="action" value="Update Episode" style="width: 13%; right: 34.7%;" class="edit-button">
                    <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . "/episode/index/" . $series_id ?>"' style="width: 13%;right: 29%;" class="edit-button">
                </form>
            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
