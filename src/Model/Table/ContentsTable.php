<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
// use Cake\ORM\Rule\IsUnique;

class ContentsTable extends Table
{
    
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('contents');
        $this->setDisplayField('content_title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Specs', [
            'foreignKey' => 'content_id',
			'dependent' => true,
			'cascadeCallbacks' => true
        ]);
        
		$this->addBehavior('Log');
    }

    
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->add('content_src', [
                'unique' => [
                    'message'   => __('content_already_exist'),
                    'provider'  => 'table',
                    'rule'      => ['validateUnique', ['scope' => 'content_src']],
                    'on'=>'create'
                ],
            ]);
        
        // $validator
        //     ->add('unique' => [
        //         'rule' => ['unique', 'content_src'],
        //         'message' => __('content_already_exist'),
        //         'provider'  => 'table',
        //         'on' => 'create',
        //     ]);
            
        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
