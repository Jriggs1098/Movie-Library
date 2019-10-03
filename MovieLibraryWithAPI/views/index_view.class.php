<?php
/*
 * Author: Jack Riggs
 * Date: Apr 7, 2019
 * File: index_view.class.php
 * Description: Parent class for all view classes.  Header and Footer display functions.
 */

class IndexView {

    //Display page header.
    static public function displayHeader($page_title) {
        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <title><?= $page_title ?></title>
                <link type="text/css" rel="stylesheet" href='<?= BASE_URL ?>/www/css/style.css' />
                <script>
                    //create the JavaScript variable for the base url
                    var base_url = "<?= BASE_URL ?>";
                </script>
            </head>
            <body>
                <div id="wrapper">
                    <div class='account'>
                        <?php
                        if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
                            $name = $_SESSION['name'];
                            echo "<p style='width:150px;'>Hello $name!";
                            echo "<p><a href='" . BASE_URL . "/user/library/$user_id'>Library</a></p>";
                            echo "<p><a href='" . BASE_URL . "/user/logout'>Logout</a></p>";
                        } else {
                            echo "<p><a href='" . BASE_URL . "/user/index'>Register</a></p>";
                            echo "<p><a href='" . BASE_URL . "/user/login'>Login</a></p>";
                        }
                        ?>
                    </div>

                    <div class="logo">
                        <a href="<?= BASE_URL ?>/index">Digital Video Provider</a>
                    </div>

                    <ul class="nav-bar">
                        <li class="travel"><a href="<?= BASE_URL ?>/index">Home</a></li>
                        <li class="travel"><a href="<?= BASE_URL ?>/movie/index">Movies</a></li>
                        <li class="travel"><a href="<?= BASE_URL ?>/tv/index">TV</a></li>

                        <!-- PUT SEARCH BAR INSIDE OF A FORM -->
                        <li>
                            <form method="get" action="<?= BASE_URL ?>/welcome/search">
                                <input type="image" src='<?= BASE_URL ?>/www/img/search.png' alt="Submit" id="search-img"/><input type="text" name="query-terms" placeholder="Enter at least 1 search term..." id="search-bar" required autocomplete="off">
                                <div id="suggestionDiv"></div>
                            </form>
                            
                        </li>
                    </ul>
                    <?php
                }

                //End of displayHeader function.
                //Display page footer.
                public static function displayFooter() {
                    ?>
                    <div id="footer">
                        <hr/>
                        &copy 2019 Digital Video Provider
                    </div>
                </div>
                
<!--                <script type="text/javascript" src="<?= BASE_URL ?>/www/js/ajax_autosuggestion.js"></script>-->
                <script type="text/javascript" src="<?= BASE_URL ?>/www/js/ajax_auto_add.js"></script>
            </body>
        </html>
        <?php
    }

//End of displayFooter function.
}
