<?php

declare(strict_types=1);

namespace OurEnergy\WitsApi\Models;

class Node
{
    protected string $island;
    protected string $node;

    /**
     * @return string
     */
    public function getIsland(): string
    {
        return $this->island;
    }

    /**
     * @param string $island
     */
    public function setIsland(string $island): void
    {
        $this->island = $island;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->node;
    }

    /**
     * @param string $node
     */
    public function setNode(string $node): void
    {
        $this->node = $node;
    }

    /**
     * @param array $data
     *
     * @return Node
     */
    public static function create(array $data): Node
    {
        $instance = new Node();
        $instance->setIsland($data['island']);
        $instance->setNode($data['node']);

        return $instance;
    }
}