<?php

require_once "simplehtmldom/simple_html_dom.php";
require_once "Snoopy/Snoopy.class.php";

ini_set("upload_max_filesize", "100M");
ini_set("post_max_file", "100M");

function printer($user, $pass, $printer, $file, $one_sided = true, $copies = 1, $range='') {
    $pass=stripcslashes($pass);
	if($con = ssh2_connect("remote1.studat.chalmers.se", 22)){
		if(ssh2_auth_password($con, $user, $pass)){
			ssh2_exec($con, "mkdir -p .print");
			ssh2_scp_send($con, $file, ".print/chalmersit.dat", 0644);
			$sides = (!$one_sided ? "one-sided" : "two-sided-long-edge");
			$range = (empty($range) ? "" : "-o page-ranges=$range");

            // The environment variable CUPS_GSSSERVICENAME=HTTP must be set, othewise kerberos throws unauthorized
			$stream = ssh2_exec($con, "CUPS_GSSSERVICENAME=HTTP;lpr -P $printer -# $copies -o sides=$sides $range .print/chalmersit.dat");
            $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
            stream_set_blocking($errorStream, true);
            $outputError = stream_get_contents($errorStream);
            fclose($stream);
            fclose($errorStream);

            if(strlen($outputError) > 0) {
                log_to_file($outputError, 50, $user);
                return "Printservern rapporterade: " . str_replace("lpr:", "", $outputError);
            }
			return ""; // Everything went alright
		}
		else {
            log_to_file("bad cid/pass", 100, $user);
			return "Fel cid eller lösenord";
        }
	}
	else {
        log_to_file("connection to remote server error/timeout", 200, $user);
		return "Det gick inte att ansluta till printerservern. Försök igen om en liten stund";
    }
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
$preErrorMsg = "Kunde inte skriva ut din fil. ";

if(isset($_POST['print'])) {

	if(!empty($_FILES) && in_array($_FILES["upload"]["type"], $file_types) && $_FILES["upload"]["size"] < 100500000) {

        $printerError = printer($_POST["user"], $_POST["pass"], $_POST["printer"], $_FILES["upload"]["tmp_name"], $_POST["oneSided"], intval($_POST['copies']), $_POST['ranges']);
		if(empty($printerError)) {

			$notice = "Din fil är utskriven!";
			@unlink($_FILES["upload"]["tmp_name"]);
		}
		else {
			$errors[] = "Kunde inte skriva ut din fil. " . $printerError;
			@unlink($_FILES["upload"]["tmp_name"]);
		}
	}
	else {
		$errors[] = "Kunde inte skriva ut filen. Kontrollera filtyp och storlek";
	}
}

function log_to_file($msg, $code, $cid, $extra = "")
{ // http://en.wikipedia.org/wiki/Common_Log_Format   not exactly the same, but close enough ;)
    $f = fopen("/var/log/live.chalmers.it-printer", 'a');
    $time = strftime("%d/%b/%Y:%H:%M:%S %z");
    $client = $_SERVER['REMOTE_ADDR'];
    
    $extra = (strlen($extra) > 0 ? " extra: " . $extra : "");
    $logLine = $client . " user-identifier " . $cid . " [" . $time . "] \"" . trim($msg) . "\" " . $code . $extra . "\n";
    
    fwrite($f, $logLine);
    fclose($f);
}

?>
