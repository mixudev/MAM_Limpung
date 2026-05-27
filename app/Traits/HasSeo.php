<?php

namespace App\Traits;

use App\Models\Seo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    /**
     * Get the SEO relationship associated with the model.
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable')->withDefault([
            'is_indexed' => true,
            'is_followed' => true,
        ]);
    }
}
