<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\OrmEntities;

use Cycle\Annotated\Annotation as Cycle;
use Cycle\Annotated\Annotation\Relation as Relation;
use Doctrine\Common\Collections\ArrayCollection;

/** @Cycle\Entity() */
class Company
{
    public function __construct()
    {
        $this->companyCities = new ArrayCollection();
        $this->companyDivisions = new ArrayCollection();
        $this->companyUsers = new ArrayCollection();
    }

    /** @Cycle\Column(type="primary") */
    public $id;

    /** @Cycle\Column(type="string") */
    public $companyName;

    /** @Cycle\Column(type="string") */
    public $ownershipType;

    /** @Cycle\Column(type="string") */
    public $chiefName;

    /** @Relation\HasMany(target=City::class) */
    public $companyCities;

    /** @Relation\HasMany(target=Division::class) */
    public $companyDivisions;

    /** @Relation\HasMany(target=User::class) */
    public $companyUsers;
}
