<?php

namespace App\Services\Integracao\Traits;

use Illuminate\Support\Facades\Storage;

trait Log {
    private $logBuffer = "";
    private $lastTrace = "";

    public function writeLog($message, $path = "/") {
        try {
            Storage::append($this->getPath($path), $this->now() . $message);
        } catch (\Throwable $th) {
            if (! Storage::exists($this->getPath($path)))
            {
                Storage::makeDirectory(dirname($this->getPath($path)));
            };
            Storage::put($this->getPath($path), $this->now() . $message);
        }
    }

    public function addLog($message, \Throwable $th = null)
    {
        if(env("LOG_TRACEBACK", 0) == 1 && isset($th))
        {
            $message .= "\n$".$th->getTraceAsString()."\n";
        }
        $this->lastTrace = $th->getTraceAsString();
        $this->logBuffer .= $message . "\n@";
    }

    public function saveLog($path = "/")
    {
        if (! empty($this->logBuffer))
        {
            $this->writeLog($this->logBuffer, $path);
            \Rollbar\Rollbar::error(
                "Erro no mapeamento dos campos ou referência de registro inexistente",
                [
                    "log" => $this->logBuffer,
                    "count" => substr_count($this->logBuffer, "@"),
                    "trace" => $this->lastTrace
                ]
            );
            $this->logBuffer = "";
            $this->lastTrace = "";
        }
    }

    public function readLog() {
        if (Storage::exists($this->getPath())) {
            return nl2br(Storage::get($this->getPath()));
        }
        return null;
    }

    public function getTimelineByLog() {
        $collection = collect(explode("<br />\n", $this->readLog()));
        $filtered = $collection->filter(function ($line) {
            return preg_match("/sincronizado|Inicio|Fim/", $line);
        });
        // convert collection to array, get array values and reverse
        $timeline = array_reverse(array_values($filtered->toArray()));

        $firstLineSplited = count($timeline) > 0 ? explode("! ", $timeline[0]) : [];

        $completed = count($firstLineSplited) > 1 ? $firstLineSplited[1] : null;

        return [
            "concluido" => $completed,
            "timeline" => $timeline
        ];
    }

    private function getPath($custom = "") {
        if ($this->tenant == null) return "empresas/empresa-null/" . $this->today() . "/" . $custom . ".log";
        return "empresas/empresa-" . $this->tenant . "/" . $this->today() . "/" . $custom . ".log";
    }

    private function getClassName($param = 'model') {
        return [
            'model' => str_replace('Service', '', (new \ReflectionClass($this))->getShortName()),
            'service' => (new \ReflectionClass($this))->getShortName(),
        ][$param];
    }

    private function now() {
        return "[" . date("d/m/Y H:i:s") . "] ";
    }

    private function today() {
        return date("d-m-Y");
    }
}