<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JobArchive\App\elasticsearch\Search;

class ArchivedJobController extends Controller
{
    public function index(Request $request, Search $search) {

        $cCollection = $search->getArchivedJobs($request->query("page") ?? 1, $request->query("perPage") ?? 10);

        return view("welcome", ["results" => $cCollection]);
    }
}
