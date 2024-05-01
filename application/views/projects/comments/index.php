<div class="panel">
    <?php
	if(isset($project_data->description) && $project_data->description != ''){
                    ?>
                <div class="col-md-12 col-sm-12">
                    <div class="row">
                        <div class="panel">
                            <div class="tab-title clearfix">
                                <h4><?php echo lang('description'); ?> (<?php echo $project_data->created_date; ?>)</h4>
                                <?php
                                $options = array( "project_id" => $project_data->id );
                                $files_data = $this->Project_files_model->get_details($options)->result();
                                if ( count ($files_data ) > 0 ){
                                    echo '<div class="project_attachments">';
                                    echo '<h5>' . lang('attached_files') .'</h5>';
                                    echo '<ul>';
                                    foreach( $files_data as $file_info ){
                                        $file_download = anchor(get_uri("projects/download_file/" . $file_info->id), "<i class='fa fa fa-cloud-download'></i>". remove_file_prefix($file_info->file_name) ."", array("title" => lang("download")));
                                        echo '<li>';
                                            echo $file_download;
                                        echo '</li>';                                    }
                                    echo '</ul></div>';
                                }
                                ?>
                                </ul>                            </div>
                            <div class="p15">
                                <?php echo nl2br( link_it( $project_data->description ) ); ?>
                            </div>
                        </div>  
                    </div>
                </div>
    <?php
              }
              
    
    ?>
</div>
    <div class="col-md-12 col-sm-12 comments_list">
    <?php $this->load->view("projects/comments/comment_list"); ?>
    </div>    
   <?php $this->load->view("projects/comments/comment_form");?>
</div>
