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
            <table class="table-striped table-hover" id="view_users_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Last Login</th>
                    </tr>
                </thead>
                <?php foreach($user_roles as $ur):
                    if(!$this->controller->user->canManageRole($ur['id']))
                        continue;
                    $rolename = ucwords($ur['name']);?>
                    <tbody>
                        <tr>
                            <td colspan="4"><?php echo $rolename;?></td>
                        </tr>
                        <?php $users = $this->controller->user->getAllUsersByRoleID($ur['id'], $active);
                        if(count($users)):
                            foreach($users as $user):?>
                            <tr>
                                <td><img src="/images/profile_pictures/<?php echo $user['profile_picture'];?>" alt="profile image" class="img-thumbnail" /> <?php echo $user['name'];?></td>
                                <td><?php echo $this->controller->client->getClientName($user['client_id']);?></td>
                                <td><?php echo $user['email'];?></td>
                                <td></td>
                            </tr>
                            <?php endforeach;
                        else:?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                <?php endforeach;?>
            </table>
        </div>

    </div>

</div>