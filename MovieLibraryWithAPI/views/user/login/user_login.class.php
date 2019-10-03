<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_login.class.php
 * Description: Display the login form.
 * 
 * ADMIN LOGIN:
 *  Username: admin
 *  Password: password
 * 
 * GUEST LOGIN:
 *  Username: guest
 *  Password: password
 * 
 * GUEST2 LOGIN:
 *  Username: guest2
 *  Password: guest2
 */

class UserLogin extends IndexView {
    
    //Display movies function.
    public function display() {
        //display page header
        parent::displayHeader("Login");
        ?>

        <div class="content">
            <h1>Login to an Existing Account</h1>
            <div class="content-row">
                <p class="account-info">Login to add movies to your library and view your current library. If you do not already have an account, please click "Register" to create one.</p>
                <form action='<?= BASE_URL . "/user/verify" ?>' method="post">
                    <table class="account-form">
                        <tr>
                            <td>Username:<br> <input name="username" type="text"></td>
                        </tr>
                        <tr>
                            <td>Password:<br> <input name="password" type="password"></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="Login" class="edit-button" /><br>
                                <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . '/index' ?>"' class="edit-button"/>              
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <?php
        //display page footer
        parent::displayFooter();
    }

//end of display method
}
