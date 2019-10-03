<?php
/*
 * Author: Jack Riggs
 * Date: Apr 15, 2019
 * File: movie_add.class.php
 * Description: Display the form to add a movie to the database.
 */

class MovieAdd extends IndexView{
   
    public function display() {
        //Display page header.
        parent::displayHeader("Add Movie");

        ?>
        <!-- display movie details in a form-->
        <div class="detail-row" style="margin-bottom: 0px;">
            <h1>Add Movie</h1>
            <hr>  
            <div class="content-row" style="border-top: none; padding-top: 0px; padding-bottom: 60px;">
                <form action="<?= BASE_URL . "/movie/insert/"?>" method="post" class="edit-form">
                    <input type="hidden" name='type' id='type' value="movie"/>
                    <p class="titles">Title:</p>
                    <p><input id='title-add' name="title-add" type="text" placeholder="Enter title." style="width: 30%; right: 32.5%;" required autocomplete="off" onkeyup="handleKeyUp(event)"/></p>
                    <div id="suggest-div" style="right: 67%;"></div>
                    <p class="titles">Year:</p>
                    <p><input name="year-add" type="number" placeholder="Enter release year." size="4"/></p>

                    <input type="submit" name="action" value="Add Movie" style="width: 13%; right: 34.7%;" class="edit-button">
                    <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . "/movie/index/"?>"' style="width: 13%;right: 29%;" class="edit-button">
                </form>
            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
