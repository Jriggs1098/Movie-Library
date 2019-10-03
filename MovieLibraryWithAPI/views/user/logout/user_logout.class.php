<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_logout.class.php
 * Description: Display logout confirmation message.
 */

class UserLogout extends IndexView {

    //Display movies function.
    public function display() {
        //display page header
        parent::displayHeader("Logout");
        ?>

        <div class="content">
            <h1>Logout</h1>
            <div class="content-row">
                <p>You have successfully logged out.</p>       
            </div>

            <?php
            //display page footer
            parent::displayFooter();
        }

//end of display method
    }
    