<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SpecsTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('specs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Contents', [
            'foreignKey' => 'content_id',
            'joinType' => 'INNER',
        ]);
    }
    
    public function validationDefault(Validator $validator): Validator
    {
        // $validator
        //     ->notEmptyString('language_id');

        // $validator
        //     ->integer('content_id')
        //     ->notEmptyString('content_id');

        // $validator
        //     ->scalar('spec_name')
        //     ->maxLength('spec_name', 255)
        //     ->requirePresence('spec_name', 'create')
        //     ->notEmptyString('spec_name');

        // $validator
        //     ->scalar('spec_value')
        //     ->maxLength('spec_value', 255)
        //     ->requirePresence('spec_value', 'create')
        //     ->notEmptyString('spec_value');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // $rules->add($rules->existsIn('content_id', 'Contents'), ['errorField' => 'content_id']);

        return $rules;
    }
}
