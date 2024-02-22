<?php

namespace App\Console;

use App\Jobs\IntegradorJob;
use App\Models\Central\ConfiguracaoEmpresa;
use App\Models\Central\PeriodoSincronizacao;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const PROGRAMADA = 1;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->scheduleIntegradorJob($schedule);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    private function scheduleIntegradorJob(Schedule $schedule)
    {
        $configEmpresas = ConfiguracaoEmpresa::where([
            'tipo' => self::PROGRAMADA,
        ])->get();
        $periodoEmpresas = PeriodoSincronizacao::get()->keyBy('fk_empresa');

        $req = new \stdClass();
        $req->tipo = self::PROGRAMADA;

        foreach ($periodoEmpresas as $tenant => $periodo) {
            $configByTenant = $configEmpresas->where('fk_empresa', $tenant);
            $req->ids = $configByTenant->pluck('fk_tipo_configuracao_empresa')->toArray();

            $dias = explode(",", $periodo->dia);
            $horas = explode(",", $periodo->hora);

            foreach ($dias as $dia) {
                if(empty($dia)) continue;
                foreach ($horas as $hora) {
                    $schedule->job((new IntegradorJob($req, $tenant))->onQueue("integracao"))->weeklyOn($this->days()[$dia], $hora);
                }
            }
        }
    }

    private function days() {
        return [
            'Dom' => 0,
            'Seg' => 1,
            'Ter' => 2,
            'Qua' => 3,
            'Qui' => 4,
            'Sex' => 5,
            'Sab' => 6,
        ];
    }
}
