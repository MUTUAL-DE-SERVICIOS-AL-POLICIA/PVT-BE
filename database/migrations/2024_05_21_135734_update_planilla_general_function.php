<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePlanillaGeneralFunction extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement("DROP FUNCTION IF EXISTS public.planilla_general(bigint);");
    DB::statement("
        CREATE
        OR REPLACE FUNCTION public.planilla_general(bigint) RETURNS TABLE(
            nro bigint,
            observacion text,
            estado_observacion text,
            affiliate_id bigint,
            code character varying,
            identity_card character varying,
            first_name character varying,
            second_name character varying,
            last_name character varying,
            mothers_last_name character varying,
            surname_husband character varying,
            regional character varying,
            tipo_prestamo character varying,
            tipo_recepcion character varying,
            categoria character varying,
            grado character varying,
            ente_gestor character varying,
            total_rent numeric,
            total_rent_calc numeric,
            seniority numeric,
            salary_reference numeric,
            salary_quotable numeric,
            difference numeric,
            total_amount_semester numeric,
            complementary_factor numeric,
            total_complement numeric,
            amortizacion_prestamos numeric,
            amortizacion_reposicion numeric,
            amortizacion_retencion_segun_juzgado numeric,
            amortizacion_auxilio numeric,
            amortizacion_cuentasxcobrar numeric,
            total numeric,
            ubicacion character varying,
            tipo_beneficiario character varying,
            estado character varying,
            sigep_status character varying,
            account_number bigint,
            financialentities character varying,
            eco_com_state_id bigint
        ) LANGUAGE plpgsql AS \$function\$ BEGIN create temp table tmp_planilla_general as (
            select
                row_number() OVER (
                    ORDER BY
                        ec.affiliate_id
                ) as nro,
                ec.id,
                '' as observacion,
                '' as estado_observacion,
                case
                    when (ec.is_paid = true) then ' (pago por única vez a la viuda)'
                    else ''
                end as viudedad,
                ec.affiliate_id,
                ec.code,
                eca.identity_card,
                eca.first_name,
                eca.second_name,
                eca.last_name,
                eca.mothers_last_name,
                eca.surname_husband,
                c2.name as regional,
                pm.name as tipo_prestamo,
                ecrt.name as tipo_recepcion,
                ec.category_id,
                ec.degree_id,
                ec.total_rent,
                ec.total_rent_calc,
                ec.seniority,
                ec.salary_reference,
                ec.salary_quotable,
                ec.difference,
                ec.total_amount_semester,
                ec.complementary_factor,
                0.0 as total_complement,
                0.0 as amortizacion_prestamos,
                0.0 as amortizacion_reposicion,
                0.0 as amortizacion_retencion_segun_juzgado,
                0.0 as amortizacion_auxilio,
                0.0 as amortizacion_cuentasxcobrar,
                ec.total,
                ws.name as ubicacion,
                ecm.name as tipo_beneficiario,
                ecs.name as estado,
                ec.eco_com_state_id
            from
                economic_complements ec
                inner join cities c2 on ec.city_id = c2.id
                inner join eco_com_applicants eca on ec.id = eca.economic_complement_id
                inner join wf_states ws on ec.wf_current_state_id = ws.id
                inner join eco_com_modalities ecm on ec.eco_com_modality_id = ecm.id
                inner join procedure_modalities pm on ecm.procedure_modality_id = pm.id
                inner join eco_com_states ecs on ec.eco_com_state_id = ecs.id
                inner join eco_com_reception_types ecrt on ec.eco_com_reception_type_id = ecrt.id
                inner join categories cat on ec.category_id = cat.id
            where
                ec.eco_com_procedure_id = $1
                and ec.deleted_at is null
                and wf_current_state_id in (3)
                and ec.eco_com_state_id in (16, 28, 18, 29)
            order by
                ec.affiliate_id
        );

        update
            tmp_planilla_general tpg
        set
            amortizacion_prestamos = dtec.amount
        from
            discount_type_economic_complement dtec
        where
            tpg.id = dtec.economic_complement_id
            and dtec.discount_type_id = 5;

        update
            tmp_planilla_general tpg
        set
            amortizacion_reposicion = dtec.amount
        from
            discount_type_economic_complement dtec
        where
            tpg.id = dtec.economic_complement_id
            and dtec.discount_type_id = 6;

        update
            tmp_planilla_general tpg
        set
            amortizacion_retencion_segun_juzgado = dtec.amount
        from
            discount_type_economic_complement dtec
        where
            tpg.id = dtec.economic_complement_id
            and dtec.discount_type_id = 8;

        update
            tmp_planilla_general tpg
        set
            amortizacion_auxilio = dtec.amount
        from
            discount_type_economic_complement dtec
        where
            tpg.id = dtec.economic_complement_id
            and dtec.discount_type_id = 7;

        update
            tmp_planilla_general tpg
        set
            amortizacion_cuentasxcobrar = dtec.amount
        from
            discount_type_economic_complement dtec
        where
            tpg.id = dtec.economic_complement_id
            and dtec.discount_type_id = 4;

        update
            tmp_planilla_general
        set
            total_complement = tmp_planilla_general.total + (
                tmp_planilla_general.amortizacion_prestamos + tmp_planilla_general.amortizacion_reposicion + tmp_planilla_general.amortizacion_retencion_segun_juzgado + tmp_planilla_general.amortizacion_auxilio + tmp_planilla_general.amortizacion_cuentasxcobrar
            )
        where
            1 = 1;

        update
            tmp_planilla_general
        set
            observacion = 'Beneficiario sin observacion'
        where
            id not in (
                select
                    observable_id
                from
                    observables
                where
                    observable_type = 'economic_complements'
                    and deleted_at is null
            );

        update
            tmp_planilla_general tpg
        set
            observacion = qs.shortened,
            estado_observacion = qs.estado_observacon
        from
            (
                select
                    o.observable_id,
                    ot.shortened,
                    case
                        when (o.enabled = true) then 'Subsanado'
                        else 'No subsanado'
                    end estado_observacon
                from
                    observables o
                    inner join observation_types ot on o.observation_type_id = ot.id
                where
                    o.observable_type = 'economic_complements'
                    and o.deleted_at is null
                    and observable_id in (
                        select
                            observable_id
                        from
                            observables o
                            inner join observation_types ot on o.observation_type_id = ot.id
                        where
                            o.observable_type = 'economic_complements'
                            and deleted_at is null
                        group by
                            observable_id
                        having
                            count(observable_id) = 1
                    )
            ) as qs
        where
            tpg.id = qs.observable_id;

        update
            tmp_planilla_general tpg
        set
            observacion = 'Multiples Observaciones',
            estado_observacion = 'Subsanado'
        where
            tpg.id in (
                select
                    distinct o.observable_id
                from
                    observables o
                    inner join observation_types ot on o.observation_type_id = ot.id
                where
                    o.observable_type = 'economic_complements'
                    and o.deleted_at is null
                    and observable_id in (
                        select
                            observable_id
                        from
                            observables o
                            inner join observation_types ot on o.observation_type_id = ot.id
                        where
                            o.observable_type = 'economic_complements'
                            and deleted_at is null
                        group by
                            observable_id
                        having
                            count(observable_id) > 1
                    )
                    and o.enabled = true
            );

        update
            tmp_planilla_general tpg
        set
            observacion = 'Multiples Observaciones',
            estado_observacion = 'No subsanado'
        where
            tpg.id in (
                select
                    distinct o.observable_id
                from
                    observables o
                    inner join observation_types ot on o.observation_type_id = ot.id
                where
                    o.observable_type = 'economic_complements'
                    and o.deleted_at is null
                    and observable_id in (
                        select
                            observable_id
                        from
                            observables o
                            inner join observation_types ot on o.observation_type_id = ot.id
                        where
                            o.observable_type = 'economic_complements'
                            and deleted_at is null
                        group by
                            observable_id
                        having
                            count(observable_id) > 1
                    )
                    and o.enabled = false
            );

        return QUERY
        select
            tpg.nro,
            concat(tpg.observacion, tpg.viudedad) as observacion,
            tpg.estado_observacion,
            tpg.affiliate_id,
            tpg.code,
            tpg.identity_card,
            tpg.first_name,
            tpg.second_name,
            tpg.last_name,
            tpg.mothers_last_name,
            tpg.surname_husband,
            tpg.regional,
            tpg.tipo_prestamo,
            tpg.tipo_recepcion,
            cat.name as categoria,
            d.name as grado,
            pe.name as ente_gestor,
            tpg.total_rent,
            tpg.total_rent_calc,
            tpg.seniority,
            tpg.salary_reference,
            tpg.salary_quotable,
            tpg.difference,
            tpg.total_amount_semester,
            tpg.complementary_factor,
            tpg.total_complement,
            tpg.amortizacion_prestamos,
            tpg.amortizacion_reposicion,
            tpg.amortizacion_retencion_segun_juzgado,
            tpg.amortizacion_auxilio,
            tpg.amortizacion_cuentasxcobrar,
            tpg.total,
            tpg.ubicacion,
            tpg.tipo_beneficiario,
            tpg.estado,
            a.sigep_status,
            a.account_number,
            fe.name financialentities,
            tpg.eco_com_state_id
        from
            tmp_planilla_general tpg
            inner join categories cat on tpg.category_id = cat.id
            inner join degrees d on tpg.degree_id = d.id
            inner join affiliates a on tpg.affiliate_id = a.id
            inner join pension_entities pe on a.pension_entity_id = pe.id
            left join financial_entities fe on a.financial_entity_id = fe.id
        order by
            affiliate_id;

        drop table tmp_planilla_general;

        END;

        \$function\$;
        ");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::unprepared('DROP FUNCTION IF EXISTS public.planilla_general(bigint)');
  }
}
