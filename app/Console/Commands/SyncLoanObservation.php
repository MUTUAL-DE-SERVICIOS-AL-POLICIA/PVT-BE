<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ObservationType;
use Muserpol\Models\Spouse;
use Muserpol\User;

class SyncLoanObservation extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'loan:sync {date?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Overdue loans synchronization';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    if (!$this->argument('date')) $date = Carbon::now();
    // 2 = Suspendido - Préstamo en Mora
    $id_overdue = 2;

    \Log::info('Synchronization started ' . Carbon::now());
    \Log::info('Removing old data...');
    DB::table('observables')->where('observable_id', $id_overdue)->delete();

    $user = User::first();

    $loans = DB::connection('sqlsrv')->select("SELECT dbo.Prestamos.IdPrestamo, dbo.Prestamos.PresNumero, dbo.Padron.IdPadron, DATEDIFF(month, Amortizacion.AmrFecPag, '" . $date . "') as Overdue from dbo.Prestamos join dbo.Padron on Prestamos.IdPadron = Padron.IdPadron join dbo.Producto on Prestamos.PrdCod = Producto.PrdCod join dbo.Amortizacion on (Prestamos.IdPrestamo = Amortizacion.IdPrestamo and Amortizacion.AmrNroPag = (select max(AmrNroPag) from Amortizacion where Amortizacion.IdPrestamo = Prestamos.IdPrestamo and Amortizacion.AMRSTS <>'X' )) where Prestamos.PresEstPtmo = 'V' and dbo.Prestamos.PresSaldoAct > 0 and Amortizacion.AmrFecPag <  cast('" . $date . "' as datetime) and DATEDIFF(month, Amortizacion.AmrFecPag, '" . $date . "') >= 2;");

    foreach ($loans as $loan) {
      $padron = DB::connection('sqlsrv')->table('Padron')->where('IdPadron', $loan->IdPadron)->first();

      if (!$padron) {
        \Log::error('Padron with IdPadron: ' . $loan->IdPadron . 'does not exist');
        continue;
      }

      $loan->affiliate = true;
      $loan->PadSpouseCedulaIdentidad = null;

      if (trim($padron->PadMatriculaTit) != '' and $padron->PadMatriculaTit != null and trim($padron->PadMatriculaTit) != '0' and strlen(trim($padron->PadMatriculaTit)) > 4) {
        $loan->affiliate = false;
        $loan->PadSpouseCedulaIdentidad = $padron->PadCedulaIdentidad;
        $padron_holder = DB::connection('sqlsrv')->table('Padron')->where('PadMatricula', $padron->PadMatriculaTit)->first();
        if ($padron_holder) {
          $padron = $padron_holder;
        } else {
          // preg_match("/(\\d+)([a-zA-Z]+)/", $padron->PadMatriculaTit, $match);
          // if (count($match) > 0) {
          //   $padron->PadCedulaIdentidad = $match[1];
          //   \Log::info('Padron with PadMatricula: ' . $padron->PadMatriculaTit . ' does not exist. Getting PadCedulaIdentidad as ' . $padron->PadCedulaIdentidad);
          // } else {
          //   \Log::error('PadMatriculaTit: ' . $padron->PadMatriculaTit . 'does not contain numbers');
          //   continue;
          // }
          \Log::info('Padron with PadMatricula: ' . $padron->PadMatriculaTit . ' does not exist');
          continue;
        }
      }

      $loan->PadCedulaIdentidad = utf8_encode(trim($padron->PadCedulaIdentidad));
      $loan->PadMatricula = utf8_encode(trim($padron->PadMatricula));
      $loan->PadName = implode(' ', [utf8_encode(trim($padron->PadPaterno)), utf8_encode(trim($padron->PadMaterno)), utf8_encode(trim($padron->PadNombres))]);

      $affiliate = Affiliate::where('identity_card', $loan->PadCedulaIdentidad)->first();
      if (!$affiliate and !$loan->affiliate) {
        $spouse = Spouse::where('identity_card', $loan->PadSpouseCedulaIdentidad)->first();
        if ($spouse) {
          $affiliate = $spouse->affiliate;
        }
      }
      if (!$affiliate) {
        \Log::error('Affiliate with identity_card: ' . $loan->PadCedulaIdentidad . ' does not exist');
        $affiliates = Affiliate::where('identity_card', 'like', $loan->PadCedulaIdentidad . '%')->get();
        $affiliates->merge(Spouse::where('identity_card', 'like', $loan->PadCedulaIdentidad . '%')->get());
        if ($affiliates->count() > 0) {
          $names = [];
          foreach ($affiliates as $option) {
            $names[] = implode(' ', [$option->identity_card, $option->last_name ?? '', $option->mothers_last_name ?? '', $option->first_name ?? '', $option->second_name ?? '', '- id:', $option->id]);
          }
          $message = 'Possible options for identity_card: ' . $loan->PadCedulaIdentidad . PHP_EOL;
          foreach ($names as $name) {
            $message .= $loan->PadCedulaIdentidad . ' ' . $loan->PadName . ' --> ' . $name . PHP_EOL;
          }
          \Log::error($message);
        }
        continue;
      }

      // 2 = Suspendido - Préstamo en Mora
      $observation = ObservationType::find($id_overdue);
      $affiliate->observations()->save($observation, [
        'user_id' => $user->id,
        'date' => Carbon::now(),
        'message' => 'Préstamo con mora de ' . $loan->Overdue,
        'enabled' => true
      ]);
    }

    \Log::info('Sincronización terminada ' . Carbon::now());
  }
}
