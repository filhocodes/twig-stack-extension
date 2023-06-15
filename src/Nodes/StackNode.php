<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension\Nodes;

use FilhoCodes\TwigStackExtension\TwigStackExtension;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * StackNode
 *
 * Node that will be rendered into a placeholder, where contents will
 * be added via the Push and Prepend nodes.
 */
final class StackNode extends Node
{
    /**
     * new StackNode()
     *
     * @param string $name
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(string $name, int $lineno = 0, string $tag = null)
    {
        parent::__construct([], ['name' => $name], $lineno, $tag);
    }

    /**
     * StackNode->compile()
     *
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler): void
    {
        /** @var $extension TwigStackExtension */
        $extension = $compiler->getEnvironment()->getExtension(TwigStackExtension::class);
        $manager = $extension->getManager();

        $stackName = $this->getAttribute('name');
        $stackPlaceHolder = $manager->makeStackPlaceHolder($stackName);
        $expression = "echo '{$stackPlaceHolder}';\n";
        $compiler->write($expression);
    }
}
