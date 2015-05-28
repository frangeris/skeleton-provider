<?php namespace Provider\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('provider:new')
            ->setDescription('Scaffolding for generate a provider class')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	// func for EOF
        $fn = function ($func) {
            return $func;
        };

        $helper = $this->getHelper('question');
        $question = new Question("Insert the name for new provider: ");
        $answer = $helper->ask($input, $output, $question);

        $id = $entity = null;
        if ($answer) {
            $classCode = <<<EOF
<?php

namespace Provider\Collection\\{$fn(ucfirst($answer))};

use Skeleton\SDK\Common\Supplier\ISupplier;
use Skeleton\SDK\Common\Client;
use Skeleton\SDK\Providers\AbstractProvider;

/**
 * Provider for resource /{$fn(strtolower($answer))}
 */
class {$fn(ucfirst($answer))}Provider extends AbstractProvider implements ISupplier
{
    /**
     * Create new resource
     *
     * @param object|array $entity New resource data to create
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function create(\$entity)
    {
        return \$this->skeleton->post('/{$fn(strtolower($answer))}', \$entity);
    }

    /**
     * Get all from resource
     *
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function read()
    {
        return \$this->skeleton->get('/{$fn(strtolower($answer))}');
    }

    /**
     * Update the resource
     *
     * @param string $id Resource Id
     * @param object|array $entity Data to update
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function update(\$id, \$entity)
    {
        return \$this->skeleton->put('/{$fn(strtolower($answer))}/'.\$id, \$entity);
    }

    /**
     * Delete a resource
     *
     * @param string $id Resource id
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function delete(\$id)
    {
        return \$this->skeleton->delete('/{$fn(strtolower($answer))}/'.\$id);
    }

    /**
     * Get a resource by id
     *
     * @param string $id Resource id
     * @return GuzzleHttp\Message\Response  Response from guzzle
     */
    public function getById(\$id)
    {
        return \$this->skeleton->get('/{$fn(strtolower($answer))}/'.\$id);
    }
}
EOF;
		// Determinate the directory
        $file = __DIR__.'/../collection/'.strtolower($answer).'/'.ucfirst($answer).'Provider.php';
        $directory = dirname($file);

        // Create directories
        $dirs = [
        	$directory,
        	$directory.'/enum',
        	$directory.'/exceptions',
        ];
        array_walk($dirs, function($dir) use ($output){
        	if (!file_exists($dir))
        	{
        		mkdir($dir, 0777, true);
				touch($dir.'/.gitkeep');
        	} else
        		$output->writeln('<error>Error: unable to create '.$dir.', already exist</error>');
        });

        // Create the file .php
        $fp = fopen($file, 'w');
        fwrite($fp, $classCode);
        fclose($fp);

		// success
		$output->writeln('<info>Scaffold Provider\Collection\\'.ucfirst($answer).'Provider.php was created succesful</info>');

        } else {
            return;
        }
    }
}