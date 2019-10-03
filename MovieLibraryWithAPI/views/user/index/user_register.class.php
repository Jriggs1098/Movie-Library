<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_register.class.php
 * Description: Display confirmation message.
 */

class UserRegister extends IndexView {
    //Display movies function.
    public function display($register) {
        //display page header
        parent::displayHeader("Register");
        ?>

        <div class="content">
            <h1>Create an Account</h1>
            <div class="content-row">
                <?php
                if (!$register) {
                echo "<p>There has been an error</p>";
            } else {
                echo "<p>Your account has been successfully created.</p>";
            }
                ?>        
        </div>

        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
