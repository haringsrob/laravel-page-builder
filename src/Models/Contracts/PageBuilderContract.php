<?php

namespace Haringsrob\LaravelPageBuilder\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface PageBuilderContract
{
    public function pageBuilderPage(): BelongsTo;
}
