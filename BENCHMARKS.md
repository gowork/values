Performance: native vs wrapped
==============================

### groups: array diff

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayDiffSameFunction | 10 | 2,600,176b | 0.35ms | 1.10x
ArrayValueBench | arrayDiffSameWrapped | 10 | 3,781,632b | 0.69ms | 2.15x
ArrayValueBench | arrayDiffSubsetFunction | 10 | 2,575,600b | 0.32ms | 1.00x
ArrayValueBench | arrayDiffSubsetWrapped | 10 | 3,757,112b | 0.86ms | 2.66x
ArrayValueBench | arrayDiffComparatorFunction | 10 | 2,895,128b | 17.02ms | 52.83x
ArrayValueBench | arrayDiffComparatorWrapped | 10 | 3,757,120b | 17.40ms | 54.03x

### groups: array filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayFilterFunction | 10 | 2,500,888b | 0.92ms | 1.00x
ArrayValueBench | arrayFilterForeach | 10 | 2,500,888b | 0.95ms | 1.03x
ArrayValueBench | arrayFilterWrapped | 10 | 2,737,096b | 1.04ms | 1.13x
AssocValueBench | arrayFilterWrapped | 10 | 2,554,048b | 0.93ms | 1.01x

### groups: array map

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapFunction | 10 | 3,178,736b | 1.46ms | 1.06x
ArrayValueBench | arrayMapForeach | 10 | 3,178,728b | 1.37ms | 1.00x
ArrayValueBench | arrayMapWrapped | 10 | 4,360,128b | 1.55ms | 1.13x
AssocValueBench | arrayMapWrapped | 10 | 3,551,544b | 2.14ms | 1.57x

### groups: array map filter

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arrayMapAndFilterFunction | 10 | 3,236,144b | 2.07ms | 1.00x
ArrayValueBench | arrayMapAndFilterWrapped | 10 | 4,360,144b | 2.56ms | 1.24x
AssocValueBench | arrayMapAndFilterWrapped | 10 | 3,551,560b | 2.79ms | 1.35x

### groups: array map keys

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
AssocValueBench | arrayMapKeysForeach | 10 | 3,435,712b | 2.14ms | 1.00x
AssocValueBench | arrayMapKeysWrapped | 10 | 3,900,784b | 2.14ms | 1.00x

### groups: array sort

benchmark | subject | revs | mem_peak | time_rev | diff
 --- | --- | --- | --- | --- | --- 
ArrayValueBench | arraySortFunction | 10 | 3,128,584b | 13.73ms | 1.00x
ArrayValueBench | arraySortWrapped | 10 | 3,781,648b | 13.97ms | 1.02x
AssocValueBench | arraySortWrapped | 10 | 2,732,416b | 13.97ms | 1.02x

