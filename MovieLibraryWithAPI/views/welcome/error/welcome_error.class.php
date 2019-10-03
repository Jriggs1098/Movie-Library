<?php
/*
 * Author: Jack Riggs
 * Date: April 7, 2019
 * File: movie_error.class.php
 * Description: Display errors from the welcome controller.
 */

class WelcomeError extends IndexView {

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
