<?php
declare(strict_types=1);

namespace Tasks;

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 28.10.17
 * Time: 17:09
 */

use App\Parser\Base\ParserBase;
use Phalcon\Cli\Task;

/**
 * {@inheritDoc}
 */
class MainTask extends Task
{
    public function mainAction()
    {
        $directories = glob(APP_DIR . '/library/App/Parser/Sites/Parse/*' , GLOB_ONLYDIR);

        $folders = [];

        $parsers = [];

        foreach ($directories as $directory) {
            $dir =explode('/', $directory);
            $dir = end($dir);
            $folders[] = $dir;
            $parserClassName =  '\App\Parser\Sites\Parse\\'.$dir.'\\Parser';
            $mapperClassName = '\App\Parser\Sites\Parse\\'.$dir.'\\DataMapper';
            $configClassName = '\App\Parser\Sites\Parse\\'.$dir.'\\MappingConfig';

            $config = new $configClassName();
            $mapper = new $mapperClassName($config);

            $extractor = new  \App\Parser\Base\Extractor($mapper);


            /** @var ParserBase $parser */
            $parser = new $parserClassName($extractor);

//            $parser->parse();

            echo $parser->check();

            $parsers[] = get_class($parser);
        }
    }

    public function articleAction()
    {
        $directories = glob(APP_DIR . '/library/App/Parser/Articles/Parse/*' , GLOB_ONLYDIR);

        $folders = [];

        $parsers = [];

        foreach ($directories as $directory) {
            $dir =explode('/', $directory);
            $dir = end($dir);
            $folders[] = $dir;
            $parserClassName =  '\App\Parser\Articles\Parse\\'.$dir.'\\Parser';
            $mapperClassName = '\App\Parser\Articles\Parse\\'.$dir.'\\DataMapper';
            $configClassName = '\App\Parser\Articles\Parse\\'.$dir.'\\MappingConfig';

            $config = new $configClassName();
            $mapper = new $mapperClassName($config);

            $extractor = new  \App\Parser\Base\Extractor($mapper);


            /** @var ParserBase $parser */
            $parser = new $parserClassName($extractor);

//            $parser->parse();

            echo $parser->check();

            $parsers[] = get_class($parser);
        }
    }
}
