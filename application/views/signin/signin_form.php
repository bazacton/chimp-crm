<div class="panel panel-default mb15">
    <div class="panel-heading text-center no-border">
        <h2><?php echo lang('signin'); ?></h2>
    </div>
    <div class="panel-body p30">
        <?php echo form_open("signin", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>

        <?php if (validation_errors()) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo validation_errors(); ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control p10",
                "placeholder" => lang('email'),
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
                "placeholder" => lang('password'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required")
            ));
            ?>
        </div>
        <input type="hidden" name="redirect" value="<?php if (isset($redirect)) {
                echo $redirect;
            } ?>" />

        <div class="form-group mb0">
            <button class="btn btn-lg btn-primary btn-block mt15" type="submit"><?php echo lang('signin'); ?></button>
        </div>
<?php echo form_close(); ?>
        <div class="mt5"><?php echo anchor("signin/request_reset_password", lang("forgot_password")); ?></div>
    </div>
</div>
<?php if (!get_setting("disable_client_login_and_signup")) { ?>
    <div><?php echo lang("you_dont_have_an_account") ?> &nbsp; <?php echo anchor("signup", lang("signup")); ?></div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#signin-form").appForm({ajaxSubmit: false, isModal: false});
    });
</script>    