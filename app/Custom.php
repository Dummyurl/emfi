<?php
namespace App;

/**
 * Custom Class.
 *
 * @subpackage custom class
 * @author     
 */
class Custom 
{

    public static function createThumnails($uploadPath, $filename) 
    {
        $sizes = env('APP_IMAGE_THUMB_SIZES');
        $sizes = explode(',', $sizes);
        $orgFile = $uploadPath . $filename;

        foreach ($sizes as $size) 
        {
            $temp = explode('X', $size);
            $thumbFile = $uploadPath . "thumb_" . $size . "_" . $filename;

            $w = isset($temp[0]) ? $temp[0] : 100;
            $h = isset($temp[1]) ? $temp[1] : 100;

            \Image::make($orgFile)
                    ->resize($w, $h)                    
                    ->save($thumbFile);
        }
    }
    public static function getLanguages()
    {
        $data = array();
        $data = ['en'=>'English','es'=>'Spanish'];
        return $data;
    }
    public static function getTeamData()
    {
        $record[1] = ['name'=>'Usman Sheikh','post'=>'Chief Executive','linkedin'=>'https://www.linkedin.com/in/emfiusman/'];
        $record[2] = ['name'=>'Mansoor Razzaq','post'=>'Asset Management','linkedin'=>'https://www.linkedin.com/in/mansoor-razzaq-5011a2/'];
        $record[3] = ['name'=>'Gabriel De Jesus Sierra','post'=>'Wealth Management','linkedin'=>'https://www.linkedin.com/in/gabriel-de-jesus-sierra-28751485/'];
        $record[4] = ['name'=>'Daniel Kushner','post'=>'Compliance','linkedin'=>'https://www.linkedin.com/in/daniel-kushner-a1814332/'];
        $record[5] = ['name'=>'Imran Ashraf','post'=>'Finance','linkedin'=>'https://www.linkedin.com/in/imran-ashraf-cheema-9455b7/'];
        $record[6] = ['name'=>'Patricio Diaz','post'=>'Business Development','linkedin'=>'https://www.linkedin.com/in/diazpatricio/'];
        $record[7] = ['name'=>'Ruth Benitez','post'=>'Client Integration','linkedin'=>'#'];
        $record[8] = ['name'=>'Henry Travieso','post'=>'Operations','linkedin'=>'https://www.linkedin.com/in/henry-travieso-49a95b2/'];
        $record[9] = ['name'=>'Luisa Gastambide','post'=>'Marketing / HR','linkedin'=>'https://www.linkedin.com/in/luisa-gastambide-5174a511/'];
        return $record;
    }
}

if (!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

}
?>