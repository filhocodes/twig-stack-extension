<?php
declare(strict_types=1);

namespace FilhoCodes\TwigStackExtensionTests;

use FilhoCodes\TwigStackExtension\TwigStackExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * TwigStackExtensionTest
 */
final class TwigStackExtensionTest extends TestCase
{
    /**
     * TwigStackExtensionTest->twig
     *
     * @var Environment
     */
    private $twig;

    /**
     * TwigStackExtensionTest->setUp()
     *
     * Initialize the Twig Environment for each test case.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->twig = new Environment(new FilesystemLoader(__DIR__.'/templates'));
        $this->twig->addExtension(new TwigStackExtension());
    }

    /**
     * TwigStackExtensionTest->testPushPrepend()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependViaInclude(): void
    {
        $result = trim($this->twig->render('via-include/template.html.twig'));
        $this->assertMatchesRegularExpression('/^1\s+2\s+3$/', $result);
    }

    /**
     * TwigStackExtensionTest->testPushPrepend()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependViaExtends(): void
    {
        $result = trim($this->twig->render('via-extends/template.html.twig'));
        $this->assertMatchesRegularExpression('/^4\s+5\s+6$/', $result);
    }

    /**
     * TwigStackExtensionTest->testPushPrependViaEmbed()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependViaEmbed(): void
    {
        $result = trim($this->twig->render('via-embed/template.html.twig'));
        $this->assertMatchesRegularExpression('/^7\s+8\s+9$/', $result);
    }

    /**
     * TwigStackExtensionTest->testPushPrependWithUniqueId()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependWithUniqueId(): void
    {
        $result = trim($this->twig->render('via-embed-with-unique-id/template.html.twig'));

        $lines = preg_split('/\s*\n+\s*/', $result);
        $this->assertEquals([
            '<script>MyScript = () => {}</script>',
            '<script>MyScript("First Action")</script>',
            '<script>MyScript("Second Action")</script>',
            '<script>MyScript("Third Action")</script>',
        ], $lines);
    }
}
