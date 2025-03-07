<?php
class FtpService {
    private $ftpServer;
    private $ftpUser;
    private $ftpPass;
    private $connId;

    public function __construct() {
        // Charger les variables d'environnement avec des valeurs par défaut
        $this->ftpServer = getenv('FTP_SERVER') ?: 'ftp.smarttech.sn';
        $this->ftpUser = getenv('FTP_USER') ?: 'ftpuser';
        $this->ftpPass = getenv('FTP_PASS') ?: '123';
    }

    private function connect() {
        try {
            $this->connId = ftp_connect($this->ftpServer, 21, 30);
            if (!$this->connId) {
                throw new Exception("❌ Erreur : Impossible de se connecter au serveur FTP.");
            }

            $loginResult = ftp_login($this->connId, $this->ftpUser, $this->ftpPass);
            if (!$loginResult) {
                throw new Exception("❌ Erreur : Identifiants FTP incorrects.");
            }

            ftp_pasv($this->connId, true); // Mode passif
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function uploadFile($localFile, $remoteFile) {
        try {
            $this->connect();

            if (!file_exists($localFile)) {
                throw new Exception("❌ Erreur : Le fichier '$localFile' est introuvable.");
            }

            $remoteDir = dirname($remoteFile);
            if (!$this->ensureRemoteDirExists($remoteDir)) {
                throw new Exception("❌ Erreur : Impossible de créer le dossier '$remoteDir' sur le serveur FTP.");
            }

            if (ftp_put($this->connId, $remoteFile, $localFile, FTP_BINARY)) {
                ftp_close($this->connId);
                return ["success" => true, "message" => "✅ Fichier '$remoteFile' uploadé avec succès."];
            } else {
                throw new Exception("❌ Erreur : Échec du transfert de '$localFile' vers '$remoteFile'.");
            }
        } catch (Exception $e) {
            if ($this->connId) {
                ftp_close($this->connId);
            }
            error_log($e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function deleteFile($remoteFile) {
        try {
            $this->connect();

            if (ftp_delete($this->connId, $remoteFile)) {
                ftp_close($this->connId);
                return ["success" => true, "message" => "✅ Fichier '$remoteFile' supprimé avec succès."];
            } else {
                throw new Exception("❌ Erreur : Échec de la suppression du fichier '$remoteFile'.");
            }
        } catch (Exception $e) {
            if ($this->connId) {
                ftp_close($this->connId);
            }
            error_log($e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    private function ensureRemoteDirExists($dir) {
        $parts = explode("/", $dir);
        $path = "";

        foreach ($parts as $part) {
            $path .= "/$part";
            if (!@ftp_chdir($this->connId, $path)) {
                if (!ftp_mkdir($this->connId, $path)) {
                    return false;
                }
            }
        }
        return true;
    }
}
?>
