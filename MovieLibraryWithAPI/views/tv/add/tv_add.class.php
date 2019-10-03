<?php
/*
 * Author: Jack Riggs
 * Date: Apr 15, 2019
 * File: tv_add.class.php
 * Description:
 */

class TvAdd extends IndexView{

    public function display() {
        //Display page header.
        parent::displayHeader("Add Show");
        ?>
        <!-- display movie details in a form-->
        <div class="detail-row" style="margin-bottom: 0px;">
            <h1>Add Series</h1>
            <hr>  
            <div class="content-row" style="border-top: none; padding-top: 0px; padding-bottom: 60px;">
                <form action="<?= BASE_URL . "/tv/insert/" ?>" method="post" class="edit-form">
                    <input type="hidden" name='type' id='type' value="tv"/>
                    <p class="titles">Title:</p>
                    <p><input id='title-add' name="title-add" type="text" placeholder="Enter title." required autocomplete="off" onkeyup="handleKeyUp(event)"/></p>
                    <div id='suggest-div' style="right: 75%;"></div>
                    
                    <input type="submit" name="action" value="Add Show" style="width: 13%; right: 34.7%;" class="edit-button">
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
