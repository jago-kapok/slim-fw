<?php

class UploadModel
{
    public static function uploadImage($image_name, $image_rename, $destination, $user_id, $note)
    {
        // check upload folder writing rights
        if (!self::isFolderWritable($destination)) {
            return false;
        }

        // check file name meet criteria
        if (!self::isValidFileName($image_name)) {
            return false;
        }

        //Get file extension and check file is a valid image
        $file_extension = strtolower(pathinfo($_FILES[$image_name]['name'], PATHINFO_EXTENSION));
        if (!self::isValidImageFile($image_name, $file_extension)) {
            return false;
        }

        //replace semua yang bukan angka dan huruf dengan -
        $file_title  = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $image_name));
        $file_name = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $user_id));
        
        //for value in database
        $file_path = $destination . '/' . $file_title . '-' . $file_name . '.' . $file_extension;
        $target_file_path = Config::get('PATH_UPLOAD') . $file_path;
        //if they DID upload a file...
        if($_FILES[$image_name]['name']) {
            //if no errors...
            if(!$_FILES[$image_name]['error']) {
                //chek if upload success
                if (move_uploaded_file($_FILES[$image_name]['tmp_name'], $target_file_path)) {
                    self::writeToDatabase($destination, $user_id, $image_rename, $file_path, $note);
                    Session::add('feedback_positive', 'SUKSES!, upload file image berhasil');
                    return true;
                } else {
                    return false;
                }
            } else { //if there is an error...
                return false;
            }
        } else {
            return false;
        }
        
        return false; // default return
    }

    public static function uploadDocument($document_name, $document_rename, $destination, $user_id, $note)
    {
        // check upload folder writing rights
        if (!self::isFolderWritable($destination)) {
            return false;
        }
        
        // check file name meet criteria
        if (!self::isValidFileName($document_name)) {
            return false;
        }

        //Get file extension and check file is a valid image
        $file_extension = strtolower(pathinfo($_FILES[$document_name]['name'], PATHINFO_EXTENSION));
        if (!self::isValidDocumentFile($document_name, $file_extension)) {
            return false;
        }

        //replace semua yang bukan angka dan huruf dengan -
        $file_title  = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $document_rename));
        $file_name = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $user_id));

        //for value in database
        $file_path = $destination . '/' . $file_title . '-' . $file_name . '.' . $file_extension;
        $target_file_path = Config::get('PATH_UPLOAD') . $file_path;
        //if they DID upload a file...
        if($_FILES[$document_name]['name']) {
            //if no errors...
            if(!$_FILES[$document_name]['error']) {
                //chek if upload success
                if (move_uploaded_file($_FILES[$document_name]['tmp_name'], $target_file_path)) {
                    self::writeToDatabase($destination, $user_id, $document_rename, $file_path, $note);
                    Session::add('feedback_positive', 'SUKSES!, upload file document berhasil');
                    return true;
                } else {
                    return false;
                }
            } else { //if there is an error...
                return false;
            }
        } else {
            return false;
        }
        
        return false; // default return
    }



    /**
     * Checks if the avatar folder exists and is writable
     *
     * @return bool success status
     */
    public static function isFolderWritable($destination)
    {
        if (is_dir(Config::get('PATH_UPLOAD') . $destination) AND is_writable(Config::get('PATH_UPLOAD') . $destination)) {
            return true;
        }

        Session::add('feedback_negative', "ERROR!, $destination folder tidak ada atau tidak writable");
        return false;
    }

    /**
     * Validates the image
     * Only accepts gif, jpg, png types
     * @see http://php.net/manual/en/function.image-type-to-mime-type.php
     *
     * @return bool
     */
    public static function isValidImageFile($image_name, $file_extension)
    {
        if (!isset($_FILES[$image_name]) AND !empty($_FILES[$image_name])) {
            Session::add('feedback_negative', 'GAGAL!, tipe file tidak dipilih');
            return false;
        }

        // if input file too big (>5MB)
        if ($_FILES[$image_name]['size'] > 3000000) {
            Session::add('feedback_negative', 'GAGAL!, Ukuran file (file size) tidak boleh melebihi 3MB');
            return false;
        }

        // get the image width, height and mime type
        $image_proportions = getimagesize($_FILES[$image_name]['tmp_name']);

        // if input file too small, [0] is the width, [1] is the height
        if ($image_proportions[0] < 100 OR $image_proportions[1] < 100) {
            Session::add('feedback_negative', 'GAGAL!, dimensi file (lebar x panjang) terlalu kecil, minimum 100 x 100 pixel');
            return false;
        }

        // if file type is not jpg, gif or png
        if (!in_array($image_proportions['mime'], array('image/jpeg', 'image/gif', 'image/png'))) {
            Session::add('feedback_negative', 'GAGAL!, tipe file tidak diijinkan, hanya gunakan Photo/Gambar format JPG, GIF dan PNG');
            return false;
        }

        // if file type is not jpg, gif or png
        if (!in_array($file_extension, array('jpeg', 'gif', 'png', 'jpg'))) {
            Session::add('feedback_negative', 'Tipe file tidak diinjinkan');
            return false;
        }

        return true;
    }

    /**
     * Validates the image
     * Only accepts gif, jpg, png types
     * @see http://php.net/manual/en/function.image-type-to-mime-type.php
     *
     * @return bool
     */
    public static function isValidDocumentFile($file_name, $file_extension)
    {
        if (!isset($_FILES[$file_name]) AND !empty($_FILES[$file_name])) {
            Session::add('feedback_negative', 'GAGAL!, tipe file tidak dipilih');
            return false;
        }

        // if input file too big (>5MB)
        if ($_FILES[$file_name]['size'] > 3000000) {
            Session::add('feedback_negative', 'GAGAL!, Ukuran file (file size) tidak boleh melebihi 3MB');
            return false;
        }

        /*
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES[$file_name]['tmp_name']);
        $ok = false;
        switch ($mime) {
            case 'application/pdf': //.pdf
                $ok = true;
                break;
            case 'application/msword': //.doc
                $ok = true;
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': //.docx
                $ok = true;
                break;
            case 'application/vnd.ms-powerpoint': //.ppt
                $ok = true;
                break;
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation': //.pptx
                $ok = true;
                break;
            case 'application/vnd.ms-excel': //.xls
                $ok = true;
                break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': //.xlsx
                $ok = true;
                break;
           default:
               $ok = false;
        }

        // if file type is not jpg, gif or png
        if (!$ok) {
            Session::add('feedback_negative', 'GAGAL!, tipe file tidak sesuai. Hanya gunakan file document PDF, mircosoft words (.doc, .docx), mircosoft excel (.xls, .xlsx), microsoft power point (.ppt, .pptx)');
            return false;
        }

        // if file type is not jpg, gif or png
        if (!in_array($file_extension, array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'))) {
            Session::add('feedback_negative', 'Tipe file tidak diinjinkan');
            return false;
        }
        */
        return true;
    }

    /**
    * Check $_FILES[][name]
    *
    * @param (string) $filename - Uploaded file name.
    * @author Yousef Ismaeil Cliprz
    */
    public static function isValidFileName($file_name)
    {
        /*
        //make sure that the file name not bigger than 250 characters.
        if (mb_strlen($file_name,"UTF-8") > 225) {
            Session::add('feedback_negative', 'GAGAL!, nama file photo yang diupload lebih dari 250 karakter');
            return false;
        }
        */

        return true;
    }


    /**
     * Writes marker to database, saying user has an avatar now
     *
     * @param $uid
     */
    public static function writeToDatabase($category, $item_id, $item_name, $value, $note)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("INSERT INTO `upload_list` (`uid`, `category`, `item_id`, `item_name`, `value`, `note`, `creator_id`) VALUES (:uid, :category, :item_id, :item_name, :value, :note, :creator_id)");
        $query->execute(array(
                            ':uid' => GenericModel::guid(),
                            ':category' => $category,
                            ':item_id' => $item_id,
                            ':item_name' => $item_name,
                            ':value' => $value,
                            ':note' => $note,
                            ':creator_id' => SESSION::get('uid'),
                        ));
    }

}
