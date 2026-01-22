<?php
require_once APP_ROOT . '/core/Database.php';
class Mailer {
    public static function parseTemplate($template, $data) {
        $vars = [
            '[tesista]' => $data['tesista'] ?? '',
            '[titulo]' => $data['titulo'] ?? '',
            '[fecha]' => date('d/m/Y'),
            '[jurados]' => $data['jurados_lista'] ?? '',
            '[asesor]' => $data['asesor'] ?? '',
            '[facultad]' => $data['facultad'] ?? '',
            '[estado]' => $data['estado'] ?? '',
            '[resultado]' => $data['resultado'] ?? ''
        ];
        return str_replace(array_keys($vars), array_values($vars), $template);
    }
    public static function enviar($to, $subject, $body) {
        $db = (new Database())->connect();
        $conf = $db->query("SELECT * FROM config_email WHERE id=1")->fetch(PDO::FETCH_ASSOC);
        
        $senderName = $conf['sender_name'] ?? 'Sistema NADIA';
        $senderEmail = $conf['sender_email'] ?? 'noreply@nadia.edu.pe';
        
        // MODO 1: SMTP OFF - Usar mail() nativo (sendmail/gsmtp)
        if (empty($conf['smtp_active']) || $conf['smtp_active'] == 0) {
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: $senderName <$senderEmail>\r\n";
            $headers .= "Reply-To: $senderEmail\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            return mail($to, $subject, $body, $headers);
        }
        
        // MODO 2: SMTP ON - Cliente SMTP manual
        try {
            $host = $conf['smtp_server'];
            $port = $conf['smtp_port'];
            $secure = $conf['smtp_secure'] ?? 'ssl';
            $username = $conf['smtp_user'];
            $password = $conf['smtp_pass'];
            $transport = ($secure == 'ssl') ? 'ssl://' : ''; 
            $socket = fsockopen($transport . $host, $port, $errno, $errstr, 10);
            
            if (!$socket) throw new Exception("Error conexión SMTP: $errstr");
            $read = function($s) { 
                $data = ""; 
                while($str = fgets($s, 515)) { 
                    $data .= $str; 
                    if(substr($str,3,1) == " ") break; 
                } 
                return $data; 
            };
            
            $cmd = function($s, $c) use ($read) { 
                fputs($s, $c . "\r\n"); 
                return $read($s); 
            };
            $read($socket); // Banner
            $cmd($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'nadia'));
            
            if ($secure == 'tls') {
                $cmd($socket, "STARTTLS");
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $cmd($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'nadia'));
            }
            if (!empty($username) && !empty($password)) {
                $cmd($socket, "AUTH LOGIN");
                $cmd($socket, base64_encode($username));
                $cmd($socket, base64_encode($password));
            }
            $cmd($socket, "MAIL FROM: <$senderEmail>");
            $cmd($socket, "RCPT TO: <$to>");
            $cmd($socket, "DATA");
            $message = "MIME-Version: 1.0\r\n";
            $message .= "Content-type: text/html; charset=utf-8\r\n";
            $message .= "From: $senderName <$senderEmail>\r\n";
            $message .= "To: <$to>\r\n";
            $message .= "Subject: $subject\r\n";
            $message .= "\r\n";
            $message .= $body . "\r\n";
            $message .= ".";
            $result = $cmd($socket, $message);
            $cmd($socket, "QUIT");
            fclose($socket);
            return (strpos($result, '250') !== false);
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Alias para compatibilidad
    public static function send($to, $subject, $body, $db = null) {
        return self::enviar($to, $subject, $body);
    }
}
