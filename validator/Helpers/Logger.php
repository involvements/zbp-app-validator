<?php
/**
 * Created by PhpStorm.
 * User: sx
 * Date: 2017/10/22
 * Time: 10:27
 */

namespace Zsxsoft\AppValidator\Helpers;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Log;

/**
 * Class Logger
 * @package Zsxsoft\AppValidator\Helpers
 * @method static log($level, $message, array $context = array())
 * @method static debug($message, array $context = array())
 * @method static info($message, array $context = array())
 * @method static notice($message, array $context = array())
 * @method static warn($message, array $context = array())
 * @method static warning($message, array $context = array())
 * @method static err($message, array $context = array())
 * @method static error($message, array $context = array())
 * @method static crit($message, array $context = array())
 * @method static critical($message, array $context = array())
 * @method static alert($message, array $context = array())
 * @method static emerg($message, array $context = array())
 * @method static emergency($message, array $context = array())
 *
 */
class Logger
{
    use StaticInstance;

    protected $log = NULL;

    public function __construct()
    {
        $this->log = new Log('log');
        if ($this->hasColorSupport()) {
            $handler = new StreamHandler('php://stdout', Log::DEBUG);
            $handler->setFormatter(new ColoredLineFormatter());
            $this->log->pushHandler($handler);
        }
    }

    public function __call($name, $arguments)
    {
        call_user_func_array([$this->log, $name], $arguments);
    }


    /**
     * Returns true if the stream supports colorization.
     *
     * Colorization is disabled if not supported by the stream:
     *
     *  -  Windows != 10.0.10586 without Ansicon, ConEmu or Mintty
     *  -  non tty consoles
     *
     * @see https://github.com/symfony/console/blob/master/Output/StreamOutput.php#L81
     * @return bool true if the stream supports colorization, false otherwise
     */
    protected function hasColorSupport()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return
                '10.0.10586' === PHP_WINDOWS_VERSION_MAJOR.'.'.PHP_WINDOWS_VERSION_MINOR.'.'.PHP_WINDOWS_VERSION_BUILD
                || false !== getenv('ANSICON')
                || 'ON' === getenv('ConEmuANSI')
                || 'xterm' === getenv('TERM');
        }

        return true;//function_exists('posix_isatty');
    }
}