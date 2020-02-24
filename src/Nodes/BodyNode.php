<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension\Nodes;

use FilhoCodes\TwigStackExtension\TwigStackExtension;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * BodyNode
 *
 * Decorates the default body node, replacing all the placeholders for
 * stacks with the final result.
 */
final class BodyNode extends Node
{
    /**
     * new BodyNode()
     *
     * @param Node $body
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(Node $body, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }

    /**
     * BodyNode->compile()
     *
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $extension = TwigStackExtension::class;

        $compiler
            ->write("ob_start();\n")
            ->write("try {\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("} catch (Exception \$e) {\n")
            ->indent()
            ->write("ob_end_clean();\n")
            ->write("throw \$e;\n")
            ->outdent()
            ->write("}\n\n")
            ->write("\$extension = \$this->env->getExtension('{$extension}');\n")
            ->write("\$manager = \$extension->getManager();\n")
            ->write("echo \$manager->replaceBodyWithStacks(ob_get_clean());\n\n");
    }
}
