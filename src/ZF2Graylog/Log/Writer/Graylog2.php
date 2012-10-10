<?php

namespace ZF2Graylog\Log\Writer;

use \Zend\Log\Writer\AbstractWriter;
use \Zend\Log\Formatter\FormatterInterface;

use \GELFMessagePublisher;

class Graylog2 extends AbstractWriter
{
    private $publisher;
    protected $formatter;

    public function __construct($facility, $hostname, $port = GELFMessagePublisher::GRAYLOG2_DEFAULT_PORT) {
        $this->publisher = new GELFMessagePublisher($hostname, $port);

        $this->formatter = new \ZF2Graylog\Log\Formatter\Gelf($facility);
    }

    public function setFormatter(FormatterInterface $formatter)
    {
    }

    public function doWrite(array $event) {
        $message = $this->formatter->format($event);
        $this->publisher->publish($message);
    }
}
