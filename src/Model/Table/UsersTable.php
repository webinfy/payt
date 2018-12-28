<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use App\Controller\Component\SendEmailComponent;
use App\Controller\Component\CustomComponent;
use Cake\ORM\TableRegistry;

class UsersTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Merchants', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
            'dependent' => TRUE
        ]);
        $this->hasOne('Employees', [
            'foreignKey' => 'user_id',            
            'joinType' => 'LEFT',
            'dependent' => TRUE
        ]);        
        $this->hasOne('MerchantPaymentGateways', [
            'className' => 'MerchantPaymentGateways',
            'foreignKey' => 'merchant_id',
            'conditions' => ['is_default' => 1],
            'joinType' => 'LEFT',
        ]);

        $this->hasMany('UserLogins', [
            'foreignKey' => 'user_id',
        ]);
    }
    
    public function beforeDelete($event, $entity, $options) {
        
        $merchant = TableRegistry::getTableLocator()->get('Merchants');
        $merchantEntity = $merchant->find()->where(['user_id' => $entity->id])->first();
//        $merchant->delete($merchantEntity);
        
    }

    public function validationDefault(Validator $validator) {

        $validator
                ->scalar('name')
                ->requirePresence('name', 'create', 'Contact name required!!')
                ->notEmpty('name', 'Contact name should not be empty!!')
                ->maxLength('name', 60, 'Contact name length should not exceed 60!!');
        $validator
                ->notEmpty('email', 'email required!!')
                ->add("email", "validEmail", [
                    "rule" => ["email"],
                    "message" => "Invalid email!!"
                ])
                ->maxLength('email', 60, 'email length should not exceed 60!!');

        $validator
                ->notEmpty('phone', 'Mobile number required!!')
                ->numeric('phone', 'Mobile number should be numeric!!')
                ->maxLength('phone', 15, 'Mobile number length should not exceed 15!!');

        $validator
                ->scalar('address')
                ->notEmpty('address', 'Address Name required!!')
                ->maxLength('address', 100, 'Address Name length should not exceed 256!!');

        $validator
                ->scalar('city')
                ->notEmpty('city', 'city Name required!!')
                ->maxLength('city', 100, 'city Name length should not exceed 256!!');

        $validator
                ->scalar('state')
                ->notEmpty('state', 'State Name required!!')
                ->maxLength('state', 100, 'State Name length should not exceed 256!!');

        $validator
                ->scalar('country')
                ->notEmpty('country', 'Country Name required!!')
                ->maxLength('country', 100, 'Country Name length should not exceed 256!!');

        $validator
                ->scalar('description')
                ->notEmpty('description', 'Description required!!')
                ->maxLength('description', 1000, 'description length should not exceed 1000!!');

        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email'], 'Email Already Exist!!'));
        $rules->add($rules->isUnique(['phone'], 'Phone Already Exist!!'));
        return $rules;
    }

    public function validationPassword(Validator $validator) {

        $validator
                ->add('old_password', 'custom', [
                    'rule' => function($value, $context) {
                        $user = $this->get($context['data']['id']);
                        if ($user) {
                            if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                                return true;
                            }
                        }
                        return false;
                    },
                    'message' => 'The old password does not match the current password!',
                ])
                ->notEmpty('old_password');

        $validator
                ->add('password1', [
                    'length' => [
                        'rule' => ['minLength', 6],
                        'message' => 'The password have to be at least 6 characters!',
                    ]
                ])
                ->add('password1', [
                    'match' => [
                        'rule' => ['compareWith', 'password2'],
                        'message' => 'The passwords does not match!',
                    ]
                ])
                ->notEmpty('password1');
        $validator
                ->add('password2', [
                    'length' => [
                        'rule' => ['minLength', 6],
                        'message' => 'The password have to be at least 6 characters!',
                    ]
                ])
                ->add('password2', [
                    'match' => [
                        'rule' => ['compareWith', 'password1'],
                        'message' => 'The passwords does not match!',
                    ]
                ])
                ->notEmpty('password2');

        return $validator;
    }

    public function sendEmail($userID, $templateName, $data = []) {

        $query = $this->find()->where(['Users.id' => $userID]);

        if ($query->count()) {

            $this->AdminSettings = TableRegistry::get('AdminSettings');
            $this->MailTemplates = TableRegistry::get('MailTemplates');
            $this->SendEmail = new SendEmailComponent();

            $adminSetting = $this->AdminSettings->find()->where(['id' => 1])->first();

            $mailTemplate = $this->MailTemplates->find()->where(['name' => $templateName, 'is_active' => 1])->first();
            $subject = $mailTemplate->subject;
            $message = $mailTemplate->content;

            switch ($templateName) {

                case 'MERCHANT_SIGNUP_EMAIL':

                    $user = $this->find()->where(['Users.id' => $userID])->contain(['Merchants'])->first();

                    $text_link = HTTP_ROOT . "activate-account/" . $user->uniq_id;
                    $link = "<a targrt='_blank' style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 5px 10px;text-align: center;width: 232px;text-decoration:none;' href='" . $text_link . "'>Click here to activate your account</a>";

                    $message = str_replace("[NAME]", ucwords($user->name), $message);
                    $message = str_replace("[EMAIL]", $user->email, $message);
                    $message = str_replace("[PHONE]", $user->phone, $message);
                    $message = str_replace("[LINK]", $link, $message);
                    $message = str_replace("[LINK_TEXT]", $text_link, $message);
                    $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT . "'>" . SITE_NAME . "</a>", $message);

                    $this->SendEmail->sendEmail($user->email, $mailTemplate->subject, $message);

                    break;

                case 'MERCHANT_SIGNUP_EMAIL_ADMIN':

                    $merchant = $this->find()->where(['Users.id' => $userID])->contain(['Merchants'])->first();

                    $message = str_replace("[NAME]", "Admin", $message);
                    $message = str_replace("[MERCHANT_NAME]", ucwords($merchant->name), $message);
                    $message = str_replace("[MERCHANT_EMAIL]", $merchant->email, $message);
                    $message = str_replace("[MERCHANT_PHONE]", $merchant->phone, $message);
                    $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT . "'>" . SITE_NAME . "</a>", $message);

                    $this->SendEmail->sendEmail($adminSetting->admin_email, $mailTemplate->subject, $message);

                    break;

                case 'MERCHANT_ACCOUNT_ACTIVATED':

                    $merchant = $this->find()->where(['Users.id' => $userID])->first();

                    $loginLink = "<a href='" . HTTP_ROOT . "login" . "' style='background:#F15D22;padding:5px 10px;color:#FFFFFF;text-decoration:none;border-radius:5px 5px 5px 5px;font-weight:bold;border:1px solid #F15D22;'>Login</a>";
                    $message = str_replace("[NAME]", ucwords($merchant->name), $message);
                    $message = str_replace("[LOGIN_LINK]", $loginLink, $message);
                    $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT . "'>" . SITE_NAME . "</a>", $message);

                    $this->SendEmail->sendEmail($merchant->email, $mailTemplate->subject, $message);

                    break;

                case 'FORGOT_PASSWORD':

                    $user = $this->find()->where(['Users.id' => $userID])->first();

                    $link = HTTP_ROOT . "reset-password/{$user->unique_id}/{$user->qstr}";
                    $linkBtn = "<a href='" . $link . "' style='background:#F15D22;padding:5px 10px;color:#FFFFFF;text-decoration:none;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;font-size:14px;font-weight:bold;border:1px solid #F15D22;margin-left:37%;'>Reset Password</a>";

                    $message = str_replace(array("[NAME]"), $user->name, $message);
                    $message = str_replace(array("[EMAIL]"), $user->email, $message);
                    $message = str_replace(array("[LINK]"), $link, $message);
                    $message = str_replace(array("[BTN_LINK]"), $linkBtn, $message);
                    $message = str_replace(array("[SITE_NAME]"), SITE_NAME, $message);

                    $this->SendEmail->sendEmail($user->email, $mailTemplate->subject, $message);
                    break;

                case 'FORGOT_PASSWORD_MAIL':

                    $user = $this->find()->where(['Users.id' => $userID])->first();

                    $text_link = HTTP_ROOT . "reset-password/" . $user->uniq_id . "/" . $user->qstr;
                    $link = "<a targrt='_blank' style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 5px 10px;text-align: center;width: 232px;text-decoration:none;' href='" . $text_link . "'>Click here to change your password</a>";

                    $message = str_replace(array("[NAME]"), $user->name, $message);
                    $message = str_replace(array("[EMAIL]"), $user->email, $message);
                    $message = str_replace(array("[LINK_TEXT]"), $text_link, $message);
                    $message = str_replace(array("[LINK]"), $link, $message);
                    $message = str_replace(array("[SITE_NAME]"), SITE_NAME, $message);

                    $this->SendEmail->sendEmail($user->email, $mailTemplate->subject, $message);
                    break;

                case 'FORGOT_PASSWORD_OTP':

                    $user = $this->find()->where(['Users.id' => $userID])->first();                    

                    $message = str_replace(array("[NAME]"), $user->name, $message);
                    $message = str_replace(array("[OTP]"), @$data['otp'], $message);
                    $message = str_replace(array("[SITE_NAME]"), SITE_NAME, $message);

                    $this->SendEmail->sendEmail($user->email, $mailTemplate->subject, $message);
                    break;

                case 'EMPLOYEE_SIGNUP_EMAIL':

                    $user = $this->find()->where(['Users.id' => $userID])->contain(['Employees'])->first();

                    $text_link = HTTP_ROOT . "login";
                    $link = "<a targrt='_blank' style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 5px 10px;text-align: center;width: 232px;text-decoration:none;' href='" . $text_link . "'>Click here to login your account</a>";

                    $message = str_replace("[NAME]", ucwords($user->name), $message);
                    $message = str_replace("[EMAIL]", $user->email, $message);
                    $message = str_replace("[PHONE]", $user->phone, $message);
                    $message = str_replace("[PASSWORD]", $data['password'], $message);
                    $message = str_replace("[LINK]", $link, $message);
                    $message = str_replace("[LINK_TEXT]", $text_link, $message);
                    $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT . "'>" . SITE_NAME . "</a>", $message);

                    $this->SendEmail->sendEmail($user->email, $mailTemplate->subject, $message);

                    break;

                default :
            }
        }
    }
    
    public function genProfileUrl($user) {
        try {
            $query = $this->Merchants->find()->where(['profile_url' => $user->merchant->profile_url, 'user_id !=' => $user->id]);
            if ($query->count()) {
                $profile_url = strtolower($user->name) . '-' . $user->id;
                $profile_url = preg_replace('/\s+/', '-', $profile_url);
                $this->Merchants->query()->update()->set(['profile_url' => $profile_url])->where(['user_id' => $user->id])->execute();
            }
        } catch (\Exception $ex) {
            
        }
    }

}
