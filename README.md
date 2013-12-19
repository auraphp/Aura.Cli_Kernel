# Aura.Cli_Kernel

Unlike Aura library packages, this Kernel package is *not* intended for
independent use. It exists so it can be embedded in an [Aura.Cli_Project][]
created via Composer.  Please see the [Aura.Cli_Project][] repository for
more information.

Because this is not an independent package, you cannot run its integration
tests directly. To run the tests:

1. Install [Aura.Cli_Project][].

2. Go to the `vendor/aura/cli-project/tests` directory.

3. Issue `phpunit` to run the kernel integration tests within the project.

[Aura.Cli_Project]: https://github.com/auraphp/Aura.Cli_Project
