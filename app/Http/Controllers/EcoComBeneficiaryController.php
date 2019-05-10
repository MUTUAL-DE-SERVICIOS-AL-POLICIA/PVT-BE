<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\Address;
use Muserpol\Helpers\Util;

class EcoComBeneficiaryController extends Controller
{
    public function update(Request $request, $id)
    {
        $beneficiary = EcoComBeneficiary::find($id);
        $beneficiary->city_identity_card_id = $request->city_identity_card_id;
        $beneficiary->identity_card = mb_strtoupper(trim($request->identity_card));
        $beneficiary->last_name = mb_strtoupper(trim($request->last_name));
        $beneficiary->mothers_last_name = mb_strtoupper(trim($request->mothers_last_name));
        $beneficiary->first_name = mb_strtoupper(trim($request->first_name));
        $beneficiary->second_name = mb_strtoupper(trim($request->second_name));
        $beneficiary->surname_husband = mb_strtoupper(trim($request->surname_husband));
        $beneficiary->birth_date = Util::verifyBarDate($request->birth_date) ? Util::parseBarDate($request->birth_date) : $request->birth_date;
        $beneficiary->gender = $request->gender;
        $beneficiary->nua = $request->nua;
        $beneficiary->phone_number = implode(",", $request->phone_number);
        $beneficiary->cell_phone_number = implode(",", $request->cell_phone_number);
        $beneficiary->save();

        $eco_com = $beneficiary->economic_complement;

        /**
         * Update or create address
         */
        if (sizeOf($beneficiary->address) > 0) {
            $address_id = $beneficiary->address()->first()->id;
            $address = Address::find($address_id);
            if ($request->address[0]['zone'] || $request->address[0]['street'] || $request->address[0]['number_address']) {
                $address->city_address_id = $request->address[0]['city_address_id'] ?? 1;
                $address->zone = $request->address[0]['zone'];
                $address->street = $request->address[0]['street'];
                $address->number_address = $request->address[0]['number_address'];
                $address->save();
                if ($eco_com->procedure_modality_id == 24) {
                    $update_affiliate = $eco_com->affiliate;
                    if ($update_affiliate->address->contains($address->id)) { } else {
                        $update_affiliate->address()->save($address);
                    }
                }
            } else {
                if ($eco_com->procedure_modality_id == 24) {
                    $update_affiliate = $eco_com->affiliate;
                    $update_affiliate->address()->detach($address->id);
                }
                $beneficiary->address()->detach($address->id);
                $address->delete();
            }
        } else {
            if (sizeOf($request->address) > 0) {
                $address = new Address();
                $address->city_address_id = $request->address[0]['city_address_id'] ?? 1;
                $address->zone = $request->address[0]['zone'];
                $address->street = $request->address[0]['street'];
                $address->number_address = $request->address[0]['number_address'];
                $address->save();
                $beneficiary->address()->save($address);
                if ($eco_com->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($eco_com->affiliate_id);
                    $update_affiliate->address()->save($address);
                }
            }
        }
        return $beneficiary;
    }
}
