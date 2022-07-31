<?php
declare(strict_types=1);

namespace App\Transformers;

use App\Model\Countries;
use League\Fractal\Resource\Collection;
use PhalconRest\Transformers\ModelTransformer;

/**
 * Class CountriesTransformer
 * @package App\Transformers
 */
class CountriesTransformer extends ModelTransformer
{
    /**
     * Transforms are automatically handled
     * based on your model when you extend ModelTransformer
     * and assign the modelClass property
     */


    public function __construct()
    {
        $this->modelClass = Countries::class;

        $this->availableIncludes = [
            'Users'
        ];
    }

    /**
     * @param Countries $model
     * @return Collection
     */
    public function includeUsers(Countries $model): Collection
    {
        return $this->collection($model->getUsers(), new UsersTransformer());
    }
}
