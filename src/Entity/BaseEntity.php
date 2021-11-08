<?php


namespace Entity;


use Vendor\Core\Hydrator;

abstract class BaseEntity
{
    use Hydrator;

    /**
     * BaseEntity constructor.
     * @param array $datas
     */
    public function __construct(array $datas = [])
    {
        $this->hydrate($datas);
    }
}