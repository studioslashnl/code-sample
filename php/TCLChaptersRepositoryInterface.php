<?php
// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

namespace App\Interfaces;

interface TCLChaptersRepositoryInterface
{
    public function getTeaserChapter();
    public function getWebsiteChapter();
    public function getUIChapter();
}