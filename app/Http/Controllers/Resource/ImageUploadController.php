<?php

namespace App\Http\Controllers\Resource;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{

    public function cover(Request $request)
    {

        $path = $request->getRequestUri();
        // 自動計算文件的md5
        // Storage::putFile('photos', new File('/path/to/photo'));

        // 手動指定文件名...
        // Storage::putFileAs('photos', new File('/path/to/photo'), 'photo.jpg');
        //通過path獲取路徑

        $md5 = md5(time());
        $path = sprintf('%s/%s/%s/%s',$path,$md5[0],$md5[1],$md5[2]);
        $file = $request->file('file');

        //手動指定驅動爲public
        $success = Storage::disk('public')->put($path, $file);

        $url = $request->getSchemeAndHttpHost().Storage::url($success);

        return ['name'=>$file->getClientOriginalName(),'path'=>$success,'url'=>$url];

    }
}