<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $status = $request->has('status') ? true : false;
        $order = $request->order ?? 0;

        // Multiple images upload support
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $idx => $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);

                Slider::create([
                    'image' => $filename,
                    'title' => $idx === 0 ? $request->title : null,
                    'subtitle' => $idx === 0 ? $request->subtitle : null,
                    'description' => $idx === 0 ? $request->description : null,
                    'btn_text' => $idx === 0 ? $request->btn_text : null,
                    'btn_link' => $idx === 0 ? $request->btn_link : null,
                    'order' => $order + $idx,
                    'status' => $status,
                ]);
            }
            return redirect()->route('admin.sliders.index')->with('success', count($files) . ' slides uploaded successfully.');
        }

        // Single image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            Slider::create([
                'image' => $filename,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'description' => $request->description,
                'btn_text' => $request->btn_text,
                'btn_link' => $request->btn_link,
                'order' => $order,
                'status' => $status,
            ]);

            return redirect()->route('admin.sliders.index')->with('success', 'Slide created successfully.');
        }

        return back()->withErrors(['image' => 'Please select at least one image to upload.']);
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'btn_text' => $request->btn_text,
            'btn_link' => $request->btn_link,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false,
        ];

        if ($request->hasFile('image')) {
            if ($slider->image && file_exists(public_path('uploads/' . $slider->image))) {
                @unlink(public_path('uploads/' . $slider->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image && file_exists(public_path('uploads/' . $slider->image))) {
            @unlink(public_path('uploads/' . $slider->image));
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slide deleted successfully.');
    }

    public function toggle(Slider $slider)
    {
        $slider->status = !$slider->status;
        $slider->save();

        return back()->with('success', 'Slide status updated.');
    }
}
