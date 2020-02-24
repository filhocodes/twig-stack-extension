<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension;

/**
 * StacksManager
 *
 * Manage all the stacks available to the Twig Templates.
 */
final class StacksManager
{
    /**
     * StacksManager->stacks
     *
     * @var Stack[]
     */
    private $stacks = [];

    /**
     * StacksManager->getStackByName()
     *
     * @param string $name
     * @return Stack
     */
    private function getStackByName(string $name): Stack
    {
        if (!isset($this->stacks[$name])) {
            $this->stacks[$name] = new Stack($name);
        }

        return $this->stacks[$name];
    }

    /**
     * StacksManager->makeStackPlaceHolder()
     *
     * @param string $name
     * @return string
     */
    public function makeStackPlaceHolder(string $name): string
    {
        $stack = $this->getStackByName($name);
        return "###{FilhoCodes\\TwigStackExtension\\Stack\\{$stack->getName()}}###";
    }

    /**
     * StacksManager->pushContentIntoStack()
     *
     * @param string $name
     * @param string|null $id
     * @param string $contents
     */
    public function pushContentIntoStack(string $name, ?string $id, string $contents): void
    {
        $stack = $this->getStackByName($name);
        $stack->pushContents($contents, $id);
    }

    /**
     * StacksManager->prependContentIntoStack()
     *
     * @param string $name
     * @param string|null $id
     * @param string $contents
     */
    public function prependContentIntoStack(string $name, ?string $id, string $contents): void
    {
        $stack = $this->getStackByName($name);
        $stack->prependContents($contents, $id);
    }

    /**
     * StacksManager->replaceBodyWithStacks()
     *
     * @param string $contents
     * @return string
     */
    public function replaceBodyWithStacks(string $contents): string
    {
        $regex = '/###\{FilhoCodes\\\\TwigStackExtension\\\\Stack\\\\(.+)\}###/';
        return preg_replace_callback($regex, function ($matches) {
            $stack = $this->getStackByName($matches[1]);
            return $stack->getContents();
        }, $contents);
    }
}
