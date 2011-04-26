<?php

  /**
   * File:     lib/fs/sno_fs_interface.php
   * Author:   Royce Stubbs
   * Purpose:  Library class that provides an easy to use file
   *           system interface with SNOctopus-specific functions
   * Use:      You do not need to instantiate this class. Simply
   *           call the methods with the static accessor, ie.
   *           sno_fs_interface::(method).          
   */


class sno_fs_interface
{

    /*
     * @params    String $directory    Directory to search 
     * @returns   Array                Array of folder names
     *
     */
    public static function directoryToArray($directory) 
    {
        $array = glob($directory.'*', GLOB_ONLYDIR);
        $dirName = array();
        
        foreach ($array as $dir) {
            $dirName[] = str_replace($directory, "", $dir);
        }
        
        return $dirName;
    }

}
