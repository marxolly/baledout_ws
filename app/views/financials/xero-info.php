<?php
  $line = $c = 1;
?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-md-12">
            <h2>Invoices</h2>
            <?php
            foreach($invoices as $i)
            {
                $contact = $i->getContact();
                $owing = "$".number_format($i->AmountDue, 2,'.', ',');
                echo "<p>".$contact->getName()." - ".$owing."</p>";
            }
            ?>
            <h2>Contacts</h2>
            <?php
            foreach($contacts as $contact)
            {
                //do something here
                $name = $contact->getName()." (".$contact->getFirstName()." ".$contact->getLastName().")";
                $email = $contact->getEmailAddress();
                echo "<p>$name - $email</p>";
                //this will give you the raw data without the object structure
                //echo "<pre>",print_r($contact->toStringArray()),"</pre>";
            }
            ?>
        </div>
    </div>
</div>