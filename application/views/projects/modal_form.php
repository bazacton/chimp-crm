<?php echo form_open(get_uri("projects/save"), array("id" => "project-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <?php if ( isset( $project_type) && $project_type == 'quotation' || isset( $model_info->type ) && $model_info->type == 'quotation' ){ ?>
        <div class="form-group">
            <label for="theme_name" class=" col-md-3"><?php echo 'Theme Name'; ?></label>
            <div class=" col-md-9">
                 <?php
                    include_once("application/libraries/Envato_marketplaces.php");
                    $config['username'] = 'chimpstudio';
                    $config['api_key'] = 'ta6zyfpt2pjc6b471lwt1595fykh3lej';
                    $e = new Envato_marketplaces();
                    $e->set_api_key($config['api_key']);
                    $all_themes = $e->new_files_from_user($config['username']);
                    $theme_options['']  = 'Select Theme';
                    foreach( $all_themes    as $key => $theme ){
                        $theme_options[$theme->item]    = $theme->item;
                    }

                    echo form_dropdown( 'theme_name', $theme_options, '', "class='select2'");
                ?>
            </div>
        </div>
    <?php  } ?>
    <?php $user_data    = $this->Users_model->get_access_info($this->Users_model->login_user_id()); ?>
        <?php if ($user_data->is_admin == 1){ ?>
            <div class="form-group">
                <label for="client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
                <div class=" col-md-9">
                        <?php
                            echo form_dropdown("client_id", $clients_dropdown, array($model_info->client_id), "class='select2'");
                        ?>

                </div>
            </div>
    <?php } else { ?>
        <?php
        echo form_hidden(array(
            "client_id" => $user_data->client_id,
        ));
        ?>
    <?php } ?>
    <?php if ($user_data->is_admin != 1){ ?>
        <?php
        echo form_hidden(array(
            "type" => 'quotation',
        ));
        ?>
    <?php } ?>
    
    <div class="form-group">
        <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description,
                "class" => "form-control",
                "placeholder" => lang('description'),
                "style" => "height:150px;",
            ));
            ?>
        </div>
    </div>
    <?php if ( isset( $project_type) && $project_type != 'quotation' ){ ?>
    <?php if ( isset( $model_info->type ) && $model_info->type != 'quotation' ) { ?>
    <div class="form-group">
        <label for="start_date" class=" col-md-3"><?php echo lang('start_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "start_date",
                "name" => "start_date",
                "value" => $model_info->start_date,
                "class" => "form-control",
                "placeholder" => lang('start_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required")
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="deadline" class=" col-md-3"><?php echo lang('deadline'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "deadline",
                "name" => "deadline",
                "value" => $model_info->deadline,
                "class" => "form-control",
                "placeholder" => lang('deadline'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#start_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <?php } } ?>
    <?php if ( (!isset( $project_type) || $project_type != 'quotation') && $this->login_user->user_type != 'client' ){ ?>
    <div class="form-group">
        <label for="price" class=" col-md-3"><?php echo lang('price'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "price",
                "name" => "price",
                "value" => $model_info->price ? to_decimal_format($model_info->price) : "",
                "class" => "form-control",
                "placeholder" => lang('price')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="project_labels" class=" col-md-3"><?php echo lang('labels'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "project_labels",
                "name" => "labels",
                "value" => $model_info->labels,
                "class" => "form-control",
                "placeholder" => lang('labels')
            ));
            ?>
        </div>
    </div>
    <?php } ?>
    
    <div class="form-group">
        <label for="project_labels" class=" col-md-3"><?php echo lang('upload_file'); ?></label>
        <div id="quotation-dropzone" class="post-dropzone box-content form-group col-md-9">
            <?php $this->load->view("includes/dropzone_preview"); ?>
            <footer class="panel-footer b-a clearfix">
                    <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
            </footer>
        </div>
    </div>
    
    
    <?php
        if ($model_info->type) {
        ?>
         <div class="form-group">
                <label for="type" class=" col-md-3"><?php echo lang('type'); ?></label>
                <div class=" col-md-9">
                        <?php
                        echo form_dropdown("type", array("project" => lang("project"), "quotation" => lang("single_quotation")), array($model_info->type), "class='select2'");
                        ?>
                </div>
        </div>
        <?php		

        } else {
                $type	= $this->uri->segment(3) ? $this->uri->segment(3):'project';
                 echo form_hidden(array(
        "type" => $type,
                ));
        }
    ?>
    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <label for="status" class=" col-md-3"><?php echo lang('status'); ?></label>
            <div class=" col-md-9">
                <?php
                $statuses_array	= array(
                    "new"           => lang("new"),
                    "un-replied"    => lang("un_replied"),
                    "replied"       => lang("replied"),
                    "on-hold"       => lang("on_hold"),
                    "disqualified"  => lang("disqualified"),
                    "completed"     => lang("completed"),
                );
				
                echo form_dropdown("status", $statuses_array, array($model_info->status), "class='select2'");
                ?>
            </div>
        </div>
    <?php } ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#project-form").appForm({
            beforeAjaxSubmit: function(data) {
                var description = encodeAjaxPostData($("#description").code());
                $.each(data, function(index, obj) {
                    if (obj.name === "description") {
                        data[index]["value"] = description;
                    }
                });
            },
            onSuccess: function(result) {
                location.reload();
                //$("#project-table").appTable({newData: result.data, dataId: result.title});
            }
        });
        $("#title").focus();
        $("#project-form .select2").select2();

        setDatePicker("#start_date, #deadline");

        $("#project_labels").select2({
            tags: <?php echo json_encode($label_suggestions); ?>
        });
    });
</script>    
<script type="text/javascript">
    $(document).ready(function() {
        
        var dropzone;
<?php if ($comment_type != "file") { ?>
            var uploadUrl = "<?php echo get_uri("quotation/upload_quotation_file"); ?>";
            var validationUrl = "<?php echo get_uri("quotation/validate_quotation_file"); ?>";
            dropzone = attachDropzoneWithForm("#quotation-dropzone", uploadUrl, validationUrl);
<?php } ?>
    
        $("#description").summernote({
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();

                // Firefox fix
                setTimeout(function () {
                    document.execCommand('insertText', false, bufferText);
                }, 10);
            },
            height: 160,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

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