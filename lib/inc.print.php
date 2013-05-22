<?php

require_once "simplehtmldom/simple_html_dom.php";
require_once "Snoopy/Snoopy.class.php";

ini_set("upload_max_filesize", "100M");
ini_set("post_max_file", "100M");

class BadLoginException extends Exception {
	public function __construct() {
		parent::__construct("Fel cid eller lösenord");
	}
}

function printer($user, $pass, $printer, $file, $one_sided = true, $copies = 1, $range='') {
    $pass=stripcslashes($pass);
	if($con = ssh2_connect("remote2.student.chalmers.se", 22)){
		if(ssh2_auth_password($con, $user, $pass)){
            try {
			    ssh_exec($con, "mkdir -p .print");
			    ssh2_scp_send($con, $file, ".print/chalmersit.dat", 0644);
			    $sides = (!$one_sided ? "one-sided" : "two-sided-long-edge");
			    $range = (empty($range) ? "" : "-o page-ranges=$range");

                // The environment variable CUPS_GSSSERVICENAME=HTTP must be set, othewise kerberos throws unauthorized
			    ssh_exec($con, "lpr -P $printer -# $copies -o sides=$sides $range .print/chalmersit.dat");
            }
            catch(Exception $e) {
                log_to_file($e->getMessage(), $e->getCode(), $user);
                throw new Exception("Printservern rapporterade: " . str_replace("lpr:", "", $e->getMessage()));
            }

			return; // Everything went alright
		}
		else {
            log_to_file("bad cid/pass", 100, $user);
			throw new BadLoginException("Fel cid eller lösenord");
        }
	}
	else {
        log_to_file("connection to remote server error/timeout", 200, $user);
		throw new Exception("Det gick inte att ansluta till printerservern. Försök igen om en liten stund");
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

global $errors, $notice, $fileName, $jsCmd;
$errors = array();
$preErrorMsg = "Kunde inte skriva ut filen. ";
$jsCmd = "undefined";

if(isset($_POST['print'])) {

	if((!empty($_FILES) && in_array($_FILES["upload"]["type"], $file_types) && $_FILES["upload"]["size"] < 100500000) || !empty($_POST["sessionStorage"])) {

		try {
			$fileName = empty($_FILES) ? $_POST["sessionStorage"] : $_FILES["upload"]["tmp_name"];
        	printer($_POST["user"], $_POST["pass"], $_POST["printer"], $fileName, $_POST["oneSided"], intval($_POST['copies']), $_POST['ranges']);
        	$notice = "Din fil är utskriven!";
        	$jsCmd = "'UNSET'";
        	@unlink($_FILES["upload"]["tmp_name"]);
		} catch (BadLoginException $ble) {
			$errors[] = $preErrorMsg . $ble->getMessage();
			$jsCmd = '"'.$fileName.'"';
		} catch (Exception $e) {
			$errors[] = $preErrorMsg . $e->getMessage();
			$jsCmd = "'UNSET'";
			@unlink($_FILES["upload"]["tmp_name"]);
		}
	}
	else {
		$errors[] = $preErrorMsg . "Kontrollera filtyp och storlek";
	}
}

// Shamelessly stolen from http://stackoverflow.com/a/10939967 (With minor mods)
function ssh_exec($con, $command )
{
    $command = 'export CUPS_GSSSERVICENAME=HTTP;' . $command;
    $result = rawExec($con, $command.';echo -en "\n$?"' );
    if( ! preg_match( "/^(.*)\n(0|-?[1-9][0-9]*)$/s", $result[0], $matches ) ) {
        throw new RuntimeException( "output didn't contain return status" );
    }
    if( $matches[2] !== "0" ) {
        throw new RuntimeException( $result[1], (int)$matches[2] );
    }
    return $matches[1];
}

function rawExec($con, $command )
{
    $stream = ssh2_exec( $con, $command, $env);
    $error_stream = ssh2_fetch_stream( $stream, SSH2_STREAM_STDERR );
    stream_set_blocking( $stream, TRUE );
    stream_set_blocking( $error_stream, TRUE );
    $output = stream_get_contents( $stream );
    $error_output = stream_get_contents( $error_stream );
    fclose( $stream );
    fclose( $error_stream );
    return array( $output, $error_output );
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
