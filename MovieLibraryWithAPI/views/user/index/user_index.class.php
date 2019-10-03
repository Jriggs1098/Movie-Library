<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_index.class.php
 * Description: Display the user registration form.
 */

class UserIndex extends IndexView {

    //Display movies function.
    public function display() {
        //display page header
        parent::displayHeader("Register");
        ?>

        <div class="content">
            <h1>Create an Account</h1>
            <div class="content-row">
                <p class="account-info">By creating an account, you will be able to add movies to your Library to watch whenever you are logged in!</p>
                <form action='<?= BASE_URL . "/user/register" ?>' method="post">
                    <table class="account-form">
                        <tr>
                            <td>First Name:<br> <input name="firstname" type="text"></td>
                        </tr>
                        <tr>
                            <td>Last Name:<br> <input name="lastname" type="text"></td>
                        </tr>
                        <tr>
                            <td>Username:<br> <input name="username" type="text"></td>
                        </tr>
                        <tr>
                            <td>Password:<br> <input name="password" type="password"></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="Register" class="edit-button" /><br>
                                <input type="button" value="Cancel" onclick='window.location.href = "<?= BASE_URL . '/index' ?>"'class="edit-button"/>              
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
