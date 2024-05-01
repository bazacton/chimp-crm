<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('my_tasks'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="task-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#task-table").appTable({
            source: '<?php echo_uri("projects/my_tasks_list_data") ?>',
            order: [[0, "desc"]],
            filterDropdown: [{name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>}],
            checkBoxes: [
                {text: '<?php echo lang("to_do") ?>', name: "status", value: "to_do", isChecked: true},
                {text: '<?php echo lang("in_progress") ?>', name: "status", value: "in_progress", isChecked: true},
                {text: '<?php echo lang("done") ?>', name: "status", value: "done", isChecked: false}
            ],
            columns: [
                {title: '<?php echo lang("id") ?>'},
                {title: '<?php echo lang("title") ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo lang("start_date") ?>', "iDataSort": 2},
                {visible: false, searchable: false},
                {title: '<?php echo lang("deadline") ?>', "iDataSort": 4},
                {title: '<?php echo lang("project") ?>'},
                {title: '<?php echo lang("assigned_to") ?>', "class": "min-w150"},
                {title: '<?php echo lang("collaborators") ?>'},
                {title: '<?php echo lang("status") ?>'},
                {targets: [4], visible: false, searchable: false}
            ],
            printColumns: [0, 1, 3, 5, 6, 7, 8, 9],
            xlsColumns: [0, 1, 3, 5, 6, 7, 8, 9],
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).addClass(aData[11]);
            }
        });

    });
</script>

<?php $this->load->view("projects/tasks/update_task_script"); ?>