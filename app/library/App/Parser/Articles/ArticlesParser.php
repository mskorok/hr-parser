<?php
namespace App\Parser\Articles;

use App\Model\Articles;


/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:39
 */

class ArticlesParser extends \App\Parser\Base\ParserBase
{
    public function parse()
    {
        $this->addParsed();
        $paths = $this->extractor->getPagesUrl();

        foreach ($paths as $path) {
            if ($this->isParsed($path)) {
                continue;
            }

            $this->extractor->extract($path);

            sleep(1);
        }
    }

    protected function addParsed(): void
    {
        $parsed = Articles::find([
            'conditions' => ' parsed = ?1 AND link LIKE "%' . $this->host .  '%" ',
            'bind' => [
                1 => 1
            ]
        ]);

        foreach ($parsed as $path) {
            if (!$this->isParsed($path)) {
                $this->parsed[] = $path;
            }
        }
    }
}