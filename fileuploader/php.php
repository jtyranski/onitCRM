<?php



/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }


    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE, $section_id, $table, $id, $description, $path){
        
		
		if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        //$filename = $pathinfo['filename'];
		$filename = uniqueTimeStamp();
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];
		$desc = $pathinfo['filename'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
		    
			
			$sql = "INSERT into $table(section_id, $description) values (
	        '$section_id', \"\")";
	        executeupdate($sql);
			
	        $sql = "SELECT $id from $table where section_id='$section_id' and $description=\"\"
	        order by $id desc limit 1";
	        $photo_id=getsingleresult($sql);
			
	        $_SESSION['newphoto_ids'][] = $photo_id;
		

  	        resizeimage("uploads/$filename" . "." . $ext, "../uploaded_files/$path/", 640);
			@copy("../uploaded_files/$path/$filename" . "." . $ext, "../uploaded_files/$path/th_" . $filename . "." . $ext);
	        resizeimage("../uploaded_files/$path/th_" . $filename . "." . $ext, "../uploaded_files/$path/", 100);
			@unlink("uploads/$filename" . "." . $ext);
	
	         
	
	        $sql = "UPDATE $table set photo='$filename" . "." . $ext . "', $description=\"$desc\" 
		    where $id='$photo_id' and section_id='$section_id'";
	        executeupdate($sql);
		
		
            return array('success'=>true);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 6 * 1024 * 1024;
require_once "../includes/functions.php";

$section_id = $_GET['section_id'];
$table = $_GET['table'];
$id = $_GET['id'];
$description = $_GET['description'];
$path = $_GET['path'];

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('uploads/', FALSE, $section_id, $table, $id, $description, $path);

// '../uploaded_files/' . $path . '/'
//$result = $uploader->handleUpload('fileuploader/uploads/', FALSE, $section_id, $table, $id, $description, $path);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
