<div class="main-content-section">   
    <div class="main-content payment-list-div">       
        <div class="profile-data">
            <h2>View Profile <a href="<?= HTTP_ROOT . "users/edit-profile"; ?>" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <h3><?= $user->name; ?></h3>            
            <div class="pro-d-left">Name</div>
            <div class="pro-d-right"><?= $user->name; ?></div>
            <div class="pro-d-left">Email</div>
            <div class="pro-d-right"><?= $user->email; ?></div>
            <div class="pro-d-left">Phone</div>
            <div class="pro-d-right"><?= $user->phone; ?></div>   
            <div class="pro-d-left">Access</div>
            <div class="pro-d-right"><?= $user->access == 2 ? "Full Access" : "View Only"; ?></div>   
            <div style="clear: both;">&nbsp;</div>
        </div>              
    </div>
</div>