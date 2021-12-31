<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class InstallCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install
        {--f|force : Force the installation}
        {--l|local : Install a configration at your current path}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'install dev-cli';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $configPath = $this->getConfigPath();

        $configDirectory = pathinfo($configPath, PATHINFO_DIRNAME);

        if (!is_dir($configDirectory)) {
            $this->task('Create config directory',
                fn () => mkdir($configDirectory, 0777, true)
            );
        }

        if ($this->option('force') && is_file($configPath)) {
            $this->task('Delete default config',
                fn () => unlink($configPath)
            );
        }

        if (!is_file($configPath)) {
            $this->task('Create default config',
                fn () => file_put_contents($configPath, $this->defaultConfig())
            );
        }
    }

    private function getConfigPath(): string
    {
        if ($this->option('local')) {
            return sprintf(
                '%s/.dev-cli.php',
                getenv('PWD')
            );
        }

        return sprintf(
            '%s/.config/dev-cli/default.php',
            getenv('HOME')
        );
    }

    private function defaultConfig(): string
    {
        return $this->option('local')
            ? $this->localConfig()
            : $this->globalConfig();
    }

    private function globalConfig(): string
    {
        return <<< 'EOT'
        <?php

        $this->env('default', function () {
            $this->action('start', function () {
                $this->description('Start the default dev env');
                $this->task('Valet', 'valet start');
                $this->task('Open DBngin', 'open /Applications/DBngin.app');
            });


            $this->action('stop', function () {
                $this->description('Stop the default dev env');
                $this->task('Valet', 'valet stop');
                $this->task('Open DBngin', 'open /Applications/DBngin.app');
            });
        });

        EOT;

    }

    private function localConfig(): string
    {
        return <<< 'EOT'
        <?php

        $this->env(basename(getenv('PWD')), function () {
            $this->action('start', function () {
                $this->description('Start the default dev env');
                $this->task('Valet', 'valet start');
                $this->task('Open DBngin', 'open /Applications/DBngin.app');
            });


            $this->action('stop', function () {
                $this->description('Stop the default dev env');
                $this->task('Valet', 'valet stop');
                $this->task('Open DBngin', 'open /Applications/DBngin.app');
            });
        });

        EOT;

    }
}
