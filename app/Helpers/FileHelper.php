<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     *
     * @param UploadedFile 
     * @param string 
     * @return string
     */
    public static function storeFileRegister(UploadedFile $file, string $type, string $content, string $disk = 'public'): string
    {       //? $type/$content/filename
        //? images/licenses/uuid.png
        $ext = $file->extension();
        $filename = (string) Str::uuid() . '.' . $ext;
        $path = "{$type}/{$content}/{$filename}";
        return $file->storeAs($path, $filename, 'public');
    }

    public static function storeFileMedia($file, $type, string $content, string $disk = 'public')
    {
        //* images/post/2002/2/3/uuid.png

        $ext = $file->extension();
        $date = date('Y/m/d');
        $filename = (string) Str::uuid() . '.' . $ext;
        $contentType = $type === 'image' ? 'images' : 'videos';
        $subdir = "{$contentType}/{$content}/{$date}";
        return $path = $file->storeAs($subdir, $filename, 'public');
    }



    ///storage/images/users/{user_id}/avatar.jpg
    ///storage/images/publishers/{publisher_id}/avatar.jpg
    public static function storeFileProfile($file, $userId, string $userType)
    {

        $folder = "images/{$userType}/{$userId}";
        $filename = "avatar." . $file->extension();
        $path = "{$folder}/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        Storage::disk('public')->put($path, file_get_contents($file)); // this way is better to make over write 

        return $path;
    }

    public static function storeAvatar(UploadedFile $file, string $ownerId, string $ownerType, string $disk = 'public'): string
    {
        // Folder path: images/{type}/{id}
        $folder = "images/{$ownerType}/{$ownerId}";

        $ext = strtolower($file->getClientOriginalExtension());
        $filename = "avatar.{$ext}";
        $path = "{$folder}/{$filename}";

        foreach (['jpg', 'jpeg', 'png', 'gif', 'webp'] as $oldExt) {
            $oldPath = "{$folder}/avatar.{$oldExt}";
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        Storage::disk('public')->put($path, file_get_contents($file));

        return $path;
    }
}
