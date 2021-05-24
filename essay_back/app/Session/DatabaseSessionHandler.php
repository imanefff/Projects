<?php

namespace App\Session;
use Session;

class DatabaseSessionHandler extends \Illuminate\Session\DatabaseSessionHandler
{
    /**
     * {@inheritDoc}
     */
    public function write($sessionId, $data)
    {
         function get_ip(){
            if(isset($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            }
            elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            else{
                isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

            }
             }

        $user_id = (auth()->check()) ? auth()->user()->id : null;
       

        if ($this->exists) {

            $this->getQuery()->where('id', $sessionId)->update([
                'payload' => base64_encode($data), 'user_id' => $user_id,'last_activity' => time(), 
                'request_last_url' => $_SERVER['REQUEST_URI'], 'ip_address' => get_ip(),
                'lastest_activity' => date('Y:m:d H:i:s', $_SERVER['REQUEST_TIME']),
                
                // 'time_spend' => (\App\Session::find($sessionId)->begin_activity)->diffInMinutes(date('Y:m:d H:i:s', $_SERVER['REQUEST_TIME'])),
               

            ]);
        } else {
            $this->getQuery()->insert([
                 'id' => $sessionId, 'payload' => base64_encode($data),
                 'last_activity' => time(), 
                 'user_id' => $user_id,
                'request_last_url' => $_SERVER['REQUEST_URI'], 'ip_address' => get_ip(),
                'lastest_activity' => date('Y:m:d H:i:s', $_SERVER['REQUEST_TIME']),
                 'begin_activity' => date('Y:m:d H:i:s', $_SERVER['REQUEST_TIME']), 
                 'http_refered' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                 'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                  'country' => @unserialize(file_get_contents('http://ip-api.com/php/'.get_ip()))['country'],
            ]);
        }

        $this->exists = true;
    }
}