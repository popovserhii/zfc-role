<?php
namespace Popov\ZfcRole\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Popov\ZfcUser\Model\User;

/**
 * @ORM\Entity(repositoryClass="Popov\ZfcRole\Model\Repository\RoleRepository")
 * @ORM\Table(name="role")
 */
class Role {
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
	private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     */
	private $name;

    /**
     * @var string
     * @ORM\Column(name="mnemo", type="string", length=32, nullable=false)
     */
	private $mnemo;

    /**
     * @var string
     * @ORM\Column(name="resource", type="string", nullable=false)
     */
	private $resource;

    /**
     * Many Groups have Many Users.
     *
     * @var User[]
     * @ORM\ManyToMany(targetEntity="Popov\ZfcUser\Model\User", mappedBy="roles")
     */
    private $users;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->users = new ArrayCollection();
	}

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Role
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getMnemo()
    {
        return $this->mnemo;
    }

    /**
     * @param string $mnemo
     * @return Role
     */
    public function setMnemo($mnemo)
    {
        $this->mnemo = $mnemo;

        return $this;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $resource
     * @return Role
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[] $users
     * @return Role
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }
}
