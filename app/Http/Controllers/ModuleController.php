<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module; 

class ModuleController extends Controller
{
    public function index()
{
    $module = Module::orderBy('index_order', 'asc')->get();
    return view('admin.dashboard', compact('module'));
}

    public function store(Request $request)
    {
        $request->validate([
            'module_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'module_name' => 'required|string|max:255',
            'module_description' => 'required|string',
            'index_order' => 'required|numeric'
        ]);

        // Proses upload gambar
        $imageName = null;
        if ($request->hasFile('module_image')) {
            $imageName = time().'.'.$request->module_image->extension();
            $request->module_image->move(public_path('uploads/module'), $imageName);
        }

        // Simpan data ke database
        Module::create([
            'module_image' => $imageName,
            'module_name' => $request->module_name,
            'module_description' => $request->module_description,
            'index_order' => $request->index_order,
        ]);

        return redirect()->back()->with('success', 'Module berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        return view('admin.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required|string',
            'module_description' => 'required|string',
            'index_order' => 'nullable|numeric',
        ]);

        $module = Module::findOrFail($id);
        $module->update($request->only('module_name', 'module_description', 'index_order'));

        return redirect()->route('module.index')->with('success', 'Module updated successfully!');
    }

    public function show($id)
    {
        $module = Module::findOrFail($id);
        return view('admin.show', compact('module'));
    }
    public function destroy($id)
    {
        try {
            $module = Module::findOrFail($id);
            $module->delete();

            return redirect()->route('module.index')->with('success', 'Module deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('module.index')->with('error', 'Failed to delete the module.');
        }
    }


}