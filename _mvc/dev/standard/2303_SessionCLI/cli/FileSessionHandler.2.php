<?php

namespace Concerto\standard;

class FileSessionHandler implements \SessionHandlerInterface
{
    private $savePath;
    private $data;

    public function open($savePath, $sessionName) {
        $this->savePath = $savePath;
        if ( !is_dir($this->savePath) ) {
            mkdir($this->savePath, 0777);
        }
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        $this->data = false;
        $filename = $this->savePath.'/sess_'.$id;
        if ( file_exists($filename) ){
            $this->data = @file_get_contents($filename);
        }

        if ( $this->data === false ){
            $this->data = '';
        }
        return $this->data;
    }

    public function write($id, $data) {
        $filename = $this->savePath.'/sess_'.$id;

        // check if data has changed since first read
        if ( $data !== $this->data ) {
            // write data
            return @file_put_contents($filename, $data, LOCK_EX) === false ?
                false : true;
        }else {
            // let's not forget to postpone session garbage collection
            return @touch($filename);
        }
    }

    public function destroy($id) {
        $filename = $this->savePath.'/sess_'.$id;
        if ( file_exists($filename) ) {
            @unlink($filename);
        }
        return true;
    }

    // garbage collection, delete obsolete session files
    public function gc($maxlifetime) {
        foreach ( glob($this->savePath.'/sess_*') as $filename ) {
            if (
                filemtime($filename) + $maxlifetime < time() &&
                file_exists($filename) 
            ) {
                @unlink($filename);
            }
        }

        return true;
    }
}

