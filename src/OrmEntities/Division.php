<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\OrmEntities;

use Cycle\Annotated\Annotation as Cycle;
use Cycle\Annotated\Annotation\Relation as Relation;

/** @Cycle\Entity() */
class Division
{
    /** @Cycle\Column(type="primary") */
    public $id;

    /** @Cycle\Column(type="string") */
    public $divisionName;

    /** @Cycle\Column(type="string") */
    public $divisionAddress;

    /** @Cycle\Column(type="string") */
    public $divisionCoordinates;

    /** @Cycle\Column(type = "enum(cafe, office)", default = "cafe") */
    public $divisionType;

    /** @Relation\HasOne(target=City::class) */
    public $divisionCity;
}
