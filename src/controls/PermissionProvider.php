<?php

namespace Cothema\CMSBE\Service;

use IPub;
use Nette;

class PermissionProvider extends Nette\DI\CompilerExtension implements IPub\Permissions\DI\IPermissionsProvider
{

    /**
     * Return array of module permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        return [
            'someResourceName: somePrivilegeName' => [
                'title' => 'this part is optional and can be used pro editing purposes, etc.',
                'description' => 'this part is optional and can be used pro editing purposes, etc.'
            ],
        ];
    }
}
