<?php

namespace Lokalkoder\Entree\Commands\Concerns;

trait UseEntreeModel
{

    protected function userModel()
    {
        return app(config('entree.user.model'));
    }

    protected function roleModel()
    {
        return app(config('entree.user.role'));
    }

    protected function permissionModel()
    {
        return app(config('entree.user.permission'));
    }
}
