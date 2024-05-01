<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('description'); ?> (<?php echo $project_info->created_date; ?>)</h4>
        <?php
        $options = array( "project_id" => $project_info->id );
        $files_data = $this->Project_files_model->get_details($options)->result();
        if ( count ($files_data ) > 0 ){
            echo '<div class="project_attachments">';
            echo '<h5>' . lang('attached_files') .'</h5>';
            echo '<ul>';
            foreach( $files_data as $file_info ){
                $file_download = anchor(get_uri("projects/download_file/" . $file_info->id), "<i class='fa fa fa-cloud-download'></i>". remove_file_prefix($file_info->file_name) ."", array("title" => lang("download")));
                echo '<li>';
                    echo $file_download;
                echo '</li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>
    <div class="p15">
        <?php echo nl2br(link_it($project_info->description)); ?>
    </div>
</div>