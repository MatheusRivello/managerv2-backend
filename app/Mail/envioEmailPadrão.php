<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class envioEmailPadrão extends Mailable
{
    use Queueable, SerializesModels;

    public $assunto;
    public $titulo;
    public $texto;

    public function __construct($assunto, $titulo, $texto)
    {
        $this->assunto = $assunto;
        $this->titulo = $titulo;
        $this->texto = $texto;
    }

    public function build()
    {
        return $this->view('emailPadrao')
            ->with('tituloEmail', "{$this->titulo->id} - {$this->titulo->razao_social}")
            ->with('textoEmail', $this->texto)
            ->subject("{$this->assunto} - {$this->titulo->id} - {$this->titulo->razao_social}");
    }
}
