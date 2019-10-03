<?php
/*
 * Author: Jack Riggs
 * Date: Apr 8, 2019
 * File: episode_error.class.php
 * Description:  DIsplay episode errors.
 */

class EpisodeError extends IndexView {

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
