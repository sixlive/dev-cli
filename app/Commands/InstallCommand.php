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
    protected $signature = 'install {--f|force}';

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
        $configPath = sprintf(
            '%s/.config/dev-cli/default.php',
            getenv("HOME")
        );

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

    private function defaultConfig(): string
    {
        return <<< 'EOT'
        <?php

        $this->env('default', function () {
            $this->action('start', function () {
                $this->task('Valet', 'valet start');
                $this->task('DBngin', 'open /Applications/DBngin.app');
            });


            $this->action('stop', function () {
                $this->task('Valet', 'valet stop');
                $this->task('DBngin', 'open /Applications/DBngin.app');
            });
        });

        EOT;
    }
}
