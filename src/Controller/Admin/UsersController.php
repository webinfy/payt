<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

class UsersController extends AdminController {

    public function initialize() {
        parent::initialize();

        // Load Models
        $this->loadModel('Users');
        $this->loadModel('AdminSettings');
        $this->loadModel('MailTemplates');  
       
        // Set Layout
        $this->viewBuilder()->setLayout('admin');

        // Auth Allow
        $this->Auth->allow(['login', 'dashboard']);
    }

    public function login() {
        $this->viewBuilder()->setLayout('');
        $this->render('/Users/login');
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  16th Oct 2018
     * Description :  Admin Dashboard
     */

    public function dashboard() {
        if (!$this->Auth->user('id')) {
            return $this->redirect(HTTP_ROOT . "login");
        }

        $pageHeading = "Dashboard";
        $this->set(compact('pageHeading'));
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  16th Oct 2018
     * Description :  Update Admin Account Details    
     */

    public function accountSetup() {
        $pageHeading = "Account Setup";

        $user = $this->Users->get($this->Auth->user('id'));

        if ($this->request->is('put', 'post')) {

            $data = $this->request->getData();
            $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Profile Updated Successfully'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Profile Updation Failed'));
            }
        }

        $this->set(compact(['user', 'pageHeading']));
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  16th Oct 2018
     * Description :  Change Password by admin.    
     */

    public function changePassword() {
        $pageHeading = "Change Password";

        $user = $this->Users->get($this->Auth->user('id'));

        if ($this->request->is('post', 'put')) {
            $data = $this->request->getData();

            $this->Users->patchEntity($user, ['old_password' => $data['old_password'], 'password' => $data['password1'], 'password1' => $data['password1'], 'password2' => $data['password2']], ['validate' => 'password']);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Password changed successfully!!'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Current password is not correct!!'));
            }
        }
        $this->set(compact('pageHeading'));
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  16th Oct 2018
     * Description :  Update application setting like site name, admin email, bcc email etc. 
     */

    public function adminSettings() {

        $pageHeading = "Application Setting";

        $adminSetting = $this->AdminSettings->find()->first();

        if ($this->request->is('put', 'post')) {

            $data = $this->request->getData();

            $this->AdminSettings->patchEntity($adminSetting, $data);

            if ($this->AdminSettings->save($adminSetting)) {

                $content = "<?php\n";
                foreach ($data as $key => $value) {
                    $content .= 'define("' . strtoupper($key) . '", "' . $value . '");' . "\n";
                }

                file_put_contents('../config/constants.php', $content);

                $this->Flash->success(__('Settings Updated Successfully'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Settings Updation Failed'));
            }
        }

        $this->set(compact(['adminSetting', 'pageHeading']));
    }

    public function viewEmailTemplates() {

        $templates = $this->MailTemplates->find()->where(['webfront_id' => 0]);

        $pageHeading = 'Email Templates';
        $this->set(compact(['pageHeading', 'templates']));
    }

    public function updateTemplateStatus($id) {

        $template = $this->MailTemplates->get($id);

        $update = $this->MailTemplates->query()->update();
        if ($template->is_active == 1) {
            $update->set(['is_active' => 0])->where(['id' => $id])->execute();
            return $this->redirect($this->referer());
        } else {
            $update->set(['is_active' => 1])->where(['id' => $id])->execute();
            return $this->redirect($this->referer());
        }
    }

    public function editTemplate($uniqueID) {

        $template = $this->MailTemplates->find()->where(['unique_id' => $uniqueID])->first();

        if ($this->request->is('put')) {

            $data = $this->request->getData();

            $this->MailTemplates->patchEntity($template, $data);
            if ($this->MailTemplates->save($template)) {
                $this->Flash->success(__('Template Updated Successfully'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('Template Updation Failed!!'));
        }

        $pageHeading = 'Edit Template';
        $this->set(compact(['pageHeading', 'template']));
    }

}
