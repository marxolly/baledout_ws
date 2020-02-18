        <!-- Jquery JavaScript -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <!-- Validation JavaScript -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js" ></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js" ></script>
        <script src="/scripts/form_validators.js?t=<?php echo time();?>" ></script>
        <!-- Bootstrap JavaScript -->
        <!--script src="/scripts/bootstrap.3.3.4.min.js"></script-->
        <!--script src="/scripts/bootstrap.3.3.7.min.js"></script-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <!-- Bootstrap Select Styling >
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
        <!-- Menu JavaScript -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.8/metisMenu.min.js"></script>
        <!-- DataTables JavaScript -->
        <script src="//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
        <!-- Block UI JavaScript -->
        <script src="/scripts/jquery.blockUI.js"></script>
        <!-- Print Area Javascript -->
        <script src="/scripts/jquery.PrintArea.js"></script> 
        <!-- Live Filter JavaScript -->
        <!--script src="/scripts/jquery.liveFilter.js"></script-->
        <script src="/scripts/jquery.filtertable.min.js"></script>
        <!-- File download JavaScript -->
        <script src="/scripts/jquery.filedownload.js"></script>
        <!-- Sacnner detection -->
        <script src="/scripts/jquery.scannerdetection.js"></script>
        <!-- Sweet alerts JavaScript -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- Sound PlayingJavaScript -->
        <script src='https://cdn.rawgit.com/admsev/jquery-play-sound/master/jquery.playSound.js'></script>
        <!-- Google Charts JavaScript -->
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- Sticky table headers -->
        <script src="https://unpkg.com/sticky-table-headers"></script>
        <!-- WMS JavaScript -->
        <script src="/scripts/common.js?t=<?php echo time();?>"></script>

        <!-- Assign CSRF Token to JS variable -->
        <?php Config::setJsConfig('csrfToken', Session::generateCsrfToken()); ?>
        <!-- Assign pages for menu highlighting -->
        <?php
        if(Session::getIsLoggedIn()):
            $user_role = (Session::isAdminUser())? 'admin' : Session::getUserRole();
            $user_role = str_replace(" ","_", $user_role);
            $pages = Config::get(strtoupper($user_role."_PAGES"));
            Config::setJsConfig('allPages', $pages);
        else:
            Config::setJsConfig('allPages', '');
        endif;
        ?>
        <!-- Assign all configuration variables -->
        <script>config = <?php echo json_encode(Config::getJsConfig()); ?>;</script>