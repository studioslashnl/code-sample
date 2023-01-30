<?php
// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

namespace App\Repositories;

use App\Models\Page;
use App\Interfaces\TCLChaptersRepositoryInterface;

class TCLChaptersRepository implements TCLChaptersRepositoryInterface
{
    public function getTeaserChapter()
    {
        return Page::where('name', 'teaser-chapter')->get();
    }

    public function getWebsiteChapter()
    {
        return Page::where('name', 'website-chapter')->get();
    }

    public function getUIChapter()
    {
        return Page::where('name', 'ui-chapter')->get();
    }
}