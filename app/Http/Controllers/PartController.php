<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $parts = Part::query()
            ->with(['media'])
            ->filter($request->only(['search']))
            ->orderBy('number')
            ->paginate(10)
            ->withQueryString();

        return view('parts.index', compact('parts'));
    }

    public function show(Part $part)
    {
        $part->load(['episodes' => fn ($q) => $q->orderBy('episode_number'), 'media']);

        return view('parts.show', compact('part'));
    }
}
