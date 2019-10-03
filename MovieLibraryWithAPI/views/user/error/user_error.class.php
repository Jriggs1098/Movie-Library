<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_error.class.php
 * Description: Define the display method to display user errors.
 */

class UserError  extends IndexView {
    public function display($message) {

        //display page header
        parent::displayHeader("Error");
        ?>

        <div class="content">
            <h1>Error</h1>
            <div class="content-row">
                <a href="<?= BASE_URL ?>/index" class="error-link">Home</a>
                <p id="error"><?= $message ?></p>
            </div>
        </div>
        <?php
        //display page footer
        parent::displayFooter();
    }

}
