<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_verify.class.php
 * Description: Display confirmation message.
 */

class UserVerify extends IndexView {

    //Display movies function.
    public function display($verify) {
        //display page header
        parent::displayHeader("Login");
        ?>

        <div class="content">
            <h1>Login</h1>
            <div class="content-row">
                <?php
                if ($verify) {
                    echo "<p>You have successfully logged in.</p>";
                }
                ?>        
            </div>

            <?php
            //display page footer
            parent::displayFooter();
        }

//end of display method
    }
    