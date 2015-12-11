<?php

class FileHelper extends CFileHelper {
	
	/**
	 * 遍历指定目录及子目录下的文件，返回所有与匹配模式符合的文件名
	 *
	 * @param string $dir
	 * @param string $pattern
	 *
	 * @return array
	 */
	public static function recursionGlob($dir, $pattern) {
		$dir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
		$files = array();
		
		// 遍历目录，删除所有文件和子目录
		$dh = opendir($dir);
		if (!$dh)
			return $files;
		
		$items = (array) glob($dir . $pattern);
		foreach ($items as $item) {
			if (is_file($item))
				$files[] = $item;
		}
		
		while (($file = readdir($dh))) {
			if ($file == '.' || $file == '..')
				continue;
			
			$path = $dir . $file;
			if (is_dir($path)) {
				$files = array_merge($files,
						self::recursionGlob($path, $pattern));
			}
		}
		closedir($dh);
		
		return $files;
	}
	
	/**
	 * 创建一个目录树，失败抛出异常
	 *
	 * 用法：
	 * @code php
	 * Helper_Filesys::mkdirs('/top/second/3rd');
	 * @endcode
	 *
	 * @param string $dir 要创建的目录
	 * @param int $mode 新建目录的权限
	 *
	 * @throw Q_CreateDirFailedException
	 */
	public static function mkdirs($dir, $mode = 0777) {
		if (!is_dir($dir)) {
			$ret = @mkdir($dir, $mode, true);
			if (!$ret) {
				throw new Exception($dir);
			}
		}
		return true;
	}
	
	/**
	 * 删除指定目录及其下的所有文件和子目录，失败抛出异常
	 *
	 * 用法：
	 * @code php
	 * // 删除 my_dir 目录及其下的所有文件和子目录
	 * Helper_Filesys::rmdirs('/path/to/my_dir');
	 * @endcode
	 *
	 * 注意：使用该函数要非常非常小心，避免意外删除重要文件。
	 *
	 * @param string $dir 要删除的目录
	 *
	 * @throw Q_RemoveDirFailedException
	 */
	public static function rmdirs($dir) {
		$dir = realpath($dir);
		if ($dir == '' || $dir == '/'
				|| (strlen($dir) == 3 && substr($dir, 1) == ':\\')) {
			// 禁止删除根目录
			throw new Exception('"' . $dir . '" is not allowed to be delete');
		}
		
		// 遍历目录，删除所有文件和子目录
		if (false !== ($dh = opendir($dir))) {
			while (false !== ($file = readdir($dh))) {
				if ($file == '.' || $file == '..') {
					continue;
				}
				
				$path = $dir . DIRECTORY_SEPARATOR . $file;
				if (is_dir($path)) {
					self::rmdirs($path);
				} else {
					unlink($path);
				}
			}
			closedir($dh);
			if (@rmdir($dir) == false) {
				throw new Exception('"' . $dir . '" is can not be delete');
			}
		} else {
			throw new Exception('"' . $dir . '" is can not be open');
		}
	}
	
	public static function getFileExt($fileName) {
		return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	}
}
