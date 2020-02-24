<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension\Nodes;

use FilhoCodes\TwigStackExtension\TwigStackExtension;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * PrependNode
 *
 * Node with a content that instead of being rendered in place, will
 * be rendered in his respective stack. The contents will be prepended
 * (added to the beginning) into said stack.
 */
final class PrependNode extends Node
{
    /**
     * new PrependNode()
     *
     * @param string $name
     * @param string|null $id
     * @param Node $body
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(string $name, ?string $id, Node $body, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['body' => $body], [
            'name' => $name,
            'id' => $id,
        ], $lineno, $tag);
    }

    /**
     * PrependNode->compile()
     *
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $extension = TwigStackExtension::class;
        $stackName = $this->getAttribute('name');
        $id = ($idValue = $this->getAttribute('id')) ? "'{$idValue}'" : 'null';

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
            ->write("\$manager = \$extension->getManager();\n\n")
            ->write("echo \$manager->prependContentIntoStack('{$stackName}', {$id}, ob_get_clean());\n\n");
    }
}
