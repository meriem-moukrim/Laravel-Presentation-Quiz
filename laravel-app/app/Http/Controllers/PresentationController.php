<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * Manages the presentation/course content.
 */
class PresentationController extends Controller
{
    /**
     * Display the course homepage.
     * 
     * - Scans the `resources/data/sections` directory.
     * - Loads and sorts JSON/Markdown content files.
     * - Passes the structured data to the view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sectionsPath = resource_path('data/sections');
        $sections = [];

        if (File::exists($sectionsPath)) {
            $files = File::files($sectionsPath);

            // Sort files by name naturally to respect numbering (0-intro, 1-routing, etc.)
            usort($files, function ($a, $b) {
                return strnatcmp($a->getFilename(), $b->getFilename());
            });

            foreach ($files as $file) {
                $content = json_decode(File::get($file->getPathname()), true);
                if ($content) {
                    $sections[] = $content;
                }
            }
        }

        return view('home', compact('sections'));
    }
}
