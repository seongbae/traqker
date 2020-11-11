<?php

namespace App\Traits;

use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use File;
use Illuminate\Support\Facades\Log;

trait HasAttachments
{
    public function addFile($file)
    {
        // Make a image name based on user name and current timestamp
        $name = Str::slug($file->getClientOriginalName()).'_'.time(); //.'.' . $image->getClientOriginalExtension();
        // Define folder path
        $folder = '/files/';
        // Make a file path where image will be stored [ folder path + file name + file extension]
        // $filePath = '/storage'.$folder . $name. ;
        // Upload image
        $this->uploadOne($file, $folder, 'public', $name);
        // Set user profile image path in database to filePath
        $filename = $name. '.' . $file->getClientOriginalExtension();

        $sizeinMB = $file->getSize()/1024/1024;
        if ($sizeinMB < 0.1)
            $size = round($sizeinMB, 2);
        elseif ($sizeinMB < 1)
            $size = round($sizeinMB, 2);
        else
            $size = round($sizeinMB, 0);

        return Attachment::create([
            'filename'=>$filename,
            'label'=>$file->getClientOriginalName(),
            'size'=>$size,
            'attached_model'=>get_class($this),
            'attached_model_id'=>$this->id
        ]);
    }

}
