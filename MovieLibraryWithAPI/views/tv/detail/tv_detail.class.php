<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: tv_detail.class.php
 * Description: Defines display method for displaying a tv series details.
 */

class TvDetail extends IndexView {

    public function display($tv, $confirm = "") {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //Display page header from parent class.
        parent::displayHeader("Series Details");

        $id = $tv->getId();
        $title = $tv->getTitle();
        $years = $tv->getYears();
        $rating = $tv->getRating();
        $imdb = $tv->getImdb();
        $genre = $tv->getGenre();
        $seasons = $tv->getSeasons();
        $image = $tv->getImage();
        $plot = $tv->getPlot();

        //Fix the image links if they do not contain https (the "broken" links use are written with http, without the "s".
        if (!strpos($image, "https://")) {
            $image = str_replace("http://", "https://", $image);
        }
        ?>

        <!-- display series details-->
        <div class="detail-row">
            <h1>Series Details <?php if ($confirm != "") {
            echo "- ", $confirm;
        } ?></h1>
            <?php
            //If user is an admin, show edit button.
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
                echo "<a href='" . BASE_URL . "/episode/add/$id' class='add' style='right:17.1%;'>Add Episodes</a>";
            }
            ?>
            <form action="<?= BASE_URL ?>/episode/index/<?= $id ?>" method="post">
                <select name="season" class='change-season' onchange="this.form.submit();">
                    <option value="" selected disabled hidden>Select Season</option>
                    <?php
                    for ($i = 1; $i <= $seasons; $i++) {
                        echo "<option value='$i'>Season $i</option>";
                    }
                    ?>
                </select>
            </form>
            <hr>
            <img src="<?= $image ?>" alt="<?= $title ?>" class="poster-details"/> 
            <a href="<?= BASE_URL ?>/tv/index" class="return" style="left: 30.3%;">Return</a> 
            <?php
            //If user is an admin, show edit button.
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
                echo "<a href='" . BASE_URL . "/tv/edit/$id' class='edit'>Edit</a>";
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
                        <p>Seasons:</p>
                        <p>Years:</p>
                        <p>Rating:</p>
                        <p>IMDB:</p><br>                         
                        <p>Plot:</p><br><br><br><br><br><br><br><br><br><br><br>
                    </td>
                    <td>
                        <h2><?= $title ?></h2><br>
                        <p><?= $genre ?></p>
                        <p><?= $seasons ?></p>
                        <p><?= $years ?></p>
                        <p><?= $rating ?></p>
                        <p><?= $imdb ?></p><br>                         
                        <p style=" width: 300px; height: 250px;"><?= $plot ?></p>
                    </td>
                </tr>    
            </table>
        </div>



        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
