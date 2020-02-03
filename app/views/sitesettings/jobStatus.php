<?php

?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/form-top.php");?>
    <?php echo Form::displayError('general');?>
    <div class="row">
        <form id="add-jobstatus"  method="post" enctype="multipart/form-data" action="/form/procJobStatusAdd">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Add New Job Status</h3>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label"><sup><small><i class="fas fa-asterisk text-danger"></i></small></sup> Name</label>
                <div class="col-md-4">
                    <input type="text" class="form-control required" name="name" id="name" value="<?php echo Form::value('name');?>" />
                    <?php echo Form::displayError('name');?>
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo Session::generateCsrfToken(); ?>" />
            <div class="form-group row">
                <label class="col-md-3 col-form-label">&nbsp;</label>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Add Role</button>
                </div>
            </div>
        </form>
    </div>






    <div class="row">
        <div class="col-lg-12">
            <select  class="form-control selectpicker" name="status"><?php echo $this->controller->jobstatus->getSelectStatus();?></select>
        </div>
    </div>
</div>