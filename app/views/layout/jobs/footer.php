
        <?php include(Config::get('VIEWS_PATH')."layout/page-includes/footer_common.php");?>
        <?php include(Config::get('VIEWS_PATH')."layout/page-includes/footer_scripts.php");?>

        <script>
            //the actions for each page
            var actions = {
                common: {
                    init: function(){

                    },
                'jobs-edit': {
                    init: function(){
                        $('form#order-edit').submit(function(e){
                            if($(this).valid())
                            {
                                $.blockUI({ message: '<div style="height:160px; padding-top:20px;"><h2>Updating Order...</h2></div>' });
                            }
                            else
                            {
                                return false;
                            }
                        });
                    }
                },
                'job-update' : {
                    init: function(){
                        actions.common.init();
                    }
                },
                'add-job': {
                    init: function()
                    {

                    }
                },
                'view-jobs': {
                    init: function(){

                    }
                },
                'job-search':{
                    init: function(){
                        $("form#order_search").submit(function(e){
                            if($(this).valid())
                            {
                                $.blockUI({ message: '<div style="height:160px; padding-top:20px;"><h2>Searching Orders...</h2></div>' });
                            }
                            else
                            {
                                return false;
                            }
                        });

                        datePicker.betweenDates();
                    }
                },
                'job-search-results':{
                    init: function(){
                        actions['order-search'].init();
                    }
                },
                'order-tracking' : {
                    init: function(){

                    }
                },
                'order-detail' : {
                    init: function(){
                        $('button#print').click(function(e){
                        	$("div#print_this").printArea({
                                    //put some options in
                            });;
                        });
                    }
                }
            }
            //console.log('current page: '+config.curPage);
            //run the script for the current page
            actions[config.curPage].init();
        </script>
        <?php Database::closeConnection(); ?>
    </body>
</html>