<?php
  $line = $c = 1;
?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-md-12">
           <h2>Outstanding Invoices</h2>
        </div>
    </div>
    <?php if(count($invoices)):?>
    <div id="waiting" class="row">
        <div class="col-lg-12 text-center">
            <h2>Drawing Table..</h2>
            <p>May take a few moments</p>
            <img class='loading' src='/images/preloader.gif' alt='loading...' />
        </div>
    </div>
    <div class="row" id="table_holder" style="display:none">
        <div class="col-md-12">
            <table class="table-striped table-hover view-users" id="invoices_table" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Invoice Number</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Still Owing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($invoices as $invoice):
                        $contact = $invoice->getContact();
                        $person = $contact->getFirstName()." ".$contact->getLastName();
                        $date = $invoice->Date;
                        $owing = "$".number_format($invoice->AmountDue, 2,'.', ',');
                        ?>
                        <tr>
                            <td class="number"><?php echo $c;?></td>
                            <td class="number"><?php echo $date->format('d-m-Y');?></td>
                            <td><?php echo $invoice->InvoiceNumber;?></td>
                            <td><?php echo $contact->getName();?></td>
                            <td><?php echo $person;?></td>
                            <td><?php echo $contact->getEmailAddress();?></td>
                            <td class="number"><?php echo $owing;?></td>
                        </tr>
                    <?php ++$c;
                    endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else:?>
        <div class="row">
            <div class="col-md-12">
                <div class='feedbackbox'>
                    <h3>No invoices Listed</h3>
                    <p>All invoices for the past 28 days are currently marked as paid</p>
                </div>
            </div>
        </div>
    <?php endif;?>
    <div class="row">
        <div class="col-md-12">
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