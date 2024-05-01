<div class="list-group" id="notificaion-popup-list" style="max-height: 400px; overflow-y: scroll;">
    <?php
    $view_data["notifications"] = $notifications;
    $this->load->view("notifications/list_data", $view_data);
    ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if ($.fn.slimscroll) {
            //don't apply slimscroll for mobile devices
            if ($(window).width() > 640) {
                if ($('#notificaion-popup-list').height() >= 400) {
                    $('#notificaion-popup-list').slimscroll({
                        borderRadius: "0",
                        height: "400px"
                    });
                } else {
                    $('#notificaion-popup-list').css({"overflow-y": "auto"});
                }

            }
        }
    });
</script>
