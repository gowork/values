Performance: native vs wrapped
==============================

### groups: array diff

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayDiffSameFunction | 1000 | 3,306,376b | 0.21ms | 1.10x
ArrayValueBench | arrayDiffSameWrapped | 1000 | 3,468,240b | 0.21ms | 1.11x
ArrayValueBench | arrayDiffSubsetFunction | 1000 | 3,220,360b | 0.19ms | 1.00x
ArrayValueBench | arrayDiffSubsetWrapped | 1000 | 3,869,672b | 0.25ms | 1.32x
ArrayValueBench | arrayDiffComparatorFunction | 1000 | 3,535,792b | 103.11ms | 542.65x
ArrayValueBench | arrayDiffComparatorWrapped | 1000 | 3,870,264b | 102.03ms | 536.98x

### groups: array filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayFilterFunction | 1000 | 3,141,624b | 6.46ms | 1.00x
ArrayValueBench | arrayFilterForeach | 1000 | 3,141,624b | 6.74ms | 1.04x
ArrayValueBench | arrayFilterWrapped | 1000 | 3,141,624b | 6.75ms | 1.05x
AssocValueBench | arrayFilterWrapped | 1000 | 3,258,304b | 7.36ms | 1.14x

### groups: array map

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapFunction | 1000 | 3,819,472b | 6.69ms | 1.03x
ArrayValueBench | arrayMapForeach | 1000 | 3,819,360b | 6.73ms | 1.04x
ArrayValueBench | arrayMapWrapped | 1000 | 3,981,088b | 6.50ms | 1.00x
AssocValueBench | arrayMapWrapped | 1000 | 4,385,312b | 8.91ms | 1.37x

### groups: array map filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapAndFilterFunction | 1000 | 3,880,912b | 14.12ms | 1.00x
ArrayValueBench | arrayMapAndFilterWrapped | 1000 | 4,061,864b | 14.09ms | 1.00x
AssocValueBench | arrayMapAndFilterWrapped | 1000 | 4,388,280b | 16.68ms | 1.18x

### groups: array map keys

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
AssocValueBench | arrayMapKeysForeach | 1000 | 4,238,208b | 8.43ms | 1.00x
AssocValueBench | arrayMapKeysWrapped | 1000 | 4,385,456b | 8.48ms | 1.00x

### groups: array sort

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arraySortFunction | 1000 | 3,834,800b | 81.86ms | 1.00x
ArrayValueBench | arraySortWrapped | 1000 | 3,996,552b | 84.59ms | 1.03x
AssocValueBench | arraySortWrapped | 1000 | 3,533,088b | 82.66ms | 1.01x

