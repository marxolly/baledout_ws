
        <?php include(Config::get('VIEWS_PATH')."layout/page-includes/footer_common.php");?>
        <?php include(Config::get('VIEWS_PATH')."layout/page-includes/footer_scripts.php");?>

        <script>
            //the actions for each page
            var actions = {
                common: {
                    init: function(){

                    },
                    userActivation: function(){
                        $("a.deactivate").off('click').click(function(e){
                            //console.log('click');
                            var $but = $(this);
                            var thisuserid = $but.data('userid');
                            var data = {userid: thisuserid};
                            swal({
                                title: "Deactivate User?",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            }).then( function(willDeactivate) {
                                if (willDeactivate) {
                                    $.blockUI({ message: '<div style="height:160px; padding-top:40px;"><h1>Deactivating User...</h1></div>' });
                                    //console.log(data);
                                    $.post('/ajaxfunctions/deactivateUser', data, function(d){
                                        $but.closest('p').html("<a class='btn btn-success reactivate' data-userid='"+thisuserid+"'>Reactivate User</a>");
                                        $.unblockUI();
                                        actions.common.userActivation();
                                    });
                                }
                            });
                        });

                        $("a.reactivate").off('click').click(function(e){
                            var $but = $(this);
                            var thisuserid = $but.data('userid');
                            var data = {userid: thisuserid};
                            swal({
                                title: "Reactivate User?",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            }).then( function(willReactivate) {
                                if (willReactivate) {
                                    $.blockUI({ message: '<div style="height:160px; padding-top:40px;"><h1>Reactivating User...</h1></div>' });
                                    $.post('/ajaxfunctions/reactivateUser', data, function(d){
                                        $but.closest('p').html("<a class='btn btn-danger deactivate' data-userid='"+thisuserid+"'>Deactivate User</a>");
                                        $.unblockUI();
                                        actions.common.userActivation();
                                    });
                                }
                            });
                        });
                    }
                },
                'job-status': {
                    init: function()
                    {
                        $('form#add-jobstatus, form.edit-jobstatus').submit(function(){
                            if($(this).valid())
                            {
                                $.blockUI({ message: '<div style="height:140px; padding-top:20px;"><h2>Processing form...</h2></div>' });
                            }
                            else
                            {
                                return false;
                            }
                        });
                    }
                },
                'couriers':{
                    init: function(){
                        $('form#add-courier, form.edit-courier').submit(function(){
                            if($(this).valid())
                            {
                                $.blockUI({ message: '<div style="height:140px; padding-top:20px;"><h2>Processing form...</h2></div>' });
                            }
                            else
                            {
                                return false;
                            }
                        });
                    }
                },
                'user-roles':{
                    init: function(){
                        /*
                        $.validator.addClassRules("userrolename", {
                            uniqueUserRole : {
                                url: '/ajaxfunctions/checkRoleNames',
                                //data: { 'term': function(){ return $(this).val(); } }
                            }
                        });
                        */
                        $( "#sortable" ).sortable({
                            axis: 'y',
                            placeholder:"ui-state-highlight",
                            stop: function(event, ui){
                                var data = $(this).sortable('serialize');
                                var url = "/ajax-functions/update-role-rankings";
                                //$('span#text').text(data+" "+url);
                                $.blockUI({ message: '<div style="height:140px; padding-top:20px;"><h2>Updating Heirarchy...</h2></div>' });
                                $.post(url, data, function(d){
                                    $.unblockUI();
                                    if(d.error)
                                    {
                                        swal({
                                            title: 'Could not update',
                                            text: d.feedback,
                                            icon: "error"
                                        });
                                    }
                                    else
                                    {
                                        swal({
                                            title: 'Heirarchy Updated',
                                            text: d.feedback,
                                            icon: "success"
                                        });
                                    }
                                });
                            }
                        });
                        $('form#add-userrole, form.edit-userrole').submit(function(){
                            if($(this).valid())
                            {
                                $.blockUI({ message: '<div style="height:140px; padding-top:20px;"><h2>Processing form...</h2></div>' });
                            }
                            else
                            {
                                return false;
                            }
                        });
                    }
                },
                'manage-users':{
                    init: function(){
                        dataTable.init($('table#view_users_table'), {
                            "ordering": false
                        } );
                        actions.common.userActivation();
                    }
                }
            }

            //run the script for the current page
            actions[config.curPage].init();
        </script>
        <?php Database::closeConnection(); ?>
    </body>
</html>