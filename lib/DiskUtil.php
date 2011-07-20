<?php

class DiskUtil {
	public static function fileIsZip($file) 
	{
		return substr(file_get_contents($file, TRUE,null,0,3),0,2)=='PK';	
	}
    
    public static function assertPath($dir)
    { // takes full path to directory (not file)
        if(is_file
            ($dir)) {
            error_log("this is a regular file!");
           $dir = pathinfo($dir, PATHINFO_DIRNAME);
        }
        $parts = explode("/", $dir);
        $stem = "";
        while(count($parts)>0) {
            $part = array_shift($parts);
            $stem .= "$part/";
            if(!file_exists($stem)) {
                error_log("making non-existent directory:".$stem);
                mkdir($stem, 0775);
            }
        }
    }

		public static function removeDir($dir) {
		   if (is_dir($dir)) {
		     $objects = scandir($dir);
		     foreach ($objects as $object) {
		       if ($object != "." && $object != "..") {
		         if (filetype($dir."/".$object) == "dir") self::removeDir($dir."/".$object); else unlink($dir."/".$object);
		       }
		     }
		     reset($objects);
		     rmdir($dir);
		   }
		 }
		
    public static function getGroupName($file)
    {
      $oinfo = self::getGroupArray($file);
      return $oinfo['name'];      
    }
    
    
    public static function getOwnerName($file)
    {
      $oinfo = self::getOwnerArray($file);
      return $oinfo['name'];
    }
    
    public static function getGroupArray($file)
    {
      return posix_getgrgid(filegroup($file));
    }    
    
    public static function getOwnerArray($file)
    {
      return posix_getpwuid(fileowner($file));
    }    
    
	public static function makeDir($dir)
	{
		//error_log('trying to make directory:'.$dir);
		if(!file_exists($dir)) {
			// check the mode
			if(is_writable(dirname($dir))) {
				if(!mkdir($dir)) {
					throw new Exception("Could not create package directory:".$dir);	
				} else {
					chmod($dir, 0777);
				}						
			} else { // parent dir is not writeable
				if(chmod(dirname($dir),0777)) // try to chmod it
				{
					if(!mkdir($dir)) {
						throw new Exception("Could not create package directory:".$dir);	
					} else {
						chmod($dir, 0777);
					}
				} else {
					throw new Exception("Couldn't chmod dir ".$dir);
				}
			}
		} else {
			error_log("Package directory ".$dir." already exists--not going to overwrite it");
		}
	}
	
	public static function findFile($path, $regex)
	{	

		exec(escapeshellcmd("find $path -name $regex"), $output, $retval);
		if($retval==0) {
			return $output[0];
		} else {
			return false;
		}
	
	
	}
	
	public static function dir_copy($srcdir, $dstdir, $offset = '', $verbose = false)
	{
	    // A function to copy files from one directory to another one, including subdirectories and
	    // nonexisting or newer files. Function returns number of files copied.
	    // This function is PHP implementation of Windows xcopy  A:\dir1\* B:\dir2 /D /E /F /H /R /Y
	    // Syntaxis: [$returnstring =] dircopy($sourcedirectory, $destinationdirectory [, $offset] [, $verbose]);
	    // Example: $num = dircopy('A:\dir1', 'B:\dir2', 1);

	    // Original by SkyEye.  Remake by AngelKiha.
	    // Linux compatibility by marajax.
	    // Offset count added for the possibilty that it somehow miscounts your files.  This is NOT required.
	    // Remake returns an explodable string with comma differentiables, in the order of:
	    // Number copied files, Number of files which failed to copy, Total size (in bytes) of the copied files,
	    // and the files which fail to copy.  Example: 5,2,150000,\SOMEPATH\SOMEFILE.EXT|\SOMEPATH\SOMEOTHERFILE.EXT
	    // If you feel adventurous, or have an error reporting system that can log the failed copy files, they can be
	    // exploded using the | differentiable, after exploding the result string.
	    //
	    if(!isset($offset)) $offset=0;
	    $num = 0;
	    $fail = 0;
	    $sizetotal = 0;
	    $fifail = '';
			$ret = '';
	    if(!is_dir($dstdir)) mkdir($dstdir);
	    if($curdir = opendir($srcdir)) {
	        while($file = readdir($curdir)) {
	            if($file != '.' && $file != '..') {
	//                $srcfile = $srcdir . '\\' . $file;    # deleted by marajax
	//                $dstfile = $dstdir . '\\' . $file;    # deleted by marajax
	                $srcfile = $srcdir . '/' . $file;    # added by marajax
	                $dstfile = $dstdir . '/' . $file;    # added by marajax
	                if(is_file($srcfile)) {
	                    if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
	                    if($ow > 0) {
	                        if($verbose) echo "Copying '$srcfile' to '$dstfile'...<br />";
	                        if(copy($srcfile, $dstfile)) {
	                            touch($dstfile, filemtime($srcfile)); $num++;
	                            chmod($dstfile, 0777);    # added by marajax
	                            $sizetotal = ($sizetotal + filesize($dstfile));
	                            if($verbose) echo "OK\n";
	                        }
	                        else {
	                            echo "Error: File '$srcfile' could not be copied to '$dstfile'!<br />\n";
	                            $fail++;
	                            $fifail = $fifail.$srcfile.'|';
	                        }
	                    }
	                }
	                else if(is_dir($srcfile)) {
	                    $res = explode(',',$ret);
	                    $ret = self::dir_copy($srcfile, $dstfile, $verbose);
	                    $mod = explode(',',$ret);
	                    @$imp = array($res[0] + $mod[0],$mod[1] + $res[1],$mod[2] + $res[2],$mod[3].$res[3]);
	                    $ret = implode(',',$imp);
	                }
	            }
	        }
	        closedir($curdir);
	    }
	    $red = explode(',',$ret);
	    @$ret = ($num + $red[0]).','.(($fail-$offset) + $red[1]).','.($sizetotal + $red[2]).','.$fifail.$red[3];
	    return $ret;
	}

	public static function getTempDir()
	{
		// Get temporary directory
		if (!empty($_ENV['TMP'])) {
		        $tempdir = $_ENV['TMP'];
		} elseif (!empty($_ENV['TMPDIR'])) {
		        $tempdir = $_ENV['TMPDIR'];
		} elseif (!empty($_ENV['TEMP'])) {
		        $tempdir = $_ENV['TEMP'];
		} else {
		        $tempdir = dirname(tempnam('', 'na'));
		}

		if (empty($tempdir)) { error_log ('No temporary directory'); }

		return $tempdir;
	}
}



?>
