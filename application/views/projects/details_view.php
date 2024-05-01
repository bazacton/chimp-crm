
<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-md-12">
            <div class="page-title mb15 clearfix">
                <h1>
                    <?php if ($project_info->status == "new") { ?>
                    <i class="fa fa-th-large" title="<?php echo lang("new"); ?>"></i>
                    <?php } else if ($project_info->status == "un-replied") { ?>
                       <i class="fa fa-question-circle" title="<?php echo lang("un_replied"); ?>"></i>
                    <?php } else if ($project_info->status == "replied") { ?>
                       <i class="fa fa-mail-reply" title="<?php echo lang("replied"); ?>"></i>
                    <?php } else if ($project_info->status == "on-hold") { ?>
                       <i class="fa fa-bell" title="<?php echo lang("on_hold"); ?>"></i>
                    <?php } else if ($project_info->status == "disqualified") { ?>
                       <i class="fa fa-times-circle" title="<?php echo lang("disqualified"); ?>"></i>
                    <?php } else if ($project_info->status == "completed") { ?>
                       <i class="fa fa-check-circle" title="<?php echo lang("completed"); ?>"></i>
                    <?php } ?>

                    <?php echo $project_info->title; ?> 
                       <?php if ( isset(  $project_info->theme_name ) &&  $project_info->theme_name != '' ){
                        echo '( ' . $project_info->theme_name. ' )';
                       } ?>
                </h1>

                <div class="title-button-group" id="project-timer-box">
                    <?php if ($can_create_projects || $this->login_user->user_type == 'staff' ) { ?>
                        <span class="dropdown inline-block">
                            <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                                <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/new"), "<i class='fa fa-th-large'></i> " . lang('mark_as_new'), array("class" => "", "title" => lang('mark_as_new'), "data-reload-on-success" => "1")); ?> </li>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/un-replied"), "<i class='fa fa-question-circle'></i> " . lang('mark_as_un_replied'), array("class" => "", "title" => lang('mark_as_un_replied'), "data-reload-on-success" => "1")); ?> </li>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/replied"), "<i class='fa fa-mail-reply'></i> " . lang('mark_as_replied'), array("class" => "", "title" => lang('mark_as_replied'), "data-reload-on-success" => "1")); ?> </li>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/on-hold"), "<i class='fa fa-bell'></i> " . lang('mark_as_on_hold'), array("class" => "", "title" => lang('mark_as_on_hold'), "data-reload-on-success" => "1")); ?> </li>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/disqualified"), "<i class='fa fa-times-circle'></i> " . lang('mark_as_disqualified'), array("class" => "", "title" => lang('mark_as_disqualified'), "data-reload-on-success" => "1")); ?> </li>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/completed"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_completed'), array("class" => "", "title" => lang('mark_as_completed'), "data-reload-on-success" => "1")); ?> </li>
                            </ul>
                        </span>
                    <?php } ?>
                    <?php
                    if ($show_timmer) {
                        $this->load->view("projects/project_timer");
                    }
                    ?>
                </div>
            </div>
            <ul id="project-tabs" data-toggle="ajax-tab" class="nav nav-tabs classic" role="tablist">
                <?php if ($this->login_user->user_type === "staff") { ?>
                    <li><a role="presentation" href="<?php echo_uri("projects/overview/" . $project_info->id); ?>" data-target="#project-overview-section"><?php echo lang('overview'); ?></a></li>
                    <li><a role="presentation" href="<?php echo_uri("projects/tasks/" . $project_info->id); ?>" data-target="#project-tasks-section"><?php echo lang('tasks'); ?></a></li>
                    <?php if( $project_info->type != 'quotation' ) { ?>
                        <li><a role="presentation" href="<?php echo_uri("projects/milestones/" . $project_info->id); ?>" data-target="#project-milestones-section"> <?php echo lang('milestones'); ?></a></li>
                        <li><a role="presentation" href="<?php echo_uri("projects/notes/" . $project_info->id); ?>" data-target="#project-notes-section"> <?php echo lang('notes'); ?></a></li>
                        <li><a role="presentation" href="<?php echo_uri("projects/files/" . $project_info->id); ?>" data-target="#project-files-section"> <?php echo lang('files'); ?></a></li>
                    <?php } ?>
                    <li><a role="presentation" href="<?php echo_uri("projects/comments/" . $project_info->id); ?>" data-target="#project-comments-section"> <?php echo lang('comments'); ?></a></li>
                    <li><a role="presentation" href="<?php echo_uri("projects/customer_feedback/" . $project_info->id); ?>" data-target="#project-customer-feedback-section"> <?php echo lang('customer_messages'); ?>
                        <?php if(isset($new_messages) && $new_messages > 0 ){
                                echo '<span class="new-msgs"> '. $new_messages .' </span>';
                            } 
                        ?>
                        </a></li>
                    <?php if( $project_info->type != 'quotation' ) { ?>
                    <li><a role="presentation" href="<?php echo_uri("projects/timesheets/" . $project_info->id); ?>" data-target="#project-timesheets-section"> <?php echo lang('timesheets'); ?></a></li>
                        <?php if ($show_invoice_info) { ?>
                            <li><a  role="presentation" href="<?php echo_uri("projects/invoices/" . $project_info->id); ?>" data-target="#project-invoices"> <?php echo lang('invoices'); ?></a></li>
                            <li><a  role="presentation" href="<?php echo_uri("projects/payments/" . $project_info->id); ?>" data-target="#project-payments"> <?php echo lang('payments'); ?></a></li>
                        <?php } ?>
                        <?php if ($show_expense_info) { ?>
                            <li><a  role="presentation" href="<?php echo_uri("projects/expenses/" . $project_info->id); ?>" data-target="#project-expenses"> <?php echo lang('expenses'); ?></a></li>
                        <?php } ?>  
                    <?php } ?> 

                <?php } else { ?>
                    
                    <li><a role="presentation" href="<?php echo_uri("projects/customer_feedback/" . $project_info->id); ?>" data-target="#project-customer-feedback-section"> <?php echo lang('messages'); ?>
                        <?php if(isset($new_messages) && $new_messages > 0 ){
                                echo '<span class="new-msgs"> '. $new_messages .' </span>';
                            } 
                        ?>
                    </a></li>
                    <?php if ($show_tasks) { ?>
                        <li><a role="presentation" href="<?php echo_uri("projects/tasks/" . $project_info->id); ?>" data-target="#project-tasks-section"><?php echo lang('tasks'); ?></a></li>
                    <?php } ?>
                <?php } ?>


            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active" id="project-overview-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-tasks-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-milestones-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-files-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-comments-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-customer-feedback-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-notes-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-timesheets-section"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-invoices"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-payments"></div>
                <div role="tabpanel" class="tab-pane fade" id="project-expenses"></div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        var tab = "<?php echo $tab; ?>";
        if (tab === "comment") {
            $("[data-target=#project-comments-section]").trigger("click");
        } else if (tab === "customer_feedback") {
            $("[data-target=#project-customer-feedback-section]").trigger("click");
        } else if (tab === "files") {
            $("[data-target=#project-files-section]").trigger("click");
        }
    });
</script>