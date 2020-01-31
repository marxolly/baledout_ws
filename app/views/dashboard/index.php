<?php
$panel_classes = array(
    'primary',
    'info',
    'success',
    'warning',
    'danger'
);
$c = 1;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Baledout Web Portal</h1>
        </div>
    </div>
     <?php //echo $user_role; die();?> 
    <?php if($user_role == "admin"):?>
        <input type="hidden" id="admin_from_value" value="<?php echo strtotime('last friday', strtotime('-3 months'));?>" />
        <input type="hidden" id="admin_to_value" value="<?php echo strtotime('last friday', strtotime('tomorrow'));?>" />
        <div class="row">
            <h1>Header 1</h1>
            <h2>Header 2</h2>
            <h3>Header 3</h3>
            <h4>Header 4</h4>
        </div><!-- end 1st row -->
        <div class="row"><!-- second row -->

        </div> <!-- end 2nd row -->
        <div class="row">

        </div>
    <?php else:?>
        <div class="row">
            <div class="col-lg-12">
                <div class="errorbox">
                    <div class="row">
                        <div class="col-lg-2" style="font-size:96px">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="col-lg-6">
                            <h2>User Classification Error</h2>
                            <p>Sorry, there has been an error determining your access priviledges</p>
                            <p><a href="/login/logout">Please click here to login again</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>