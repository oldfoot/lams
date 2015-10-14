<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."inc/functions/filesystem/size_int.php";
require_once $GLOBALS['dr']."inc/functions/db/row_exists.php";

class UploadDocument {

	function __construct() {
		$this->errors="";
	}

	public function SetParameters($filename,$filetype,$filesize,$application_id,$attachment) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (EMPTY($filename)) { $this->Errors("Invalid file [1]"); return False; }
		if (EMPTY($filetype)) { $this->Errors("Invalid file [2]"); return False; }
		if (EMPTY($filesize)) { $this->Errors("Invalid file [3]"); return False; }
		if (EMPTY($attachment)) { $this->Errors("Invalid file [4]"); return False; }
		if (EMPTY($application_id) || !IS_NUMERIC($application_id)) { $this->Errors("Invalid application [5]"); echo "error"; return False; }

		$category_exists=RowExists("leave_applications","application_id",$application_id,"AND workspace_id = '".$GLOBALS['workspace_id']."'");
		/* STORE VARIABLES */
		$this->filename=$filename;
		$this->filetype=$filetype;
		$this->filesize=$filesize;
		$this->application_id=$application_id;
		$this->attachment=$attachment;

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function UploadFile() {

		/* CHECK THE PARAMETERS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed");  return False; }

		$upload_file=True;

		$result=$this->SaveToDatabase();
		if ($result) {
			//echo "ok";
			return True;
		}
		else {
			//echo "err";
			return False;
		}
	}

	/* SAVE TO DATABASE */
	private function SaveToDatabase() {

		$db=$GLOBALS['db'];

		/* CHECK WHICH FUNCTION TO USE TO ESCAPE BINARY DATA */
		if ($GLOBALS['database_type']=="postgres") {
			$escape_binary_function="pg_escape_bytea";
		}
		else {
			$escape_binary_function="mysql_escape_string";
		}


		/* CREATE A DOWNLOAD KEY */
		//$anonymous_download_key=MD5($_SESSION['user_id'].$this->filename.$this->filetype.$this->filesize.microtime());

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_application_attachments
				(application_id,filename,filetype,filesize,attachment)
				VALUES (
				'".$this->application_id."',
				'".$this->filename."',
				'".$this->filetype."',
				'".$this->filesize."',
				'".mysql_escape_string($this->attachment)."'
				)";
		//
		//echo $sql;

		$db->query($sql);
		//return "";
		/* GRAB THE LAST INSERTED ID */
		$this->attachment_id=$db->LastInsertId();

		return True;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>