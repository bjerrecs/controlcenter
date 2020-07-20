<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    /**
     * Get the file
     *
     * @param Request $request
     * @param File $file
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function get(Request $request, File $file)
    {
        $this->authorize('view', $file);

        if (Storage::exists($file->full_path)) {
            return response()->file(Storage::path($file->full_path));
        } else {
            return abort(404);
        }
    }

    /**
     * Store the file
     *
     * @param Request $request
     * @return false|\Illuminate\Http\RedirectResponse|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', File::class);

        $this->validateRequest();

        $id = $this->saveFile($request->file('file'));

        if ($request->expectsJson()) {
            return json_encode([
                'file_id' => $id,
                'message' => 'File successfully uploaded'
            ]);
        }

        return redirect()->back()->withSuccess('File successfully uploaded');
    }

    /**
     * Delete the provided file
     *
     * @param Request $request
     * @param File $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, File $file)
    {
        $this->authorize('delete', $file);

        if (Storage::delete($file->full_path) == true) {
            return redirect()->back()->withSuccess('File successfully deleted');
        }

        return redirect()->back()->with('error', 'An error occurred during the deletion of the file');

    }

    private function validateRequest()
    {
        return request()->validate([
            'file' => 'required|file'
        ]);
    }

    /**
     * Save the provided file using the naming scheme.
     *
     * @param UploadedFile $file
     * @param string|null $filename
     * @return string
     */
    public static function saveFile(UploadedFile $file, string $filename = null)
    {
        $extension = $file->getExtension();
        $id = sha1($file->getClientOriginalName() . now()->format('Ymd_His') . rand(1000, 9999));

        if ($filename == null) {
            $filename = now()->format('Ymd_His') . "_" . $id;
        }

        $filename = $filename . "." . $extension;

        Storage::putFileAs('public/files/', $file, $filename);

        File::create([
            'id' => $id,
            'name' => $file->getClientOriginalName(),
            'uploaded_by' => Auth::id(),
            'path' => $filename
        ]);

        return $id;
    }

}
