<?php 
class logger {
	private static $logTarget = 'logs/all.log';
	private static $fileHandle = "";
	
	public function __construct() {
			self::$fileHandle = fopen(self::$logTarget, 'a');
			$this->write("FileHandle opened");
	}
	
	public function write($string) {
		$rc = fwrite(self::$fileHandle, date("Y/m/d")." ".date("H:i")." -> ".$string."\n");
	}

	public function __descruct() {
		$this->write("FileHandle closed");
		fclose(self::$fileHandle);
	}
}
?>
