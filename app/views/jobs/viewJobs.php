<?php
  $states = array(
    "VIC",
    "NSW",
    "TAS",
    "ACT",
    "QLD",
    "NT",
    "SA",
    "WA",
  );
  asort($states);
?>
<div id="page-wrapper">
    <input type="hidden" id="fulfilled" value="<?php echo $fulfilled;?>" />
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <?php if(count($jobs)):?>
        <div class="row">
            <?php echo "<pre>",print_r($jobs),"</pre>";?>
        </div>
    <?php else:?>
        <div class="row">
            <div class="col-lg-12">
                <div class="errorbox">
                    <h2><i class="fas fa-exclamation-triangle"></i> No Orders Listed</h2>
                    <p>Either all orders are fulfilled or you need to remove some filters</p>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>
