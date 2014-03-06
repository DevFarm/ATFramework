<?php

namespace classes\base;

use ATCore;
use Imagick;
use ImagickPixel;
use classes\base\ATCore_Db as db;

class ATCore_File
{
    public static $static_host = '';

    public static $_source_path = '/../file';

    public static $_images_path = '/../www/images';

    public static function get($id, $type = 'full')
    {
        $file = ATCore::$serv->document_root . static::build_path($id, 'image') . '/' . substr($id, -1) . '/' . $type;

        if (file_exists($file)) {
            $type = filetype($file);
            header('Content-type: ' . $type);
            echo file_get_contents($file);
        } else {
            static::optimize($id, $type);
            static::get($id, $type);
        }
    }

    public static function optimize($id, $type)
    {
        $source_path = ATCore::$serv->document_root . static::build_path($id) . '/' . substr($id, -1) . '.file';
        $images_path = ATCore::$serv->document_root . static::build_path($id, 'image') . '/' . substr($id, -1) . '/';

        if (class_exists('imagick')) {
            $imagick = new Imagick($source_path);
            $imageprops = $imagick->getImageGeometry();

            switch ($type) {
                case 'full':
                {
                    $width = $imageprops['width'];
                    $height = $imageprops['height'];

                    break;
                }
                case 'small':
                {
                    $width = 400;
                    $height = 400;

                    break;
                }
                case 'preview':
                {
                    $width = 100;
                    $height = 100;

                    break;
                }
                default:
                    {
                    $width = 0;
                    $height = 0;

                    break;
                    }
            }

            if ($imageprops['width'] >= $width && $imageprops['height'] >= $height) {
                $imagick->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1.0, true);
            }

            $s_width = $imagick->getimagewidth();
            $s_height = $imagick->getimageheight();

            $x = ceil(abs($s_width - $width) / 2);
            $y = ceil(abs($s_height - $height) / 2);

            $imagick->cropimage($width, $height, $x, $y);
            $imageprops = $imagick->getimagegeometry();

            $image = null;

            if ($imageprops['width'] < $width || $imageprops['height'] < $height) {
                $newimg = new Imagick();
                $newimg->newImage($width, $height, new ImagickPixel('white'));
                $newimg->setImageFormat('jpeg');

                $x = ceil(abs($imageprops['width'] - $width) / 2);
                $y = ceil(abs($imageprops['height'] - $height) / 2);

                $newimg->compositeImage($imagick, Imagick::COMPOSITE_ATOP, $x, $y);

                $image = $newimg;
                $newimg->getimageblob();
            } else {
                $image = $imagick;
                $imagick->getimageblob();
            }

            if (!file_exists(getcwd() . '/upload_pic/' . self::build_path($id) . '/' . $id)) {
                mkdir(getcwd() . '/upload_pic/' . self::build_path($id) . '/' . $id, 0777);
                chmod(getcwd() . '/upload_pic/' . self::build_path($id) . '/' . $id, 0777);
            }

            $image->writeimage(getcwd() . '/upload_pic/' . self::build_path($id) . '/' . $id . '/' . $type);
        } else {
            if (!file_exists($images_path)) {
                mkdir($images_path, 0777);
                chmod($images_path, 0777);
            }

            copy($source_path, $images_path . $type);
        }
    }

    public static function build_path($id, $mode = 'file')
    {
        $id = strval($id);
        $path = '';

        for ($i = 0; $i < (strlen($id) - 1); $i++) {
            $path .= '/' . $id[$i];
        }

        switch ($mode) {
            case 'file':
            {
                $path = static::$_source_path . $path;
                break;
            }
            case 'image':
            {
                $path = static::$_images_path . $path;
                break;
            }
        }

        return $path;
    }

    public static function upload(array $files = array())
    {
        $result = array();

        if (is_array($files['name'])) {
            foreach ($files['name'] as $key => $name) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );

                $result[] = static::adaptive_upload($file);
            }
        } else {
            $result = self::adaptive_upload($files);
        }

        return $result;
    }

    public static function adaptive_upload(array $file = array())
    {
        $hash = md5_file($file['tmp_name']);
        $info = self::info(array('hash' => $hash));

        if (isset($info['id'])) {
            return $info;
        } else {
            db::query('
				INSERT INTO `file` SET
					`hash`	= "' . $hash . '",
					`name`	= "' . $file['name'] . '",
					`mime`	= "' . $file['type'] . '",
					`size`	= ' . intval($file['size']) . '
			');

            $id = db::insert_id();

            $upload_dir = ATCore::$serv->document_root . '/../file' . static::build_path($id) . '/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777);
            }

            $write_id = substr($id, -1);
            copy($file['tmp_name'], $upload_dir . $write_id . '.file');

            $data = array(
                'id' => $id,
                'hash' => $hash,
                'name' => $file['name'],
                'mime' => $file['type'],
                'size' => $file['size'],
                'date' => date('Y-m-d H:i:s')
            );

            return $data;
        }
    }

    public static function info(array $data = array())
    {
        if (!empty($data['hash'])) {
            $sql = db::query('SELECT * FROM `file` WHERE `hash` = "' . $data['hash'] . '" LIMIT 1');
        } else {
            $sql = db::query('SELECT * FROM `file` WHERE `id` = ' . intval($data['id']) . ' LIMIT 1');
        }

        return db::fetch($sql);
    }
}