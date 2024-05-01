<div class="panel panel-default mb15">
    <div class="panel-heading text-center no-border">
        <h2><?php echo lang('add_quotation'); ?></h2>
    </div>
    <div class="panel-body p30">
		<?php
		if(isset($error)){
			echo '<div id="app-alert-2p7s6" class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="app-alert-message">'.$error.'</div><div class="progress"><div class="progress-bar progress-bar-success hide" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div>';
		} ?>
        <?php echo form_open_multipart("quotation", array("id" => "quotation-form", "class" => "general-form", "role" => "form")); ?>

        <?php if (validation_errors()) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo validation_errors(); ?>
            </div>
        <?php } ?>
        <div class="form-group">
          <?php
            echo form_input(array(
                "id" => "project_title",
                "name" => "project_title",
                "class" => "form-control p10",
                "placeholder" => lang('project_title') . ' *',
                "autofocus" => true,
                "value" => $this->input->post('project_title'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        
        <div class="form-group">
          <?php
          
           
            $seller_id = 'Chimpstudio';
        	$network = 'themeforest';
        
            // Set API Key  
    
            $url = "https://api.envato.com/v1/market/new-files-from-user:{$seller_id},{$network}.json";
            $curl = curl_init($url);
    
            $personal_token = "FXkbo6WD6cq0qcmqzxCkpD3rDnjY0nnf";
            $header = array();
            $header[] = 'Authorization: Bearer '.$personal_token;
            $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
            $header[] = 'timeout: 20';
    
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
    
            $envatoRes = curl_exec($curl);
            curl_close($curl);
            $products = json_decode($envatoRes);
    
            $all_themes = (array) $products->{'new-files-from-user'};
            
           
            $theme_options['']  = 'Select Theme *';
            foreach( $all_themes    as $key => $theme ){
                $theme_options[$theme->item]    = $theme->item;
            }

            echo form_dropdown( 'theme_name', $theme_options, '', "class='select2'");
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
		
		 <div class="form-group ajaxcheck">
          <?php
            echo form_input(array(
                "id" => "company_name",
                "name" => "company_name",
                "class" => "form-control p10",
                "placeholder" => lang('company_name'),
                "value" => $this->input->post('company_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
		
		<div class="form-group">
          <?php
            echo form_input(array(
                "id" => "first_name",
                "name" => "first_name",
                "class" => "form-control p10",
                "placeholder" => lang('first_name') . ' *',
                "value" => $this->input->post('first_name'),
                "autofocus" => true,
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
                "placeholder" => lang('last_name') . ' *',
                "value" => $this->input->post('last_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
		
		
		
		<div class="form-group">
          <?php
            echo form_input(array(
                "id" => "phone",
                "name" => "phone",
                "class" => "form-control p10",
                "placeholder" => lang('phone'),
                "value" => $this->input->post('phone'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
		
		<div class="form-group">
          <?php
            echo form_input(array(
                "id" => "skype",
                "name" => "skype",
                "class" => "form-control p10",
                "placeholder" => lang('skype'),
                "value" => $this->input->post('skype'),
                "autofocus" => true,
            ));
            ?>
        </div>
        <div class="form-group">
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
		
        <div class="form-group mb0">
            <button class="btn btn-lg btn-primary btn-block mt15 submitbtn" type="submit" onclick="editor_description();"><?php echo lang('submit'); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
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
                ['insert', ['link', 'hr',]],
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
            onSuccess: function(result) {
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