<?php
declare(strict_types=1);

namespace App\Transformers;

use App\Model\Users;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use PhalconRest\Transformers\ModelTransformer;

/**
 * Class UsersTransformer
 * @package App\Transformers
 */
class UsersTransformer extends ModelTransformer
{
    /**
     * Transforms are automatically handled
     * based on your model when you extend ModelTransformer
     * and assign the modelClass property
     */


    public function __construct()
    {
        $this->modelClass = Users::class;

        $this->availableIncludes = [
            'Images',
            'Countries'
        ];
    }

    /**
     * @return array
     */
    protected function excludedProperties(): array
    {
        $excluded = parent::excludedProperties();
        return array_merge($excluded, ['password']);
    }


    /**
     * @param Users $model
     * @return Item
     */
    public function includeCountries(Users $model): Item
    {
        return $this->item($model->getCountries(), new CountriesTransformer());
    }

    /**
     * @param Users $model
     * @return Item
     */
    public function includeImages(Users $model): Item
    {
        return $this->item($model->getImages(), new ImagesTransformer());
    }
}
