<?php
  $line = $c = 1;
?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-md-12">
            <?php echo "ORG<pre>",print_r($contacts),"</pre>"; ?> 
        </div>
    </div>
</div>