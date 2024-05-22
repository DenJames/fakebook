<?php

namespace App\Repositories\WordFilter;

use App\Models\Ban;
use App\Models\WordFilter;
use Illuminate\Http\RedirectResponse;

class WordFilterRepository
{
    public function replaceWordsInString($inputString): string|RedirectResponse
    {
        $replacements = WordFilter::active()->get();
        foreach ($replacements as $replacement) {
            if ($replacement->bannable) {
                if (str_contains(strtolower($inputString), strtolower($replacement->word))) {
                    Ban::create(['user_id' => auth()->id(), 'reason' => 'You used a banned word ('.$replacement->word.')', 'expires_at' => now()->addDays(7)]);
                    return redirect()->route('banned');
                }
            }
            $inputString = str_ireplace($replacement->word, $replacement->replacement, $inputString);
        }
        return $inputString;
    }
}
