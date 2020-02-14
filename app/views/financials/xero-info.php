<?php
  $line = $c = 1;
?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-md-12">
           <h2>Invoices</h2>
        </div>
    </div>
    <div id="waiting" class="row">
        <div class="col-lg-12 text-center">
            <h2>Drawing Table..</h2>
            <p>May take a few moments</p>
            <img class='loading' src='/images/preloader.gif' alt='loading...' />
        </div>
    <div class="row" id="table_holder" style="display:none">
        <div class="col-md-12">
            <table class="table-striped table-hover view-users" id="invoices_table" width="100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Invoice Number</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Amount Paid</th>
                        <th>Date Paid</th>
                        <th>Outstanding</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($invoices as $invoice):
                        $contact = $invoice->getContact();
                        $date = $invoice->DateString;
                        ?>
                        <tr>
                            <td><?php echo $date;?></td>
                            <td><?php echo $invoice->InvoiceNumber;?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">


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