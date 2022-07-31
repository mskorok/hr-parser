<?php
declare(strict_types=1);

namespace App\Resources;

use App\Controllers\UsersController;
use App\Transformers\UsersTransformer;
use PhalconApi\Constants\PostedDataMethods;
use PhalconRest\Api\ApiResource;
use App\Api\Endpoint as ApiEndpoint;
use App\Model\Users;
use App\Constants\AclRoles;

/**
 * Class UsersResource
 * @package App\Resources
 */
class UsersResource extends ApiResource
{

    public function initialize()
    {
        $this
            ->name('Users')
            ->model(Users::class)
            ->expectsJsonData()
            ->transformer(UsersTransformer::class)
            ->handler(UsersController::class)
            ->itemKey('user')
            ->collectionKey('users')
//            ->allow([AclRoles::ADMIN, AclRoles::SUPERADMIN, AclRoles::EMPLOYER, AclRoles::MANAGER])
//            ->deny(AclRoles::UNAUTHORIZED)
            ->allow(AclRoles::UNAUTHORIZED)

            ->endpoint(
                ApiEndpoint::all()
                    ->allow([AclRoles::ADMIN, AclRoles::SUPERADMIN, AclRoles::EMPLOYER, AclRoles::MANAGER])
                    ->description('Returns all registered users')
            )
            ->endpoint(
                ApiEndpoint::create()
                    ->allow([AclRoles::ADMIN, AclRoles::SUPERADMIN, AclRoles::APPLICANT])
                    ->postedDataMethod(PostedDataMethods::POST)
            )
            ->endpoint(
                ApiEndpoint::find()
                    ->allow([AclRoles::ADMIN, AclRoles::SUPERADMIN, AclRoles::EMPLOYER, AclRoles::MANAGER])
            )
            ->endpoint(
                ApiEndpoint::update()
                    ->allow([AclRoles::ADMIN, AclRoles::SUPERADMIN, AclRoles::APPLICANT])
                    ->postedDataMethod(PostedDataMethods::POST)
            )
            ->endpoint(
                ApiEndpoint::remove()
                    ->allow([AclRoles::ADMIN, AclRoles::SUPERADMIN, AclRoles::APPLICANT])
            )
            ->endpoint(
                ApiEndpoint::get('/me', 'me')
                    ->allow([AclRoles::AUTHORIZED])
                    ->deny(AclRoles::UNAUTHORIZED)
                    ->description('Returns the currently logged in user')
            )
            ->endpoint(
                ApiEndpoint::post('/authenticate', 'authenticate')
                    ->allow(AclRoles::UNAUTHORIZED)
                    ->deny(AclRoles::AUTHORIZED)
                    ->description(
                        'Authenticates user credentials provided in the
                         authorization header and returns an access token'
                    )
                    ->exampleResponse(
                        ''
                    )
            )
//            ->endpoint(
//                ApiEndpoint::get('/logout', 'logout')
//                    ->allow(AclRoles::UNAUTHORIZED)
//                    //->allow(AclRoles::AUTHORIZED)
//                    ->description('Logout authenticated user')
//            )
            ->endpoint(
                ApiEndpoint::post('/password', 'newPassword')
                    ->allow(AclRoles::AUTHORIZED)
                    ->description('Recovery new pass')
            )->endpoint(
                ApiEndpoint::get('/password', 'newPassword')
                    ->allow(AclRoles::AUTHORIZED)
                    ->description('Recovery new pass')
            );
    }
}
