<?php

require_once "simplehtmldom/simple_html_dom.php";
require_once "Snoopy/Snoopy.class.php";

ini_set("upload_max_filesize", "100M");
ini_set("post_max_file", "100M");

function printer($user, $pass, $printer, $file, $one_sided = true, $copies = 1, $range='') {
	error_log("Printing .. ");

	if($con = ssh2_connect("remote1.studat.chalmers.se", 22)){
		if(ssh2_auth_password($con, $user, $pass)){
			ssh2_exec($con, "mkdir .print");
			ssh2_scp_send($con, $file, ".print/tmp.dat", 0644);
			$sides = (!$one_sided ? "one-sided" : "two-sided-long-edge");
			$range = (empty($range) ? "" : "-o page-ranges=$range");
			ssh2_exec($con, "lpr -P $printer -# $copies -o sides=$sides $range .print/tmp.dat");
			
			return true;
		}
		else
			return false;
	}
	else
		return false;
		
}

$file_types = array(
	"application/pdf",
	"text/plain",
	"image/jpeg",
	"text/html",
	"image/x-MS-bmp",
	"application/rtf",
	"image/tiff",
	"image/gif",
	"text/html",
	"image/png",
	"text/css",
	"text/javascript",
	"text/x-csrc",
	"application/octet-stream"
);

global $errors, $notice;
$errors = array();

if(isset($_POST['print'])) {

	if(!empty($_FILES) && in_array($_FILES["upload"]["type"], $file_types) && $_FILES["upload"]["size"] < 100500000) {
		error_log("Posting .. ");

		if(printer($_POST["user"], $_POST["pass"], $_POST["printer"], $_FILES["upload"]["tmp_name"], $_POST["oneSided"], intval($_POST['copies']), $_POST['ranges'])) {

			$notice = "Din fil är utskriven!";
			@unlink($_FILES["upload"]["tmp_name"]);
		}
		else {
			$errors[] = "Kunde inte skriva ut din fil. Kontrollera fil, CID-namn och lösenord";
			@unlink($_FILES["upload"]["tmp_name"]);
			#header("Location: printer_login.php?err");
			#exit();
		}
	}
	else {
		$errors[] = "Kunde inte skriva ut filen. Kontrollera filtyp och storlek";
	}
}

?>
