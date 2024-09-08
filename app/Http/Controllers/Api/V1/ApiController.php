<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApiController extends Controller
{
    use ApiResponses;
    protected $policyClass;
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble($ability, $targetModel)
    {
        try {
            $this->authorize($ability, [$targetModel, $this->policyClass]);
            return true;
        } catch (AuthorizationException $authorizationException) {
            return false;
        }
    }
}
