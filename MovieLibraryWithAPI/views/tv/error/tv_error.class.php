<?php
/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: tv_error.class.php
 * Description: Display tv series errors.
 */

class TvError extends IndexView {
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
