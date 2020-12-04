### [0.4.0] 2020-??

  * BC BREAK: return type of AssocValue::keys changed from  `StringsArray` to `ArrayValue<TKey>`
  * BC BREAK: return type of StringsArray::toArray changed from  `string[]` to `array<int, StringValue>` (method `StringsArray::toNativeStrings(): string[]` was added as replacement)
  * BC BREAK: return type of StringsArray::map callback changed from `StringValue|string` to `StringValue`
