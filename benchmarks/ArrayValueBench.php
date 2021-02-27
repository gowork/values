<?php

namespace bench\GW\Value;

use GW\Value\Wrap;

/**
 * @OutputTimeUnit("milliseconds", precision=2)
 */
final class ArrayValueBench
{
    /** @var array */
    private $dataSet;

    /** @var array */
    private $dataSubset;

    /** @var \Closure */
    private $mapper;

    /** @var \Closure */
    private $filter;

    /** @var \Closure */
    private $comparator;

    public function __construct()
    {
        $this->dataSet = array_map('md5', range(1, 10000));
        $this->dataSubset = \array_slice($this->dataSet, 100, 1000);

        $this->mapper = function (string $value): string {
            return strrev($value);
        };

        $this->filter = function (string $value): bool {
            return strpos($value, 'a') === 0;
        };

        $this->comparator = function (string $a, string $b): int {
            return $a <=> $b;
        };
    }

    /**
     * @Subject
     * @Groups({"array map"})
     */
    public function arrayMapFunction(): void
    {
        array_map($this->mapper, $this->dataSet);
    }

    /**
     * @Subject
     * @Groups({"array map"})
     */
    public function arrayMapForeach(): void
    {
        $result = [];
        foreach ($this->dataSet as $value) {
            $result[] = ($this->mapper)($value);
        }
    }

    /**
     * @Subject
     * @Groups({"array map"})
     */
    public function arrayMapWrapped(): void
    {
        Wrap::array($this->dataSet)->map($this->mapper)->toArray();
    }

    /**
     * @Subject
     * @Groups({"array filter"})
     */
    public function arrayFilterFunction(): void
    {
        array_filter($this->dataSet, $this->filter);
    }

    /**
     * @Subject
     * @Groups({"array filter"})
     */
    public function arrayFilterForeach(): void
    {
        $result = [];
        foreach ($this->dataSet as $value) {
            if (($this->filter)($value)) {
                $result[] = $value;
            }
        }
    }

    /**
     * @Subject
     * @Groups({"array filter"})
     */
    public function arrayFilterWrapped(): void
    {
        Wrap::array($this->dataSet)->filter($this->filter)->toArray();
    }

    /**
     * @Subject
     * @Groups({"array map filter"})
     */
    public function arrayMapAndFilterFunction(): void
    {
        array_filter(array_map($this->mapper, $this->dataSet), $this->filter);
    }

    /**
     * @Subject
     * @Groups({"array map filter"})
     */
    public function arrayMapAndFilterWrapped(): void
    {
        Wrap::array($this->dataSet)->map($this->mapper)->filter($this->filter)->toArray();
    }

    /**
     * @Subject
     * @Groups({"array sort"})
     */
    public function arraySortFunction(): void
    {
        $dataSet = $this->dataSet;
        uasort($dataSet, $this->comparator);
    }

    /**
     * @Subject
     * @Groups({"array sort"})
     */
    public function arraySortWrapped(): void
    {
        Wrap::array($this->dataSet)->sort($this->comparator)->toArray();
    }

    /**
     * @Subject
     * @Groups({"array diff"})
     */
    public function arrayDiffSameFunction(): void
    {
        array_diff($this->dataSet, $this->dataSet);
    }

    /**
     * @Subject
     * @Groups({"array diff"})
     */
    public function arrayDiffSameWrapped(): void
    {
        Wrap::array($this->dataSet)->diff(Wrap::array($this->dataSet))->toArray();
    }

    /**
     * @Subject
     * @Groups({"array diff"})
     */
    public function arrayDiffSubsetFunction(): void
    {
        array_diff($this->dataSet, $this->dataSubset);
    }

    /**
     * @Subject
     * @Groups({"array diff"})
     */
    public function arrayDiffSubsetWrapped(): void
    {
        Wrap::array($this->dataSet)->diff(Wrap::array($this->dataSubset))->toArray();
    }

    /**
     * @Subject
     * @Groups({"array diff"})
     */
    public function arrayDiffComparatorFunction(): void
    {
        array_udiff($this->dataSet, $this->dataSubset, $this->comparator);
    }

    /**
     * @Subject
     * @Groups({"array diff"})
     */
    public function arrayDiffComparatorWrapped(): void
    {
        Wrap::array($this->dataSet)->diff(Wrap::array($this->dataSubset), $this->comparator)->toArray();
    }
}
