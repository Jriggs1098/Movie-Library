<?php
/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: error.php
 * Description: Display global errors.
 */

$page_title = "Error";
//display header
IndexView::displayHeader($page_title);

?>
<div class="content">
            <h1>Error</h1>
            <div class="content-row">
                <a href="<?= BASE_URL ?>/index" class="error-link">Home</a>
                <p id="error"><?= urldecode($message) ?></p>
            </div>
        </div>

<?php
//display footer
IndexView::displayFooter();