<?php
/**
 * DocBlockGenerator
 *
 * Generate PHP Docblock placeholders for files/folders.
 *
 * > php docblock.php file|folder [-r] [targetFunction]
 * | file or folder  | the file or folder you want to docblock (php files)     |
 * | -r              | optional, recursively go through a folder               |
 * | targetFunction  | optional, docblock only a specific method/function name |
 *
 * Examples:
 * > php docblock.php target.php targetFunction
 * > php docblock.php target/dir -r
 *
 * Copied from
 * @version   0.85 (2010-06-16)
 * @link      https://github.com/agentile/PHP-Docblock-Generator
 *
 * @version   0.86 (2014-05-19)
 * @link      https://github.com/mbrowniebytes/PHP-Docblock-Generator
 */
class DocBlockGenerator {

    public $exts = array('.php', '.php4', '.php5', '.phps', '.inc');
	public $newline = "\n"; 		  // typically \r\n windows; \n *nix
	public $add_anonymous = false;    // true, add docblock for anonymous functions; false, skip
	public $add_full = false; 		  // true, add all docblock params; false, add minimal
	public $description_placeholder = "Insert description here";  // text, if any, to add as placeholder for description

    public $target;
    public $target_function;
    public $recursive;

    public $file_contents;
    public $log = array();

	private $nbr_dockblocks = 0;


    /**
     * __construct
     *
     * @param $target
     * @param $target_function
     * @param $recursive
     *
     * @return void
     *
     * @access public
     * @static
     * @since 0.85
     */
    public function __construct($target, $target_function = null, $recursive = false)
    {
        $this->target = $target;
        $this->target_function = $target_function;
        $this->recursive = $recursive;
    }

    /**
     * result
     * Print output to command line
     *
     *
     * @return string
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function result()
    {
        $str = '';
        foreach ($this->log as $log_item) {
            $str .= "{$log_item}\n";
        }
        echo $str;
    }

    /**
     * start
     * Begin the docblocking process, determine if a file or folder was given
     *
     * @return void
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function start()
    {
        if (is_file($this->target)) {
            $valid_file = $this->fileCheck($this->target);
            if ($valid_file == false) {
                return;
            }
            $this->fileDocBlock();
        } elseif (is_dir($this->target)) {
            if ($this->recursive == true) {
                $files = $this->scanDirectories($this->target, true);
            } else {
                $files = $this->scanDirectories($this->target);
            }
            foreach ($files as $file) {
                $this->target = $file;
                $this->fileDocBlock();
            }
        } else {
            $this->log[] = 'This is not a file or folder.';
            return;
        }
    }

    /**
     * fileCheck
     * Make sure we can deal with the target file
     *
     * @param $target
     *
     * @return bool
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function fileCheck($target)
    {
        $file_ext = strtolower(substr($target, strrpos($target, '.')));
        $bool = true;
        if (!in_array($file_ext, $this->exts)) {
            $this->log[] = "{$target} is not a PHP file.";
            $bool = false;
        }
        if (!is_readable($target)) {
            $this->log[] = "{$target} is not readable.";
            $bool = false;
        }
        if (!is_writable($target)) {
            $this->log[] = "{$target} is not writeable.\nCheck file permissions";
            $bool = false;
        }
        return $bool;
    }

    /**
     * fileDocBlock
     * Shell method for docblock operations, explodes file, performs docblock methods, impodes.
     *
     * @return void
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function fileDocBlock()
    {
		$this->nbr_dockblocks = 0;
        $this->file_contents = file_get_contents($this->target);
        list($funcs, $classes) = $this->getProtos();
        $handle = fopen($this->target, 'r');
        if ($contents = fread($handle, filesize($this->target))) {
            $contents = explode($this->newline, $contents);
            $contents = $this->docBlock($contents, $funcs, $classes, $this->target_function);
            $contents = implode($this->newline, $contents);
            fclose($handle);
			if ($this->nbr_dockblocks == 0) {
				$this->log[] = "{$this->target} Nothing to Doc Block";
			} else {
				$handle = fopen($this->target, 'w');
				if (fwrite($handle, $contents)) {
					$this->log[] = "{$this->target} Doc Blocked ".$this->nbr_dockblocks;
					fclose($handle);
					return;
				} else {
					fclose($handle);
					$this->log[] = "Could not write new content.\nCheck Permissions";
					return;
				}
			}
        } else {
            fclose($handle);
            $this->log[] = "Could not get file contents.\nCheck Permissions";
            return;
        }
    }

    /**
     * getProtos
     * This function goes through the tokens to gather the arrays of information we need
     *
     * @return array
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function getProtos()
    {
        $tokens = token_get_all($this->file_contents);
        $funcs = array();
        $classes = array();
        $curr_class = '';
        $class_depth = 0;
        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            if (is_array($tokens[$i]) && $tokens[$i][0] == T_CLASS) {
                $line = $tokens[$i][2];
                ++$i; // whitespace;
                $curr_class = $tokens[++$i][1];
                if (!in_array(array('line' => $line, 'name' => $curr_class), $classes)) {
                    $classes[] = array('line' => $line, 'name' => $curr_class);
                }
                while ($tokens[++$i] != '{') {}
                ++$i;
                $class_depth = 1;
                continue;
            } elseif (is_array($tokens[$i]) && $tokens[$i][0] == T_FUNCTION) {
                $next_by_ref = FALSE;
                $this_func = array();

                while ($tokens[++$i] != ')') {
                    if (is_array($tokens[$i]) && $tokens[$i][0] != T_WHITESPACE) {
                        if (!$this_func) {
							if (empty($tokens[$i][1])) {
								// no name
								continue;
							} else if (substr($tokens[$i][1], 0, 1) == '$') {
								// anonymous function
								if (!$this->add_anonymous) {
									continue;
								} else {
									// go back and try to find php function being used in
									for ($j = $i; $i - $j < 10; $j--) {
										if (is_array($tokens[$j]) && $tokens[$j][0] == T_STRING) {
											$tokens[$i][1] = $tokens[$j][1];
											break;
										}
									}
								}
							}
                            $this_func = array(
                                'name' => $tokens[$i][1],
                                'class' => $curr_class,
                                'line' => $tokens[$i][2],
                            );
                        } else {
                            $this_func['params'][] = array(
                                'byRef' => $next_by_ref,
                                'name' => $tokens[$i][1],
                            );
                            $next_by_ref = FALSE;
                        }
                    } elseif ($tokens[$i] == '&') {
                        $next_by_ref = TRUE;
                    } elseif ($tokens[$i] == '=') {
                        while (!in_array($tokens[++$i], array(')', ','))) {
							// default may be a negative (-) number
                            if ($tokens[$i][0] != T_WHITESPACE && $tokens[$i][0] != '-') {
                                break;
                            }
                        }
                       	$this_func['params'][count($this_func['params']) - 1]['default'] = $tokens[$i][1];
                    }
                }
				if (!empty($this_func)) {
                	$funcs[] = $this_func;
				}
            } elseif ($tokens[$i] == '{' || $tokens[$i] == 'T_CURLY_OPEN' || $tokens[$i] == 'T_DOLLAR_OPEN_CURLY_BRACES') {
                ++$class_depth;
            } elseif ($tokens[$i] == '}') {
                --$class_depth;
            }

            if ($class_depth == 0) {
                $curr_class = '';
            }
        }

        return array($funcs, $classes);
    }

    /**
     * docBlock
     * Main docblock function, determines if class or function docblocking is need and calls
     * appropriate subfunction.
     *
     * @param $arr
     * @param $funcs
     * @param $classes
     * @param $target_function
     *
     * @return array
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function docBlock($arr, $funcs, $classes, $target_function)
    {
        $func_lines = array();
        foreach ($funcs as $func) {
            $func_lines[] = $func['line'];
        }
        $class_lines = array();
        foreach ($classes as $class) {
            $class_lines[] = $class['line'];
        }
        $class_or_func = '';
        $count = count($arr);
        for($i = 0; $i < $count; $i++) {
            $line = $i + 1;
            $code = $arr[$i];

            if (in_array($line, $class_lines) && !$this->docBlockExists($arr[($i - 1)])) {
                $class_or_func = 'class';
            } elseif (in_array($line, $func_lines) && !$this->docBlockExists($arr[($i - 1)])) {
                $class_or_func = 'func';
            } else {
                continue;
            }

            if ($class_or_func === 'func') {
                $data = $this->getData($line, $funcs);
            } elseif ($class_or_func === 'class') {
                $data = $this->getData($line, $classes);
            }
            if ($target_function !== null && $target_function !== '') {
                if ($data['name'] !== $target_function) {
                    continue;
                }
            }
			$indent = $this->getStrIndent($code);
            if ($class_or_func === 'func') {
                $doc_block = $this->functionDocBlock($indent, $data);
            } elseif ($class_or_func === 'class') {
                $doc_block = $this->classDocBlock($indent, $data);
            }
			$this->nbr_dockblocks++;
            $arr[$i] = $doc_block . $arr[$i];
        }
        return $arr;
    }

    /**
     * scanDirectories
     * Get all specific files from a directory and if recursive, subdirectories
     *
     * @param $dir
     * @param $recursive
     * @param $data
     *
     * @return array
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function scanDirectories($dir, $recursive = false, $data = array())
    {
        // set filenames invisible if you want
        $invisible = array('.', '..', '.htaccess', '.htpasswd');
        // run through content of root directory
        $dir_content = scandir($dir);
        foreach ($dir_content as $key => $content) {
            // filter all files not accessible
            $path = $dir . '/' . $content;
            if (!in_array($content, $invisible)) {
                // if content is file & readable, add to array
                if (is_file($path) && is_readable($path)) {
                    // what is the ext of this file
                    $file_ext = strtolower(substr($path, strrpos($path, ".")));
                    // if this file ext matches the ones from our array
                    if (in_array($file_ext, $this->exts)) {
                        // save file name with path
                        $data[] = $path;
                    }
                    // if content is a directory and readable, add path and name
                } elseif (is_dir($path) && is_readable($path)) {
                    // recursive callback to open new directory
                    if ($recursive == true) {
                        $data = $this->scanDirectories($path, true, $data);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * getData
     * Retrieve method or class information from our arrays
     *
     * @param $line
     * @param $arr
     *
     * @return mixed
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function getData($line, $arr)
    {
        foreach ($arr as $k => $v) {
            if ($line == $v['line']) {
                return $arr[$k];
            }
        }
        return false;
    }

    /**
     * docBlockExists
     * Primitive check to see if docblock already exists
     *
     * @param $line
     *
     * @return bool
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function docBlockExists($line)
    {
        // ok we are simply going to check the line above the function and look for */
        // TODO: make this a more accurate check.
		$line = ltrim($line);
        $len = strlen($line);
        if ($len == 0) {
            return false;
        }
        $asterik = false;
        for ($i = 0; $i < $len; $i++) {
            if ($line[$i] == '*') {
                $asterik = true;
            } elseif ($line[$i] == '/' && $asterik == true) {
                return true;
            } else {
                $asterik = false;
            }
        }
        return false;
    }

    /**
     * functionDocBlock
     * Docblock for function
     *
     * @param $indent
     * @param $data
     *
     * @return string
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function functionDocBlock($indent, $data)
    {
        $doc_block = $indent."/**".$this->newline;
        $doc_block .= $indent." * {$data['name']}".$this->newline;
		if (!empty($this->description_placeholder)) {
			$doc_block .= $indent." * ".$this->description_placeholder.$this->newline;
		}
        $doc_block .= $indent." *".$this->newline;
        $doc_block .= $indent." *".$this->newline;
        if (isset($data['params'])) {
            foreach($data['params'] as $func_param) {
                $doc_block .= $indent." * @param {$func_param['name']}".$this->newline;
            }
			$doc_block .= $indent." *".$this->newline;
        }
        $doc_block .= $indent." * @return".$this->newline;
		if ($this->add_full) {
			$doc_block .= $indent." *".$this->newline;
			$doc_block .= $indent." * @access".$this->newline;
			$doc_block .= $indent." * @static".$this->newline;
			$doc_block .= $indent." * @see".$this->newline;
			$doc_block .= $indent." * @since".$this->newline;
		}
        $doc_block .= $indent." */".$this->newline;

        return $doc_block;
    }

    /**
     * classDocBlock
     * Docblock for class
     *
     * @param $indent
     * @param $data
     *
     * @return string
     *
     * @access public
     * @static
     * @since  0.85
     */
    public function classDocBlock($indent, $data)
    {
        $doc_block = $indent."/**".$this->newline;
        $doc_block .= $indent." * {$data['name']}".$this->newline;
		if (!empty($this->description_placeholder)) {
			$doc_block .= $indent." * ".$this->description_placeholder.$this->newline;
		}
        $doc_block .= $indent." *".$this->newline;
        $doc_block .= $indent." *".$this->newline;
		if ($this->add_full) {
			$doc_block .= $indent." * @category".$this->newline;
			$doc_block .= $indent." * @package".$this->newline;
			$doc_block .= $indent." * @author".$this->newline;
			$doc_block .= $indent." * @copyright".$this->newline;
			$doc_block .= $indent." * @license".$this->newline;
			$doc_block .= $indent." * @version".$this->newline;
			$doc_block .= $indent." * @link".$this->newline;
			$doc_block .= $indent." * @see".$this->newline;
			$doc_block .= $indent." * @since".$this->newline;
		}
        $doc_block .= $indent." */".$this->newline;

        return $doc_block;
    }

    /**
     * getStrIndent
     * Returns indentation of a string
     *
     * @param $str
     * @param $count
     *
     * @return int
     *
     * @access public
     * @static
     * @since  0.86
     */
    public function getStrIndent($str)
    {
    	// preserve tabs and spaces
		if (preg_match('/^(\s+)/', $str, $matches)) {
			$indent = $matches[1];
		} else {
			$indent = '';
		}
		return $indent;
    }

}

$argv = empty($_SERVER['argv']) ? array(0 => '') : $_SERVER['argv'];

$current_dir = getcwd();

$options = array(
    'file_folder' => '',
    'target_function' => '',
    'recursive' => false
);

foreach ($argv as $k => $arg) {
    if ($k !== 0) {
        if (strtolower($arg) === '-r') {
            $options['recursive'] = true;
        } elseif (is_file($arg)) {
            $options['file_folder'] = $arg;
        } elseif (is_file($current_dir . '/' . $arg)) {
            $options['file_folder'] = $current_dir . '/' . $arg;
        } elseif (is_dir($arg)) {
            $options['file_folder'] = $arg;
        } elseif (is_dir($current_dir . '/' . $arg)) {
            $options['file_folder'] = $current_dir . '/' . $arg;
        } else {
            $options['target_function'] = $arg;
        }
    }
}

if (isset($argv[1])) {
    if (is_file($options['file_folder']) || is_dir($options['file_folder'])) {
        $doc_block_generator = new DocBlockGenerator($options['file_folder'], $options['target_function'], $options['recursive']);
        $doc_block_generator->start();
        $doc_block_generator->result();
    } else {
        die("\nThis is not a valid file or directory\n");
    }

} else {
    die("\nPlease provide a file or directory as a parameter\n");
}
