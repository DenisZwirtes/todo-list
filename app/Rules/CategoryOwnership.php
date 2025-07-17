<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryOwnership implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $category = Category::find($value);

        if (!$category) {
            $fail('A categoria não foi encontrada.');
            return;
        }

        if ($category->user_id !== auth()->id()) {
            $fail('Esta categoria não pertence ao usuário autenticado.');
        }
    }
}
