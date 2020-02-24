<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension\TokenParsers;

use FilhoCodes\TwigStackExtension\Nodes\PushNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * PushTokenParser
 *
 * Declares the tag `{% push %}{% endpush %}`.
 */
final class PushTokenParser extends AbstractTokenParser
{
    /**
     * @inheritDoc
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $id = ($idToken = $stream->nextIf(Token::NAME_TYPE)) ? $idToken->getValue() : null;
        $this->parser->pushLocalScope();

        if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse(function (Token $token) {
                return $token->test('endpush');
            }, true);
            if ($token = $stream->nextIf(Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value != $name) {
                    throw new SyntaxError(
                        sprintf("Expected endpush for stack '$name', but got %s", $value),
                        $stream->getCurrent()->getLine(),
                        $stream->getSourceContext()
                    );
                }
            }
        } else {
            $body = new Node([
                new PrintNode($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ]);
        }
        $this->parser->popLocalScope();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new PushNode($name, $id, $body, $lineno, $this->getTag());
    }

    /**
     * @inheritDoc
     */
    public function getTag()
    {
        return 'push';
    }
}
