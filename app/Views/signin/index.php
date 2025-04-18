<!DOCTYPE html>

<html lang="en">

    <head>

        <?php echo view('includes/head'); ?>

    </head>

    <body class="body-bg-gradient">
          <img class="fullscreen-bg" src="<?php echo base_url('files/system/img-bg-pagelogin.webp'); ?>" />
        <?php

        if (get_setting("show_background_image_in_signin_page") === "yes") {
            $background_url = get_file_from_setting("signin_page_background");
            ?>
        <?php } ?>



        <div class="scrollable-page d-flex flex-column justify-content-center align-items-center vh-100">
            <div class="form-signin">
                <?php
                if (isset($form_type) && $form_type == "request_reset_password") {
                    echo view("signin/reset_password_form");
                } else if (isset($form_type) && $form_type == "new_password") {
                    echo view('signin/new_password_form');
                } else {
                    echo view("signin/signin_form");
                }
                ?>
            </div>
            <div class="d-flex w-100 position-relative justify-content-center">
              <div class="position-btn-lms d-flex flex-column align-items-center">
                <span class="text-white fw-bold fs-1">Cursos Online</span>
                <span class="text-white mb-3 fw-bold fs-3">Gana monedas aprendiendo <img class="icon-coin" src="<?php echo base_url('files/system/icon-coin.webp'); ?>"> </span>
                <a href="/lms" class="btn-lms text-white border border-white px-4 py-3 rounded text-uppercase fw-bold ">Login Rubymed academy</a>
              </div>
            </div>
        </div>
        <script>

            $(document).ready(function () {

                initScrollbar('.scrollable-page', {

                    setHeight: $(window).height() - 50

                });

            });

        </script>



        <?php echo view("includes/footer"); ?>

    </body>

</html>