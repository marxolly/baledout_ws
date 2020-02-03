<?php

?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-lg-12">
            <select  class="form-control selectpicker" name="status"><?php echo $this->controller->jobstatus->getSelectStatus();?></select>
        </div>
    </div>
</div>