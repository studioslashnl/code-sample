<?php
// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

namespace App\Http\Controllers;

use App\Interfaces\TCLChaptersRepositoryInterface;

class LibraryController extends Controller
{
    private TCLChaptersRepositoryInterface $categoriesRepository;


    public function __construct(TCLChaptersRepositoryInterface $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function __invoke()
    {
        return [
            'website' => $this->categoriesRepository->getWebsiteChapter(),
            'ui' => $this->categoriesRepository->getUIChapter(),
        ];
    }
}