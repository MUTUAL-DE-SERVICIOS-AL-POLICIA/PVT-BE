<?php

namespace Muserpol\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ObservationType;
use Muserpol\Models\Spouse;
use Muserpol\User;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EconomicComplement;

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
  protected $file_content;
  protected $file_name;
  protected $file_path;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->file_content = '';
    $this->file_path = 'sincronizacion_prestamos';
    $date = Carbon::now();
    $this->file_name = implode('/', [$this->file_path, $date->year, $date->format('m'), $date->format('d_m_Y_\a_\h\r\s_H_i') . '.txt']);
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    /*if (!$this->argument('date')) {
      $date = Carbon::now();
    } else {
      try {
        $date = Carbon::parse($date);
      } catch (\Exception $e) {
        $this->error('Invalid input date');
        exit($e->getMessage());
      }
    }
    $date = $date->subMonth()->endOfMonth()->format('Ymd');
    $user = User::first();

    // 2 = Suspendido - Préstamo en Mora
    $id_overdue = 2;
    // 16 = Complement económico en proceso
    $in_process_id = 16;
    $message = ' Sincronización iniciada ' . Carbon::now();
    $this->new_line($message);
    $this->info($message);
    \Log::info($message);
    \Log::info('Removing old data...');
    DB::table('observables')->where('observation_type_id', $id_overdue)->where('observable_type', 'affiliates')->where('message', 'not like', 'PRIORITARIO%')->delete();

    $current_procedures = Util::getEcoComCurrentProcedure();
    $overdue_eco_coms = DB::table('observables')->where('observation_type_id', $id_overdue)->where('observable_type', 'economic_complements')->where('message', 'not like', 'PRIORITARIO%')->whereEnabled(false)->pluck('observable_id');

    foreach ($overdue_eco_coms as $eco_com) {
      $economic_complement = EconomicComplement::find($eco_com);
      if (in_array($economic_complement->eco_com_procedure_id, $current_procedures->all()) && $economic_complement->eco_com_state_id == $in_process_id) {
        DB::table('observables')->where('observation_type_id', $id_overdue)->where('observable_type', 'economic_complements')->where('observable_id', $eco_com)->delete();
      }
    }

    $loans = DB::connection('sqlsrv')->select("SELECT dbo.Prestamos.IdPrestamo, dbo.Prestamos.PresNumero, dbo.Padron.IdPadron, DATEDIFF(month, Amortizacion.AmrFecPag, '" . $date . "') as Overdue from dbo.Prestamos join dbo.Padron on Prestamos.IdPadron = Padron.IdPadron join dbo.Producto on Prestamos.PrdCod = Producto.PrdCod join dbo.Amortizacion on (Prestamos.IdPrestamo = Amortizacion.IdPrestamo and Amortizacion.AmrNroPag = (select max(AmrNroPag) from Amortizacion where Amortizacion.IdPrestamo = Prestamos.IdPrestamo and Amortizacion.AMRSTS <>'X' )) where Prestamos.PresEstPtmo = 'V' and dbo.Prestamos.PresSaldoAct > 0 and Amortizacion.AmrFecPag <  cast('" . $date . "' as datetime) and DATEDIFF(month, Amortizacion.AmrFecPag, '" . $date . "') >= 2;");

    $bar = $this->output->createProgressBar(count($loans));
    $bar->start();
    $count = 0;
    $eco_count = 0;

    foreach ($loans as $loan) {
      $bar->advance();
      $padron = DB::connection('sqlsrv')->table('Padron')->where('IdPadron', $loan->IdPadron)->first();

      if (!$padron) {
        $message = ' ID de padrón: ' . $loan->IdPadron . ' inexistente';
        $this->new_line($message);
        $this->error($message);
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
          $message = ' Matrícula de padrón: ' . $padron->PadMatriculaTit . ' inexistente';
          $this->new_line($message);
          $this->error($message);
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
        $message = ' Afiliado con CI: ' . $loan->PadCedulaIdentidad . ' inexistente';
        $this->new_line($message);
        $this->error($message);
        $affiliates = Affiliate::where('identity_card', 'like', $loan->PadCedulaIdentidad . '%')->get();
        $affiliates->merge(Spouse::where('identity_card', 'like', $loan->PadCedulaIdentidad . '%')->get());
        if ($affiliates->count() > 0) {
          $names = [];
          $db_name = DB::connection('pgsql')->getDatabaseName();
          foreach ($affiliates as $option) {
            $names[] = [
              $db_name,
              $option->id,
              $option->identity_card,
              implode(' ', [$option->last_name ?? '', $option->mothers_last_name ?? '', $option->first_name ?? '', $option->second_name ?? ''])
            ];
          }
          $message = ' Posibles opciones para el CI: ' . $loan->PadCedulaIdentidad . PHP_EOL;
          $this->new_line($message);
          foreach ($names as $name) {
            $message .= $loan->PadCedulaIdentidad . ' ' . $loan->PadName . ' --> ' . $name[2] . ' ' . $name[3] . ' - id: ' . $name[1] . PHP_EOL;
            $this->new_line($message);
          }
          $this->table(['BD', 'Id', 'CI', 'Nombre'], array_merge([[DB::connection('sqlsrv')->getDatabaseName(), $loan->IdPadron, $loan->PadCedulaIdentidad, $loan->PadName]], $names));
        }
        continue;
      }

      // 2 = Suspendido - Préstamo en Mora
      $observation = ObservationType::find($id_overdue);

      $affiliate->observations()->save($observation, [
        'user_id' => $user->id,
        'date' => Carbon::now(),
        'message' => 'Préstamo con mora de ' . $loan->Overdue . ' meses (generado automáticamente)',
        'enabled' => false
      ]);

      $eco_coms = $affiliate->economic_complements()->whereIn('eco_com_procedure_id', $current_procedures)->get();
      foreach ($eco_coms as $eco) {
        if (!$eco->hasObservationType($id_overdue) && $eco->eco_com_state_id == $in_process_id) {
          $eco->observations()->save($observation, [
            'user_id' => $user->id,
            'date' => Carbon::now(),
            'message' => 'Préstamo con mora de ' . $loan->Overdue . ' meses (generado automáticamente)',
            'enabled' => false
          ]);
          $eco_count++;
        }
      }
      $count++;
    }

    $message = ' Número de afiliados observados: ' . $count;
    $this->new_line($message);
    $this->info($message);

    $message = ' Número de trámites de complemento económico observados: ' . $eco_count;
    $this->new_line($message);
    $this->info($message);

    $bar->finish();

    $message = ' Sincronización terminada ' . Carbon::now();
    $this->new_line($message);
    $this->info($message);
    \Log::info($message);
    Storage::disk('local')->put($this->file_name, $this->file_content);
  */
  }

  private function new_line($line)
  {
    $this->file_content =  $this->file_content . PHP_EOL . $line;
  }
}
