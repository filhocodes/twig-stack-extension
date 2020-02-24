<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension;

/**
 * Stack
 *
 * A storage of contents to be rendered into a Twig Template.
 */
final class Stack
{
    /**
     * Stack->name
     *
     * @var string
     */
    private $name;

    /**
     * Stack->contents
     *
     * @var string[]
     */
    private $contents = [];

    /**
     * Stack->ids
     *
     * @var array
     */
    private $ids = [];

    /**
     * new Stack()
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Stack->getName()
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Stack->pushContents()
     *
     * @param string $contents
     * @param string|null $id
     */
    public function pushContents(string $contents, string $id = null): void
    {
        if (!is_null($id) && $this->isUniqueComponentRegistered($id)) {
            return;
        }

        array_push($this->contents, $contents);
    }

    /**
     * Stack->prependContents()
     *
     * @param string $contents
     * @param string|null $id
     */
    public function prependContents(string $contents, string $id = null): void
    {
        if (!is_null($id) && $this->isUniqueComponentRegistered($id)) {
            return;
        }

        array_unshift($this->contents, $contents);
    }

    /**
     * Stack->getContents()
     *
     * @return string
     */
    public function getContents(): string
    {
        return join("\n", $this->contents);
    }

    /**
     * Stack->isUniqueComponentRegistered()
     *
     * @param string $id
     * @return bool
     */
    private function isUniqueComponentRegistered(string $id): bool
    {
        if (in_array($id, $this->ids)) {
            return true;
        }

        array_push($this->ids, $id);
        return false;
    }
}
