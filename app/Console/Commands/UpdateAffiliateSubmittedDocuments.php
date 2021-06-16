<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Muserpol\Models\AffiliateSubmittedDocument;
use Carbon\Carbon;

class UpdateAffiliateSubmittedDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:afi_sub_doc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $affiliate_submitted_documents = AffiliateSubmittedDocument::with('procedure_document', 'affiliate:id')->get();
        foreach ($affiliate_submitted_documents as $asd) {
            $procedure_document = $asd->procedure_document;
            if (now() >= Carbon::parse($asd->reception_date)->addDays($procedure_document->expire_date)) {  
                $asd->status =  false;
                $asd->save();
            }
        }
    }
}
