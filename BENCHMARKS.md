Performance: native vs wrapped
==============================

### groups: array diff

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayDiffSameFunction | 1000 | 2,319,264b | 0.29ms | 1.09x
ArrayValueBench | arrayDiffSameWrapped | 1000 | 2,396,984b | 0.31ms | 1.18x
ArrayValueBench | arrayDiffSubsetFunction | 1000 | 2,233,248b | 0.26ms | 1.00x
ArrayValueBench | arrayDiffSubsetWrapped | 1000 | 2,798,448b | 0.37ms | 1.41x
ArrayValueBench | arrayDiffComparatorFunction | 1000 | 2,548,704b | 20.66ms | 78.72x
ArrayValueBench | arrayDiffComparatorWrapped | 1000 | 2,798,528b | 24.17ms | 92.07x

### groups: array filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayFilterFunction | 1000 | 2,154,400b | 0.93ms | 1.00x
ArrayValueBench | arrayFilterForeach | 1000 | 2,154,400b | 0.95ms | 1.03x
ArrayValueBench | arrayFilterWrapped | 1000 | 2,154,400b | 0.96ms | 1.03x
AssocValueBench | arrayFilterWrapped | 1000 | 2,281,328b | 1.02ms | 1.10x

### groups: array map

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapFunction | 1000 | 2,832,288b | 0.95ms | 1.00x
ArrayValueBench | arrayMapForeach | 1000 | 2,832,256b | 1.13ms | 1.19x
ArrayValueBench | arrayMapWrapped | 1000 | 2,909,864b | 1.07ms | 1.13x
AssocValueBench | arrayMapWrapped | 1000 | 3,338,928b | 1.94ms | 2.04x

### groups: array map filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapAndFilterFunction | 1000 | 2,893,816b | 2.16ms | 1.00x
ArrayValueBench | arrayMapAndFilterWrapped | 1000 | 2,988,648b | 2.18ms | 1.01x
AssocValueBench | arrayMapAndFilterWrapped | 1000 | 3,339,912b | 4.72ms | 2.19x

### groups: array map keys

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
AssocValueBench | arrayMapKeysForeach | 1000 | 3,261,336b | 1.82ms | 1.00x
AssocValueBench | arrayMapKeysWrapped | 1000 | 3,338,960b | 2.03ms | 1.11x

### groups: array sort

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arraySortFunction | 1000 | 2,847,680b | 18.24ms | 1.07x
ArrayValueBench | arraySortWrapped | 1000 | 2,925,344b | 16.99ms | 1.00x
AssocValueBench | arraySortWrapped | 1000 | 2,486,960b | 21.20ms | 1.25x

