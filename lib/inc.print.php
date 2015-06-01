<?php

require "class.PrintJob.php";

ini_set("upload_max_filesize", "100M");
ini_set("post_max_file", "100M");

define('CHALMERS_SSH_HOST', 'remote1.student.chalmers.se');
define('CHALMERS_SSH_PORT', 22);
define('CHALMERS_SSH_FILENAME', '.print/chalmersit.dat');

class SSHConnection {

	private $connection = null;

	public function __construct($user, $passwd) {
		if (! ($this->connection = ssh2_connect(CHALMERS_SSH_HOST, CHALMERS_SSH_PORT))) {
			log_to_file("connection to remote server error/timeout", 200, $user);
			throw new PrintException("Det gick inte att ansluta till printerservern. Försök igen om en liten stund");
		}
		$res = ssh2_auth_password($this->connection, $user, $passwd);
		if (!$res) {
			throw new BadLoginException($user);
		}
	}

	public function upload($file, $name, $permissons = 0644) {
		ssh2_scp_send($this->connection, $file, $name, $permissons);
	}

	public function ssh_exec($command) {
		try {
			$this->internal_exec($command);
		} catch (RuntimeException $e) {
			log_to_file($e->getMessage(), $e->getCode(), $user);
			throw new PrintException("Printservern rapporterade: " . str_replace("lpr:", "", $e->getMessage()));
		}
	}

	// Shamelessly stolen from http://stackoverflow.com/a/10939967 (With minor mods)
	private function internal_exec($command) {
		$command = 'export CUPS_GSSSERVICENAME=HTTP;' . $command;
		$result = $this->rawExec($command.';echo -en "\n$?"');
		if (!preg_match( "/^(.*)\n(0|-?[1-9][0-9]*)$/s", $result[0], $matches)) {
			throw new RuntimeException("output didn't contain return status");
		}
		if ($matches[2] !== "0") {
			throw new RuntimeException($result[1], (int)$matches[2]);
		}
		return $matches[1];
	}

	private function rawExec($command) {
		$stream = ssh2_exec($this->connection, $command, $env);
		$error_stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
		stream_set_blocking($stream, TRUE);
		stream_set_blocking($error_stream, TRUE);
		$output = stream_get_contents($stream);
		$error_output = stream_get_contents($error_stream);
		fclose($stream);
		fclose($error_stream);
		return array($output, $error_output);
	}
}

class PrintException extends Exception {
	public function __construct($message) {
		parent::__construct($message);
	}
}

class BadLoginException extends PrintException {
	public function __construct($user) {
		parent::__construct("Fel cid eller lösenord");
	}
}

function post($var, $default = false) {
	return isset($_POST[$var]) ? $_POST[$var] : $default;
}

$file_types = array(
	"application/pdf",
	"text/plain",
	"text/html",
	"application/rtf",
	"text/html",
	"text/css",
	"text/javascript",
	"text/x-csrc",
	"application/octet-stream"
);

function log_to_file($msg, $code, $cid, $extra = "") {
	// http://en.wikipedia.org/wiki/Common_Log_Format   not exactly the same, but close enough ;)
	$f = fopen("/var/log/live.chalmers.it-printer", 'a');
	$time = strftime("%d/%b/%Y:%H:%M:%S %z");
	$client = $_SERVER['REMOTE_ADDR'];

	$extra = (strlen($extra) > 0 ? " extra: " . $extra : "");
	$logLine = $client . " user-identifier " . $cid . " [" . $time . "] \"" . trim($msg) . "\" " . $code . $extra . "\n";

	fwrite($f, $logLine);
	fclose($f);
}

global $errors, $notice;
$errors = array();

if(isset($_POST['print'])) {

	try {
		if($_FILES["upload"]["size"] == 0) {
			throw new PrintException("Ingen fil angiven eller ogiltig fil");
		}
		if (!in_array($_FILES["upload"]["type"], $file_types) || $_FILES["upload"]["size"] > 100500000) {
			throw new PrintException("Felaktig filtyp och/eller filstorlek");
		}

		$filename = $_FILES["upload"]["tmp_name"];
		$printer = post("printer");
		$copies = intval(post('copies'));
		$options = array();

		if (post("duplex")) $options['sides'] = post('duplex') == '1' ? 'two-sided-long-edge' : 'one-sided';
		if (post('ranges')) $options['page-ranges'] = post('ranges');
		if (post('ppi') && post('ppi') !== 'auto') $options['ppi'] = post('ppi');
		if (post('media')) $options['media'] = post('media');

		$user = post('user');
		$pass = post('pass');

		// Validation of print job is done _before_ uploadning the document to the server!    - Great!
		$print_job = new PrintJob(CHALMERS_SSH_FILENAME, $printer, $copies, $options);

		$ssh = new SSHConnection($user, $pass);

		$ssh->ssh_exec("mkdir -p " . dirname(CHALMERS_SSH_FILENAME));
		$ssh->upload($filename, CHALMERS_SSH_FILENAME);

		// The environment variable CUPS_GSSSERVICENAME=HTTP must be set, othewise kerberos throws unauthorized
		$ssh->ssh_exec($print_job);

		increment_printer(post('printer'));

		$notice = "Din fil är utskriven!";

	} catch (BadLoginException $ble) {
		$errors[] = $ble->getMessage();
		log_to_file("bad cid/pass", 100, $user);

	} catch (PrintException $e) {
		$errors[] = $e->getMessage();

	} finally {
		@unlink($filename);
	}

}
