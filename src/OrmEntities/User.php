<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */
namespace OpenAPIServer\OrmEntities;

use Cycle\Annotated\Annotation as Cycle;

/** @Cycle\Entity() */
class User
{
    /** @Cycle\Column(type="primary") */
    public $id;

    /** @Cycle\Column(type="string") */
    public $userRealName;

    /** @Cycle\Column(type="string") */
    public $userLoginName;

    /** @Cycle\Column(type="string") */
    public $userPassword;

    /** @Cycle\Column(type="string") */
    public $userPhone;

    /** @Cycle\Column(type="boolean", default=false) */
    public $userLoginStatus;

    /** @Cycle\Column(type="string", nullable=true) */
    public $token;
}
