<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension\TokenParsers;

use FilhoCodes\TwigStackExtension\Nodes\StackNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * StackTokenParser
 *
 * Declares the tag `{% stack %}`.
 */
final class StackTokenParser extends AbstractTokenParser
{
    /**
     * @inheritDoc
     */
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();

        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new StackNode($name, $token->getLine());
    }

    /**
     * @inheritDoc
     */
    public function getTag()
    {
        return 'stack';
    }
}
