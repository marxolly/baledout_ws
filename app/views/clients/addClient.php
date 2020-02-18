<?php

?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-lg-12">
            <p><a href="/clients/view-clients/" class="btn btn-primary">Return to Client List</a></p>
        </div>
    </div>
    <?php echo $coming_soon;?>
</div>