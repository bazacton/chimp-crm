<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('includes/head'); ?>
    </head>
    <body>
        <div class="signin-box quotation-box">
            <?php
                include_once("application/libraries/Envato_marketplaces.php");
                $this->load->view("app/app_form");
            ?>
        </div>
    </body>
</html>

<?php
load_css(array(
    "assets/js/summernote/summernote.css",
    "assets/js/summernote/summernote-bs3.css"
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
    "assets/js/bootstrap-confirmation/bootstrap-confirmation.js",
));
?>