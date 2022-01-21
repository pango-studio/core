<?php

namespace Salt\Core\Commands;

use Illuminate\Console\GeneratorCommand;

class GenerateOptionsClassCommand extends GeneratorCommand
{
    protected $name = 'core:generate-options';

    protected $description = "Generate an Options class";

    protected $type = "Options";

    protected function getStub()
    {
        $optionsClassName = $this->getNameInput();

        return __DIR__ . "/../../stubs/options/$optionsClassName.php.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Options';
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
