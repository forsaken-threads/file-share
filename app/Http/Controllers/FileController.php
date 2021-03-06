<?php

namespace App\Http\Controllers;

use App\File;
use App\Visibility;
use Illuminate\Support\MessageBag;

class FileController extends Controller
{

    /**
     * @param $name
     * @param null $preview
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function get($name, $preview = null)
    {
        $file = File::named($name)->first();

        if (!$file) {
            return $this->redirect('That file could not be found');
        }

        if ($file->isMine()) {
            return $this->showFile($file, $preview);
        }

        if ($file->visibility == Visibility::PUBLIC_WITHOUT_PASSWORD) {
            return $this->showFile($file, $preview);
        }

        if ($file->visibility == Visibility::PUBLIC_WITH_PASSWORD) {
            return $this->showProtectedFile($file, $preview);
        }

        if ($file->visibility == Visibility::ANY_AUTHENTICATED_USER && auth()->check()) {
            return $this->showFile($file, $preview);
        }

        return $this->redirect('You do not have access to that file');
    }

    /**
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirect($message)
    {
        $route = auth()->check() ? 'home' : 'public';
        return redirect()->route($route)->with('error', $message);
    }

    /**
     * @param File $file
     * @param $preview
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function showFile(File $file, $preview)
    {
        $file->downloads++;
        $file->save();

        return $preview
            ? response()->file($file->path)
            : response()->download($file->path, $file->share_name);
    }

    /**
     * @param File $file
     * @param $preview
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function showProtectedFile(File $file, $preview)
    {
        $errors = new MessageBag();

        if (request()->isMethod('post') && request('file_password') == decrypt($file->password)) {
            return $this->showFile($file, $preview);
        } elseif (request()->isMethod('post')) {
            $errors->add(0, 'Invalid password');
        }

        return view('prompt', compact('file', 'errors'));
    }
}
