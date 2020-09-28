<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\OrmEntities;

use Cycle\Annotated\Annotation as Cycle;

/** @Cycle\Entity() */
class City
{
    /** @Cycle\Column(type = "primary") */
    public $id;

    /** @Cycle\Column(type="string") */
    public $cityName;

    /** @Cycle\Column(type="string") */
    public $timeZone;
}
