<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAttributeInArray implements ValidationRule
{

    protected string $attributeInArray;
    protected array $duplicates;

    public function __construct(string $attributeInArray)
    {
        $this->attributeInArray = $attributeInArray;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $values = array_column($value, $this->attributeInArray);
        $values = array_filter($values, fn($value) => $value !== null && $value !== "");
        $this->duplicates = array_keys(array_filter(array_count_values($values), fn($count) => $count > 1));

        // Fail if duplicates exist
        if (!empty($this->duplicates)) {
            $fail(__('validation.custom.unique_attribute_in_array', [
                'array' => str_replace("_"," ",$attribute),
                'attribute' => str_replace("_"," ",$this->attributeInArray),
                'duplicates' => implode(', ', $this->duplicates),
            ]));
        }
    }
}
