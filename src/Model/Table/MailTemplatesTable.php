<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MailTemplatesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
    }

    public function generateWebfrontTemplates($webfrontID) {

        $webfrontTemplates = ['PAYMENT_NOTIFICATION', 'PAYMENT_FAILURE', 'PAYMENT_CONFIRMATION'];

        $exitsingTemplates = $this->find('list', ['keyField' => 'name'])->where(['webfront_id' => $webfrontID]);

        $defaultTemplates = $this->find()->where(['webfront_id' => 0, 'name IN' => $webfrontTemplates, 'name NOT IN' => $exitsingTemplates]);

        foreach ($defaultTemplates as $template) {
            $templateData = $template->toArray();
            unset($templateData['id'], $templateData['unique_id'], $templateData['created']);
            $newEmailTemplate = $this->newEntity($templateData);
            $newEmailTemplate->webfront_id = $webfrontID;
            $newEmailTemplate->unique_id = generateUniqId();
            $this->save($newEmailTemplate);
        }
    }
    
    public function getTemplate($name, $webfrontID = 0) {

        $query = $this->find()->where(['webfront_id' => $webfrontID, 'name' => $name, 'is_active' => 1]);
        if ($query->count()) {
            return $query->first();
        }

        $query = $this->find()->where(['webfront_id' => 0, 'name' => $name, 'is_active' => 1]);
        if ($query->count()) {
            return $query->first();
        }

        return NULL;
    }

}
