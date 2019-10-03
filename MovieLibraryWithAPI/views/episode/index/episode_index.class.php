<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: episode_index.class.php
 * Description: Display all episodes for a specific season of a show.
 */

class EpisodeIndex extends IndexView {

    //Display episodes function.
    public function display($episodes, $season, $series_id, $confirm = "") {
        //display page header
        parent::displayHeader("Episodes");

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Get number of seasons from session variable.
        if (isset($_SESSION['num_seasons'])) {
            $num_seasons = $_SESSION['num_seasons'];
        }
        ?>
        <div class="content">
            <h1>Episodes for Season: <?= $season ?><?php if ($confirm != "") {
            echo "- ", $confirm;
        } ?></h1>
            <form action="<?= BASE_URL ?>/episode/index/<?= $series_id ?>" method="post">
                <select name="season" class='change-season' onchange="this.form.submit();" >
                    <?php
                    for ($i = 1; $i <= $num_seasons; $i++) {
                        if ($i == $season) {
                            echo "<option value='$i' selected>Season $i</option>";
                        } else {
                        echo "<option value='$i'>Season $i</option>";
                        }
                    }
                    ?>
                </select>
            </form>
            <div class="content-row" style="padding-top: 0px; padding-bottom: 25px;">
                <?php
                if ($episodes === 0) {
                    echo "<p>There are currently no episodes available for this season.</p>";
                    //display page footer
                    parent::displayFooter();
                } else {
                    //Display epsisodes for that season.
                    foreach ($episodes as $i => $episode) {
                        $id = $episode->getId();
                        $series_id = $episode->getSeries_id();
                        $episode_number = $episode->getEpisode();
                        $title = $episode->getTitle();
                        $release_date = $episode->getRelease_date();
                        $runtime = $episode->getRuntime();
                        $image = $episode->getImage();
                        $plot = $episode->getPlot();

                        //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
                        if (!strpos($image, "https://")) {
                            $image = str_replace("http://", "https://", $image);
                        }
                        
                        //Hide edit episode button if user is not admin.
                        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
                            $visibility = "visible";
                        } else {
                            $visibility = "hidden";
                        }

                        echo "<div class='tv-row'><h2>$title</h2><p class='p1'><strong>$plot</p><p class='p2'>$runtime</p><div class='crop'><img src='" . $image . "' class='tv-poster'/></div><h3>Episode: $episode_number</h3><p class='p3'>$release_date</p>"
                                . "<a href='".BASE_URL."/user/episode/$id' class='tv-button'>Add to Library</a>"
                                . "<a href='" . BASE_URL . "/episode/edit/$id' class='tv-button' style='visibility:$visibility'>Edit</a></div>";
                    }
                    ?>                  
                </div>
            </div>

            <?php
            //display page footer
            parent::displayFooter();
        }

//end of display method
    }

}
