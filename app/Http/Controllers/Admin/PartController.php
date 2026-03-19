<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;
use App\Models\Part;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function __construct(protected ImageService $imageService) {}

    public function index(Request $request)
    {
        $parts = Part::query()
            ->with(['media'])
            ->filter($request->only(['search']))
            ->orderBy('number')
            ->paginate(10)
            ->withQueryString();

        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create');
    }

    public function store(StorePartRequest $request)
    {
        $part = Part::create($request->validated());

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaData = $this->imageService->compressAndStore($file, 'media');
                $part->media()->create($mediaData);
            }
        }

        return redirect()->route('admin.parts.index')->with('success', 'Part created successfully.');
    }

    public function show(Part $part)
    {
        $part->load(['episodes' => fn ($q) => $q->orderBy('episode_number'), 'media']);

        return view('admin.parts.show', compact('part'));
    }

    public function edit(Part $part)
    {
        return view('admin.parts.edit', compact('part'));
    }

    public function update(UpdatePartRequest $request, Part $part)
    {
        $part->update($request->validated());

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaData = $this->imageService->compressAndStore($file, 'media');
                $part->media()->create($mediaData);
            }
        }

        return redirect()->route('admin.parts.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $part->delete();

        return redirect()->route('admin.parts.index')->with('success', 'Part has been erased from history!');
    }
}
