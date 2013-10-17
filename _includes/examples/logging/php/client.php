<?php

/**
 * An extremely rudimentary logger that passes log messages through gearman
 */
class GearmanLogger {

    static $instances = array();

    /**
     * Fetch (and create if needed) an instance of this logger.
     *
     * @param string $server
     * @param int $port
     * @param string $queue
     * @return self
     */
    public static function getInstance($server = '127.0.0.1', $port = 4730, $queue = 'log') {
        $hash = $queue . $server . $port;
        if (!array_key_exists($hash, self::$instances)) {
            self::$instances[$hash] = new self($queue, $server, $port);
        }

        return self::$instances[$hash];
    }

    /** @var GearmanClient */
    private $gmc;
    /** @var string */
    private $queue;

    public function __construct($queue, $server, $port) {
        $this->gmc   = new GearmanClient();
        $this->queue = $queue;
        $this->gmc->addServer($server, $port);
    }

    /**
     * Log a message
     *
     * @param mixed $message
     * @param string $level
     */
    public function log($message, $level = 'DEBUG') {
        $this->gmc->doBackground($this->queue, json_encode(array(
            'level'   => $level,
            'message' => $message,
            'ts'      => time(),
            'host'    => gethostname(),
        )));
    }

    /**
     * Log a warning
     * @param mixed $message
     */
    public function warn($message) {
        $this->log($message, 'WARN');
    }

    /**
     * Log an error
     * @param mixed $message
     */
    public function error($message) {
        $this->log($message, 'ERROR');
    }

}

GearmanLogger::getInstance()->log('A debug message');
GearmanLogger::getInstance()->warn('A warning');
GearmanLogger::getInstance()->error('A serious problem');
GearmanLogger::getInstance()->error(array('an array?', 'interesting...', 'structured log messages!'));
