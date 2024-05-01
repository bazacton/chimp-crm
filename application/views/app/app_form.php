<div class="panel panel-default mb15">
    <div class="panel-heading text-center no-border">
        <h2>Android App Request Form</h2>
    </div>
    <div class="panel-body p30">
        <?php
        if (isset($error)) {
            echo '<div id="app-alert-2p7s6" class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="app-alert-message">' . $error . '</div><div class="progress"><div class="progress-bar progress-bar-success hide" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div>';
        }
        ?>
        <?php echo form_open_multipart("app", array("id" => "app-form", "class" => "general-form", "role" => "form")); ?>

<?php if (validation_errors()) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <?php echo validation_errors(); ?>
            </div>
            <?php } ?>
        
        
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "app_name",
                "name" => "app_name",
                "class" => "form-control p10",
                "placeholder" => 'App Name *',
                "autofocus" => true,
                "value" => $this->input->post('app_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <label><i>&nbsp; Make sure to have active 'Support license' for JobCareer Theme before requesting free Apk for Mobile Application.</i></label>
            <?php
            echo form_input(array(
                "id" => "purchase_code",
                "name" => "purchase_code",
                "class" => "form-control p10",
                "placeholder" => 'Purchase Code *',
                "autofocus" => true,
                "value" => $this->input->post('purchase_code'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>

        <div class="form-group">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "class" => "form-control p10",
                "placeholder" => lang('description') . ' *',
                "value" => $this->input->post('description'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "app_id",
                "name" => "app_id",
                "class" => "form-control p10",
                "placeholder" => 'APP ID (E.g : com.chimpgroup.jobcareer) *',
                "autofocus" => true,
                "value" => $this->input->post('app_id'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "author_name",
                "name" => "author_name",
                "class" => "form-control p10",
                "placeholder" => 'Author Name *',
                "autofocus" => true,
                "value" => $this->input->post('author_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "author_email",
                "name" => "author_email",
                "class" => "form-control p10",
                "placeholder" => 'Author Email *',
                "autofocus" => true,
                "value" => $this->input->post('author_email'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-email" => true,
                "data-msg-required" => lang("enter_valid_email")
            ));
            ?>
        </div>
        <div class="form-group">
            <h4>If you have to put it on Playstore please provide below details too.</h4>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "first_name",
                "name" => "first_name",
                "class" => "form-control p10",
                "placeholder" => 'First Name *',
                "autofocus" => true,
                "value" => $this->input->post('first_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "class" => "form-control p10",
                "placeholder" => 'Last Name *',
                "autofocus" => true,
                "value" => $this->input->post('last_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "organization_unit",
                "name" => "organization_unit",
                "class" => "form-control p10",
                "placeholder" => 'Organization Unit *',
                "autofocus" => true,
                "value" => $this->input->post('organization_unit'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "organization",
                "name" => "organization",
                "class" => "form-control p10",
                "placeholder" => 'Organization *',
                "autofocus" => true,
                "value" => $this->input->post('organization'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "city",
                "name" => "city",
                "class" => "form-control p10",
                "placeholder" => 'City / Locality *',
                "autofocus" => true,
                "value" => $this->input->post('city'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "state",
                "name" => "state",
                "class" => "form-control p10",
                "placeholder" => 'State / Province *',
                "autofocus" => true,
                "value" => $this->input->post('state'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "country_code",
                "name" => "country_code",
                "class" => "form-control p10",
                "placeholder" => 'Country Code *',
                "autofocus" => true,
                "value" => $this->input->post('country_code'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
         <div class="form-group">
            <h4>Recommended App Icon Sizes</h4>
            </div>
        <div class="form-group">
            <pre>
<b>Launch Icon</b>

mipmap-hdpi                                                                    			72x72
-------------------------------------------------------------------------------------------------------
mipmap-ldpi                                                                    			36x36
-------------------------------------------------------------------------------------------------------
mipmap-mdpi                                                                    			48x48
-------------------------------------------------------------------------------------------------------
mipmap-xhdpi                                                                    		96x96
-------------------------------------------------------------------------------------------------------
mipmap-xxhdpi                                                                    		144x144
-------------------------------------------------------------------------------------------------------
mipmap-xxxhdpi     

<hr>
<b>Splash Screen</b>

drawable-hdpi                                                           Land 800x480 Port 480x800
-------------------------------------------------------------------------------------------------------
Screen-Name                                                             hdpi_landscape_800x480.png	
                                                                        hdpi_portrait_480x800.png
-------------------------------------------------------------------------------------------------------

drawable-ldpi                                                           Land 320x200 Port 200x320
-------------------------------------------------------------------------------------------------------
Screen-Name                                                             ldpi_landscape_320x200.png
                                                                        ldpi_portrait_200x320.png
-------------------------------------------------------------------------------------------------------

drawable-mdpi                                                           Land 480x320 Port 320x480
-------------------------------------------------------------------------------------------------------
Screen-Name                                                             mdpi_landscape_480x320.png
                                                                        mdpi_portrait_320x480.png
-------------------------------------------------------------------------------------------------------

drawable-xhdpi                                                          Land 1280x720 Port 720x1280
-------------------------------------------------------------------------------------------------------
Screen-Name                                                             xhdpi_landscape_1280x720.png
                                                                        xhdpi_portrait_720x1280.png
-------------------------------------------------------------------------------------------------------

drawable-xxhdpi                                                         Land 1600x960 Port 960x1600
-------------------------------------------------------------------------------------------------------
Screen-Name                                                             xxhdpi_landscape_1600x960.png
                                                                        xxhdpi_portrait_960x1600.png
-------------------------------------------------------------------------------------------------------

drawable-xxxhdpi                                                        Land 1920x1280 Port 1280x1920
-------------------------------------------------------------------------------------------------------
Screen-Name                                                             xxxhdpi_landscape_1920x1280.png
                                                                        xxxhdpi_portrait_1280x1920.png
-------------------------------------------------------------------------------------------------------

</pre>
        </div>

        <div class="form-group">
            <a href="http://chimpgroup.com/app-icons-sample.zip" target="_blank">Download App Icons Samples</a>
            <div id="quotation-dropzone" class="post-dropzone box-content form-group">
                <?php $this->load->view("includes/dropzone_preview"); ?>
                <footer class="panel-footer b-a clearfix">
                    <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                </footer>
            </div>
        </div>
        
        
        
        <div class="form-group">
            <h4><?php echo lang('create_your_login_account'); ?></h4>
        </div>

        <div class="form-group ajaxcheck">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control p10",
                "placeholder" => lang('email') . ' *',
                "value" => $this->input->post('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-email" => true,
                "data-msg-required" => lang("enter_valid_email")
            ));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_password(array(
                "id" => "password",
                "name" => "password",
                "class" => "form-control p10",
                "placeholder" => lang('password') . ' *',
                "value" => $this->input->post('password'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
         <div class="form-group">
            <b>Here are the certain terms and conditions to read, follow and deploy Jobcareer Mobile Application :</b><br><br>

            <i>1. Only first Android apk will be provided by Chimpstudio developer team for Free. To get complete and ready apk, always give complete details of images and information as requested on APP Request Form including WordPress admin and ftp details.<br><br>

            2. Any structural modification or changing request will be considered as Custom work.<br><br>

            3. Launcher Icon and Splash screen images should be suitable and have exact dimensions as mentioned above.<br><br>

            4. You need to pay incase you need to deploy your Mobile Application on Google store or Apple store. You can contact developer team regarding charges details here : <a href="http://chimpgroup.com/crm">http://chimpgroup.com/crm</a><br></i>
        </div>
        <div class="form-group mb0">
            <button class="btn btn-lg btn-primary btn-block mt15 submitbtn" type="submit" onclick="editor_description();"><?php echo lang('submit'); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".general-form .select2").select2();
        $("#description").summernote({
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();

                // Firefox fix
                setTimeout(function () {
                    document.execCommand('insertText', false, bufferText);
                }, 10);
            },
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'hr', ]],
                ['view', ['fullscreen', 'codeview']]
            ],
        });


        var dropzone;
<?php if ($comment_type != "file") { ?>
            var uploadUrl = "<?php echo get_uri("quotation/upload_quotation_file"); ?>";
            var validationUrl = "<?php echo get_uri("quotation/validate_quotation_file"); ?>";
            dropzone = attachDropzoneWithForm("#quotation-dropzone", uploadUrl, validationUrl);
<?php } ?>

        $("#quotation-comment-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                $(".comment_description").val("");
                $(result.data).insertAfter("#quotation-comment-form-container");
                appAlert.success(result.message, {duration: 10000});

                if (dropzone) {
                    dropzone.removeAllFiles();
                }
            }
        });
    });
</script>