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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h2>Current Status Labels</h2>
        </div>
    </div>
    <?php if(count($statusses)):?>
        <div class="row">
            <?php foreach($statusses as $s):?>
                <form class="edit-jobstatus" id="role_<?php echo $s['id'];?>"action="/form/procJobStatusEdit" method="post">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-form-label">Name</label>
                            <input type="text" class="form-control required statusname" name="name_<?php echo $r['id'];?>" id="name_<?php echo $s['id'];?>" value="<?php echo ucwords($s['name']);?>" />
                            <input type="hidden" name="currentname_<?php echo $s['id'];?>" value="<?php echo $s['name'];?>"/>
                            <?php echo Form::displayError("name_{$s['id']}");?>
                        </div>
                        <?php if(Session::getUserRole() == "super admin");?>
                            <div class="col-md-1">
                                <label class="col-form-label">Locked</label>
                                <div class="checkbox checkbox-default">
                                    <input class="form-check-input styled" type="checkbox" id="checked_<?php echo $r['id'];?>" name="checked_<?php echo $s['id'];?>" <?php if($s['checked'] > 0) echo "checked";?> />
                                    <label for="active_<?php echo $s['id'];?>"></label>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="col-md-1">
                            <label class="col-form-label">&nbsp;</label>
                            <div class="input-group">
                                <input type="hidden" name="csrf_token" value="<?php echo Session::generateCsrfToken(); ?>" />
                                <input type="hidden" name="line_id" value="<?php echo $s['id'];?>" />
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach;?>
        </div>
    <?php else:?>
        <div class="row">
            <div class="col-lg-12">
                <div class="errorbox">
                    <h2><i class="fas fa-exclamation-triangle"></i> No Status Labels Listed</h2>
                    <p>You will need to add some first</p>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>