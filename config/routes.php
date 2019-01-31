<?php

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Users', 'action' => 'home']); 
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
    $routes->connect('/forgot-password', ['controller' => 'Users', 'action' => 'forgotPassword']);
    $routes->connect('/forgot-password-verify-otp/*', ['controller' => 'Users', 'action' => 'forgotPasswordVerifyOtp']);
    $routes->connect('/reset-password/*', ['controller' => 'Users', 'action' => 'resetPassword']);
    $routes->connect('/signup', ['controller' => 'Users', 'action' => 'signup']);
    $routes->connect('/activate-account/*', ['controller' => 'Users', 'action' => 'activateAccount']);
    $routes->connect('/signup-success/*', ['controller' => 'Users', 'action' => 'signupSuccess']);
    $routes->connect('/congratulation/*', ['controller' => 'Users', 'action' => 'congratulation']);
            
    $routes->connect('/merchants', ['controller' => 'Merchants', 'action' => 'index']);
    $routes->connect('/basic-webfronts', ['controller' => 'Merchants', 'action' => 'basicWebfronts']);
    $routes->connect('/download-sample-excel/*', ['controller' => 'Merchants', 'action' => 'downloadSampleExcel']);
    $routes->connect('/advance-webfronts', ['controller' => 'Merchants', 'action' => 'advanceWebfronts']);
    $routes->connect('/resend-invoice-email/*', ['controller' => 'Merchants', 'action' => 'resendEmail']);
    $routes->connect('/delete-invoice/*', ['controller' => 'Merchants', 'action' => 'deletePayment']); 
    
    $routes->connect('/webfronts', ['controller' => 'Webfronts', 'action' => 'index']);
    $routes->connect('/webfronts/:profile_url', ['controller' => 'Webfronts', 'action' => 'index'], ['pass' => ['profile_url']]);    
    $routes->connect('/webfront/:url', ['controller' => 'Webfronts', 'action' => 'viewWebfront'], ['pass' => ['url']]);
    $routes->connect('/advance-webfront-reports/', ['controller' => 'Webfronts', 'action' => 'advanceWebfrontReports']);
    $routes->connect('/basic-webfront-reports/', ['controller' => 'Webfronts', 'action' => 'basicWebfrontReports']);
    $routes->connect('/view-transactions/*', ['controller' => 'Webfronts', 'action' => 'viewTransactions']);

    $routes->connect('/preview-invoice/:uniq_id', ['controller' => 'Invoices', 'action' => 'previewInvoice'], ['pass' => ['uniq_id']]);
    $routes->connect('/payuResponse/*', ['controller' => 'Invoices', 'action' => 'payuResponse']);
    $routes->connect('/razorPayResponse/*', ['controller' => 'Invoices', 'action' => 'razorPayResponse']);  
    
    $routes->connect('/add-new-user/', ['controller' => 'Users', 'action' => 'addNewUser']);
    $routes->connect('/view-profile', ['controller' => 'Users', 'action' => 'viewProfile']);
    $routes->connect('/edit-profile', ['controller' => 'Users', 'action' => 'editProfile']);
    $routes->connect('/update-profile-pic', ['controller' => 'Users', 'action' => 'updateProfilePic']);
    $routes->connect('/change-password', ['controller' => 'Users', 'action' => 'changePassword']);    
    
    
    $routes->connect('/:url', ['controller' => 'Webfronts', 'action' => 'viewWebfront'], ['pass' => ['url']]);
    
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('admin', function ($routes) {
    $routes->connect('/', ['controller' => 'Users', 'action' => 'dashboard']);
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
    $routes->connect('/account-setup', ['controller' => 'Users', 'action' => 'accountSetup']);
    $routes->connect('/payment-success-ratio', ['controller' => 'Users', 'action' => 'paymentSuccessRatio']);
    $routes->connect('/modes-of-payment', ['controller' => 'Users', 'action' => 'modesOfPayment']);
    $routes->connect('/date-wise-status', ['controller' => 'Users', 'action' => 'dateWiseStatus']);
    $routes->connect('/barchart', ['controller' => 'Users', 'action' => 'barchart']);
    $routes->connect('/admin-settings', ['controller' => 'Users', 'action' => 'adminSettings']);
    $routes->connect('/change-password', ['controller' => 'Users', 'action' => 'changePassword']);

    $routes->connect('/merchant-listing', ['controller' => 'Merchants', 'action' => 'merchantListing']);
    $routes->connect('/update-status/*', ['controller' => 'Merchants', 'action' => 'updateStatus']);
    $routes->connect('/view-merchant-profile/*', ['controller' => 'Merchants', 'action' => 'viewMerchantProfile']);
    $routes->connect('/edit-merchant/*', ['controller' => 'Merchants', 'action' => 'editMerchant']);
    $routes->connect('/delete-merchant/*', ['controller' => 'Merchants', 'action' => 'deleteMerchant']);
    $routes->connect('/resend-email/*', ['controller' => 'Merchants', 'action' => 'resendEmail']);
    
    $routes->connect('/view-email-templates', ['controller' => 'Users', 'action' => 'viewEmailTemplates']);
    $routes->connect('/edit-template/*', ['controller' => 'Users', 'action' => 'editTemplate']);
    $routes->connect('/update-template-status/*', ['controller' => 'Users', 'action' => 'updateTemplateStatus']);
        
    $routes->connect('/view-submerchants', ['controller' => 'SubMerchants', 'action' => 'index']);
    $routes->connect('/add-submerchant', ['controller' => 'SubMerchants', 'action' => 'add']);

    $routes->connect('/payments/*', ['controller' => 'Webfronts', 'action' => 'viewPayments']);

    $routes->fallbacks('DashedRoute');
});
