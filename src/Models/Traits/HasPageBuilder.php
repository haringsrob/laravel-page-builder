<?php

namespace Haringsrob\LaravelPageBuilder\Models\Traits;

use Haringsrob\LaravelPageBuilder\Models\PageBuilderPage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasPageBuilder
{
    protected $pageBuilderField = 'page_builder_page_id';

    public function pageBuilderPage(): BelongsTo
    {
        return $this->belongsTo(PageBuilderPage::class, $this->pageBuilderField);
    }
}
