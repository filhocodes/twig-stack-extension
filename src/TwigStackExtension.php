<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtension;

use FilhoCodes\TwigStackExtension\NodeVisitors\StackNodeVisitor;
use FilhoCodes\TwigStackExtension\TokenParsers\PrependTokenParser;
use FilhoCodes\TwigStackExtension\TokenParsers\PushTokenParser;
use FilhoCodes\TwigStackExtension\TokenParsers\StackTokenParser;
use Twig\Extension\ExtensionInterface;

/**
 * TwigStackExtension
 *
 * Define all the features of this extension.
 */
final class TwigStackExtension implements ExtensionInterface
{
    /**
     * TwigStackExtension->manager
     *
     * @var StacksManager
     */
    private $manager;

    /**
     * TwigStackExtension->enableBlockParsers
     *
     * @var bool
     */
    private $enableBlockParsers = false;

    /**
     * new TwigStackExtension()
     *
     * @param bool $enableBlockParsers
     */
    public function __construct(bool $enableBlockParsers = false)
    {
        $this->manager = new StacksManager();
        $this->enableBlockParsers = $enableBlockParsers;
    }

    /**
     * TwigStackExtension->getManager()
     *
     * @return StacksManager
     */
    public function getManager(): StacksManager
    {
        return $this->manager;
    }

    /**
     * @inheritDoc
     */
    public function getTokenParsers(): array
    {
        return [
            new StackTokenParser(),
            new PushTokenParser(),
            new PrependTokenParser(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNodeVisitors(): array
    {
        return [
            new StackNodeVisitor(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFilters(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getTests(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getOperators(): array
    {
        return [];
    }
}
