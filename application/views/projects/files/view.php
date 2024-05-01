<div class="app-modal">
    <div class="app-modal-content">
        <?php 
        
        
        if ($is_image_file) { ?>
            <img src="<?php echo $file_url; ?>" />
            <?php
        } else if (get_real_ip() == "127.0.0.1" || !$is_google_preview_available) {
            //don't show google preview in localhost
            echo lang("file_preview_is_not_available") . "<br />";
            echo anchor($file_url, lang("download"));
        } else {
            ?>
            <iframe id='google-file-viewer' src="https://drive.google.com/viewerng/viewer?url=<?php echo $file_url; ?>?pid=explorer&efh=false&a=v&chrome=false&embedded=true" style="width: 100%; margin: 0; border: 0;"></iframe>

            <script type="text/javascript">
                $(document).ready(function () {
                    $("#google-file-viewer").css({height: $(window).height() + "px"});
                    $(".app-modal .expand").hide();
                });
            </script>

        <?php }
        ?>

    </div>

    <div class="app-modal-sidebar">
        <div class="mb15 pl15 pr15">
            <div class="media-left ">
                <span class='avatar avatar-sm'><img src='<?php echo get_avatar($file_info->uploaded_by_user_image); ?>' alt='...'></span>
            </div>
            <div class="media-left">
                <div class="mt5"><?php
                    if ($file_info->uploaded_by_user_type == "staff") {
                        echo get_team_member_profile_link($file_info->uploaded_by, $file_info->uploaded_by_user_name);
                    } else {
                        echo get_client_contact_profile_link($file_info->uploaded_by, $file_info->uploaded_by_user_name);
                    }
                    ?></div>
                <small><span class="text-off"><?php echo format_to_relative_time($file_info->created_at); ?></span></small>
            </div>
            <div class="pt10 pb10 b-b">
                <?php echo $file_info->description; ?>
            </div>
        </div>
        <div class="mr15">
            <?php
            if ($can_comment_on_files) {
                $this->load->view("projects/comments/comment_form");
            }
            ?>
            <?php $this->load->view("projects/comments/comment_list"); ?>
        </div>
    </div>

</div>