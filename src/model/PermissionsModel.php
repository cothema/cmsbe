<?php

namespace Cothema\CMSBE\Model;

use IPub\Permissions;
use IPub\Permissions\Entities;

class PermissionsModel implements Permissions\Models\IRolesModel {

	/**
	 * @return array|Entities\IRole[]
	 */
	public function findAll() {
		// Create guest role
		$guest = (new Permissions\Entities\Role)
				->setKeyName(Entities\IRole::ROLE_ANONYMOUS)
				->setName('Guest')
				->setPriority(0)
				->setPermissions([
			'firstResourceName:firstPrivilegeName'
		]);
		// Create authenticated role
		$authenticated = (new Permissions\Entities\Role)
				->setKeyName(Entities\IRole::ROLE_AUTHENTICATED)
				->setName('Registered user')
				->setPriority(0)
				->setPermissions([
			'firstResourceName:firstPrivilegeName',
			'secondResourceName:secondPrivilegeName'
		]);
		$administrator = (new Permissions\Entities\Role)
				->setKeyName(Entities\IRole::ROLE_ADMINISTRATOR)
				->setName('Administrator')
				->setPriority(0)
				->setPermissions([
			'firstResourceName:firstPrivilegeName',
			'secondResourceName:secondPrivilegeName',
			'thirdResourceName:thirdPrivilegeName'
		]);
		$custom = (new Permissions\Entities\Role)
				->setKeyName('user-defined-role')
				->setName('Registered in custom role')
				->setPriority(0)
				->setPermissions([
			'firstResourceName:firstPrivilegeName',
			'thirdResourceName:thirdPrivilegeName'
		]);

		return [];

		return [
			$guest,
			$authenticated,
			$administrator,
			$custom
		];
	}

}
