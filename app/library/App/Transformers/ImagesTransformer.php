<?php
declare(strict_types=1);

namespace App\Transformers;

use App\Model\Images;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use PhalconRest\Transformers\ModelTransformer;

/**
 * Class ImagesTransformer
 * @package App\Transformers
 */
class ImagesTransformer extends ModelTransformer
{
    /**
     * Transforms are automatically handled
     * based on your model when you extend ModelTransformer
     * and assign the modelClass property
     */


    public function __construct()
    {
        $this->modelClass = Images::class;

        $this->availableIncludes = [
            'ArticleImages',
            'Users',
            'Articles'
        ];
    }

    /**
     * @param Images $model
     * @return Collection
     */
    public function includeArticleImages(Images $model): Collection
    {
        return $this->collection($model->getArticleImages(), new ArticleImagesTransformer());
    }

    /**
     * @param Images $model
     * @return Collection
     */
    public function includeUsers(Images $model): Collection
    {
        return $this->collection($model->getUsers(), new UsersTransformer());
    }

    /**
     * @param Images $model
     * @return Collection
     */
    public function includeArticles(Images $model): Collection
    {
        return $this->collection($model->getArticles(), new ArticlesTransformer());
    }
}
