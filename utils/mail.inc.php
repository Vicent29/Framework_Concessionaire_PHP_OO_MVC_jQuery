<?php
    class mail {
        public static function send_email($e_values) {
            switch ($e_values['type']) {
                case 'contact';
                    $e_values['toEmail'] = 'vicentesteve2002@gmail.com';
                    $e_values['inputEmail'] = 'vicentesteve2002@gmail.com';
                    break;
                case 'validate';
                    $e_values['toEmail'] = 'vicentesteve2002@gmail.com';
                    $e_values['inputEmail'] = 'vicentesteve2002@gmail.com';
                    $e_values['fromEmail'] = 'vicentesteve2002@gmail.com';
                    $e_values['inputMatter'] = 'Email verification';
                    $e_values['inputMessage'] = "<h2>Email verification.</h2><a href='http://localhost/Framework_Concesionaire/?module=login&op=login_register_view&verify&$e_values[token]'>Click here for verify your email.</a>";
                    break;                                                                                                      
                case 'recover';
                    $e_values['toEmail'] = 'vicentesteve2002@gmail.com';
                    $e_values['inputEmail'] = 'vicentesteve2002@gmail.com';
                    $email['inputMatter'] = 'Recover password';
                    $email['inputMessage'] = "<a href='http://localhost/Framework_Concesionaire/?module=login&op=login_register_view&recover&$e_values[token]'>Click here for recover your password.</a>";
                    break; 
            }
            return self::send_mailgun($e_values);
        }

        public static function send_mailgun($values){
            $mailgun = parse_ini_file(MODEL_PATH . "mailgun.ini");
            $api_key = $mailgun['api_key'];
            $api_url = $mailgun['api_url'];  

            $config = array();
            $config['api_key'] = $api_key; 
            $config['api_url'] = $api_url;

            $message = array();
            $message['from'] = $values['fromEmail'];
            $message['to'] = 'vicentesteve2002@gmail.com';
            $message['h:Reply-To'] = $values['inputEmail'];
            $message['subject'] = $values['inputMatter'];
            $message['html'] = $values['inputMessage'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $config['api_url']);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    }