<div class="main-content-section">
    <div class="main-content payment-list-div">

        <div class="profile-data">
            
            <?php if ($this->request->getSession()->read('Auth.User.id') == $merchantDetails->id) { ?>
                <h2>Basic Info <a href="<?= HTTP_ROOT . "merchants/account-setup"; ?>#BasicInfo" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <?php } else { ?>
                <h2>Basic Info <a href="<?= HTTP_ROOT . "admin/edit-merchant/" . $merchantDetails->uniq_id; ?>#BasicInfo" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <?php } ?>

                <div class="profile-data-inn">
                    <h3><?= $merchantDetails->name; ?></h3>
                    <p><?= $merchantDetails->merchant->description; ?></p>
                    <div class="pro-d-left">Name</div>
                    <div class="pro-d-right"><?= $merchantDetails->name; ?></div>
                    <div class="pro-d-left">Profile URL</div>
                    <div class="pro-d-right"><a href="<?= HTTP_ROOT . "webfronts/" . $merchantDetails->merchant->profile_url; ?>" target="_blank"><?= HTTP_ROOT . "webfronts/" . $merchantDetails->merchant->profile_url; ?></a></div>
                    <div class="pro-d-left">Email</div>
                    <div class="pro-d-right"><?= $merchantDetails->email; ?></div>
                    <div class="pro-d-left">Phone</div>
                    <div class="pro-d-right"><?= $merchantDetails->phone; ?></div>
                    <div class="pro-d-left">Address</div>
                    <div class="pro-d-right"><?= $merchantDetails->merchant->address; ?>, <?= $merchantDetails->merchant->city; ?>, <?= $merchantDetails->merchant->state; ?>, <?= $merchantDetails->merchant->country; ?></div>
                    <div class="pro-d-left">Convenience Fee</div>
                    <div class="pro-d-right">Rs.<?= formatPrice($merchantDetails->merchant->convenience_fee_amount); ?></div>
                </div>
        </div>

        <div class="profile-data">
            
            <?php if ($this->request->getSession()->read('Auth.User.id') == $merchantDetails->id) { ?>
                <h2>Payment Gateways <a href="<?= HTTP_ROOT . "merchants/account-setup"; ?>#PaymentGateways" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <?php } else { ?>
                <h2>Payment Gateways <a href="<?= HTTP_ROOT . "admin/edit-merchant/" . $merchantDetails->uniq_id; ?>#PaymentGateways" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <?php } ?>
            
            <div class="section-2" style="width: 100%;float:left;padding: 10px;">
                <div class="section-tile">
                    <div class="accordion" id="fields">
                        <?php
                        $i = 1;
                        foreach ($merchantPaymentGateways as $paymentGateway) {
                            ?>
                            <div class="card">
                                <div class="card-header">
                                    <a class="making-block <?= ($i != 1) ? "collapsed" : "" ?>" data-toggle="collapse" href="#collapse<?= $i ?>" role="button" aria-expanded="false">
                                        <span style="display: block;"><?= $paymentGateway->title; ?></span>
                                    </a>
                                </div>
                                <div id="collapse<?= $i ?>" class="collapse <?= ($i == 1) ? "show" : "" ?>" data-parent="#fields">
                                    <div class="card-body" style="padding: 0px;display: block;float: left; max-width: 85%; margin-top: 20px;">
                                        <div id="showPGInfo<?= $paymentGateway->id ?>">
                                            <div class="pro-d-left">Name</div>
                                            <div class="pro-d-right"><?= $paymentGateway->title; ?></div>
                                            <div class="pro-d-left">Payment Gateway</div>
                                            <div class="pro-d-right"><?= $paymentGateway->payment_gateway->name; ?></div>
                                            <div class="pro-d-left"> Key</div>
                                            <div class="pro-d-right"><?= $paymentGateway->merchant_key; ?></div>
                                            <div class="pro-d-left"> Salt </div>
                                            <div class="pro-d-right"><?= $paymentGateway->merchant_salt; ?></div>
                                            <div class="pro-d-left">Active </div>
                                            <div class="pro-d-right"><?= $paymentGateway->is_default ? 'Yes' : 'No'; ?></div>
                                        </div>
                                    </div>  
                                    <div style="float: right; margin: 5px 10px;">
                                        <?php if ($paymentGateway->is_default) { ?>
                                            <a href="<?= HTTP_ROOT . "merchants/activatePaymentGateway/{$paymentGateway->unique_id}" ?>" onclick="return confirm('Are you sure you want to De-Activate?')"><i class="fa fa-thumbs-up" style="color: #28d227; font-size: 25px;" aria-hidden="true"></i></a>&nbsp;
                                        <?php } else { ?>
                                            <a href="<?= HTTP_ROOT . "merchants/activatePaymentGateway/{$paymentGateway->unique_id}" ?>" onclick="return confirm('Are you sure you want to activate?')"><i class="fa fa-thumbs-down" style="color: #e22e4e; font-size: 25px;" aria-hidden="true"></i></a>&nbsp;
                                        <?php } ?>                                   
                                    </div> 
                                </div> 
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                    <?php if ($merchantPaymentGateways->count() == 0) { ?>
                        <p style="color: red">Your account is not activated yet. One of our team member will get in touch with you for yout KYC and get your account activated.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="profile-data">
            
            <?php if ($this->request->getSession()->read('Auth.User.id') == $merchantDetails->id) { ?>
                <h2>Website & Social Info <a href="<?= HTTP_ROOT . "merchants/account-setup"; ?>#WebsiteAndSocial" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <?php } else { ?>
                <h2>Website & Social Info <a href="<?= HTTP_ROOT . "admin/edit-merchant/" . $merchantDetails->uniq_id; ?>#WebsiteAndSocial" class="btn-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></h2>
            <?php } ?>
                
            <div class="pro-d-left">Website </div>
            <div class="pro-d-right"><?= $merchantDetails->merchant->website; ?></div>
            <div class="pro-d-left">Facebook Url</div>
            <div class="pro-d-right"><?= $merchantDetails->merchant->facebook_url; ?></div>
            <div class="pro-d-left">Twitter Url</div>
            <div class="pro-d-right"><?= $merchantDetails->merchant->twitter_url; ?></div>
        </div>   

    </div>
</div>