<?php

namespace Salt\Core\Commands;

use Illuminate\Console\GeneratorCommand;

class GenerateSeederClassCommand extends GeneratorCommand
{
    protected $name = 'core:generate-seeder';

    protected $description = "Generate a Seeder class";

    protected $type = "Seeder";

    protected function getStub()
    {
        $seederClassName = $this->getNameInput();

        return __DIR__ . "/../../stubs/seeders/$seederClassName.php.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\database\seeders';
    }

    public function handle()
    {
        parent::handle();

        // Get the fully qualified class name (FQN)
        $class = $this->qualifyClass($this->getNameInput());

        // get the destination path, based on the default namespace
        $path = $this->getPath($class);

        // Update the file content
        $content = file_get_contents($path);
        file_put_contents($path, $content);
    }
}
