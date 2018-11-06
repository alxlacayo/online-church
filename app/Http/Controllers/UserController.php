<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\User;

class UserController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user()->id),
            ]
        ]);

        $user = $request->user();
        $data = $request->only('name', 'email');
        $user->fill($data);
        $user->save();
        $user->setProfilePictureSize('large');

        return response()->json($user);
    }

    /**
     * Return the currently logged in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {   
        return response()->json($request->user()->makeVisible('email')); 
    }

    /**
     * Upload users profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePicture(Request $request)
    {
        if (!$request->hasFile('picture')) {
            return response()->json(['message' => 'Nothing to upload.'], 422);
        }
        
        // Validation!
        $user = $request->user();
        $oldFilename = $user->getOriginal('profile_picture');
        $filename = Str::random(40) . '.jpg';
        $img = Image::make($request->picture);

        foreach (User::PROFILE_PICTURE_SIZES as $key => $size) {
            $imgVariation = Image::make($request->picture)
                ->fit($size)
                ->save();

            Storage::disk('public')->put(
                '/users/' . $user->id . '/profile_pictures/' . $key . '/' . $filename, $imgVariation
            );
        }

        $user->profile_picture = $filename;
        $user->setProfilePictureSize('large');

        if ($user->save()) {
            $this->removeProfilePictureFiles($user->id, $oldFilename);
        }

        return response()->json($user);
    }

    /**
     * Delete users profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyProfilePicture(Request $request)
    {
        $user = $request->user();

        if (is_null($user->getOriginal('profile_picture'))) {
            return response()->json(['message' => 'Nothing to delete.'], 422);
        }

        $user->profile_picture = null;

        if ($user->save()) {
            $this->removeProfilePictureDirectory($user->id);
        }
       
        return response()->json($user);
    }

    /**
     * Remove the pictures from storage.
     *
     * @param  int    $userId
     * @param  string $filename
     * @return bool
     */
    private function removeProfilePictureFiles($userId, $filename)
    {
        $files = [];
        
        foreach (User::PROFILE_PICTURE_SIZES as $key => $size) {
            $files[] = '/users/' . $userId . '/profile_pictures/' . $key . '/' . $filename; 
        }

        return Storage::disk('public')->delete($files);
    }

    /**
     * Remove the users directory from storage.
     *
     * @param  int  $userId
     * @return bool
     */
    private function removeProfilePictureDirectory($userId)
    {
        return Storage::disk('public')->deleteDirectory('/users/' . $userId);
    }
}
