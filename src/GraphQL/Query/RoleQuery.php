<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2019 Bielov Andrii
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Stagem
 * @package Stagem_<package>
 * @author Bielov Andrii <bielovandrii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

use GraphQL\Type\Definition\Type;
use Popov\ZfcRole\Model\Role;
use \GraphQL\Doctrine\Types;

class RoleQuery
{
    public function __invoke(Types $types)
    {
        return [
            'role' => [
                'type' => $this->types->getOutput(Role::class), // Use automated ObjectType for output
                'description' => 'Returns user by id',
                'args' => [
                    'id' => Type::nonNull(Type::id()),
                ],
                'resolve' => function ($root, $args) use ($types) {
                    $queryBuilder = $this->types->createFilteredQueryBuilder(Role::class, $args['filter'] ?? [],
                        $args['sorting'] ?? []);
                    $result = $queryBuilder->getQuery()->getArrayResult();

                    return $result;
                },
            ],
        ];
    }
}