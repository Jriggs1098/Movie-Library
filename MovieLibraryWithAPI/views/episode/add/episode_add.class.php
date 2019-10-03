<?php
/*
 * Author: Jack Riggs
 * Date: Apr 15, 2019
 * File: episode_add.class.php
 * Description: Display form to add new episodes.
 */

class EpisodeAdd extends IndexView{
    
    public function display($id) {
        //Display page header.
        parent::displayHeader("Add Episodes");
        ?>
        <!-- display movie details in a form-->
        <div class="detail-row" style="margin-bottom: 0px;">
            <h1>Add Episode</h1>
            <hr>  
            <div class="content-row" style="border-top: none; padding-top: 0px; padding-bottom: 60px;">
                <form action="<?= BASE_URL . "/episode/insert/$id" ?>" method="post" class="edit-form">
                    <p class="titles">Series:</p> 
                    <p><input name="series" type="text" value="<?= $id ?>" readonly required/></p>
                    <p class="titles">Season:</p> 
                    <p><input name="season" type="text" placeholder="Enter season number."/></p>

                    <input type="submit" name="action" value="Add Episodes" style="width: 13%; right: 34.7%;" class="edit-button">
                    <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . "/tv/index/" ?>"' style="width: 13%;right: 29%;" class="edit-button">
                </form>
            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
