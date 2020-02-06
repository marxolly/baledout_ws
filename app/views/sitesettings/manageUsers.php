<?php

?>
<div id="page-wrapper">
    <?php include(Config::get('VIEWS_PATH')."layout/page-includes/page_top.php");?>
    <div class="row">
        <div class="col-lg-4"><p><a href="/user/add-user" class="btn btn-primary">Add New User</a></p></div>
        <div class="col-lg-4"><p><a href="/site-settings/manage-users/active=1" class="btn btn-success">View Only Active Users</a></p></div>
        <div class="col-lg-4"><p><a href="/site-settings/manage-users/active=0" class="btn btn-danger">View Only Inactive Users</a></p></div>
        <div class="col-lg-4"></div>
    </div>
    <div id="waiting" class="row">
        <div class="col-lg-12 text-center">
            <h2>Drawing Table..</h2>
            <p>May take a few moments</p>
            <img class='loading' src='/images/preloader.gif' alt='loading...' />
        </div>
    </div>
    <div class="row" id="table_holder" style="display:none">
        <div class="col-md-12">
            <table class="table-striped table-hover view-users" id="view_users_table" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>User Level</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Last Login</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($user_roles as $ur):
                    if(!$this->controller->user->canManageRole($ur['id']))
                        continue;
                    $rolename = ucwords($ur['name']);?>
                        <?php $users = $this->controller->user->getAllUsersByRoleID($ur['id'], $active);
                        if(count($users)):
                            foreach($users as $user):
                            $last_log = ($user['last_log'] > 0)? date("F j, Y, g:i a", $user['last_log']):"Never"?>
                            <tr>
                                <td class="username"><img src="/images/profile_pictures/<?php echo $user['profile_picture'];?>" alt="profile image" class="img-thumbnail" /> <?php echo $user['name'];?></td>
                                <td><?php echo $rolename;?></td>
                                <td><?php echo $this->controller->client->getClientName($user['client_id']);?></td>
                                <td><?php echo $user['email'];?></td>
                                <td><?php echo $last_log;?></td>
                                <td></td>
                            </tr>
                            <?php endforeach;
                        endif;?>

                <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

</div>