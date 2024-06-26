<?php
if ($mode === "inbox") {
    if ($is_reply) {
        $user_image = $this->login_user->image;
    } else {
        $user_image = $message_info->user_image;
    }
} if ($mode === "sent_items") {
    if ($is_reply) {
        $user_image = $message_info->user_image;
    } else {
        $user_image = $this->login_user->image;
    }
}
?>

<div class="media b-b p15 m0 bg-white">
    <div class="media-left"> 
        <span class="avatar avatar-sm">
            <img src="<?php echo get_avatar($user_image); ?>" alt="..." />
        </span>
    </div>
    <div class="media-body w100p">
        <div class="media-heading clearfix">
            <?php if ($mode === "sent_items" && $is_reply != "1" || $mode === "inbox" && $is_reply == "1") { ?>
                <label class="label label-success large"><?php echo lang("to"); ?></label>
            <?php } ?>
            <?php echo get_team_member_profile_link($message_info->from_user_id, $message_info->user_name, array("class" => "dark strong")); ?>
            <span class="text-off pull-right"><?php echo format_to_relative_time($message_info->created_at); ?></span>
        </div>
        <p class="p5 b-b b-turquoise">
            <?php echo lang("subject"); ?>:  <?php echo $message_info->subject; ?>  
        </p>
        <p>
            <?php echo nl2br(link_it($message_info->message)); ?>
        </p>
        <p>
            <?php
            $files = unserialize($message_info->files);
            $total_files = count($files);

            if ($total_files) {
                $download_caption = lang('download');
                if ($total_files > 1) {
                    $download_caption = sprintf(lang('download_files'), $total_files);
                }
                echo "<i class='fa fa-paperclip pull-left font-16'></i>";
                echo anchor(get_uri("messages/download_message_files/" . encode_id($message_info->id, "message_id")), $download_caption, array("class" => "", "title" => $download_caption));
            }
            ?>
        </p>
    </div>
</div>

<?php foreach ($replies as $reply_info) { ?>
    <?php $this->load->view("messages/reply_row", array("reply_info" => $reply_info)); ?>
<?php } ?>

<div id="reply-form-container" class="pr15">
    <div id="reply-form-dropzone" class="post-dropzone">
        <?php echo form_open(get_uri("messages/reply"), array("id" => "message-reply-form", "class" => "general-form", "role" => "form")); ?>
        <div class="p15 box b-b">
            <div class="box-content avatar avatar-md pr15">
                <img src="<?php echo get_avatar($this->login_user->image); ?>" alt="..." />
            </div>
            <div class="box-content form-group">
                <input type="hidden" name="message_id" value="<?php echo $encrypted_message_id; ?>">
                <?php
                echo form_textarea(array(
                    "id" => "reply_message",
                    "name" => "reply_message",
                    "class" => "form-control",
                    "placeholder" => lang('write_a_reply'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
                <?php $this->load->view("includes/dropzone_preview"); ?>    
                <footer class="panel-footer b-a clearfix ">
                    <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                    <button class="btn btn-primary pull-right btn-sm " type="submit"><i class='fa fa-reply'></i> <?php echo lang("reply"); ?></button>
                </footer>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var uploadUrl = "<?php echo get_uri("messages/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("messages/validate_message_file"); ?>";

        var dropzone = attachDropzoneWithForm("#reply-form-dropzone", uploadUrl, validationUrl);

        $("#message-reply-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                $("#reply_message").val("");
                $(result.data).insertBefore("#reply-form-container");
                appAlert.success(result.message, {duration: 10000});
                if (dropzone) {
                    dropzone.removeAllFiles();
                }
            }
        });

    });
</script>
