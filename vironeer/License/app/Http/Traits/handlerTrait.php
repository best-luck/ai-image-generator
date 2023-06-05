<?php

namespace Vironeer\License\App\Http\Traits;

trait handlerTrait
{
    public function extensionsArray()
    {
        $extensionsArray = [];
        foreach (phpExtensions() as $extension) {
            $extensionsArray[] = extensionAvailability($extension);
        }
        return $extensionsArray;
    }

    public function permissionsArray()
    {
        $permissions = filePermissions();
        $permissionsArray = [];
        foreach ($permissions as $permission) {
            $permissionsArray[] = filePermissionValidation($permission);
        }
        return $permissionsArray;
    }

}
