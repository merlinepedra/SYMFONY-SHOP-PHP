<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Event;

use App\Entity\Orden;
use Symfony\Contracts\EventDispatcher\Event;

class OrdenCreatedEvent extends Event
{
    protected $orden;

    public function __construct(Orden $orden)
    {
        $this->Orden = $orden;
    }

    public function getOrden(): Orden
    {
        return $this->orden;
    }
}
