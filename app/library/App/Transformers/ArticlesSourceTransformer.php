<?php
declare(strict_types=1);

namespace App\Transformers;

use App\Model\ArticlesSource;
use League\Fractal\Resource\Item;
use PhalconRest\Transformers\ModelTransformer;

/**
 * Class ArticlesSourceTransformer
 * @package App\Transformers
 */
class ArticlesSourceTransformer extends ModelTransformer
{
    /**
     * Transforms are automatically handled
     * based on your model when you extend ModelTransformer
     * and assign the modelClass property
     */


    public function __construct()
    {
        $this->modelClass = ArticlesSource::class;

        $this->availableIncludes = [
            'Language'
        ];
    }

    /**
     * @param ArticlesSource $model
     * @return Item
     */
    public function includeLanguage(ArticlesSource $model): Item
    {
        return $this->item($model->getLanguage(), new LanguagesTransformer());
    }
}
