<?php

namespace App\Http\Controllers;

use App\File;
use App\Visibility;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @param $name
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($name)
    {
        $file = File::mine()->named($name)->first();

        if (!$file) {
            return response('Not Found', 404);
        }

        return response()->json([
            'share-name' => $file->share_name,
            'force-download' => $file->force_download,
            'visibility' => $file->visibility,
            'password' => $file->visibility == Visibility::PUBLIC_WITH_PASSWORD ? decrypt($file->password) : '',
            'allowed-users' => $file->allowed_users,
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::mine()->get();

        return view('home', compact('files'));
    }

    /**
     * @param Request $request
     * @param $name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $name)
    {
        $file = File::mine()->named($name)->first();

        if (!$file) {
            return response()->route('home')->with('error', 'Share file not found');
        }

        $this->validate($request, [
            'edit_share_name' => 'required',
            'edit_force_download' => 'required|boolean',
            'edit_visibility' => 'required|exists:visibilities,id',
            'edit_password' => 'required_if:edit_visibility,3'
        ]);

        $file->share_name = $request->get('edit_share_name');
        $file->force_download = $request->get('edit_force_download');
        $file->visibility = $request->get('edit_visibility');
        if ($file->visibility == 3) {
            $file->password = encrypt($request->get('edit_password'));
        } else {
            $file->password = '';
        }
        $file->save();

        return redirect()->route('home')->with('status', 'Share file updated successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'shared_file' => 'required|file',
            'shared_force_download' => 'required|boolean',
            'visibility' => 'required|exists:visibilities,id',
            'shared_password' => 'required_if:visibility,3'
        ]);

        $upload = $request->file('shared_file');

        try {
            \DB::beginTransaction();
            $file = new File();
            $file->user_id = auth()->user()->id;
            $file->original_name = $upload->getClientOriginalName();
            $file->share_name = $upload->getClientOriginalName();
            $file->force_download = $request->get('shared_force_download');
            $file->mime_type = $upload->getClientMimeType();
            $file->size = $upload->getClientSize();
            $file->visibility = $request->get('visibility');
            if ($file->visibility == 3) {
                $file->password = encrypt($request->get('shared_password'));
            }
            $file->save();

            $upload->storeAs($file->dir, $file->id);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('home')->with('error', 'Failed to save the uploaded file' . $e->getMessage());
        }

        return redirect()->route('home')->with('status', 'File has been successfully shared');
    }
}
