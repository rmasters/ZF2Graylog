<?php

namespace ZF2Graylog\Log\Formatter;

use \Zend\Log\Formatter\Base;
use \GELFMessage;

class Gelf extends Base
{
    private $facility = 'ZF2';

    public function __construct($facility = null) {
        if (!is_null($facility)) {
            $this->facility = (string) $format;
        }
    }

    /**
     * Returns a GELFMessage instance to be used with a GELFMessagePublisher
     *
     * @return GELFMessage
     */
    public function format($event) {
        $message = new GELFMessage;

        $message->setHost(gethostname());

        if (isset($event['priority'])) {
            $message->setLevel($event['priority']);
        } else if (isset($event['errno'])) {
            // @todo Convert to syslog error levels?
            $message->setLevel($event['errno']);
        }

        $message->setFullMessage($event['message']);
        $message->setShortMessage($event['message']);
        if (isset($event['full'])) $message->setFullMessage($event['full']);
        if (isset($event['short'])) $message->setShortMessage($event['short']);

        if (isset($event['file'])) $message->setFile($event['file']);
        if (isset($event['line'])) $message->setLine($event['line']);

        if (isset($event['version'])) $message->setVersion($event['version']);

        if (isset($event['facility'])) {
            $message->setFacility($event['facility']);
        } else {
            $message->setFacility($this->facility);
        }

        // Set timestamp
        $timestamp = $event['timestamp'];
        if ($event['timestamp'] && ($event['timestamp'] instanceof \DateTime)) {
            $timestamp = $event['timestamp']->getTimestamp();
        }
        $message->setTimestamp($timestamp);

        foreach ($event as $k => $v) {
            if (!in_array($k, ['message', 'priority', 'errno', 'full', 'short',
                'file', 'line', 'version', 'facility', 'timestamp'])) {
                $message->setAdditional($k, $v);
            }
        }

        return $message;
    }
}
