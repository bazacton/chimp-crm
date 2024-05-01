<?php if (isset($page_type) && $page_type === "full") { ?>
    <div id="page-content" class="m20 clearfix">
    <?php } ?>

    <div class="panel">
        <?php if (isset($page_type) && $page_type === "full" && $type == 'quotation') { ?>
            <div class="page-title clearfix">
                <h1><?php echo lang($type.'s'); ?></h1>
                <div class="title-button-group">
                    <?php
                        echo modal_anchor(get_uri("projects/modal_form/".$type), "<i class='fa fa-plus-circle'></i> " . lang('add_'.$type), array("class" => "btn btn-default", "title" => lang('add_'.$type)));
                    ?>
                </div>
            </div>
        <?php } else if (isset($page_type) && $page_type === "dashboard") { ?>
            <div class="page-title panel-sky clearfix">
                <h1><?php echo lang($type.'s'); ?></h1>
            </div>
        <?php } else { ?>
            <div class="tab-title clearfix">
                <h4><?php echo lang($type.'s'); ?></h4>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table id="project-table" class="display" width="100%">            
            </table>
        </div>
    </div>
    <?php if (isset($page_type) && $page_type === "full") { ?>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        var hideTools = "<?php
if (isset($page_type) && $page_type === 'dashboard') {
    echo 1;
}

?>";

        $("#project-table").appTable({
            source: '<?php echo_uri("projects/projects_list_data_of_client/" . $client_id. "/". $type) ?>',
            order: [[0, "desc"]],
            hideTools: hideTools,
            columns: [
            <?php if (isset($type) && $type === "quotation") { ?>
                {title: '<?php echo lang("title") ?>'},
                {title: '<?php echo lang("created_date") ?>'},
            <?php } else { ?>
                {title: '<?php echo lang("id") ?>', "class": "w50"},
                {title: '<?php echo lang("title") ?>'},
                {targets: [2], visible: false, searchable: false},
                {title: '<?php echo lang("price") ?>', "class": "w10p"},
                {visible: false, searchable: false},
                {title: '<?php echo lang("start_date") ?>', "class": "w10p", "iDataSort": 4},
                {visible: false, searchable: false},
                {title: '<?php echo lang("deadline") ?>', "class": "w10p", "iDataSort": 6},
                {title: '<?php echo lang("progress") ?>', "class": "w15p"},
                {title: '<?php echo lang("status") ?>', "class": "w10p"},
            <?php } ?>
            ],
            printColumns: [0, 1, 3, 5, 7, 9],
            xlsColumns: [0, 1, 3, 5, 7, 9]
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