<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validation;
use Cake\Http\Exception\NotFoundException;
use Cake\Datasource\ConnectionManager;

class UsersController extends AppController {

    public function initialize() {
        parent::initialize();

        // Load Models
        $this->loadModel('Users');
        $this->loadModel('Merchants');
        $this->loadModel('ForgotPasswordOtps');
        $this->loadModel('AdminSettings');
        $this->loadModel('MailTemplates');
        $this->loadModel('Webfronts');
        $this->loadModel('Employees');

        // Load Components
        $this->loadComponent('Custom');
        $this->loadComponent('SendEmail');
        $this->loadComponent('Paginator');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $authAllow = ['login', 'logout', 'home', 'ajaxCheckLogin', 'forgotPassword', 'forgotPasswordVerifyOtp', 'resendOtp', 'resetPassword', 'signup', 'ajaxCheckEmailAvail', 'activateAccount', 'signupSuccess', 'congratulation', 'contactUs'];
        $this->Auth->allow($authAllow);
    }

    public function home() {
        $this->viewBuilder()->setLayout('');
    }

    public function login() {       
        $this->viewBuilder()->setLayout('');
        
        // Redirect if Alreday Login
        if ($this->userId) {
            $this->_loginRedirect();
        }
        
        if ($this->request->is('post')) {
            
            $user = $this->Auth->identify();
            
            if (empty($user)) {
                $this->Flash->error(__('Invalid Email or Password, try again.'));               
            } else if ($user['is_active'] == '0') {
                $this->Flash->error(__('You Account not activated yet. Please contact admin.'));                
            } else {
                // If user is an merchant then update his users table merchant_id with his own id if not updated yet.
                if ($user['type'] == 2 && !$user['merchant_id']) {
                    $this->Users->query()->update()->set(['merchant_id' => $user['id']])->where(['id' => $user['id']])->execute();
                    $user['merchant_id'] = $user['id'];
                }
                
                // Store User data into Session
                $this->Auth->setUser($user);
                
                // Update User Login Logs
                $this->Users->UserLogins->updateUserLoginLog($this->Auth->user('id'));
                
                // Login and Redirect to appropriate user dashboard
                $this->_loginRedirect();
            }
            return $this->redirect($this->referer());
        }
        
        $pageTitle = SITE_NAME . " : Login";
        $this->set(compact('pageTitle'));
    }

    // Redirect to default landing page based on user type.
    private function _loginRedirect() {
        if ($this->Auth->user('type') == 1) {
            return $this->redirect(HTTP_ROOT . 'admin'); // Admin Dashboard
        } else if ($this->Auth->user('type') == 2 || $this->Auth->user('type') == 3) {
            return $this->redirect(HTTP_ROOT . 'merchants'); // Merchant Dashboard
        } else if ($this->Auth->user('type') == 4) {
            return $this->redirect(HTTP_ROOT . 'customers'); // Customer Dashboard
        } else {
            return $this->redirect(HTTP_ROOT);
        }
    }

    public function logout() {
        if (isset($_COOKIE['rememberme'])) {
            setcookie("rememberme", $_COOKIE['rememberme'], time() - (86400 * 30), "/"); //Removing cookie
        }
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        $this->Auth->logout();
        $this->redirect(HTTP_ROOT . 'login');
    }

    public function forgotPassword() {
        $this->viewBuilder()->setLayout('');

        $pageTitle = SITE_NAME . ": Forgot Password";
        $this->set(compact('pageTitle'));

        if ($this->request->is('post')) {
            
            $phone = trim($this->request->getData('phone'));
            $query = $this->Users->find()->where(['OR' => [['Users.phone' => $phone], ['Users.email' => $phone]]]);
            
            if ($query->count() <= 0) {
                $this->Flash->error(__("Phone/Email does't exist!!."));
            } else {
                
                $userDetails = $query->first();
                $otp = $this->ForgotPasswordOtps->newEntity();
                $otp->uniqid = $this->Custom->generateUniqId();
                $otp->user_id = $userDetails->id;
                $otp->otp = $this->Custom->generateOTP();
                $otp->created = time();
                
                if ($this->ForgotPasswordOtps->save($otp)) {
                    $this->_sendOtp($otp->uniqid);
                    $this->Flash->success(__('OTP sent successfully. Please check your phone.'));
                    return $this->redirect(HTTP_ROOT . "forgot-password-verify-otp/{$otp->uniqid}");
                } else {
                    $this->Flash->error(__('Some error occured.Please try again!!'));                    
                }
            }
            
            return $this->redirect($this->referer());
        }
    }

    private function _sendOtp($uniqid) {
        $forgotPasswordOtp = $this->ForgotPasswordOtps->find()->where(['ForgotPasswordOtps.uniqid' => $uniqid])->contain(['Users'])->first();

        // Send OTP to user mobile 
        if (!empty($forgotPasswordOtp->user->phone)) {
            $phone = $forgotPasswordOtp->user->phone;
            $sms = "Your otp is {$forgotPasswordOtp->otp}.";
            if (sendSMS($phone, $sms)) {
                return TRUE;
            }
        }
        pj($forgotPasswordOtp);exit;
        // Send Email If SMS not sent.
        $this->Users->sendEmail($forgotPasswordOtp->user->id, 'FORGOT_PASSWORD_OTP', ['otp' => $forgotPasswordOtp->otp]);

        return TRUE;
    }

    public function resendOtp($uniqid) {
        $this->_sendOtp($uniqid);
        $this->Flash->success(__('OTP Resent Successfully.'));
        return $this->redirect($this->referer());
    }

    public function forgotPasswordVerifyOtp($uniqid = NULL) {
        $this->viewBuilder()->setLayout('');
        $pageTitle = "Forgot Password Verify OTP : " . SITE_NAME;
        $this->set(compact('pageTitle'));

        $forgotPasswordOtp = $this->ForgotPasswordOtps->find()->where(['ForgotPasswordOtps.uniqid' => $uniqid])->contain(['Users'])->first();

        if ($this->request->is('post')) {

            $otp = $this->request->getData('otp');

            if ($forgotPasswordOtp->otp == $otp) {

                $qstr = generateUniqId();
                $this->Users->query()->update()->set(["qstr" => $qstr])->where(['id' => $forgotPasswordOtp->user->id])->execute();

                // $this->_forgotPasswordMail($forgotPasswordOtp->user->id);  
                // Delete OTP Entry from DB
                $this->ForgotPasswordOtps->delete($forgotPasswordOtp);

                return $this->redirect(HTTP_ROOT . "reset-password/" . $forgotPasswordOtp->user->uniq_id . "/" . $qstr);
            } else {
                $this->Flash->error(__('Invalid OTP!!'));
            }
            return $this->redirect($this->referer());
        }
    }

    private function _forgotPasswordMail($userId) {
        $userDetails = $this->Users->find()->where(['Users.id' => $userId])->first();
        $this->Users->sendEmail($userDetails->id, 'FORGOT_PASSWORD_MAIL');
    }

    public function resetPassword($uniq_id = NULL, $qstr = NULL) {

        $this->viewBuilder()->setLayout('');
        $pageTitle = SITE_NAME . ": Reset Password";
        $this->set(compact('pageTitle'));

        $query = $this->Users->find()->where(['Users.uniq_id' => $uniq_id, 'Users.qstr' => $qstr]);
        if ($query->count() <= 0) {
            $this->Flash->error(__('Invalid Link!!'));
            return $this->redirect(HTTP_ROOT);
        }

        if ($this->request->is('post')) {
            
            $data = $this->request->getData();
            
            $password = $data['password'];
            $confPassword = $data['conf_password'];
            
            if (strlen($password) <= 8) {
                $this->Flash->error(__('Password length should not be less than 8 character!!'));                
            } else if ($password != $confPassword) {
                $this->Flash->error(__("Password & Confrim password does't matches!!"));                
            } else {  
                
                // Update Password & Login User
                $user = $query->first();
                $user->password = $password;
                $user->qstr = ''; 
                
                if ($this->Users->save($user)) {
                    $this->Auth->setUser($user->toArray());                     
                    $this->_loginRedirect(); // Login and Redirect to appropriate user dashboard
                }
            }
            return $this->redirect($this->referer());
        }
    }

//Forgot password section ends here by prakash

    public function ajaxCheckLogin() {
        $this->viewBuilder()->setLayout('ajax');
        if ($this->Auth->user('id')) {
            echo json_encode(['status' => 'loggedin', 'user' => $this->Auth->user()]);
        } else {
            echo json_encode(['status' => 'loggedout']);
        }
        exit;
    }

    public function ajaxChangePasssword() {
        $data = $this->request->getData();
        if (empty($data['old_password'])) {
            echo json_encode(['status' => 'error', 'msg' => 'Please enter current password.']);
        } else if (empty($data['password1'])) {
            echo json_encode(['status' => 'error', 'msg' => 'Please enter new password']);
        } else if (strlen($data['password1']) < 6) {
            echo json_encode(['status' => 'error', 'msg' => 'Password length must be greated that 5 character.']);
        } else if ($data['password1'] != $data['password2']) {
            echo json_encode(['status' => 'error', 'msg' => 'Password & Retype Password does\'t  match.']);
        } else {
            if (!empty($this->Auth->user('submerchant.id'))) {
                $user = $this->Users->get($this->Auth->user('submerchant.id'));
            } else {
                $user = $this->Users->get($this->Auth->user('id'));
            }
            $user = $this->Users->patchEntity($user, ['old_password' => $data['old_password'], 'password' => $data['password1'], 'password1' => $data['password1'], 'password2' => $data['password2']], ['validate' => 'password']);
            if ($this->Users->save($user)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Current password is not correct.']);
            }
        }
        exit;
    }

    /*
     * Dev      : Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created  : 24th Oct 2018
     * Desc     : Merchant Signup      
     */

    public function signup() {
        if ($this->userId) {
            $this->_loginRedirect();
        }

        $this->viewBuilder()->setLayout('');

        $userEntity = $this->Users->newEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
           
            $data['type'] = 2;
            $data['is_active'] = 1;
            $data['uniq_id'] = $this->Custom->generateUniqId();
            
            $this->Users->patchEntity($userEntity, $data, ['associated' => ['Merchants']]);
           
            if ($this->Users->save($userEntity)) {
                
                // Assign an profile url to merchant
                $this->Users->genProfileUrl($userEntity);

                // Send Email to Merchant
                $this->Users->sendEmail($userEntity->id, 'MERCHANT_SIGNUP_EMAIL');

                // Send Email to Admin
                $this->Users->sendEmail($userEntity->id, 'MERCHANT_SIGNUP_EMAIL_ADMIN');

                // Redirect to signup success page
                return $this->redirect(HTTP_ROOT . "signup-success/{$userEntity->uniq_id}");
            } else {
                $this->Flash->error(__('Error Occured!!'));
            }
        }

        $pageTitle = SITE_NAME . " : Merchant Signup";
        $this->set(compact('pageTitle', 'userEntity'));
    }

    /*
     * Developer   : Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     : 26th Sep 2018
     * Description : Check if email available or not.     
     * @param email    
     */

    public function ajaxCheckEmailAvail() {
        $data = $_REQUEST;
        $email = trim(urldecode($data['email']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(false);
        } else {
            $query = $this->Users->find('all')->where(['Users.email' => $email]);
            if ($query->count()) {
                echo json_encode(false);
            } else {
                echo json_encode(true);
            }
        }
        exit;
    }

    /*
     * Developer   : Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     : 26th Sep 2018
     * Description : Activate User Account.
     * @param unique_id    
     */

    public function activateAccount($uniqueID) {
        $query = $this->Users->find()->where(['Users.uniq_id' => $uniqueID]);
        if ($query->count()) {
            $user = $query->first();

            $this->Users->query()->update()->set(['is_active' => 1])->where(['uniq_id' => $uniqueID])->execute();

            // Send Account Activateion Email
            $this->Users->sendEmail($user->id, 'MERCHANT_ACCOUNT_ACTIVATED');

            // Display Success Message & Redirect to login page.
            $this->Flash->success(__('Account Activated Successfully!!'));
            return $this->redirect(HTTP_ROOT . 'congratulation');
        } else {
            $this->Flash->error(__('Invalid Link!!'));
        }
    }

    /*
     * Developer   : Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     : 24th Oct 2018
     * Description : Merchant Signup Success Page.
     * @param $uniqueID    
     */

    public function signupSuccess($uniqueID = NULL) {
        $this->viewBuilder()->setLayout('');
        $user = $this->Users->find()->where(['Users.uniq_id' => $uniqueID])->first();

        // Resend Activation Email
        if (isset($_REQUEST['resendemail'])) {
            $this->Users->sendEmail($user->id, 'MERCHANT_SIGNUP_EMAIL');
            $this->Flash->success(__('Activation mail sent successfully.!!'));
            return $this->redirect(HTTP_ROOT . "signup-success/" . $user->uniq_id);
        }

        $pageTitle = SITE_NAME . " : Merchant Signup Success";
        $this->set(compact('pageTitle', 'user'));
    }

    public function congratulation() {
        $this->viewBuilder()->setLayout('');
    }

    public function checkNameAvail() {
        $name = $_REQUEST['name'];

        $query = $this->Users->find('all')->where(['name' => $name]);

        if ($query->count()) {
            echo json_encode(false);
        } else {
            echo json_encode(true);
        }
        exit;
    }

    public function checkEmailAvail() {
        $data = $_REQUEST;

        $email = trim(urldecode($data['email']));

        if (!empty($data['id'])) {
            $query = $this->Users->find('all')->where(['email' => $email, 'id !=' => $data['id']]);
        } else {
            $query = $this->Users->find('all')->where(['email' => $email]);
        }

        if ($query->count()) {
            echo json_encode(false);
        } else {
            echo json_encode(true);
        }
        exit;
    }

    public function addNewUser() {
        $this->viewBuilder()->setLayout('default');
        $userEntity = $this->Users->newEntity();
        
        if ($this->request->is(['post', 'patch', 'put'])) {
            $data = $this->request->getData();

            $data['type'] = 3;
            $data['is_active'] = 1;
            $data['uniq_id'] = $this->Custom->generateUniqId();
            $data['merchant_id'] = $this->Auth->user('id');

            $this->Users->patchEntity($userEntity, $data, ['associated' => 'Employees']);

            if ($this->Users->save($userEntity)) {
                $this->Users->sendEmail($userEntity->id, 'EMPLOYEE_SIGNUP_EMAIL', $data);
                $this->Flash->success(__('User Added successfully!!'));
                return $this->redirect(HTTP_ROOT . "users/view-all-user");
            } else {
                $this->Flash->error(__('Error Occured!!'));
            }
        }

        $pageHeading = "Add New User";
        $this->set(compact('pageHeading'));
    }

    public function viewAllUser() {
        $this->viewBuilder()->setLayout('default');

        $users = $this->Users->find()->where(['Users.id !=' => $this->Auth->User('id'), 'Users.merchant_id' => $this->Auth->User('id')])->contain(['Employees']);

        $pageHeading = "User Listing";
        $this->set(compact(['pageHeading', 'users']));
    }

    public function editUser($uniqID = NULL) {
        $this->viewBuilder()->setLayout('default');

        $userEntity = $this->Users->find()->where(['uniq_id' => $uniqID])->contain(['Employees'])->first();

        if ($this->request->is(['patch', 'put'])) {
            $data = $this->request->getData();

            $this->Users->patchEntity($userEntity, $data, ['associated' => 'Employees']);

            if ($this->Users->save($userEntity)) {
                $this->Flash->success(__('User data updated successfully!!'));
                return $this->redirect(HTTP_ROOT . "users/view-all-user");
            }
        }

        $pageHeading = "Edit User";
        $this->set(compact(['pageHeading', 'userEntity']));
    }

    public function activate($uniqID = NULL) {
        $status = $this->Users->query()->update()->set(['is_active' => 1])->where(['uniq_id' => $uniqID])->execute();
        if ($status) {
            $users = $this->Users->find()->where(['Users.id !=' => $this->Auth->User('id'), 'Users.merchant_id' => $this->Auth->User('id')]);
            $this->Flash->Success(__('User Activated Successfully!!'));
            $this->redirect($this->referer());
        }
    }

    public function inactivate($uniqID = NULL) {
        $status = $this->Users->query()->update()->set(['is_active' => 0])->where(['uniq_id' => $uniqID])->execute();
        if ($status) {
            $users = $this->Users->find()->where(['Users.id !=' => $this->Auth->User('id'), 'Users.merchant_id' => $this->Auth->User('id')]);
            $this->Flash->Success(__('User Inactivated Successfully!!'));
            $this->redirect($this->referer());
        }
    }

    public function deleteUser($uniqID = NULL) {

        $query = $this->Users->find()->where(['uniq_id' => $uniqID]);

        if (!$query->count()) {
            $this->Flash->error(__("User Doesn't Exist!!"));
            return $this->redirect($this->referer());
        }

        if ($this->Users->delete($query->first())) {
            $this->Flash->Success(__("User Deleted Successfully!!"));
        } else {
            $this->Flash->error(__("User couldn't be deleted!!"));
        }
        return $this->redirect($this->referer());
    }

    public function viewProfile() {
        $pageHeading = "View Profile";
        $user = $this->Users->find()->where(['Users.id' => $this->userId])->contain(['Employees' => ['fields' => ['phone' => 'Employees.phone']]])->first();
        $this->set(compact('pageHeading', 'user'));
    }

    public function editProfile() {
        $pageHeading = "Edit Profile";
        $user = $this->Users->find()->where(['Users.id' => $this->userId])->contain(['Employees'])->first();

        if ($this->request->is(['patch', 'put'])) {
            $data = $this->request->getData();

            $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Profile Updated Successfully!!'));
                return $this->redirect(HTTP_ROOT . "view-profile");
            }
        }

        $this->set(compact('pageHeading', 'user'));
    }

    public function changePassword() {

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $userEntity = $this->Users->get($this->Auth->user('id'));
            $userFields['fields'] = [['old_password', 'password1', 'password2'], 'validate' => 'password'];

            $this->Users->patchEntity($userEntity, ['old_password' => $data['old_password'], 'password' => $data['password1'], 'password1' => $data['password1'], 'password2' => $data['password2']], ['validate' => 'password']);

            if ($this->Users->save($userEntity)) {
                $this->Flash->success(__('Password changed successfully!!'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Current password is not correct!!'));
            }
        }

        $pageHeading = "Change Password";
        $this->set(compact('pageHeading'));
    }

    public function updateProfilePic() {

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            list(, $imgdata) = explode(';', $data['response']);
            list(, $newimgdata) = explode(',', $imgdata);
            $decodedimgdata = base64_decode($newimgdata);
            $profilePic = md5(time() . mt_rand(1111, 9999)) . ".png";

            if (file_put_contents(MERCHANT_LOGO . $profilePic, $decodedimgdata)) {

                $update = $this->Employees->query()->update()->set(['profile_pic' => $profilePic])->where(['user_id' => $this->userId])->execute();

                if ($update) {
                    //file_exists(MERCHANT_LOGO . $oldPhoto) && unlink(MERCHANT_LOGO . $oldPhoto);
                    $this->Flash->success(__('Profile Pic Updated Successfully'));
                    return $this->redirect($this->referer());
                }
            }

            $this->Flash->error(__('Profile Pic Uploading Failed'));
        }

        $pageHeading = "Update Profile Picture";
        $this->set(compact('pageHeading'));
    }

    public function contactUs() {
        $this->loadModel('MailTemplates');
        $this->loadModel('AdminSettings');

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $name = $data['name'];
            $email = filter_var($data['email']);
            $number = $data['number'];
            $subject = $data['subject'];
            $message = $data['message'];

            $mailTemplate = $this->MailTemplates->find()->where(['name' => 'CONTACT_US', 'is_active' => 1])->first();
            $adminSetting = $this->AdminSettings->find()->where(['id' => 1])->first();

            $subject = $mailTemplate->subject;
            $message = $mailTemplate->content;
            $message = str_replace("[NAME]", $data['name'], $message);
            $message = str_replace("[EMAIL]", $data['email'], $message);
            $message = str_replace("[NUMBER]", $data['number'], $message);
            $message = str_replace("[MESSAGE]", $data['message'], $message);
            $message = str_replace("[SUBJECT]", $data['subject'], $message);
            $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT . "'>Team PayTring</a>", $message);

            $this->SendEmail->sendEmail($adminSetting->admin_email, $mailTemplate->subject, $message);
            echo "Email Sent Successfully!!";
        }
        exit;
    }

}
