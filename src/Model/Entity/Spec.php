<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Spec extends Entity
{
    
    protected $_accessible = [
        'language_id' => true,
        'content_id' => true,
        'spec_name' => true,
        'spec_value' => true,
        'spec_configs' => [
            'type'=>true,
            'currency'=>true,
            'alt'=>true,
        ],
        'content' => true,
    ];
}
