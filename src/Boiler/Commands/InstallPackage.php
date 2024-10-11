<?php

namespace YonisSavary\Sharp\Extensions\Boiler\Commands;

use YonisSavary\Sharp\Classes\CLI\Args;
use YonisSavary\Sharp\Classes\CLI\Command;
use YonisSavary\Sharp\Classes\CLI\Terminal;
use YonisSavary\Sharp\Classes\Data\ObjectArray;
use YonisSavary\Sharp\Classes\Env\Storage;
use YonisSavary\Sharp\Core\Utils;
use YonisSavary\Sharp\Extensions\Boiler\Classes\InstallPackagePolicy;

class InstallPackage extends Command
{
    const REL_PATH = "vendor/yonis-savary/sharp-extensions/src/Boiler/Packages";

    public function getHelp(): string
    {
        return "From ".self::REL_PATH.", install boilerplates inside of any application";
    }

    public function listAvailablesModules()
    {
        echo "This command need module name(s)\n";
        echo "Availables modules :\n";

        $storage = new Storage(Utils::relativePath(self::REL_PATH));
        foreach ($storage->listDirectories() as $directory)
            echo " - " . basename($directory) . "\n";
        echo "\n";
    }

    protected function installPackage(string $package, string $application, InstallPackagePolicy $policy)
    {
        $package = ucfirst($package);
        $packagePath = Utils::joinPath(self::REL_PATH, $package);
        $packageNamespace = Utils::pathToNamespace($packagePath);

        $path = Utils::relativePath($packagePath);
        $namespace = Utils::pathToNamespace($path);

        if (!is_dir($path))
            return print("Package not found [$path]\n");

        $storage = new Storage($path);
        $files = $storage->exploreDirectory("/", Storage::ONLY_FILES);

        if (!$policy->allowOverwrite)
        {
            $gotError = false;
            foreach ($files as $file)
            {
                $relPath = str_replace($storage->getRoot(), "", $file);
                if (str_starts_with($relPath, "/"))
                    $relPath = substr($relPath, 1);

                $distPath = Utils::relativePath($relPath, $application);
                if (!is_file($distPath))
                    continue;

                $gotError = true;
                echo "Already existing file [$distPath]\n";
            }
            if ($gotError)
            {
                print("Could not install module [$namespace]\n");
                return print("Overwrite policy set to false: use --overwrite or -o to replace old files\n");
            }
        }


        echo "Installing [$namespace] in [$application]\n";
        foreach ($files as $file)
        {
            $relPath = str_replace($storage->getRoot(), "", $file);
            if (str_starts_with($relPath, "/"))
                $relPath = substr($relPath, 1);

            $distPath = Utils::relativePath(Utils::joinPath($application, $relPath));

            $content = file_get_contents($file);
            $content = str_replace($packageNamespace, $application, $content);

            echo "Writing [$distPath]\n";

            $distDirectory = dirname($distPath);
            if (!is_dir($distDirectory))
                mkdir($distDirectory, recursive:true);


            file_put_contents($distPath, $content);
        }
    }

    public function __invoke(Args $args)
    {
        $toInstall = ObjectArray::fromArray($args->values())->filter()->collect();

        if (!count($toInstall))
            return $this->listAvailablesModules();

        $application = Terminal::chooseApplication();

        $policy = new InstallPackagePolicy(
            $args->isPresent("o", "overwrite")
        );

        foreach ($toInstall as $package)
            $this->installPackage($package, $application, $policy);
    }
}