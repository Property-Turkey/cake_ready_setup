<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Content extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'language_id' => true,
        'content_title' => true,
        'content_desc' => true,
        'content_type' => true,
        'content_istranslated' => true,
        'content_src' => true,
        'features_ids' => true,
        'seo_keywords' => true,
        'stat_created' => true,
        'stat_updated' => true,
        'stat_views' => true,
        'stat_shares' => true,
        'rec_state' => true,
        'user' => true,
        'specs' => true,
    ];
}
