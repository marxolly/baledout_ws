<?php
  $line = $c = 1;
?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach($contacts as $contact)
            {
                //do something here

                //this will give you the raw data without the object structure
                echo "<pre>",print_r($contact->toStringArray()),"</pre>";
            }

            ?>
        </div>
    </div>
</div>