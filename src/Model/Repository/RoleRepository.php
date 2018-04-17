<?php
namespace Popov\ZfcRole\Model\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use	Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository {

	protected $_table = 'roles';

	protected $_alias = 'role';

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRoles()
    {
        $qb = $this->createQueryBuilder($this->_alias);
        $qb->select($this->_alias);

        return $qb;
    }


    public function findByUsers($users) {
        $userAlias = 'user';

        $qb = $this->createQueryBuilder($this->_alias);
        $qb->select($this->_alias)
            ->addSelect($userAlias)
            ->leftJoin($this->_alias . '.users', $userAlias)
        ;

        $qb->where($qb->expr()->in($userAlias . '.id', '?1'));
        $qb->setParameter(1, $users);

        //$query = $qb->getQuery();
        //\Zend\Debug\Debug::dump($query->getSql()); die(__METHOD__);

        return $qb;
    }


	/**
	 * @return array
	 */
	public function findAllItems()
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT *
			FROM {$this->_table} {$this->_alias}",
			$rsm
		);

		return $query->getResult();
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @return mixed
	 */
	public function findOneItem($id, $field = 'id')
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT *
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`$field` = ?
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$id]);

		$result = $query->getResult();

		if (count($result) == 0)
		{
			$result = $this->createOneItem();
		}
		else
		{
			$result = $result[0];
		}

		return $result;
	}

}