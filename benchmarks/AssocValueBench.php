<?php

namespace bench\GW\Value;

use GW\Value\Wrap;

/**
 * @OutputTimeUnit("milliseconds", precision=2)
 */
final class AssocValueBench
{
    /** @var array */
    private $dataSet;

    /** @var \Closure */
    private $mapper;

    /** @var \Closure */
    private $filter;

    /** @var \Closure */
    private $sorter;

    public function __construct()
    {
        $this->dataSet = array_map('md5', range(1, 10000));
        $this->dataSet = array_combine($this->dataSet, $this->dataSet);

        $this->mapper = function (string $value): string {
            return strrev($value);
        };

        $this->filter = function (string $value): bool {
            return strpos($value, 'a') === 0;
        };

        $this->sorter = function (string $a, string $b): int {
            return $a <=> $b;
        };
    }

    /**
     * @Subject
     * @Groups({"array map"})
     */
    public function arrayMapWrapped(): void
    {
        Wrap::assocArray($this->dataSet)->map($this->mapper);
    }

    /**
     * @Subject
     * @Groups({"array filter"})
     */
    public function arrayFilterWrapped(): void
    {
        Wrap::assocArray($this->dataSet)->filter($this->filter);
    }

    /**
     * @Subject
     * @Groups({"array map filter"})
     */
    public function arrayMapAndFilterWrapped(): void
    {
        Wrap::assocArray($this->dataSet)->map($this->mapper)->filter($this->filter);
    }

    /**
     * @Subject
     * @Groups({"array sort"})
     */
    public function arraySortWrapped(): void
    {
        Wrap::assocArray($this->dataSet)->sort($this->sorter);
    }

    /**
     * @Subject
     * @Groups({"array map keys"})
     */
    public function arrayMapKeysForeach(): void
    {
        $result = [];
        foreach ($this->dataSet as $key => $value) {
            $result[($this->mapper)($key)] = $value;
        }
    }

    /**
     * @Subject
     * @Groups({"array map keys"})
     */
    public function arrayMapKeysWrapped(): void
    {
        Wrap::assocArray($this->dataSet)->mapKeys($this->mapper);
    }
}
