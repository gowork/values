Performance: native vs wrapped
==============================

### groups: array diff

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayDiffSameFunction | 100 | 3,306,376b | 0.21ms | 1.10x
ArrayValueBench | arrayDiffSameWrapped | 100 | 3,468,240b | 0.22ms | 1.17x
ArrayValueBench | arrayDiffSubsetFunction | 100 | 3,220,360b | 0.19ms | 1.00x
ArrayValueBench | arrayDiffSubsetWrapped | 100 | 3,869,672b | 0.26ms | 1.41x
ArrayValueBench | arrayDiffComparatorFunction | 100 | 3,535,792b | 97.18ms | 518.64x
ArrayValueBench | arrayDiffComparatorWrapped | 100 | 3,870,264b | 105.43ms | 562.71x

### groups: array filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayFilterFunction | 100 | 3,141,624b | 6.58ms | 1.00x
ArrayValueBench | arrayFilterForeach | 100 | 3,141,624b | 6.90ms | 1.05x
ArrayValueBench | arrayFilterWrapped | 100 | 3,141,624b | 6.63ms | 1.01x
AssocValueBench | arrayFilterWrapped | 100 | 3,258,304b | 7.10ms | 1.08x

### groups: array map

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapFunction | 100 | 3,819,472b | 7.00ms | 1.05x
ArrayValueBench | arrayMapForeach | 100 | 3,819,360b | 7.08ms | 1.06x
ArrayValueBench | arrayMapWrapped | 100 | 3,981,088b | 6.66ms | 1.00x
AssocValueBench | arrayMapWrapped | 100 | 4,385,312b | 8.30ms | 1.25x

### groups: array map filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapAndFilterFunction | 100 | 3,880,912b | 13.63ms | 1.03x
ArrayValueBench | arrayMapAndFilterWrapped | 100 | 4,061,864b | 13.27ms | 1.00x
AssocValueBench | arrayMapAndFilterWrapped | 100 | 4,388,280b | 16.74ms | 1.26x

### groups: array map keys

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
AssocValueBench | arrayMapKeysForeach | 100 | 4,238,208b | 8.21ms | 1.00x
AssocValueBench | arrayMapKeysWrapped | 100 | 4,385,456b | 8.41ms | 1.02x

### groups: array sort

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arraySortFunction | 100 | 3,834,800b | 75.45ms | 1.00x
ArrayValueBench | arraySortWrapped | 100 | 3,996,552b | 80.27ms | 1.06x
AssocValueBench | arraySortWrapped | 100 | 3,533,088b | 79.62ms | 1.06x

