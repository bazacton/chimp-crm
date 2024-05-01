<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang($type.'s'); ?></h1>
            <div class="title-button-group">
                <?php
                if ($can_create_projects) {
                    echo modal_anchor(get_uri("projects/modal_form/".$type), "<i class='fa fa-plus-circle'></i> " . lang('add_'.$type), array("class" => "btn btn-default", "title" => lang('add_'.$type)));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="project-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var optionVisibility = false;
        if ("<?php echo ($can_edit_projects || $can_delete_projects) ; ?>") {
            optionVisibility = true;
        }

        $("#project-table").appTable({
            source: '<?php echo_uri("projects/list_data/".$type."/") ?>',
            <?php if (isset ($type ) && $type == 'quotation'){ ?>
                radioButtons: [{text: '<?php echo lang("new") ?>', name: "status", value: "new", isChecked: true, total_count:'<?php echo $total_new_counts; ?>'}, {text: '<?php echo lang("un_replied") ?>', name: "status", value: "un-replied", isChecked: false, total_count:'<?php echo $total_unreplied_counts; ?>'}, {text: '<?php echo lang("replied") ?>', name: "status", value: "replied", isChecked: false, total_count:'<?php echo $total_replied_counts; ?>'}, {text: '<?php echo lang("on_hold") ?>', name: "status", value: "on-hold", isChecked: false, total_count:'<?php echo $total_onhold_counts; ?>'}, {text: '<?php echo lang("disqualified") ?>', name: "status", value: "disqualified", isChecked: false, total_count:'<?php echo $total_disqualified_counts; ?>'}, {text: '<?php echo lang("completed") ?>', name: "status", value: "completed", isChecked: false, total_count:'<?php echo $total_completed_counts; ?>'}],
            <?php } else { ?>
                radioButtons: [{text: '<?php echo lang("new") ?>', name: "status", value: "new", isChecked: true}, {text: '<?php echo lang("un_replied") ?>', name: "status", value: "un-replied", isChecked: false}, {text: '<?php echo lang("replied") ?>', name: "status", value: "replied", isChecked: false}, {text: '<?php echo lang("on_hold") ?>', name: "status", value: "on-hold", isChecked: false}, {text: '<?php echo lang("disqualified") ?>', name: "status", value: "disqualified", isChecked: false}, {text: '<?php echo lang("completed") ?>', name: "status", value: "completed", isChecked: false}],
            <?php } ?>
            filterDropdown: [{name: "project_label", class: "w200", options: <?php echo $project_labels_dropdown; ?>}],
            columns: [
                <?php if (isset ($type ) && $type == 'quotation'){ ?>
                    {title: '<?php echo lang("id") ?>', "class": "w50"},
                    {title: '<?php echo lang("title") ?>'},
                    {title: '<?php echo lang("client") ?>', "class": "w10p"},
                    {visible: optionVisibility, title: '<?php echo lang("project_members") ?>', "class": "w10p"},
                    {title: '<?php echo lang("status") ?>', "class": "w10p"},
                    {title: '<?php echo lang("date") ?>', "class": "w10p"},
                    {visible: optionVisibility, title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}    
                <?php } else { ?>
                    {title: '<?php echo lang("id") ?>', "class": "w50"},
                    {title: '<?php echo lang("title") ?>'},
                    {title: '<?php echo lang("client") ?>', "class": "w10p"},
                    {visible: optionVisibility, title: '<?php echo lang("price") ?>', "class": "w10p"},
                    {visible: false, searchable: false},
                    {title: '<?php echo lang("start_date") ?>', "class": "w10p", "iDataSort": 4},
                    {visible: false, searchable: false},
                    {title: '<?php echo lang("deadline") ?>', "class": "w10p", "iDataSort": 6},
                    {title: '<?php echo lang("progress") ?>', "class": "w10p"},
                    {title: '<?php echo lang("status") ?>', "class": "w10p"},
                    {visible: optionVisibility, title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
                <?php } ?>
            ],
            order: [[1, "desc"]],
            printColumns: [0, 1, 2, 3, 5, 7, 8],
            xlsColumns: [0, 1, 2, 3, 5, 7, 8]
        });
    });
</script>
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